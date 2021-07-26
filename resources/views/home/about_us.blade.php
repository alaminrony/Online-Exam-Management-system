@include('home.home_header')
<!-- About Us section -->
<section id="about_us">	
	<div class="container">
		<div class="row">
			<div  class="col-sm-12 col-md-12 col-xs-12">
				<h1  id="title" class="middle-heading">{{__('label.ABOUT_US')}}</h1>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12">
			   <p>
			   {!! $configurationArr->about_us!!}
				</p>
			   <div id="clearfix"></div>
			   
			</div>
		</div>
	</div>
</section>	
<!-- end about us section -->
@include('home.home_footer')