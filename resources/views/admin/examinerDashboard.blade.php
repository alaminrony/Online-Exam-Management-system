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
                    <div id="sixMonthsExamScheduleChart"></div>
                </div>
                <div class="col-md-6">
                    <div id="examScheduleChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row margin-top-15">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-dark" href="#" id="todaysExamDetails" >
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <span data-counter="counterup" data-value="{!! !empty($upcomingWeekAssessmentList) ? count($upcomingWeekAssessmentList) : 0 !!}">{!! !empty($upcomingWeekAssessmentList) ? count($upcomingWeekAssessmentList) : 0 !!}</span>
                            <i class="icon-tag"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc text-right"> @lang('label.UPCOMING_WEEK_ASSESSMENTS')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-soft" href="{!! URL::to('/epedsmarking') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.EPE_MARKING_SUBJECTIVE') </div>
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
                    <a class="dashboard-stat dashboard-stat-v2 purple" href="{!! URL::to('/mockTestReport') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="fa fa-bookmark-o"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.MOCK_TEST_RESULT') </div>
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
            colors: ["#8E44AD"]
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
            colors: ["#8E44AD"],
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

    //six months exam schedule chart
    var options = {
        series: [{
                name: "@lang('label.NO_OF_EXAMS')",
                data: [
<?php
if (!empty($sixMonthsExamScheduleList)) {
    foreach ($sixMonthsExamScheduleList as $month => $count) {
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
            text: "@lang('label.EXAM_SCHEDULE_LAST_6_MONTHS')",
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
            width: 2
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
if (!empty($monthDayFromToday)) {
    foreach ($monthDayFromToday as $month) {
        echo "'$month',";
    }
}
?>
            ],
//            tickPlacement: 'off',
            title: {
                text: "@lang('label.MONTH')",
            }
        },
        yaxis: {
            title: {
                text: "@lang('label.NO_OF_EXAMS')",
            },
        },
        fill: {
            type: 'gradient',
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

    var chart = new ApexCharts(document.querySelector("#sixMonthsExamScheduleChart"), options);
    chart.render();
});

</script>
@endsection