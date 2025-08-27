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
        // Get the current month
        $currentMonth = Carbon::now()->format('m');

        // Retrieve visits for the current month (optimized with ordering and limit)
        $visits = $this->model::whereMonth('visit_date', $currentMonth)
            ->orderBy('visit_date', 'asc')
            ->limit(31) // Max 31 days in a month
            ->get();
        $chartData = []; // Initialize an array to store data for the chart

        foreach ($visits as $visit) {
            // Convert access_date to Carbon object
            $visitDate = Carbon::parse($visit->visit_date);
            $chartData[] = [
                'date' => $visitDate->format('d-m-Y'),
                'count' => $visit->visit_count,
            ];
        }
        $data['chartData'] = json_encode($chartData);

        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }
}
