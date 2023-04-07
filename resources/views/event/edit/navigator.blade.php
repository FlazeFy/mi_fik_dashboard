<style>
    .event-navigator {
        position: relative;
        height: 30px;
        margin-top: 25px;
    }
    .event-navigator .navigator-link {
        color: #F78A00;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
        font-size:18px !important;
    }
    .event-navigator .navigator-right {
        position: absolute;
    }
</style>

<div class="event-navigator">
    <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > <a class="navigator-link" onclick="location.href='/event/detail/{{$c->slug_name}}'">Detail</a> > Edit > {{$c->content_title}}</span>
    <form action="/event/edit/update/draft/{{$c->slug_name}}" method="POST" class="d-inline">
        @csrf
        @if($c->is_draft == 1)
            <input hidden name="is_draft" value="0">
            <button class="btn btn-success navigator-right rounded-pill px-4 py-2" style="right:0" title="Unset draft" type="submit"><i class="fa-regular fa-eye"></i> Set as Public</button>
        @else 
            <input hidden name="is_draft" value="1">
            <button class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:0" title="Set draft" type="submit"><i class="fa-solid fa-eye-slash"></i> Set as draft</button>
        @endif
    </form>
</div>
