<div class="position-relative">
    <button class="btn btn-primary px-3" type="button" id="section-select-tag" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        Select Tags
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-select-tag">
        <span class="dropdown-item">
            <label class="mb-2">Available Tag</label><br>
            @foreach($tag as $tg)
                <!-- Initial variable -->
                @php($found = false)
                @php($check = "")

                <!-- Check if tag is selected -->
                @foreach(session()->get('selected_tag_calendar') as $slct)
                    @if($slct == $tg->tag_name)
                        @php($found = true)
                    @endif
                @endforeach
                @if($found)
                    @php($check = "Checked")
                @endif

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$tg->tag_name}}" id="flexCheckDefault" <?php echo $check; ?>>
                    <label class="form-check-label mt-1" for="flexCheckDefault">
                        &nbsp {{$tg->tag_name}}
                    </label>
                </div>
            @endforeach
        </span>
    </div>
</div>