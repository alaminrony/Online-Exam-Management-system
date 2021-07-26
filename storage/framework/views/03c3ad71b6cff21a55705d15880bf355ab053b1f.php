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
                <span><?php echo app('translator')->get('label.PARTICIPATION_STATUS'); ?></span>
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
                <p style="height: 25px;"></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="text-center">
                <p>
                    <label><b><?php echo app('translator')->get('label.FROM_DATE'); ?>:</b> <?php echo e(!empty($request->from_date) ? $request->from_date : 'N/A'); ?></label>
                    <label><b><?php echo app('translator')->get('label.TO_DATE'); ?>:</b> <?php echo e(!empty($request->to_date) ? $request->to_date : 'N/A'); ?></label>
                </p>
            </div>
        </div>
        <?php endif; ?>
        <?php if(Request::get('view') == 'excel'): ?>
        <table>
            <tr>
                <td>
                    <label><?php echo app('translator')->get('label.FROM_DATE'); ?>: <?php echo e(!empty($request->from_date) ? $request->from_date : 'N/A'); ?></label>
                    <label><?php echo app('translator')->get('label.TO_DATE'); ?>: <?php echo e(!empty($request->to_date) ? $request->to_date : 'N/A'); ?></label>
                </td>
            </tr>
        </table>
        <?php endif; ?>
        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="center">
                    <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th><?php echo app('translator')->get('label.EXAM'); ?></th>
                    <th><?php echo app('translator')->get('label.ENROLL'); ?></th>
                    <th><?php echo app('translator')->get('label.ATTENDENT'); ?></th>
                    <th><?php echo app('translator')->get('label.ABSENT'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($targetArr)): ?>
                <?php
                $page = Request::get('page');
                $page = empty($page) ? 1 : $page;
                $sl = ($page - 1) * Session::get('paginatorCount');
                ?>
                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examId => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(++$sl); ?></td>
                    <td><?php echo e($examList[$examId]); ?></td>
                    <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="<?php echo e(__('label.VIEW_ENROLL_STUDENT')); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="1"><?php echo e($result['enroll']); ?></a></td>
                    <td><a type="button" class="tooltips studentDetails" data-toggle="modal" title="<?php echo e(__('label.VIEW_ATTENDENT_STUDENT')); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="2" ><?php echo e($result['attendend']); ?></a></td>
                    <td><a type="button" class="tooltips studentDetails"  data-toggle="modal" title="<?php echo app('translator')->get('label.VIEW_ABSENT_STUDENT'); ?>" data-target="#viewStudentModal" data-id="<?php echo e($examId); ?>" data-type="3" ><?php echo e($result['absent']); ?></a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <tr>
                    <td colspan="10"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
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








<?php /**PATH C:\xampp\htdocs\oem\resources\views/report/participationStatus/print/participationStatus.blade.php ENDPATH**/ ?>