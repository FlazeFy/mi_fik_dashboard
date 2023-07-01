@if(session()->get('ordering_event') == "ASC")
    <form class="d-inline" action="/homepage/ordered/DESC" title="Sort content by ascending" method="POST">
        @csrf
        <button class="btn btn-primary px-3 py-2 @if($isMobile) w-100 @endif" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) Ascending @else Asc @endif</button>
    </form>
@else
    <form class="d-inline" action="/homepage/ordered/ASC" title="Sort content by descending" method="POST">
        @csrf
        <button class="btn btn-primary px-3 py-2 @if($isMobile) w-100 @endif" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) Descending @else Desc @endif</button>
    </form>
@endif