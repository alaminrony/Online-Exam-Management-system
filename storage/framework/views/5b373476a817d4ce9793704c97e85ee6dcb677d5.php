<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i><?php echo app('translator')->get('label.VIEW_PASSWORD_SETUP'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('passwordSetup/'.$target->id.'/edit')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.EDIT_PASSWORD_SETUP'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table  table-bordered table-hover">
                    <tr>
                        <th><?php echo app('translator')->get('label.MAX_LENGTH'); ?></th>
                        <td><?php echo e($target->maximum_length); ?> <?php echo app('translator')->get('label.CHARACTERS'); ?></td>
                    <tr/>
                    <tr>
                        <th><?php echo app('translator')->get('label.MIN_LENGTH'); ?></th>
                        <td><?php echo e($target->minimum_length); ?> <?php echo app('translator')->get('label.CHARACTERS'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.SPECIAL_CHARECTER'); ?></th>
                        <td>
                            <?php if($target->special_character =='1'): ?>
                            <span class="label label-success"><?php echo e(__('label.YES')); ?></span>
                            <?php else: ?>
                            <span class="label label-danger"><?php echo e(__('label.NO')); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.LOWER_CASE'); ?></th>
                        <td>
                            <?php if($target->lower_case =='1'): ?>
                            <span class="label label-success"><?php echo e(__('label.YES')); ?></span>
                            <?php else: ?>
                            <span class="label label-danger"><?php echo e(__('label.NO')); ?></span>
                            <?php endif; ?>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.UPPER_CASE'); ?></th>
                        <td>
                            <?php if($target->upper_case =='1'): ?>
                            <span class="label label-success"><?php echo e(__('label.YES')); ?></span>
                            <?php else: ?>
                            <span class="label label-danger"><?php echo e(__('label.NO')); ?></span>
                            <?php endif; ?>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.EXPEIRED_OF_PASSWORD'); ?></th>
                        <td><?php echo e($target->expeired_of_password); ?> <?php echo app('translator')->get('label.DAYS'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.SPACE_NOT_ALLOWED'); ?></th>
                        <td><?php echo e($target->space_not_allowed  == '1' ? __('label.NOT_ALLOWED') :''); ?></td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/passwordSetup/index.blade.php ENDPATH**/ ?>