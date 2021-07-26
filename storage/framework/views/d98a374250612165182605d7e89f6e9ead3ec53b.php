<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i><?php echo app('translator')->get('label.UPDATE_PASSWORD_SETUP'); ?> </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::open(['route' => array('passwordSetup.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal'])); ?>

            <?php echo csrf_field(); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="maximumLength"><?php echo app('translator')->get('label.MAX_LENGTH'); ?> :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo e(Form::text('maximum_length',!empty($target->maximum_length)? $target->maximum_length : '', array('id'=> 'maximumLength', 'class' => 'form-control'))); ?>

                                    <span class="input-group-addon"><?php echo app('translator')->get('label.CHARACTERS'); ?></span>
                                </div>
                                <span class="help-block text-danger"> <?php echo e($errors->first('maximum_length')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="minimumLength"><?php echo app('translator')->get('label.MIN_LENGTH'); ?> :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo e(Form::text('minimum_length',!empty($target->minimum_length)? $target->minimum_length : '', array('id'=> 'minimumLength', 'class' => 'form-control'))); ?>

                                    <span class="input-group-addon"><?php echo app('translator')->get('label.CHARACTERS'); ?></span>
                                </div>
                                <span class="help-block text-danger"> <?php echo e($errors->first('minimum_length')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="specialCharacter"><?php echo app('translator')->get('label.SPECIAL_CHARECTER'); ?> :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('special_character',$selectValue,!empty($target->special_character)? $target->special_character : '',array('id'=> 'specialCharacter', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('special_character')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="lowerCase"><?php echo app('translator')->get('label.LOWER_CASE'); ?> :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('lower_case',$selectValue,!empty($target->lower_case)? $target->lower_case : '',array('id'=> 'lowerCase', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('lower_case')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="upperCase"><?php echo app('translator')->get('label.UPPER_CASE'); ?> :<span class="required"> </span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('upper_case',$selectValue,!empty($target->upper_case)? $target->upper_case : '',array('id'=> 'upperCase', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('upper_case')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="expeiredPassword"><?php echo app('translator')->get('label.EXPEIRED_OF_PASSWORD'); ?> :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo e(Form::text('expeired_of_password', !empty($target->expeired_of_password)? $target->expeired_of_password : '', array('id'=> 'expeiredPassword', 'class' => 'form-control'))); ?>

                                    <span class="input-group-addon"><?php echo app('translator')->get('label.DAYS'); ?></span>
                                </div>
                                <span class="help-block text-danger"> <?php echo e($errors->first('expeired_of_password')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="spaceNotAllowed"><?php echo app('translator')->get('label.SPACE_NOT_ALLOWED'); ?> : <span class=""></span></label> 
                            <div class="col-md-8 margin-top-10">
                                <label><?php echo app('translator')->get('label.NOT_ALLOWED'); ?></label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green"><?php echo app('translator')->get('label.SUBMIT'); ?></button>
                        <a href="<?php echo e(URL::to('passwordSetup')); ?>">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><?php echo app('translator')->get('label.CANCEL'); ?></button> 
                        </a>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

            <!-- END FORM-->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(function () {
            $(document).on("submit", '#branchUpdate', function (e) {
                //This function use for sweetalert confirm message
                e.preventDefault();
                var form = this;
                swal({
                    title: 'Are you sure you want to Submit?',
                    text: '',
                    type: 'warning',
                    html: true,
                    allowOutsideClick: true,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonClass: 'btn-info',
                    cancelButtonClass: 'btn-danger',
                    confirmButtonText: 'Yes, I agree',
                    cancelButtonText: 'No, I do not agree',
                },
                        function (isConfirm) {
                            if (isConfirm) {
                                toastr.info("Loading...", "Please Wait.", {"closeButton": true});
                                form.submit();
                            } else {
                                //swal(sa_popupTitleCancel, sa_popupMessageCancel, "error");

                            }
                        });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/passwordSetup/edit.blade.php ENDPATH**/ ?>