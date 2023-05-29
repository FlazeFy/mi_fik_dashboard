<div class="position-absolute" style="top:0; left:110px;">
    <div class="mb-2" style="font-size:15px;">
        <label for="tag_category" >Filter Suggestion</label>
        <form action="/social/feedback/filter_suggest" method="POST">
            @csrf
            <select class="form-select" style="font-size:14px; padding:4px auto;" id="feedback_suggest" name="feedback_suggest" onchange="this.form.submit()"  aria-label="Floating label select example" required>
                @php($i=0)
                @foreach($dct as $dtag)
                    @if($i == 0)
                        @if(session()->get("selected_filter_suggest") == "All")
                            <option value="All" selected>All</option>
                        @else
                            <option value="All">All</option>
                        @endif
                    @endif

                    @if($dtag->slug_name == session()->get("selected_filter_suggest"))
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