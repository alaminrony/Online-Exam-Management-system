<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i><?php echo app('translator')->get('label.UPDATE_SUBJECT'); ?> </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::model($subject, array('route' => array('subject.update', $subject->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'subjectUpdate'))); ?>

            <?php echo e(csrf_field()); ?>

            <?php echo e(Form::hidden('filter', Helper::queryPageStr($qpArr))); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="title"><?php echo app('translator')->get('label.SUBJECT_NAME'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('title', Request::get('title'), array('id'=> 'title', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('title')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="code"><?php echo app('translator')->get('label.SUBJECT_CODE'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('code', Request::get('code'), array('id'=> 'subjectCode', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('code')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="details"><?php echo app('translator')->get('label.SUBJECT_DETAILS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('details', Request::get('details'), array('id'=> 'subjectDetails', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('details')); ?></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-4 control-label" for="order"><?php echo app('translator')->get('label.ORDER'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('order',$orderList,Request::get('order'),array('id'=> 'order', 'min' => 0, 'class' => 'form-control js-source-states'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('order')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('status', array('1' => 'Active', '0' => 'Inactive'), Request::get('status'), array('class' => 'form-control', 'id' => 'status'))); ?>

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
                        <a href="<?php echo e(URL::to('subject')); ?>">
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
        $(document).on("submit", '#subjectUpdate', function (e) {
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/subject/edit.blade.php ENDPATH**/ ?>