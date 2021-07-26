<div class="pricing-content-1">
    <div class="col-md-12">
        <?php if($mockExamInfoArr->total_question > 0): ?>
            <div class="price-column-container border-active">
                <div class="price-table-head bg-green">
                    <h2 class="no-margin"><?php echo e($mockExamInfoArr->title); ?></h2>
                </div>
                <div class="price-table-content epe-exam">
                    <div class="row mobile-padding">
                        <div class="col-xs-5 text-right mobile-padding">
                           <?php echo e(__('label.SUBJECT').' : '); ?>

                        </div>
                        <div class="col-xs-7 text-left mobile-padding"><?php echo e($mockExamInfoArr->subject_title); ?></div>
                    </div>
                    <div class="row mobile-padding">
                        <div class="col-xs-5 text-right mobile-padding">
                           <?php echo e(__('label.DURATION').' : '); ?>

                        </div>
                        <div class="col-xs-7 text-left mobile-padding"><?php echo e(($mockExamInfoArr->duration_hours > 0) ? $mockExamInfoArr->duration_hours.' hour' : ''); ?> <?php echo e(($mockExamInfoArr->duration_minutes > 0) ? $mockExamInfoArr->duration_minutes.' minutes' : ''); ?></div>
                    </div>
                    <div class="row mobile-padding">
                        <div class="col-xs-5 text-right mobile-padding">
                           <?php echo e(__('label.NO_OF_ATTEMPT').' : '); ?>

                        </div>
                        <div class="col-xs-7 text-left mobile-padding"><?php echo e($mockExamInfoArr->total_attempt); ?></div>
                    </div>
                    <div class="row mobile-padding">
                        <div class="col-xs-5 text-right mobile-padding">
                           <?php echo e(__('label.TOTAL_QUESTIONS').' : '); ?>

                        </div>
                        <div class="col-xs-7 text-left mobile-padding"><?php echo e($mockExamInfoArr->obj_no_question); ?></div>
                    </div>
                    <?php if(!empty($epeInfo->total_mark)): ?>
                    <?php 
                    $passMark = ceil(($epeInfo->pass_mark * $mockExamInfoArr->obj_no_question)/$epeInfo->total_mark);
                    ?>
                    <div class="row mobile-padding">
                         <div class="col-md-5 text-right mobile-padding">
                           <?php echo e(__('label.TOTAL_MARK').' : '); ?>

                        </div>
                        <div class="col-md-7 text-left mobile-padding"><?php echo e($mockExamInfoArr->obj_no_question); ?></div>
                    </div>
                    <div class="row mobile-padding">
                         <div class="col-md-5 text-right mobile-padding">
                           <?php echo e(__('label.PASSING_MARK').' : '); ?>

                        </div>
                        <div class="col-md-7 text-left mobile-padding"><?php echo e($passMark); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="arrow-down arrow-grey"></div>
                <div class="price-table-footer">
                    <a href="<?php echo e(URL::to('mockExam?id='.$mockExamInfoArr->id)); ?>" class="btn green price-button btn-circle uppercase"><i class="fa fa-play-circle" ></i> Start</a>&nbsp;
                    <button type="button" data-dismiss="modal" class="btn btn-circle price-button grey-salsa uppercase"><i class="fa fa-close" ></i> Close</button>
                </div>
            </div>
        <?php else: ?> 
            <div class="note note-warning margin-top-20">
                <h3><?php echo e(__('label.NO_QUESTION_FOUND')); ?></h3>
                <p><?php echo e(__('label.NO_QUESTION_FOUND_FOR')); ?> <?php echo e($mockExamInfoArr->title); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\oem\resources\views/isspstudentactivity/show_play_box.blade.php ENDPATH**/ ?>