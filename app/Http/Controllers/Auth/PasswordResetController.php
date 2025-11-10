<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetOtpMail;

class PasswordResetController extends Controller
{
    // show unified request form (email or phone)
    public function showRequestForm()
    {
        // the 'request' view was removed/renamed; use the phone-specific view that exists
        return view('frontend.modules.auth.forgot-password');
    }

    // detect phone vs email and dispatch
    public function sendResetLinkOrOtp(SendOtpRequest $request)
    {
        $identifier = $request->input('identifier');

        // naive phone detection: starts with +84 or 0 or all digits
        if (preg_match('/^(?:\+84|0)\d{9}$/', preg_replace('/\s+/', '', $identifier))) {
            // phone flow
            return $this->sendOtpByPhone($request);
        }

        // else email -> use normal password reset (laravel) or send link
        // For now, fallback to Laravel's built-in notification (not implemented here)
        return back()->with(['error' => 'Reset bằng email chưa được cấu hình. Vui lòng sử dụng số điện thoại.']);
    }

    // phone-specific flows (moved from previous controller)
    public function sendOtpByPhone(SendOtpRequest $request)
    {
        $phone = $request->input('phone') ?? $request->input('identifier');

        $user = $this->findUserByPhone($phone);

        if (! $user) {
            return back()->with(['error' => 'Số điện thoại không tồn tại'])->withInput();
        }

        $canonicalPhone = $user->phone;
        $otp = random_int(100000, 999999);
        $key = 'password_reset_otp_'.$canonicalPhone;
        Cache::put($key, bcrypt($otp), now()->addMinutes(5));

        try {
            Mail::to($user->email)->queue(new PasswordResetOtpMail((string)$otp));
        } catch (\Throwable $e) {
            Log::error('Failed to queue password reset email: '.$e->getMessage());
            Log::info("[OTP] phone={$canonicalPhone} otp={$otp}");
        }

        $masked = $this->maskEmail($user->email);
        return redirect()->route('password.phone.verify.form')->with(['phone' => $canonicalPhone, 'success' => 'Mã OTP đã được gửi vào email: '.$masked]);
    }

    public function showVerifyForm(Request $request)
    {
        $phone = session('phone') ?? $request->old('phone') ?? $request->query('phone');
        return view('frontend.modules.auth.verify-otp', compact('phone'));
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $phone = $request->input('phone');
        $inputOtp = $request->input('otp');

        $user = $this->findUserByPhone($phone);
        if (! $user) {
            return back()->with(['error' => 'Số điện thoại không tồn tại'])->withInput();
        }

        $canonicalPhone = $user->phone;
        $key = 'password_reset_otp_'.$canonicalPhone;

        $hash = Cache::get($key);
        if (! $hash) {
            return back()->with(['error' => 'OTP đã hết hạn hoặc không tồn tại.']);
        }

        if (! Hash::check($inputOtp, $hash)) {
            return back()->with(['error' => 'OTP không đúng'])->withInput();
        }

        // OTP ok -> allow reset (store flag in cache short-lived)
        Cache::put('password_reset_verified_'.$canonicalPhone, true, now()->addMinutes(10));
        Cache::forget($key);

        return redirect()->route('password.phone.reset.form')->with(['phone' => $canonicalPhone]);
    }

    public function showResetForm(Request $request)
    {
        $phone = session('phone') ?? $request->old('phone') ?? $request->query('phone');
        return view('frontend.modules.auth.reset-password', compact('phone'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $phone = $request->input('phone');
        $user = $this->findUserByPhone($phone);

        if (! $user) {
            return redirect()->route('password.phone.form')->with(['error' => 'Số điện thoại không tồn tại']);
        }

        $canonicalPhone = $user->phone;
        $verified = Cache::get('password_reset_verified_'.$canonicalPhone);
        if (! $verified) {
            return redirect()->route('password.phone.form')->with(['error' => 'Bạn chưa xác thực OTP hoặc OTP đã hết hạn.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        Cache::forget('password_reset_verified_'.$canonicalPhone);

        return redirect()->route('login')->with(['success' => 'Mật khẩu đã được thay đổi.']);
    }

    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $local = $parts[0];
        $domain = $parts[1] ?? '';
        $len = strlen($local);
        if ($len <= 6) {
            $maskedLocal = substr($local,0,1) . str_repeat('*', max(0, $len-2)) . substr($local,-1);
        } else {
            $start = intval(($len - 5) / 2);
            $maskedLocal = substr($local,0,$start) . str_repeat('*',5) . substr($local,$start+5);
        }
        return $maskedLocal.'@'.$domain;
    }

    private function findUserByPhone(string $phone)
    {
        $p = preg_replace('/\s+/', '', $phone);

        $candidates = [$p];

        if (str_starts_with($p, '+84')) {
            $candidates[] = '0'.substr($p, 3);
        } elseif (str_starts_with($p, '0')) {
            $candidates[] = '+84'.substr($p, 1);
        }

        foreach ($candidates as $c) {
            $user = User::where('phone', $c)->first();
            if ($user) return $user;
        }

        return null;
    }
}
