<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i><?php echo app('translator')->get('label.UPDATE_SCROLL_MESSAGE'); ?> </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo e(Form::model($message, array('route' => array('scrollmessage.update', $message->id), 'method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'messageUpdate'))); ?>

            <?php echo e(csrf_field()); ?>

            <?php echo e(Form::hidden('filter', Helper::queryPageStr($qpArr))); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.SELECT_SCOPE'); ?> : <span class="required"> *</span></label>
                            <div class="col-md-5">
                                <div class="md-checkbox margin-bottom-10">
                                    <input type="checkbox" name="scope[]" class="checkboxes" id="home" <?php echo (!empty($selectedArr[3])) ? 'checked' : ''; ?> value="3">
                                    <label for="home">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo app('translator')->get('label.HOME_PAGE'); ?>
                                    </label>
                                </div>
                                <div class="md-checkbox margin-bottom-10">
                                    <input type="checkbox" name="scope[]" class="checkboxes" id="issp" <?php echo (!empty($selectedArr[1])) ? 'checked' : ''; ?> value="1">
                                    <label for="issp">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span><?php echo app('translator')->get('label.ISSP_DASHBOARD'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.MESSAGE'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('message', null, ['class' => 'form-control input-large'])); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('message')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.PUBLISH'); ?> : <span class="required"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group input-large date-picker input-daterange" data-date="2017-01-01" data-date-format="yyyy-mm-dd">
                                    <?php echo e(Form::text('from_date', Request::get('from_date'), array('id'=> 'courseFromDate', 'class' => 'form-control', 'placeholder' => 'Enter From Date', 'readonly' => true))); ?>

                                    <span class="input-group-addon"> to </span>
                                    <?php echo e(Form::text('to_date', Request::get('to_date'), array('id'=> 'courseToDate', 'class' => 'form-control', 'placeholder' => 'Enter To Date', 'readonly' => true))); ?>

                                </div>
                                <span class="help-block text-danger"><?php echo e($errors->first('from_date')); ?></span>
                                <span class="help-block text-danger"><?php echo e($errors->first('to_date')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.STATUS'); ?> : <span class="required"> *</span></label>
                            <div class="col-md-5">
                                <?php echo e(Form::select('status', $statusList, Request::get('status'),array('class' => 'form-control js-source-states-hidden-search', 'id' => 'courseStatus'))); ?>

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
                        <a href="<?php echo e(URL::to('scrollmessage')); ?>">
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
        $(document).on("submit", '#messageUpdate', function (e) {
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

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/scrollmessage/edit.blade.php ENDPATH**/ ?>