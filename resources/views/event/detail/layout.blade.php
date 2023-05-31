<style>
    .box-event-detail{
        margin:20px 0 20px 0;
        min-height: 80vh;
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
    .content-detail-views{
        position: absolute;
        left: 10px;
        bottom: 10px;
        color: #F5F5F5;
    }
    .event-header-size-toogle{
        color: #F78A00 !important;
        background: none;
        border: none;
        position: absolute;
        top: 15px;
        left: 15px;
        cursor: pointer;
    }
    .event-tag-box{
        border-radius:6px;
        color:#F5F5F5 !important;
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

    .btn.archive-box{
        position: relative;
    }
    .btn.archive-box .icon-holder{
        display: none;
        color: #F5F5F5;
        position: absolute;
        top: 20px;
        left: -32.5px; 
    }
    .btn.archive-box:hover{
        border-left: 50px solid #00C363;
        transition: all .30s linear;
    }
    .btn.archive-box:hover .icon-holder{
        display: inline;
    }
    .btn.archive-box.active{
        color: #F5F5F5;
        background: #F78A00;
        border: none !important;
    }
    .btn.archive-box.active h6{
        color: #F5F5F5 !important;
    }
    .btn.archive-box.active:hover{
        border-left: 50px solid #E74645 !important;
        transition: all .30s linear;
    }
    .btn-add-archive{
        margin-top: 10px;
        font-size: 13px;
        color: #212121;
    }
    .btn-add-archive:hover{
        color: #F78A00;
    }
  
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
</style>

    <div class="box-event-detail">
        @if($c->content_image)
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('{{$c->content_image}}');" id="event-header-image">
                <button class="event-header-size-toogle" title="Resize image" onclick="resize('<?= $c->content_image; ?>')"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
                <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
            </div>
        @else
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/default_content.jpg')}});" id="event-header-image">
                <button class="event-header-size-toogle" title="Resize image" onclick="resize(null)"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
                <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
            </div>
        @endif
        <div class="row p-3">
            <div class="col-lg-8">
                <!-- <button class="btn btn-primary px-3 float-end" type="button" id="section-select-archive" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <i class="fa-solid fa-list-check"></i></button> -->
                    <h5>{{$c->content_title}}</h5>

                <!--My Archive-->
                <!-- include('event.detail.archive') -->

                <span><?php echo $c->content_desc; ?></span><br>

                <!--Content attachment-->
                <hr><h5>Attachment</h5>
                @include('event.detail.attachment')

                <!--Content location-->
                <hr><h5>Location</h5>
                @if($c->content_loc)
                    @include('event.detail.maps')
                @else 
                    <img src="{{asset('assets/noloc.png')}}" class="img nodata-icon" style="height:18vh;">
                    <h6 class="text-center text-secondary">This Event doesn't have location</h6>
                @endif
            </div>
            <div class="col-lg-4">
                @include('event.detail.properties')
            </div>
        </div>
    </div>

<script>
    var i = 0;
    var j = 0;

    function copylink(id) {
        var copyText = document.getElementById("copy_url_"+id);

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value);
    }

    function resize(img){
        if(img){
            var img_url = "background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('" + img + "');";
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