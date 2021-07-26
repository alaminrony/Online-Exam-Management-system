<?php $__env->startSection('data_count'); ?>
<?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-graduation-cap"></i><?php echo e(__('label.SUBMISSION_COMPLETE')); ?> </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="note note-warning">
                        <h3><?php echo e(__('label.THANK_YOU')); ?></h3>
                        <br/>
                        <h3>Your Exam Script for <div class="subjective-complete"><?php echo e(!empty($epeInfo->title) ? $epeInfo->title : ''); ?></div>has been submitted successfully</h3>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="<?php echo e(URL::to('isspstudentactivity/myepe')); ?>">
                        <button type="button" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-left" data-spinner-color="#333">
                            <span class="ladda-label">
                                <i class="icon-arrow-left"></i> <?php echo e(__('label.GO_TO_MY_EPE')); ?></span>
                            <span class="ladda-spinner"></span>
                        </button>
                        <a/>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        //Clear Local Storage
        localStorage.clear();
    </script> 
    <?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/epeExam/subjectiveComplete.blade.php ENDPATH**/ ?>