@extends('admin.master')
@section('module', 'Dashboard')
@section('action', 'Analytics')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Top Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.product.index') }}" class="text-decoration-none">
                    <div class="card card-animate border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted fs-12 mb-0">Sản phẩm</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success-subtle text-success fs-12">
                                        <i class="ri-arrow-up-line align-middle me-1"></i>Hoạt động
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h3 class="fs-22 fw-bold text-dark mb-0">{{ number_format($totalProducts) }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success bg-gradient rounded-3 fs-3 shadow">
                                        <i class="bx bx-package text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.news.index') }}" class="text-decoration-none">
                    <div class="card card-animate border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted fs-12 mb-0">Số bài viết</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-info-subtle text-info fs-12">
                                        <i class="ri-article-line align-middle me-1"></i>Tin tức
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h3 class="fs-22 fw-bold text-dark mb-0">{{ number_format($totalNews) }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info bg-gradient rounded-3 fs-3 shadow">
                                        <i class="bx bx-news text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.comment.index') }}" class="text-decoration-none">
                    <div class="card card-animate border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted fs-12 mb-0">Số comment</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-warning-subtle text-warning fs-12">
                                        <i class="ri-chat-1-line align-middle me-1"></i>Tương tác
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h3 class="fs-22 fw-bold text-dark mb-0">{{ number_format($totalComments) }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning bg-gradient rounded-3 fs-3 shadow">
                                        <i class="bx bx-comment text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-semibold text-muted fs-12 mb-0">Truy Cập</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary-subtle text-primary fs-12">
                                    <i class="ri-eye-line align-middle me-1"></i>Toàn thời gian
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-3">
                            <div>
                                <h3 class="fs-22 fw-bold text-dark mb-0" id="totalViewsDisplay">{{ number_format($totalViews) }}</h3>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary bg-gradient rounded-3 fs-3 shadow">
                                    <i class="bx bx-show text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chart Section (Full Width Hero) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header align-items-center d-flex bg-white py-3 border-bottom border-light">
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-0 fw-bold text-dark"><i class="ri-bar-chart-grouped-line text-primary me-2"></i>Thống Kê Lượt Truy Cập Hệ Thống</h4>
                            <p class="text-muted fs-13 mb-0">Theo dõi lưu lượng truy cập website theo thời gian thực</p>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-2 align-items-center">
                            <!-- Month Select -->
                            <select id="filterMonth" class="form-select form-select-sm border-light bg-light fw-medium" style="width: 110px;">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                            <!-- Year Select -->
                            <select id="filterYear" class="form-select form-select-sm border-light bg-light fw-medium" style="width: 100px;">
                                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Chart Summary Indicators -->
                        <div class="row text-center mb-4 bg-light p-3 rounded-3 g-3">
                            <div class="col-md-4 border-end border-light">
                                <span class="text-muted fs-12 text-uppercase fw-medium">Lượt truy cập tháng này</span>
                                <h4 class="fs-20 fw-bold text-primary mb-0 mt-1" id="monthViewsDisplay">{{ number_format($monthViews) }}</h4>
                            </div>
                            <div class="col-md-4 border-end border-light">
                                <span class="text-muted fs-12 text-uppercase fw-medium">Lượt truy cập tháng trước</span>
                                <h4 class="fs-20 fw-bold text-secondary mb-0 mt-1">{{ number_format($lastMonthViews) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted fs-12 text-uppercase fw-medium">Lượt truy cập tuần này</span>
                                <h4 class="fs-20 fw-bold text-success mb-0 mt-1">{{ number_format($weekViews) }}</h4>
                            </div>
                        </div>

                        <!-- ApexChart Container -->
                        <div id="analyticsApexChart" style="min-height: 420px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawData = @json(json_decode($chartData, true));
        
        let categories = rawData.map(item => item.date);
        let seriesData = rawData.map(item => item.count);

        const options = {
            series: [{
                name: 'Lượt truy cập',
                data: seriesData
            }],
            chart: {
                type: 'area',
                height: 420,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                dropShadow: {
                    enabled: true,
                    color: '#4b38b3',
                    top: 12,
                    left: 0,
                    blur: 6,
                    opacity: 0.15
                }
            },
            colors: ['#4b38b3'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                categories: categories,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#878a99',
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Số lượt',
                    style: {
                        color: '#878a99',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                },
                labels: {
                    style: {
                        colors: '#878a99',
                        fontSize: '12px'
                    },
                    formatter: function (val) {
                        return Math.floor(val);
                    }
                },
                min: 0
            },
            tooltip: {
                theme: 'dark',
                x: {
                    format: 'dd/MM/yyyy'
                },
                y: {
                    formatter: function (val) {
                        return val + " lượt truy cập";
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#analyticsApexChart"), options);
        chart.render();

        // AJAX Filtering handler
        function updateChartData() {
            const month = document.getElementById('filterMonth').value;
            const year = document.getElementById('filterYear').value;

            fetch(`{{ route('admin.analytics.index') }}?month=${month}&year=${year}&ajax=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const newCategories = data.chartData.map(item => item.date);
                const newSeries = data.chartData.map(item => item.count);

                chart.updateOptions({
                    xaxis: {
                        categories: newCategories
                    }
                });
                chart.updateSeries([{
                    name: 'Lượt truy cập',
                    data: newSeries
                }]);

                if(document.getElementById('monthViewsDisplay')) {
                    document.getElementById('monthViewsDisplay').innerText = new Intl.NumberFormat().format(data.monthViews);
                }
            })
            .catch(error => console.error('Error updating chart:', error));
        }

        document.getElementById('filterMonth').addEventListener('change', updateChartData);
        document.getElementById('filterYear').addEventListener('change', updateChartData);
    });
</script>
@endpush