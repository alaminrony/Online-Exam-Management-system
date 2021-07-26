<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.SUBJECT_NAME'); ?> :<span class="required"> *</span></label>
    <div class="col-md-8">
        <?php echo e(Form::text('subject_title', ($taeInfoObjArr) ? $taeInfoObjArr->subject_title : null, array('id'=> 'epeSubjectName', 'class' => 'form-control', 'readonly' => true))); ?>

        <span class="help-block text-danger"> <?php echo e($errors->first('subject_title')); ?></span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.START_DATE_TIME'); ?> :<span class="required"> *</span></label>
    <div class="col-md-7">
        <div class="input-group date mock_test_datetime">
            <?php echo e(Form::text('start_at', ($taeInfoObjArr) ? $taeInfoObjArr->publish_date : null, array('id'=> 'EpeStartDateTime', 'class' => 'form-control', 'placeholder' => 'Enter Start Date Time', 'size' => '16', 'readonly' => true))); ?>

            <span class="input-group-btn">
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Start Date Time Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('EpeStartDateTime');" remove="EpeStartDateTime"></i></span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.END_DATE_TIME'); ?> :<span class="required"> *</span></label>
    <div class="col-md-7">
        <div class="input-group date mock_test_datetime">
            <?php echo e(Form::text('end_at', ($taeInfoObjArr) ? $taeInfoObjArr->deadline : null, array('id'=> 'mockTestEndDateTime', 'class' => 'form-control', 'placeholder' => 'Enter End Date Time', 'size' => '16', 'readonly' => true))); ?>

            <span class="input-group-btn">
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <span data-tooltip="tooltip" class="text-danger date-remove tooltips" title="Result Publish Date Time Remove">&nbsp;<i class="fa fa-remove remove-date" onclick="remove_date('mockTestEndDateTime');" remove="mockTestEndDateTime"></i></span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.TITLE'); ?> :<span class="required"> *</span></label>
    <div class="col-md-8">
        <?php echo e(Form::text('title', null, array('id'=> 'mockTestTitle', 'class' => 'form-control', 'placeholder' => 'Enter Mock Test Title'))); ?>

        <span class="help-block text-danger"> <?php echo e($errors->first('title')); ?></span>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.DURATION'); ?> :<span class="required"> *</span></label>
    <div class="col-md-2">
        <?php echo e(Form::select('duration_hours', $hoursList,null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationHours'))); ?> 
    </div>
	<label class="col-md-1 control-label durations"><?php echo app('translator')->get('label.HOURS'); ?></label>
    
    <div class="col-md-2">
        <?php echo e(Form::select('duration_minutes', $minutesList, null, array('class' => 'form-control input-xsmall js-source-states', 'id' => 'durationMinutes'))); ?>

    </div>
	<label class="col-md-1 control-label durations"><?php echo app('translator')->get('label.MINUTES'); ?></label>
    
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.TOTAL_NUMBER_OF_QUESTIONS'); ?> :<span class="required"> *</span></label>
    <div class="col-md-8">
        <?php echo e(Form::number('obj_no_question', null, array('id'=> 'mockTestObjNoQuestion', 'min' => 0, 'maxlength' => 3, 'class' => 'form-control only-number', 'placeholder' => 'Enter Total Number of Questions', 'required' => 'true'))); ?>

        <span class="help-block"><?php echo e($objectiveQuestionCount); ?> <?php echo app('translator')->get('label.QUESTIONS_AVAIABLE_AT_QUESTION_BANK'); ?></span>
        <?php echo e(Form::hidden('total_objective_questions', $objectiveQuestionCount, array('id' => 'total_objective_questions'))); ?>

    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.QUESTION_AUTO_SELECTION'); ?> :<span class="required">*</span></label>
    <div class="col-md-8">
        <div class="md-checkbox">
            <input type="checkbox" name="obj_auto_selected" class="checkboxes" id="obj_auto_selected" value="1">
            <label for="obj_auto_selected">
                <span class="inc"></span>
                <span class="check"></span>
                <span class="box"></span> <?php echo app('translator')->get('label.KEEP_BLANK_FOR_MANUAL_SELECTION'); ?></label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
    <div class="col-md-8">
        <?php echo e(Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), 1, array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))); ?>

        <span class="help-block text-danger"><?php echo e($errors->first('status')); ?></span>
        <?php 
        $id = null;
        ?>
        <?php echo e(Form::hidden('id', $id, array('id' => 'idMockTest'))); ?>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").tooltip({ selector: '[data-tooltip=tooltip]' });
    });
</script>

<?php /**PATH C:\xampp\htdocs\oem\resources\views/mocktest/show_mock_test_info.blade.php ENDPATH**/ ?>