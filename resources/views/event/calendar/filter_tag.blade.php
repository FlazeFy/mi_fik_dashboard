<div class="position-relative me-1">
    <button class="btn btn-primary px-3 py-2" type="button" id="section-select-tag" data-bs-toggle="dropdown"
        ><i class="fa-solid fa-hashtag"></i>
        @php($tag_coll = session()->get('selected_tag_'.$from))

        @if($tag_coll != "All")
            {{count($tag_coll)}} {{ __('messages.slct_tag') }}
        @else
            {{ __('messages.all_tag') }}
        @endif
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" style="width:480px;" onclick="event.stopPropagation()" aria-labelledby="section-select-tag">
        <span class="filter-section dropdown-item p-0">
            <div class="dropdown-header">
                <h6 class="dropdown-title">{{ __('messages.filter_tag') }}</h6>
                <form action="/event/calendar/set_filter_tag/1/{{$from}}" method="POST" class="position-absolute" style="right:15px; top:20px;">
                    @csrf
                    <button class="btn btn-noline text-danger" type="submit"><i class="fa-regular fa-trash-can"></i> {{ __('messages.clear') }}</button>
                </form>
            </div><hr>
            <div class="dropdown-body">
                <form action="/event/calendar/set_filter_tag/0/{{$from}}" method="POST" style="white-space:normal;">
                    @csrf
                    @if(session()->get('role_key') == 1)
                        @php($colltag = $tag)
                    @else   
                        @php($colltag = $mytag)
                    @endif

                    @foreach($colltag as $tg)
                        @php($found = false)
                        @php($check = "")

                        @if(is_array($tag_coll))
                            @foreach($tag_coll as $slct)
                                @if(is_array($colltag))
                                    @if($slct->slug_name == $tg['slug_name'])
                                        @php($found = true)
                                    @endif
                                @else 
                                    @if($slct->slug_name == $tg->slug_name)
                                        @php($found = true)
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if($found)
                            @php($check = "Checked")
                        @endif

                        <a class="tag-check action">
                            <label>
                                @if(is_array($colltag))
                                    <input name="tag[]" type="checkbox" value="{{$tg['slug_name']}}__{{$tg['tag_name']}}" id="flexCheckDefault" <?php echo $check; ?>>
                                    <span>{{$tg['tag_name']}}</span>
                                @else 
                                    <input name="tag[]" type="checkbox" value="{{$tg->slug_name}}__{{$tg->tag_name}}" id="flexCheckDefault" <?php echo $check; ?>>
                                    <span>{{$tg->tag_name}}</span>
                                @endif
                            </label>
                        </a>
                    @endforeach
            </div><hr>
            <div class="dropdown-footer">
                    <button class="btn btn-submit float-end mb-3"><i class="fa-solid fa-filter"></i> {{ __('messages.apply_filter') }}</button>
                </form> 
            </div>
        </span>
    </div>
</div>

