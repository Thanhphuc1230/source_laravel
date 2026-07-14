<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-App-Token');
        $expectedToken = env('API_TOKEN');

        if (!$token || $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Missing or invalid API Token.'
            ], 401);
        }

        return $next($request);
    }
}
