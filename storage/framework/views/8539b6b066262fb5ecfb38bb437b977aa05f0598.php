<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i><?php echo app('translator')->get('label.EXAM_RESULT'); ?>
            </div>
            <?php if($request->generate == 'true'): ?>
            <?php if(!empty($finalArr)): ?>
            <div class="pull-right" style="margin-top: 3px;">
                <a href="<?php echo e(URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=print')); ?>"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
                <a href="<?php echo e(URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=pdf')); ?>"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf"><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo e(URL::to('examResultReport?generate=true&fill_exam_id='.Request::get('fill_exam_id').'&fill_employee_id='.Request::get('fill_employee_id').'&view=excel')); ?>"  title="Download Excel" target="_blank" class="btn btn-danger tooltips rounded-0"><i class="fa fa-file-excel-o"></i></a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'examResultReport/generate','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('generate','true'); ?>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="examId"><?php echo app('translator')->get('label.EXAM'); ?>:<span class="required"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('exam_id',$examInfoArr ?? '',Request::get('fill_exam_id'),['class' => 'form-control js-source-states', 'id'=>'examId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('exam_id')); ?></span>
                        </div>
                    </div>
                </div>
                <?php if(Auth::user()->group_id != 3): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="employeeId"><?php echo app('translator')->get('label.EMPLOYEE'); ?>:</label>
                        <div class="col-md-8">
                            <?php echo Form::select('employee_id',$employeeArr,Request::get('fill_employee_id'),['class' => 'form-control js-source-states','id'=>'employeeId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('employee_id')); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->
            <?php if($request->generate == 'true'): ?>
             <?php if(!empty($finalArr)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.EMPLOYEE_NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.OBJECTIVE_MARK'); ?></th>
                            <th><?php echo app('translator')->get('label.SUBJECTIVE_MARK'); ?></th>
                            <th><?php echo app('translator')->get('label.ACHIEVED_MARK'); ?></th>
                            <th><?php echo e(__('label.ACHIEVED_MARK'). '(%)'); ?></th>
                            <th><?php echo e(__('label.RESULT')); ?> <?php echo e(__('label.STATUS')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $finalArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($result['employee_name']); ?>(<?php echo e($result['username']); ?>)</td>
                            <td><?php echo e(Helper::numberformat($result['objective_mark'])); ?></td>
                            <td><?php echo e(Helper::numberformat($result['subjective_mark'])); ?></td>
                            <td><?php echo e(Helper::numberformat($result['achieved_mark'])); ?></td>
                            <td><?php echo e(Helper::numberformat($result['achieved_mark_per'])); ?>%</td>
                            <td><?php echo e(Helper::findGrade(Helper::numberformat($result['achieved_mark_per']))); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php else: ?>
                  <?php if(Auth::user()->group_id == 3): ?>
                  <h2 class="text-center text-danger"> <?php echo app('translator')->get('label.THE_RESULT_HAVE_NOT_PUBLISHED'); ?></h2>
                  <?php else: ?>
                   <h2 class="text-center text-danger"> <?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></h2>
                   <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '#examId', function (e) {
            var examId = $(this).val();
            $.ajax({
                url: "<?php echo e(URL::to('examResultReport/getEmployee')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {exam_id: examId},
                success: function (response) {
                    $('#employeeId').html(response.html);
                    $('.js-source-states').select2();
                    $(".tooltips").tooltip({html: true});
                    App.unblockUI();

                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else if (jqXhr.status == 500) {
                        toastr.error(jqXhr.responseJSON.error.message, jqXhr.statusText, {"closeButton": true});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error("Error", "Something went wrong", {"closeButton": true});
                    }
                    //Ending ajax loader
                    App.unblockUI();
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/report/examResult/examResultReport.blade.php ENDPATH**/ ?>