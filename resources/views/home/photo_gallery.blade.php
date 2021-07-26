@include('home.home_header')

<!-- gallery section -->
<section id="gallery" class="bg-blue-oleo bg-font-blue-oleo">	
    <div class="container">
        <div class="row">
            <div  class="col-sm-12 col-md-12 col-xs-12">
                <h1  id="title" class="middle-heading">{{__('label.PHOTO_GALLERY')}}</h1>
            </div>

            <div class="bacco-gallery col-xs-12 col-sm-12 col-md-12">
                <ul id="lightgallery" class="list-unstyled row">				
                    @if(!empty($galleryArr))
                    @foreach($galleryArr as $gallery)
                    @if ($gallery->status == '1')
                    <li data-responsive="{{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}} 375, {{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}} 480" data-src="{{URL::to('/')}}/public/uploads/gallery/originalImage/{{$gallery->photo}}" data-sub-html="">                       
                        <img src="{{URL::to('/')}}/public/uploads/gallery/thumb/{{$gallery->thumb}}" id="galleryimg" width="100%" />
                    </li>
                    @endif
                    @endforeach
                    @else
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <p><i class="fa fa-bell-o fa-fw"></i>{{__('label.NO_PHOTO_AT_GALLERY')}}</p>
                    </div>
                    @endif
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                {{ $galleryArr->appends(Request::all())->links() }}
                <?php
                $start = empty($galleryArr->total()) ? 0 : (($galleryArr->currentPage() - 1) * $galleryArr->perPage() + 1);
                $end = ($galleryArr->currentPage() * $galleryArr->perPage() > $galleryArr->total()) ? $galleryArr->total() : ($galleryArr->currentPage() * $galleryArr->perPage());
                ?> 
            </div>
            <div class="col-md-3">
                @lang('label.SHOWING') {{ $start }} @lang('label.TO') {{$end}} @lang('label.OF')  {{$galleryArr->total()}} @lang('label.RECORDS')
            </div>
        </div>
    </div>
</section>	
@include('home.home_footer')
<!-- end gallery section -->