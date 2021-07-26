<?php $__env->startSection('data_count'); ?>

<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i><?php echo e(__('label.MOCK_TEST_EXAM_RESULT')); ?> </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="note note-warning">
                        <h3>
                            <?php
                            if ($mockInfo->pass == 1) {
                                echo __("label.YOU_HAVE_SUCCESSFULLY_PASSED_THIS_MOCK");
                            } else if ($mockInfo->pass == 2) {
                                echo __("label.SORRY_YOU_ARE_FAILED_THIS_MOCK");
                            }
                            $to_time = strtotime($mockInfo->exam_date . " " . $mockInfo->start_time);
                            $from_time = strtotime($mockInfo->submission_time);
                            $totalExamMin = round(abs($to_time - $from_time) / 60);
                            ?>
                        </h3>
                        <h3><?php echo e(__('label.YOUR_CORRECT_ANSWER_IS')); ?> <strong><?php echo e($mockInfo->no_correct_answer); ?></strong> <?php echo e(__('label.OUT_OF')); ?> <strong><?php echo e($mockInfo->no_of_question); ?></strong></h3>
                        <h3><?php echo e(__('label.FOR_THIS_ATTEMPT_YOU_HAVE_TAKEN')); ?> <strong><?php echo e($totalExamMin); ?> <?php echo e(__('label.MIN')); ?></strong> <?php echo e(__('label.OUT_OF')); ?> <strong><?php echo e(($mockInfo->duration_hours > 0) ? $mockInfo->duration_hours.' Hour' : ''); ?> <?php echo e(($mockInfo->duration_minutes > 0 ? $mockInfo->duration_minutes.' Min' : '')); ?></strong> </strong></h3>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="<?php echo e(URL::to('isspstudentactivity/mymocktest')); ?>">
                        <button type="button" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-left" data-spinner-color="#333">
                            <span class="ladda-label">
                                <i class="icon-arrow-left"></i> <?php echo e(__('label.ANOTHER_TEST')); ?></span>
                            <span class="ladda-spinner"></span>
                        </button>
                        <a/>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/mockExam/examresult.blade.php ENDPATH**/ ?>