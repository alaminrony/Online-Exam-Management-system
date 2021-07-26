<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" style="padding-left: 16px;"><span class="text-center btn green font-lg bold uppercase"><i class="fa fa-clock-o"></i> <span id="time"></span></span> <span class="bangladesh-time">Bangladesh Time (GMT+6.00 Hours)</span></h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <p><i class="fa fa-bell-o fa-fw"></i> <?php echo $message ?></p>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th class="text-center" colspan="3"> <?php echo e(__('label.SUPPORTED_BROWSER')); ?></th>
            </tr>
            <tr>
                <th width="40%"><?php echo e(__('label.SL')); ?> </th>
                <th width="40%"><?php echo e(__('label.BROWSER')); ?> </th>
                <th width="40%"><?php echo e(__('label.VERSION')); ?> </th>
            </tr>
            <?php $i=0; ?>
            <?php $__currentLoopData = $suppBrowser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $browserInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $i++; ?>
            <tr>
                <td width="40%"><?php echo e($i); ?> </td>
                <td width="40%"><?php echo e($browserInfo['browser']); ?> </td>
                <td width="40%"><?php echo e($browserInfo['version']); ?> </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    <div class="modal-footer">&nbsp;</div>
</div><?php /**PATH C:\xampp\htdocs\oem\resources\views/isspstudentactivity/versionErrorModal.blade.php ENDPATH**/ ?>