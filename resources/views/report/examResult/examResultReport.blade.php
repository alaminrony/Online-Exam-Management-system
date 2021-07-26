@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i>@lang('label.EXAM_RESULT')
            </div>
            @if($request->generate == 'true')
            @if (!empty($finalArr))
            <div class="pull-right" style="margin-top: 3px;">
                <a href="{{URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=print')}}"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="{{URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=pdf')}}"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="{{URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=excel')}}"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            @endif
            @endif
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => 'examResultReport/generate','class' => 'form-horizontal')) !!}
            {!! Form::hidden('generate','true') !!}

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="examId">@lang('label.EXAM'):<span class="required"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('exam_id',$examInfoArr ?? '',Request::get('fill_exam_id'),['class' => 'form-control js-source-states', 'id'=>'examId']) !!}
                            <span class="text-danger">{{$errors->first('exam_id')}}</span>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->group_id != 3)
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="employeeId">@lang('label.EMPLOYEE'):</label>
                        <div class="col-md-8">
                            {!! Form::select('employee_id',$employeeArr,Request::get('fill_employee_id'),['class' => 'form-control js-source-states','id'=>'employeeId']) !!}
                            <span class="text-danger">{{$errors->first('employee_id')}}</span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if($request->generate == 'true')
             @if (!empty($finalArr))
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.EMPLOYEE_NAME')</th>
                            <th>@lang('label.OBJECTIVE_MARK')</th>
                            <th>@lang('label.SUBJECTIVE_MARK')</th>
                            <th>@lang('label.ACHIEVED_MARK')</th>
                            <th>{{__('label.ACHIEVED_MARK'). '(%)'}}</th>
                            <th>{{__('label.RESULT')}} {{__('label.STATUS')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($finalArr as $result)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $result['employee_name'] }}({{$result['username']}})</td>
                            <td>{{ Helper::numberformat($result['objective_mark']) }}</td>
                            <td>{{ Helper::numberformat($result['subjective_mark']) }}</td>
                            <td>{{ Helper::numberformat($result['achieved_mark'])}}</td>
                            <td>{{ Helper::numberformat($result['achieved_mark_per']) }}%</td>
                            <td>{{ Helper::findGrade(Helper::numberformat($result['achieved_mark_per'])) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                  @if(Auth::user()->group_id == 3)
                  <h2 class="text-center text-danger"> @lang('label.THE_RESULT_HAVE_NOT_PUBLISHED')</h2>
                  @else
                   <h2 class="text-center text-danger"> @lang('label.NO_DATA_FOUND')</h2>
                   @endif
                @endif
            </div>
            @endif
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '#examId', function (e) {
            var examId = $(this).val();
            $.ajax({
                url: "{{ URL::to('examResultReport/getEmployee') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {exam_id: examId},
                success: function (response) {
                    $('#employeeId').html(response.html);
                    $('.js-source-states').select2();
                    $(".tooltips").tooltip({html: true});
                    App.unblockUI();

                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                    //Ending ajax loader
                    App.unblockUI();
                }
            });
        });
    });
</script>
@stop