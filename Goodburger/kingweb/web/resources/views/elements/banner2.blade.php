
<div class="slider mb-35">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="brand-logo-wrapper pt-20 pb-20">
                    <div class="single-banners2-logo">
                        @foreach($banners2 as $key => $data)
                            <a href="{{$data->link}}">
                                <img src="{{$data->filename}}" class="img-fluid" alt="">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // https://kenwheeler.github.io/slick/

    $('.single-banners2-logo').slick({
        autoplay: true,
        dots: false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><img src="img/arrowl.png" style="width: 20px"></button>',
        nextArrow: '<button type="button" class="slick-next"><img src="img/arrowr.png" style="width: 20px"></i></button>',
    });

</script>

