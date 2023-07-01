<p class="my-1 text-secondary" style="@if(!$isMobile) font-size:14px; @endif">Active filters : 
    @php($tag_coll = session()->get('selected_tag_calendar'))
    @php($date = session()->get('filtering_date'))
    @php($order = session()->get('ordering_event'))

    @if($tag_coll != "All")
        @foreach($tag_coll as $tg)
            {{ucfirst(str_replace("_", " ", $tg))}},
        @endforeach
    @endif

    @if($order == "DESC")
        Descending
    @else 
        Ascending
    @endif

    @if($date != "all")
        @php($dt = explode("_", $date))
        , Start from {{date("d M Y", strtotime($dt[0]))}} until {{date("d M Y", strtotime($dt[1]))}}
    @endif

    <span id="filter_title_search_msg"></span>
</p>

<script>
    getFilterTitleMsg(search_storage)

    function getFilterTitleMsg(check){
        var res = check
        if(check != null && check.trim() != ''){
            document.getElementById("filter_title_search_msg").innerHTML = ', Title like "'+res+'"';
        }
    }
</script>