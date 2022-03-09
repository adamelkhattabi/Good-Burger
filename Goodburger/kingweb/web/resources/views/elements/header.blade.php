@inject('lang', 'App\Lang')

@include('elements.cartlist', array())

<header>
    <div class="header-top pt-10 pb-10 pt-lg-10 pb-lg-10 pt-md-10 pb-md-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center text-sm-left">
                    <div class="lang-currency-dropdown">
                        <ul>
                            <li> <a href="#">{{$lang->defLangName()}} <i class="fa fa-chevron-down"></i></a>
                                <ul>
                                    @foreach($lang->langs() as $key => $data)
                                        @if ($lang->defLang() != $data["file"])
                                            <li><a href="{{route('/setLang')}}?lang={{$data['file']}}">{{$data["name"]}}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12  text-center text-sm-right">
                    <div class="header-top-menu">
                        <ul>
                            @if (Auth::check())
                                <li>
                                    <a href="{{route('/chat')}}" style="display: inline-block;">{{$lang->get(146)}}</a>  {{--Chat--}}
                                    <div id="chat-count" style="background-color: red; height: 20px; width: 20px; border-radius: 50%; display: inline-block" hidden>
                                        <div id="chat-messages-count" style="display: table; margin: 0 auto; color: white; vertical-align: middle; text-align: center;">0</div>
                                    </div>
                                </li>
                            @endif
                            <li><a href="{{route('/account')}}">{{$lang->get(41)}}</a></li> {{--My account--}}
                            <li><a href="{{route('/wishlist')}}">{{$lang->get(42)}}</a></li> {{--Wishlist--}}
                            @if (Auth::check())
                                <li><a href="{{route('/cart')}}">{{$lang->get(113)}}</a></li> {{--View Cart--}}
                                <li><a onclick="logoutFromAccount();" href="#" >{{$lang->get(56)}}</a></li> {{--Logout--}}
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom header-bottom-one header-sticky">
        <div class="container">
            <div class="row">
                <div class="row col-md-12 q-mt10">
                    <div class="col-md-8 px-0">
                        <div class="d-flex" style="margin-left: 20px">
                            <a href="{{route('/')}}">
                                <img src="img/logo.png" class="img-fluid d-inline" style="max-height: 80px;">
                            </a>
                            <div class="container">
                                @include('elements.search', array('default_tax' => ""))
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-6  align-self-center">
                        @include('elements.basket', array('1' => ""))
                    </div>
                </div>

                <!-- Menu  -->
                @include('elements.menu', array('parent' => '-1'))

                <div class="col-12">
                    <!-- Mobile Menu -->
                    <div class="mobile-menu d-block d-lg-none"></div>
                </div>
            </div>
        </div>
    </div>
</header>


<script>
    function logoutFromAccount(){
        clearBasket();
        window.location='{{route('/logout')}}';
    }

    setInterval(getChatNewMessages, 10000); // one time in 10 sec
    getChatNewMessages();

    function getChatNewMessages() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("getChatMessagesNewCount") }}',
            data: {
            },
            success: function (data){
                console.log(data);
                if (data.error != "0")
                    return;
                if (document.getElementById("chat-count") != null)
                    document.getElementById("chat-count").hidden = true;
                if (data.count == 0)
                    return;
                document.getElementById("chat-count").hidden = false;
                document.getElementById("chat-messages-count").innerHTML = data.count;
            },
            error: function(e) {
                console.log(e);
            }}
        );
    }

</script>
