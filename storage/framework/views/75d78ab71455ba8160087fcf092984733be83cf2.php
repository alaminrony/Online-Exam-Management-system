<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i><?php echo app('translator')->get('label.CREATE_NEW_BRANCH'); ?>
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body form">

            <?php echo e(Form::open(array('role' => 'form', 'url' => 'branch', 'class' => 'form-horizontal', 'id'=>'createBranch'))); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-7">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="regionId"><?php echo app('translator')->get('label.SELECT_REGION'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('region_id', $regionList, Request::get('region_id'), array('class' => 'form-control js-source-states', 'id' => 'regionId'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('region_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="clusterId"><?php echo app('translator')->get('label.SELECT_CLUSTER'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('cluster_id', $clusterList, null, array('class' => 'form-control js-source-states', 'id' => 'clusterId'))); ?>

                                <span class="help-block text-danger"><?php echo e($errors->first('cluster_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('name', Request::get('name'), array('id'=> 'name', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('name')); ?></span>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="col-md-4 control-label" for="solId"><?php echo app('translator')->get('label.SOL_ID'); ?> :<span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('sol_id', Request::get('sol_id'), array('id'=> 'solId', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('sol_id')); ?></span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-4 control-label" for="Location"><?php echo app('translator')->get('label.LOCATION'); ?> :<span class="required"></span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::text('location', Request::get('location'), array('id'=> 'Location', 'class' => 'form-control'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('location')); ?></span>
                            </div>
                        </div>


                       
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="order"><?php echo app('translator')->get('label.ORDER'); ?> : <span class="required"> *</span></label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('order',$orderList,Request::get('order'), array('id'=> 'order', 'class' => 'form-control js-source-states'))); ?>

                                <span class="help-block text-danger"> <?php echo e($errors->first('order')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo app('translator')->get('label.STATUS'); ?>  : </label>
                            <div class="col-md-8">
                                <?php echo e(Form::select('status', array('1' => __('label.ACTIVE'), '0' => __('label.INACTIVE')), Request::get('status'), array('class' => 'form-control'))); ?>

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
                        <a href="<?php echo e(URL::to('branch')); ?>">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline"><?php echo app('translator')->get('label.CANCEL'); ?></button> 
                        </a>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>


        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {

        $(document).on('change', '#regionId', function (e) {
            var regionId = $('#regionId').val();
            $.ajax({
                url: "<?php echo e(URL::to('branch/getCluster')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    region_id: regionId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#clusterId').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            }); //ajax
        });


        $(document).on("submit", '#createBranch', function (e) {
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


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/branch/create.blade.php ENDPATH**/ ?>