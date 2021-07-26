<?php $__env->startSection('data_count'); ?>

<div class="page-content">

    <div class="portlet box">

        <div class="portlet-body form">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'saveSubjectiveMarking/'. $epeMarkInfo->id , 'class' => 'form-horizontal', 'id'=>'answerScript'))); ?>


            <?php echo e(Form::hidden('epe_id', $epeInfo->id, array('id'=>'epe_id'))); ?>

            <?php echo e(Form::hidden('epe_mark_id', $epeMarkInfo->id, array('id'=>'epe_mark_id'))); ?>


            <div class="form-body text-center navbar-fixed-top" style="padding: 0px; background: #fff;">

                <div class="row">
                    <div class="col-md-12"><h2><?php echo e($epeInfo->title); ?></h2></div>
                </div>
                <?php
                if ($epeInfo->type == '1') {
                    
                } else if (in_array($epeInfo->type, array('2', '3'))) {

                    foreach ($epeInfo->epeDetail as $key => $item) {
                        
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-1 text-left"><?php echo e(__('label.EXAM_DATE').' : '.Helper::printDate($epeMarkInfo->exam_date)); ?></div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-md-offset-1 text-left"><?php echo e(__('label.SUBJECT').' : '.$epeInfo->Subject->title); ?></div>
                    <div class="col-md-2 text-left"><?php echo e(__('label.TOTAL_MARK').' : '.$epeMarkInfo->total_mark); ?></div>
                    <div class="col-md-2 col-md-offset-1 text-right"><?php echo e(__('label.DURATION').' : '.$epeInfo->obj_duration_hours.'H:'.$epeInfo->obj_duration_minutes.'M'); ?></div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-md-offset-1 text-left"><?php echo e(__('label.NO_OF_QUESTION').' : '.$epeInfo->obj_no_question); ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php echo e(__('label.SUBMISSION_DATELINE')); ?> : <strong><?php echo e($epeInfo->submission_deadline); ?></strong>&nbsp;|
                        <?php echo e(__('label.RESULT_PUBLISHED_DATE')); ?> : <strong><?php echo e($epeInfo->result_publish); ?></strong>
                    </div>
                </div>
                <hr />
            </div>

            <div class="form-body" id="questionBody" style="margin-top: 170px">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <span class="btn btn-success btn-sm yellow-soft"> 
                            <i class='fa fa-file-text'></i> <?php echo e(__('label.SUBJECTIVE')); ?> : <?php echo e($epeSubSum->total_mark); ?>

                        </span>
                        &nbsp;&nbsp;&nbsp;
                        <a class="tooltips question_answer_sheet" data-toggle="modal" data-target="#question_answer_sheet" data-mark-id="<?php echo e($epeMarkInfo->id); ?>" href="#question_answer_sheet" id="questionAnswerSheet<?php echo e($epeMarkInfo->id); ?>" title="<?php echo e(__('label.CLICK_TO_VIEW_QUESTION_AND_ANSWER_SHEET')); ?>" data-container="body" data-trigger="hover" data-placement="top">
                            <span class="btn btn-success btn-sm green "> 
                                <i class='fa fa-list-ul'></i> <?php echo e(__('label.OBJECTIVE')); ?> : <?php echo e($epeObjSum->total_mark); ?>

                            </span>
                        </a>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            <?php if(!empty($qusSubArr)): ?>
                            <?php $j = 1; ?>
                            <?php $__currentLoopData = $qusSubArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($answerArr[$question['question_id']])): ?>
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="subjective-question">
                                        <?php echo e($j.') '.$question['question']); ?>

                                        <?php if(!empty($question['note'])): ?>
                                        <span class="tooltips question-node" title="<?php echo e($question['note']); ?>" data-placement="top"><i class="btn btn-xs btn-circle dark fa fa-info"></i> </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="subjective-question"><?php echo e($question['mark']); ?></div>
                                </div>
                            </div>
                            <?php if(!empty($question['image'])): ?>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="btn tooltips" title="<?php echo e(__('label.CLICK_TO_EXPAND_IMAGE')); ?>" href="<?php echo e(URL::to('/')); ?>/question/getImage/<?php echo e($question['image']); ?>" data-target="#image-loader" data-toggle="modal">
                                        <img class="question-script-image-first-tab" src="<?php echo e(URL::to('/')); ?>/public/uploads/questionBank/<?php echo e($question['image']); ?>" alt="<?php echo e($question['image']); ?>"> 
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="note note-info">
                                        <h4 class="block"><?php echo e(__('label.ANSWER')); ?> : </h4>
                                        <?php echo $answerArr[$question['question_id']]->submitted_answer; ?> 
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <?php if($epeMarkInfo->ds_status == 2): ?>
                                <div class="col-md-12"><?php echo e(__('label.MARKS').' : '.$answerArr[$question['question_id']]->ds_mark); ?></div>
                                <?php else: ?>
                                <label class="col-md-1 control-label"><?php echo e(__('label.MARK')); ?> : </label>
                                <div class="col-md-2">
                                    <div class="input-group">                                                       
                                        <?php echo e(Form::text('ds_mark['.$answerArr[$question['question_id']]->id.']', $answerArr[$question['question_id']]->ds_mark, array('data-max-mark'  => $question['mark'],  'class' => 'form-control interger-decimal-only ds-mark', 'autocomplete'=>'off' ,'id' => 'ds_mark_'.$answerArr[$question['question_id']]->id))); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php if($epeMarkInfo->ds_status == 2): ?>
                                <div class="col-md-12"><?php echo e(__('label.REMARKS').' : '.$answerArr[$question['question_id']]->ds_remarks); ?></div>
                                <?php else: ?>
                                <label class="col-md-1 control-label"><?php echo e(__('label.REMARKS')); ?> : </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <?php echo e(Form::textarea('ds_remarks['.$answerArr[$question['question_id']]->id.']', $answerArr[$question['question_id']]->ds_remarks , array('rows' => '2', 'class' => 'form-control', 'id' => 'ds_remarks_'.$answerArr[$question['question_id']]->id))); ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php $j++; ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-actions">
                        <div class="col-md-12 text-center">
                            <?php if($epeMarkInfo->ds_status == 2): ?>
                            <div class="text-danger margin-bottom-10"><?php echo e(__('label.THIS_ANSWER_SCRIPT_HAS_BEEN_ASSESSED_AND_LOCKED').' by '.$lockerInfo->Rank->short_name.' '.$lockerInfo->first_name.' '.$lockerInfo->last_name.' ('.$lockerInfo->username.') at '.$epeMarkInfo->ds_lock_at); ?></div>
                            <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="submit" value="save" id="save"><i class="fa fa-save"></i> <?php echo e(__('label.SAVE_AS_DRAFT')); ?></button>&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary" name="submit" value="lock" id="lock"><i class="fa fa-lock"></i> <?php echo __('label.LOCK_AND_SUBMIT'); ?></button>&nbsp;&nbsp;
                            <?php endif; ?>
                            <a href="<?php echo e(URL::to('epedsmarking?epe_id='.$epeInfo->id)); ?>"><button type="button" class="btn btn-default"><i class="fa fa-close"></i> <?php echo e(__('label.CANCEL')); ?></button></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo e(Form::close()); ?>

            <!-- END FORM-->
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
<div class="modal fade bs-modal-lg" id="question_answer_sheet" tabindex="-1" role="dialog" aria-hidden="true">
    <div id="display_question_answer_sheet"></div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        //Tooltip, activated by hover event
        $(".tooltips").tooltip({html: true});
        //They can be chained like the example above (when using the same selector).

    });

    $('.ds-mark').each(function () {
        $(this).keyup(function (e) {
            var max = parseFloat($(this).attr('data-max-mark'));
            var number = parseFloat($(this).val());

            if (number > max) {
                swal('Maximum mark can be ' + max + ' for this input!');
                $(this).val('');
            }

        });
    });

    $('#lock').click(function (e) {
        var c = confirm('Are you sure you want to lock this answer script?');
        return c;
    });

    //This function use for view question ans answer sheet
    $(document).on('click', '.question_answer_sheet', function (e) {
        e.preventDefault();
        var epeMarkId = $(this).data('mark-id'); // get id of clicked row
        $('#display_question_answer_sheet').html(''); // leave this div blank
        $.ajax({
            url: "<?php echo e(URL::to('epedsmarking/questionanswersheet/')); ?>",
            type: "get",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                epe_mark_id: epeMarkId
            },
            cache: false,
            contentType: false,
            success: function (response) {
                $('#display_question_answer_sheet').html(''); // blank before load.
                $('#display_question_answer_sheet').html(response.html); // load here
                $(".tooltips").tooltip({html: true});
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
                if (jqXhr.status == 500) {
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

</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.epeExam', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/epeDsMarking/subjectiveMarking.blade.php ENDPATH**/ ?>