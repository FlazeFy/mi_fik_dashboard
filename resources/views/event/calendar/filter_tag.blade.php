<div class="position-relative me-2">
    <button class="btn btn-primary px-3" type="button" id="section-select-tag" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false"><i class="fa-solid fa-hashtag"></i> 
        @php($tag_coll = session()->get('selected_tag_calendar'))
        @if($tag_coll != "All")
            {{count($tag_coll)}} Selected Tags
        @else
            All Tags
        @endif
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" aria-labelledby="section-select-tag">
        <span class="dropdown-item">
            <div class="dropdown-header">
                <h6 class="dropdown-title">Filter Tag</h6>
                <form action="/event/calendar/set_filter_tag/1" method="POST" class="position-absolute" style="right:15px; top:20px;">
                    @csrf
                    <button class="btn btn-noline text-danger" type="submit"><i class="fa-regular fa-trash-can"></i> Clear All</button>
                </form>
            </div><hr>
            <div class="dropdown-body">
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
                            <div class="form-check custom">
                                <input class="form-check-input" name="slug_name[]" type="checkbox" value="{{$tg->slug_name}}" id="flexCheckDefault" <?php echo $check; ?>>
                                <label class="form-check-label mt-1" for="flexCheckDefault">
                                    &nbsp {{$tg->tag_name}}
                                </label>
                            </div>
                        </div>
                    @endforeach
            </div><hr>
            <div class="dropdown-footer">
                    <button class="btn btn-submit float-end mb-3"><i class="fa-solid fa-filter"></i> Apply Filter</button>
                </form> 
            </div>
        </span>
    </div>
</div>

