<?php
$currentControllerName = Request::segment(1);
//echo $currentFullRouteName;
?>
<html>
    <head>
        <meta charset=utf-8> <meta content="IE=edge" http-equiv=X-UA-Compatible>
        <meta content="width=device-width,initial-scale=1" name=viewport>
        <meta content="Command and Staff Training Institute (CSTI), CSTI, ISSP, JCSC, JC&SC" name=description>
        <meta content="CSTI" name=author>
        <title><?php echo app('translator')->get('label.CSTI_FULL'); ?></title>
        <link rel="shortcut icon" type="image/icon" href="<?php echo e(URL::to('/')); ?>/public/img/favicon.ico"/>
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/css/style.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/css/nab.css')); ?>" rel="stylesheet" type="text/css">
        <!-- gallery -->
        <script src="<?php echo e(asset('public/css/gallery/js/jquery.min.js')); ?>" type="text/javascript"></script>
        <script src="<?php echo e(asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
        <link href="<?php echo e(asset('public/css/gallery/gallery-style.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/css/gallery/profiles.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/assets/gallery/css/lightgallery.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/custom.css')); ?>" rel="stylesheet" type="text/css">
    </head>
    <body>
        <header>
            <!--<div class="container">
                <div id="logo_img" class="col-sm-12">-->
            <a href="<?php echo e(URL::to('/')); ?>">
                <img class="img-responsive" src="<?php echo e(URL::to('/')); ?>/public/img/csti_bg.jpg" width="1920px" alt = "Command and Staff Training Institute (CSTI)"/>
            </a>

            <!--</div>
        </div>-->
        </header>

        <style type="text/css">
            .header-link{
                padding: 8px 30px;
                color: #fff;
                font-weight: bold;
                border-radius: 3px;
            }

            .header-link:hover{
                text-decoration: none;
                -webkit-box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);
                box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);
            }

            #header-link-1{
                background: #e74f5b;
            }

            #header-link-2{
                background: #31c6d2;
            }

            #header-link-3{
                background: #e74fb3;
            }

            #header-link-4{
                background: #4fe792;
            }


        </style>

        <!-- menu section -->
        <section id="main_menu">
            <div class="container">
                <nav class="navbar navbar-inverse main_manu">
                    <div class="navbar-header">
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
                    <div class="collapse navbar-collapse js-navbar-collapse">
                        <ul class="nav navbar-nav navbar-left csti-nav">
                            <li class="level1"><a class="<?php echo e(($currentControllerName == '') ? 'active' : ''); ?>" href="<?php echo e(URL::to('/')); ?>" ><?php echo e(__('label.HOME')); ?></a></li>
                            <li class="level1"><a class="<?php echo e(($currentControllerName == 'about_us') ? 'active' : ''); ?>" href="<?php echo e(URL::to('about_us')); ?>" ><?php echo e(__('label.ABOUT_US')); ?></a></li>
                            <li class="level1"><a class="<?php echo e(($currentControllerName == 'history') ? 'active' : ''); ?>" href="<?php echo e(URL::to('history')); ?>" ><?php echo e(__('label.HISTORY')); ?></a></li>
                            <li class="level1"><a href="<?php echo e(URL::to('login')); ?>" ><?php echo e(__('label.ISSP')); ?></a></li>
                            <li class="level1"><a class="<?php echo e(($currentControllerName == 'photo_gallery') ? 'active' : ''); ?>" href="<?php echo e(URL::to('photo_gallery')); ?>"><?php echo e(__('label.GALLERY')); ?></a></li>
                            <!--<li class="level1"><a class="" target="_blank" href="<?php echo e(URL::to('ejournal')); ?>"><?php echo e(__('label.EJOURNAL')); ?></a></li>-->
                            <?php
                            if (Auth::guest()) {
                                //If not LoggedIn, No Item will be shown
                            } else {
                                //If LoggedIn, show "Logout" Menu Item
                                ?>
                                <li class="level1">
                                    <a class="tooltips"  title="Logout" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        <?php echo app('translator')->get('label.LOGOUT'); ?>
                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>


                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div><!-- /.nav-collapse -->
                </nav>
            </div>
        </section> 
        <!-- end menu section --><?php /**PATH C:\xampp\htdocs\oem\resources\views/home/home_header.blade.php ENDPATH**/ ?>