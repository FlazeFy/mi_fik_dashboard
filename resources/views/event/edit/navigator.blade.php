<style>
    .event-navigator {
        position: relative;
        height: 30px;
        margin-top: 25px;
    }
    .event-navigator .navigator-link {
        color: var(--primaryColor);
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
        font-size:18px !important;
    }
</style>

<div class="event-navigator d-flex justify-content-between">
    <div>
        <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > <a class="navigator-link" onclick="location.href='/event/detail/{{$c->slug_name}}'">Detail</a> > Edit > {{$c->content_title}}</span>
    </div>
    <div style="white-space:nowrap;">
    @if(!$isMobile)
        <form action="/event/edit/update/draft/{{$c->slug_name}}" method="POST" class="d-inline">
            <input hidden name="content_title" value="{{$c->content_title}}">
            @csrf
            @if($c->is_draft == 1)
                <input hidden name="is_draft" value="0">
                <button class="btn btn-success navigator-right rounded-pill px-4 py-2" title="Unset draft" type="submit"><i class="fa-regular fa-eye"></i> Set as Public</button>
            @else 
                <input hidden name="is_draft" value="1">
                <button class="btn btn-info navigator-right rounded-pill px-4 py-2" title="Set draft" type="submit"><i class="fa-solid fa-eye-slash"></i> Set as draft</button>
            @endif
        </form>
        <a class="btn btn-danger navigator-right rounded-pill px-4 py-2" onclick="location.href='/event/detail/{{$c->slug_name}}'" title="Close" ><i class="fa-solid fa-xmark fa-lg"></i> Close</a>
    @endif
    </div>
</div>
