<div class="position-absolute" style="@if(!$isMobile) top:25px; @else top:20px; @endif  left:110px;">
    <div class="mb-2" style="font-size:15px;">
        <label for="tag_category" >Show Category</label>
        <form action="/event/tag/filter_category" method="POST">
            @csrf
            <select class="form-select" style="font-size:14px; padding:4px auto;" id="tag_category" name="tag_category" onchange="this.form.submit()"  aria-label="Floating label select example" required>
                @php($i=0)
                @foreach($dct_tag as $dtag)
                    @if($i == 0)
                        @if(session()->get("selected_tag_category") == "All")
                            <option value="All" selected>All</option>
                        @else
                            <option value="All">All</option>
                        @endif
                    @endif

                    @if($dtag->slug_name == session()->get("selected_tag_category"))
                        <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                    @else 
                        <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                    @endif

                    @php($i++)
                @endforeach
            </select>
        </form>
    </div>
</div>