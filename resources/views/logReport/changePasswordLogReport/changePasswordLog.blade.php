@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>@lang('label.CHANGE_PASSWORD_REPORT')
            </div>
            @if(!empty($request->generate) && $request->generate == 'true')
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="{{URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="{{URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            @endif
        </div>


        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'changePasswordLog/generate','class' => 'form-horizontal')) !!}
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

                <div class="col-md-4">
                    <div class="text-center">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            @if($request->generate == 'true')
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.DATE')</th>
                            <th>@lang('label.AFFECTED_USER')</th>
                            <th>@lang('label.REFORMING_USER')</th>
                            <th>@lang('label.ACTION_TOKEN')</th>
                            <th>@lang('label.DATE_OF_ACTION')</th>
                            <th>@lang('label.OPERATING_SYSTEM')</th>
                            <th>@lang('label.BROWSER')</th>
                            <th>@lang('label.IP_ADDRESS')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($targetArr))
                        <?php
                        $sl = 0;
                        ?>
                    @foreach($targetArr as $target)
                    <tr>

                        <td>{{ ++$sl }}</td>
                        <td>{{Helper::printDate($target['date'])}}</td>
                        <td>{{$userList[$target['affected_user_id']]??''}}</td>
                        <td>{{$userList[$target['reforming_user_id']]??''}}</td>
                        <td>{{$target['action']}}</td>
                        <td>{{Helper::formatDateTime($target['date_time'])}}</td>
                        <td>{{$target['operating_system']}}</td>
                        <td>{{$target['browser']}}</td>
                        <td>{{$target['ip_address']}}</td>

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
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>
@stop