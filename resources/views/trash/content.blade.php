<div class="container mt-5 p-0">
    <div class="event-holder row mt-4" >        
        <div class="accordion row p-0 m-0 content-container" id="data-wrapper"></div>
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
        var order = <?php echo '"'.session()->get('ordering_trash').'";'; ?>
        var cat = <?php echo '"'.session()->get('filtering_trash').'";'; ?>
        <?php 
            foreach($info as $in){
                echo "var info_type = '".$in->info_type."';
                var info_body = '".$in->info_body."';";
            }
        ?>

        function getFind(check){
            if(check == null || check.trim() === ''){
                return " "
            } else {
                return check
            }
        }
        
        $.ajax({
            url: "/api/v1/trash/order/" + order + "/cat/" + cat + "/find/" + getFind(search_storage) + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function () {
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
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata2.png' class='img nodata-icon'><h6 class='text-secondary text-center'>Trash can is empty</h6>");
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

                function getDateTime(datetime){
                    const result = new Date(datetime);
                    const now = new Date(Date.now());
                    const yesterday = new Date();
                    yesterday.setDate(yesterday.getDate() - 1);
                    
                    //FIx this!!!
                    if(result.toDateString() === now.toDateString()){
                        // $start_date = new DateTime(datetime);
                        // $since_start = $start_date->diff(new DateTime(Date.now()));

                        // if(result.getHours() == now.getHours()){
                        //     const min = result.getMinutes() - now.getMinutes();
                        //     if(min <= 10 && min > 0){
                        //         return $since_start->m;
                        //     } else {
                        //         return  min + " minutes ago";    
                        //     }
                        // } else if(now.getHours() - result.getHours() <= 6){
                        //     return now.getHours() - result.getHours() + " hours ago";    
                        // } else {
                            return "Today at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                        //}
                    } else if(result.toDateString() === yesterday.toDateString()){
                        return "Yesterday at" + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                    } else {
                        return " " + result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
                    }
                }

                function getUsername(admin_username, user_username){
                    if(admin_username){
                        return admin_username
                    } else {
                        return user_username
                    }
                }

                function getDaysRemaining(date){
                    date = new Date(date)
                    const currDate = new Date()
                    const deadDate = new Date(currDate.setDate(currDate.getDate() + 30))
                    const start = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate())
                    const end = Date.UTC(deadDate.getFullYear(), deadDate.getMonth(), deadDate.getDate())
                    
                    const timeDiff = end - start
                    var daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24))

                    if(daysDiff == '31'){
                        daysDiff = '30'
                    }
                    
                    return "<a class='text-danger fst-italic fw-bold' title='Days before auto deleted from system' style='font-size:12px;'>" + daysDiff + " days remaining</a>";
                }

                function getRecoverModal(type, slug_name, data_from, info_type, info_body, content_title){
                    return "<div class='modal fade' id='recover" + type + "-" + slug_name + "' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'> " +
                        "<div class='modal-dialog'> " +
                            "<div class='modal-content'> " +
                                "<div class='modal-body text-center pt-4'> " +
                                    "<button type='button' class='custom-close-modal' data-bs-dismiss='modal' aria-label='Close' title='Close pop up'><i class='fa-solid fa-xmark'></i></button> " +
                                    "<form class='d-inline' action='/trash/recover/" + slug_name + "/" + data_from + "' method='POST'> " +
                                        '@csrf ' +
                                        "<p style='font-weight:500;'>Are you sure want to recover '<span class='text-primary'>" + content_title + "</span>' task?</p> " +                                                
                                            "<div class='info-box " + info_type + "'> " +
                                                "<label><i class='fa-solid fa-circle-info'></i> " + info_type + "</label><br> " +
                                                info_body + 
                                            "</div> " +
                                        "<button class='btn btn-submit' type='submit'>Recover</button> " +
                                    "</form> " +
                                "</div> " +
                            "</div> " +
                        "</div> " +
                    "</div>";
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var content_title = data[i].content_title;
                    var content_desc = data[i].content_desc;
                    var content_loc = data[i].content_loc;
                    var content_tag = data[i].content_tag;
                    var content_image = null; //For now.
                    var content_date_start = data[i].content_date_start;
                    var content_date_end = data[i].content_date_end;
                    var data_from = data[i].data_from;
                    var created_at = data[i].created_at;
                    var deleted_at = data[i].deleted_at;
                    var au_created = data[i].admin_username_created;
                    var uu_created = data[i].user_username_created;
                    var au_updated = data[i].admin_username_updated;
                    var uu_updated = data[i].user_username_updated;
                    var au_deleted = data[i].admin_username_deleted;
                    var uu_deleted = data[i].user_username_deleted;

                    if(data_from == 1){ // Event
                        var elmt = " " +
                            "<div class='col-lg-4 col-md-6 col-sm-12 pb-3 content-item'> " +
                                "<button class='card shadow event-box ultimate' onclick=''> " +
                                    "<div class='card-header header-image' style='background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), " + getContentImage(content_image) + ";'></div> " +
                                    "<div class='event-created-at'>" + getDateTime(created_at) + "</div> " +
                                    "<div class='card-body p-2 w-100'> " +
                                        "<div class='row px-2'> " +
                                            "<div class='col-lg-2 px-1'> " +
                                                "<img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'> " +
                                            "</div> " +
                                            "<div class='col-lg-9 p-0 py-1'> " +
                                                "<h6 class='event-title'>" + content_title + "</h6> " +
                                                "<h6 class='event-subtitle'>[username]</h6> " +
                                            "</div> " +
                                        "</div> " +
                                        "<div style='height:45px;'> " +
                                            "<p class='event-desc my-1'>" + removeTags(content_desc) + "</p> " +
                                        "</div> " +
                                        "<div class='row d-inline-block px-2'> " +
                                            getEventLoc(content_loc) +
                                            getEventDate(content_date_start, content_date_end) +
                                            getEventTag(content_tag) +
                                            getDaysRemaining(deleted_at) +
                                        "</div> " +
                                        "<hr style='margin-bottom:10px; margin-top:10px;'> " +
                                        "<div class='position-relative'> " +
                                            "<a class='btn btn-info px-3 me-1' title='See deleted info' data-bs-toggle='collapse' href='#collapseInfo_event_"+ slug_name +"' role='button' aria-expanded='false' aria-controls='collapseInfo'>" +
                                                "<i class='fa-solid fa-info'></i> " +
                                            "</a> " +
                                            "<a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recoverEvent-" + slug_name + "'> " +
                                                "<i class='fa-solid fa-arrow-rotate-right'></i> " +
                                            "</a> " +
                                            "<a class='btn btn-danger' role='button' title='Permanently delete'> " +
                                                "<i class='fa-solid fa-fire-flame-curved'></i> " +
                                            "</a> " +
                                            "<div class='form-check position-absolute' style='top:0; right:5px;'> " +
                                                "<input class='form-check-input' style='width:30px; height:30px;' name='event_check[]' type='checkbox' value='' id='check_task_"+ slug_name +"'> " +
                                            "</div> " +
                                        "</div> " +
                                        "<div class='collapse' id='collapseInfo_event_"+ slug_name +"' data-bs-parent='#data-wrapper'> " +
                                            "<hr style='margin-bottom:10px; margin-top:10px;'> " +
                                            "<div class='row px-2'> " +
                                                "<div class='col-lg-2 px-1'> " +
                                                    "<img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'> " +
                                                "</div> " +
                                                "<div class='col-lg-9 p-0 py-1'> " +
                                                    "<h6 class='event-title'>Deleted By ~ Deleted At</h6> " +
                                                    "<h6 class='event-subtitle'>" + getUsername(au_deleted, uu_deleted) + " ~ " + getDateTime(deleted_at) + "</h6> " +
                                                "</div> " +
                                            "</div> " +
                                        "</div> " +
                                    "</div> " +
                                "</button> " +
                            "</div> " +
                            getRecoverModal("Event", slug_name, data_from, info_type, info_body, content_title);
                        } else if(data_from == 2){ // Task
                            var elmt = " " +
                                "<div class='col-lg-4 col-md-6 col-sm-12 pb-3 content-item'> " +
                                    "<button class='card shadow task-box ultimate' onclick=''> " +
                                        "<div class='task-created-at'>" + getDateTime(created_at) + "</div> " +
                                        "<div class='card-body p-2 w-100'> " +
                                            "<div class='row px-2'> " +
                                                "<div class='col-lg-2 px-1'> " +
                                                    "<img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'> " +
                                                "</div> " +
                                                "<div class='col-lg-9 p-0 py-1'> " +
                                                    "<h6 class='task-title'>" + content_title + "</h6> " +
                                                    "<h6 class='task-subtitle'>[username]</h6> " +
                                                "</div> " +
                                            "</div> " +
                                            "<div style='height:45px;'> " +
                                                "<p class='task-desc my-1'>" + content_desc + "</p> " +
                                            "</div> " +
                                            "<div class='row d-inline-block px-2'> " +
                                                getEventDate(content_date_start, content_date_end) +
                                                getDaysRemaining(deleted_at) +
                                            "</div> " +
                                            "<hr style='margin-bottom:10px; margin-top:10px;'> " +
                                            "<div class='position-relative'> " +
                                                "<a class='btn btn-info px-3 me-1' title='See deleted info' data-bs-toggle='collapse' href='#collapseInfo_task_"+ slug_name +"' role='button' aria-expanded='false' aria-controls='collapseInfo'> " +
                                                    "<i class='fa-solid fa-info'></i> " +
                                                "</a> " +
                                                "<a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recoverTask-" + slug_name + "'> " +
                                                    "<i class='fa-solid fa-arrow-rotate-right'></i> " +
                                                "</a> " +
                                                "<a class='btn btn-danger' role='button' title='Permanently delete'> " +
                                                    "<i class='fa-solid fa-fire-flame-curved'></i> " +
                                                "</a> " +
                                                "<div class='form-check position-absolute' style='top:0; right:5px;'> " +
                                                    "<input class='form-check-input' style='width:30px; height:30px;' name='task_check[]' type='checkbox' value='' id='check_task_"+ slug_name +"'> " +
                                                "</div> " +
                                            "</div> " +
                                            "<div class='collapse' id='collapseInfo_task_"+ slug_name +"' data-bs-parent='#data-wrapper'> " +
                                                "<hr style='margin-bottom:10px; margin-top:10px;'> " +
                                                "<div class='row px-2'> " +
                                                    "<div class='col-lg-2 px-1'> " +
                                                        "<img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'> " +
                                                    "</div> " +
                                                    "<div class='col-lg-9 p-0 py-1'> " +
                                                        "<h6 class='task-title'>Deleted By ~ Deleted At</h6> " +
                                                        "<h6 class='task-subtitle'>" + getUsername(au_deleted, uu_deleted) + " ~ " + getDateTime(deleted_at) + "</h6> " +
                                                    "</div> " +
                                                "</div> " +
                                            "</div> " +
                                        "</div> " +
                                    "</button> " +
                                "</div> " +
                                getRecoverModal("Task", slug_name, data_from, info_type, info_body, content_title);
                        }

                    $("#data-wrapper").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }
</script>