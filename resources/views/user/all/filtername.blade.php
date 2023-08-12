<div class="position-relative">
    <button class="btn btn-primary px-3" type="button" id="section-date-picker" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false"><i class="fa-solid fa-filter"></i> Filter Name
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-date-picker">
        <span class="filter-section dropdown-item py-2">
            <div class="dropdown-header">
                <h6 class="dropdown-title">Filter Name</h6>
                <form action="/user/all/set_filter_name/1/all" method="POST" class="position-absolute" style="right:15px; top:20px;">
                    @csrf
                    <button class="btn btn-noline text-danger" type="submit"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</button>
                </form>
            </div><hr>
            <div class="dropdown-body">
                <form action="/user/all/set_filter_name/0/front" method="POST" style="white-space:normal;">
                    @csrf

                    @php($alph = ["all","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"])
                    <h6>Front Name</h6>
                    @foreach($alph as $ap)
                        @if($ap == session()->get('filtering_fname'))
                            @php($res = "selected")
                        @else 
                            @php($res = "")
                        @endif
                        <input class="btn btn-filter {{$res}}" type="submit" value="{{$ap}}" name="filter_alph">
                    @endforeach
                </form>            
                <form action="/user/all/set_filter_name/0/last" method="POST" style="white-space:normal;">
                    @csrf
                    
                    <h6 class="mt-4">Last Name</h6>
                    @foreach($alph as $ap)
                        @if($ap == session()->get('filtering_lname'))
                            @php($res = "selected")
                        @else 
                            @php($res = "")
                        @endif
                        <input class="btn btn-filter {{$res}}" type="submit" value="{{$ap}}" name="filter_alph">
                    @endforeach
                </form>
            </div>
        </span>
    </div>
</div>