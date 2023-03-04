<div class="p-0 m-0">
    <!--Get event tag-->
    @if($c->content_tag)
        @php($tag = $c->content_tag)
        @foreach($tag as $tg)
            <a class="btn event-tag-box">{{$tg['tag_name']}}</a>
        @endforeach
    @endif

    <h6 class="mt-2">Event Location</h6>
    <!--Get event location-->
    @if($c->content_loc)
        <div id="map"></div>
    @else
        <img src="http://127.0.0.1:8000/assets/noloc.png" class="img nodata-icon" style="height:18vh;">
        <h6 class="text-center text-secondary">This Event doesn't have location</h6>
    @endif

    <h6 class="mt-2">Date & Time</h6>

    <!--Get event date start-->
    @if($c->content_date_start && $c->content_date_end)
        @if(date('y-m-d', strtotime($c->content_date_start)) == date('y-m-d', strtotime($c->content_date_end)))
            <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($c->content_date_start))}} - {{date('h:i A', strtotime($c->content_date_end))}}</a>
        @else
            <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($c->content_date_start))}} - {{date('y/m/d h:i A', strtotime($c->content_date_end))}}</a>
        @endif
    @else
        <img src="http://127.0.0.1:8000/assets/nodate.png" class="img nodata-icon" style="height:18vh;">
        <h6 class="text-center text-secondary">This Event doesn't have date</h6>
    @endif

    <hr>
    <h6 class="text-secondary" title="Event Created At">Created At : {{date('d M Y h:i:s', strtotime($c->created_at))}}</h6>
    @if($c->updated_at)
        <h6 class="text-secondary" title="Event Updated At">Created At : {{date('d M Y h:i:s', strtotime($c->updated_at))}}</h6>
    @endif
</div>