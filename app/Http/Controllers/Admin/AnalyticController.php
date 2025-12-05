<?php

namespace App\Http\Controllers\Admin;

use App\Models\Analytic;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Dashboard Stats
        $data['totalProducts'] = Product::count();
        $data['totalOrders'] = DB::table('tp_order_status')->count();
        $data['totalRevenue'] = DB::table('tp_order_status')->sum('total');
        $data['totalUsers'] = User::count();

        // Top Products (sold quantity)
        $data['topProducts'] = DB::table('tp_order_product')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'name' => $product ? $product->name_vn : 'Unknown',
                    'sold' => $item->total_sold,
                ];
            });

        // Recent Orders
        $data['recentOrders'] = DB::table('tp_order_status')
            ->join('tp_order_shipping', 'tp_order_status.shipping_id', '=', 'tp_order_shipping.id_order_shipping')
            ->select('tp_order_status.*', DB::raw('CONCAT(tp_order_shipping.f_name_order, " ", tp_order_shipping.l_name_order) as name'), 'tp_order_shipping.phone')
            ->orderBy('tp_order_status.created_at', 'desc')
            ->limit(5)
            ->get();

        // User Activity (recent registrations)
        $data['recentUsers'] = User::orderBy('created_at', 'desc')->limit(5)->get();

        // Revenue Chart (monthly for current year)
        $revenueData = DB::table('tp_order_status')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as revenue'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $revenueChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueChart[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'revenue' => $revenueData->get($m)->revenue ?? 0,
            ];
        }
        $data['revenueChart'] = json_encode($revenueChart);

        return $this->view_admin('dashboard', $data);
    }
}
