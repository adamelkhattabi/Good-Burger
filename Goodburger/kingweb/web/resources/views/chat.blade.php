@inject('lang', 'App\Lang')
@inject('docs', 'App\Docs')
@inject('currency', 'App\Currency')
@inject('category', 'App\Categories')

<html>

<head>
    @include('elements.head', array('title' => $lang->get(146)))    {{--chat--}}

    <style>
        .containerChat {
            border: 2px solid #dedede;
            background-color: #f1f1f1;
            border-radius: 15px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>

<body>

@include('elements.header', array())

<!-- breadcrumb -->
<div class="breadcrumb-area mb-0 mt-20">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-container">
                    <ul>
                        <li><a href="{{route('/')}}"><i class="fa fa-home"></i>{{$lang->get(12)}}</a></li>      {{--HOME--}}
                        <li class="active">{{$lang->get(146)}}</li> {{--Chat--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-page-container mb-50">
    <div class="container">
        <div style="height: 75vh; min-height: 75vh; width: 100%; position:relative; background-color: white">
            <div id="messagesWindow" style="max-height:65vh; min-height: 65vh; overflow:auto;border:1px solid grey;">
            </div>
            <div id="sendMsg" style="height: 10vh; border:1px solid grey;">
                <div class="col-md-12" style="margin-top: 10px">
                    <div class="header-advance-search">
                        <input id="messageText" type="text" placeholder="{{$lang->get(147)}}">  {{--Input text--}}
                        <button style="background: white">
                            <div class="px-0" >
                                <img src="img/iconsend.png" onclick="sendMsg();" class="img-fluid" style="padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 5px"/>
                            </div>
                        </button>
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
@include('elements.wishlist', array('default_tax' => ""))

<!-- scroll to top  -->
<a href="#" class="scroll-top"><img src="img/arrowup.png" style="padding: 10px;" class="img-fluid"> </a>

<script>

    var currentLength = 0;

    function myGet() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("getChatMessages") }}',
            data: {
            },
            success: function (data){
                console.log(data);
                if (currentLength != data.messages.length)
                    drawMsg(data);
                document.getElementById("chat-count").hidden = true;
            },
            error: function(e) {
                console.log(e);
            }}
        );
    }

    setInterval(myGet, 10000); // one time in 10 sec
    myGet();

    function drawMsg(data){
        var last = "";
        var msg = document.getElementById("messagesWindow");
        msg.innerHTML = "";

        currentLength = data.messages.length;
        data.messages.forEach(function(entry){
            var now = entry.created_at.substr(0, 11);
            if (now != last) {
                var div = document.createElement("div");
                div.innerHTML = `
                        <div class="containerChat" style="width:20%; margin-left: 40%; margin-right: 40%;">
                            <div style="text-align: center;">
                                <div class="font-14">`+ now +`</div>
                            </div>
                        </div>
                        `;
                last = now;
                msg.appendChild(div);
            }
            var div = document.createElement("div");
            var date = entry.created_at.substr(11,5);
            if (entry.author == "customer"){
                div.innerHTML = `
                            <div class="containerChat" style="width:60%; margin-left: 35%; margin-right: 5%; background-color: #cbecff">
                                <h4>`+ entry.text +`</h4>
                                <div align="right"><h5>` + date + `</h5></div>
                            </div>
                        `;
            }else{
                div.innerHTML = `
                            <div class="containerChat" style="width:60%; margin-left: 5%; margin-right: 35%; ">
                                <h4>`+ entry.text +`</h4>
                                <div align="right"><h5>` + date + `</h5></div>
                            </div>
                        `;
            }
            msg.appendChild(div);
        });
        msg.scrollTop = msg.scrollHeight;
    }

    function sendMsg(){
        var text = document.getElementById("messageText").value;
        if (text == "")
            return;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("chatSendMessage") }}',
            data: {
                text: text,
            },
            success: function (data){
                console.log(data);
                document.getElementById("messageText").value = "";
                drawMsg(data)
            },
            error: function(e) {
                console.log(e);
            }}
        );
    }

</script>

<script src="js/main.js"></script>
<script src="js/bootstrap-notify/bootstrap-notify.js"></script>
<script src="js/utils.js"></script>

</body>
</html>
