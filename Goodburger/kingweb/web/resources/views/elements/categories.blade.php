@foreach($categoriesAll as $key => $data)

    <div class="slider slider-with-banner mb-35">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--=======  blog slider section title  =======-->

                    <div class="section-title">
                        <h3>{{$data->name}}</h3>
                    </div>

                    <!--=======  End of blog slider section title  =======-->
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <!--=======  banner slider wrapper  =======-->

                    <div class="banner-slider-wrapper">
                        <div class="row no-gutters">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <!--=======  slider side banner  =======-->

                                <div class="slider-side-banner">
                                    <a href="category?cat={{$data->id}}">
                                        <img src="{{$data->filename}}" class="img-fluid" alt="">
                                    </a>
                                </div>

                                <!--=======  End of slider side banner  =======-->
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <!--=======  banner slider container  =======-->

                                <div class="banner-slider-container">
                                    <!--=======  single banner slider product  =======-->

                                    @isset($data->foods)
                                        @include('elements.topfoods', array('products' => $data->foods, 'type' => 1))
                                    @endisset

                                </div>

                            </div>
                        </div>
                    </div>

                    <!--=======  End of banner slider wrapper =======-->
                </div>
            </div>
        </div>
    </div>

@endforeach

<script>
    // https://kenwheeler.github.io/slick/

    $('.banner-slider-container').slick({
        arrows: true,
        autoplay: false,
        dots: false,
        infinite: true,
        slidesToShow: 4,
        prevArrow: '<button type="button" class="slick-prev"><img src="img/arrowl.png" style="width: 20px"></button>',
        nextArrow: '<button type="button" class="slick-next"><img src="img/arrowr.png" style="width: 20px"></i></button>',
        responsive: [{
            breakpoint: 1499,
            settings: {
                slidesToShow: 4,
            }
        },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });

</script>
