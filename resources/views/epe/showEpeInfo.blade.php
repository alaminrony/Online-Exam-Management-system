<?php
$examDate = (!empty($epeObjArr->exam_date)) ? $epeObjArr->exam_date : '';
$startTime = ($epeObjArr) ? $epeObjArr->start_time : '00:00:00';
$endTime = ($epeObjArr) ? $epeObjArr->end_time : '00:00:00';
$datetime1 = new DateTime($examDate . ' ' . $startTime);
$datetime2 = new DateTime($examDate . ' ' . $endTime);
$interval = $datetime1->diff($datetime2);
// echo $interval->format('%H').":".$interval->format('%I')
?>
<div class="form-group">
    <label class="col-md-4 control-label" for="examType">@lang('label.EXAM_TYPE') :<span class="required"> *</span></label>
    <div class="col-md-7">
        {{Form::select('type',$examType,!empty($epeObjArr->type) ?$epeObjArr->type : Request::get('type'), array('class' => 'form-control js-source-states', 'id' => 'examType'))}}
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
                    {{ Form::text('start_time', ($epeObjArr) ? $epeObjArr->start_time : null, array('id'=> 'startTime', 'class' => 'form-control timepicker epe_exam_time', 'readonly' => true)) }}
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
        {{ Form::number('no_of_mock', ($epeObjArr) ? $epeObjArr->no_of_mock : 0, array('id'=> 'epeNoOfMock', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control only-number', 'placeholder' => 'Enter No of Mock Test', 'required' => 'true')) }}
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
                    <div class="input-group">
                        {{ Form::text('qus_type_total['.$typeId.']',(!empty($qusQusTypeDetailList[$typeId])) ? $qusQusTypeDetailList[$typeId]: null, array('id'=> 'qus_type_total_'.$typeId, 'min' => 0, 'maxlength' => 3, 'class' => 'form-control m-b-3 qusTypeTotal integer-only type-question', 'required' => 'true',$readonly,'title'=>!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0','data-id'=>$typeId)) }}  
                        <span class="input-group-addon tooltips" id="typeQuestion{{$typeId}}"  data-value="{{!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'}}" title="{{!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'}} @lang('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK')">{{!empty($typeWiseQuestionArr[$typeId])?$typeWiseQuestionArr[$typeId] :'0'}}</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label" for="questionnaireFormat">@lang('label.QUESTIONNAIRE_FORMAT') :<span class="required"> *</span></label>
    <div class="col-md-7">
        {{Form::select('questionnaire_format', $qusFormatList, Request::get('questionnaire_format'), array('class' => 'form-control js-source-states', 'id' => 'questionnaireFormat'))}}
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
        {{ Form::hidden('id', $id, array('id' => 'idEpe')) }}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
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
        })

        $(document).on("click", '.qusType', function (event) {
            var typeId = $(this).attr('data-type-id');
            if (this.checked == false) {
                $("#qus_type_total_" + typeId).attr('readonly', 'readonly');
            } else {
                $("#qus_type_total_" + typeId).removeAttr('readonly');
                $("#qus_type_total_" + typeId).val('');
            }

        })
        $(document).on("keyup", '.qusTypeTotal', function (event) {
            var totalNumberQus = $("#epeObjNoQuestion").val();
            var totaltypeQus = 0;
            $(".qusTypeTotal").each(function () {
                totaltypeQus += (isNaN(parseInt($(this).val()))) ? 0 : parseInt($(this).val());
            });
            if (totalNumberQus < totaltypeQus) {
                swal("@lang('label.TYPE_QUESTION_TOTAL_SHOULD_BE_SMALLER_THAN_TOTAL_NUMBER_OF_QUESTION')");
                $(this).val('');
                return false;
            }
        });

        $(document).on("keyup", '.type-question', function (event) {
            var questionType = $(this).attr('data-id');
            var inputQuestionNo = parseInt($('#qus_type_total_' + questionType).val());
            var totalTypeQusNo = parseInt($('#typeQuestion' + questionType).attr('data-value'));
            if (totalTypeQusNo < inputQuestionNo) {
                swal("@lang('label.YOUR_NUMBER_IS_LARGER_THEN_TOTAL_TYPE_QUESTION')");
                $('#qus_type_total_' + questionType).val('');
                return false;
            }
        });

        $("body").tooltip({selector: '[data-tooltip=tooltip]'});
    });
</script>



