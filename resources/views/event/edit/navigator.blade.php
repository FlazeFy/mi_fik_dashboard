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
    <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:0" title="Set as draft" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-eye"></i> Public</a>
</div>
