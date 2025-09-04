<?php

namespace App\Http\Controllers\Admin;

use App\Models\Analytic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AnalyticController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    public function __construct()
    {
        $this->module = 'analytics';
        $this->model = new Analytic;
        $this->nameItem = 'Biểu đồ';

        parent::__construct($this->module);

        View::share('nameClass', 'chart');
    }

    public function index(Request $request)
    {
        // Get current date (may be overridden by request month/year)
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Allow overriding month/year via query params (for AJAX requests)
        if ($request->has('month') && $request->has('year')) {
            $currentMonth = intval($request->get('month'));
            $currentYear = intval($request->get('year'));
            // set a new "now" based on requested month/year for week calculations if needed
            $now = Carbon::createFromDate($currentYear, $currentMonth, 1);
        }

        // Tổng lượt truy cập tất cả
        $totalViews = $this->model::sum('visit_count');

        // Tổng lượt truy cập cho month/year hiện tại (có thể là tháng được yêu cầu)
        $monthViews = $this->model::whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->sum('visit_count');

        // Tổng lượt truy cập tháng trước
        $lastMonth = $now->copy()->subMonth();
        $lastMonthViews = $this->model::whereMonth('visit_date', $lastMonth->month)
            ->whereYear('visit_date', $lastMonth->year)
            ->sum('visit_count');

        // Tổng lượt truy cập tuần này (relative to $now)
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $weekViews = $this->model::whereBetween('visit_date', [$weekStart, $weekEnd])
            ->sum('visit_count');

        // Dữ liệu cho biểu đồ cho month/year hiện tại
        $visits = $this->model::whereMonth('visit_date', $currentMonth)
            ->whereYear('visit_date', $currentYear)
            ->orderBy('visit_date')
            ->get();
        $chartData = [];
        foreach ($visits as $visit) {
            $visitDate = Carbon::parse($visit->visit_date);
            $chartData[] = [
                'date' => $visitDate->format('d-m-Y'),
                'count' => $visit->visit_count,
            ];
        }
        $data['chartData'] = json_encode($chartData);
        // If this is an AJAX request (or explicitly requested), return JSON so the frontend can update the chart
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'chartData' => $chartData,
                'monthViews' => $monthViews,
                'month' => $currentMonth,
                'year' => $currentYear,
            ]);
        }
        $data['totalViews'] = $totalViews;
        $data['monthViews'] = $monthViews;
        $data['lastMonthViews'] = $lastMonthViews;
        $data['weekViews'] = $weekViews;
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }
}
