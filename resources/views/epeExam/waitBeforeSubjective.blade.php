@extends('layouts.epeExam')
@section('data_count')

<div class="page-content">

    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box">

                <div class="portlet-body form">
                    {{ Form::open(array('role' => 'form', 'url' => 'epeExam', 'class' => 'form-horizontal', 'id'=>'answerScript')) }}

                    {{ Form::hidden('epe_id', $epe->id, array('id'=>'epe_id')) }}
                    {{ Form::hidden('epe_mark_id', $target->id, array('id'=>'epe_mark_id')) }}
                    {{ Form::hidden('redirect', 0, array('id'=>'redirect')) }}

                    <div class="form-body text-center" id="objectiveExamHeader">
                        <?php 
                            $courseName = $epe->Course->title;
                            $partName = $epe->Part->title;
                            $phaseName = $epe->Phase->full_name;
                        ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 confidential">{{ trans('english.CONFIDENTIAL') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 exam-header">{{ trans('english.EXAM_HEADER') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 exam-header">{{ trans('english.ISSP_ABBR').' : '.trim($courseName, ', ') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 exam-header">{{ trim($phaseName, ', ').'; '.trim($partName, ', ').' : '.$epe->Subject->title. ' ('.trans('english.SUBJECTIVE').')' }}</div>
                        </div>
                        <br />

                    </div>



                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>{{ trans('english.WAIT_THANKS_MESSAGE') }}</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center" id="subjectiveWaiter">
                                <div>{{ trans('english.WAIT_BEFORE_SUBJECTIVE_INSTRUCTION') }}</div>
                                <div id="counter"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">{{ trans('english.STARTING_SUBJECTIVE_EXAM') }}</h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong>Redirecting to your subjective exam script...</strong></strong></span>
            </div>
            <div class="modal-footer">&nbsp;</div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        //Disable cut copy paste
        $('body').bind('cut copy paste', function (e) {
            e.preventDefault();
        });
        //Disable mouse right click
        $("body").on("contextmenu", function (e) {
            return false;
        });

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
        var countDownDate = new Date("{{ date('Y-m-d H:i:s', strtotime($target->objective_end_time)) }}").getTime();

        var objInitialTime = localStorage.getItem("objInitialTime");
        var getTimeArr = JSON.parse(localStorage.getItem("objTimeArr"));
        var totalSeconds = parseInt(getTimeArr.totalSeconds);
        var now = parseInt(objInitialTime) + (totalSeconds * 1000);

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Find the distance between now an the count down date
            now += 1000;
            var distance = countDownDate - now;
            // Time calculations for hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            totalSeconds++;

            var setTimeArr = {distance: distance, hours: hours, minutes: minutes, seconds: seconds, totalSeconds: totalSeconds};
            localStorage.setItem("objTimeArr", JSON.stringify(setTimeArr));

            // Display the result in the element with id="demo"
            document.getElementById("counter").innerHTML = hours + ":" + minutes + ":" + seconds;
            // If the count down is finished, write some text 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("counter").innerHTML = "Starting Subjective Exam...";
                var data = new FormData($('form')[0]);
                $('#static').modal('show');

                setTimeout(function () {
                    var epeId = $('#epe_id').val();
                    $('#redirect').val('1');
                    window.location = "{{ URL::to('/examSubjective?id=" + epeId + "') }}";
                }, 3000);
            }
        }, 1000);

    });






</script> 

<style type="text/css">

    .text-red{
        color: red;
    }

</style>

@stop

