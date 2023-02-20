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
    .text-link{
        cursor: pointer;
    }
    .btn-copy-link{
        color: #808080;
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-copy-link:hover{
        color: #F78A00;
    }
</style>

@foreach($content as $c)
    <div class="box-event-detail">
        <div class="event-detail-img-header">

        </div>
        <div class="row p-3">
            <div class="col-lg-8">
                <h5>{{$c->content_title}}</h5>
                <span><?php echo $c->content_desc; ?></span>

                <!--Content attachment-->
                @if($c->content_attach != null)
                    @php($att = json_decode($c->content_attach))
                    @foreach($att as $at)
                        @if($at->attach_type == "attachment_url")
                            @if($at->attach_name)
                                <h6>{{$at->attach_name}} : </h6>
                            @endif
                            <input id="copy_url_{{$at->id}}" value="{{$at->attach_url}}" hidden>
                            <a class="btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at->id; ?>)"><i class="fa-solid fa-copy"></i> </a><a class="text-link" title="Open this link" href="{{$at->attach_url}}" target="_blank">{{$at->attach_url}}</a>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="col-lg-4">
                <!--Get event tag-->
                @if($c->content_tag != null)
                    @php($tag = json_decode($c->content_tag))
                    @foreach($tag as $tg)
                        <a class="btn event-tag-box">{{$tg->tag_name}}</a>
                    @endforeach
                @endif

                <h6 class="mt-2">Event Location</h6>
                <div id="map"></div>

                <h6 class="mt-2">Attachment</h6>

                <h6 class="mt-2">Date & Time</h6>
                
                <!--Get event date start-->
                @if($c->content_date_start != null && $c->content_date_end != null)
                    @if(date('y-m-d', strtotime($c->content_date_start)) == date('y-m-d', strtotime($c->content_date_end)))
                        <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($c->content_date_start))}} - {{date('h:i A', strtotime($c->content_date_end))}}</a>
                    @else
                        <a class="event-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('y/m/d h:i A', strtotime($c->content_date_start))}} - {{date('y/m/d h:i A', strtotime($c->content_date_end))}}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endforeach

<script>
    function copylink(id) {
        var copyText = document.getElementById("copy_url_"+id);

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value);
    }
</script>