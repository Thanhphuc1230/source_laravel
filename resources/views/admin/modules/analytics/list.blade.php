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
     // Lấy dữ liệu từ PHP và chuyển đổi thành mảng JavaScript
     var chartData = <?php echo $chartData; ?>;

    // Format dữ liệu cho biểu đồ
    var seriesData = chartData.map(function(item) {
        return {
            name: item.date,
            y: item.count
        };
    });

    // Render the chart
    Highcharts.chart('container', {
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
</script>
@endsection