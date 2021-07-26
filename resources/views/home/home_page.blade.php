@include('home.home_header')
@include('home.home_scroll')
@include('home.home_banner')


<!--content section 
<section id="about_us">	
    <div class="container">
<!-- About Us section -->
<!--	<div class="row">
<div  class="col-sm-12 col-md-12 col-xs-12">
<h1  id="title" class="middle-heading">{{__('label.ABOUT_US')}}</h1>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
<p>
                   {{$aboutUs}}
                        </p>
                   <div id="clearfix"></div>
                   <div class="read_more_btn_div">
                        <a href="{{URL::to('about_us')}}" class="btn btn-primary">{{__('label.READ_MORE')}}</a>
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
                <h1  id="title" class="middle-heading">{{__('label.PHOTO_GALLERY')}}</h1>
            </div>

            <div class="bacco-gallery col-xs-12 col-sm-12 col-md-12">

                @if(!empty($galleryArr))
                <ul id="lightgallery" class="list-unstyled row">
                    @foreach($galleryArr as $gallery)
                    @if ($gallery->status == '1')
                    <li data-responsive="{{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}} 375, {{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}} 480" data-src="{{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}}" data-sub-html="">                       
                        <img src="{{URL::to('/')}}/public/uploads/gallery/thumb/{{$gallery->thumb}}" id="galleryimg" width="100%" />
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <p><i class="fa fa-bell-o fa-fw"></i>{{__('label.NO_PHOTO_AT_GALLERY')}}</p>
                </div>
                @endif
                <div id="clearfix"></div>
                <div class="read_more_btn_div">
                    <a href="{{URL::to('photo_gallery')}}" class="btn btn-primary">{{__('label.VIEW_GALLERY')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>	
<!--end gallery section -->
@include('home.home_footer')