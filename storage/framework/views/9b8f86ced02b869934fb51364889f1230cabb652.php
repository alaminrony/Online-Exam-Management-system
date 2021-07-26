<?php echo $__env->make('home.home_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- About Us section -->
<section id="about_us">	
	<div class="container">
		<div class="row">
			<div  class="col-sm-12 col-md-12 col-xs-12">
				<h1  id="title" class="middle-heading"><?php echo e(__('label.ABOUT_US')); ?></h1>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12">
			   <p>
			   <?php echo $configurationArr->about_us; ?>

				</p>
			   <div id="clearfix"></div>
			   
			</div>
		</div>
	</div>
</section>	
<!-- end about us section -->
<?php echo $__env->make('home.home_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/home/about_us.blade.php ENDPATH**/ ?>