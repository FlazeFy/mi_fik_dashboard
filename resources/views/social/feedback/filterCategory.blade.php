<div class="mb-2" style="font-size:15px;" id="filter-suggest">
    <label for="tag_category">{{ __('messages.filter_suggest') }}</label>
    <form action="/social/feedback/filter_suggest" method="POST">
        @csrf
        <select class="form-select table-header" id="feedback_suggest" name="feedback_suggest" onchange="this.form.submit()"  aria-label="Floating label select example" required>
            @php($i=0)
            @foreach($dct as $dtag)
                @if($i == 0)
                    @if(session()->get("selected_filter_suggest") == "All")
                        <option value="All" selected>{{ __('messages.all') }}</option>
                    @else
                        <option value="All">{{ __('messages.all') }}</option>
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