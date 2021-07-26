<?php $__env->startSection('login_content'); ?>
<div class="form-title" style="color: #edf4f8;font-size: 19px;font-weight: 400!important;text-align: center;display: block;">
    <span class="form-title">&nbsp;</span>
</div>
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGO -->
    <div class="logo_login_page" style="text-align:center; background-color:#6c7a8d;margin: -10px -40px 0px;">
        <a href="<?php echo e(URL::to('/')); ?>">
            <img src="<?php echo e(URL::to('/')); ?>/public/img/oem-logo.png" alt="logo" width="150px" height="auto"/>
        </a>
    </div>
    <!-- END LOGO -->


    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" method="POST" action="<?php echo e(route('login')); ?>" autocomplete="off">
        <?php echo csrf_field(); ?>
        <h3 class="form-title font-green"><?php echo e(__('label.LOGIN')); ?> (<?php echo e(__('label.ISSP')); ?>)</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> <?php echo e(__('label.ENTER_ANY_USERNAME_AND_PASSWORD')); ?></span>
        </div>

        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9"><?php echo e(__('label.USERNAME')); ?></label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" /> 
            <?php if($errors->has('username')): ?>
            <span class="invalid-feedback">
                <strong class="text-danger"><?php echo e($errors->first('username')); ?></strong>
            </span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9"><?php echo e(__('label.PASSWORD')); ?></label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 
            <?php if($errors->has('password')): ?>
            <span class="invalid-feedback">
                <strong class="text-danger"><?php echo e($errors->first('password')); ?></strong>
            </span>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn green uppercase"><?php echo e(__('label.LOGIN')); ?></button>
            <!--<label class="rememberme check mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="remember" value="1" /><?php echo e(__('label.REMEMBER')); ?>

                <span></span>
            </label>-->
            <!--<a href="javascript:;" id="" class="forget-password">Forgot Password?</a>-->
        </div>
        <div class="create-account">
            <p><a href="<?php echo e(URL::to('/')); ?>" class="uppercase">GO TO HOME PAGE</a></p>
        </div>
        </from>
</div>
<div class="copyright"><?php echo __('label.COPYRIGHT'); ?></div>
<!-- END LOGIN FORM -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/auth/login.blade.php ENDPATH**/ ?>