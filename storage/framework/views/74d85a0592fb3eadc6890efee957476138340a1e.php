<?php echo $__env->make('home.home_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('home.home_scroll', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('home.home_banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<!--content section 
<section id="about_us">	
    <div class="container">
<!-- About Us section -->
<!--	<div class="row">
<div  class="col-sm-12 col-md-12 col-xs-12">
<h1  id="title" class="middle-heading"><?php echo e(__('label.ABOUT_US')); ?></h1>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
<p>
                   <?php echo e($aboutUs); ?>

                        </p>
                   <div id="clearfix"></div>
                   <div class="read_more_btn_div">
                        <a href="<?php echo e(URL::to('about_us')); ?>" class="btn btn-primary"><?php echo e(__('label.READ_MORE')); ?></a>
                   </div>
</div>
</div>
</div>
</section>	
<!-- end about us section -->
<section id="gallery" class="bg-blue-oleo bg-font-blue-oleo">	
    <div class="container">

        <!-- gallery section -->
        <div class="row">
            <div  class="col-sm-12 col-md-12 col-xs-12">
                <h1  id="title" class="middle-heading"><?php echo e(__('label.PHOTO_GALLERY')); ?></h1>
            </div>

            <div class="bacco-gallery col-xs-12 col-sm-12 col-md-12">

                <?php if(!empty($galleryArr)): ?>
                <ul id="lightgallery" class="list-unstyled row">
                    <?php $__currentLoopData = $galleryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($gallery->status == '1'): ?>
                    <li data-responsive="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?> 375, <?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?> 480" data-src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?>" data-sub-html="">                       
                        <img src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/thumb/<?php echo e($gallery->thumb); ?>" id="galleryimg" width="100%" />
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <p><i class="fa fa-bell-o fa-fw"></i><?php echo e(__('label.NO_PHOTO_AT_GALLERY')); ?></p>
                </div>
                <?php endif; ?>
                <div id="clearfix"></div>
                <div class="read_more_btn_div">
                    <a href="<?php echo e(URL::to('photo_gallery')); ?>" class="btn btn-primary"><?php echo e(__('label.VIEW_GALLERY')); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>	
<!--end gallery section -->
<?php echo $__env->make('home.home_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/home/home_page.blade.php ENDPATH**/ ?>