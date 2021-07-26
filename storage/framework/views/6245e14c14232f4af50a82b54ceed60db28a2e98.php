<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i><?php echo app('translator')->get('label.VIEW_GRADING_SYSTEM'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('label.SL'); ?></th>
                            <th><?php echo app('translator')->get('label.MARKS'); ?> (%)</th>
                            <th><?php echo app('translator')->get('label.GRADE'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($grades->isNotEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="contain-center">
                            <td><?php echo e($i++); ?></td>
                            <?php if($grade->to_mark < '100'): ?>
                            <td><?php echo e($grade->from_mark); ?> - < <?php echo e($grade->to_mark); ?></td>
                            <?php else: ?>
                            <td><?php echo e($grade->from_mark); ?> - <?php echo e($grade->to_mark); ?></td>
                            <?php endif; ?>
                            <td><?php echo e($grade->grade); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9"><?php echo app('translator')->get('label.EMPTY_DATA'); ?></td>
                        </tr>
                        <?php endif; ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/report/gradingSystem/index.blade.php ENDPATH**/ ?>