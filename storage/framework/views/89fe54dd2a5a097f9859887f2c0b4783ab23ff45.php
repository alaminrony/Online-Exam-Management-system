<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-suitcase"></i><?php echo app('translator')->get('label.CREATE_NEW_APPOINTMENT'); ?> </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'designation', 'class' => 'form-horizontal', 'id'=>'createAppointment'))); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title"><?php echo app('translator')->get('label.TITLE'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('title', Request::get('title'), array('id'=> 'title', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('title')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="shortName"><?php echo app('translator')->get('label.SHORT_NAME'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('short_name', Request::get('short_name'), array('id'=> 'shortName', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('short_name')); ?></span>
                            </div>
                        </div>
                       <div class="form-group">
                            <label class="control-label col-md-4" for="order"><?php echo app('translator')->get('label.ORDER'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('order')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control', 'id' => 'status'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('status')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green"><?php echo app('translator')->get('label.SUBMIT'); ?></button>
                        <a href="<?php echo e(URL::to('designation')); ?>">
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
    $(function () {
        $(document).on("submit", '#createAppointment', function (e) {
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


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/designation/create.blade.php ENDPATH**/ ?>