<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Kiểm tra auth
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('getLogin');
        }

        $user = auth()->user();

        // Kiểm tra hasPermission với multiple permissions (OR logic)
        if (!$user->hasPermission($permissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn không có quyền thực hiện hành động này.'
                ], 403);
            }
            
            toast('Bạn không có quyền thực hiện hành động này.', 'error');
            return redirect()->back()->with('error', 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
