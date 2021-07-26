@extends('layouts.epeExam')
@section('data_count')

<div class="page-content">

    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box">

                <div class="portlet-body form">
                    {{ Form::open(array('role' => 'form', 'url' => 'examSubjective', 'class' => 'form-horizontal', 'id'=>'answerScript')) }}

                    {{ Form::hidden('epe_id', $epe->id, array('id'=>'epe_id')) }}
                    {{ Form::hidden('epe_mark_id', $target->id, array('id'=>'epe_mark_id')) }}
                    {{ Form::hidden('max_possible_answer', $epe->sub_no_mandatory, array('id'=>'max_possible_answer')) }}
                    {{ Form::hidden('max_selected_answer', (count($existingDataArr) > 0) ? count($existingDataArr) : 0, array('id'=>'max_selected_answer')) }}
                    {{ Form::hidden('redirect', 0, array('id'=>'redirect')) }}

                    <?php
                    $courseName = $epe->Course->title;
                    $partName = $epe->Part->title;
                    $phaseName = $epe->Phase->full_name;
                    ?>

                    <div class="form-body text-center" id="subjectiveExamHeader">
                        <div id="script-header">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1 confidential">{{ __('label.CONFIDENTIAL') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.EXAM_HEADER') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.ISSP_ABBR').' : '.trim($courseName. ', ') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1 exam-header">{{ trim($phaseName, ', ').'; '.trim($partName, ', ').' : '.$epe->Subject->title. ' ('.__('label.SUBJECTIVE').')' }}</div>
                            </div>
                            <br />
                        </div>
                        <div class="row script-header-sticky">
                            <div class="col-md-4 col-md-offset-1 text-left">
                                {{ __('label.NEED_TO_ANSWER').' <strong>'.$epe->sub_no_mandatory.' </strong>'.__('label.QUESTIONS_OUT_OF').'<strong> '.$epe->sub_no_question.'</strong> sets' }}
                            </div>
                            <div class="col-md-2 text-left">{{ __('label.TOTAL_MARK').' : <strong>'.$target->subjective_mark.'</strong>' }}</div>
                            <div class="col-md-2 col-md-offset-1 text-right"><strong><span class="text-red" id="counter"></span></strong></div>
                        </div>
                    </div>

                    <div class="form-body" id="questionBody">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1" >
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a href="#tab_0" data-toggle="tab" aria-expanded="true" id="question0" >{{ __('label.QUESTION_SET') }}</a>
                                                </li>
                                                @for($i=1; $i<= count($epeSubQusSet); $i++)
                                                <?php
                                                $questionSetStyle = array_key_exists($i, $existingDataArr) ? 'style="background-color:rgb(50, 197, 210);"' : '';
                                                ?>
                                                <li class="">
                                                    <a href="#tab_{{$i}}" data-toggle="tab" aria-expanded="false" id="question{{$i}}" {{$questionSetStyle}}>{{ 'Q'.$i }}</a>
                                                </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button type="submit" class="btn btn-primary tooltips" title="{{ __('label.SUBJECTIVE_SUBMISSION_ALARM_MESSAGE') }}" data-placement="top" id="submitSubjective"><i class="fa fa-save"></i> {{__('label.SUBMIT_SUBJECTIVE_SCRIPT')}}</button>
                                        </div>
                                    </div>

                                    <div class="tab-content">

                                        <div class="tab-pane fade active in" id="tab_0">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if(!empty($epeSubQusSet))
                                                    <?php //echo '<pre>';print_r($epeSubQusSet); echo '</pre>'; ?>
                                                    @foreach($epeSubQusSet as $key => $questionSet)


                                                    <div class="subject_question">
                                                        <div class="bg-font-grey-cararra"><h4>{{'Q'.$key.'.  '.$questionSet['set_title']}}</h4></div>
                                                    </div>


                                                    @if(!empty($questionSetArr[$key]))
                                                    <?php $j = 0; ?>
                                                    @foreach($questionSetArr[$key] as $question)
                                                    <div class="row">
                                                        <div class="col-md-10">

                                                            @if(count($questionSetArr[$key]) > 1)
                                                            {{ chr($j+97).'. ' }}
                                                            @endif

                                                            {{ $question['question'] }}
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            {{$question['mark']}}
                                                        </div>
                                                    </div>

                                                    @if(!empty($question['image']))
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{URL::to('/')}}/question/getImage/{{ $question['image'] }}" data-target="#image-loader" data-toggle="modal">
                                                                <img class="question-script-image-first-tab" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question['image'] }}" alt="{{ $question['image'] }}"> 
                                                            </a>
                                                        </div>
                                                    </div>             

                                                    @endif


                                                    <?php $j++; ?>
                                                    @endforeach

                                                    @endif
                                                    <p style="margin: 0;padding: 0;">&nbsp;</p>
                                                    @endforeach
                                                    @else 
                                                    <h3 class="text-center text-danger">{{__('label.NO_QUESTION_FOUND_FOR_THIS_EPE')}}</h3>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        @for($i=1; $i<= count($epeSubQusSet); $i++)
                                        <?php
                                        $j = 0;

                                        //if subjective question set not exist.
                                        //Initialize an array 
                                        $subQuestionSetArr = array();

                                        $textEditorSet = 'text-readonly';
                                        //This section use for already answer this set
                                        if (array_key_exists($i, $existingDataArr)) {

                                            //This section use for lock question set activity
                                            if (array_key_exists($i, $lockQuestionSetList)) {
                                                $questionSetDisabled = 'disabled="disabled"';
                                            } else {
                                                $questionSetDisabled = '';
                                            }

                                            $questionSetValue = 1;
                                            $questionSetChecked = 'checked="checked"';
                                            $buttonDisabled = '';

                                            $textEditorSet = 'text-normal';
                                        } else {
                                            $questionSetChecked = '';
                                            $buttonDisabled = 'disabled="disabled"';
                                            $questionSetValue = 0;
                                            $questionSetDisabled = '';
                                        }

                                        //This section use for get total answer count for this qptional set 
                                        if (array_key_exists($i, $maxSelectedQuestionAnswerForOptionalSetList)) {
                                            $optionalMaxSelectedQuestionAnswer = $maxSelectedQuestionAnswerForOptionalSetList[$i];
                                        } else {
                                            $optionalMaxSelectedQuestionAnswer = 0;
                                        }
                                        ?>

                                        <div class="tab-pane fade in" id="tab_{{$i}}">

                                            @if(!empty($questionSetArr[$i]))

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="subjective-question">
                                                        @if(array_key_exists($i, $epeSubQusSet))
                                                        <?php $subQuestionSetArr = $epeSubQusSet[$i]; ?>
                                                        @if($subQuestionSetArr['options'])
                                                        {{ Form::hidden('max_possible_question_answer['.$i.']', $subQuestionSetArr['answer'], array('id'=>'max_possible_question_answer_'.$i)) }}
                                                        {{ Form::hidden('max_selected_question_answer['.$i.']', $optionalMaxSelectedQuestionAnswer, array('id'=>'max_selected_question_answer_'.$i)) }}
                                                        {{ Form::hidden('has_option['.$i.']', 1, array('id'=>'has_option_'.$i)) }}
                                                        @else
                                                        {{ Form::hidden('has_option['.$i.']', 0, array('id'=>'has_option_'.$i)) }}
                                                        @endif
                                                        @endif
                                                        <input type="checkbox" id="questionSet_{{$i}}" name="question_set[{{$i}}]" class="question-set-control questionSet_{{$i}}" data-set-id="{{$i}}" data-has-option="{{(!empty($subQuestionSetArr['options'])) ? 1 : 0}}" value="{{$questionSetValue}}" {{$questionSetChecked}} {{$questionSetDisabled}} /> <label for="questionSet_{{$i}}">{{ __('label.I_WILL_ANSWER_THIS_QUESTION')}}</label>
                                                        <input type="hidden" id="questionSet_{{$i}}" name="question_set[{{$i}}]" class="questionSet_{{$i}}" value="{{$questionSetValue}}" />

                                                    </div>
                                                </div>

                                                <!--<div class="col-md-4 text-right">
                                                    <label class="control-label text-danger"><strong>Need to answer {{$subQuestionSetArr['answer']}} out of {{$subQuestionSetArr['no_of_qus']}}</strong></label>
                                                </div>-->

                                            </div>

                                            @if($subQuestionSetArr['options'])
                                            <div class="row">
                                                <div class="col-md-10">{{ $epeSubQusSet[$i]['set_title'] }}</div>
                                                <div class="col-md-2 text-right">
                                                    <!--<strong>{{ __('label.TOTAL_MARK').' : '. Custom::numberFormat($epeSubQusSet[$i]['mark']) }}</strong>
                                                    <strong>{{ $subQuestionSetArr['answer'].' X '.$epeSubQusSet[$i]['mark'].' = '.Custom::numberFormat($subQuestionSetArr['answer']*$epeSubQusSet[$i]['mark']) }}</strong>-->
                                                </div>
                                            </div>
                                            @endif

                                            @foreach($questionSetArr[$i] as $question)

                                            <?php
                                            $questionId = $question['question_id'];
                                            $existingDataArr[$i]['question'] = !empty($existingDataArr[$i]['question']) ? $existingDataArr[$i]['question'] : array();



                                            if (array_key_exists($questionId, $existingDataArr[$i]['question'])) {
                                                $targetArr = $existingDataArr[$i]['question'][$questionId];
                                                $existsAnswer = $targetArr['answer'];
                                                $disabledAnswer = "";
                                                $hasOptionQuestionCheckd = !empty($targetArr['has_options']) ? 'checked="checked"' : '';
                                                if ($targetArr['lock'] == 1) {
                                                    $buttonShowHide = 'style="display:none;"';
                                                    $optionalQuestionDisabled = 'disabled="disabled"';
                                                    $textEditor = 'text-readonly';
                                                } else {
                                                    $buttonShowHide = 'style="display:block;"';
                                                    $optionalQuestionDisabled = '';
                                                    $textEditor = 'text-normal';
                                                }
                                                $specificQuestionDisabled = '';
                                            } else {

                                                $targetArr = array();
                                                $existsAnswer = null;
                                                $disabledAnswer = "disabled";
                                                $hasOptionQuestionCheckd = '';
                                                $buttonShowHide = 'style="display:block;"';

                                                if (array_key_exists($i, $hasOptionSetList)) {
                                                    $optionalQuestionDisabled = '';
                                                    $textEditor = 'text-readonly';
                                                } else {
                                                    $textEditor = $textEditorSet;
                                                    $optionalQuestionDisabled = 'disabled="disabled"';
                                                }
                                                $specificQuestionDisabled = 'disabled="disabled"';
                                            }

                                            $answerChrCount = 4000;
                                            if (!empty($existsAnswer)) {
                                                $answerChrCount = $answerChrCount - strlen(strip_tags($existsAnswer));
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <div class="subjective-question">
                                                        <label class="bold">

                                                            @if($subQuestionSetArr['options'])
                                                            <input type="checkbox" id="optional_question_answer_{{$i}}_{{$question['question_id']}}" name="optional_question_answer[{{$i}}][{{$question['question_id']}}]" class="optional-question-set-control has-option-set-{{$i}}" data-optional-set-id="{{$i}}" data-optional-question-id="{{$question['question_id']}}" value="1" {{$hasOptionQuestionCheckd}} {{$optionalQuestionDisabled}}/>&nbsp;
                                                            <input type="hidden" id="optional_question_answer_org_{{$i}}_{{$question['question_id']}}" name="optional_question_answer[{{$i}}][{{$question['question_id']}}]" value="1" {{$specificQuestionDisabled}} class="has-option-set-original-{{$i}}" />&nbsp;
                                                            @endif

                                                            @if(count($questionSetArr[$i])  > 1)
                                                            {{ chr($j+97).'. ' }}
                                                            @endif

                                                            {{$question['question'] }}

                                                            @if(!empty($question['note']))
                                                            <span class="tooltips  question-node" title="{{$question['note']}}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                                            @endif
                                                        </label>

                                                    </div>
                                                </div>
                                                <div class="col-md-1">

                                                    <div class="subjective-question">{{ $question['mark'] }}</div>

                                                </div>
                                            </div>

                                            @if(!empty($question['image']))
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{URL::to('/')}}/question/getImage/{{ $question['image'] }}" data-target="#image-loader" data-toggle="modal">
                                                        <img class=" question-script-image" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $question['image'] }}" alt="{{ $question['image'] }}"> 
                                                    </a>
                                                </div>
                                            </div>                                            
                                            @endif


                                            <div class="row">
                                                <div class="col-md-12 {{$textEditor}}">
                                                    {{ Form::textarea('answer['.$i.']['.$question['question_id'].']', $existsAnswer, array('id'=> 'answer_'.$i.'_'.$question['question_id'], 'class' => 'form-control text-editor answer-set-'.$i, 'data-question-set' => $i, 'data-question-no' => (count($questionSetArr[$i])  > 1) ? chr($j+97) : '' )) }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-counter text-right">
                                                    Maximum 4000 character; <span id="text-counter-{{'answer_'.$i.'_'.$question['question_id']}}">{{ $answerChrCount }}</span> remaining
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12" id="question_answer_button_{{$i}}_{{$question['question_id']}}" {{$buttonShowHide}}>
                                                    <button type="button" class="btn btn-circle btn-primary individual-question-answer-save answer-set-save-{{$i}}" id="answer_set_save_{{$i}}_{{$question['question_id']}}" data-question-set-id="{{$i}}" data-question-id="{{$question['question_id']}}" {{$buttonDisabled}}><i class="fa fa-save"></i> {{__('label.SAVE')}}</button>
                                                    <!--<button type="button" class="btn btn-circle btn-success individual-question-answer-lock answer-set-lock-{{$i}}" id="answer_set_lock_{{$i}}_{{$question['question_id']}}" data-question-set-id="{{$i}}" data-question-id="{{$question['question_id']}}" {{$buttonDisabled}}><i class="fa fa-lock"></i> {{__('label.SAVE_LOCK')}}</button>-->
                                                </div>
                                            </div>

                                            <?php $j++; ?>
                                            @endforeach
                                            @else
                                            <div class="row">
                                                <div class="col-md-12">

                                                    {{ __('label.NO_QUESTION_ASSIGNED_FOR_THIS_QUESTION_SET') }}

                                                </div>
                                            </div>
                                            @endif

                                        </div>

                                        @endfor

                                    </div>
                                </div>

                            </div>
                        </div>

                        <!--<div class="row">
                            <div class="form-actions">
                                <div class="col-md-12 text-center">
                                    <div class="col-md-12 text-center">                                       
                                        <button type="submit" class="btn btn-primary tooltips" title="{{ __('label.SUBJECTIVE_SUBMISSION_ALARM_MESSAGE') }}" data-placement="top" id="submitSubjective"><i class="fa fa-save"></i> {{__('label.SUBMIT_SUBJECTIVE_SCRIPT')}}</button>
                                        <br />
                                        <span class="text-danger">{{ __('label.SUBJECTIVE_SUBMISSION_ALARM_MESSAGE') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                    </div>



                    {{ Form::close() }}
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="image-loader" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ __('label.PRESS_ESC_TO_CLOSE') }}</h4>
            </div>
            <div class="modal-body text-center"> <img src="{{URL::to('/public/assets/global/img/loading-spinner-grey.gif')}}" alt="" class="loading"> </div>
            <div class="modal-footer">
                <button type="button" class="btn green" data-dismiss="modal">{{ __('label.CLOSE')}}</button>
            </div>
        </div>
    </div>
</div>


<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">{{ __('label.TIME_EXPIRED') }}</h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong>Sorry! Time is over. Your Subjective script will be submitted automatically!</strong></strong></span>
            </div>
            <div class="modal-footer">&nbsp;</div>
        </div>
    </div>
</div>


<div id="5-min-warning" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-danger">{{ __('label.REMINDER') }}</h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong>{{ __('label.REMINDER_TEXT') }}</strong></span>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn green">{{ __('label.OK') }}</button>
            </div>
        </div>
    </div>
</div>


{{ HTML::style('public/assets/global/plugins/bootstrap-summernote/summernote.css'); }}
{{ HTML::script('public/assets/pages/scripts/components-editors.min.js') }}
{{ HTML::script('public/assets/global/plugins/bootstrap-summernote/summernote.min.js') }}

<script type="text/javascript">

    $(document).ready(function () {

        //Tooltip, activated by hover event
        $(".tooltips").tooltip({html: true});

        //Disable cut copy paste
//         $('body').bind('cut copy paste', function (e) {
//             e.preventDefault();
//         });

        //Disable mouse right click
//         $("body").on("contextmenu", function (e) {
//             return false;
//         });


        window.addEventListener("beforeunload", function (e) {

            var confirmationMessage = 'Your quiz progress will lost if you leave this page!';
            var redirect = $("#redirect").val();

            if (redirect == '0') {
                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            }

        });

        //window.onload="toggleFullScreen(document.body)";

        function toggleFullScreen(elem) {
            // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
            if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
                if (elem.requestFullScreen) {
                    elem.requestFullScreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullScreen) {
                    elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPATCH);
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }

        // Set the date we're counting down to
        var countDownDate = new Date("{{ $target->exam_date.' '.$target->subjective_end_time }}").getTime();


        var now;
        var subInitialTime = localStorage.getItem("subInitialTime");
        var getTimeArr = JSON.parse(localStorage.getItem("timeArr"));

        var totalSeconds = 0;
        if (subInitialTime) {

            var totalSeconds = parseInt(getTimeArr.totalSeconds);
            now = parseInt(subInitialTime) + (totalSeconds * 1000);
//            console.log('im resuming')
//            console.log(now);
            //now = 1000000;
        } else {
//            console.log('fot the first time')
            now = new Date("<?php echo date("Y-m-d H:i:s") ?>").getTime();
            localStorage.setItem("subInitialTime", now);
        }

        // Get todays date and time
        //var now = new Date("<?php echo date("Y-m-d H:i:s") ?>").getTime();

// Update the count down every 1 second
        var x = setInterval(function () {

            // Find the distance between now an the count down date
            now += 1000;
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            //var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            totalSeconds++;

            var setTimeArr = {distance: distance, hours: hours, minutes: minutes, seconds: seconds, totalSeconds: totalSeconds};
            localStorage.setItem("timeArr", JSON.stringify(setTimeArr));
            document.cookie = "timeArr=" + JSON.stringify(setTimeArr);

            if (distance > 0) {
                document.getElementById("counter").innerHTML = "Time Remaining : " + hours + ":" + minutes + ":" + seconds;
            }

            if (hours == 0 && minutes == 5 && seconds == 0) {
                $('#5-min-warning').modal('show');
            }


            if (seconds % 30 == 0) {

                $.ajax({
                    url: "{{ URL::to('/') }}",
                    type: "GET",
                    success: function (response) {
                        $('#submitSubjective').attr('disabled', false);
                    },
                    beforeSend: function () {

                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error("You don't have Internet Connection!", "Caution", {"closeButton": true, positionClass: "toast-bottom-right"});
                        $('#submitSubjective').attr('disabled', true);
                    }
                });

            }//if



            // If the count down is finished, write some text 
            if ((distance <= 0) && (seconds % 30 == 0)) {

                document.getElementById("counter").innerHTML = "EXPIRED";
                var data = new FormData($('form')[0]);
                $('#static').modal('show');
                //$('#questionBody').html('<span class="text-danger">Sorry! Time is over. Your answer will be submitted automatically!</span>');

                $.ajax({
                    url: "{{ URL::to('examSubjective') }}",
                    type: "POST",
                    data: data,
                    dataType: 'html',
                    //cache: false,
                    contentType: false,
                    processData: false,
                    //async: true,
                    success: function (response) {
                        clearInterval(x);
                        setTimeout(function () {
                            $('#redirect').val('1');
                            var epeId = $('#epe_id').val();
                            //alert('redirecting');
                            window.location = "{{ URL::to('subjectiveComplete?id=" + epeId + "') }}";
                        }, 3000);
                    },
                    beforeSend: function () {
                        //$("#taeSubmit").prop("disabled", true);
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {

                    }
                });
            }
        }, 1000);


        $('.text-editor').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                //['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            disableDragAndDrop: true,
            height: 150,
            callbacks: {
                onPaste: function (e) {
                    e.preventDefault();
                },
                onKeyup: function (e) {

                    //var totalChr = 4000 - $('#'+this.id).code().replace(/(<([^>]+)>)/ig,"").length;
                    var totalChr = 4000 - $(this).parent().find('.note-editor .note-editable').text().length;
                    $('#text-counter-' + this.id).html(totalChr);
                }
            }
        });

        $('.text-editor').each(function (index) {
            //$(this).summernote('disable');
            $('.text-readonly .note-editor .note-editing-area .note-editable').attr('contenteditable', false)
        });

        $('.question-set-control').click(function (event) {

            var setId = $(this).attr('data-set-id');

            var hasOption = $(this).attr('data-has-option');

            var selectedCount = parseInt($('#max_selected_answer').val());  //Number of checked questioin set
            var maxCount = parseInt($('#max_possible_answer').val());       //Maximum number of set that can be answered

            if ($('#' + this.id).is(':checked')) {

                var newCount = selectedCount + 1;

                if (newCount > maxCount) {
                    event.preventDefault();
                    swal('Maximum number of Question Set is already selected for answer!');
                } else {

                    $('#max_selected_answer').val(newCount);
                    if (hasOption == 1) {
                        $('.has-option-set-' + setId).prop('disabled', false);
                        $('.has-option-set-original-' + setId).prop('disabled', true);
                    } else {
                        $('.answer-set-' + setId).prop('disabled', false);
                        $('.answer-set-' + setId).each(function (index) {
                            $(this).summernote('enable');
                        });

                        //save & lock botton disable false
                        $('.answer-set-save-' + setId).prop('disabled', false);
                        $('.answer-set-lock-' + setId).prop('disabled', false);
                    }
                    //Checkbox value set for question set
                    $('.questionSet_' + setId).val("1");
                    //Question Set background-color set
                    $("a#question" + setId).css('background-color', '#32c5d2');
                    $("a#question" + setId).css("color", "#fff")

                }

            } else {

                swal({
                    title: 'Are you sure you want to uncheck?',
                    text: 'You will loose all your answer for this Question!',
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
                            //event.preventDefault();
                            if (isConfirm) {

                                var newCount = selectedCount - 1;
                                $('#max_selected_answer').val(newCount);

                                //$('.answer-set-' + setId).val('');
                                $('.answer-set-' + setId).prop('disabled', true);
                                $('.answer-set-' + setId).each(function (index) {
                                    $(this).summernote('disable');
                                    //$(this).summernote('reset');
                                });

                                //Question set value set
                                $('.questionSet_' + setId).val("0");
                                $('#max_selected_question_answer_' + setId).val("0");

                                //This optional question has disabled
                                if (hasOption == 1) {
                                    $('.has-option-set-' + setId).prop('disabled', true);
                                    $('.has-option-set-' + setId).prop('checked', false);
                                    $('.has-option-set-original-' + setId).prop('disabled', true);
                                }


                                //save & lock botton disable true
                                $('.answer-set-save-' + setId).prop('disabled', true);
                                $('.answer-set-lock-' + setId).prop('disabled', true);

                                //Question Set background-color set
                                $("a#question" + setId).css("background-color", "#fff")
                                $("a#question" + setId).css("color", "#000")

                                //Delete answer script for this question set
                                //toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                                /*$.ajax({
                                 url: "{{ URL::to('epeExam/deleteQuestionSetAnswers') }}",
                                 type: "POST",
                                 data: {epe_mark_id: $('#epe_mark_id').val(), set_id: setId},
                                 dataType: "json",
                                 success: function (response) {
                                 toastr.success("You've lost all your answers for this Question Set.", "Success", {"closeButton": true});
                                 
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
                                 });*/

                            } else {
                                $('#questionSet_' + setId).prop('checked', true);
                                event.preventDefault();
                            }

                        });

            }//else


        });

        //This function use for optional question set
        $('.optional-question-set-control').click(function (event) {

            var setId = $(this).attr('data-optional-set-id');
            var questionId = $(this).attr('data-optional-question-id');

            var selectedCount = parseInt($('#max_selected_question_answer_' + setId).val());  //Number of checked questioin set
            var maxCount = parseInt($('#max_possible_question_answer_' + setId).val());       //Maximum number of question that can be answered

            if ($('#' + this.id).is(':checked')) {

                var newCount = selectedCount + 1;

                if (newCount > maxCount) {
                    event.preventDefault();
                    swal('Maximum number of Question is already selected for answer!');
                } else {

                    $('#max_selected_question_answer_' + setId).val(newCount);

                    $('#answer_' + setId + '_' + questionId).prop('disabled', false);
                    $('#answer_' + setId + '_' + questionId).summernote('enable');

                    //save & lock botton disable false
                    $('#answer_set_save_' + setId + '_' + questionId).prop('disabled', false);
                    $('#answer_set_lock_' + setId + '_' + questionId).prop('disabled', false);

                }

            } else {

                swal({
                    title: 'Are you sure you want to uncheck?',
                    text: 'You will lose answer for this Question!',
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
                                event.preventDefault();
                                var newCount = selectedCount - 1;
                                $('#max_selected_question_answer_' + setId).val(newCount);

                                $('#optional_question_answer_' + setId + '_' + questionId).prop('disabled', true);
                                $('#optional_question_answer_org_' + setId + '_' + questionId).prop('disabled', true);

                                $('#answer_' + setId + '_' + questionId).prop('disabled', true);
                                $('#answer_' + setId + '_' + questionId).summernote('disable');
                                $('#answer_' + setId + '_' + questionId).summernote('reset');

                                //Delete answer script for this question set
                                toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                                $.ajax({
                                    url: "{{ URL::to('epeExam/deleteSubjectiveIndividualAnswer') }}",
                                    type: "POST",
                                    data: {epe_mark_id: $('#epe_mark_id').val(), set_id: setId, question_id: questionId},
                                    dataType: "json",
                                    success: function (response) {
                                        toastr.success("You've lost the answer for this question.", "Success", {"closeButton": true});
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

                                //save & lock botton disable false
                                $('#answer_set_save_' + setId + '_' + questionId).prop('disabled', true);
                                $('#answer_set_lock_' + setId + '_' + questionId).prop('disabled', true);
                            } else {
                                $('#optional_question_answer_' + setId + '_' + questionId).prop('checked', true);
                                $('#optional_question_answer_org_' + setId + '_' + questionId).prop('disabled', false);
                                event.preventDefault();
                            }

                        });
            }//else

        });


        $(document).on("submit", '#answerScript', function (e) {

            e.preventDefault();

            var title = '{{trans("english.ARE_YOU_SURE_TO_SUBMIT_SUBJECTIVE_SCRIPT")}}';

            var maxPossibleAnswer = parseInt($('#max_possible_answer').val());
            var maxSelectedAnswer = parseInt($('#max_selected_answer').val());
            var questionLeft = maxPossibleAnswer - maxSelectedAnswer;
            if (maxSelectedAnswer > maxPossibleAnswer) {
                swal('You can submit maximum ' + maxPossibleAnswer + ' question set. You have selected ' + maxSelectedAnswer + ' sets!');
                return false;
            }

            if (questionLeft != 0) {
                title = title.concat('<br />You have ' + questionLeft + ' question sets left two answer!');
            }

            $('.text-editor').each(function (index) {
                if ($(this).parent().find('.note-editor .note-editable').attr('contenteditable') == 'true') {
                    if ($(this).parent().find('.note-editor .note-editable').text().length == 0) {
                        title = title.concat('<br />You have not answered ' + $('#' + this.id).attr('data-question-set') + $('#' + this.id).attr('data-question-no'));
                    }
                }
            });

            var form = this;

            swal({
                title: title,
                text: '<strong></strong>',
                type: 'warning',
                html: true,
                allowOutsideClick: true,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-info',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'No, Do Not Submit',
            }, function (isConfirm) {

                if (isConfirm) {
                    $('#redirect').val('1');
                    toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                    form.submit();
                }
            });

//        var c = confirm('Are you sure you want to submit your Subjctive Script?');
//        if (c) {
//            $('#redirect').val('1');
//        }
//        return c;
        });

        $('input').bind('copy paste', function (e) {
            e.preventDefault();
        });

        //This function use for individual question answer
        $(document).on("click", '.individual-question-answer-save', function (event) {
            event.preventDefault();

            var setId = $(this).attr('data-question-set-id');
            var hasOptions = $("#has_option_" + setId).val();
            var questionId = $(this).attr('data-question-id');
            var epeMarkId = $("#epe_mark_id").val();
            var answer = $("#answer_" + setId + "_" + questionId).val();

            toastr.info("Loading...", "Please Wait.", {"closeButton": true});

            $.ajax({
                url: "{{ URL::to('saveSingleSubjectiveQuestion') }}",
                type: "POST",
                data: {epe_mark_id: epeMarkId, set_id: setId, has_options: hasOptions, question_id: questionId, answer: answer},
                success: function (response) {
                    toastr.success(response.data, "Success", {"closeButton": true});
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

        //This function use for individual question answer
        $(document).on("click", '.individual-question-answer-lock', function (event) {
            event.preventDefault();

            var setId = $(this).attr('data-question-set-id');
            var hasOptions = $("#has_option_" + setId).val();
            var questionId = $(this).attr('data-question-id');
            var epeMarkId = $("#epe_mark_id").val();
            var answer = $("#answer_" + setId + "_" + questionId).val();

            //This function use for sweetalert confirm message
            swal({
                title: 'Are you sure you want to Lock?',
                text: '<strong>No way to Unlock futher!</strong>',
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
                                url: "{{ URL::to('saveLockSingleSubjectiveQuestion') }}",
                                type: "POST",
                                data: {epe_mark_id: epeMarkId, set_id: setId, has_options: hasOptions, question_id: questionId, answer: answer},
                                success: function (response) {
                                    toastr.success(response.data, "Success", {"closeButton": true});
                                    $("#answer_" + setId + "_" + questionId).summernote('disable');
                                    //Question set disabled
                                    $("#questionSet_" + setId).attr('disabled', true);

                                    //Optional question disabled
                                    //                    $("#optional_question_answer_" + setId).attr('disabled', true);
                                    $('#optional_question_answer_' + setId + '_' + questionId).prop('disabled', true);
                                    $('#optional_question_answer_org_' + setId + '_' + questionId).prop('disabled', true);
                                    //Individual question save & lock button disabled
                                    $("#question_answer_button_" + setId + "_" + questionId).hide('slow');
                                    App.unblockUI();

                                },
                                beforeSend: function () {
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

                                    //Ending ajax loader
                                    App.unblockUI();
                                }
                            });
                        } else {
                            //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");
                        }
                    });

        });

        var fixmeTop = $('.script-header-sticky').offset().top;
        $(window).scroll(function () {
            var currentScroll = $(window).scrollTop();
            if (currentScroll >= fixmeTop) {
                $('#script-header').hide();
                $('.script-header-sticky').css({
                    position: 'fixed',
                    //top: '49px',
                    //right: '4%',
                    padding: '20px',
                    background: '#fff',
                    'z-index': '9999',
                    'margin-right': '0px',
                    width: '100%',
                    //border: '1px solid #000000',
                    'border-radius': '',
                });

            } else {
                $('#script-header').show();
                $('.script-header-sticky').css({
                    position: 'static',
                    top: '0%',
                    right: '0%',
                    padding: '10px',
                    background: 'none',
                    width: '',
                    border: '',
                });
            }
        });
    });

</script> 

<style type="text/css">

    .text-red{
        color: red;
    }

    .nav-tabs li.active a{
        background: #E87E04 !important;
        color:#FFF;
    }   


</style>

<!--<input type="button" value="click to toggle fullscreen" onclick="toggleFullScreen(document.body)">-->

@stop

