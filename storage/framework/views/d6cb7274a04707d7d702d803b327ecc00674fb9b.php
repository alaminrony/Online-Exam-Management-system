<?php if(!empty($employeeTypeList)): ?>
<div class="form-group">
    <label class="col-md-4 control-label" for="employeeType"><?php echo app('translator')->get('label.SELECT_EMPLOYEE_TYPE'); ?> :</label>
    <div class="col-md-8">
        <?php echo e(Form::select('employee_type', $employeeTypeList, Request::get('employee_type'), array('class' => 'form-control js-source-states', 'id' => 'employeeType'))); ?>

        <span class="help-block text-danger"><?php echo e($errors->first('employee_type')); ?></span>
    </div>
</div>
<?php endif; ?>
<?php if(!empty($supervisorList)): ?>
<div class="form-group">
    <label class="col-md-4 control-label" for="userId"><?php echo app('translator')->get('label.SELECT_SUPERVISOR'); ?> :</label>
    <div class="col-md-8">
        <?php echo e(Form::select('user_id', $supervisorList, Request::get('user_id'), array('class' => 'form-control js-source-states', 'id' => 'userId'))); ?>

        <span class="help-block text-danger"><?php echo e($errors->first('user_id')); ?></span>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/user/ShowData.blade.php ENDPATH**/ ?>