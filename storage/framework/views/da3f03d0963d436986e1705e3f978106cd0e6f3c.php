<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" style="padding-left: 16px;"><span class="text-center btn green font-lg bold uppercase"><i class="fa fa-clock-o"></i> <span id="time"></span></span> <span class="bangladesh-time">Bangladesh Time (GMT+6.00 Hours)</span></h4>
    </div>
    <div class="modal-body">
        <div class="pricing-content-1">
            <div class="col-md-12">
                <?php if($epeExamInfoArr->submitted == 2): ?>
                <div class="note note-warning margin-top-20">
                    <h3><?php echo e(__('label.EPE_ALREADY_SUBMITTED')); ?></h3>
                    <p><?php echo e(__('label.YOU_HAVE_ALREADY_SUBMITTED_ANSWERS')); ?> for  <?php echo e($epeExamInfoArr->title); ?></p>
                </div>
                <?php elseif(($epeExamInfoArr->set_obj_question > 0)): ?>
                <div class="price-column-container border-active">
                    <div class="price-table-content epe-exam">

                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.SUBJECT').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($epeExamInfoArr->Subject->title); ?></div>
                        </div>
                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.EXAM_TITLE').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($epeExamInfoArr->title); ?></div>
                        </div>
                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.START_TIME').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($epeExamInfoArr->start_time); ?></div>
                        </div>
                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.END_TIME').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($epeExamInfoArr->end_time); ?></div>
                        </div>
                        <?php
                        $objExamDuration = ($epeExamInfoArr->obj_duration_hours * 60) + $epeExamInfoArr->obj_duration_minutes;
                        $subExamDuration = ($epeExamInfoArr->sub_duration_hours * 60) + $epeExamInfoArr->sub_duration_minutes;

                        //Get Total Duration
                        $totalMinutes = $objExamDuration + $subExamDuration;
                        $hours = floor($totalMinutes / 60);
                        $minutes = ($totalMinutes % 60);

                        $durationHours = ($hours > 0) ? ($hours > 1) ? $hours . ' hours ' : $hours . ' hour ' : '';
                        $durationMinutes = ($minutes > 0) ? $minutes . ' minutes ' : '';
                        $durationTime = $durationHours . $durationMinutes;

                        //Get objective duration
                        $objectiveHouese = ($epeExamInfoArr->obj_duration_hours > 0) ? ($epeExamInfoArr->obj_duration_hours > 1) ? $epeExamInfoArr->obj_duration_hours . ' hours ' : $epeExamInfoArr->obj_duration_hours . ' hour ' : '';
                        $objectiveMinutes = ($epeExamInfoArr->obj_duration_minutes > 0) ? $epeExamInfoArr->obj_duration_minutes . ' minutes ' : '';

                        //Get subjective duration
                        $subjectiveHouese = ($epeExamInfoArr->sub_duration_hours > 0) ? ($epeExamInfoArr->sub_duration_hours > 1) ? $epeExamInfoArr->sub_duration_hours . ' hours ' : $epeExamInfoArr->sub_duration_hours . ' hour ' : '';
                        $subjectiveMinutes = ($epeExamInfoArr->sub_duration_minutes > 0) ? $epeExamInfoArr->sub_duration_minutes . ' minutes ' : '';
                        ?>

                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.TOTAL_DURATION').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($durationTime); ?></div>
                        </div>
                        <div class="row mobile-padding">
                            <div class="col-xs-5 text-right mobile-padding">
                                <?php echo e(__('label.TOTAL_MARK').' : '); ?>

                            </div>
                            <div class="col-xs-7 text-left mobile-padding"><?php echo e($epeExamInfoArr->total_mark); ?></div>
                        </div>

                    </div>
                    <div class="arrow-down arrow-grey"></div>
                    <div class="price-table-footer">
                        <?php if($presentDatetime > $minusExamEndTime && empty($checkEmployeeAttendend)): ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <p><i class="fa fa-bell-o fa-fw"></i><span><?php echo app('translator')->get('label.YOU_HAVE_NO_TIME_TO_ATTEND_EXAM'); ?></span></p>
                        </div>
                        <?php else: ?>
                        <a href="<?php echo e(URL::to('disclaimer?id='.$epeExamInfoArr->id)); ?>" class="btn green price-button btn-circle uppercase"><i class="fa fa-play-circle" ></i> <?php echo e(__('label.START').' : '); ?></a>&nbsp;
                        <?php endif; ?>
                        <button type="button" data-dismiss="modal" class="btn btn-circle price-button grey-salsa uppercase"><i class="fa fa-close" ></i> <?php echo e(__('label.CLOSE').' : '); ?></button>
                    </div>
                </div>
                <?php else: ?> 
                <div class="note note-warning margin-top-20">
                    <h3><?php echo e(__('label.NO_QUESTION_FOUND')); ?></h3>
                    <p><?php echo e(__('label.NO_QUESTION_FOUND_FOR')); ?> <?php echo e($epeExamInfoArr->title); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\oem\resources\views/isspstudentactivity/epeSummary.blade.php ENDPATH**/ ?>