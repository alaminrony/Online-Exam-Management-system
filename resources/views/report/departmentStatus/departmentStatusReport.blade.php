@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>@lang('label.DEPARTMENT_STATUS')
            </div>
            @if(!empty($request->generate) && $request->generate == 'true')
            @if(!empty($targetArr))
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="{{URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="{{URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('departmentStatusReport?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&department_id='.Request::get('department_id').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            @endif
            @endif
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'departmentStatusReport/generate','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="departmentId">@lang('label.DEPARTMENT'):<span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            {!! Form::select('department_id',$departmentList,Request::get('department_id'),['class' => 'form-control js-source-states','id'=>'departmentId']) !!}
                            <span class="text-danger">{{$errors->first('department_id')}}</span>
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
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.GENERATE')
                    </button>
                </div>
                <div class="col-md-6">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!empty($targetArr))
                    <div class="col-md-6 pull-right">
                        <div class="pull-right">
                            <button  type="button" class="btn btn-md green btn-outline filter-submit margin-bottom-20" id="graphShow" data-id="1">
                                <i class="fa fa-line-chart"></i><span class="left-margin">@lang('label.GRAPHICAL_VIEW')</span>
                            </button>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {!! Form::close() !!}

            @if(!empty($request->generate) && $request->generate == 'true')
            <div class="bg-blue-hoki bg-font-blue-hoki">
                <h5 style="padding: 10px;">
                    @lang('label.DEPARTMENT') : <strong>{{!empty($departmentList[$request->department_id]) ? $departmentList[$request->department_id] : 'N/A'}} |</strong>
                    {{__('label.EXAM_DATE')}} : <strong>{{!empty($request->from_date) ? $request->from_date : 'N/A'}} |</strong>
                    {{__('label.TO_DATE')}} : <strong>{{!empty($request->to_date) ? $request->to_date : 'N/A'}} </strong>
                </h5>
            </div>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.EXAM_TITLE')</th>
                            <th>@lang('label.EXAM_DATE')</th>
                            <th>{{__('label.AVERAGE_MARKS'). '(%)'}}</th>
                            <th>{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($targetArr))
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $epeId=>$totalPercentage)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $epeList[$epeId]['title'] }}</td>
                            <td>{{ Helper::dateFormat($epeList[$epeId]['exam_date'])}}</td>
                            <td>{{ Helper::numberformat($totalPercentage,2) }}%</td>
                            <td>{{ Helper::findGrade($totalPercentage) }}</td>
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
            <div id="graphicalView" style="display:none">
                <div id="chart">

                </div> 
            </div>
        </div>
    </div>
</div>
<script src="{{asset('public/js/apexchart.js')}}"></script>
<script src="{{asset('public/js/ohlc.js')}}"></script>
<script>
    $(document).ready(function(){
    $(document).on('click', '#graphShow', function(){
    var status = $(this).attr('data-id');
    if (status == '1'){
    $(this).attr('data-id', '2');
    $(this).find("i.fa").toggleClass("fa-line-chart").toggleClass("fa-table");
    $(this).find('span').text('Tabuler View')
            $('#graphicalView').show('slow');
    $('#tableData').hide();
    } else{
    $(this).attr('data-id', '1');
    $(this).find("i.fa").toggleClass("fa-table").toggleClass("fa-line-chart");
    $(this).find('span').text('Graphical View')
            $('#tableData').show('slow');
    $('#graphicalView').hide();
    }
    });
    });
    var colors = ['#449DD1', '#F86624', '#EA3546', '#662E9B', '#C5D86D'];
    var options = {
    series: [{
    name: "@lang('label.AVERAGE_MARKS')",
            data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $epeId => $totalPercentage) {
        ?>
                    "{{ Helper::numberformat($totalPercentage)}}",
        <?php
    }
}
?>
            ],
    }],
            chart: {
            height: 350,
                    type: 'bar',
            },
            fill: {
            type: 'gradient',
                    gradient: {
                    shade: 'light',
                            type: "horizontal",
                            shadeIntensity: 0.25,
                            gradientToColors: undefined,
                            inverseColors: true,
                            opacityFrom: 0.95,
                            opacityTo: 0.95,
                            stops: [50, 0, 100]
                    },
            },
            colors: colors,
            plotOptions: {
            bar: {
            dataLabels: {
            position: 'top', // top, center, bottom
            },
                    columnWidth: '35%',
                    distributed: true,
                    endingShape: 'rounded'
            }
            },
            dataLabels: {
            enabled: true,
                    formatter: function (val) {
                    return val + '%';
                    },
                    offsetY: - 20,
                    style: {
                    fontSize: '12px',
                            colors: ["#304758"]
                    }
            },
            legend: {
            show: false
            },
            xaxis: {
            categories: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $epeId => $totalPercentage) {
        ?>
                    "{{ $epeList[$epeId]['title'] }}",
        <?php
    }
}
?>
            ],
                    labels: {
                    rotate: - 55,
                            style: {
                            colors: colors,
                                    fontSize: '12px'
                            }
                    },
                    //             tickPlacement: 'off'
            },
            tooltip: {
            y: {
            formatter: function(val) {
            return  val + "%"
            },
            }
            },
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@stop