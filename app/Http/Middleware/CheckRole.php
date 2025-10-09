<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kiểm tra auth
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('getLogin');
        }

        $user = auth()->user();

        // Kiểm tra hasRole với multiple roles (OR logic)
        if (!$user->hasRole($roles)) {
            $roleNames = implode(', ', $roles);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Bạn không có quyền truy cập. Yêu cầu vai trò: {$roleNames}"
                ], 403);
            }
            
            toast("Bạn không có quyền truy cập. Yêu cầu vai trò: {$roleNames}", 'error');
            return redirect()->back()->with('error', 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
