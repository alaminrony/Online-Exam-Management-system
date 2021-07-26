
<footer>
    <div class="container">
        <div  class="col-sm-12">					
            {!! __('label.COPYRIGHT') !!}
        </div>
    </div>
</footer>
<!--Gallery Js-->
<!--profile-->
<script src="{{asset('public/css/gallery/js/modernizr.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/modules-combined.min.js')}}" type="text/javascript"></script>
<!--profile-->

<!--------Album----------->

<script src="{{asset('public/assets/gallery/js/lightgallery-all.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
    $('#lightgallery').lightGallery();
    });</script>

<script src="{{asset('public/css/gallery/js/gallery/picturefill.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lightgallery.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/picturefill.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-thumbnail.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-video.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-autoplay.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-zoom.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-pager.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/lg-hash.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/gallery/jquery.mousewheel.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/css/gallery/js/nivo-lightbox.js')}}" type="text/javascript"></script>
<!--gallery-->
<script src="{{asset('public/js/jquery.marquee.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/jquery.mCustomScrollbar.min.js')}}" type="text/javascript"></script>

</body>
</html>
<script>
    $(document).ready(function(){
    $(".dropdown").hover(
            function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("400");
            $(this).toggleClass('open');
            },
            function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("400");
            $(this).toggleClass('open');
            }
    );
    });
    $(function() {                       //run when the DOM is ready
    $(".level1 a").click(function() {  //use a class, since your ID gets mangled
    $(this).addClass("active"); //a1dddd the class to the clicked element
    $(".level1 a:first-child").removeClass("active");
    $(this).addClass("active");
    });
    });
</script>
