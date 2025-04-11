<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Analytic;

class Visit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Get client IP address
        $ip = $request->ip();
        
        // Get or initialize session visit count for this IP
        $sessionKey = 'visits_' . str_replace('.', '_', $ip);
        $ipVisitCount = Session::get($sessionKey, 0);
        
        // Check if IP has exceeded max visits per session (20)
        if ($ipVisitCount >= 20) {
            // IP has reached max visits, don't increment analytics
            return $next($request);
        }
        
        // Increment session visit count for this IP
        Session::put($sessionKey, $ipVisitCount + 1);
        
        // Get the current date
        $currentDate = now()->toDateString();
        
        // Tìm record theo ngày
        $visit = Analytic::where('visit_date', $currentDate)->first();
        
        if ($visit) {
            // Nếu đã có thì update +1
            $visit->increment('visit_count');
        } else {
            // Nếu chưa có thì tạo mới với visit_count = 1
            Analytic::create([
                'visit_date' => $currentDate,
                'visit_count' => 1
            ]);
        }
        
        return $next($request);
    }
}