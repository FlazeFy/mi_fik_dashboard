@if(session()->get('ordering_event') == "ASC")
    <form class="d-inline" action="/homepage/ordered/DESC" title="{{ __('messages.sort_asc_des') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-3 py-2 @if($isMobile) w-100 @endif" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) {{ __('messages.asc') }} @else Asc @endif</button>
    </form>
@else
    <form class="d-inline" action="/homepage/ordered/ASC" title="{{ __('messages.sort_desc_des') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-3 py-2 @if($isMobile) w-100 @endif" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) {{ __('messages.desc') }} @else Desc @endif</button>
    </form>
@endif