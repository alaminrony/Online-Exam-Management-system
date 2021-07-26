<?php $__env->startSection('data_count'); ?>

<div class="page-content">

    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- END PORTLET-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box">

                <div class="portlet-body form">
                    <?php echo e(Form::open(array('role' => 'form', 'url' => 'mockExam', 'class' => 'form-horizontal', 'id'=>'answerScript'))); ?>


                    <?php echo e(Form::hidden('mock_id', $mock->id, array('id'=>'mock_id'))); ?>

                    <?php echo e(Form::hidden('mock_mark_id', $target->id, array('id'=>'mock_mark_id'))); ?>

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
                                <div class="col-md-10 col-md-offset-1 exam-header"><?php echo e(__('label.SUBJECT').' : '.$mock->Subject->title. ' ('.__('label.MOCK_TEST').')'); ?></div>
                            </div>
                            <br />
                        </div>

                        <div class="row script-header-sticky">
                            <div class="col-md-12 hidden-sm hidden-xs visible-lg visible-md">
                                <?php if(!empty($questionArr)): ?>
                                <?php for($sl = 1;$sl<= (int)$mock->obj_no_question;$sl++): ?>
                                <a href="#question-<?php echo e($sl); ?>" class="btn btn-circle btn-icon-only selected-objective-question bg-grey bg-font-grey question-<?php echo e($sl); ?>">
                                    <?php echo e($sl); ?>

                                </a>
                                <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 col-md-offset-1 text-left">
                                <?php echo __('label.ANSWERED').' : <span id="answered">0</span> out of '.$mock->obj_no_question; ?><!-- __('label.DURATION').' : '.$durationHours.$durationMin-->
                            </div>
                            <div class="col-md-2 text-left"><?php echo __('label.MARK').' : '.$target->total_mark; ?></div>
                            <div class="col-md-2 col-md-offset-1 text-right"><strong><span class="text-red" id="counter"></span></strong></div>
                        </div>

                    </div>

                    <div class="form-body" id="questionBody">
                        <?php if(!empty($questionArr)): ?>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $questionArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php if($question['type_id'] != '6'): ?>
                        <div class="row" id="<?php echo e($question['id']); ?>">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group" id="question-<?php echo $i; ?>">
                                    <strong>Q<?php echo $i.'. '.$question['question']; ?></strong>
                                    <?php if(!empty($question['note'])): ?>
                                    <span class="tooltips question-node" title="<?php echo $question['note']; ?>" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                    <?php endif; ?>
                                    <input type="hidden" name="question_id[]" value="<?php echo e($question['id']); ?>" />
                                </div>

                                <?php if(!empty($question['document'])): ?>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <?php if(!empty($question['content_type_id']) && $question['content_type_id'] == '1'): ?>
                                        <a class="btn tooltips" title="<?php echo e(__('label.CLICK_TO_EXPAND_IMAGE')); ?>" href="<?php echo e(asset('public/uploads/questionBank/image/'.$question['document'])); ?>" data-target="#image-loader" data-toggle="modal">
                                            <img class="question-script-image-first-tab" src="<?php echo e(asset('public/uploads/questionBank/image/'.$question['document'])); ?>" alt="<?php echo e($question['document']); ?>"> 
                                        </a>
                                        <?php elseif(!empty($question['content_type_id']) && $question['content_type_id'] == '2'): ?>
                                        <audio controls>
                                            <source src="<?php echo e(asset('public/uploads/questionBank/audio/'.$question['document'])); ?>" alt="<?php echo e($question['document']); ?>" type="audio/mpeg">
                                        </audio>
                                        <?php elseif(!empty($question['content_type_id']) && $question['content_type_id'] == '3'): ?>
                                        <video controls width="50%">
                                            <source src="<?php echo e(asset('public/uploads/questionBank/video/'.$question['document'])); ?>" alt="<?php echo e($question['document']); ?>" type="video/mp4">
                                        </video>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="form-group" style="padding-left:30px;">

                                    <?php if($question['type_id'] == '1'): ?>

                                    <div class="mt-radio-list">

                                        <?php for($j=1;$j<=4;$j++): ?>
                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[<?php echo e($question['id']); ?>]" id="opt_<?php echo e($j.'_'.$question['id']); ?>" value="<?php echo e($j); ?>" data-selected-question="<?php echo e($i); ?>"> <?php echo e($question['opt_'.$j]); ?>

                                            <span></span>
                                        </label>
                                        <?php endfor; ?>
                                    </div>

                                    <?php elseif($question['type_id'] == '3'): ?>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" autocomplete="off" name="question[<?php echo e($question['id']); ?>]" id="txt_<?php echo e($question['id']); ?>" class="form-control input-inline input-medium count-answer-text" placeholder="<?php echo e(__('label.TYPE_YOUR_ANSWER_HERE')); ?>" data-selected-question="<?php echo e($i); ?>">
                                        </div>
                                    </div>

                                    <?php elseif($question['type_id'] == '5'): ?>

                                    <div class="mt-radio-list">

                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[<?php echo e($question['id']); ?>]" id="btnTrue_<?php echo e($question['id']); ?>" value="1" data-selected-question="<?php echo e($i); ?>"> <?php echo e(__('label.TRUE')); ?>

                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            <input type="radio" class="count-answer-radio" name="question[<?php echo e($question['id']); ?>]" id="btnFalse_<?php echo e($question['id']); ?>" value="0" data-selected-question="<?php echo e($i); ?>"> <?php echo e(__('label.FALSE')); ?>

                                            <span></span>
                                        </label>
                                    </div>


                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php $i++; ?>

                        <?php else: ?>

                        <?php if(!empty($question['match_item'])): ?>
                        <div class="row">
                            <div class="form-group" id="question-<?php echo $i; ?>">
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
                                                <?php $__currentLoopData = $question['match_item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                                            
                                                <tr id="question-<?php echo $i; ?>">
                                                    <td class="text-center"><?php echo e($i); ?> </td>
                                                    <td>
                                                        <?php echo e($item['question']); ?>

                                                        <?php if(!empty($item['note'])): ?>
                                                        <span class="tooltips  question-node" title="<?php echo e($item['note']); ?>" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                                        <?php endif; ?>

                                                        <?php if(!empty($item['image'])): ?>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <a class="btn tooltips" title="<?php echo e(__('label.CLICK_TO_EXPAND_IMAGE')); ?>" href="<?php echo e(URL::to('/')); ?>/question/getImage/<?php echo e($item['image']); ?>" data-target="#image-loader" data-toggle="modal">
                                                                    <img class="question-script-image-first-tab" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($item['image']); ?>" alt="<?php echo e($item['image']); ?>"> 
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(Form::select('question['.$item['id'].']', $matchAnswer[$question['chunk_key']], null, array('class' => 'form-control js-source-states count-answer-select',  'data-selected-question'=>$i))); ?>

                                                        <input type="hidden" name="question_id[]" value="<?php echo e($item['id']); ?>" />
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary" id="mockExamSubmit"><i class="fa fa-save"></i> <?php echo e(__('label.SUBMIT')); ?></button>
                            </div>
                        </div>
                    </div>

                    <?php echo e(Form::close()); ?>

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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-danger"><?php echo e(__('label.TIME_EXPIRED')); ?></h4>
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
        <?php if(!empty($questionArr)): ?>
        <?php for($sl = 1;$sl<=(int)$mock->obj_no_question;$sl++): ?>

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
    var countDownDate = new Date("<?php echo e($target->exam_date.' '.$target->end_time); ?>").getTime();
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
                url: "<?php echo e(URL::to('mockExam')); ?>",
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
                window.location = "<?php echo e(URL::to('/mockExam/examresult?mock_id=" + mockId + "')); ?>";

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

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.epeExam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/mockExam/exam.blade.php ENDPATH**/ ?>