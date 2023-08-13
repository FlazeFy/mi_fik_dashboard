<div class="position-relative me-1">
    <button class="btn btn-primary px-3" type="button" id="section-select-tag" data-bs-toggle="dropdown"
        ><i class="fa-solid fa-hashtag"></i> 
        @php($tag_coll = session()->get('selected_role_user'))
        @if($tag_coll != "All")
            {{count($tag_coll)}} {{ __('messages.slct_tag') }}
        @else
            {{ __('messages.all_tag') }}
        @endif
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-select-tag">
        <span class="dropdown-item">
            <div class="dropdown-header">
                <h6 class="dropdown-title">Filter Role</h6>
                <form action="/user/all/set_filter_role/1" method="POST" class="position-absolute" style="right:15px; top:20px;">
                    @csrf
                    <button class="btn btn-noline text-danger" type="submit"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</button>
                </form>
            </div><hr>
            <div class="dropdown-body">
                <form action="/user/all/set_filter_role/0" method="POST" style="white-space:normal;">
                    @csrf
                    @if(session()->get('role_key') == 1)
                        @php($colltag = $tag)
                    @else   
                        @php($colltag = $mytag)
                    @endif

                    @foreach($colltag as $tg)
                        <!-- Initial variable -->
                        @php($found = false)
                        @php($check = "")

                        <!-- Check if tag is selected -->
                        @if(is_array(session()->get('selected_role_user')))
                            @foreach(session()->get('selected_role_user') as $slct)
                                @if(is_array($colltag))
                                    @if($slct == $tg['slug_name'])
                                        @php($found = true)
                                    @endif
                                @else 
                                    @if($slct == $tg->slug_name)
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
                                    <input class="" name="slug_name[]" type="checkbox" value="{{$tg['slug_name']}}" id="flexCheckDefault" <?php echo $check; ?>>
                                    <span>{{$tg['tag_name']}}</span>
                                @else 
                                    <input class="" name="slug_name[]" type="checkbox" value="{{$tg->slug_name}}" id="flexCheckDefault" <?php echo $check; ?>>
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

