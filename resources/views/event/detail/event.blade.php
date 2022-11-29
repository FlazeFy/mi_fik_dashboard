<style>
    .box-event-detail{
        border-radius:20px;
        background:white;
        margin:20px 0 20px 0;
    }
    .event-detail-img-header{
        height:30vh;
    }
    .event-tag-box{
        border-radius:6px;
        color:whitesmoke !important;
        background: #F78A00;
    }
    .event-detail{
        color:#F78A00 !important;
        text-decoration:none;
    }
</style>

@foreach($event as $e)
    <div class="box-event-detail">
        <div class="event-detail-img-header">

        </div>
        <div class="row p-3">
            <div class="col-lg-8">
                <h5>{{$e->content_title}}</h5>
                <span><?php echo $e->content_desc; ?></span>
            </div>
            <div class="col-lg-4">
                <!--Get event tag-->
                @if($e->content_tag != null)
                    @php($tag = json_decode($e->content_tag))
                    @foreach($tag as $tg)
                        <a class="btn event-tag-box">{{$tg->tag_name}}</a>
                    @endforeach
                @endif

                <h6 class="mt-2">Event Location</h6>
                <div id="map"></div>

                <h6 class="mt-2">Attachment</h6>

                <h6 class="mt-2">Date & Time</h6>
                <!--Get event date start-->
                @if($e->content_date_start != null && $e->content_date_end != null)
                    @if(date('y-m-d', strtotime($e->content_date_start)) == date('y-m-d', strtotime($e->content_date_end)))
                        <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($e->content_date_start))}} - {{date('h:i A', strtotime($e->content_date_end))}}</a>
                    @else
                        <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($e->content_date_start))}} - {{date('y/m/d h:i A', strtotime($e->content_date_end))}}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endforeach