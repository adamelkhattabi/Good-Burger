@inject('currency', 'App\Currency')

<script>

    var minAmount = "";

    function initTableCart(){
        if (document.getElementById("cart_tbody") == null)
            return;

        document.getElementById("cart_tbody").innerHTML = basketItems.map((product, i) => {
            if (product.discountprice != 0)
                var currentPrice = parseFloat(product.discountprice);
            else
                var currentPrice = parseFloat(product.price);
            var subtotal = product.price * product.count;
            var subtotal = getPriceString(currentPrice * product.count);
            var price = getPriceString(currentPrice);
            {{--if ({{$currency->rightSymbol()}} == "false"){--}}
            {{--    var price = parseFloat(currentPrice).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";--}}
            {{--    subtotal = parseFloat(subtotal).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";--}}
            {{--}else{--}}
            {{--    var price = "{{$currency->currency()}}" + parseFloat(currentPrice).toFixed({{$currency->symbolDigits()}});--}}
            {{--    subtotal = "{{$currency->currency()}}" + parseFloat(subtotal).toFixed({{$currency->symbolDigits()}});--}}
            {{--}--}}
            // extras
            var text_extras = "";
            if (product.extras != null)
                product.extras.forEach(function (data) {
                    if (!data.select)
                        return;
                    text_extras = `${text_extras}
                        <tr>
                            <td class="pro-thumbnail"></td>
                            <td class="pro-title">${data.name}</td>
                            <td class="pro-price"><span>${getPriceString(data.price)}</span></td>
                            <td class="pro-quantity">${product.count}</td>
                            <td class="pro-subtotal"><span>${getPriceString(data.price*product.count)}</span></td>
                            <td class="pro-remove"></td>
                        </tr>
                        ${text_extras}
                        `;
                });
            return `
                <tr>
                    <td class="pro-thumbnail"><a href="{{route('/foodDetails')}}?id=${product.id}"><img src="${product.image}" class="img-fluid" alt="Product"></a></td>
                    <td class="pro-title"><a href="{{route('/foodDetails')}}?id=${product.id}">${product.title}</a></td>
                    <td class="pro-price"><span>${price}</span></td>
                    <td class="pro-quantity"><div class="pro-qty"><input id="qt${product.id}" type="text" value="${product.count}"></div></td>
                    <td class="pro-subtotal"><span>${subtotal}</span></td>
                    <td class="pro-remove"><a href="#"><img src="img/delete.png" onclick="removeFromCart(${product.id});" class="img-fluid" style="max-height: 30px"/></a></td>
                </tr>
                ${text_extras}
                `;
        }).join("");
        initQuantity();
        var prices = getPrices();
        console.log("basket2_subtotal2");
        console.log(document.getElementById("basket2_coupon"));
        if (prices.coupon != "")
            document.getElementById("basket2_coupon").innerHTML = `<del>${prices.couponPrice}</del>`;
        else
            document.getElementById("basket2_coupon").innerHTML = "";
        document.getElementById("basket2_subtotal").innerHTML = prices.subTotal;
        document.getElementById("basket2_tax").innerHTML = prices.tax;
        document.getElementById("basket2_fee").innerHTML = prices.fee;
        document.getElementById("basket2_total").innerHTML = prices.total;
        console.log(prices.total);
        console.log(minAmount);
        if (minAmount > prices._total){
            document.getElementById("minamount").hidden = false;
            document.getElementById("minamount-sum").innerHTML = getPriceString(minAmount);
            document.getElementById("btn-checkout").hidden = true;
        }else{
            document.getElementById("minamount").hidden = true;
            document.getElementById("btn-checkout").hidden = false;
        }
    }

    function removeFromCart(id){
        removeFromBasket(id);
        redrawCart();
    }

    function redrawCart(){
        initBasket();
        initTableCart("");
        initQuantity();
        saveOrder();
    }

    function proQtyCallback(el, val){
        var id = el.id.substr(2, el.id.length - 2)
        console.log(id, val);
        for (var i = basketItems.length; i--;)
            if (basketItems[i].id == id) {
                basketItems[i].count = val;
                break;
            }
        localStorage.setItem('items', JSON.stringify(basketItems));
        redrawCart();
    }

    function initQuantity() {
        console.log("initQuantity");
        $('.pro-qty').append('<a href="#" class="inc qty-btn">+</a>');
        $('.pro-qty').append('<a href="#" class= "dec qty-btn">-</a>');
        $('.qty-btn').on('click', function (e) {
            e.preventDefault();
            var $button = $(this);
            var oldValue = $button.parent().find('input').val();
            if ($button.hasClass('inc')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            proQtyCallback($button.parent().find('input').get(0), newVal);
            $button.parent().find('input').val(newVal);
        });
    }

    function changeCoupon(){
        redrawCart();
    }

</script>
