<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-phone-square"></i> <?php echo __('label.STUDENT_DETAILS'); ?></h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                <th class='text-center vcenter'><?php echo app('translator')->get('label.PHOTO'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.DEPARTMENT'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.RANK'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.APPOINTMENT'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.BRANCH'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.REGION'); ?></th>
                <th class="text-center vcenter"><?php echo app('translator')->get('label.CLUSTER'); ?></th>
            </tr>
            <?php $i=1;
            ?>
            <?php $__currentLoopData = $studentDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i++); ?></td>
                <td class="text-center vcenter">
                    <?php if(!empty($result->photo)): ?>
                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($result->photo); ?>" alt="<?php echo e($result->employee_name); ?>">
                    <?php else: ?>
                    <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($result->employee_name); ?>">
                    <?php endif; ?>
                </td>
                <td><?php echo e($result->employee_name); ?> (<?php echo e($result->username); ?>)</td>
                <td><?php echo e($result->department_name); ?></td>
                <td><?php echo e($result->grade); ?></td>
                <td><?php echo e($result->designation_title); ?></td>
                <td><?php echo e($result->branch_name); ?></td>
                <td><?php echo e($result->region_name); ?></td>
                <td><?php echo e($result->cluster_name); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>


<?php /**PATH C:\xampp\htdocs\oem\resources\views/report/participationStatus/studentDetails.blade.php ENDPATH**/ ?>