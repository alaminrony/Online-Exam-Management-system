<?php if($request->view == 'print' || $request->view == 'pdf'): ?>
<html>
    <head>
        <title><?php echo app('translator')->get('label.COMMAND_AND_STAFF_TRAINING_INSTITUTE_CSTI_BAF'); ?></title>
        <?php if(Request::get('view') == 'print'): ?>
        <link rel="shortcut icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')); ?>" rel="stylesheet" type="text/css" />

        <?php elseif(Request::get('view') == 'pdf'): ?>
        <link rel="shortcut icon" href="<?php echo base_path(); ?>/public/img/favicon.ico" />
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'); ?>" rel="stylesheet" type="text/css"/>
        <?php endif; ?>
    </head>
    <body>
        <div class="header">
            <div class="logoRetail">
                <?php if(Request::get('view') == 'pdf'): ?>
                <img src="<?php echo base_path(); ?>/public/img/retail_logo.png" /> 
                <?php else: ?>
                <img src="<?php echo asset('public/img/retail_logo.png'); ?>"  /> 
                <?php endif; ?>
            </div>
            <div class="logoTile">
                <span><?php echo app('translator')->get('label.EMPLOYEE_WISE_RESULT'); ?></span>
            </div>
            <div class="logoCityBank">
                <?php if(Request::get('view') == 'pdf'): ?>
                <img src="<?php echo base_path(); ?>/public/img/logo.png"/> 
                <?php else: ?>
                <img src="<?php echo asset('public/img/logo.png'); ?>"  />
                <?php endif; ?>
            </div>
        </div>
        <?php if(Request::get('view') == 'pdf'): ?>
        <div class="row">
            <div class="text-center">
                <p style="height: 20px;"></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="text-center">
                <p>
                    <label><b><?php echo app('translator')->get('label.EMPLOYEE'); ?>:</b> <?php echo e(!empty($employeeArr[Request::get('fill_employee_id')])?$employeeArr[Request::get('fill_employee_id')]: 'N/A'); ?></label>
                    <label><b><?php echo app('translator')->get('label.SUBJECT'); ?>:</b> <?php echo e(!empty($subjectArr[Request::get('fill_subject_id')]) ? $subjectArr[Request::get('fill_subject_id')] : 'N/A'); ?></label>
                    <?php if(!empty(Request::get('fill_exam_id'))): ?>
                    <label><b><?php echo app('translator')->get('label.EXAM'); ?>:</b> <?php echo e(!empty($examInfoArr[Request::get('fill_exam_id')])?$examInfoArr[Request::get('fill_exam_id')]: 'N/A'); ?></label>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        <?php if(!empty($request->generate) && $request->generate == 'true'): ?>
        <?php if(!empty($finalArr)): ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="center">
                        <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <th><?php echo app('translator')->get('label.EXAM'); ?></th>
                        <th><?php echo app('translator')->get('label.EXAM_DATE'); ?></th>
                        <th><?php echo app('translator')->get('label.RESULT_PUBLISH_DATE_TIME'); ?></th>
                        <th><?php echo app('translator')->get('label.TOTAL_MARK'); ?></th>
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
                        <td><?php echo e($result['title']); ?></td>
                        <td><?php echo e(Helper::printDate($result['exam_date'])); ?></td>
                        <td><?php echo e(Helper::formatDateTime($result['result_publish'])); ?></td>
                        <td><?php echo e($result['total_mark']); ?></td>
                        <td><?php echo e($result['employee_name']); ?></td>
                        <td><?php echo e(Helper::numberformat($result['objective_mark'])); ?></td>
                        <td><?php echo e(Helper::numberformat($result['subjective_mark'])); ?></td>
                        <td><?php echo e(Helper::numberformat($result['achieved_mark'])); ?></td>
                        <td><?php echo e(Helper::numberformat($result['achieved_mark_per'],2)); ?>%</td>
                        <td><?php echo e(Helper::findGrade($result['achieved_mark_per'])); ?></td>
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
        <!--Laravel Excel not supported  body & other tags, only Table tag accepted-->


        <?php if($request->view == 'print' || $request->view == 'pdf'): ?>
        <div class="row">
            <div class="col-md-4">
                <div class="col-md-4">
                    <p><?php echo app('translator')->get('label.REPORT_GENERATED_ON'); ?> <?php echo e(Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name); ?></p>
                </div>
            </div>
            <div class="col-md-8 print-footer">
                <p><b><?php echo e(__('label.ONLINE_EXAM_MANAGEMENT')); ?></b></p>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {
        window.print();
    });
</script>
<?php endif; ?>








<?php /**PATH C:\xampp\htdocs\oem\resources\views/report/employeeWiseResult/printEmployeeWiseReport.blade.php ENDPATH**/ ?>