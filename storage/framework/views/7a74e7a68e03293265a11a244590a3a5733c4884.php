<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> <?php echo __('label.VIEW_ASSIGNED_STUDENT'); ?></h4>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTables_wrapper" id="dataTable">
            <thead>
                <tr>
                    <th width="20%"> <?php echo e(__('label.NAME')); ?> </th>
                    <th> <?php echo e(__('label.REGION')); ?> </th>
                    <th> <?php echo e(__('label.CLUSTER')); ?> </th>
                    <th> <?php echo e(__('label.BRANCH')); ?> </th>
                    <th> <?php echo e(__('label.DEPARTMENT')); ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php if(!$studentArr->isEmpty()): ?>
                <?php
                $class = 'noStd';
                ?>
                <?php $__currentLoopData = $studentArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="odd gradeX">
                    <td ><?php echo e(!empty($student->rank->short_name) ? $student->rank->short_name : ''); ?> <?php echo e($student->first_name??''); ?> <?php echo e($student->last_name??''); ?> (<?php echo e($student->username??''); ?>)</td>
                    <td><?php echo e(!empty($student->region_name) ? $student->region_name : ''); ?></td>
                    <td><?php echo e(!empty($student->cluster_name) ? $student->cluster_name : ''); ?></td>
                    <td><?php echo e(!empty($student->branch_name) ? $student->branch_name: ''); ?></td>
                    <td><?php echo e(!empty($student->department_name) ? $student->department_name: ''); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>



<?php /**PATH C:\xampp\htdocs\oem\resources\views/examtostudent/getAssignedStudent.blade.php ENDPATH**/ ?>