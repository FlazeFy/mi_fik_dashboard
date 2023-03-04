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
        transition: all .25s linear;
    }
    .event-header-size-toogle{
        color: #F78A00 !important;
        background: none;
        border: none;
        margin-top: 10px;
        margin-left: 10px;
    }
    .event-tag-box{
        border-radius:6px;
        color:whitesmoke !important;
        background: #F78A00;
    }
    .event-detail{
        color: #F78A00 !important;
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
    .archive-holder{
        display: flex;
        flex-direction: column;
        height: 300px;
        padding-inline: 10px;
        overflow-y: scroll;
        overflow-x: hidden;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .archive-box{
        padding: 10px;
        margin-top: 14px;
        width: 200px !important;
    }
    .archive-count{
        font-size: 12px;
        font-weight: 400;
    }
    .dropdown-menu{
        border: none;
        margin: 10px 0 0 0 !important; 
        border-radius: 15px !important;
        padding-bottom: 0px;
    }
    .dropdown-menu-end .dropdown-item.active, .dropdown-menu-end .dropdown-item:active, .dropdown-menu-end .dropdown-item:hover{
        background: none !important;
    }
    .btn.archive-box:hover{
        border-left: 6px solid #F78A00;
        transition: all .15s linear;
    }
    .btn.archive-box.active{
        color: whitesmoke;
        background: #F78A00;
        border: none !important;
    }
    .btn.archive-box.active h6{
        color: whitesmoke !important;
    }
    
</style>

@foreach($content as $c)
    <div class="box-event-detail">
        @if($c->content_image)
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('http://127.0.0.1:8000/storage/{{$c->content_image}}');" id="event-header-image">
                <button class="event-header-size-toogle" title="Resize image" onclick="resize('<?php echo $c->content_image; ?>')"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
            </div>
        @else
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/default_content.jpg')}});" id="event-header-image">
                <button class="event-header-size-toogle" title="Resize image" onclick="resize(null)"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
            </div>
        @endif
        <div class="row p-3">
            <div class="col-lg-8">
                <button class="btn btn-primary px-3 float-end" type="button" id="section-select-archive" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="fa-solid fa-list-check"></i></button>
                    <h5>{{$c->content_title}}</h5>

                <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="section-select-archive">
                    <span class="dropdown-item py-2 px-0">
                        <label class="fw-bold ms-2">My Archive</label><br>
                        <div class="archive-holder">
                            @php($i = 0)
                            @foreach($archive as $ar)
                                @php($found = false)
                                @foreach($archive_relation as $arl)
                                    @if($arl->archive_id == $ar->id)
                                        @php($found = true)
                                        @php($id = $arl->id)
                                    @endif
                                @endforeach

                                @if($found)
                                    <form class="d-inline" action="/event/detail/delete_relation/{{$id}}" method="POST">
                                        @csrf
                                        <button class="btn archive-box active shadow text-start" type="submit" title="Add event to {{$ar->archive_name}}">
                                            <h6 class="text-secondary" id="archive-title-{{$i}}">{{$ar->archive_name}}</h6>
                                            <h6 class="archive-count"><span>Event : </span>&nbsp<span>Task : </span></h6>
                                        </button>
                                    </form>
                                @else 
                                    <form class="d-inline" action="/event/detail/add_relation/{{$c->slug_name}}" method="POST">
                                        @csrf
                                        <input hidden value="{{$ar->id}}" name="archive_id">
                                        <button class="btn archive-box shadow text-start" type="submit" title="Add event to {{$ar->archive_name}}">
                                            <h6 class="text-secondary" id="archive-title-{{$i}}">{{$ar->archive_name}}</h6>
                                            <h6 class="archive-count"><span>Event : </span>&nbsp<span>Task : </span></h6>
                                        </button>
                                    </form>
                                @endif
                                @php($i++)
                            @endforeach
                        </div>
                    </span>
                </div>
                <span><?php echo $c->content_desc; ?></span>

                <!--Content attachment-->
                @if($c->content_attach)
                    @php($att = $c->content_attach)
                    @foreach($att as $at)
                        <!-- Show attachment title or name  -->
                        @if($at['attach_name'] || $at['attach_name'] == "")
                            <h6>{{$at['attach_name']}} : </h6>
                        @endif

                        <!-- Show file -->
                        @if($at['attach_type'] == "attachment_url")
                            <input id="copy_url_{{$at['id']}}" value="{{$at['attach_url']}}" hidden>
                            <a class="btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at['id']; ?>)"><i class="fa-solid fa-copy"></i> </a><a class="text-link" title="Open this link" href="{{$at['attach_url']}}" target="_blank">{{$at['attach_url']}}</a>
                        @elseif($at['attach_type'] == "attachment_image")
                            <img class="img img-fluid mx-auto rounded mb-2" src="{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
                        @elseif($at['attach_type'] == "attachment_video")
                            <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
                                <source src="{{$at['attach_url']}}">
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

                <hr>
                <h6 class="text-secondary" title="Event Created At">Created At : {{date('d M Y h:i:s', strtotime($c->created_at))}}</h6>
                @if($c->updated_at)
                    <h6 class="text-secondary" title="Event Updated At">Created At : {{date('d M Y h:i:s', strtotime($c->updated_at))}}</h6>
                @endif
            </div>
        </div>
    </div>
@endforeach

<script>
    var i = 0;

    function copylink(id) {
        var copyText = document.getElementById("copy_url_"+id);

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value);
    }

    function resize(img){
        if(img){
            var img_url = "background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('http://127.0.0.1:8000/storage/" + img + "');";
        } else {
            var img_url = "background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('http://127.0.0.1:8000/assets/default_content.jpg');";
        }

        if(i % 2 == 0){
            document.getElementById('event-header-image').style = "height: 100vh; " + img_url;
        } else {
            document.getElementById('event-header-image').style = "height: 30vh; " + img_url;
        }
        i++;
    }
</script>