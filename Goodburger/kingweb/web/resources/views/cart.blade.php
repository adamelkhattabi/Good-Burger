@inject('lang', 'App\Lang')
@inject('docs', 'App\Docs')
@inject('settings', 'App\Settings')
@inject('userinfo', 'App\UserInfo')
@inject('currency', 'App\Currency')

<html>
    @include('elements.head', array('title' => $title)) {{--Cart--}}
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
                        <li class="active">{{$title}}</li>  {{--Cart--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-page-container mb-50">

{{--    cart--}}

    <div id="view-cart" class="container" >

        <div class="cart-table table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="pro-thumbnail">{{$lang->get(45)}}</th>  {{--Image--}}
                    <th class="pro-title">{{$lang->get(46)}}</th> {{--Product--}}
                    <th class="pro-price">{{$lang->get(47)}}</th> {{--Price--}}
                    <th class="pro-price">{{$lang->get(115)}}</th> {{--Quantity--}}
                    <th class="pro-price">{{$lang->get(20)}}</th> {{--Total--}}
                    <th class="pro-remove">{{$lang->get(48)}}</th> {{--Remove--}}
                </tr>
                </thead>
                <tbody id="cart_tbody">

                </tbody>
            </table>
        </div>

        <div class="row mt-15 mr-0" >

            <div class="col-5 p-5">
                <div class="cart-table" style="background-color: white;">
                    <div class="account-details-form">
                        <div class="row p-5">
                        <div class="mb-10">
                            <p class="h6">{{$lang->get(100)}}:</p> {{--Coupon Code--}}
                        </div>
                        <div>
                            <input id="couponCode" oninput="changeCoupon();" placeholder="{{$lang->get(116)}}" type="text" >  {{--Enter coupon--}}
                        </div>
                        <div class="col-12 mt-10">
                            <p class="h6 text-right" id="coupon_text"></p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 cart-table p-5" >

                <div style="background-color: white;">
                    <div style=" display: flex; justify-content: space-between;">
                        <span class="h6 pull-left">{{$lang->get(19)}} </span>   {{--Subtotal--}}
                        <span id="" class="h6 pull-right">
                            <span id="basket2_coupon" class="h6"></span>
                            <span id="basket2_subtotal" class="h6 pull-right"></span>
                        </span>
                    </div>
                    <div style=" display: flex; justify-content: space-between;">
                        <span class="h6 pull-left">{{$lang->get(21)}} </span> <span id="basket2_tax" class="h6 pull-right"></span> {{--Tax--}}
                    </div>
                    <div style=" display: flex; justify-content: space-between;">
                        <span class="h6 pull-left">{{$lang->get(22)}} </span> <span id="basket2_fee" class="h6 pull-right"></span> {{--Delivery Fee--}}
                    </div>
                    <div style=" display: flex; justify-content: space-between;">
                        <span class="h4 pull-left">{{$lang->get(20)}} </span> <span id="basket2_total" class="h4 pull-right"></span> {{--Total--}}
                    </div>

                    <div id="minamount" hidden>
                        <p style="text-align: center; color: red; font-size: 25px; margin-top: 20px; font-size: 25px">{{$lang->get(150)}} {{--Minimum order amount--}}
                            <span id="minamount-sum"></span>
                        </p>
                    </div>
                    <div id="btn-checkout" class="floating-cart-btn text-center mt-25">
                        <a href="#" onclick="selectDeliveryMethod();">{{$lang->get(43)}}</a> {{--Checkout--}}
                    </div>
                </div>

            </div>
        </div>
    </div>

{{--    delivery method--}}

    <div id="view-deliveryMethod" class="container" hidden>
        <div class="row" >
            <div class="col-12">
                <div class="cart-table">
                    <div class="account-details-form">
                        <div class="p-5">
                            <div class="myaccount-content">
                                <div class="col-12 mb-20">
                                    <p class="h5">{{$lang->get(119)}}:</p> {{--Select Destination method--}}
                                </div>
                                <div class="col-12 mb-10">
                                    @include('elements.check', array('id' => "pickup", 'text' => $lang->get(120), 'initvalue' => "false", 'callback' => "onRadioDestinationMethod"))  {{--Curbside pickup. I will pick up the groceries myself.--}}
                                </div>
                                <div class="col-12 mb-20">
                                    @include('elements.check', array('id' => "delivered", 'text' => $lang->get(121), 'initvalue' => "true", 'callback' => "onRadioDestinationMethod"))  {{--Deliver my products--}}
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-6 mt-30">
                                        <button onclick="onBackToStart();" class="save-change-btn">{{$lang->get(123)}}</button> {{--Back--}}
                                    </div>
                                    <div class="col-6 mt-30 ">
                                        <div align="right">
                                            <button onclick="onPaymentMethodClick();" class="save-change-btn">{{$lang->get(122)}}</button> {{--Next--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div >

{{--    select address--}}

    <div id="view-Address" class="container" hidden>
        <div class="cart-table">
            <div class="account-details-form">
                <div class="p-5">

                    <div class="myaccount-content">
                        <div class="col-12 mb-20">
                            <p class="h5">{{$lang->get(124)}}:</p> {{--Select Address--}}
                        </div>
                        <div class="col-12 mb-10" id="addresses-body" >
                            <div class="btn-group btn-group-toggle" id="AddressesSelectValue" data-toggle="buttons">

                            </div>
                        </div>
                        <div id="no-address" class="col-12">
                            <p>{{$lang->get(125)}}  {{--No addresses found. You can add addresses --}}
                            <a class="text-primary" href="{{route("/account")}}">
                                {{$lang->get(126)}}  {{--here--}}
                            </a>
                            </p>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-30">
                                <button onclick="onBackToDeliveryMethods();" class="save-change-btn">{{$lang->get(123)}}</button> {{--Back--}}
                            </div>
                            <div class="col-6 mt-30 ">
                                <div id="next-button-2" align="right">
                                    <button onclick="onPaymentMethods();" class="save-change-btn">{{$lang->get(122)}}</button> {{--Next--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    payments method--}}

    <div id="view-Payments" class="container" hidden>
        <div class="cart-table">
            <div class="account-details-form">
                <div class="p-5">

                    <div class="myaccount-content">
                        <div class="col-12 mb-20">
                            <p class="h5">{{$lang->get(127)}}:</p> {{--Payments Method--}}
                        </div>
                        <div class="col-12 mb-10" id="payments-body" >
                            <div class="btn-group btn-group-toggle" id="PaymentsSelectValue" data-toggle="buttons">

                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-30">
                                <button onclick="onBackToDeliveryMethods();" class="save-change-btn">{{$lang->get(123)}}</button> {{--Back--}}
                            </div>
                            <div class="col-6 mt-30 ">
                                <div id="next-button-2" align="right">
                                    <button onclick="onPayment();" class="save-change-btn">{{$lang->get(122)}}</button> {{--Next--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>

<!--=====  down of page  ======-->

@include('elements.footer', array('docs' => $docs->get()))

<!--=====  Dialogs, elements, etc  ======-->

@include('elements.dialogDetails', array('pages' => ""))
@include('elements.cartlist', array('default_tax' => ""))

<script>
    minAmount = '{{$minAmount}}';
    initTableCart();

    //
    // delivery method
    //
    function selectDeliveryMethod(){
        document.getElementById("view-cart").hidden = true;
        document.getElementById("view-deliveryMethod").hidden = false;
    }

    function onBackToStart(){
        document.getElementById("view-cart").hidden = false;
        document.getElementById("view-deliveryMethod").hidden = true;
    }

    function onRadioDestinationMethod(name, value){
        if (!pickup && !delivered){
            if (name == "pickup")
                onSetCheck_pickup(true);
            if (name == "delivered")
                onSetCheck_delivered(true);
        }
        if (name == "pickup" && value)
            onSetCheck_delivered(false);
        if (name == "delivered" && value)
            onSetCheck_pickup(false);

    }

    function onPaymentMethodClick(){
        if (pickup)
            onPaymentMethods();
        if (delivered)
            onAddressSelect();
    }

    //
    // Address
    //

    function onAddressSelect(){
        document.getElementById("view-Address").hidden = false;
        document.getElementById("view-deliveryMethod").hidden = true;
        loadAddressForCheckOuts();
    }

    function onBackToDeliveryMethods(){
        document.getElementById("view-Address").hidden = true;
        document.getElementById("view-deliveryMethod").hidden = false;
        document.getElementById("view-Payments").hidden = true;
    }

    var user_address;

    function loadAddressForCheckOuts(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'GET',
            url: '{{ url("addresses") }}',
            data: {},
            success: function (data){
                console.log("addresses");
                console.log(data);
                var text = "";
                user_address = data.address;
                if (data.address.length == 0){
                    document.getElementById("no-address").hidden = false;
                    document.getElementById("next-button-2").hidden = true;
                    return;
                }
                document.getElementById("next-button-2").hidden = false;
                document.getElementById("no-address").hidden = true;
                data.address.forEach(function(data) {
                    var ch = "";
                    if (data.default == "true")
                        ch = "checked";
                    text += `
                            <div class="col-12" >
                                <div class="col-6">
                                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios${data.id}"  style="width: 10%" ${ch}>
                                </div>
                                <div class="col-6 ml-30">
                                    <label id="exampleRadiosText${data.id}" class="form-check-label" for="exampleRadios${data.id}">${data.text}</label>
                                </div>
                            </div>
                    `;
                });
                //
                //
                document.getElementById("addresses-body").innerHTML = text;
            },
            error: function(e) {
                console.log(e);
                showNotification("pastel-danger", "{{$lang->get(89)}}", "bottom", "center", "", "");  // Something went wrong
            }}
        );
    }

    //
    // Payments method
    //
    let payment_methods = new Array();

    var selected_address_lat = "";
    var selected_address_lng = "";
    var selected_address_text = "";

    function onPaymentMethods(){
        var selectedItem;
        if (!pickup) {
            user_address.forEach(function (data) {
                var value = document.getElementById(`exampleRadios${data.id}`).checked;
                if (value) {
                    selectedItem = data.id;
                    selected_address_text = data.text;
                    selected_address_lat = data.lat;
                    selected_address_lng = data.lng;
                }
                //console.log(`exampleRadios${data.id}`, value);
            });
            console.log(document.getElementById(`exampleRadiosText${selectedItem}`).innerHTML);
        }
        if (pickup)
            document.getElementById("view-deliveryMethod").hidden = true;
        document.getElementById("view-Payments").hidden = false;
        document.getElementById("view-Address").hidden = true;
        loadPaymentsMethods();
    }

    function loadPaymentsMethods(){
        var text = "";
        if ("{{$settings->pmCashOnDelivery()}}" == "true") {
            text += onePaymentItem("cashOnDelivery", "checked", "{{$lang->get(137)}}");  {{--Cash on Delivery--}}
            payment_methods.push("cashOnDelivery");
        }
        if ("{{$settings->pmPayPal()}}" == "true") {
            text += onePaymentItem("PayPal", "", "PayPal");
            payment_methods.push("PayPal");
        }
        if ("{{$settings->pmStripeEnable()}}" == "true") {
            text += onePaymentItem("Stripe", "", "{{$lang->get(138)}}");  {{--Visa & Mastercard--}}
            payment_methods.push("Stripe");
        }

        document.getElementById(`payments-body`).innerHTML = text;
    }

    function onePaymentItem(id, ch, text){
        return `<div class="col-12" >
                    <div class="col-6">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="${id}"  style="width: 10%" ${ch}>
                    </div>
                    <div class="col-6 ml-30">
                        <label class="form-check-label" for="${id}">${text}</label>
                    </div>
                </div>`;
    }

    let order_info = JSON.parse(localStorage.getItem("order_info")) || [];

    //
    // Payment
    //
    function onPayment(){
        pickupPresent = "true";
        var prices = getPrices();
        pickupPresent = "false";
        var order_info = {
            hint: "",
            address: selected_address_text,
            phone: "{{$userinfo->getUserPhone()}}",
            total: prices._total.toFixed({{$currency->symbolDigits()}}),
            lat: selected_address_lat,
            lng: selected_address_lng,
            couponName: _coupon[0],
            curbsidePickup: (pickup) ? "true" : "false",
        };
        console.log("save data1");
        console.log(order_info);
        localStorage.setItem('order_info', JSON.stringify(order_info));
        //
        for (let item of payment_methods) {
            var value = document.getElementById(item).checked;
            if (item == "cashOnDelivery" && value){
                window.location='{{route("/complete")}}';
            }
            if (item == "PayPal" && value){
                payPalPayment();
            }
            if (item == "Stripe" && value){
                window.location='{{route("/stripe")}}';
            }
        }
    }

</script>

@include('elements.paypal', array())


<!-- scroll to top  -->
<a href="#" class="scroll-top"><img src="img/arrowup.png" style="padding: 10px;" class="img-fluid"> </a>

<script src="js/main.js"></script>
<script src="js/bootstrap-notify/bootstrap-notify.js"></script>
<script src="js/utils.js"></script>

</body>
</html>
