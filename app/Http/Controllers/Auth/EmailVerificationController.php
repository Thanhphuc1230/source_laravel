<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\RateLimitService;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationOtp;

class EmailVerificationController extends Controller
{
    public function showVerifyForm(Request $request)
    {
        return view('frontend.modules.auth.verify-email');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $ip = $request->ip();
        $rate = RateLimitService::for('email_resend', 3, 5);
        if ($rate->isBlocked($ip)) {
            return back()->with(['error' => $rate->getErrorMessage()]);
        }

        $user = User::where('email', $request->input('email'))->first();
        if (! $user) {
            return back()->with(['error' => 'Email không tồn tại']);
        }

        // generate new OTP
        $otp = random_int(100000, 999999);
        $user->email_verification_token = bcrypt($otp);
        $user->email_verification_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        try {
            Mail::to($user->email)->queue(new EmailVerificationOtp((string)$otp));
        } catch (\Throwable $e) {
            Log::error('Failed to queue verification email (resend): '.$e->getMessage());
            return back()->with(['error' => 'Không thể gửi mã, vui lòng thử lại sau.']);
        }

        $rate->incrementAttempts($ip);

        return back()->with(['success' => 'Mã OTP đã được gửi lại.']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (! $user) {
            return back()->with(['error' => 'Email không tồn tại'])->withInput();
        }

        if (! $user->email_verification_token || ! $user->email_verification_expires_at) {
            return back()->with(['error' => 'Không có mã xác thực hoặc đã xác thực trước đó']);
        }

        if (Carbon::now()->greaterThan($user->email_verification_expires_at)) {
            return back()->with(['error' => 'Mã xác thực đã hết hạn']);
        }

        if (! Hash::check($request->input('otp'), $user->email_verification_token)) {
            return back()->with(['error' => 'Mã xác thực không đúng']);
        }

        $user->email_verified_at = Carbon::now();
        $user->email_verification_token = null;
        $user->email_verification_expires_at = null;
        $user->save();

        return redirect()->route('login')->with(['success' => 'Email đã được xác thực. Bạn có thể đăng nhập.']);
    }
}
