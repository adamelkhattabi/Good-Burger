
@if ($type == 1)    {{--big--}}
    <div class="gf-product shop-grid-view-product">
        <div class="image">
            <a href="{{route('/foodDetails')}}?id={{$data->id}}">
                @if ($data->discountprice != 0)
                    <span class="onsale">{{$lang->get(1)}}</span>           {{--Sale!--}}
                @endif
                <img src="{{$data->image}}" class="img-fluid" alt="">
            </a>
            <div class="product-hover-icons">
                <a href="javascript:void(0);" data-tooltip="{{$lang->get(14)}}" onClick="addToBasketById({{$data->id}});"> <img src="img/cartw.png" class="img-fluid" style="padding: 10px"></a> {{--Add to cart--}}
                <a href="javascript:void(0);" data-tooltip="{{$lang->get(15)}}" > <img src="img/addwash.png" class="img-fluid" style="padding: 10px" onClick="addToWishList({{$data->id}});"> </a> {{--Add to wishlist--}}
                <a href="javascript:void(0);" data-tooltip="{{$lang->get(16)}}" onClick="openModal({{$data->id}});" data-toggle="modal" data-target="#quick-view-modal-container">  {{--Quick view--}}
                    <img src="img/view.png" class="img-fluid" style="padding: 10px"> </a>
            </div>
        </div>
        <div class="product-content">
            <h3 class="product-title"><a href="{{route('/foodDetails')}}?id={{$data->id}}">{{$data->name}}</a></h3>
            <div class="price-box">
                @if ($data->discountprice != 0)
                    <span class="main-price">{{$data->price2}}</span>
                    <span class="discounted-price">{{$data->discountprice2}}</span>
                @else
                    <span class="discounted-price">{{$data->price2}}</span>
                @endif
            </div>
        </div>
    </div>
@endif

@if ($type == 2)    {{--small--}}
    <div class="top-rated-product-container">
        <div class="single-top-rated-product d-flex align-content-center">

            <div class="row">
                <div class="col-md-4 px-0">
                    <a href="{{route('/foodDetails')}}?id={{$data->id}}">
                        <img src="{{$data->image}}" class="img-fluid" alt="" style="height: auto; width: 100px;">
                    </a>
                </div>
                <div class="col-md-8">
                    <p><a href="{{route('/foodDetails')}}?id={{$data->id}}">{{$data->name}}</a></p>
                    <p class="product-price">
                        @if ($data->discountprice != 0)
                            <span class="discounted-price">{{$currency->makePrice($data->discountprice)}}</span>
                            <span class="main-price">{{$currency->makePrice($data->price)}}</span>
                        @else
                            <span class="discounted-price">{{$currency->makePrice($data->price)}}</span>
                        @endif

                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

