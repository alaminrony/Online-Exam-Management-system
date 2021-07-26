@extends('layouts.epeExam')
@section('data_count')

<div class="page-content">

    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box">

                <div class="portlet-body form">
                    {{ Form::open(array('role' => 'form', 'url' => 'mockExam', 'class' => 'form-horizontal', 'id'=>'answerScript')) }}

                    {{ Form::hidden('mock_id', $mock->id, array('id'=>'mock_id')) }}
                    {{ Form::hidden('mock_mark_id', $target->id, array('id'=>'mock_mark_id')) }}
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
                                <div class="col-md-10 col-md-offset-1 exam-header">{{ __('label.SUBJECT').' : '.$mock->Subject->title. ' ('.__('label.MOCK_TEST').')' }}</div>
                            </div>
                            <br />
                        </div>

                        <div class="row script-header-sticky">
                            <div class="col-md-12 hidden-sm hidden-xs visible-lg visible-md">
                                @if(!empty($questionArr))
                                @for($sl = 1;$sl<= (int)$mock->obj_no_question;$sl++)
                                <a href="#question-{{$sl}}" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-{{$sl}}">
                                    {{$sl}}
                                </a>
                                @endfor
                                @endif
                            </div>
                            <div class="col-md-4 col-md-offset-1 text-left">
                                {!! __('label.ANSWERED').' : <span id="answered">0</span> out of '.$mock->obj_no_question  !!}<!-- __('label.DURATION').' : '.$durationHours.$durationMin-->
                            </div>
                            <div class="col-md-2 text-left">{!! __('label.MARK').' : '.$target->total_mark !!}</div>
                            <div class="col-md-2 col-md-offset-1 text-right"><strong><span class="text-red" id="counter"></span></strong></div>
                        </div>

                    </div>

                    <div class="form-body" id="questionBody">
                        @if(!empty($questionArr))
                        <?php $i = 1; ?>
                        @foreach($questionArr as $question)

                        @if($question['type_id'] != '6')
                        <div class="row" id="{{ $question['id'] }}">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group" id="question-<?php echo $i; ?>">
                                    <strong>Q{!! $i.'. '.$question['question'] !!}</strong>
                                    @if(!empty($question['note']))
                                    <span class="tooltips question-node" title="{!! $question['note'] !!}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                    @endif
                                    <input type="hidden" name="question_id[]" value="{{$question['id']}}" />
                                </div>

                                @if(!empty($question['document']))
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        @if(!empty($question['content_type_id']) && $question['content_type_id'] == '1')
                                        <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{asset('public/uploads/questionBank/image/'.$question['document'])}}" data-target="#image-loader" data-toggle="modal">
                                            <img class="question-script-image-first-tab" src="{{asset('public/uploads/questionBank/image/'.$question['document'])}}" alt="{{ $question['document'] }}"> 
                                        </a>
                                        @elseif(!empty($question['content_type_id']) && $question['content_type_id'] == '2')
                                        <audio controls>
                                            <source src="{{asset('public/uploads/questionBank/audio/'.$question['document'])}}" alt="{{ $question['document'] }}" type="audio/mpeg">
                                        </audio>
                                        @elseif(!empty($question['content_type_id']) && $question['content_type_id'] == '3')
                                        <video controls width="50%">
                                            <source src="{{asset('public/uploads/questionBank/video/'.$question['document'])}}" alt="{{ $question['document'] }}" type="video/mp4">
                                        </video>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <div class="form-group" style="padding-left:30px;">

                                    @if($question['type_id'] == '1')

                                    <div class="mt-radio-list">

                                        @for($j=1;$j<=4;$j++)
                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[{{$question['id']}}]" id="opt_{{$j.'_'.$question['id']}}" value="{{ $j }}" data-selected-question="{{$i}}"> {{ $question['opt_'.$j] }}
                                            <span></span>
                                        </label>
                                        @endfor
                                    </div>

                                    @elseif($question['type_id'] == '3')

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" autocomplete="off" name="question[{{$question['id']}}]" id="txt_{{$question['id']}}" class="form-control input-inline input-medium count-answer-text" placeholder="{{ __('label.TYPE_YOUR_ANSWER_HERE') }}" data-selected-question="{{$i}}">
                                        </div>
                                    </div>

                                    @elseif($question['type_id'] == '5')

                                    <div class="mt-radio-list">

                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[{{$question['id']}}]" id="btnTrue_{{$question['id']}}" value="1" data-selected-question="{{$i}}"> {{ __('label.TRUE') }}
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[{{$question['id']}}]" id="btnFalse_{{$question['id']}}" value="0" data-selected-question="{{$i}}"> {{ __('label.FALSE') }}
                                            <span></span>
                                        </label>
                                    </div>


                                    @endif
                                </div>
                            </div>
                        </div>

                        <?php $i++; ?>

                        @else

                        @if(!empty($question['match_item']))
                        <div class="row">
                            <div class="form-group" id="question-<?php echo $i; ?>">
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
                                                @foreach($question['match_item'] as $item)                                            
                                                <tr id="question-<?php echo $i; ?>">
                                                    <td class="text-center">{{ $i }} </td>
                                                    <td>
                                                        {{  $item['question'] }}
                                                        @if(!empty($item['note']))
                                                        <span class="tooltips  question-node" title="{{$item['note']}}" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                                        @endif

                                                        @if(!empty($item['image']))
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <a class="btn tooltips" title="{{ __('label.CLICK_TO_EXPAND_IMAGE') }}" href="{{URL::to('/')}}/question/getImage/{{ $item['image'] }}" data-target="#image-loader" data-toggle="modal">
                                                                    <img class="question-script-image-first-tab" src="{{URL::to('/')}}/public/uploads/questionBank/{{ $item['image'] }}" alt="{{ $item['image'] }}"> 
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        {{ Form::select('question['.$item['id'].']', $matchAnswer[$question['chunk_key']], null, array('class' => 'form-control js-source-states count-answer-select',  'data-selected-question'=>$i)) }}
                                                        <input type="hidden" name="question_id[]" value="{{$item['id']}}" />
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif 
                        @endforeach
                        @endif

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary" id="mockExamSubmit"><i class="fa fa-save"></i> {{__('label.SUBMIT')}}</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-danger">{{ __('label.TIME_EXPIRED') }}</h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong>Sorry! Time is over. Your mock test script will be submitted automatically!</strong></strong></span>
            </div>
            <div class="modal-footer">&nbsp;</div>
        </div>
    </div>
