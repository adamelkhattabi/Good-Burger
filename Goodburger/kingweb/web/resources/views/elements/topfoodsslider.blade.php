@inject('topfoods', 'App\TopFoods')

<div class="slider related-product-slider mb-35">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="section-title">
                    <h3>{{$lang->get(10)}}</h3>  {{--Top rated products--}}
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="related-product-slider-wrapper">

                    @include('elements.topfoods', array('products' => $topfoods->getList(), 'type' => 1))

                </div>
            </div>
        </div>
    </div>
</div>
