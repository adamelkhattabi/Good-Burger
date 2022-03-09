@inject('currency', 'App\Currency')
@inject('topfoods', 'App\TopFoods')

<div class="slider related-product-slider mb-35">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="section-title">
                    <h3>{{$lang->get(145)}}</h3>  {{--Most popular products--}}
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="related-product-slider-wrapper">

                    @include('elements.topfoods', array('products' => $topfoods->mostPopular(), 'type' => 1))

                </div>
            </div>
        </div>
    </div>
</div>
