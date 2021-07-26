@extends('layouts.default.master')

@section('data_count')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}

</div>
@endif

<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb margin-top-10">
            <li>
                <span>Dashboard</span>
            </li>
        </ul>
        <div class="page-toolbar margin-top-15">
            <h5 class="dashboard-date font-blue-madison"><span class="icon-calendar"></span> Today is <span class="font-blue-madison">{!! date('d F Y') !!}</span> </h5>   
        </div>
    </div>
    <div class="row">
        <marquee onmouseover="this.stop();" onmouseout="this.start();">
            <div class="marquee marquee2">

                <?php
                $str = '';
                ?>
                @foreach($scrollmessageList as $message)
                <?php $str .= '<i class="fa fa-envelope-o"></i> ' . $message->message . ' | '; ?>
                @endforeach
                <?php
                echo trim($str, " | ");
                ?>

            </div>
        </marquee>
    </div>
    <div class="row margin-bottom-20">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="lastSixExamResultChart"></div>
                </div>
                <div class="col-md-6">
                    <div id="examScheduleChart"></div>
                </div>
                <div class="col-md-6">
                    <div id="chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row margin-top-15">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-dark" href="{{url('isspstudentactivity/myepe')}}" id="#">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <span data-counter="counterup" data-value="{!! !empty($todaysExam) ? count($todaysExam) : 0 !!}">{!! !empty($todaysExam) ? count($todaysExam) : 0 !!}</span>
                            <i class="icon-tag"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc text-right">@lang('label.TODAYS_EXAMS')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-soft" href="{{url('isspstudentactivity/mymocktest')}}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <span data-counter="counterup" data-value="{!! !empty($todaysMockTest) ? count($todaysMockTest) : 0 !!}">{!! !empty($todaysMockTest) ? count($todaysMockTest) : 0 !!}</span>
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.TODAYS_MOCK_TESTS') </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 green-sharp" href="{!! URL::to('/examResultReport') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="fa fa-bookmark"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.EXAM_RESULT') </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 purple" href="{!! URL::to('/changePassword/'.Auth::user()->id) !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="icon-key"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.CHANGE_PASSWORD') </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('public/js/apexchart.js')}}"></script>
<script src="{{asset('public/js/ohlc.js')}}"></script>
<script>
$(function () {
    //exam schedule chart
    var options = {
        series: [{
                name: "@lang('label.NO_OF_EXAMS')",
                data: [
<?php
if (!empty($examScheduleList)) {
    foreach ($examScheduleList as $day => $count) {
        echo $count . ',';
    }
}
?>
                ]
            }],
//          annotations: {
//          points: [{
//            x: 'Bananas',
//            seriesIndex: 0,
//            label: {
//              borderColor: '#775DD0',
//              offsetY: 0,
//              style: {
//                color: '#fff',
//                background: '#775DD0',
//              },
//              text: 'Bananas are good',
//            }
//          }]
//        },
        chart: {
            height: 350,
            type: 'bar',
        },
        title: {
            text: "@lang('label.EXAM_SCHEDULE_NEXT_15_DAYS')",
            align: 'center'
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                endingShape: 'rounded',
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            colors: ["#F2784B"]
        },

        grid: {
            row: {
                colors: ['#fff', '#f2f2f2']
            }
        },
        xaxis: {
            labels: {
                rotate: -45,
            },
            categories: [
<?php
if (!empty($monthFromToday)) {
    foreach ($monthFromToday as $day) {
        $date = date("d M y", strtotime($day));
        echo "'$date',";
    }
}
?>
            ],
//            tickPlacement: 'off',
            title: {
                text: "@lang('label.DATE')",
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.NO_OF_EXAMS')",
            },
        },
        fill: {
            type: 'gradient',
            colors: ["#F2784B"],
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [50, 0, 100]
            },
        }
    };

    var chart = new ApexCharts(document.querySelector("#examScheduleChart"), options);
    chart.render();

    //last 6 exam result
    //var colors = ["#F2784B", "#8E44AD"];
    var colors = ["#8E44AD"];
    var options = {
        series: [{
                name: "@lang('label.ACHEIVED_MARK')",
                data: [
<?php
if (!empty($lastSixExamResultList)) {
    foreach ($lastSixExamResultList as $result) {
        echo $result['achieved_mark_per'] . ',';
    }
} 
?>
                ]
            }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        colors: colors,
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: "@lang('label.EXAM_RESULT_LAST_5_EXAMS')",
            align: 'center'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            labels: {
                rotate: -60,
            },
            categories: [
<?php
if (!empty($lastSixExamResultList)) {
    foreach ($lastSixExamResultList as $result) {
        $exam = $result['exam_name'];
        echo "'$exam',";
    }
}
?>
            ],
            title: {
                text: "@lang('label.EXAM_TITLE')",
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.ACHEIVED_MARK_PERCENT')",
            },
            labels: {
                formatter: function (value) {
                    return value + "%";
                }
            },
        }
    };

    var chart = new ApexCharts(document.querySelector("#lastSixExamResultChart"), options);
    chart.render();
    
    
});

</script>
@endsection