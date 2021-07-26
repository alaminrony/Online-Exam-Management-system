@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>@lang('label.STAFF_TREND_ANALYSIS')
            </div>
            @if(!empty($request->generate) && $request->generate == 'true')
            @if(!empty($finalArr))
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="{{URL::to('staffTrendAnalysis?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&employee_id='.Request::get('employee_id').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="{{URL::to('staffTrendAnalysis?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&employee_id='.Request::get('employee_id').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('staffTrendAnalysis?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&employee_id='.Request::get('employee_id').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            @endif
            @endif
        </div>

        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'staffTrendAnalysis/generate','class' => 'form-horizontal')) !!}
            {!! Form::hidden('generate','true') !!}

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="employeeId">@lang('label.EMPLOYEE'):<span class="required"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('employee_id',$employeeArr,Request::get('employee_id'),['class' => 'form-control js-source-states','id'=>'employeeId']) !!}
                            <span class="text-danger">{{$errors->first('employee_id')}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE'):</label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            {!! Form::text('from_date',Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!}
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="fromDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{$errors->first('from_date')}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE'):</label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            {!! Form::text('to_date',Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']) !!}
                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="toDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger">{{$errors->first('to_date')}}</span> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center">
                    <div>
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
                @if(!empty($request->generate) && $request->generate == 'true')
                @if(!empty($finalArr))
                <div class="col-md-6">
                    <div class="pull-right">
                        <button  type="button" class="btn btn-md green btn-outline filter-submit margin-bottom-20" id="graphShow" data-id="1">
                            <i class="fa fa-line-chart"></i><span class="left-margin">@lang('label.GRAPHICAL_VIEW')</span>
                        </button>
                    </div>
                </div>
                @endif
                @endif
            </div>

            {!! Form::close() !!}
            <!-- End Filter -->
            @if(!empty($request->generate) && $request->generate == 'true')
             <div class="bg-blue-hoki bg-font-blue-hoki">
                <h5 style="padding: 10px;">
                    @lang('label.EMPLOYEE') : <strong>{{!empty($employeeArr[$request->employee_id]) ? $employeeArr[$request->employee_id] : 'N/A'}} |</strong>
                    {{__('label.EXAM_DATE')}} : <strong>{{!empty($request->from_date) ? $request->from_date : 'N/A'}} |</strong>
                    {{__('label.TO_DATE')}} : <strong>{{!empty($request->to_date) ? $request->to_date : 'N/A'}} </strong>
                </h5>
            </div>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.EXAM')</th>
                            <th>@lang('label.EXAM_DATE')</th>
                            <th>@lang('label.ACHIEVED_MARK')</th>
                            <th>{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
                            <th>{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($finalArr))
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($finalArr as $result)
                        <?php
                        ?>
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $result['exam_name'] }}</td>
                            <td>{{ $result['exam_date'] }}</td>
                            <td>{{ Helper::numberformat($result['achieved_mark'])}}</td>
                            <td>{{ Helper::numberformat($result['achieved_mark_per'],2) }}%</td>
                            <td>{{ Helper::findGrade($result['achieved_mark_per']) }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_DATA_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </div>	

        <div id="graphicalView" style="display: none">
            <div id="chart">

            </div>  
        </div>
    </div>
</div>

<script src="{{asset('public/js/apexchart.js')}}"></script>
<script src="{{asset('public/js/ohlc.js')}}"></script>
<script>
        $(document).ready(function () {
$('.datepicker').datepicker({
format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        });
        $(document).on('click', '#graphShow', function(){
var status = $(this).attr('data-id');
        if (status == '1'){
$(this).attr('data-id', '2');
        $(this).find("i.fa").toggleClass("fa-line-chart").toggleClass("fa-table");
        $(this).find('span').text('Tabuler View')
        $('#graphicalView').show('slow');
        $('#tableData').hide();
        $('#groupIcon').hide();
        } else{
$(this).attr('data-id', '1');
        $(this).find("i.fa").toggleClass("fa-table").toggleClass("fa-line-chart");
        $(this).find('span').text('Graphical View')
        $('#tableData').show('slow');
        $('#graphicalView').hide();
        $('#groupIcon').show('slow');
        }
});
        });
        var options = {
        series: [
        {
        name: "@lang('label.ACHIEVED_MARKS')",
                data: [
<?php
if (!empty($finalArr)) {
    foreach ($finalArr as $result) {
        ?>
                        "{{ Helper::numberformat($result['achieved_mark_per'])}}",
        <?php
    }
}
?>
                ]
        },
        ],
                chart: {
                height: 350,
                        type: 'line',
                        dropShadow: {
                        enabled: true,
                                color: '#000',
                                top: 18,
                                left: 7,
                                blur: 10,
                                opacity: 0.2
                        },
                        toolbar: {
                        show: true,
                                tools: {
                                download: true,
                                        selection: true,
                                        zoom: false,
                                        zoomin: true,
                                        zoomout: true,
                                        pan: false,
                                        reset: false
                                },
                        }
                },
                colors: ['#77B6EA', '#545454'],
                dataLabels: {
                enabled: true,
                },
                stroke: {
                curve: 'straight'
                },
                grid: {
                borderColor: '#e7e7e7',
                        row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                        },
                },
                markers: {
                size: 1
                },
                xaxis: {
                categories: [
<?php
if (!empty($finalArr)) {
    foreach ($finalArr as $result) {
        ?>
                        "{{ $result['exam_name'] }}",
        <?php
    }
}
?>
                ],
                        title: {
                        text: "@lang('label.EXAM')",
                        }
                },
                yaxis: {
                labels: {
                formatter: function (value) {
                return value + "%";
                }
                },
                        title: {
                        text: "@lang('label.ACHIEVED_MARKS') (%)",
                        },
                        min:5,
                        max: 100,
                },
                tooltip: {
                y: {
                formatter: function(val) {
                return  val + "%"
                },
                }
                }
        }; var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script>
@stop