@extends('layouts.epeExam')
@section('data_count')

@include('layouts.flash')
<div class="portlet box">
    <div class="portlet-body form">
        {{ Form::open(array('role' => 'form', 'url' => 'epeExam', 'class' => 'form-horizontal', 'id'=>'answerScript')) }}
        {{ Form::hidden('epe_id', $epe->id, array('id'=>'epe_id')) }}
        {{ Form::hidden('epe_mark_id', $target->id, array('id'=>'epe_mark_id')) }}
        {{ Form::hidden('redirect', 0, array('id'=>'redirect')) }}
        {{ Form::hidden('answered_str', '', array('id'=>'answered_str')) }}

        <div class="form-body text-center" id="objectiveExamHeader">
            <div id="script-header">

                <div class="row">
                    <div class="col-md-10 col-md-offset-1 confidential">{{ __('label.CONFIDENTIAL') }}</div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.EXAM_HEADER') }}</div>
                </div>

                <div class="row">
                    <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.SUBJECT').' : '.$epe->Subject->title }}
                    </div>

                    <div class="col-md-11 text-right">
                        @if(!empty($existEpe->file))
                        <a href="{{url('epeExam/viewFile?examId='.$examId)}}" title="View File" class="tooltips btn btn-success" target="_blank">@lang('label.VIEW_FILE')</a>
                        @endif
                        <strong><span class="text-red" id="counter"></span></strong>
                    </div>

                </div>
                <br/>
            </div>
            <div class="row script-header-sticky">
                <div class="col-md-2 col-md-offset-1 text-left">
                    <?php $answeredNo = $qusSerialNo - 1; ?>
                    {!! __('label.ANSWERED').' : <span id="answered">'.$answeredNo.'</span> out of '.$epe->obj_no_question  !!}<!-- __('label.DURATION').' : '.$durationHours.$durationMin-->
                </div>
                <div class="col-md-2 text-left">{{__('label.TOTAL_MARK').' : '.$target->total_mark }}</div>
                <div class="col-md-2 text-left">{{__('label.THIS_QUESTION_MARK').' : '.$epeToQusList[$qusNo]['mark'] }}</div>
                <div class="col-md-4 text-right"><strong><span class="text-red" id="qusCounter"></span></strong></div>
            </div>
        </div>

        <div class="form-body" id="questionBody">
            @if(!empty($questionArr[$qusNo]))
            <?php
            $existsAnswer = null;
            $textEditor = 'text-normal';
            $answerChrCount = 4000;
            ?>
            @if($questionArr[$qusNo]['type_id'] != '6')
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group" id="question-<?php echo $qusSerialNo; ?>">
                        <strong>Q{{ $qusSerialNo.'. '.$questionArr[$qusNo]['question'] }}</strong> 
                        @if(!empty($questionArr[$qusNo]['note']))
                        <span class="tooltips question-node" title="{{htmlspecialchars_decode(stripslashes($questionArr[$qusNo]['note']))}}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                        @endif
                        <input type="hidden" name="question_id" value="{{$questionArr[$qusNo]['id']}}" />
                    </div>

                    @if(!empty($questionArr[$qusNo]['document']))
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '1')
                            <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{asset('public/uploads/questionBank/image/'.$questionArr[$qusNo]['document'])}}" data-target="#image-loader" data-toggle="modal">
                                <img class="question-script-image-first-tab" src="{{asset('public/uploads/questionBank/image/'.$questionArr[$qusNo]['document'])}}" alt="{{ $questionArr[$qusNo]['document'] }}"> 
                            </a>
                            @elseif(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '2')
                            <audio controls>
                                <source src="{{asset('public/uploads/questionBank/audio/'.$questionArr[$qusNo]['document'])}}" alt="{{ $questionArr[$qusNo]['document'] }}" type="audio/mpeg">
                            </audio>
                            @elseif(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '3')
                            <video controls width="50%">
                                <source src="{{asset('public/uploads/questionBank/video/'.$questionArr[$qusNo]['document'])}}" alt="{{ $questionArr[$qusNo]['document'] }}" type="video/mp4">
                            </video>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="form-group" style="padding-left:30px;">

                        @if($questionArr[$qusNo]['type_id'] == '1')
                        <div class="mt-radio-list" id="question-<?php echo $qusSerialNo; ?>">
                            <?php
                            $optionArr = range(1, 4);
                            shuffle($optionArr);
                            ?>

                            @foreach($optionArr as $j)
                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[{{$questionArr[$qusNo]['id']}}]"  {{ (Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == $j) ? 'checked="checked"' : ''  }}  id="opt_{{$j.'_'.$questionArr[$qusNo]['id']}}" value="{{ $j }}" data-selected-question="{{$qusSerialNo}}"> {{ $questionArr[$qusNo]['opt_'.$j] }}
                                <span></span>
                            </label>
                            @endforeach

                        </div>

                        @elseif($questionArr[$qusNo]['type_id'] == '3')

                        <div class="col-md-12">
                            <div class="form-group" id="question-<?php echo $qusSerialNo; ?>">
                                <input type="text" autocomplete="off" name="question[{{$questionArr[$qusNo]['id']}}]" id="txt_{{$questionArr[$qusNo]['id']}}" class="form-control input-inline input-medium count-answer-text" placeholder="{{ __('label.TYPE_YOUR_ANSWER_HERE') }}" value="{{ Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) }}" data-selected-question="{{$qusSerialNo}}">
                            </div>
                        </div>

                        @elseif($questionArr[$qusNo]['type_id'] == '5')
                        <div class="mt-radio-list" id="question-<?php echo $qusSerialNo ?>">

                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[{{$questionArr[$qusNo]['id']}}]" id="btnTrue_{{$questionArr[$qusNo]['id']}}" value="1" {{ (Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == '1') ? 'checked="checked"' : ''  }} data-selected-question="{{$qusSerialNo}}"> {{ __('label.TRUE') }}
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[{{$questionArr[$qusNo]['id']}}]" id="btnFalse_{{$questionArr[$qusNo]['id']}}" value="0" {{ (Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == '0') ? 'checked="checked"' : ''  }}  data-selected-question="{{$qusSerialNo}}"> {{ __('label.FALSE') }}
                                <span></span>
                            </label>
                        </div>
                        @elseif($questionArr[$qusNo]['type_id'] == '4')
                        <div class="row">
                            <div class="col-md-12 {{$textEditor}}">
                                {{ Form::textarea('question['.$questionArr[$qusNo]['id'].']', Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']), array('id'=> 'answer_'.$qusSerialNo.'_'.$questionArr[$qusNo]['id'], 'class' => 'form-control text-editor')) }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-counter text-right">
                                Maximum 4000 character; <span id="text-counter-{{'answer_'.$qusSerialNo.'_'.$questionArr[$qusNo]['id']}}">{{ $answerChrCount }}</span> remaining
                            </div>
                        </div>

                        @endif

                    </div>
                </div>
            </div>

            @else

            @if(!empty($questionArr[$qusNo]['match_item']))

            <div class="row">
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-1"><strong>{{ 'Q. '.__('label.QUESTION_INSTRUCTION_FOR_MATCHING') }}</strong></div>
                </div>                            
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <div class="form-group" style="padding-left:30px;">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center col-md-1">{{__('label.SL_NO')}}</th>
                                        <th class="text-center col-md-6">{{__('label.COLUMN_A')}}</th>
                                        <th class="text-center col-md-5">{{__('label.COLUMN_B')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($questionArr[$qusNo]['match_item'] as $item)                                            
                                    <tr id="question-<?php echo $qusSerialNo; ?>">
                                        <td class="text-center">{{ $qusSerialNo }} </td>
                                        <td>
                                            {{  $item['question'] }} 

                                            @if(!empty($item['image']))
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{URL::to('/')}}/question/getImage/{{ $item['image'] }}" data-target="#image-loader" data-toggle="modal">
                                                        <img class="question-script-image-first-tab" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $item['image'] }}" alt="{{ $item['image'] }}">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif

                                            @if(!empty($item['note']))
                                            <span class="tooltips  question-node" title="{{htmlspecialchars_decode(stripslashes($item['note']))}}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ Form::select('question['.$item['id'].']', $matchAnswer[$questionArr[$qusNo]['chunk_key']], Session::get('epeExam.'.Request::get('id').'.answerArr.'.$item['id']), array('class' => 'form-control js-source-states count-answer-select', 'data-selected-question'=>$qusSerialNo)) }}
                                            <input type="hidden" name="question_id" value="{{$item['id']}}" />
                                        </td>
                                    </tr>
                                    <?php $qusSerialNo++; ?>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @endif
            @endif

            <div class="row">
                <div class="form-actions">
                    <div class="col-md-12 text-center">
                        <div class="col-md-12 text-center">
                            {{ Form::hidden('question_queue', $questionQueue, array('id' => 'question_queue') ) }}
                            @if(!empty($mAnswerQueue))
                            @foreach($mAnswerQueue as $chunkKey => $content)
                            {{ Form::hidden('m_answer_queue['.$chunkKey.']', $content, array('id' => 'm_answer_queue_'.$chunkKey) ) }}
                            @endforeach
                            @endif
                            <button type="submit" class="btn btn-primary" id="epeExamSubmit"><i class="fa fa-save"></i> @if($qusSerialNo ==$epe->obj_no_question) {{__('label.SUBMIT')}} @else {{__('label.SUBMIT_AND_NEXT')}} @endif </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
        <!-- END FORM-->
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
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                <h4 class="modal-title text-danger">{{ __('label.TIME_EXPIRED') }}</h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong>Sorry! Time is over. Your objective script will be submitted automatically!</strong></strong></span>
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

<!-- BEGIN QUICK NAV -->
<nav class="quick-nav hidden-md hidden-lg visible-sm visible-xs" id="quick_nav">
    <a class="quick-nav-trigger" href="javascript:void[0];">
        <span aria-hidden="true"></span>
    </a>
    <ul class="bg-green bg-font-green">
        @if(!empty($questionArr[$qusNo]))
        @for($sl = 1;$sl<=(int)$epe->obj_no_question;$sl++)

        <li>
            <a href="#question-{{$sl}}" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-{{$sl}}" style="padding:0; height: 20px;">
                {{$sl}}
            </a>
        </li>
        <!--                <a href="#question-{{$sl}}" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-{{$sl}}">
                            {{$sl}}
                        </a>-->
        @endfor
        @endif
    </ul>
    <span aria-hidden="true" class="quick-nav-bg"></span>
</nav>
<div class="quick-nav-overlay" style="display:none;"></div>

<style type="text/css">
    #quick_nav ul {
        width: 50px;
        height: 500px;
        overflow-y: scroll;
        background: #ccc;
        white-space: nowrap;
        padding-top: 0px!important;
        margin-top: 50px;
        margin-bottom: 20px!important;
    }

    .quick-nav {
        top: 45%!important;
    }

    @media (max-width: 991px) {
        /* 991px */
        .quick-nav {
            top:11%!important;
            margin-top: 0; 
        } 
    }

    .text-red{
        color: red;
    }

    .selected-objective-question{
        width: 20px; 
        height: 20px; 
        padding: 0; 
        margin-bottom: 5px;
        margin-right: 5px;
    }

</style>


<script type="text/javascript">

    jQuery(document).ready(function () {
        // Select all links with hashes
        $('a[href*="#"]')
                // Remove links that don't actually link to anything
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function (event) {
                    // On-page links
                    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                        // Figure out element to scroll to
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        // Does a scroll target exist?
                        if (target.length) {
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top
                            }, 1000, function () {
                                // Callback after animation
                                // Must change focus!
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                } else {
                                    $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                                    $target.focus(); // Set focus again
                                }
                                ;
                            });
                        }
                    }
                });
    });

    $(document).ready(function () {
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

        //Tooltip, activated by hover event
        $(".tooltips").tooltip({html: true});
        //They can be chained like the example above (when using the same selector).

        //Disable cut copy paste
        $('body').bind('cut copy paste', function (e) {
            e.preventDefault();
        });
        // Disable mouse right click
        $("body").on("contextmenu", function (e) {
            return false;
        });

//        window.addEventListener("beforeunload", function (e) {
//
//            var confirmationMessage = 'Your quiz progress will lost if you leave this page!';
//            var redirect = $("#redirect").val();
//
//            if (redirect == '0') {
//                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
//                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
//            }
//
//        });

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
        var countDownDate = new Date("{{ $target->exam_date.' '.$target->objective_end_time }}").getTime();

        // Get todays date and time
        var now;
        var objInitialTime = localStorage.getItem("objInitialTime");
        var getTimeArr = JSON.parse(localStorage.getItem("objTimeArr"));

        var totalSeconds = 0;
        if (objInitialTime) {
            var totalSeconds = parseInt(getTimeArr.totalSeconds);
            now = parseInt(objInitialTime) + (totalSeconds * 1000);
//            console.log('im resuming')
//            console.log(now);
            //now = 1000000;
        } else {
//            console.log('fot the first time')
            now = new Date("<?php echo date("Y-m-d H:i:s") ?>").getTime();
            localStorage.setItem("objInitialTime", now);
        }

        // Update the count down every 1 second
        var x = setInterval(function () {
            // Find the distance between now an the count down date
            now += 1000;
            var distance = countDownDate - now;
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            totalSeconds++;

            if (distance > 0) {
                document.getElementById("counter").innerHTML = "Time Remaining : " + hours + ":" + minutes + ":" + seconds;
            }

            var setTimeArr = {distance: distance, hours: hours, minutes: minutes, seconds: seconds, totalSeconds: totalSeconds};
            localStorage.setItem("objTimeArr", JSON.stringify(setTimeArr));
            document.cookie = "objTimeArr=" + JSON.stringify(setTimeArr);
            if (hours == 0 && minutes == 5 && seconds == 0) {
                $('#5-min-warning').modal('show');
            }

//            alert(seconds);
//            alert(seconds % 10);



            if ((distance <= 0) && (seconds % 30 == 0)) {

                //alert('time finished');

                $('#static').modal('show');
                document.getElementById("counter").innerHTML = "EXPIRED";
                var data = new FormData($('#answerScript')[0]);

                //$('#questionBody').html('<span class="text-danger">Sorry! Time is over. Your answer will be submitted automatically!</span>');
                localStorage.removeItem("qusInitialTime");
                localStorage.removeItem("qusTimeArr");

                $.ajax({
                    url: "{{ URL::to('epeExam') }}",
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (response) {

                        clearInterval(x);
                        setTimeout(function () {
                            var epeId = $('#epe_id').val();
                            $('#redirect').val('1');
                            window.location.href = response.url;
                        }, 3000);
                    },
                    beforeSend: function () {
                        //$("#taeSubmit").prop("disabled", true);
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        //toastr.error("Connect with Internet and submit your script manually.", "Caution", {"closeButton": true, positionClass: "toast-bottom-right"});
                        //$('#epeExamSubmit').attr('disabled', false);
                    }
                });

            }
        }, 1000);

        /// time count Down only question 
        var countDownTimeQusWise = new Date("{{ $target->exam_date.' '.$epeToQusList[$qusNo]['time'] }}").getTime();

        // Get todays date and time
        var nowQus;
        var qusInitialTime = localStorage.getItem("qusInitialTime");
        var qusTimeArr = JSON.parse(localStorage.getItem("qusTimeArr"));

        var totalSecondsQus = 0;
        if (qusInitialTime) {
            var totalSecondsQus = parseInt(qusTimeArr.totalSecondsQus);
            nowQus = parseInt(qusInitialTime) + (totalSecondsQus * 1000);
        } else {

            nowQus = new Date("<?php echo date("Y-m-d H:i:s") ?>").getTime();
            localStorage.setItem("qusInitialTime", nowQus);
        }
        // Update the count down every 1 second qus wise
        var y = setInterval(function () {
            // Find the distance between now an the count down date
            nowQus += 1000;
            var distanceQus = countDownTimeQusWise - nowQus;
            var hs = Math.floor((distanceQus % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var mins = Math.floor((distanceQus % (1000 * 60 * 60)) / (1000 * 60));
            var secs = Math.floor((distanceQus % (1000 * 60)) / 1000);
            totalSecondsQus++;

            if (distanceQus > 0) {
                document.getElementById("qusCounter").innerHTML = "This Question Time Remaining : " + hs + ":" + mins + ":" + secs;
            }

            var setQusTimeArr = {distanceQus: distanceQus, hs: hs, mins: mins, secs: secs, totalSecondsQus: totalSecondsQus};
            localStorage.setItem("qusTimeArr", JSON.stringify(setQusTimeArr));
            document.cookie = "qusTimeArr=" + JSON.stringify(setQusTimeArr);


            //save the answers every 30 seconds
            if (secs % 30 == 0) {
                var tempData = new FormData($('form')[0]);
                $.ajax({
                    url: "{{ URL::to('objectiveTempSave') }}",
                    type: "POST",
                    data: tempData,
                    dataType: 'html',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#epeExamSubmit').attr('disabled', false);
                    },
                    beforeSend: function () {

                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error("You don't have Internet Connection!", "Caution", {"closeButton": true, positionClass: "toast-bottom-right"});
                        $('#epeExamSubmit').attr('disabled', true);
                    }
                });

            }//if

            if ((distanceQus <= 0) && (secs % 30 == 0)) {
                clearInterval(y);
                clearInterval(x);
                $("#epeExamSubmit").prop("disabled", true);
                localStorage.removeItem("qusInitialTime");
                localStorage.removeItem("qusTimeArr");
                var data = new FormData($('#answerScript')[0]);
                $.ajax({
                    url: "{{ URL::to('epeExam') }}",
                    type: "POST",
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        setTimeout(function () {
                            var epeId = $('#epe_id').val();
                            $('#redirect').val(epeId);
                            window.location.href = response.url;
                        }, 3000);
                    },
                    beforeSend: function () {

                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error("Connect with Internet and submit your script manually.", "Caution", {"closeButton": true, positionClass: "toast-bottom-right"});
                        $('#epeExamSubmit').attr('disabled', false);
                    }
                });

            }
        }, 1000);

        $(document).on("click", '#epeExamSubmit', function (e) {
            $("#epeExamSubmit").attr("disabled", true);

            e.preventDefault();
            var alertTitle = '{!! __("label.ARE_YOU_SURE_TO_SUBMIT_AND_NEXT") !!}';

            swal({
                title: alertTitle,
                text: '',
                type: 'warning',
                html: true,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Yes, Submit & Next',
                closeOnConfirm: false,
            }, function (isConfirm) {

                if (isConfirm) {
                    var tempData = new FormData($('#answerScript')[0]);
//                     console.log(tempData);return false;
                    $.ajax({
                        //url: "{{ URL::to('objectiveTempSave') }}",
                        url: "{{ URL::to('epeExam') }}",
                        type: "POST",
                        data: tempData,
                        dataType: 'json',
                        //cache: false,
                        contentType: false,
                        processData: false,
                        //async: true,
                        success: function (response) {
                            localStorage.removeItem("qusInitialTime");
                            localStorage.removeItem("qusTimeArr");
                            clearInterval(y);
                            clearInterval(x);
                            $('#redirect').val('1');
                            toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                            window.location.href = response.url;
                        },
                        beforeSend: function () {
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            toastr.error("You don't have Internet Connection! Please, connect with Internet & Submit your Script.", "Caution", {"closeButton": true, positionClass: "toast-bottom-right"});
                        }
                    });
                } else {
                    $('#epeExamSubmit').removeAttr("disabled");
                }
            });

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

</script> 


<!--<input type="button" value="click to toggle fullscreen" onclick="toggleFullScreen(document.body)">-->

@stop

