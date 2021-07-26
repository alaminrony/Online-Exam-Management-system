<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.CONFIGURATION'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('configuration/1/edit')); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('label.UPDATE_CONFIGURATION'); ?> </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th><?php echo app('translator')->get('label.ADMIN_EMAIL'); ?></th>
                        <td><?php echo $targetArr->admin_email; ?></td>
                    </tr>
                    
                    <tr>
                        <th><?php echo app('translator')->get('label.ABOUT_US'); ?></th>
                        <td><?php echo $targetArr->about_us; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo app('translator')->get('label.HISTORY'); ?></th>
                        <td><?php echo $targetArr->history; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/configuration/index.blade.php ENDPATH**/ ?>