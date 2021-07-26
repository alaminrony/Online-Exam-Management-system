<?php echo $__env->make('home.home_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- gallery section -->
<section id="gallery" class="bg-blue-oleo bg-font-blue-oleo">	
    <div class="container">
        <div class="row">
            <div  class="col-sm-12 col-md-12 col-xs-12">
                <h1  id="title" class="middle-heading"><?php echo e(__('label.PHOTO_GALLERY')); ?></h1>
            </div>

            <div class="bacco-gallery col-xs-12 col-sm-12 col-md-12">
                <ul id="lightgallery" class="list-unstyled row">				
                    <?php if(!empty($galleryArr)): ?>
                    <?php $__currentLoopData = $galleryArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($gallery->status == '1'): ?>
                    <li data-responsive="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?> 375, <?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?> 480" data-src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/originalImage/<?php echo e($gallery->photo); ?>" data-sub-html="">                       
                        <img src="<?php echo e(URL::to('/')); ?>/public/uploads/gallery/thumb/<?php echo e($gallery->thumb); ?>" id="galleryimg" width="100%" />
                    </li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <p><i class="fa fa-bell-o fa-fw"></i><?php echo e(__('label.NO_PHOTO_AT_GALLERY')); ?></p>
                    </div>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <?php echo e($galleryArr->appends(Request::all())->links()); ?>

                <?php
                $start = empty($galleryArr->total()) ? 0 : (($galleryArr->currentPage() - 1) * $galleryArr->perPage() + 1);
                $end = ($galleryArr->currentPage() * $galleryArr->perPage() > $galleryArr->total()) ? $galleryArr->total() : ($galleryArr->currentPage() * $galleryArr->perPage());
                ?> 
            </div>
            <div class="col-md-3">
                <?php echo app('translator')->get('label.SHOWING'); ?> <?php echo e($start); ?> <?php echo app('translator')->get('label.TO'); ?> <?php echo e($end); ?> <?php echo app('translator')->get('label.OF'); ?>  <?php echo e($galleryArr->total()); ?> <?php echo app('translator')->get('label.RECORDS'); ?>
            </div>
        </div>
    </div>
</section>	
<?php echo $__env->make('home.home_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- end gallery section --><?php /**PATH C:\xampp\htdocs\oem\resources\views/home/photo_gallery.blade.php ENDPATH**/ ?>