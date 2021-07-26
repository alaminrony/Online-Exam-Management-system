<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i><?php echo app('translator')->get('label.CHANGE_PASSWORD_REPORT'); ?>
            </div>
            <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
            <div class="pull-right" style="margin-top: 3px;" id="groupIcon">
                <a href="<?php echo e(URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=print')); ?>"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="<?php echo e(URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=pdf')); ?>"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo e(URL::to('changePasswordLog?generate=true&from_date='.Request::get('from_date').'&to_date='.Request::get('to_date').'&view=excel')); ?>"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            <?php endif; ?>
        </div>


        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'changePasswordLog/generate','class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-4">
                    <label class="control-label col-md-4" for="fromDate"><?php echo app('translator')->get('label.FROM_DATE'); ?>:<span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            <?php echo Form::text('from_date',Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?>

                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="fromDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('from_date')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4" for="toDate"><?php echo app('translator')->get('label.TO_DATE'); ?>:<span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <div class="input-group date datepicker" style="z-index: 9994 !important">
                            <?php echo Form::text('to_date',Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly' => '']); ?>

                            <span class="input-group-btn">
                                <button class="btn default reset-date" type="button" remove="toDate">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                        <div>
                            <span class="text-danger"><?php echo e($errors->first('to_date')); ?></span> 
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-center">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>


            <?php if($request->generate == 'true'): ?>
            <div class="table-responsive" id="tableData">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.DATE'); ?></th>
                            <th><?php echo app('translator')->get('label.AFFECTED_USER'); ?></th>
                            <th><?php echo app('translator')->get('label.REFORMING_USER'); ?></th>
                            <th><?php echo app('translator')->get('label.ACTION_TOKEN'); ?></th>
                            <th><?php echo app('translator')->get('label.DATE_OF_ACTION'); ?></th>
                            <th><?php echo app('translator')->get('label.OPERATING_SYSTEM'); ?></th>
                            <th><?php echo app('translator')->get('label.BROWSER'); ?></th>
                            <th><?php echo app('translator')->get('label.IP_ADDRESS'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($targetArr)): ?>
                        <?php
                        $sl = 0;
                        ?>
                    <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>

                        <td><?php echo e(++$sl); ?></td>
                        <td><?php echo e(Helper::printDate($target['date'])); ?></td>
                        <td><?php echo e($userList[$target['affected_user_id']]??''); ?></td>
                        <td><?php echo e($userList[$target['reforming_user_id']]??''); ?></td>
                        <td><?php echo e($target['action']); ?></td>
                        <td><?php echo e(Helper::formatDateTime($target['date_time'])); ?></td>
                        <td><?php echo e($target['operating_system']); ?></td>
                        <td><?php echo e($target['browser']); ?></td>
                        <td><?php echo e($target['ip_address']); ?></td>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                    <tr>
                        <td colspan="10"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/logReport/changePasswordLogReport/changePasswordLog.blade.php ENDPATH**/ ?>