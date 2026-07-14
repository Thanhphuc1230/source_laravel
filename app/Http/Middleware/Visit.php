<?php

namespace App\Http\Middleware;

use App\Models\Analytic;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Visit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') || $request->expectsJson()) {
            return $next($request);
        }

        $visitorId = $request->cookie('site_visit_id');
        $shouldQueueCookie = $visitorId === null;

        if ($visitorId !== null) {
            $visitorKey = hash('sha256', $visitorId);
        } else {
            $visitorKey = hash('sha256', implode('|', [
                $request->ip() ?? '',
                $request->userAgent() ?? '',
            ]));
            $visitorId = (string) Str::uuid();
        }

        $cacheKey = 'visit_tracked_'.$visitorKey;

        if (Cache::add($cacheKey, true, now()->addMinutes(30))) {
            $currentDate = now()->toDateString();

            Cache::lock('visit_counter_'.$currentDate, 5)->block(3, function () use ($currentDate) {
                $visit = Analytic::query()
                    ->whereDate('visit_date', $currentDate)
                    ->first();

                if ($visit) {
                    $visit->increment('visit_count');
                    return;
                }

                Analytic::query()->create([
                    'visit_date' => $currentDate,
                    'visit_count' => 1,
                ]);
            });
        }

        $response = $next($request);

        if ($shouldQueueCookie) {
            Cookie::queue('site_visit_id', $visitorId, 60 * 24 * 30);
        }

        return $response;
    }
}