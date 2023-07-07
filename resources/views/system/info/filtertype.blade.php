<div class="mb-2" style="font-size:15px;" id="filter-type">
    <label for="tag_category" >Filter Type</label>
    <form action="/system/info/filter_type" method="POST">
        @csrf
        <select class="form-select table-header" id="info_type" name="info_type" onchange="this.form.submit()"  aria-label="Floating label select example" required>
            @php($i=0)
            @foreach($dct as $dtag)
                @if($i == 0)
                    @if(session()->get("selected_filter_info_type") == "All")
                        <option value="All" selected>All</option>
                    @else
                        <option value="All">All</option>
                    @endif
                @endif

                @if($dtag->slug_name == session()->get("selected_filter_info_type"))
                    <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                @else 
                    <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                @endif

                @php($i++)
            @endforeach
        </select>
    </form>
</div>