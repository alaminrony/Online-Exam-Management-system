@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-pencil-square-o"></i>@lang('label.VIEW_EXAM')
            </div>
            <div class="actions">
                <a href="{{ URL::to('epe/create') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> @lang('label.CREATE_EXAM') </a>
            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('role' => 'form', 'url' => 'epe/filter', 'class' => '', 'id' => 'epeFilter')) }}
            {{ Form::hidden('page', Helper::queryPageStr($qpArr))}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">@lang('label.SEARCH_TEXT')</label>
                        {{ Form::text('search_text', Request::get('search_text'), array('id'=> 'epeSearchText', 'class' => 'form-control', 'placeholder' => 'Search by Title/Exam Date')) }}
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label">&nbsp;</label>
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> @lang('label.FILTER')
                    </button>
                </div>
            </div>
            {{Form::close()}}
            <div class="row">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="contain-center">
                                    <th>@lang('label.SL_NO')</th>
                                    <th class="text-center">@lang('label.SUBJECT_INFO')</th>
                                    <th class="text-center">@lang('label.EXAM_TYPE')</th>
                                    <th>@lang('label.TITLE')</th>
                                    <th>@lang('label.EXAM_INFO')</th>
                                    <th class="text-center">@lang('label.NO_OF_MOCK_TEST')</th>
                                    <th class="text-center">@lang('label.QUESTION_SELECTION')</th>
                                    <th class="text-center">@lang('label.STATUS')</th>
                                    <th class='text-center'>@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$targetArr->isEmpty())
                                <?php
                                $page = Request::get('page');
                                $page = empty($page) ? 1 : $page;
                                $sl = ($page - 1) * 10;
                                ?>
                                @foreach($targetArr as $value)
                                <?php
                                $datetime1 = new DateTime($value->exam_date . ' ' . $value->start_time);
                                $datetime2 = new DateTime($value->exam_date . ' ' . $value->end_time);
                                $interval = $datetime1->diff($datetime2);
                                $expiryEpe = (($value->exam_date . ' ' . $value->end_time) < date("Y-m-d H:i:s")) ? 'bg-red-thunderbird bg-font-red' : '';

                                $objExamDuration = ($value->obj_duration_hours * 60) + $value->obj_duration_minutes;
                                $subExamDuration = ($value->sub_duration_hours * 60) + $value->sub_duration_minutes;

                                //Get Total Duration
                                $totalMinutes = $objExamDuration + $subExamDuration;
                                $hours = floor($totalMinutes / 60);
                                $minutes = ($totalMinutes % 60);

                                $durationHours = ($hours > 0) ? ($hours > 1) ? $hours . ' hours ' : $hours . ' hour ' : '';
                                $durationMinutes = ($minutes > 0) ? $minutes . ' minutes ' : '';
                                $totalDurationTime = $durationHours . $durationMinutes;

                                //Get objective duration
                                $objectiveHouese = ($value->obj_duration_hours > 0) ? ($value->obj_duration_hours > 1) ? $value->obj_duration_hours . ' hours ' : $value->obj_duration_hours . ' hour ' : '';
                                $objectiveMinutes = ($value->obj_duration_minutes > 0) ? $value->obj_duration_minutes . ' minutes ' : '';

                                //Get subjective duration
                                $subjectiveHouese = ($value->sub_duration_hours > 0) ? ($value->sub_duration_hours > 1) ? $value->sub_duration_hours . ' hours ' : $value->sub_duration_hours . ' hour ' : '';
                                $subjectiveMinutes = ($value->sub_duration_minutes > 0) ? $value->sub_duration_minutes . ' minutes ' : '';

                                $epeDurationInfo = '';
                                ?>
                                <tr class="{{$expiryEpe}} contain-center">
                                    <td>{{++$sl}}</td>
                                    <td>
                                        <label>@lang('label.SUBJECT') : {!!$value->subject->title .' &raquo; '.$value->subject->code!!}</label><br/>
                                    </td>
                                    <td>
                                        @if($value->type == '1')
                                        <span class="label label-success">{!!__('label.REGULAR')!!}</span>
                                        @else
                                        <span class="label label-warning">{!!__('label.RETAKE')!!}</span>
                                        @endif
                                    </td>
                                    <td>{{$value->title }}</td>
                                    <td>
                                        <label>@lang('label.EXAM_DATE') : {{$value->exam_date }}</label><br/>
                                        <label>@lang('label.START_TIME') : {{substr($value->start_time, 0,-3) }}</label><br/>
                                        <label>@lang('label.END_TIME') : {{substr($value->end_time, 0, -3) }}</label><br/>
                                        <label class="tooltips">@lang('label.DURATION') : {{$totalDurationTime}}</label><br/>
                                    </td>
                                    <td class="text-center">{{ $value->no_of_mock }}</td>
                                    <td class="text-center">
                                        @if ($value->obj_auto_selected == '1')
                                        <span class="label label-primary">@lang('label.AUTO')</span>
                                        @else
                                        <span class="label label-warning">@lang('label.MANUAL')</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($value->status == '1')
                                        <span class="label label-success">@lang('label.ACTIVE')</span>
                                        @else
                                        <span class="label label-warning">@lang('label.INACTIVE')</span>
                                        @endif
                                    </td>

                                    <td class="action-center">
                                        <div class="text-center user-action">
                                            {{ Form::open(array('url' => 'epe/' . $value->id, 'id' => 'delete')) }}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            
                                             
                                            @if(empty($value->epe_submitted))
                                            <a class='btn btn-primary btn-sm tooltips' href="{{ URL::to('epe/' . $value->id . '/edit') }}" title="Edit for {{$value->subject->title}}">
                                                <i class='fa fa-edit'></i>
                                            </a>
                                            <a class='btn btn-primary btn-sm green tooltips' href="{{ URL::to('epe/questionset/' . $value->id) }}"  title="@lang('label.OBJECTIVE_QUESTION_SETS')">
                                                <i class='fa fa-question-circle'></i>
                                            </a>
                                            @endif
                                            
                                            <a class='btn btn-warning btn-sm tooltips' href="{{ URL::to('epe/create?exam_id='. $value->id)}}" title="Clone for {{$value->subject->title}}">
                                                <i class='fa fa-clone'></i>
                                            </a>

                                            <a class="tooltips get-objective-Question" data-toggle="modal" data-target="#view_objective_question" data-id="{{$value->id}}" href="#view_objective_question" id="getObjectiveQuestion{{$value->id}}" title="@lang('label.VIEW_OBJECTIVE_QUESTION')" data-container="body" data-trigger="hover" data-placement="top">
                                                <span class="btn btn-success btn-sm yellow "> 
                                                    &nbsp;<i class='fa fa-question'></i>&nbsp;
                                                </span>
                                            </a>
                                            <!--objective question print-->
                                            <a class="tooltips" href="{{url('epe/question_details?view=print&epe_id='.$value->id)}}" title="@lang('label.PRINT_OBJECTIVE_QUESTION')" target="_blank">
                                                <span class="btn btn-sm yellow-mint"> 
                                                    &nbsp;<i class='fa fa-print'></i>&nbsp;
                                                </span>
                                            </a>  

                                            <a class="tooltips" data-toggle="modal" data-target="#view-modal" data-id="{{$value->id}}" href="#view-modal" id="getepeInfo" title="@lang('label.DETAILS_EPE')" data-container="body" data-trigger="hover" data-placement="top">
                                                <span class="btn btn-warning btn-sm"> 
                                                    &nbsp;<i class='fa fa-info'></i>&nbsp;
                                                </span>
                                            </a>
                                            @if(Auth::user()->group_id <= 3)
                                            <a class='btn btn-primary btn-sm green tooltips' data-toggle="modal" data-target="#view_publish_date" data-id="{{$value->id}}" href="#view_publish_date" id="updatePublish" title="Modify Deadline">
                                                <i class='fa fa-pencil'></i>
                                            </a>
                                            @endif

                                            @if(empty($value->epe_submitted))
                                            <button class="btn btn-danger btn-sm tooltips delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete EPE {{$value->subject->title .' &raquo; '.$value->subject->code}}" title="Delete EPE {{$value->subject->title .' &raquo; '.$value->subject->code}}">
                                                <i class='fa fa-trash'></i>
                                            </button>
                                            @endif
                                            {{ Form::close() }}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="13">@lang('label.EMPTY_DATA')</td>
                                </tr>
                                @endif 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-lg" id="view_objective_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="questionSetShow">
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="view_subjective_question" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header clone-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">@lang('label.VIEW_SUBJECTIVE_QUESTION')</h4>
            </div>
            <div class="modal-body" id="display_subjective_question">  </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="view-modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-content"><!-- mysql data will load in table --></div>
    </div>
