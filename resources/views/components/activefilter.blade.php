<p class="my-1 text-secondary" style="@if(!$isMobile) font-size:14px; @endif">{{ __('messages.active_filter') }} : 
    @php($tag_coll = session()->get('selected_tag_home'))
    @php($date = session()->get('filtering_date'))
    @php($order = session()->get('ordering_event'))

    @if($tag_coll == "All" && $date == "all")
        -
    @else
        @if($tag_coll != "All")
            @php($count_active_tag = count($tag_coll))
            @php($i = 0)
            @foreach($tag_coll as $tg)
                @if($count_active_tag - 1 != $i)
                    {{$tg->tag_name}},
                @else 
                    {{$tg->tag_name}}
                @endif
                @php($i++)
            @endforeach
        @endif

        @if($date != "all")
            @php($dt = explode("_", $date))
            , {{ __('messages.start_from') }} {{date("d M Y", strtotime($dt[0]))}} {{ __('messages.until') }} {{date("d M Y", strtotime($dt[1]))}}
        @endif

        <span id="filter_title_search_msg"></span>
    @endif
</p>

<script>
    getFilterTitleMsg(search_storage);

    function getFilterTitleMsg(check){
        var res = check
        if(check != null && check.trim() != ''){
            document.getElementById("filter_title_search_msg").innerHTML = `, {{ __('messages.title_like') }} ${res}`;
        }
    }
</script>