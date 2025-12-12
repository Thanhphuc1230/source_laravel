@extends('admin.master')
@section('module', 'Dashboard')
@section('action', 'Analytics')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <a href="{{ route('admin.product.index') }}" class="text-decoration-none">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Số lượng Sản phẩm</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +{{ $totalProducts }}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalProducts) }}</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-package text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-6">
                <a href="{{ route('admin.news.index') }}" class="text-decoration-none">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Số bài viết</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalNews) }}</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="bx bx-news text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-6">
                <a href="{{ route('admin.comment.index') }}" class="text-decoration-none">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Số comment</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalComments) }}</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-comment text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts and Widgets -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Số Lượng Truy Cập</h4>
                    </div>
                    <div class="card-body">
                        <div id="visitsChart" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Top Sản Phẩm Bán Chạy</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $product['name'] }}
                                <span class="badge bg-primary rounded-pill">{{ $product['sold'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Đơn Hàng Gần Đây</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Khách Hàng</th>
                                        <th scope="col">Tổng Tiền</th>
                                        <th scope="col">Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ number_format($order->total) }} VND</td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Người Dùng Mới</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Ngày Đăng Ký</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                    <tr>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chartData = @json(json_decode($chartData, true));
    Highcharts.chart('visitsChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: chartData.map(item => item.date)
        },
        yAxis: {
            title: {
                text: 'Số Lượng Truy Cập'
            }
        },
        series: [{
            name: 'Truy Cập',
            data: chartData.map(item => item.count),
            color: '#007bff'
        }],
        credits: {
            enabled: false
        }
    });
</script>
@endsection