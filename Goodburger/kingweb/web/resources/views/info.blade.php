@inject('lang', 'App\Lang')
@inject('docs', 'App\Docs')

<html>

<head>
    @include('elements.head', array('title' => $breadcrumb))
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
                        <li class="active">{{$breadcrumb}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shop page container -->

<div class="shop-page-container mb-50">
    <div class="container">
        {!! $text !!}
    </div>
</div>

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
