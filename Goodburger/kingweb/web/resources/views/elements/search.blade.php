@inject('lang', 'App\Lang')

    <div class="header-advance-search">
        <input id="search" type="text" placeholder="{{$lang->get(44)}}">  {{--Search your product--}}
        <button>
            <div class="px-0" >
                <img src="img/search.png" onclick="onSearch();" class="img-fluid" style="padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 5px"/>
            </div>
        </button>
    </div>

<script>
    var isSearch = false;
    function onSearch(){
        isSearch = true;
        console.log(document.getElementById("search").value);
        search = document.getElementById("search").value;
        if ("{{\Request::route()->getName()}}" == "/main")
            paginationGoPage(1);
        else
            window.location='{{route('/main')}}?search='+search;
    }

</script>
