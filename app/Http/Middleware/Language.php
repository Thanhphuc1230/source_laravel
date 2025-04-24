<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem ngôn ngữ đã được thiết lập trong session chưa
        if (!session()->has('locale')) {
            // Nếu chưa, thiết lập ngôn ngữ mặc định là 'vn'
            session()->put('locale', 'vn');
        }

        // Thiết lập ngôn ngữ từ session
        App::setLocale(session()->get('locale'));
        
        return $next($request);
    }
}
