<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i><?php echo app('translator')->get('label.FORCE_CHANGE_PASSWORD'); ?> 
            </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <table class="table">
                        <tr>
                            <th rowspan="5">
                                <?php if(isset($userInfo->photo)): ?>
                                <img width="120" class="img-circle" height="120" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($userInfo->photo); ?>" alt="<?php echo e($userInfo->first_name.' '.$userInfo->last_name); ?>">
                                <?php else: ?>
                                <img width="120" class="img-circle" height="120" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($userInfo->first_name.' '.$userInfo->last_name); ?>">
                                <?php endif; ?>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <address class="text-left">
                                    <strong><?php echo app('translator')->get('label.NAME'); ?>: </strong><?php echo e($userInfo->first_name .' '.$userInfo->last_name); ?><br />
                                    <strong><?php echo app('translator')->get('label.RANK'); ?>: </strong><?php echo e($userInfo->rank->title ?? ''); ?><br />
                                    <strong><?php echo app('translator')->get('label.APPOINTMENT'); ?>: </strong><?php echo e($userInfo->designation->title); ?><br />
                                    <strong><?php echo app('translator')->get('label.BRANCH'); ?>: </strong><?php echo e(!empty($userInfo->branch->name) ? $userInfo->branch->name: ''); ?><br />
                                </address>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- BEGIN FORM-->
            <?php echo e(Form::open(array('group' => 'form', 'url' => 'updateForcePassword/'.$userInfo->id, 'class' => 'form-horizontal', 'id'=>'forcePassword'))); ?>

            <?php echo e(Form::hidden('id',$userInfo->id)); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.PASSWORD'); ?> :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo e(Form::password('password', array('id'=> 'userPassword', 'class' => 'form-control'))); ?>

                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                  <span class="help-block">
                                    Password must be a combination of at least <?php  if($passwordSetup->upper_case =='1') {echo "one upper case ,";}?>  <?php  if($passwordSetup->lower_case =='1') {echo "one lower case ,";}?>
                                     <?php  if($passwordSetup->special_character =='1') {echo "one special character ,";}?> <?php if($passwordSetup->space_not_allowed == '1'){echo "White space not allowed ,";}?> minimum <?php echo e($passwordSetup->minimum_length); ?> & maximum <?php echo e($passwordSetup->maximum_length); ?> character.
                                 </span>
                                <span class="help-block text-danger"> <?php echo e($errors->first('password')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.CONFIRM_PASSWORD'); ?> :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo e(Form::password('password_confirmation', array('id'=> 'userConfirmPassword', 'class' => 'form-control'))); ?>

                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                <span class="help-block text-danger"> <?php echo e($errors->first('password_confirmation')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green"><?php echo app('translator')->get('label.SUBMIT'); ?></button>
                        <a href="javascript:history.back()">
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
<!-- END CONTENT BODY -->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("submit", '#forcePassword', function (e) {
            //This function use for sweetalert confirm message
            e.preventDefault();
            var form = this;
            swal({
                title: 'Are you sure you want to Submit?',
//                text: '<strong></strong>',
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
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/user/forceChangePassword.blade.php ENDPATH**/ ?>