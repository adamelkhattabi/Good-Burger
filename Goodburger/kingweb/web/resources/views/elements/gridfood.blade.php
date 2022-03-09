@inject('currency', 'App\Currency')
@inject('lang', 'App\Lang')

@isset ($data)
    @include('elements.oneItem', array('data' => $data, 'type' => 1))
@endisset

<script>

    function gridFood(id, image, name, price2, priceDiscount){

        if ({{$currency->rightSymbol()}} == "false"){
            var price = parseFloat(price2).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
            var discPrice = parseFloat(priceDiscount).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
        }else{
            var price = "{{$currency->currency()}}" + parseFloat(price2).toFixed({{$currency->symbolDigits()}});
            var discPrice = "{{$currency->currency()}}" + parseFloat(priceDiscount).toFixed({{$currency->symbolDigits()}});
        }

        if (priceDiscount != 0)
            var textPrice = `<span class="main-price">${price}</span><span class="discounted-price">${discPrice}</span>`;
        else
            var textPrice = `<span class="discounted-price">${price}</span>`;

        var sale = "";
        if (priceDiscount != 0)
            sale = `<span class="onsale">{{$lang->get(1)}}</span>`;  // Sale

        text = `
<div class="gf-product shop-grid-view-product">
    <div class="image">
        <a href="{{route('/foodDetails')}}?id=${id}">
            ${sale}
            <img src="${image}" class="img-fluid" style="height: 200px; width: auto;">
        </a>
        <div class="product-hover-icons">
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(14)}}" onClick="addToBasketById(${id});"> <img src="img/cartw.png" class="img-fluid" style="padding: 10px"></a> {{--Add to cart--}}
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(15)}}" > <img src="img/addwash.png" class="img-fluid" style="padding: 10px" onClick="addToWishList(${id});"> </a> {{--Add to wishlist--}}
            <a href="javascript:void(0);" data-tooltip="{{$lang->get(16)}}" onClick="openModal(${id});" data-toggle="modal" data-target="#quick-view-modal-container">  {{--Quick view--}}
            <img src="img/view.png" class="img-fluid" style="padding: 10px"> </a>
        </div>
        </div>
        <div class="product-content">
        <h3 class="product-title" style="height: 3em;"><a href="javascript:void(0);" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
        ${name}
        </a></h3>
        <div class="price-box">
                ${textPrice}
        </div>
    </div>
</div>
        `;
        return text;
    }


</script>
