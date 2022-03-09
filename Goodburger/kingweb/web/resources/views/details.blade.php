@inject('lang', 'App\Lang')
@inject('docs', 'App\Docs')
@inject('currency', 'App\Currency')
@inject('category', 'App\Categories')
@inject('theme', 'App\Theme')

<html>

<head>
    @include('elements.head', array('title' => $food->name))
</head>

<body>

@include('elements.header', array())

<!-- breadcrumb -->
<div class="breadcrumb-area mb-50 mt-20">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-container">
                    <ul>
                        <li><a href="{{route('/')}}"><i class="fa fa-home"></i>{{$lang->get(12)}}</a></li>      {{--HOME--}}
                        @foreach($catNames as $key => $data)
                            <li class="active">{{$data}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-page-container mb-50">
    <div class="container">

        <div class="single-product-content-container mb-35">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-xs-12">

                    <div class="product-image-slider d-flex flex-custom-xs-wrap flex-sm-nowrap align-items-center mb-sm-35">

                        <div class="tab-content product-large-image-list">
                            <div class="tab-pane fade show active" id="details-single-slide1" role="tabpanel" aria-labelledby="single-slide-tab-1">
                                <div class="single-product-img easyzoom img-full">
                                    <img src="{{$food->image}}" class="img-fluid" alt="">
                                    <a href="{{$food->image}}" class="big-image-popup"><i class="fa fa-search-plus"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-xs-12">

                    <div class="product-feature-details">
                        <h2 class="product-title mb-15">{{$food->name}}</h2>

                        <h2 class="product-price mb-15">
                            @if (sizeof($food->variants) == 0)
                                @if ($food->discountprice == 0)
                                    <span class="discounted-price"> {{$currency->makePrice($food->price)}}</span>
                                @else
                                    <span class="main-price">{{$currency->makePrice($food->price)}}</span>
                                    <span class="discounted-price"> {{$currency->makePrice($food->discountprice)}}</span>
                                @endif
                            @endif
                        </h2>

                        <p class="product-description mb-20">{{$food->desc}}</p>

                        <div id="variants" class="cart-buttons mb-20">

{{--variants--}}
                                @foreach($food->variants as $key => $data)
                                    <div id="variants{{$data->id}}" style="font-weight: bold; margin-bottom: 10px">
                                        @if ($data->image == "")
                                            <canvas style="width: 70px; height: 50px"></canvas>
                                        @else
                                            <img src="{{$data->image}}" style="width:50px; height:50px; vertical-align:sub; margin-right: 20px; ">
                                        @endif
                                        <canvas id="radio{{$data->id}}" width="30" height="30" onclick="onRadioClick2({{$data->id}});"></canvas>
                                        <span style="vertical-align: super; font-size: 20px; margin-left: 10px;">{{$data->name}}</span>
                                        {{--price--}}
                                        @if ($data->dprice2 == "")
                                            <span class="main-price" style="font-size: 25px; float: right; margin-top: 15px">{{$currency->makePrice($data->price)}}</span>
                                        @else
                                            <span class="main-price" style="font-size: 20px; float: right; margin-left: 10px; text-decoration: line-through; color: #999; margin-top: 15px">{{$data->price2}}</span>
                                            <span class="discounted-price" style="font-size: 25px; float: right; color: #{{$theme->getMainColor()}}; margin-top: 15px">{{$data->dprice2}}</span>
                                        @endif
                                    </div>
                                @endforeach
{{--extras--}}
                            @if (count($food->extrasdata) != 0)
                                <hr>
                                <h4 class="mb-20">{{$lang->get(157)}}</h4> {{--Extras--}}
                                <div id="extras" class="cart-buttons mb-20">
                                    @foreach($food->extrasdata as $key => $data)
                                        <div id="extras{{$data->id}}" style="font-weight: bold;">
                                            @if ($data->image == "")
                                                <canvas style="width: 70px; height: 50px"></canvas>
                                            @else
                                                <img src="{{$data->image}}" style="width:50px; height:50px; vertical-align:sub; margin-right: 20px; ">
                                            @endif
                                            <div class="d-inline" id="radio5{{$data->id}}" width="50px" height="50px" onclick="onRadioClick5({{$data->id}});"></div>
                                            <span style="vertical-align: super; font-size: 20px; margin-left: 10px;">{{$data->name}}</span>
                                            {{--price--}}
                                            <span class="main-price" style="font-size: 25px; float: right; margin-top: 15px">{{$currency->makePrice($data->price)}}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                            @endif

                        </div>

                        <div class="cart-buttons mb-20">
                            <div class="pro-qty mr-20 mb-xs-20">
                                <input id="product_details_count" type="text" value="1">
                            </div>
                            <div class="add-to-cart-btn">
                                <a href="#" onClick="addToBasketByIdCount({{$food->id}}, selectedItem2, arrayExtras5);"><i class="fa fa-shopping-cart"></i> {{$lang->get(49)}}</a>  {{--Add to Cart--}}
                            </div>
                        </div>
                        <div class="single-product-action-btn mb-20">
                            <a href="#" onClick="addToWishList({{$food->id}});" data-tooltip="{{$lang->get(15)}}"> <span class="icon_heart_alt"></span> {{$lang->get(15)}}</a>  {{--Add to wishlist--}}
                        </div>

                        <div class="single-product-category mb-20">
                            <h3>{{$lang->get(50)}}: <span><a href="{{route('/category')}}?cat={{$category->getIdByFoodId($food->id)}}">{{$category->getNameByFoodId($food->id)}}</a></span></h3> {{--Category--}}
                        </div>

                    </div>

                </div>
            </div>
        </div>

{{--Recommended products--}}

    @if (sizeof($food->rproducts_foods) != 0)
        <div class="slider related-product-slider mb-35">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="section-title">
                            <h3>{{$lang->get(149)}}</h3>  {{--Recommended products--}}
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="related-product-slider-wrapper">

                            @include('elements.topfoods', array('products' => $food->rproducts_foods, 'type' => 1))

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

{{--Top Rated products --}}

        @include('elements.topfoodsslider', array('topfoods' => ""))

    </div>
</div>

<script>
    var selectedItem2 = "";
    var arrayVariants2 = [];
    @foreach($food->variants as $key => $item)
        arrayVariants2.push({
            id: {{$item->id}},
            select: false
        });
    @endforeach
    if (arrayVariants2.length != 0)
        arrayVariants2[0].select = true;
    drawRadios2();

    function onRadioClick2(id){
        selectedItem2 = id;
        console.log("arrayVariants ");
        console.log(arrayVariants2);
        arrayVariants2.forEach(function(item, i, arr) {
            item.select = false;
            if (item.id == id)
                item.select = true;
        });
        drawRadios2();
    }

    function drawRadios2(){
        arrayVariants2.forEach(function(item, i, arr) {
            drawRadio2(`radio${item.id}`, item.select, "#{{$theme->getMainColor()}}");
        });
    }

    function drawRadio2(id, check, color){
        var canvas = document.getElementById(id);
        var ctx = canvas.getContext("2d");
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0, 0, 30, 30);

        ctx.beginPath();
        ctx.lineWidth=2;
        ctx.strokeStyle=color;
        ctx.arc(15,15,10,0,12);
        ctx.stroke();
        if (check) {
            ctx.beginPath();
            ctx.arc(15, 15, 5, 0, 12);
            ctx.fillStyle = color;
            ctx.fill();
        }
    }

    //
    // radio 5
    //
    var arrayExtras5 = [];
    @foreach($food->extrasdata as $key => $item)
    arrayExtras5.push({
        id: {{$item->id}},
        name: "{{$item->name}}",
        image: "{{$item->image}}",
        price: {{$item->price}},
        select: false
    });
    @endforeach

    function onRadioClick5(id){
        console.log("arrayVariants ", arrayExtras5);
        arrayExtras5.forEach(function(item, i, arr) {
            if (item.id == id)
                item.select = !item.select;
        });
        drawMultipleCheckBoxes5();
    }

    function drawMultipleCheckBoxes5(){
        arrayExtras5.forEach(function(item, i, arr) {
            drawMultipleCheckBox5(`radio5${item.id}`, item.select, "#{{$theme->getMainColor()}}");
        });
    }

    function drawMultipleCheckBox5(id, check, color){
        var value = "on";
        if (!check)
            value = "off";
        document.getElementById(id).innerHTML = `<img src="img/check_${value}.png" width="25px" style="margin-bottom: 25px">`;
    }

    //
    // End radio 5
    //

    drawMultipleCheckBoxes5();

</script>
<!--=====  down of page  ======-->

@include('elements.footer', array('docs' => $docs->get()))

<!--=====  Dialogs, elements, etc  ======-->

@include('elements.dialogDetails', array('pages' => ""))
@include('elements.wishlist', array('default_tax' => ""))

<!-- scroll to top  -->
<a href="#" class="scroll-top"><img src="img/arrowup.png" style="padding: 10px;" class="img-fluid"> </a>

<script src="js/main.js"></script>
<script src="js/bootstrap-notify/bootstrap-notify.js"></script>
<script src="js/utils.js"></script>

</body>
</html>
