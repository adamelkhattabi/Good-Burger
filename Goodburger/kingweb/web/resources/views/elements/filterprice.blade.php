@inject('currency', 'App\Currency')
@inject('lang', 'App\Lang')

<div class="sidebar mb-35">
    <h3 class="sidebar-title">{{$lang->get(11)}}</h3>   {{--Filter By Price--}}
    <div class="sidebar-price">
        <div id="price-range"></div>
        <input type="text" id="price-amount" readonly>
    </div>
</div>

<script>

    $('#price-range').slider({
        range: true,
        min: {{$min}},
        max: {{$max}},
        values: [ {{$min}}, {{$max}} ],
        slide: function( event, ui ) {
            if ({{$currency->rightSymbol()}} == "false"){
                var minText = parseFloat(ui.values[0]).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
                var maxText = parseFloat(ui.values[1]).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
            }
            else{
                var minText = "{{$currency->currency()}}" + parseFloat(ui.values[0]).toFixed({{$currency->symbolDigits()}});
                var maxText = "{{$currency->currency()}}" + parseFloat(ui.values[1]).toFixed({{$currency->symbolDigits()}});
            }
            $('#price-amount').val('{{$lang->get(9)}}' + minText + ' - ' + maxText);  // Price:
            if (!dataLoading) {
                foodMinPrice = ui.values[0];
                foodMaxPrice = ui.values[1];
                lastFoodMinPrice = foodMinPrice;
                lastFoodMaxPrice = foodMaxPrice;
                console.log("foodMinPrice " + foodMinPrice);
                console.log("foodMaxPrice " + foodMaxPrice);
                paginationGoPage(1);
            } else {
                lastFoodMinPrice = ui.values[0];
                lastFoodMaxPrice = ui.values[1];
            }
        }
    });
    var tmin = $('#price-range').slider( 'values', 0 );
    var tmax = $('#price-range').slider( 'values', 1 );
    if ({{$currency->rightSymbol()}} == "false"){
        var minText = parseFloat(tmin).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
        var maxText = parseFloat(tmax).toFixed({{$currency->symbolDigits()}}) + "{{$currency->currency()}}";
    }else{
        var minText = "{{$currency->currency()}}" + parseFloat(tmin).toFixed({{$currency->symbolDigits()}});
        var maxText = "{{$currency->currency()}}" + parseFloat(tmax).toFixed({{$currency->symbolDigits()}});
    }
    $('#price-amount').val( '{{$lang->get(9)}}' + minText + ' - ' + maxText);  // Price:

</script>
