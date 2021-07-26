@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>@lang('label.PARTICIPATION_STATUS')
            </div>
            @if(!empty($request->generate) && $request->generate == 'true')
            @if(!empty($targetArr))
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="{{URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="{{URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('participationStatus?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            @endif
            @endif
        </div>

        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'participationStatus/generate','class' => 'form-horizontal')) !!}
            {!! Form::hidden('generate','true') !!}

            <div class="row">
                <div class="col-md-4">
                    <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE'):<span class="text-danger">*</span></label>
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
                    <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE'):<span class="text-danger">*</span></label>
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
                <div class="col-md-2 text-center">
                    <div>
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!empty($targetArr))
                    <div class="pull-right">
                        <button  type="button" class="btn btn-md green btn-outline filter-submit margin-bottom-20" id="graphShow" data-id="1">
                            <i class="fa fa-line-chart"></i><span class="left-margin">@lang('label.GRAPHICAL_VIEW')</span>
                        </button>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {!! Form::close() !!}
            <!-- End Filter -->
            @if(!empty($request->generate) && $request->generate == 'true')
            <div class="bg-blue-hoki bg-font-blue-hoki">
                <h5 style="padding: 10px;">
                    {{__('label.FROM_DATE')}} : <strong>{{!empty($request->from_date) ? $request->from_date : 'N/A'}} |</strong>
                    {{__('label.TO_DATE')}} : <strong>{{!empty($request->to_date) ? $request->to_date : 'N/A'}} </strong>
                </h5>
            </div>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.EXAM')</th>
                            <th>@lang('label.ENROLL')</th>
                            <th>@lang('label.ATTENDENT')</th>
                            <th>@lang('label.ABSENT')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($targetArr))
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $examId => $result)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $examList[$examId] }}</td>
                            <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="{{__('label.VIEW_ENROLL_STUDENT')}}" data-target="#viewStudentModal" data-id="{{$examId}}" data-type="1">{{ $result['enroll'] }}</a></td>
                            <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="{{__('label.VIEW_ATTENDENT_STUDENT')}}" data-target="#viewStudentModal" data-id="{{$examId}}" data-type="2" >{{ $result['attendend'] }}</a></td>
                            <td><a type="button" class="tooltips studentDetails"  data-toggle="modal" title="@lang('label.VIEW_ABSENT_STUDENT')" data-target="#viewStudentModal" data-id="{{$examId}}" data-type="3" >{{ $result['absent'] }}</a></td>
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


<!--view contact Number Modal -->
<div class="modal fade" id="viewStudentModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="sudentDetailsShow">
        </div>
    </div>
</div>
<!--end view Modal -->


<script src="{{asset('public/js/apexchart.js')}}"></script>
<script src="{{asset('public/js/ohlc.js')}}"></script>
<script>
$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
    $(document).on('click', '#graphShow', function () {
        var status = $(this).attr('data-id');
        if (status == '1') {
            $(this).attr('data-id', '2');
            $(this).find("i.fa").toggleClass("fa-line-chart").toggleClass("fa-table");
            $(this).find('span').text('Tabuler View')
            $('#graphicalView').show('slow');
            $('#tableData').hide();
            $('#groupIcon').hide();
        } else {
            $(this).attr('data-id', '1');
            $(this).find("i.fa").toggleClass("fa-table").toggleClass("fa-line-chart");
            $(this).find('span').text('Graphical View')
            $('#tableData').show('slow');
            $('#graphicalView').hide();
            $('#groupIcon').show('slow');
        }
    });
    
    $(document).on('click','.studentDetails',function(){
        var examId = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(examId != '' && type!=''){
            $.ajax({
                url:"{{url('participationStatus/getEmployeeDetails')}}",
                type:"post",
                dataType:"json",
                data:{exam_id:examId,type:type},
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    $('#sudentDetailsShow').html(data.html);
                }
            });
        }
    });

    var colors = ["#F2784B", "#8E44AD", "#525E64"];
    var options = {
        series: [{
                name: "@lang('label.TOTAL_NO_OF_ENROLLED_STUDENTS')",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['enroll'] . ',';
    }
}
?>
                ]
            }, {
                name: "@lang('label.TOTAL_NO_OF_STUDENTS_ATTENDED_EXAMS')",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['attendend'] . ',';
    }
}
?>
                ]
            }, {
                name: "@lang('label.TOTAL_NO_OF_ABSENT_STUDENTS_EXAMS')",
                data: [
<?php
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo $result['absent'] . ',';
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
            text: "@lang('label.PARTICIPATION_STATUS_FOR_ALL_EXAM')",
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
if (!empty($targetArr)) {
    foreach ($targetArr as $examId => $result) {
        echo "'$examList[$examId]',";
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
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});

</script>
@stop