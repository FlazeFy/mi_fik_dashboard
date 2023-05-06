<div class="container mt-3 p-0">
    <div class="event-holder row mt-3" >        
        <div class="row p-0 m-0" id="data-wrapper"></div>
        <!-- Loading -->
        <div class="auto-load text-center">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>
        <div id="empty_item_holder"></div>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
    </div>
</div>

<script type="text/javascript">
    var page = 1;
    var myname = "<?= session()->get("username_key") ?>";
    infinteLoadMore(page);

    //Fix the sidebar & content page FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page++;
    //         infinteLoadMore(page);
    //     } 
    // };

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

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
            url: "/api/v2/content/slug/" + tag + "/order/" + order + "/date/" + date + "/find/" + getFind(search_storage) + "?page=" + page,
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
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder').html('<button class="btn content-more-floating my-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder').html('<h6 class="btn content-more-floating my-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getEventLoc(loc){
                    if(loc){
                        return "<span class='loc-limiter px-0 m-0'> " +
                                "<a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> "+loc[0].detail+"</a> " +
                            "</span>";
                    } else {
                        return "";
                    }
                }

                function getDateMonth(date){
                    const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                    return ("0" + date.getDate()).slice(-2) + " " + month[date.getMonth()].slice(0, 3);
                }

                function getHourMinute(date){
                    return ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);
                }

                function getEventTag(tag){
                    if(tag){
                        var str = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i != tag.length - 1){
                                str += tag[i].tag_name +", ";
                            } else {
                                str += tag[i].tag_name;
                            }
                        }

                        return '<a class="btn-detail" title="'+ str +'"><i class="fa-solid fa-hashtag"></i>'+ tag.length +'</a>';
                    } else {
                        return "";
                    }
                }

                function getEventDate(dateStart, dateEnd){
                    if(dateStart && dateEnd){
                        const ds = new Date(dateStart);
                        const de = new Date(dateEnd);

                        if(ds.getFullYear() !== de.getFullYear()){
                            //Event year not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + ds.getFullYear() + " " + getHourMinute(ds) + 
                                " - " +
                                getDateMonth(de) + " " + de.getFullYear() + " " + getHourMinute(de) + "</a>";

                        } else if(ds.getMonth() !== de.getMonth()){
                            //If month not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + ds.getFullYear() + " " + getHourMinute(ds) + 
                                " - " +
                                getDateMonth(de) + " " + getHourMinute(de) + "</a>";

                        } else if(ds.getDate() !== de.getDate()){
                            //If date not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + getHourMinute(ds) + 
                                " - " +
                                getDateMonth(de) + " " + ("0" + de.getDate()).slice(-2) + " " + getHourMinute(de) + "</a>";

                        } else if(ds.getDate() === de.getDate()){
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + getHourMinute(ds) + 
                                " - " +
                                getHourMinute(de) + "</a>";

                        }
                    } else {
                        return "";
                    }
                }

                //For now.
                function removeTags(str) {
                    if ((str===null) || (str==='')){
                        return "<span class='fst-italic'>No description provided</span>";
                    } else {
                        str = str.toString();
                    }
                        
                    return str.replace( /(<([^>]+)>)/ig, '');
                }

                function getContentImage(img){
                    if(img){
                        return 'url("'+img+'")';
                    } else {
                        return "url({{asset('assets/default_content.jpg')}})";
                    }
                }

                function getUserImage(img1, img2){
                    if(img1 || img2){
                        if(img1){
                            return img1;
                        } else {
                            return img2;
                        }
                    } else {
                        return "{{ asset('/assets/default_lecturer.png')}}";
                    }
                }

                function getUsername(username1, username2){
                    if(username1){
                        if(username1 == myname){
                            return "You";
                        } else {
                            return "@"+username1;
                        }
                    } else {
                        if(username2 == myname){
                            return "You";
                        } else {
                            return "@"+username2;
                        }
                    }
                }

                function getEventStatus(start, end){
                    const c_start = new Date(start);
                    const c_end = new Date(end);
                    const now = new Date(Date.now());

                    const msDiff_start = c_start.getTime() - now.getTime()
                    const msDiff_end = c_end.getTime() - now.getTime()

                    const hourDiff_start = Math.round(
                        msDiff_start / (24 * 60 * 60 * 60) //hr before start
                    )
                    const hourDiff_end = Math.round(
                        msDiff_end / (24 * 60 * 60 * 60) //30 minutes before end
                    )

                    if(c_start.toDateString() >= now.toDateString() && c_end.toDateString() >= now.toDateString() && hourDiff_start > 0 && hourDiff_start < 3){
                        return "<div class='event-status text-primary'><i class='fa-solid fa-circle fa-xs'></i> About to start</div>";
                    } else if(c_start.toDateString() <= now.toDateString() && c_end.toDateString() >= now.toDateString()){ //3 hr before start
                        if(hourDiff_end > 1){
                            return "<div class='event-status text-danger'><i class='fa-solid fa-circle fa-xs'></i> Live</div>";
                        } else {
                            return "<div class='event-status text-danger'><i class='fa-solid fa-circle fa-xs'></i> About to end</div>"; //1 hr before end
                        }
                    } else if(c_end.toDateString() > now.toDateString() && hourDiff_start > 0 && hourDiff_start <= 6){
                        return "<div class='event-status text-success'><i class='fa-solid fa-circle fa-xs'></i> Just Ended</div>"; //If api show finished event with datediff end is one
                    } else {
                        return ""
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
                    var created_at = data[i].created_at;

                    var elmt = " " +
                        "<div class='col-lg-4 col-md-6 col-sm-12 pb-3'> " +
                            "<button class='card shadow event-box' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'> " +
                                "<div class='card-header header-image' style='background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), " + getContentImage(content_image) + ";'></div> " +
                                "<div class='event-created-at'>" + getDateToContext(created_at) + "</div> " +
                                "<div class='event-views'><i class='fa-solid fa-eye'></i> " + total_views + "</div> " +
                                getEventStatus(content_date_start, content_date_end) +
                                "<div class='card-body p-2 w-100'> " +
                                    "<div class='row px-2'> " +
                                        "<div class='col-lg-2 px-1'> " +
                                            "<img class='img img-fluid user-image-content' src='" + getUserImage(admin_image, user_image) + "' alt='username-profile-pic.png'> " +
                                        "</div> " +
                                        "<div class='col-lg-9 p-0 py-1'> " +
                                            "<h6 class='event-title'>" + content_title + "</h6> " +
                                            "<h6 class='event-subtitle'>" + getUsername(admin_username, user_username) + "</h6> " +
                                        "</div> " +
                                    "</div> " +
                                    "<div style='height:45px;'> " +
                                        "<p class='event-desc my-1'>" + removeTags(content_desc) + "</p> " +
                                    "</div> " +
                                    "<div class='row d-inline-block px-2'> " +
                                        getEventLoc(content_loc) +
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
            console.log('Server error occured');
        });
    }
</script>