</div>


<!-- BEGIN QUICK NAV -->
<nav class="quick-nav hidden-md hidden-lg visible-sm visible-xs" id="quick_nav">
    <a class="quick-nav-trigger" href="javascript:void[0];">
        <span aria-hidden="true"></span>
    </a>
    <ul class="bg-green-jungle bg-font-green">
        @if(!empty($questionArr))
        @for($sl = 1;$sl<=(int)$mock->obj_no_question;$sl++)

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
<!-- END QUICK NAV -->

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

</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //Tooltip, activated by hover event
        $(".tooltips").tooltip({html: true});

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

    window.addEventListener("beforeunload", function (e) {

        var confirmationMessage = 'Your quiz progress will lost if you leave this page!';
        var redirect = $("#redirect").val();

        if (redirect == '0') {
            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        }

    });


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
    var countDownDate = new Date("{{ $target->exam_date.' '.$target->end_time }}").getTime();
    // Get todays date and time
    var now = new Date("<?php echo date("Y-m-d H:i:s") ?>").getTime();

// Update the count down every 1 second
    var x = setInterval(function () {

        // Find the distance between now an the count down date
        now += 1000;
        var distance = countDownDate - now;
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("counter").innerHTML = "Time Remaining : " + hours + ":" + minutes + ":" + seconds;

        // If the count down is finished, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("counter").innerHTML = "EXPIRED";

            var data = new FormData($('form')[0]);
            $('#static').modal('show');

            //$('#questionBody').html('<span class="text-danger">Sorry! Time is over. Your answer will be submitted automatically!</span>');

            var answerScript = new FormData($('#answerScript')[0]);

            $.ajax({
                url: "{{ URL::to('mockExam') }}",
                type: "POST",
                data: data,
                dataType: 'json',
                //cache: false,
                contentType: false,
                processData: false,
                //async: true,
                success: function (response) {

                },
                beforeSend: function () {
                    //$("#taeSubmit").prop("disabled", true);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {

                }
            });

            setTimeout(function () {

                var mockId = $('#mock_id').val();
                $('#redirect').val('1');
                window.location = "{{ URL::to('/mockExam/examresult?mock_id=" + mockId + "') }}";

            }, 3000);


        }
    }, 1000);

    //Disable Mouse Right Click, Cut, Copy & Paste
    $(document).ready(function () {

        $('#answerScript').submit(function () {

            var c = confirm('Are you sure you want to submit your answer script?');
            if (c) {
                $('#redirect').val('1');
            }
            return c;
        });

        //Disable cut copy paste
        $('body').bind('cut copy paste', function (e) {
            e.preventDefault();
        });

        //Disable mouse right click
        $("body").on("contextmenu", function (e) {
            return false;
        });

        $(".count-answer-radio").click(function () {

            var previousValue = $(this).data('storedValue');
            var rName = $(this).attr('name');

            var selectedQuestion = $(this).attr("data-selected-question");
            var answered = $("#answered_str").val();


            if (previousValue) {

                $(".question-" + selectedQuestion).removeClass("bg-green-jungle bg-font-green").addClass("bg-grey bg-font-grey");

                if (answered.indexOf(this.name) != -1) {

                    var replaceStr = (answered == this.name) ? this.name : ',' + this.name;
                    var answered = answered.replace(replaceStr, '');
                    $("#answered_str").val(answered)
                }

                $(this).prop('checked', !previousValue);
                $(this).data('storedValue', !previousValue);

            } else {

                $(".question-" + selectedQuestion).removeClass("bg-grey bg-font-grey").addClass("bg-green-jungle bg-font-green");

                if (answered.indexOf(this.name) == -1) {
                    var str = (answered == '') ? this.name : ',' + this.name;
                    var str = answered + str;
                    $("#answered_str").val(str)
                }

                $(this).data('storedValue', true);
                $('input[name="' + rName + '"]:not(:checked)').data("storedValue", false);
            }

            var totalAnswered = $("#answered_str").val();
            var totalCount = (totalAnswered.match(/,/g) || []).length;

            if (totalAnswered != '') {
                totalCount++;
            }

            $("#answered").html(totalCount);

        });

        $(".count-answer-select").change(function () {
            var selectedQuestion = $(this).attr("data-selected-question");
            var answered = $("#answered_str").val();

            if (this.value == '0') {

                $(".question-" + selectedQuestion).removeClass("bg-green-jungle bg-font-green").addClass("bg-grey bg-font-grey");

                if (answered.indexOf(this.name) != -1) {

                    var replaceStr = (answered == this.name) ? this.name : ',' + this.name;
                    var answered = answered.replace(replaceStr, '');
                    $("#answered_str").val(answered)
                }

            } else {

                $(".question-" + selectedQuestion).removeClass("bg-grey bg-font-grey").addClass("bg-green-jungle bg-font-green");

                if (answered.indexOf(this.name) == -1) {
                    var str = (answered == '') ? this.name : ',' + this.name;
                    var str = answered + str;
                    $("#answered_str").val(str)
                }
            }

            var totalAnswered = $("#answered_str").val();
            var totalCount = (totalAnswered.match(/,/g) || []).length;

            if (totalAnswered != '') {
                totalCount++;
            }

            $("#answered").html(totalCount);

        });

        $(".count-answer-text").keyup(function () {
            var selectedQuestion = $(this).attr("data-selected-question");

            var answer = this.value.trim();
            var answered = $("#answered_str").val();

            if (answer == '') {
                $(".question-" + selectedQuestion).removeClass("bg-green-jungle bg-font-green").addClass("bg-grey bg-font-grey");

                if (answered.indexOf(this.name) != -1) {

                    var replaceStr = (answered == this.name) ? this.name : ',' + this.name;
                    var answered = answered.replace(replaceStr, '');
                    $("#answered_str").val(answered)

                }

            } else {

                $(".question-" + selectedQuestion).removeClass("bg-grey bg-font-grey").addClass("bg-green-jungle bg-font-green");

                if (answered.indexOf(this.name) == -1) {

                    var str = (answered == '') ? this.name : ',' + this.name;
                    var str = answered + str;
                    $("#answered_str").val(str)

                }
            }

            var totalAnswered = $("#answered_str").val();
            var totalCount = (totalAnswered.match(/,/g) || []).length;

            if (totalAnswered != '') {
                totalCount++;
            }

            $("#answered").html(totalCount);

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
                $('.script-header-sticky').each(function () {
                    //this.style.setProperty('border-radius', '5px', 'important');
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

    .selected-objective-question{
        width: 20px; 
        height: 20px; 
        padding: 0; 
        margin-bottom: 5px;
        margin-right: 5px;
    }

</style>

<!--<input type="button" value="click to toggle fullscreen" onclick="toggleFullScreen(document.body)">-->

@stop