</div>

<div id="view_publish_date" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="dynamic-date"><!-- mysql data will load in table --></div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // ****************** Ajax Code for EPE *****************
        $(document).on('click', '.get-objective-Question', function (e) {
            e.preventDefault();
            var epeId = $(this).data('id'); // get id of clicked row

            $.ajax({
                url: "{{ URL::to('epe/questionDetails') }}",
                type: "GET",
                data: {
                    epe_id: epeId
                },
                cache: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#questionSetShow').html(''); // blank before load.
                    $('#questionSetShow').html(response.html); // load here
                    //Ending ajax loader
                    App.unblockUI();
                },
                beforeSend: function () {
                    //For ajax loader
                    App.blockUI({
                        boxed: true
                    });
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 500) {
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

        //This function use for view subjective question
        $(document).on('click', '.get_subjective_question', function (e) {
            e.preventDefault();
            var epeId = $(this).data('id'); // get id of clicked row

            $('#display_subjective_question').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('epe/subjective_question_details/') }}",
                type: "GET",
                data: {
                    epe_id: epeId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#display_subjective_question').html(''); // blank before load.
                    $('#display_subjective_question').html(response.html); // load here
                    //Ending ajax loader
                    App.unblockUI();
                },
                beforeSend: function () {
                    //For ajax loader
                    App.blockUI({
                        boxed: true
                    });
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 500) {
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

        $(document).on('click', '#getepeInfo', function (e) {
            e.preventDefault();
            var epeId = $(this).data('id');
            $('#dynamic-content').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('ajaxresponse/epeInfo') }}",
                type: "GET",
                data: {
                    epe_id: epeId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-content').html(''); // blank before load.
                    $('#dynamic-content').html(response.html); // load here
                    $('.date-picker').datepicker({autoclose: true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error("Error", "Something went wrong, Please try again...", {"closeButton": true});
                }
            });
        });


        $(document).on('click', '#updatePublish', function (e) {
            e.preventDefault();

            var epeId = $(this).data('id'); // get id of clicked row
            $('#dynamic-date').html(''); // leave this div blank
            $.ajax({
                url: "{{ URL::to('epe/update_publish') }}",
                type: "GET",
                data: {
                    id: epeId
                },
                cache: false,
                contentType: false,
                success: function (response) {
                    $('#dynamic-date').html(''); // blank before load.
                    $('#dynamic-date').html(response.html); // load here
                    $('.form_datetime').datetimepicker({autoclose: true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#dynamic-date').html('<i class="fa fa-info-sign"></i> Something went wrong, Please try again...');
                }
            });
        });

        $(document).on('submit', '#updatedPublishResult', function (event) {
            event.preventDefault();
            var updatedPublish = $("#updatedPublishResult").serialize();

            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
            $.ajax({
                url: "{{ URL::to('epe/updated_publish') }}",
                type: "POST",
                data: updatedPublish,
                dataType: "json",
                success: function (response) {
                    toastr.success("Result submission deadline & result publish date updated successfully", "Success", {"closeButton": true});
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);

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
                }
            });
        });
        $(document).ready(function () {
            $(".tooltips").tooltip({html: true});
        });
    });

</script>
@stop
