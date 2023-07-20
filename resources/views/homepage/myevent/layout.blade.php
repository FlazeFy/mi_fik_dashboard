<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/myevent.png'); ?>"); background-color:#FB5E5B;'
    data-bs-target="#myevent" data-bs-toggle="modal">
    <span id="total_my_event"></span>
    <h5 class="quick-action-text">My Event</h5>
    <p class="quick-action-info">This will show all event that made by you</p>
</button>

<div class="modal fade" id="myevent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>My Event</h5>
                @include('homepage.myevent.searchbar')
                <hr>
                <div class="event-holder row mt-3"  style="display: flex; flex-direction: column; max-height: 75vh; overflow-y: scroll;">        
                    <div class="row p-0 m-0" id="data-wrapper-my-event"></div>
                    <!-- Loading -->
                    <div class="auto-load-my-event text-center">
                        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
                    </div>
                    <div id="empty_myevent_holder"></div>
                </div>
                <span id="load_more_myevent_holder"></span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var page = 1;
    infinteLoadMyEvent(page);

    function loadMyEvent(route){
        page++;
        infinteLoadMyEvent(page);
    }

    function infinteLoadMyEvent(page) {
        var find = document.getElementById("myevent_search").value;
        document.getElementById("data-wrapper-my-event").innerHTML = "";

        function getFind(check){
            let trim = check.trim();
            if(check == null || trim === ''){
                return "%20"
            } else {
                document.getElementById("myevent_search").value = trim;
                return trim
            }
        }
        
        $.ajax({
            url: "/api/v1/content/my/order/desc/find/" + getFind(find) + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-my-event').show();
            }
        })
        .done(function (response) {
            $('.auto-load-my-event').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_myevent_holder').html('<button class="btn content-more-floating my-3 p-2 d-block mx-auto" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_myevent_holder').html('<h6 class="btn content-more-floating my-3 p-2 d-block mx-auto">No more item to show</h6>');
            }

            $("#total_my_event").html('<a class="total-my-event" title="You have some draft event"><i class="fa-regular fa-calendar"></i> ' + total + ' </a>');

            if (total == 0) {
                $('#empty_myevent_holder').html("<img src="+'"'+"{{asset('assets/nodata2.png')}}"+'"'+" class='img nodata-icon'><h6 class='text-secondary text-center'>My event is empty</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-my-event').html("<h5 class='text-secondary'>Woah!, You have see all the event :)</h5>");
                return;
            } else {
                function getContentView(total_views, uname){
                    if(uname == "You" || <?= session()->get("role_key") ?> == 1){
                        return "<div class='event-views' style='color:var(--darkColor)!important; right:10px;'><i class='fa-solid fa-eye'></i> " + total_views + "</div> ";
                    } else {
                        return "<div></div>";
                    }
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var content_title = data[i].content_title;
                    var content_desc = data[i].content_desc;
                    var content_loc = data[i].content_loc;
                    var content_tag = data[i].content_tag;
                    var content_image = data[i].content_image;
                    var admin_image = data[i].admin_image_created;
                    var user_image = data[i].user_image_created;
                    var admin_username = data[i].admin_username_created;
                    var user_username = data[i].user_username_created;
                    var content_image = data[i].content_image;
                    var content_date_start = data[i].content_date_start;
                    var content_date_end = data[i].content_date_end;
                    var total_views = data[i].total_views;

                    var usernameText = getUsername(admin_username, user_username);

                    var elmt = " " +
                        "<div class='col-lg-4 col-md-6 col-sm-12 pb-3'> " +
                            "<button class='card shadow event-box p-2' style='@if(!$isMobile) height:auto; @else height:180px; @endif' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'> " +
                                "<div class='d-flex justify-content-between w-100'> " +
                                    getContentView(total_views, usernameText) +
                                    getEventStatus(content_date_start, content_date_end) +
                                "</div> " +
                                "<div class='card-body event-body py-2 px-0 w-100'> " +
                                    "<div class='event-heading'> " +
                                        "<div class='d-inline-block position-relative'> " +
                                            "<img class='img user-image-content' src='" + getUserImage(admin_image, user_image, admin_username, user_username) + "' alt='username-profile-pic.png'> " +
                                        "</div> " +
                                        "<div class='d-inline-block position-relative w-75'> " +
                                            "<h6 class='event-title'>" + ucEachWord(content_title) + "</h6> " +
                                            "<h6 class='event-subtitle'>" + usernameText + "</h6> " +
                                        "</div> " +
                                    "</div> " +
                                    "<div style='height:60px;'> " +
                                        "<p class='event-desc my-1'>" + ucFirst(removeTags(content_desc)) + "</p> " +
                                    "</div> " +
                                    "<div class='event-properties row d-inline-block px-2'> " +
                                        getLocationName(content_loc) +
                                        getEventDate(content_date_start, content_date_end) +
                                        getEventTag(content_tag) +
                                    "</div> " +
                                "</div> " +
                            "</button> " +
                        "</div>";

                    $("#data-wrapper-my-event").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load-my-event').hide();
                var find = document.getElementById("myevent_search").value;
                var msg = "";
                if(find.trim() != ""){
                    msg = "Sorry but we not found specific event";
                } else {
                    msg = "You haven't created any event yet";
                }
                $("#empty_myevent_holder").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>" + msg + "</h6></div>");
            } else {
                // handle other errors
            }
        });
    }
</script>