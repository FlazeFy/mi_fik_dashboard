<div style="position:absolute; right: 60px; top:-5px;">
    @if(session()->get('ordering_finished') == "ASC")
        <form class="d-inline" action="/event/calendar/ordered/DESC" title="{{ __('messages.sort_asc_des') }}" method="POST">
            @csrf
            <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) {{ __('messages.asc') }} @else Asc @endif</button>
        </form>
    @else
        <form class="d-inline" action="/event/calendar/ordered/ASC" title="{{ __('messages.sort_desc_des') }}" method="POST">
            @csrf
            <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i>@if(!$isMobile) {{ __('messages.desc') }} @else Desc @endif</button>
        </form>
    @endif
</div>