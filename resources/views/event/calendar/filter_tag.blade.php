<style>
    .dropdown-menu{
        border: none;
        margin: 10px 0 0 0 !important; 
        border-radius: 15px !important;
        padding-bottom: 0px;
    }
    .dropdown-menu-end .dropdown-item.active, .dropdown-menu-end .dropdown-item:active, .dropdown-menu-end .dropdown-item:hover{
        background: none !important;
    }
</style>

<div class="position-relative">
    <button class="btn btn-primary px-3" type="button" id="section-select-tag" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        @php($tag_coll = session()->get('selected_tag_calendar'))
        @if($tag_coll != "All")
            {{count($tag_coll)}} Selected Tags
        @else
            All Tags
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="section-select-tag">
        <span class="dropdown-item py-2">
            <label class="fw-bold">Available Tag</label><br>
            <form action="/event/calendar/set_filter_tag/1" method="POST">
                @csrf
                <div class="form-check">
                    @if(session()->get('selected_tag_calendar') == "All")
                        <input class="form-check-input" type="checkbox" value="all" id="flexCheckDefault" checked onchange="this.form.submit()">
                    @else 
                        <input class="form-check-input" type="checkbox" value="all" id="flexCheckDefault" onchange="this.form.submit()">
                    @endif
                    <label class="form-check-label mt-1" for="flexCheckDefault">
                        &nbsp All
                    </label>
                </div>
            </form>

            <form action="/event/calendar/set_filter_tag/0" method="POST" class="row">
                @csrf
                @foreach($tag as $tg)
                    <!-- Initial variable -->
                    @php($found = false)
                    @php($check = "")

                    <!-- Check if tag is selected -->
                    @if(is_array(session()->get('selected_tag_calendar')))
                        @foreach(session()->get('selected_tag_calendar') as $slct)
                            @if($slct == $tg->slug_name)
                                @php($found = true)
                            @endif
                        @endforeach
                    @endif

                    @if($found)
                        @php($check = "Checked")
                    @endif

                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" name="slug_name[]" type="checkbox" value="{{$tg->slug_name}}" id="flexCheckDefault" <?php echo $check; ?>>
                            <label class="form-check-label mt-1" for="flexCheckDefault">
                                &nbsp {{$tg->tag_name}}
                            </label>
                        </div>
                    </div>
                @endforeach
                <div class="col-4">
                    <button class="btn btn-submit w-75" style="width:60px;"><i class="fa-solid fa-filter"></i> Filter</button>
                </div>
            </form>            
        </span>
    </div>
</div>