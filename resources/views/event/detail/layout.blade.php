<style>
    .box-event-detail{
        margin: var(--spaceLG) 0 var(--spaceLG) 0;
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
    .dropdown-menu{
        border: none;
        margin: 10px 0 0 0 !important; 
        border-radius: var(--roundedMD) !important;
        padding-bottom: 0px;
    }
    .dropdown-menu-end .dropdown-item.active, .dropdown-menu-end .dropdown-item:active, .dropdown-menu-end .dropdown-item:hover{
        background: none !important;
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
                <div class="d-flex justify-content-between py-3 px-2">
                    <div>
                        <button class="event-header-size-toogle" title="Resize image" onclick="resize('<?= $c->content_image; ?>')"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
                    </div>
                    @if($isMobile)
                        <div>
                            <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event" data-bs-toggle="modal" data-bs-target="#deleteEvent-{{$c->slug_name}}"><i class="fa-solid fa-trash"></i></a>
                            <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:130px" title="Switch to edit mode" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-pen-to-square"></i></a>
                        </div>
                    @endif
                </div>

                @if(session()->get('role_key') == 1 || $c->user_username_created == session()->get('username_key'))
                    <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
                @endif
            </div>
        @else
            <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/default_content.jpg')}});" id="event-header-image">
                <div class="d-flex justify-content-between py-3 px-2">
                    <div>
                        <button class="event-header-size-toogle" title="Resize image" onclick="resize(null)"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></button>
                    </div>    
                    @if($isMobile)
                        <div>
                            <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event" data-bs-toggle="modal" data-bs-target="#deleteEvent-{{$c->slug_name}}"><i class="fa-solid fa-trash"></i> Delete</a>
                            <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:130px" title="Switch to edit mode" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                        </div>
                    @endif
                </div>

                @if(session()->get('role_key') == 1 || $c->user_username_created == session()->get('username_key'))
                    <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
                @endif
            </div>
        @endif
        <div class="row p-3">
            <div class="col-lg-8">
                <!-- PHP Helpers -->
                <?php
                    use App\Helpers\Generator;
                ?>  
                @php($image_profile = Generator::getUserImage($c->admin_image_created, $c->user_image_created, $c->admin_username_created))
                <div class="p-0 m-0 mb-3" style="display: flex;">
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