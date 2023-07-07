<style>
    .box-event-detail{
        margin: 20px 0 20px 0;
        min-height: 80vh;
    }
    .event-detail-img-header{
        height: 30vh;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        background-size: cover;
        background-color: var(--greyColor);
        width: 100%;
        border-radius: var(--roundedLG) var(--roundedLG) 0 0;
        transition: all .25s linear;
    }
    .content-detail-views{
        position: absolute;
        left: 10px;
        bottom: 10px;
        color: var(--whiteColor);
    }
    .event-header-size-toogle{
        color: var(--primaryColor) !important;
        background: none;
        border: none;
        position: absolute;
        top: 15px;
        left: 15px;
        cursor: pointer;
    }
    .event-tag-box{
        border-radius: var(--roundedMini);
        color:var(--whiteColor) !important;
        background: var(--primaryColor);
    }
    .event-detail{
        color: var(--primaryColor) !important;
        text-decoration:none;
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
        border-radius: var(--roundedMD) !important;
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
        color: var(--whiteColor);
        position: absolute;
        top: 20px;
        left: -32.5px; 
    }
    .btn.archive-box:hover{
        border-left: 50px solid var(--successBG);
        transition: all .30s linear;
    }
    .btn.archive-box:hover .icon-holder{
        display: inline;
    }
    .btn.archive-box.active{
        color: var(--whiteColor);
        background: var(--primaryColor);
        border: none !important;
    }
    .btn.archive-box.active h6{
        color: var(--whiteColor) !important;
    }
    .btn.archive-box.active:hover{
        border-left: 50px solid var(--warningBG) !important;
        transition: all .30s linear;
    }
    .btn-add-archive{
        margin-top: 10px;
        font-size: 13px;
        color: var(--darkColor);
    }
    .btn-add-archive:hover{
        color: var(--primaryColor);
    }
  
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: var(--hoverBG); 
    }
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
    .image-att-zoomable{
        cursor: pointer;
        border-radius: var(--roundedMD);
    }
    .image-att-zoomable:hover{
        background: var(--darkColor);
        opacity: 0.7;
        -webkit-transition: all 0.2s;
        -o-transition: all 0.2s;
        transition: all 0.2s;
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
                <!-- PHP Helpers -->
                <?php
                    use App\Helpers\Generator;
                ?>  
                @php($image_profile = Generator::getUserImage($c->admin_image_created, $c->user_image_created, $c->admin_username_created))
                <div class="p-0 m-0" style="display: flex;">
                    <div class="d-inline-block me-2">
                        <img class="img rounded-circle" style="width:55px; height:55px; border:2px solid var(--primaryColor);" src="{{$image_profile}}" alt="username-profile-pic.png">
                    </div>
                    <div class="d-inline-block" style="width:auto;">
                        <h4 class="text-primary">{{ucwords($c->content_title)}}</h4><br>
                    </div>
                </div>

                @if($c->content_desc)
                    <span id="desc-holder"><?php echo $c->content_desc; ?></span><br>
                @else
                    <img src="{{asset('assets/nodesc.png')}}" class="img nodata-icon" style="height:18vh;">
                    <h6 class="text-center text-secondary">This Event doesn't have description</h6>
                @endif

                <hr><h5>Attachment</h5>
                @include('event.detail.attachment')

                <hr><h5>Location</h5>
                @include('event.detail.maps')
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