<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i><?php echo app('translator')->get('label.MANAGE_EPE'); ?> </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::open(array('role' => 'form', 'url' => '#', 'files'=> true, 'class' => 'form-horizontal', 'id'=>'manageEpe', 'method'=> 'post'))); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.SELECT_SUBJECT'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::hidden('subject_id', ($epeObjArr) ? $epeObjArr->subject_id : null, array('id' => 'epeSubjectIdHidden'))); ?>

                                <?php echo e(Form::select('subject_id', $subjectList, ($epeObjArr) ? $epeObjArr->subject_id : null, array('class' => 'form-control js-source-states', 'id' => 'epeSubjectId', 'disabled' => 'true'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('subject_id')); ?></span>
                            </div>
                        </div>
                        <div id="showEpe">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="examType"><?php echo app('translator')->get('label.EXAM_TYPE'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <?php echo e(Form::select('type', $examType,!empty($epeObjArr->type) ?$epeObjArr->type : Request::get('type'), array('class' => 'form-control js-source-states', 'id' => 'examType'))); ?>

                                    <span class="help-block text-danger"><?php echo e($errors->first('type')); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.TITLE'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo e(Form::text('title', ($epeObjArr) ? $epeObjArr->title : null, array('id'=> 'epeTitle', 'class' => 'form-control', 'placeholder' => __('label.EXAM_TITLE')))); ?>

                                    <span class="help-block text-danger"> <?php echo e($errors->first('title')); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.EXAM_TOTAL_MARK'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo e(Form::text('total_mark', ($epeObjArr) ? $epeObjArr->total_mark : null, array('id'=> 'totalMark', 'class' => 'form-control integer-only', 'placeholder' => __('label.EXAM_TOTAL_MARK')))); ?>

                                    <span class="help-block text-danger"> <?php echo e($errors->first('total_mark')); ?></span>
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.EXAM_DATE'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group date datepicker">
                                        <?php echo e(Form::text('exam_date', (!empty($epeObjArr->exam_date)) ? $epeObjArr->exam_date : '', array('id'=> 'epeExamDate', 'class' => 'form-control', 'placeholder' => 'Enter Exam Date', 'size' => '16', 'readonly' => true))); ?>

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
                            <?php
                            $examDate = (!empty($epeObjArr->exam_date)) ? $epeObjArr->exam_date : null;
                            $startTime = ($epeObjArr) ? $epeObjArr->start_time : '00:00:00';
                            $endTime = ($epeObjArr) ? $epeObjArr->end_time : '00:00:00';
                            $datetime1 = new DateTime($examDate . ' ' . $startTime);
                            $datetime2 = new DateTime($examDate . ' ' . $endTime);
                            $interval = $datetime1->diff($datetime2);
                            // echo $interval->format('%H').":".$interval->format('%I')
                            ?>
                            <div class="form-group epe-time">
                                <label class="control-label col-md-4 col-sm-12"><?php echo app('translator')->get('label.TIME'); ?>:</label>
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <div class="input-group">
                                                <?php echo e(Form::text('start_time', ($epeObjArr) ? $epeObjArr->start_time : null, array('id'=> 'startTime', 'class' => 'form-control timepicker epe_exam_time', 'readonly' => true))); ?>

                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </span>
                                        <span class="input-group-addon">
                                            <?php echo app('translator')->get('label.TO'); ?>
                                        </span>
                                        <span class="input-group-addon">
                                            <div class="input-group">
                                                <?php echo e(Form::text('end_time', ($epeObjArr) ? $epeObjArr->end_time : null, array('id'=> 'endTime', 'class' => 'form-control timepicker epe_exam_time', 'readonly' => true))); ?>

                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </span>
                                        <span class="input-group-addon" id="show_epe_exam_duration"><?php echo e($interval->format('%H').":".$interval->format('%I')); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.RESULT_SUBMISSION_DEADLINE'); ?> :</label>
                                <div class="col-md-7">
                                    <div class="input-group date epe_datetime">
                                        <?php echo e(Form::text('submission_deadline', ($epeObjArr) ? $epeObjArr->submission_deadline : null, array('id'=> 'resultSubmissionDeadline', 'class' => 'form-control', 'placeholder' => 'Enter Result Submission Deadline', 'size' => '16', 'readonly' => true))); ?>

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
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.RESULT_PUBLISH_DATE_TIME'); ?> :</label>
                                <div class="col-md-7">
                                    <div class="input-group date epe_datetime">
                                        <?php echo e(Form::text('result_publish', ($epeObjArr) ? $epeObjArr->result_publish : null, array('id'=> 'epeResultPublishedDeadline', 'class' => 'form-control', 'placeholder' => 'Enter Result Publish Date Time', 'size' => '16', 'readonly' => true))); ?>

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
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.NO_OF_MOCK_TEST'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <?php echo e(Form::number('no_of_mock', ($epeObjArr) ? $epeObjArr->no_of_mock : null, array('id'=> 'epeNoOfMock', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control only-number', 'placeholder' => 'Enter No of Mock Test', 'required' => 'true'))); ?>

                                </div>
                            </div>
                            <blockquote>
                                <p><?php echo app('translator')->get('label.QUESTION_SETUP'); ?></p>
                            </blockquote>
                            <div class="form-group epe-duration">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.DURATION'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?php echo e(Form::select('obj_duration_hours', $hoursList, ($epeObjArr) ? $epeObjArr->obj_duration_hours : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationHours'))); ?>

                                        </span>
                                        <span class="input-group-addon">
                                            <?php echo app('translator')->get('label.HOURS'); ?>
                                        </span>
                                        <span class="input-group-addon">
                                            <?php echo e(Form::select('obj_duration_minutes', $minutesList, ($epeObjArr) ? $epeObjArr->obj_duration_minutes : null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationMinutes'))); ?>

                                        </span>
                                        <span class="input-group-addon" id="epe_exam_time"><?php echo app('translator')->get('label.MINUTES'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><?php echo app('translator')->get('label.TOTAL_NUMBER_OF_QUESTIONS'); ?> :<span class="required"> *</span></label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <?php echo e(Form::number('obj_no_question', ($epeObjArr) ? $epeObjArr->obj_no_question : null, array('id'=> 'epeObjNoQuestion', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control', 'placeholder' => 'Enter Total Number of Questions', 'required' => 'true'))); ?>

                                        <span class="input-group-addon tooltips" id="objective-total-questions" title="<?php echo e($objectiveQuestionCount); ?> <?php echo app('translator')->get('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK'); ?>"><?php echo e($objectiveQuestionCount); ?> <?php echo app('translator')->get('label.QUESTIONS_AVAIABLE'); ?></span>
                                        <?php echo e(Form::hidden('total_objective_questions', $objectiveQuestionCount, array('id' => 'total_objective_questions'))); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong><?php echo app('translator')->get('label.SELECT_TYPE_QUESTIONS'); ?> :</strong></label>
                                <div class="col-md-7">
                                    <table>
                                        <?php $__currentLoopData = $qusTypeArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeId=>$typeName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $checked = !empty($qusQusTypeDetailList[$typeId]) ? 'checked' : '';
                                        $readonly = !empty($qusQusTypeDetailList[$typeId]) ? '' : 'readonly';
                                        ?>
                                        <tr>
                                            <td width="70%">
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="qus_type[<?php echo e($typeId); ?>]" class="checkboxes qusType" data-type-id='<?php echo e($typeId); ?>' id="qus-type-<?php echo e($typeId); ?>" value="1" <?php echo e($checked); ?>>
                                                    <label for="qus-type-<?php echo e($typeId); ?>">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> <?php echo e($typeName); ?>

                                                    </label> 
                                                </div>
                                            </td>
                                            <td width="2%"> : </td>
                                            <td>
                                                <div class="input-group">
                                                    <?php echo e(Form::text('qus_type_total['.$typeId.']',(!empty($qusQusTypeDetailList[$typeId])) ? $qusQusTypeDetailList[$typeId]: null, array('id'=> 'qus_type_total_'.$typeId, 'min' => 0, 'maxlength' => 3, 'class' => 'form-control m-b-3 qusTypeTotal integer-only type-question', 'required' => 'true',$readonly,'title'=>!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0','data-id'=>$typeId))); ?>  
                                                    <span class="input-group-addon tooltips" id="typeQuestion<?php echo e($typeId); ?>"  data-value="<?php echo e(!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'); ?>" title="<?php echo e(!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'); ?> <?php echo app('translator')->get('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK'); ?>"><?php echo e(!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'); ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="questionnaireFormat"><?php echo app('translator')->get('label.QUESTIONNAIRE_FORMAT'); ?> :<span class="required"> *</span></label>
                                    <div class="col-md-7">
                                        <?php echo e(Form::select('questionnaire_format', $qusFormatList, ($epeObjArr) ? $epeObjArr->questionnaire_format: null, array('class' => 'form-control js-source-states', 'id' => 'questionnaireFormat'))); ?>

                                        <span class="help-block text-danger"><?php echo e($errors->first('questionnaire_format')); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.QUESTION_AUTO_SELECTION'); ?> :<span class="required">*</span></label>
                                    <div class="col-md-7">
                                        <div class="md-checkbox">
                                            <input type="checkbox" name="obj_auto_selected" class="checkboxes" id="obj_auto_selected" value="1" >
                                            <label for="obj_auto_selected">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <?php if(!empty($epeObjArr)): ?>
                                                <span class="box"></span> <?php echo app('translator')->get('label.RESHUFFLE_AUTO_QUESTION'); ?>
                                                <?php else: ?>
                                                <span class="box"></span> <?php echo app('translator')->get('label.KEEP_BLANK_FOR_MANUAL_SELECTION'); ?>
                                                <?php endif; ?>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="pdf">
                                    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.UPLOAD_PDF_FILE'); ?> : </label>
                                    <div class="col-md-3">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new"><?php echo app('translator')->get('label.BROWSE_PDF'); ?></span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    <?php echo e(Form::file('file', array('id' => 'filePdf','accept'=>'application/pdf'))); ?>

                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> <?php echo app('translator')->get('label.REMOVE'); ?> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                                    <div class="col-md-7">
                                        <?php echo e(Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), ($epeObjArr) ? $epeObjArr->status : 1, array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))); ?>

                                        <span class="help-block text-danger"><?php echo e($errors->first('status')); ?></span>
                                        <?php
                                        $id = ($epeObjArr) ? $epeObjArr->id : null;
                                        ?>
                                        <?php echo e(Form::hidden('id', $id, array('id' => 'idEpe'))); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-circle green" id="epeSubmit"><i class="fa fa-save"></i> <?php echo app('translator')->get('label.SAVE'); ?></button>
                            <a href="<?php echo e(URL::to('epe')); ?>">
                                <button type="button" class="btn btn-circle grey-salsa btn-outline"><i class="fa fa-close"></i> <?php echo app('translator')->get('label.CANCEL'); ?></button> 
                            </a>
                        </div>
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".integer-only").each(function () {
            $(this).keypress(function (e) {
                var code = e.charCode;

                if (((code >= 48) && (code <= 57)) || code == 0) {
                    return true;
                } else {
                    return false;
                }
            });
        });

        $(document).on("keyup", '#ciReview', function (event) {
            var ciReview = $("#ciReview").val();
            if (ciReview > 100) {
                swal("CI Review cannot be larger than 100!");
                $("#ciReview").val('');
                return false;
            }

        });
        var totalNumberQus = $("#epeObjNoQuestion").val();

        $(document).on("keyup", '.qusTypeTotal', function (event) {
            var totaltypeQus = 0;
            $(".qusTypeTotal").each(function () {

                totaltypeQus += (isNaN(parseInt($(this).val()))) ? 0 : parseInt($(this).val());
            });
            if (totalNumberQus < totaltypeQus) {
                swal("<?php echo app('translator')->get('label.TYPE_QUESTION_TOTAL_SHOULD_BE_SMALLER_THAN_TOTAL_NUMBER_OF_QUESTION'); ?>");
            }
        });
        $(document).on("click", '.qusType', function (event) {
            var typeId = $(this).attr('data-type-id');
            if (this.checked == false) {
                $("#qus_type_total_" + typeId).attr('readonly', 'readonly');
            } else {
                $("#qus_type_total_" + typeId).removeAttr('readonly');
            }

        })
        /* Show the part*/
        $("#epeCourseId").change(function () {
            $.ajax({
                url: "<?php echo e(URL::to('epe/show_part_list')); ?>",
                type: "GET",
                dataType: "json",
                data: {course_id: $(this).val()},
                success: function (res) {
                    $('select#epePartId').empty();
                    $('select#epePartId').append('<option value="">--Select Part--</option>');
                    $.each(res.parts, function (i, val) {
                        $('select#epePartId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                    $('#showEpe').html('');
                    $('select#epeSubjectId').empty();
                    $('select#epeSubjectId').append('<option value="">--Select Subject--</option>');
                },
                beforeSend: function () {
                    $('select#epePartId').empty();
                    $('select#epePartId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                    $('select#epePartId').empty();
                    $('select#epePartId').append('<option value="">--Select Part--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }

                    $('#showEpe').html('');
                    $('select#epeSubjectId').empty();
                    $('select#epeSubjectId').append('<option value="">--Select Subject--</option>');
                }
            });
        });
        /* Assign Subject to Phase On Submitation*/
        $("#epePartId").change(function () {
            var courseId = $("#epeCourseId").val();
            $.ajax({
                url: "<?php echo e(URL::to('epe/show_subject')); ?>",
                type: "POST",
                data: {course_id: courseId, part_id: $(this).val()},
                dataType: "json",
                success: function (response) {
                    $('#showEpe').html('');
                    $('select#epeSubjectId').empty();
                    $('select#epeSubjectId').append('<option value="">--Select Subject--</option>');
                    $.each(response.subjects, function (i, val) {
                        $('select#epeSubjectId').append('<option value="' + val.id + '">' + val.title + '</option>');
                    });
                },
                beforeSend: function () {
                    $('select#epeSubjectId').empty();
                    $('select#epeSubjectId').append('<option value="">Loading...</option>');
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $('#showEpe').html('');
                    $('select#epeSubjectId').empty();
                    $('select#epeSubjectId').append('<option value="">--Select Subject--</option>');
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                }
            });
        });
        $("#epeSubjectId").change(function () {
            var courseId = $("#epeCourseId").val();
            var partId = $("#epePartId").val();
            var subjectId = $(this).val();
            if (subjectId != 0) {
                $.ajax({
                    url: "<?php echo e(URL::to('epe/show_epe_info')); ?>",
                    type: "POST",
                    data: {course_id: courseId, part_id: partId, subject_id: subjectId},
                    success: function (response) {
                        $('#showEpe').html(response.html);
                        //For datetimepicker
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
                        //For timepicker
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
        //This function use for save EPE information
        $("#manageEpe").submit(function (event) {
            var epeData = new FormData($('#manageEpe')[0]);
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
                                url: "<?php echo e(URL::to('epe/manage')); ?>",
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
                                    //page reload
                                    window.location.href = "<?php echo e(URL::to('epe/')); ?>";
                                    //Ending ajax loader
                                    App.unblockUI();
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
                        }
                    });
        });
        $(document).on("keyup", '#epeObjNoQuestion', function (event) {
            var noquestion = parseInt($(this).val());
            //Get total question from question bank
            var totalQuestion = parseInt($("#total_objective_questions").val());
            if (noquestion > totalQuestion) {
                alert(totalQuestion + ' Questions Avaiable At Question Bank');
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
        //For datetimepicker
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
        //For timepicker
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
        $(document).on("change", '#startTime, #endTime', function (event) {
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
//            if(startTime > endTime){
//                alert("Start Time Should be Smaller than End Time!");
//                return false;
//            }
            var startTime = moment(startTime, "HH:mm:ss");
            var endTime = moment(endTime, "HH:mm:ss");
            var duration = moment.duration(endTime.diff(startTime));
            var hours = parseInt(duration.asHours());
            var minutes = parseInt(duration.asMinutes()) - hours * 60;
            var durationHours = (hours <= 9) ? '0' + hours : hours;
            var durationMinutes = (minutes <= 9) ? '0' + minutes : minutes;
            $('span#show_epe_exam_duration').text(durationHours + ':' + durationMinutes);
        });
        
        $(document).on("keyup", '.type-question', function (event) {
            var questionType = $(this).attr('data-id');
            var inputQuestionNo = parseInt($('#qus_type_total_' + questionType).val());
            var totalTypeQusNo = parseInt($('#typeQuestion' + questionType).attr('data-value'));
            if (totalTypeQusNo < inputQuestionNo) {
                swal("<?php echo app('translator')->get('label.YOUR_NUMBER_IS_LARGER_THEN_TOTAL_TYPE_QUESTION'); ?>");
                 $('#qus_type_total_' + questionType).val('');
                return false;
            }
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/epe/edit.blade.php ENDPATH**/ ?>