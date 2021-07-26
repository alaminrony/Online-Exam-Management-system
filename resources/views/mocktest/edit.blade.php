@extends('layouts.default.master')
@section('data_count')

@include('layouts.flash')
<!-- END PORTLET-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-graduation-cap"></i>{{__('label.MANAGE_MOCK_TEST')}} </div>
                <div class="tools">
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {{ Form::open(array('role' => 'form', 'url' => '#', 'files'=> true, 'class' => 'form-horizontal', 'id'=>'manageMockTest', 'method'=> 'post')) }}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{__('label.SELECT_EPE')}} :<span class="required"> *</span></label>
                                <div class="col-md-8">
                                    {{ Form::hidden('epe_id', ($mockTestObjArr) ? $mockTestObjArr->epe_id : null, array('id' => 'epeIdHidden')) }}
                                    {{Form::select('epe_id', $epeList, ($mockTestObjArr) ? $mockTestObjArr->epe_id : null, array('class' => 'form-control js-source-states', 'id' => 'epeId','disabled' => 'true'))}}

                                    <span class="help-block text-danger">{{ $errors->first('epe_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{__('label.SUBJECT_NAME')}} :<span class="required"> *</span></label>
                                <div class="col-md-8">
                                    {{ Form::text('subject_title', ($mockTestObjArr) ? $mockTestObjArr->subject_title : null, array('id'=> 'epeSubjectName', 'class' => 'form-control', 'readonly' => true)) }}
                                    <span class="help-block text-danger"> {{ $errors->first('subject_title') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{__('label.START_DATE_TIME')}} :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group date mocktest_datetime">
                                        {{ Form::text('start_at', ($mockTestObjArr) ? $mockTestObjArr->start_at : null, array('id'=> 'startDateTime', 'class' => 'form-control', 'placeholder' => 'Enter Start Date Time', 'size' => '16', 'readonly' => true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Start Date Time Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('startDateTime');" remove="startDateTime"></i></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">{{__('label.END_DATE_TIME')}} :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group date mocktest_datetime">
                                        {{ Form::text('end_at', ($mockTestObjArr) ? $mockTestObjArr->end_at : null, array('id'=> 'mockTestEndDateTime', 'class' => 'form-control', 'placeholder' => 'Enter End Date Time', 'size' => '16', 'readonly' => true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="End Date Time Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('mockTestEndDateTime');" remove="mockTestEndDateTime"></i></span>
                                </div>
                            </div>
                            <div id="showMockTest">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{__('label.TITLE')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::text('title', ($mockTestObjArr) ? $mockTestObjArr->title : null, array('id'=> 'mockTestTitle', 'class' => 'form-control', 'placeholder' => 'Enter Mock Test Title')) }}
                                        <span class="help-block text-danger"> {{ $errors->first('title') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{__('label.DURATION')}} :<span class="required"> *</span></label>
                                    <div class="col-md-2">
                                        {{Form::select('duration_hours', $hoursList, ($mockTestObjArr) ? $mockTestObjArr->duration_hours : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationHours'))}}
                                    </div>
                                    <label class="col-md-1 control-label durations">{{__('label.HOURS')}}</label>
                                    <div class="col-md-2">
                                        {{Form::select('duration_minutes', $minutesList, ($mockTestObjArr) ? $mockTestObjArr->duration_minutes : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationMinutes'))}}
                                    </div>
                                    <label class="col-md-1 control-label durations">{{__('label.MINUTES')}}</label>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{__('label.TOTAL_NUMBER_OF_QUESTIONS')}} :<span class="required"> *</span></label>
                                    <div class="col-md-8">
                                        {{ Form::number('obj_no_question', ($mockTestObjArr) ? $mockTestObjArr->obj_no_question : null, array('id'=> 'mockTestObjNoQuestion', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control', 'placeholder' => 'Enter Total Number of Questions', 'required' => 'true')) }}
                                        <span class="help-block">{{ $objectiveQuestionCount }} {{__('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK')}}</span>
                                        {{ Form::hidden('total_objective_questions', $objectiveQuestionCount, array('id' => 'total_objective_questions')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{__('label.QUESTION_AUTO_SELECTION')}} :<span class="required">*</span></label>
                                    <div class="col-md-8">
                                        <div class="md-checkbox">
                                            <input type="checkbox" name="obj_auto_selected" class="checkboxes" id="obj_auto_selected" value="1" />
                                            <label for="obj_auto_selected">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> {{__('label.RESHUFFLE_AUTO_QUESTION')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{__('label.STATUS')}} :</label>
                                    <div class="col-md-8">
                                        {{Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), ($mockTestObjArr) ? $mockTestObjArr->status : 1, array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))}}
                                        <span class="help-block text-danger">{{ $errors->first('status') }}</span>
                                        <?php
                                        $id = ($mockTestObjArr) ? $mockTestObjArr->id : null;
                                        ?>
                                        {{ Form::hidden('id', $id, array('id' => 'idMockTest')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green" id="mockTestSubmit"><i class="fa fa-save"></i> {{__('label.SAVE')}}</button>
                            <a href="{{URL::to('mock_test')}}">
                                <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> {{__('label.CANCEL')}}</button> 
                            </a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
</div>
<!-- END CONTENT BODY -->

<script type="text/javascript">
    $(document).ready(function () {

        $("#mockTestSubjectId").change(function () {
            var courseId = $("#mockTestCourseId").val();
            var partId = $("#mockTestPartId").val();
            var subjectId = $(this).val();
            if (subjectId != 0) {
                $.ajax({
                    url: "{{ URL::to('mock_test/show_mock_test_info') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {subject_id: subjectId},
                    success: function (response) {
                        $('#showMockTest').html(response.html);
                        $('.mockTest_datetime').datetimepicker({
                            format: "yyyy-mm-dd hh:ii",
                            autoclose: true,
                            isRTL: App.isRTL(),
                            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
                        });

                        //Ending ajax loader

                        App.unblockUI();

                    },
                    beforeSend: function () {
                        $('#showMockTest').empty();
                        //For ajax loader
                        App.blockUI({
                            boxed: true
                        });
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        $('#showMockTest').empty();
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
            } else {
                $('#showMockTest').empty();
            }

        });

        //This function use for save EPE information
        $("#manageMockTest").submit(function (event) {
            var mockTestData = new FormData($('#manageMockTest')[0]);
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
                                url: "{{ URL::to('mock_test/manage') }}",
                                type: "POST",
                                data: mockTestData,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: true,
                                success: function (response) {
                                    toastr.success(response.data, "Success", {"closeButton": true});

                                    //Ending ajax loader
                                    App.unblockUI();

                                    setTimeout(function () {
                                        $("#mockTestSubmit").prop("disabled", false);
                                        window.location.href = "{{ URL::to('mock_test') }}";
                                    }, 3000);

                                },
                                beforeSend: function () {
                                    $("#mockTestSubmit").prop("disabled", true);
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
                                    $("#mockTestSubmit").prop("disabled", false);
                                    //Ending ajax loader
                                    App.unblockUI();
                                }
                            });
                        } else {
                            event.preventDefault();
                        }

                    });


        });

        $(document).on("keyup", '#mockTestObjNoQuestion', function (event) {
            var noOfQuestion = parseInt($(this).val());
            var totalObjectiveQuestion = parseInt($('#total_objective_questions').val());
            if (noOfQuestion > totalObjectiveQuestion) {
                alert(totalObjectiveQuestion + ' Questions Avaiable At Question Bank');
                $(this).val("");
                return false;
            }
        });

        $('.mocktest_datetime').datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            isRTL: App.isRTL(),
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
        });

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

