@inject('lang', 'App\Lang')
@inject('docs', 'App\Docs')

<html>
    @include('elements.head', array('title' => $title)) {{--Wishlist--}}
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
                        <li class="active">{{$lang->get(31)}}</li>  {{--Wishlist--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-page-container mb-50">
    <div class="container">

        <div class="cart-table table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="pro-thumbnail">{{$lang->get(45)}}</th>  {{--Image--}}
                    <th class="pro-title">{{$lang->get(46)}}</th> {{--Product--}}
                    <th class="pro-price">{{$lang->get(47)}}</th> {{--Price--}}
                    <th class="pro-remove">{{$lang->get(48)}}</th> {{--Remove--}}
                </tr>
                </thead>
                <tbody id="wish_tbody">

                </tbody>
            </table>
        </div>

    </div>
</div>

<!--=====  down of page  ======-->

@include('elements.footer', array('docs' => $docs->get()))

<!--=====  Dialogs, elements, etc  ======-->

@include('elements.dialogDetails', array('pages' => ""))
@include('elements.wishlist', array('default_tax' => ""))

<script>

    initTableWish();

</script>

<!-- scroll to top  -->
<a href="#" class="scroll-top"><img src="img/arrowup.png" style="padding: 10px;" class="img-fluid"> </a>

<script src="js/main.js"></script>
<script src="js/bootstrap-notify/bootstrap-notify.js"></script>
<script src="js/utils.js"></script>

</body>
</html>
