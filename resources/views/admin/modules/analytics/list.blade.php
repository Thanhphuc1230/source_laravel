@extends('admin.master')
@section('module', 'Biểu đồ')
@section('action', 'truy cập')
@section('content')
<div class="page-content">
    <div class="container-fluid">
     
        <!-- start page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Quản lý thông kê truy cập</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <!-- Thống kê tổng quan -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Tổng lượt truy cập</h5>
                                        <p class="card-text" style="font-size: 1.5em; font-weight: bold;">{{ number_format($totalViews) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Tháng này</h5>
                                        <p class="card-text" style="font-size: 1.5em; font-weight: bold;">{{ number_format($monthViews) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Tháng trước</h5>
                                        <p class="card-text" style="font-size: 1.5em; font-weight: bold;">{{ number_format($lastMonthViews) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Tuần này</h5>
                                        <p class="card-text" style="font-size: 1.5em; font-weight: bold;">{{ number_format($weekViews) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kết thúc thống kê tổng quan -->
                        <div class="d-flex justify-content-end mb-2 align-items-center">
                            <label for="monthSelect" class="mr-2 mb-0">Chọn tháng:</label>
                            <select id="monthSelect" class="form-control form-control-sm mr-2" style="width:140px;">
                                <option value="1">Tháng 1</option>
                                <option value="2">Tháng 2</option>
                                <option value="3">Tháng 3</option>
                                <option value="4">Tháng 4</option>
                                <option value="5">Tháng 5</option>
                                <option value="6">Tháng 6</option>
                                <option value="7">Tháng 7</option>
                                <option value="8">Tháng 8</option>
                                <option value="9">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                            <select id="yearSelect" class="form-control form-control-sm mr-2" style="width:100px;"></select>
                            <button id="viewMonthBtn" class="btn btn-primary btn-sm" style="margin-left:6px;">Xem</button>
                            <button id="prevMonthBtn" class="btn btn-outline-primary btn-sm" style="margin-left:6px;">Tháng trước</button>
                            <button id="currentMonthBtn" class="btn btn-outline-secondary btn-sm" style="margin-left:6px;">Tháng hiện tại</button>
                        </div>
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end col -->
        </div>

    </div>
    <!-- container-fluid -->
</div>
<script>
    // Tạo một đối tượng Date để lấy tháng và năm hiện tại
    var currentDate = new Date();
    // Lấy tháng hiện tại (từ 0 - 11)
    var currentMonth = currentDate.getMonth() + 1; // +1 vì tháng bắt đầu từ 0
    // Lấy năm hiện tại
    var currentYear = currentDate.getFullYear();
    // Lấy dữ liệu ban đầu từ PHP và chuyển đổi thành mảng JavaScript
    var chartData = <?php echo $chartData; ?>;
    // Tên tháng tiếng Việt
    var monthNamesVN = ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];

    // Format dữ liệu cho biểu đồ
    var seriesData = chartData.map(function(item) {
        return {
            name: item.date,
            y: item.count
        };
    });

    // Render the chart
    var chart = Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Thông kê lượt truy cập trong tháng ' + currentMonth + '/' + currentYear
        },
        xAxis: {
            type: 'category',
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Lượt truy cập'
            }
        },
        series: [{
            name: 'Lượt truy cập',
            data: seriesData,
            colorByPoint: true
        }],
    
    });

    // Function to update chart with new data and update title (month in number)
    function updateChartWithData(newData, month, year) {
        var newSeries = newData.map(function(item) {
            return { name: item.date, y: item.count };
        });
        // Update series
        chart.series[0].setData(newSeries, true);
        // Update title with Vietnamese month name
        var monthLabel = monthNamesVN[month - 1] + ' ' + year;
        chart.setTitle({ text: 'Thông kê lượt truy cập trong ' + monthLabel });
        // Set selects to reflect current month/year
        var ms = document.getElementById('monthSelect');
        var ys = document.getElementById('yearSelect');
        if (ms) ms.value = month;
        if (ys) ys.value = year;
    }

    // Fetch data for a given month/year via AJAX
    function fetchMonthData(month, year) {
        var url = window.location.pathname + '?month=' + month + '&year=' + year + '&ajax=1';
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(response) { return response.json(); })
        .then(function(json) {
            if (json.chartData) {
                updateChartWithData(json.chartData, json.month, json.year);
            }
        })
        .catch(function(err) { console.error('Error fetching month data', err); });
    }

    // Button handlers
    document.getElementById('prevMonthBtn').addEventListener('click', function() {
        var prev = new Date(currentYear, currentMonth - 2, 1); // month-2 because JS months are 0-based
        var m = prev.getMonth() + 1;
        var y = prev.getFullYear();
        fetchMonthData(m, y);
    });
    document.getElementById('currentMonthBtn').addEventListener('click', function() {
        fetchMonthData(currentMonth, currentYear);
    });
    // Populate year select (range currentYear-5 .. currentYear+1)
    var ys = document.getElementById('yearSelect');
    if (ys) {
        for (var y = currentYear - 5; y <= currentYear + 1; y++) {
            var opt = document.createElement('option');
            opt.value = y; opt.text = y;
            ys.appendChild(opt);
        }
        ys.value = currentYear;
    }
    // Set default month select
    var ms = document.getElementById('monthSelect');
    if (ms) ms.value = currentMonth;

    // View selected month button
    document.getElementById('viewMonthBtn').addEventListener('click', function() {
        var m = parseInt(document.getElementById('monthSelect').value, 10);
        var y = parseInt(document.getElementById('yearSelect').value, 10);
        fetchMonthData(m, y);
    });
</script>
@endsection