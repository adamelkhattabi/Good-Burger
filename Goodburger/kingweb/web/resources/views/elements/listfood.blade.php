@inject('currency', 'App\Currency')
@inject('lang', 'App\Lang')

@isset ($data)
<div class="gf-product shop-list-view-product">
    <div class="image">
        <a href="{{route('/foodDetails')}}?id={{$data->id}}">
            @if ($data->discountprice != 0)
                <span class="onsale">{{$lang->get(1)}}</span>            {{--Sale!--}}
            @endif
            <img src="{{$data->image}}" class="img-fluid" style="height: 200px; width: auto;">
        </a>
    </div>
    <div class="product-content">
        <h3 class="product-title"><a href="{{route('/foodDetails')}}?id={{$data->id}}">{{$data->name}}</a></h3>
        <div class="price-box mb-20">
            @if ($data->discountprice != 0)
                <span class="main-price">{{$currency->makePrice($data->price)}}</span>
                <span class="discounted-price">{{$currency->makePrice($data->discountprice)}}</span>
            @else
                <span class="discounted-price">{{$currency->makePrice($data->price)}}</span>
            @endif
        </div>
        <p class="product-description">{{$data->desc}}</p>
        <div class="list-product-icons">
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(16)}}" onClick="openModal({{$data->id}});" data-toggle="modal" data-target="#quick-view-modal-container">  {{--Quick view--}}
                <img src="img/view.png" class="img-fluid" style="padding: 10px"> </a>
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(14)}}" onClick="addToBasketById({{$data->id}});"> <img src="img/cartw.png" class="img-fluid" style="padding: 10px"></a> {{--Add to cart--}}
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(15)}}" > <img src="img/addwash.png" class="img-fluid" style="padding: 10px" onClick="addToWishList({{$data->id}});"> </a> {{--Add to wishlist--}}
        </div>
    </div>
</div>
@endisset

<script>
    function listFood(item){

        if ({{$currency->rightSymbol()}} == "false"){
            var price = parseFloat(item.price).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
            var discPrice = parseFloat(item.discountprice).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
        }else{
            var price = "{{$currency->currency()}}" + parseFloat(item.price).toFixed({{$currency->symbolDigits()}});
            var discPrice = "{{$currency->currency()}}" + parseFloat(item.discountprice).toFixed({{$currency->symbolDigits()}});
        }

        if (item.discountprice != 0)
            var textPrice = `<span class="main-price">${price}</span><span class="discounted-price">${discPrice}</span>`;
        else
            var textPrice = `<span class="discounted-price">${price}</span>`;

        var sale = "";
        if (item.discountprice != 0)
            sale = `<span class="onsale">{{$lang->get(1)}}</span>`;  // sale

        text = `
            <div class="gf-product shop-list-view-product">
                <div class="image">
                    <a href="{{route('/foodDetails')}}?id=${item.id}">
                        ${sale}
                        <img src="${item.image}" class="img-fluid" style="height: 200px; width: auto;">
                    </a>
                    </div>
                    <div class="product-content">
                    <h3 class="product-title"><a href="{{route('/foodDetails')}}?id=${item.id}">${item.name}</a></h3>
                    <div class="price-box mb-20">
                    ${textPrice}
                    </div>
                    <p class="product-description">${item.desc}</p>
                    <div class="list-product-icons">
                        <a href="javascript:void(0);" data-tooltip="{{$lang->get(16)}}" onClick="openModal(${item.id});" data-toggle="modal" data-target="#quick-view-modal-container">  {{--Quick view--}}
                        <img src="img/view.png" class="img-fluid" style="padding: 10px"> </a>
                        <a href="javascript:void(0);" data-tooltip="{{$lang->get(14)}}" onClick="addToBasketById(${item.id});"> <img src="img/cartw.png" class="img-fluid" style="padding: 10px"></a> {{--Add to cart--}}
                        <a href="javascript:void(0);" data-tooltip="{{$lang->get(15)}}" > <img src="img/addwash.png" class="img-fluid" style="padding: 10px" onClick="addToWishList(${item.id});"> </a> {{--Add to wishlist--}}
                </div>
            </div>
            </div>        `;
        return text;
    }


</script>
