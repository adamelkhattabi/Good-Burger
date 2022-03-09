@inject('currency', 'App\Currency')

<head>
    <meta charset="utf-8">
    <title>{{$title}}</title>
    <meta name="_token" content="{{csrf_token()}}" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon-->
    <link rel="icon" href="img/logo.png" type="image/png">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugin.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/utils.css" rel="stylesheet">

    <script src="js/modernizr-2.8.3.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/colors.js"></script>
    <script src="js/plugin.js"></script>

    @include('elements.maincss', array())

    <script>
        function makePrice(price){
            @if ($currency->rightSymbol() == "false")
                return '{{$currency->currency()}}' + parseFloat(price).toFixed({{$currency->symbolDigits()}});
            @else
                return parseFloat(price).toFixed({{$currency->symbolDigits()}}) + '{{$currency->currency()}}';
            @endif
        }

    </script>

</head>
