<div class="container mt-3 p-0">
    <div class="event-holder row mt-3" >        
        <div class="row p-0 m-0" id="data-wrapper"></div>
        <div id="empty_item_holder"></div>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
    </div>
</div>

<script type="text/javascript">
    var page = 1;
    var last = 0;
    var next_event;
    var myname = "<?= session()->get("username_key") ?>";
    infinteLoadMore(page);

    var listEvent = document.getElementById("content");
    listEvent.addEventListener('scroll', function() {
        var scrollPosition = listEvent.scrollTop + listEvent.clientHeight;
        if (scrollPosition >= listEvent.scrollHeight && page < last) {
            page++;
            var elmt = " " +
            "<div class='d-block mx-auto' id='load-page-event-"+page+"'> " +
                '<div class="row"> ' +
                    '<div class="col-lg-4 col-md-6 col-sm-12 pb-3"><div class="skeleton-box event"></div></div> ' +
                    '<div class="col-lg-4 col-md-6 col-sm-12 pb-3"><div class="skeleton-box event"></div></div> ' +
                    '<div class="col-lg-4 col-md-6 col-sm-12 pb-3"><div class="skeleton-box event"></div></div> ' +
                '</div> ' +
            "</div>";

            $("#data-wrapper").append(elmt);
            infinteLoadMore(page);
        } else if (page >= last) {
            var elmt = " " +
            "<h6 id='load-page-event-"+page+"' class='text-center mt-3'> No more content to show</h6>";

            $("#empty_item_holder").html(elmt);
        }
    });

    function infinteLoadMore(page) {
        var tag = <?php
            $tags = session()->get('selected_tag_calendar');
            
            if($tags != "All"){
                echo "'";
                $count_tag = count($tags);
                $i = 1;

                foreach($tags as $tg){
                    if($i != $count_tag){
                        echo $tg.",";
                    } else {
                        echo $tg;
                    }
                    $i++;
                }
                echo "'";
            } else {
                echo "'all'";
            }
        ?>;

        var order = <?php echo '"'.session()->get('ordering_event').'";'; ?>
        var date = <?php echo '"'.session()->get('filtering_date').'";'; ?>

        function getFind(check){
            if(check == null || check.trim() === ''){
                return " "
            } else {
                return check
            }
        }
        
        $.ajax({
            url: "/api/v2/content/slug/" + tag + "/order/" + order + "/date/" + date + "/"+getUTCHourOffset()+"/find/" + getFind(search_storage) + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            $("#load-page-event-"+page).remove();
            var data =  response.data.data;
            var total = response.data.total;
            last = response.data.last_page;

            // if(page != last){
            //     $('#load_more_holder').html('<button class="btn content-more-floating my-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            // } else {
            //     $('#load_more_holder').html('<h6 class="btn content-more-floating my-3 p-2">No more item to show</h6>');
            // }

            if (total == 0) {
                $('#empty_item_holder').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getContentView(total_views, uname){
                    if(uname == "You" || <?= session()->get("role_key") ?> == 1){
                        return "<div class='event-views'><i class='fa-solid fa-eye'></i> " + total_views + "</div> ";
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
                            "<button class='card shadow event-box' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'> " +
                                '<div class="card-header header-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), ' + getContentImage(content_image) + ';"></div> ' +
                                "<div class='d-flex justify-content-between position-absolute px-3 w-100' style='top:10px;'> " +
                                    getContentView(total_views, usernameText) +
                                    getEventStatus(content_date_start, content_date_end) +
                                "</div> " +
                                "<div class='card-body event-body p-2 w-100'> " +
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

                    $("#data-wrapper").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load-tag').hide();
            failResponse(jqXHR, ajaxOptions, thrownError, "#data-wrapper", false, null, null);
        });
    }
</script>