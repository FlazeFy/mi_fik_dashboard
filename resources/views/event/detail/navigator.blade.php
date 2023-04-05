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
    <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > Detail > {{$c->content_title}}</span>
    <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event"><i class="fa-solid fa-trash"></i> Delete</a>
    <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:130px" title="Switch to edit mode" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
</div>
