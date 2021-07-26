<?php $__env->startSection('data_count'); ?>

<?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="portlet box">
    <div class="portlet-body form">
        <?php echo e(Form::open(array('role' => 'form', 'url' => 'epeExam', 'class' => 'form-horizontal', 'id'=>'answerScript'))); ?>

        <?php echo e(Form::hidden('epe_id', $epe->id, array('id'=>'epe_id'))); ?>

        <?php echo e(Form::hidden('epe_mark_id', $target->id, array('id'=>'epe_mark_id'))); ?>

        <?php echo e(Form::hidden('redirect', 0, array('id'=>'redirect'))); ?>

        <?php echo e(Form::hidden('answered_str', '', array('id'=>'answered_str'))); ?>


        <div class="form-body text-center" id="objectiveExamHeader">
            <div id="script-header">

                <div class="row">
                    <div class="col-md-10 col-md-offset-1 confidential"><?php echo e(__('label.CONFIDENTIAL')); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 exam-header"><?php echo e(__('label.EXAM_HEADER')); ?></div>
                </div>

                <div class="row">
                    <div class="col-md-10 col-md-offset-1 exam-header"><?php echo e(__('label.SUBJECT').' : '.$epe->Subject->title); ?>

                    </div>

                    <div class="col-md-11 text-right">
                        <?php if(!empty($existEpe->file)): ?>
                        <a href="<?php echo e(url('epeExam/viewFile?examId='.$examId)); ?>" title="View File" class="tooltips btn btn-success" target="_blank"><?php echo app('translator')->get('label.VIEW_FILE'); ?></a>
                        <?php endif; ?>
                        <strong><span class="text-red" id="counter"></span></strong>
                    </div>

                </div>
                <br/>
            </div>
            <div class="row script-header-sticky">
                <div class="col-md-2 col-md-offset-1 text-left">
                    <?php $answeredNo = $qusSerialNo - 1; ?>
                    <?php echo __('label.ANSWERED').' : <span id="answered">'.$answeredNo.'</span> out of '.$epe->obj_no_question; ?><!-- __('label.DURATION').' : '.$durationHours.$durationMin-->
                </div>
                <div class="col-md-2 text-left"><?php echo e(__('label.TOTAL_MARK').' : '.$target->total_mark); ?></div>
                <div class="col-md-2 text-left"><?php echo e(__('label.THIS_QUESTION_MARK').' : '.$epeToQusList[$qusNo]['mark']); ?></div>
                <div class="col-md-4 text-right"><strong><span class="text-red" id="qusCounter"></span></strong></div>
            </div>
        </div>

        <div class="form-body" id="questionBody">
            <?php if(!empty($questionArr[$qusNo])): ?>
            <?php
            $existsAnswer = null;
            $textEditor = 'text-normal';
            $answerChrCount = 4000;
            ?>
            <?php if($questionArr[$qusNo]['type_id'] != '6'): ?>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group" id="question-<?php echo $qusSerialNo; ?>">
                        <strong>Q<?php echo e($qusSerialNo.'. '.$questionArr[$qusNo]['question']); ?></strong> 
                        <?php if(!empty($questionArr[$qusNo]['note'])): ?>
                        <span class="tooltips question-node" title="<?php echo e(htmlspecialchars_decode(stripslashes($questionArr[$qusNo]['note']))); ?>" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                        <?php endif; ?>
                        <input type="hidden" name="question_id" value="<?php echo e($questionArr[$qusNo]['id']); ?>" />
                    </div>

                    <?php if(!empty($questionArr[$qusNo]['document'])): ?>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php if(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '1'): ?>
                            <a class="btn tooltips" title="<?php echo e(__('label.CLICK_TO_EXPAND_IMAGE')); ?>" href="<?php echo e(asset('public/uploads/questionBank/image/'.$questionArr[$qusNo]['document'])); ?>" data-target="#image-loader" data-toggle="modal">
                                <img class="question-script-image-first-tab" src="<?php echo e(asset('public/uploads/questionBank/image/'.$questionArr[$qusNo]['document'])); ?>" alt="<?php echo e($questionArr[$qusNo]['document']); ?>"> 
                            </a>
                            <?php elseif(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '2'): ?>
                            <audio controls>
                                <source src="<?php echo e(asset('public/uploads/questionBank/audio/'.$questionArr[$qusNo]['document'])); ?>" alt="<?php echo e($questionArr[$qusNo]['document']); ?>" type="audio/mpeg">
                            </audio>
                            <?php elseif(!empty($questionArr[$qusNo]['content_type_id']) && $questionArr[$qusNo]['content_type_id'] == '3'): ?>
                            <video controls width="50%">
                                <source src="<?php echo e(asset('public/uploads/questionBank/video/'.$questionArr[$qusNo]['document'])); ?>" alt="<?php echo e($questionArr[$qusNo]['document']); ?>" type="video/mp4">
                            </video>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-group" style="padding-left:30px;">

                        <?php if($questionArr[$qusNo]['type_id'] == '1'): ?>
                        <div class="mt-radio-list" id="question-<?php echo $qusSerialNo; ?>">
                            <?php
                            $optionArr = range(1, 4);
                            shuffle($optionArr);
                            ?>

                            <?php $__currentLoopData = $optionArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[<?php echo e($questionArr[$qusNo]['id']); ?>]"  <?php echo e((Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == $j) ? 'checked="checked"' : ''); ?>  id="opt_<?php echo e($j.'_'.$questionArr[$qusNo]['id']); ?>" value="<?php echo e($j); ?>" data-selected-question="<?php echo e($qusSerialNo); ?>"> <?php echo e($questionArr[$qusNo]['opt_'.$j]); ?>

                                <span></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                        <?php elseif($questionArr[$qusNo]['type_id'] == '3'): ?>

                        <div class="col-md-12">
                            <div class="form-group" id="question-<?php echo $qusSerialNo; ?>">
                                <input type="text" autocomplete="off" name="question[<?php echo e($questionArr[$qusNo]['id']); ?>]" id="txt_<?php echo e($questionArr[$qusNo]['id']); ?>" class="form-control input-inline input-medium count-answer-text" placeholder="<?php echo e(__('label.TYPE_YOUR_ANSWER_HERE')); ?>" value="<?php echo e(Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id'])); ?>" data-selected-question="<?php echo e($qusSerialNo); ?>">
                            </div>
                        </div>

                        <?php elseif($questionArr[$qusNo]['type_id'] == '5'): ?>
                        <div class="mt-radio-list" id="question-<?php echo $qusSerialNo ?>">

                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[<?php echo e($questionArr[$qusNo]['id']); ?>]" id="btnTrue_<?php echo e($questionArr[$qusNo]['id']); ?>" value="1" <?php echo e((Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == '1') ? 'checked="checked"' : ''); ?> data-selected-question="<?php echo e($qusSerialNo); ?>"> <?php echo e(__('label.TRUE')); ?>

                                <span></span>
                            </label>
                            <label class="mt-radio">
                                <input type="radio" class="count-answer-radio" name="question[<?php echo e($questionArr[$qusNo]['id']); ?>]" id="btnFalse_<?php echo e($questionArr[$qusNo]['id']); ?>" value="0" <?php echo e((Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']) == '0') ? 'checked="checked"' : ''); ?>  data-selected-question="<?php echo e($qusSerialNo); ?>"> <?php echo e(__('label.FALSE')); ?>

                                <span></span>
                            </label>
                        </div>
                        <?php elseif($questionArr[$qusNo]['type_id'] == '4'): ?>
                        <div class="row">
                            <div class="col-md-12 <?php echo e($textEditor); ?>">
                                <?php echo e(Form::textarea('question['.$questionArr[$qusNo]['id'].']', Session::get('epeExam.'.Request::get('id').'.answerArr.'.$questionArr[$qusNo]['id']), array('id'=> 'answer_'.$qusSerialNo.'_'.$questionArr[$qusNo]['id'], 'class' => 'form-control text-editor'))); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-counter text-right">
                                Maximum 4000 character; <span id="text-counter-<?php echo e('answer_'.$qusSerialNo.'_'.$questionArr[$qusNo]['id']); ?>"><?php echo e($answerChrCount); ?></span> remaining
                            </div>
                        </div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <?php else: ?>

            <?php if(!empty($questionArr[$qusNo]['match_item'])): ?>

            <div class="row">
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-1"><strong><?php echo e('Q. '.__('label.QUESTION_INSTRUCTION_FOR_MATCHING')); ?></strong></div>
                </div>                            
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <div class="form-group" style="padding-left:30px;">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center col-md-1"><?php echo e(__('label.SL_NO')); ?></th>
                                        <th class="text-center col-md-6"><?php echo e(__('label.COLUMN_A')); ?></th>
                                        <th class="text-center col-md-5"><?php echo e(__('label.COLUMN_B')); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $__currentLoopData = $questionArr[$qusNo]['match_item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                                            
                                    <tr id="question-<?php echo $qusSerialNo; ?>">
                                        <td class="text-center"><?php echo e($qusSerialNo); ?> </td>
                                        <td>
                                            <?php echo e($item['question']); ?> 

                                            <?php if(!empty($item['image'])): ?>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <a class="btn tooltips" title="<?php echo e(__('label.CLICK_TO_EXPAND_IMAGE')); ?>" href="<?php echo e(URL::to('/')); ?>/question/getImage/<?php echo e($item['image']); ?>" data-target="#image-loader" data-toggle="modal">
                                                        <img class="question-script-image-first-tab" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($item['image']); ?>" alt="<?php echo e($item['image']); ?>">
                                                    </a>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <?php if(!empty($item['note'])): ?>
                                            <span class="tooltips  question-node" title="<?php echo e(htmlspecialchars_decode(stripslashes($item['note']))); ?>" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e(Form::select('question['.$item['id'].']', $matchAnswer[$questionArr[$qusNo]['chunk_key']], Session::get('epeExam.'.Request::get('id').'.answerArr.'.$item['id']), array('class' => 'form-control js-source-states count-answer-select', 'data-selected-question'=>$qusSerialNo))); ?>

                                            <input type="hidden" name="question_id" value="<?php echo e($item['id']); ?>" />
                                        </td>
                                    </tr>
                                    <?php $qusSerialNo++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <div class="form-actions">
                    <div class="col-md-12 text-center">
                        <div class="col-md-12 text-center">
                            <?php echo e(Form::hidden('question_queue', $questionQueue, array('id' => 'question_queue') )); ?>

                            <?php if(!empty($mAnswerQueue)): ?>
                            <?php $__currentLoopData = $mAnswerQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunkKey => $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(Form::hidden('m_answer_queue['.$chunkKey.']', $content, array('id' => 'm_answer_queue_'.$chunkKey) )); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary" id="epeExamSubmit"><i class="fa fa-save"></i> <?php if($qusSerialNo ==$epe->obj_no_question): ?> <?php echo e(__('label.SUBMIT')); ?> <?php else: ?> <?php echo e(__('label.SUBMIT_AND_NEXT')); ?> <?php endif; ?> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo e(Form::close()); ?>

        <!-- END FORM-->
    </div>
</div>

<div class="modal fade" id="image-loader" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo e(__('label.PRESS_ESC_TO_CLOSE')); ?></h4>
            </div>
            <div class="modal-body text-center"> <img src="<?php echo e(URL::to('/public/assets/global/img/loading-spinner-grey.gif')); ?>" alt="" class="loading"> </div>
            <div class="modal-footer">
                <button type="button" class="btn green" data-dismiss="modal"><?php echo e(__('label.CLOSE')); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                <h4 class="modal-title text-danger"><?php echo e(__('label.TIME_EXPIRED')); ?></h4>
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
                <h4 class="modal-title text-danger"><?php echo e(__('label.REMINDER')); ?></h4>
            </div>
            <div class="modal-body">
                <span class="text-danger"><strong><?php echo e(__('label.REMINDER_TEXT')); ?></strong></span>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn green"><?php echo e(__('label.OK')); ?></button>
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
        <?php if(!empty($questionArr[$qusNo])): ?>
        <?php for($sl = 1;$sl<=(int)$epe->obj_no_question;$sl++): ?>

        <li>
            <a href="#question-<?php echo e($sl); ?>" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-<?php echo e($sl); ?>" style="padding:0; height: 20px;">
                <?php echo e($sl); ?>

            </a>
        </li>
        <!--                <a href="#question-<?php echo e($sl); ?>" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-<?php echo e($sl); ?>">
                            <?php echo e($sl); ?>

                        </a>-->
        <?php endfor; ?>
        <?php endif; ?>
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
        var countDownDate = new Date("<?php echo e($target->exam_date.' '.$target->objective_end_time); ?>").getTime();

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
                    url: "<?php echo e(URL::to('epeExam')); ?>",
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
        var countDownTimeQusWise = new Date("<?php echo e($target->exam_date.' '.$epeToQusList[$qusNo]['time']); ?>").getTime();

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
                    url: "<?php echo e(URL::to('objectiveTempSave')); ?>",
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
                    url: "<?php echo e(URL::to('epeExam')); ?>",
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
            var alertTitle = '<?php echo __("label.ARE_YOU_SURE_TO_SUBMIT_AND_NEXT"); ?>';

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
                        //url: "<?php echo e(URL::to('objectiveTempSave')); ?>",
                        url: "<?php echo e(URL::to('epeExam')); ?>",
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

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.epeExam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/epeExam/examOne.blade.php ENDPATH**/ ?>