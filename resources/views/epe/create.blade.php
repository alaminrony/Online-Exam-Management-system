@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                @if(!empty(Request::get('exam_id')))
                <i class="fa fa-graduation-cap"></i>@lang('label.CLONE_EXAM')
                @else
                <i class="fa fa-graduation-cap"></i>@lang('label.CREATE_EXAM')
                @endif
            </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {{ Form::open(array('role' => 'form', 'url' => '#', 'files'=> true,'class' => 'form-horizontal', 'id'=>'manageEpe', 'method'=> 'post')) }}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="subjectId">@lang('label.SELECT_SUBJECT') :<span class="required"> *</span></label>
                            <div class="col-md-7">
                                {{Form::select('subject_id', $subjectList,!empty($epeObjArr->subject_id) ?$epeObjArr->subject_id : Request::get('subject_id'), array('class' => 'form-control js-source-states', 'id' => 'subjectId'))}}
                                <span class="help-block text-danger">{{ $errors->first('subject_id') }}</span>
                            </div>
                        </div>
                        <div id="showEpe"><!--AJAX CALL FOR TAE-->
                            @if(!empty(Request::get('exam_id')))
                            <?php
                            $examDate = (!empty($epeObjArr->exam_date)) ? $epeObjArr->exam_date : '';
                            $startTime = ($epeObjArr) ? $epeObjArr->start_time : '00:00:00';
                            $endTime = ($epeObjArr) ? $epeObjArr->end_time : '00:00:00';
                            $datetime1 = new DateTime($examDate . ' ' . $startTime);
                            $datetime2 = new DateTime($examDate . ' ' . $endTime);
                            $interval = $datetime1->diff($datetime2);
                            ?>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="examType">@lang('label.EXAM_TYPE') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    {{Form::select('type', $examType,!empty($epeObjArr->type) ?$epeObjArr->type : Request::get('type'), array('class' => 'form-control js-source-states', 'id' => 'examType'))}}
                                    <span class="help-block text-danger">{{ $errors->first('type') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.TITLE') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    {{ Form::text('title', ($epeObjArr) ? $epeObjArr->title : null, array('id'=> 'epeTitle', 'class' => 'form-control', 'placeholder' =>  __('label.EXAM_TITLE'))) }}
                                    <span class="help-block text-danger"> {{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.EXAM_TOTAL_MARK') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    {{ Form::text('total_mark', ($epeObjArr) ? $epeObjArr->total_mark : null, array('id'=> 'totalMark', 'class' => 'form-control integer-only', 'placeholder' => __('label.EXAM_TOTAL_MARK'))) }}
                                    <span class="help-block text-danger"> {{ $errors->first('total_mark') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.EXAM_DATE') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group date datepicker">
                                        {{ Form::text('exam_date', (!empty($epeObjArr->exam_date)) ? $epeObjArr->exam_date : '', array('id'=> 'epeExamDate', 'class' => 'form-control', 'placeholder' => 'Enter Exam Date', 'size' => '16', 'readonly' => true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Exam Date Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('epeExamDate');" remove="examExamDate"></i></span>
                                </div>
                            </div>


                            <div class="form-group epe-time">
                                <label class="control-label col-md-4 col-sm-12">@lang('label.TIME'):</label>
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <div class="input-group">
                                                {{ Form::text('start_time', ($epeObjArr) ? $epeObjArr->start_time : 0, array('id'=> 'startTime', 'class' => 'form-control timepicker epe_exam_time', 'readonly' => true)) }}
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </span>
                                        <span class="input-group-addon">
                                            @lang('label.TO')
                                        </span>
                                        <span class="input-group-addon">
                                            <div class="input-group">
                                                {{ Form::text('end_time', ($epeObjArr) ? $epeObjArr->end_time : null, array('id'=> 'endTime', 'class' => 'form-control timepicker epe_exam_time', 'readonly' => true)) }}
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </span>
                                        <span class="input-group-addon" id="show_epe_exam_duration">{{$interval->format('%H').":".$interval->format('%I')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.RESULT_SUBMISSION_DEADLINE') :</label>
                                <div class="col-md-7">
                                    <div class="input-group date epe_datetime">
                                        {{ Form::text('submission_deadline', ($epeObjArr) ? $epeObjArr->submission_deadline : null, array('id'=> 'resultSubmissionDeadline', 'class' => 'form-control', 'placeholder' => 'Enter Result Submission Deadline', 'size' => '16', 'readonly' => true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Result Submission Deadline Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('resultSubmissionDeadline');" remove="resultSubmissionDeadline"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.RESULT_PUBLISH_DATE_TIME') :</label>
                                <div class="col-md-7">
                                    <div class="input-group date epe_datetime">
                                        {{ Form::text('result_publish', ($epeObjArr) ? $epeObjArr->result_publish : null, array('id'=> 'epeResultPublishedDeadline', 'class' => 'form-control', 'placeholder' => 'Enter Result Publish Date Time', 'size' => '16', 'readonly' => true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Result Publish Date Time Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('epeResultPublishedDeadline');" remove="epeResultPublishedDeadline"></i></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.NO_OF_MOCK_TEST') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    {{ Form::number('no_of_mock',($epeObjArr) ? $epeObjArr->no_of_mock : null, array('id'=> 'epeNoOfMock', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control only-number', 'placeholder' => 'Enter No of Mock Test', 'required' => 'true')) }}
                                </div>
                            </div>
                            <blockquote>
                                <p>@lang('label.QUESTION_SETUP')</p>
                            </blockquote>
                            <div class="form-group epe-duration">
                                <label class="col-md-4 control-label">@lang('label.DURATION') :<span class="required"> *</span></label>
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            {{Form::select('obj_duration_hours', $hoursList, ($epeObjArr) ? $epeObjArr->obj_duration_hours : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationHours'))}}
                                        </span>
                                        <span class="input-group-addon">
                                            @lang('label.HOURS')
                                        </span>
                                        <span class="input-group-addon">
                                            {{Form::select('obj_duration_minutes', $minutesList, ($epeObjArr) ? $epeObjArr->obj_duration_minutes : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationMinutes'))}}
                                        </span>
                                        <span class="input-group-addon" id="epe_exam_time">@lang('label.MINUTES')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.TOTAL_NUMBER_OF_QUESTIONS') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        {{ Form::number('obj_no_question', ($epeObjArr) ? $epeObjArr->obj_no_question : null, array('id'=> 'epeObjNoQuestion', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control', 'placeholder' => 'Enter Total Number of Questions', 'required' => 'true')) }}
                                        <span class="input-group-addon tooltips" id="objective-total-questions" title="{{$objectiveQuestionCount}} @lang('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK')">{{$objectiveQuestionCount}} @lang('label.QUESTIONS_AVAIABLE')</span>
                                        {{ Form::hidden('total_objective_questions', $objectiveQuestionCount, array('id' => 'total_objective_questions')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>@lang('label.SELECT_TYPE_QUESTIONS') :</strong><span class="required">*</span></label>
                                <div class="col-md-7">
                                    <table>
                                        @foreach($qusTypeArr as $typeId=>$typeName)
                                        <?php
                                        $checked = !empty($qusQusTypeDetailList[$typeId]) ? 'checked' : '';
                                        $readonly = !empty($qusQusTypeDetailList[$typeId]) ? '' : 'readonly';
                                        ?>
                                        <tr>
                                            <td width="70%">
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="qus_type[{{$typeId}}]" class="checkboxes qusType" data-type-id='{{$typeId}}' id="qus-type-{{$typeId}}" value="1" {{$checked}}>
                                                    <label for="qus-type-{{$typeId}}">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> {{$typeName}}
                                                    </label> 
                                                </div>
                                            </td>
                                            <td width="2%"> : </td>
                                            <td>
                                                {{ Form::text('qus_type_total['.$typeId.']', (!empty($qusQusTypeDetailList[$typeId])) ? $qusQusTypeDetailList[$typeId]: null, array('id'=> 'qus_type_total_'.$typeId, 'min' => 0, 'maxlength' => 3, 'class' => 'form-control m-b-3 qusTypeTotal integer-only', 'required' => 'true',$readonly)) }}  
                                            </td>

                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="questionnaireFormat">@lang('label.QUESTIONNAIRE_FORMAT') :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    {{Form::select('questionnaire_format', $qusFormatList,($epeObjArr) ? $epeObjArr->questionnaire_format: Request::get('questionnaire_format'), array('class' => 'form-control js-source-states', 'id' => 'questionnaireFormat'))}}
                                    <span class="help-block text-danger">{{ $errors->first('questionnaire_format') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.QUESTION_AUTO_SELECTION') :</label>
                                <div class="col-md-7">
                                    <div class="md-checkbox">
                                        <input type="checkbox" name="obj_auto_selected" class="checkboxes" id="obj_auto_selected" value="1" >
                                        <label for="obj_auto_selected">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            @if(!empty($epeObjArr))
                                            <span class="box"></span> @lang('label.RESHUFFLE_AUTO_QUESTION')
                                            @else
                                            <span class="box"></span> @lang('label.KEEP_BLANK_FOR_MANUAL_SELECTION')
                                            @endif
                                        </label> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="pdf">
                                <label class="col-md-4 control-label">@lang('label.UPLOAD_PDF_FILE') : </label>
                                <div class="col-md-3">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new">@lang('label.BROWSE_PDF')</span>
                                                <span class="fileinput-exists"> Change </span>
                                                {{Form::file('file', array('id' => 'filePdf','accept'=>'application/pdf'))}}
                                            </span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">@lang('label.STATUS') :</label>
                                <div class="col-md-7">
                                    {{Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), ($epeObjArr) ? $epeObjArr->status : 1, array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))}}
                                    <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                                    <?php
                                    $id = ($epeObjArr) ? $epeObjArr->id : null;
                                    ?>

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="#previewEpeModal" id="previewEpe" class="btn btn-primary btn-circle epe-preview bold tooltips" title="@lang('label.CLICK_TO_PREVIEW')" data-toggle="modal" >@lang('label.PREVIEW')</a>
                        <button type="button" class="btn btn-circle green epeSubmit" id="epeSubmit"><i class="fa fa-save"></i> @lang('label.SAVE')</button>
                        <a href="{{URL::to('epe')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> @lang('label.CANCEL')</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            <!-- END FORM-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<div class="modal fade" id="previewEpeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="showPreView">
            <!-- ajax call data -->
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.epe_datetime').datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            isRTL: App.isRTL(),
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
        });
        $(document).on('change', '#subjectId', function (e) {
            var subjectId = $(this).val();
            if (subjectId != 0) {
                $.ajax({
                    url: "{{ URL::to('epe/showEpeInfo') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {subject_id: subjectId},
                    success: function (response) {
                        $('#showEpe').html(response.html);
                        $('.js-source-states').select2();
                        $(".tooltips").tooltip({html: true});
                        $('.epe_datetime').datetimepicker({
                            format: "yyyy-mm-dd hh:ii",
                            autoclose: true,
                            isRTL: App.isRTL(),
                            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
                        });

                        //For datepicker
                        $('.epe_date').datepicker({
                            format: "yyyy-mm-dd",
                            autoclose: true,
                            isRTL: App.isRTL(),
                            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
                        });

                        $('.epe_exam_time').timepicker({
                            defaultTime: '',
                            autoclose: true,
                            minuteStep: 5,
                            showSeconds: false,
                            showMeridian: false
                        });

                        // handle input group button click
                        $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function (e) {
                            e.preventDefault();
                            $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
                        });

                        //Ending ajax loader
                        App.unblockUI();

                    },
                    beforeSend: function () {
                        $('#showEpe').empty();
                        //For ajax loader
                        App.blockUI({
                            boxed: true
                        });
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        $('#showEpe').empty();
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
            } else {
                $('#showEpe').empty();
            }
        });

        $(document).on('click', '#previewEpe', function (e) {
            e.preventDefault();
            var courseId = $("#epeCourseId").val();
            var partId = $("#epePartId").val();
            var subjectId = $("#epeSubjectId").val();
            if (courseId == '') {
                swal('Course must be selected!');
//                $(this).closest('#previewEpeModal').modal('toggle');
                $('#previewEpeModal').modal('hide');
                return false;
            }
            if (partId == '') {
                swal('Part must be selected!');
                $('#previewEpeModal').modal('hide');
                return false;
            }
            if (subjectId == '') {
                swal('Subject must be selected!');
                $('#previewEpeModal').modal('hide');
                return false;
            }
            $('#previewEpeModal').modal('show');
            var formData = new FormData($('#manageEpe')[0]);
            $.ajax({
                url: "{{ URL::to('epe/previewEpe')}}",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                    $("#showPreView").html('');
                },
                success: function (res) {
                    $("#showPreView").html(res.html);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax

        });


        //This function use for save EPE information
        $(document).on('click', '.epeSubmit', function (event) {
            var epeData = new FormData($('#manageEpe')[0]);
            $('#previewEpeModal').modal('hide');
            event.preventDefault();

            swal({
                title: 'Are you sure you want to Save?',
                text: '',
                type: 'warning',
                html: true,
                allowOutsideClick: true,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, I agree',
                cancelButtonText: 'No, I do not agree',
            },
                    function (isConfirm) {
                        if (isConfirm) {
                            toastr.info("Loading...", "Please Wait.", {"closeButton": true});

                            $.ajax({
                                url: "{{ URL::to('epe/manage') }}",
                                type: "POST",
                                data: epeData,
                                dataType: 'json',
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: true,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    toastr.success(response.data, "Success", {"closeButton": true});

                                    //Ending ajax loader
                                    App.unblockUI();
                                    $("#epeSubmit").prop("disabled", false);
                                    window.location.href = "{{ URL::to('epe/') }}";

                                },
                                beforeSend: function () {
                                    $("#epeSubmit").prop("disabled", true);
                                    //For ajax loader
                                    App.blockUI({
                                        boxed: true
                                    });
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
                                    $("#epeSubmit").prop("disabled", false);
                                    //Ending ajax loader
                                    App.unblockUI();
                                }
                            });
                        } else {
                            event.preventDefault();
                            $('#previewEpeModal').modal('show');
                        }
                    });
        });

        $(document).on("keyup", '#epeObjNoQuestion', function (event) {
            var noquestion = parseInt($(this).val());
            //Get total question from question bank
            var totalQuestion = parseInt($("#total_objective_questions").val());
            if (noquestion > totalQuestion) {
                alert(totalQuestion + ' Avaiable Questions');
                $(this).val('');
                return false;
            }
        });

        $(document).on("keyup", '#epeSubNoQuestion', function (event) {
            var noOfQuestion = parseInt($('#epeSubNoQuestion').val());
            var totalSubjectiveQuestion = parseInt($('#total_subjective_question').val());
            if (noOfQuestion > totalSubjectiveQuestion) {
                alert(totalSubjectiveQuestion + ' Questions Avaiable At Question Bank');
                $('#epeSubNoQuestion').val('');
                return false;
            }

            var epeSubNoMandatory = parseInt($('#epeSubNoMandatory').val());
            if (epeSubNoMandatory > noOfQuestion) {
                alert('Mandatory Answer:  Will be smaller than Total Number of Questions');
                $('#epeSubNoMandatory').val('')
                return false;
            }
        });

        $(document).on("keyup", '#epeSubNoMandatory', function (event) {
            var noOfQuestion = parseInt($('#epeSubNoQuestion').val());
            var epeSubNoMandatory = parseInt($('#epeSubNoMandatory').val());
            if (epeSubNoMandatory > noOfQuestion) {
                alert('Mandatory Answer:  Will be smaller than Total Number of Questions');
                $('#epeSubNoMandatory').val('')
                return false;
            }
        });

        $(document).on("change", '#startTime, #endTime', function (event) {
            var startTime = ($('#startTime').val() == '') ? '00:00:00' : $('#startTime').val();
            var endTime = ($('#endTime').val() == '') ? '00:00:00' : $('#endTime').val();
            var startTime = moment(startTime, "HH:mm:ss");
            var endTime = moment(endTime, "HH:mm:ss");
            var duration = moment.duration(endTime.diff(startTime));
            var hours = parseInt(duration.asHours());
            var minutes = parseInt(duration.asMinutes()) - hours * 60;
            var durationHours = (hours <= 9) ? '0' + hours : hours;
            var durationMinutes = (minutes <= 9) ? '0' + minutes : minutes;
            $('span#show_epe_exam_duration').text(durationHours + ':' + durationMinutes);
        });


    });

    jQuery(document).ready(function () {
        ComponentsDateTimePickers.init();
    });

    $(document).ready(function () {
        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });

    function remove_date(e) {
        var id = e;
        $("#" + id).val('');
    }

</script>
<style>
    .date-remove{
        margin-left: -26px;
    }
</style>
@stop

