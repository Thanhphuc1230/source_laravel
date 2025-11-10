<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Mail\EmailVerificationOtp;
use App\Models\User;
use App\Services\RateLimitService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('user.profile.show');
        }

        return view('frontend.modules.auth.login');
    }

    public function postLogin(LoginUserRequest $request)
    {
        $ip = $request->ip();

        $rateLimit = RateLimitService::for('user_login', config('auth.rate_limit.user_max_attempts', 5), config('auth.rate_limit.user_decay_minutes', 15));

        if ($rateLimit->isBlocked($ip)) {
            return back()->with(['error' => $rateLimit->getErrorMessage()]);
        }

    $rawPhone = $request->input('phone');
    // normalize to the canonical +84... format used at registration
    $phone = $this->normalizePhone($rawPhone);

        // generate candidate phone variants to match different stored formats
        $digitsOnly = preg_replace('/\D/', '', $rawPhone);
        $last9 = substr($digitsOnly, -9);
        $candidates = array_filter([
            $phone,                // normalized +84...
            '+84'.$last9,          // +84xxxxxxxxx
            '84'.$last9,           // 84xxxxxxxxx
            '0'.$last9,            // 0xxxxxxxxx
            $digitsOnly,           // digits only
        ]);
        $candidates = array_values(array_unique($candidates));

    // candidates generated to try matching DB values

        $user = User::whereIn('phone', $candidates)->first();

        if (! $user) {
            $rateLimit->incrementAttempts($ip);
            return back()->with(['error' => 'Tài khoản không tồn tại'])->withInput();
        }

        if (! $user->email_verified_at) {
            $rateLimit->incrementAttempts($ip);
            return back()->with(['error' => 'Vui lòng xác thực email'])->withInput();
        }

        // use the stored phone value for authentication (ensures correct format)
        $credentials = [
            'phone' => $user->phone,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $rateLimit->clearAttempts($ip);
            $request->session()->regenerate();
            return redirect()->route('web.home')->with('success', 'Đăng nhập thành công.');
        }

        $rateLimit->incrementAttempts($ip);
        return back()->with(['error' => 'Số điện thoại hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.profile.show');
        }

        return view('frontend.modules.auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->only(['fullname', 'username', 'email', 'phone', 'password']);
        // normalize phone to +84 format before saving
        $data['phone'] = $this->normalizePhone($data['phone']);
        $data['password'] = Hash::make($data['password']);
        $data['uuid'] = (string) \Illuminate\Support\Str::uuid();
        // create user but without verification fields yet
        $user = User::create($data);

        // generate OTP for email verification
        $otp = random_int(100000, 999999);
        $user->email_verification_token = bcrypt($otp);
        $user->email_verification_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // queue email
        try {
            Mail::to($user->email)->queue(new EmailVerificationOtp((string)$otp));
        } catch (\Throwable $e) {
            Log::error('Failed to queue verification email: '.$e->getMessage());
        }

        // Redirect to email verification form so user can enter OTP
        return redirect()->route('email.verify.form')->with(['email' => $user->email, 'success' => 'Đăng ký thành công. Vui lòng nhập mã OTP đã gửi tới email để xác thực.']);
    }

    private function normalizePhone(string $phone): string
    {
        $p = preg_replace('/[^0-9\+]/', '', $phone);
        if (str_starts_with($p, '0')) {
            return '+84'.substr($p, 1);
        }
        if (str_starts_with($p, '+84')) {
            return $p;
        }
        // fallback: return as-is
        return $p;
    }
}
