<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $minLevel = 1): Response
    {
        // Kiểm tra user đã đăng nhập
        if (!auth()->check()) {
            return redirect()->route('getLogin');
        }

        $user = auth()->user();
        
        // Kiểm tra level của user
        if ($user->level < $minLevel) {
            // Nếu không đủ quyền, redirect về trang chủ admin với thông báo
            toast('Bạn không có quyền truy cập tính năng này', 'error');
            return redirect()->route('admin.analytics.index')->with('error', 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
