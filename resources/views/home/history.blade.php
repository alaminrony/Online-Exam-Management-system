@include('home.home_header')
<!-- history Us section -->
<section id="about_us">	
    <div class="container">

        <div class="row">
            <div  class="col-sm-12 col-md-12 col-xs-12">
                <h1  id="title" class="middle-heading"> {{__('label.HISTORY')}}</h1>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <p>
                    {!! $configurationArr->history !!}
                </p>
                <div id="clearfix"></div>

            </div>
        </div>
    </div>
</section>	
<!-- end history us section -->
@include('home.home_footer')