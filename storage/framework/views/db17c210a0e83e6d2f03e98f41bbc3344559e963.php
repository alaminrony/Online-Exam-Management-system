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
                <span><?php echo app('translator')->get('label.LOGIN_LOGOUT_REPORT'); ?></span>
            </div>
            <div class="logoCityBank">
                <?php if(Request::get('view') == 'pdf'): ?>
                <img src="<?php echo base_path(); ?>/public/img/logo.png"/> 
                <?php else: ?>
                <img src="<?php echo asset('public/img/logo.png'); ?>"  />
                <?php endif; ?>
            </div>
        </div>
        
        <div>
            <p>
                <b><?php echo app('translator')->get('label.FROM_DATE'); ?>:</b><?php echo e(Helper::printDate(Request::get('from_date'))); ?> 
                <b><?php echo app('translator')->get('label.TO_DATE'); ?>:</b><?php echo e(Helper::printDate(Request::get('to_date'))); ?>

            </p>
        </div>
        <?php endif; ?>
        <!--Laravel Excel not supported body & other tags, only Table tag accepted-->
        <table class="table table-bordered">
            <thead>
                <tr class="center">
                    <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th><?php echo app('translator')->get('label.DATE'); ?></th>
                    <th><?php echo app('translator')->get('label.AFFECTED_USER'); ?></th>
                    <th><?php echo app('translator')->get('label.OPERATING_SYSTEM'); ?></th>
                    <th><?php echo app('translator')->get('label.BROWSER'); ?></th>
                    <th><?php echo app('translator')->get('label.IP_ADDRESS'); ?></th>
                    <th><?php echo app('translator')->get('label.LOGIN_DATETIME'); ?></th>
                    <th><?php echo app('translator')->get('label.LOGOUT_DATETIME'); ?></th>
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
                    <td><?php echo e($target['operating_system']); ?></td>
                    <td><?php echo e($target['browser']); ?></td>
                    <td><?php echo e($target['ip_address']); ?></td>
                    <td><?php echo e(Helper::formatDateTime($target['login_datetime'])); ?></td>
                    <td><?php echo e(Helper::formatDateTime($target['logout_datetime'])); ?></td>

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
    <script src="<?php echo e(asset('public/js/jquery.min.js')); ?>"></script>
    <script>
$(document).ready(function () {
    window.print();
});
    </script>
</html>
<?php endif; ?>








<?php /**PATH C:\xampp\htdocs\oem\resources\views/logReport/loginReport/printLoginReport.blade.php ENDPATH**/ ?>