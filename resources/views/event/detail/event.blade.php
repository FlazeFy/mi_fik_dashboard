<style>
    .box-event-detail{
        margin:20px 0 20px 0;
    }
    .event-detail-img-header{
        height:30vh;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: black;
        width: 100%;
        border-radius: 18px 18px 0 0;
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
        @if($c->content_image)
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('http://127.0.0.1:8000/storage/{{$c->content_image}}');"></div>
        @else
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/default_content.jpg')}});"></div>
        @endif
        <div class="row p-3">
            <div class="col-lg-8">
                <h5>{{$c->content_title}}</h5>
                <span><?php echo $c->content_desc; ?></span>

                <!--Content attachment-->
                @if($c->content_attach != null)
                    @php($att = $c->content_attach)
                    @foreach($att as $at)
                        <!-- Show attachment title or name  -->
                        @if($at['attach_name'] && $at['attach_name'] == "")
                            <h6>['$at->attach_name'] : </h6>
                        @endif

                        <!-- Show file -->
                        @if($at['attach_type'] == "attachment_url")
                            <input id="copy_url_{{$at['id']}}" value="{{$at['attach_url']}}" hidden>
                            <a class="btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at['id']; ?>)"><i class="fa-solid fa-copy"></i> </a><a class="text-link" title="Open this link" href="{{$at['attach_url']}}" target="_blank">{{$at['attach_url']}}</a>
                        @elseif($at['attach_type'] == "attachment_image")
                            <img class="img img-fluid mx-auto rounded mb-2" src="http://127.0.0.1:8000/storage/{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
                        @elseif($at['attach_type'] == "attachment_video")
                            <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
                                <source src="http://127.0.0.1:8000/storage/{{$at['attach_url']}}">
                            </video>
                        @elseif($at['attach_type'] == "attachment_doc")
                            <!-- ??? -->
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="col-lg-4">
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