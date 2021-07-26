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
                    <div id="sixMonthExamineeStat"></div>
                </div>
                <div class="col-md-6">
                    <div id="examScheduleChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!--Data count-->
            <div class="count-style margin-top-15">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="dot bg-white rounded dot-border-1 margin-bottom-10">
                                    <span class="bold text-center" data-counter="counterup" data-value="{!! !empty($subjectList) ? count($subjectList) : 0 !!}">{!! !empty($subjectList) ? count($subjectList) : 0 !!}</span>
                                </div><br>
                                <span class="bold">@lang('label.SUBJECTS')</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="text-align:center">
                                <div class="dot bg-white rounded dot-border-2 margin-bottom-10">
                                    <span class="bold text-center" data-counter="counterup" data-value="{!! !empty($questionList) ? count($questionList) : 0 !!}">{!! !empty($questionList) ? count($questionList) : 0 !!}</span>
                                </div><br>
                                <span class="bold">@lang('label.QUESTIONS')</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="text-align:center">
                                <div class="dot bg-white rounded dot-border-3 margin-bottom-10">
                                    <span class="bold text-center" data-counter="counterup" data-value="{!! !empty($branchList) ? count($branchList) : 0 !!}">{!! !empty($branchList) ? count($branchList) : 0 !!}</span>
                                </div><br>
                                <span class="bold">@lang('label.BRANCHES')</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="text-align:center">
                                <div class="dot bg-white rounded dot-border-4 margin-bottom-10">
                                    <span class="bold text-center" data-counter="counterup" data-value="{!! !empty($clusterList) ? count($clusterList) : 0 !!}">{!! !empty($clusterList) ? count($clusterList) : 0 !!}</span>
                                </div><br>
                                <span class="bold">@lang('label.CLUSTERS')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Data count div-->

        <div class="col-md-12">
            <div class="row margin-top-15">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-dark todays-exam-details tooltips" href="#modalTodaysExamDetails" id="todaysExamDetails" data-toggle="modal" title="@lang('label.CLICK_TO_VIEW_TODAYS_EXAM_DETAILS')">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <span data-counter="counterup" data-value="{!! !empty($todaysExam) ? count($todaysExam) : 0 !!}">{!! !empty($todaysExam) ? count($todaysExam) : 0 !!}</span>
                            <i class="icon-tag"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.TODAYS_EXAMS')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 blue-soft" href="{!! URL::to('/epe') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.EPE_EXAM') </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 green-sharp" href="{!! URL::to('/question') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="icon-note"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.QUESTION_BANK') </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat dashboard-stat-v2 purple" href="{!! URL::to('/examtostudent') !!}">
                        <div class="visual col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <i class="icon-equalizer"></i>
                        </div>
                        <div class="details col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="desc"> @lang('label.ASSIGN_EXAM_TO_EMPLOYEE') </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal start -->

<!--todays exam details modal-->
<div class="modal fade" id="modalTodaysExamDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showTodaysExamDetails">
        </div>
    </div>
</div>

<script src="{{asset('public/js/apexchart.js')}}"></script>
<script src="{{asset('public/js/ohlc.js')}}"></script>
<script>
            $(function () {
                $(document).on("click", ".todays-exam-details", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ URL::to('dashboard/getTodaysExamDetails')}}",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {},
                        beforeSend: function () {
                            $("#showTodaysExamDetails").html('');
                            App.blockUI({
                                boxed: true
                            });
                        },
                        success: function (res) {
                            $("#showTodaysExamDetails").html(res.html);
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            App.unblockUI();
                        }
                    }); //ajax
                });

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

                //six month examinee stat
                var colors = ["#F2784B", "#8E44AD", "#525E64"];
                var options = {
                    series: [{
                            name: "@lang('label.TOTAL_NO_OF_ENROLLED_STUDENTS')",
                            data: [
<?php
if (!empty($sixMonthsEnrolledStudentList)) {
    foreach ($sixMonthsEnrolledStudentList as $month => $cEnrolled) {
        echo $cEnrolled . ',';
    }
}
?>
                            ]
                        }, {
                            name: "@lang('label.TOTAL_NO_OF_STUDENTS_ATTENDED_EXAMS')",
                            data: [
<?php
if (!empty($sixMonthsAttendedStudentList)) {
    foreach ($sixMonthsAttendedStudentList as $month => $cAttended) {
        echo $cAttended . ',';
    }
}
?>
                            ]
                        }, {
                            name: "@lang('label.TOTAL_NO_OF_ABSENT_STUDENTS_EXAMS')",
                            data: [
<?php
if (!empty($sixMonthsAbsentStudentList)) {
    foreach ($sixMonthsAbsentStudentList as $month => $cAbsent) {
        echo $cAbsent . ',';
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
                        text: "@lang('label.EXAMINEE_STATISTICS_LAST_6_MONTHS')",
                        align: 'center'
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: [
<?php
if (!empty($monthDayFromToday)) {
    foreach ($monthDayFromToday as $month) {
        echo "'$month',";
    }
}
?>
                        ],
                        title: {
                            text: "@lang('label.MONTHS')",
                        }
                    },
                    yaxis: {
                        title: {
                            text: "@lang('label.NO_OF_STUDENTS')",
                        },
                    }
                };

                var chart = new ApexCharts(document.querySelector("#sixMonthExamineeStat"), options);
                chart.render();
            });

</script>
@endsection