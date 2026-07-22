<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Models\User;
use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private RateLimitService $rateLimitService;

    public function __construct()
    {
        // Use factory method for login-specific rate limiting
        $this->rateLimitService = RateLimitService::forLogin(
            maxAttempts: config('auth.rate_limit.max_attempts', 5),
            decayMinutes: config('auth.rate_limit.decay_minutes', 15)
        );
    }

    public function getLogin()
    {
        $user = Auth::user();
        if ($user && in_array($user->level, [1, 2, 3, 4])) {
            return redirect()->route('admin.analytics.index');
        }
        return view('auth.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $email = strtolower($request->username ?? '');
        $throttleKey = $email . '|' . $request->ip();

        // Check rate limiting
        if ($this->rateLimitService->isBlocked($throttleKey)) {
            return back()->with(['error' => $this->rateLimitService->getErrorMessage($throttleKey)])->withInput();
        }

        // Validate user exists and verified
        $user = $this->findUser($request->username);
        if (! $user) {
            return $this->handleFailedLogin($throttleKey, 'Tài khoản này không tồn tại');
        }

        if (! $this->isUserVerified($user)) {
            return $this->handleFailedLogin($throttleKey, 'Vui lòng xác thực email');
        }

        // Attempt authentication
        if ($this->attemptLogin($request)) {
            return $this->handleSuccessfulLogin($request, $throttleKey);
        }

        return $this->handleFailedLogin($throttleKey, 'Mật khẩu không đúng. Vui lòng nhập lại');
    }

    /**
     * Find user by email or username
     */
    private function findUser(string $identifier): ?User
    {
        return User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();
    }

    /**
     * Check if user is verified
     */
    private function isUserVerified(User $user): bool
    {
        return $user->email_verified_at !== null;
    }

    /**
     * Attempt user login
     */
    private function attemptLogin(LoginRequest $request): bool
    {
        $credentials = [
            'password' => $request->password,
        ];

        // Try login with email first
        $credentials['email'] = $request->username;
        if (Auth::attempt($credentials)) {
            return true;
        }

        // If email login failed, try with username
        unset($credentials['email']);
        $credentials['username'] = $request->username;
        return Auth::attempt($credentials);
    }

    /**
     * Handle successful login
     */
    private function handleSuccessfulLogin(LoginRequest $request, string $throttleKey)
    {
        $this->rateLimitService->clearAttempts($throttleKey);

        $redirectRoute = $this->getRedirectRoute();
        $request->session()->regenerate();

        return redirect()->route($redirectRoute)->with('success', 'Đăng nhập thành công.');
    }

    /**
     * Handle failed login attempt
     */
    private function handleFailedLogin(string $throttleKey, string $errorMessage)
    {
        $this->rateLimitService->incrementAttempts($throttleKey);

        // Nếu bị block ngay lập tức sau khi tăng số lần thử
        if ($this->rateLimitService->isBlocked($throttleKey)) {
            return back()->with(['error' => $this->rateLimitService->getErrorMessage($throttleKey)])->withInput();
        }

        $remaining = $this->rateLimitService->getRemainingAttempts($throttleKey);

        return back()->with(['error' => $errorMessage . ". Bạn còn {$remaining} lần thử."])->withInput();
    }

    /**
     * Get redirect route based on user level
     */
    private function getRedirectRoute(): string
    {
        $user = Auth::user();

        return in_array($user->level, [1, 2, 3, 4])
            ? 'admin.analytics.index'
            : 'website.home';
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('getLogin');
    }
}
