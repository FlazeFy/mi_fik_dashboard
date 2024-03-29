<script type="text/javascript">
    var page = 1;
    var myname = "<?= session()->get("username_key") ?>";
    infinteLoadMore(page);

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

    function infinteLoadMore(page) {
        var order = <?php echo '"'.session()->get('ordering_trash').'";'; ?>
        var cat = <?php echo '"'.session()->get('filtering_trash').'";'; ?>
        <?php 
            foreach($info as $in){
                echo "var info_type_".$in->info_location." = ".'"'.$in->info_type.'"'.";
                var info_body_".$in->info_location." = ".'"'.$in->info_body.'"'.";";
            }

            foreach($settingJobs as $stj){
                echo "var dcd_range = ".$stj->DCD_range.";
                var dtd_range = ".$stj->DTD_range.";";
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
                $('#load_more_holder').html(`<button class="btn content-more-floating my-3 p-2 d-block mx-auto" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more_holder').html(`<h6 class="btn text-secondary my-3 p-2 d-block mx-auto">{{ __('messages.no_more') }}</h6>`);
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata2.png' class='img nodata-icon'><h6 class='text-secondary text-center'>Trash can is empty</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html(`<h5 class='text-secondary'>{{ __('messages.all_viewed') }}</h5>`);
                return;
            } else {
                function getDaysRemaining(date, range){
                    date = new Date(date)
                    now = new Date()
                    const deadDate = new Date(date.setDate(date.getDate() + range))
                    
                    const timeDiff = now - deadDate
                    var daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) * -1
                    
                    return "<a class='text-danger fst-italic fw-bold' title='Days before auto deleted from system' style='font-size:12px;'>" + daysDiff + " days remaining</a>";
                }

                function getRecoverModal(type, slug_name, data_from, info_type, info_body, content_title){
                    return `
                    <div class="modal fade" id="recover${type}-${slug_name}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body text-center pt-4">
                                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                    <form class="d-inline" action="/trash/recover/${slug_name}/${data_from}" method="POST">
                                        @csrf
                                        <p style="font-weight: 500;">Are you sure want to recover '<span class="text-primary">${content_title}</span>' ${type}?</p>
                                        <div class="info-box ${info_type}">
                                            <label><i class="fa-solid fa-circle-info"></i> ${info_type}</label><br>
                                            ${info_body}
                                        </div>
                                        <button class="btn btn-submit" type="submit">Recover</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }

                function getDestroyModal(type, slug_name, data_from, info_type, info_body, content_title){
                    return `
                    <div class="modal fade" id="destroy${type}-${slug_name}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body text-center pt-4">
                                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                    <form class="d-inline" action="/trash/destroy/${slug_name}/${data_from}" method="POST">
                                        @csrf
                                        <input value="${content_title}" name="content_title" hidden>
                                        <p style="font-weight: 500;">Are you sure want to permanently delete '<span class="text-primary">${content_title}</span>' ${type}?</p>
                                        <div class="info-box ${info_type}">
                                            <label><i class="fa-solid fa-triangle-exclamation"></i> ${info_type}</label><br>
                                            ${info_body}
                                        </div>
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>`;
                }

                function getTrashModalCtx(title1, title2){
                    if(data_from == 5 || data_from == 7 || data_from == 8 || data_from == 6){
                        return title2;
                    } else {
                        return title1;
                    }
                }

                for(var i = 0; i < data.length; i++){
                    var slug_name = data[i].slug_name;
                    var content_title = data[i].content_title;
                    var content_desc = data[i].content_desc;
                    var content_loc = data[i].content_loc;
                    var content_tag = data[i].content_tag;
                    var content_image = data[i].content_image;
                    var content_date_start = data[i].content_date_start;
                    var content_date_end = data[i].content_date_end;
                    var data_from = data[i].data_from;
                    var created_at = data[i].created_at;
                    var deleted_at = data[i].deleted_at;
                    var au_created = data[i].admin_username_created;
                    var uu_created = data[i].user_username_created;
                    var ai_created = data[i].admin_image_created;
                    var ui_created = data[i].user_image_created;
                    var au_updated = data[i].admin_username_updated;
                    var uu_updated = data[i].user_username_updated;
                    var au_deleted = data[i].admin_username_deleted;
                    var uu_deleted = data[i].user_username_deleted;
                    var ai_deleted = data[i].admin_image_deleted;
                    var ui_deleted = data[i].user_image_deleted;

                    if (data_from === 1) { // Event
                        const elmt = `
                            <div class='pb-3 content-item'>
                                <button class='card shadow event-box ultimate' style='min-height:auto;' onclick=''>
                                    <div class="card-header header-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.55)), ${getContentImage(content_image)};"></div>
                                    <div class='event-created-at'>${getDateToContext(created_at, "full")}</div>
                                    <div class='card-body p-2 w-100'>
                                        <div>
                                            <div class='d-inline-block'>
                                                <img class='img img-fluid user-image-content' src='${getUserImage(ai_created, ui_created, au_created, uu_created)}' alt='username-profile-pic.png'>
                                            </div>
                                            <div class='d-inline-block position-relative w-75'>
                                                <h6 class='event-title'>${ucEachWord(content_title)}</h6>
                                                <h6 class='event-subtitle'>${getUsername(au_created, uu_created)}</h6>
                                            </div>
                                        </div>
                                        <p class='event-desc my-1'>${ucFirst(removeTags(content_desc))}</p>
                                        <div class='row d-inline-block px-2'>
                                            ${getLocationName(content_loc)}
                                            ${getEventDate(content_date_start, content_date_end)}
                                            ${getEventTag(content_tag)}
                                            ${getDaysRemaining(deleted_at, dcd_range)}
                                        </div>
                                        <hr style='margin-bottom:10px; margin-top:10px;'>
                                        <div class='position-relative'>
                                            <a class='btn btn-info px-3 me-1' title='See deleted info' data-bs-toggle='collapse' href='#collapseInfo_event_${slug_name}' role='button' aria-expanded='false' aria-controls='collapseInfo'>
                                                <i class='fa-solid fa-info'></i>
                                            </a>
                                            <a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recoverEvent-${slug_name}'>
                                                <i class='fa-solid fa-arrow-rotate-right'></i>
                                            </a>
                                            <a class='btn btn-danger' role='button' title='Permanently delete' data-bs-toggle='modal' data-bs-target='#destroyEvent-${slug_name}'>
                                                <i class='fa-solid fa-fire-flame-curved'></i>
                                            </a>
                                        </div>
                                        <div class='collapse' id='collapseInfo_event_${slug_name}' data-bs-parent='#data-wrapper'>
                                            <hr style='margin-bottom:10px; margin-top:10px;'>
                                            <div>
                                                <div class='d-inline-block'>
                                                    <img class='img img-fluid user-image-content' src='${getUserImage(ai_deleted, ui_deleted, au_deleted, uu_deleted)}' alt='username-profile-pic.png'>
                                                </div>
                                                <div class='d-inline-block position-relative w-75'>
                                                    <h6 class='event-title'>{{ __('messages.deleted_by') }} ~ {{ __('messages.deleted_at') }}</h6>
                                                    <h6 class='event-subtitle'>${getUsername(au_deleted, uu_deleted)} ~ ${getDateToContext(deleted_at, "full")}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            ${getRecoverModal("Event", slug_name, data_from, info_type_recover_content, info_body_recover_content, content_title)}
                            ${getDestroyModal("Event", slug_name, data_from, info_type_destroy_content, info_body_destroy_content, content_title)}`;

                        $("#data-wrapper-event").append(elmt);
                    } else if (data_from === 2) { // Task
                        const elmt = `
                            <div class='pb-3 content-item'>
                                <button class='card shadow task-box ultimate' onclick=''>
                                    <div class='task-created-at'>${getDateToContext(created_at, "full")}</div>
                                    <div class='card-body p-2 w-100'>
                                        <div>
                                            <div class='d-inline-block'>
                                                <img class='img img-fluid user-image' src='${getUserImageGeneral(ui_created, <?= session()->get('role_key'); ?>)}'>
                                            </div>
                                            <div class='d-inline-block position-relative w-50'>
                                                <h6 class='task-title'>${content_title}</h6>
                                                <h6 class='task-subtitle'>${getUsername(au_created, uu_created)}</h6>
                                            </div>
                                        </div>
                                        <p class='task-desc my-1'>${content_desc}</p>
                                        <div class='row d-inline-block px-2'>
                                            ${getEventDate(content_date_start, content_date_end)}
                                            ${getDaysRemaining(deleted_at, dtd_range)}
                                        </div>
                                        <hr style='margin-bottom:10px; margin-top:10px;'>
                                        <div class='position-relative'>
                                            <a class='btn btn-info px-3 me-1' title='See deleted info' data-bs-toggle='collapse' href='#collapseInfo_task_${slug_name}' role='button' aria-expanded='false' aria-controls='collapseInfo'>
                                                <i class='fa-solid fa-info'></i>
                                            </a>
                                            <a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recoverTask-${slug_name}'>
                                                <i class='fa-solid fa-arrow-rotate-right'></i>
                                            </a>
                                            <a class='btn btn-danger' role='button' title='Permanently delete' data-bs-toggle='modal' data-bs-target='#destroyTask-${slug_name}'>
                                                <i class='fa-solid fa-fire-flame-curved'></i>
                                            </a>
                                        </div>
                                        <div class='collapse' id='collapseInfo_task_${slug_name}' data-bs-parent='#data-wrapper'>
                                            <hr style='margin-bottom:10px; margin-top:10px;'>
                                            <div>
                                                <div class='d-inline-block'>
                                                    <img class='img img-fluid user-image-content' src='${getUserImageGeneral(ui_deleted, <?= session()->get('role_key'); ?>)}'>
                                                </div>
                                                <div class='d-inline-block position-relative w-75'>
                                                    <h6 class='task-title'>Deleted By ~ Deleted At</h6>
                                                    <h6 class='task-subtitle'>${getUsername(au_deleted, uu_deleted)} ~ ${getDateToContext(deleted_at, "full")}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            ${getRecoverModal("Task", slug_name, data_from, info_type_recover_content, info_body_recover_content, content_title)}
                            ${getDestroyModal("Task", slug_name, data_from, info_type_destroy_content, info_body_destroy_content, content_title)}`;

                        $("#data-wrapper-task").append(elmt);
                    } else if (data_from === 3 || data_from === 5 || data_from === 4 || data_from === 7) { // Tag, Info, Group, Dictionary
                        let icon, type;
                        if (data_from === 3) {
                            icon = 'fa-hashtag';
                            type = "tag";
                        } else if (data_from === 5) {
                            icon = 'fa-circle-info';
                            type = "info";
                        } else if (data_from === 4) {
                            icon = 'fa-users';
                            type = "group";
                        } else if (data_from === 7) {
                            icon = 'fa-book';
                            type = "dictionary";
                        }

                        const elmt = `
                            <div class='pb-3 content-item'>
                                <button class='card shadow task-box ultimate' onclick=''>
                                    <div class='task-created-at'>${getDateToContext(created_at, "full")}</div>
                                    <div class='card-body p-2 w-100'>
                                        <div class='position-relative'>
                                            <div class='d-inline-block me-2'>
                                                <i class='fa-solid ${icon} fa-xl mt-3 text-primary'></i>
                                            </div>
                                            <div class='d-inline-block position-absolute w-50' style='top:37.5px;'>
                                                <h6 class='task-title'>${content_title}</h6>
                                                <h6 class='task-subtitle'>${content_tag}</h6>
                                            </div>
                                        </div>
                                        <p class='task-desc mb-1 mt-3'>${content_desc}</p>
                                        <div class='row d-inline-block px-2'>
                                            ${getEventDate(content_date_start, content_date_end)}
                                            ${getDaysRemaining(deleted_at, dcd_range)}
                                        </div>
                                        <hr style='margin-bottom:10px; margin-top:10px;'>
                                        <div class='position-relative'>
                                            <a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recover${type}-${slug_name}'>
                                                <i class='fa-solid fa-arrow-rotate-right'></i>
                                            </a>
                                            <a class='btn btn-danger' role='button' title='Permanently delete' data-bs-toggle='modal' data-bs-target='#destroy${type}-${slug_name}'>
                                                <i class='fa-solid fa-fire-flame-curved'></i>
                                            </a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            ${getRecoverModal(type, slug_name, data_from, info_type_recover_content, info_body_recover_content, getTrashModalCtx(content_title, content_desc))}
                            ${getDestroyModal(type, slug_name, data_from, info_type_destroy_content, info_body_destroy_content, getTrashModalCtx(content_title, content_desc))}`;

                        $(`#data-wrapper-${type}`).append(elmt);
                    } else if (data_from === 8) { // Question
                        const icon = 'fa-circle-question';
                        const type = "Question";

                        const elmt = `
                            <div class='pb-3 content-item'>
                                <button class='card shadow task-box ultimate' onclick=''>
                                    <div class='task-created-at'>${getDateToContext(created_at, "full")}</div>
                                    <div class='card-body p-2 w-100'>
                                        <div class='position-relative'>
                                            <div class='d-inline-block me-2'>
                                                <i class='fa-solid ${icon} fa-xl mt-3 text-primary'></i>
                                            </div>
                                            <div class='d-inline-block position-absolute w-50' style='top:45px;'>
                                                <h6 class='task-title'>${content_title}</h6>
                                            </div>
                                        </div>
                                        <h6 class='mt-1' style='font-size:12px;'>Question</h6>
                                        <p class='task-desc mb-1'>${content_desc}</p>
                                        <h6 class='mt-2' style='font-size:12px;'>Answer</h6>
                                        <p class='task-desc mb-1'>${content_tag}</p>
                                        <div class='row d-inline-block px-2'>
                                            ${getEventDate(content_date_start, content_date_end)}
                                            ${getDaysRemaining(deleted_at, dcd_range)}
                                        </div>
                                        <hr style='margin-bottom:10px; margin-top:10px;'>
                                        <div class='position-relative'>
                                            <a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recover${type}-${slug_name}'>
                                                <i class='fa-solid fa-arrow-rotate-right'></i>
                                            </a>
                                            <a class='btn btn-danger' role='button' title='Permanently delete' data-bs-toggle='modal' data-bs-target='#destroy${type}-${slug_name}'>
                                                <i class='fa-solid fa-fire-flame-curved'></i>
                                            </a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            ${getRecoverModal("Question", slug_name, data_from, info_type_recover_content, info_body_recover_content, getTrashModalCtx(content_title, content_desc))}
                            ${getDestroyModal("Question", slug_name, data_from, info_type_destroy_content, info_body_destroy_content, getTrashModalCtx(content_title, content_desc))}`;

                        $("#data-wrapper-question").append(elmt);
                    } else if (data_from === 6) { // Feedback
                        const type = "Feedback";
                        const elmt = `
                            <div class='pb-3 content-item'>
                                <button class='card shadow task-box ultimate' onclick=''>
                                    <div class='task-created-at'>${getDateToContext(created_at, "full")}</div>
                                    <div class='card-body p-2 w-100'>
                                        <div class='position-relative'>
                                            <div class='d-inline-block me-2'>
                                                <i class='fa-solid fa-star fa-lg mt-3 text-primary'> <span style='font-size:16px;'>${ucFirst(content_title)}</span></i>
                                            </div>
                                            <div class='d-inline-block position-absolute w-50' style='top:30px;'>
                                                <h6 class='task-subtitle'>${content_tag}</h6>
                                            </div>
                                        </div>
                                        <p class='task-desc mb-1 mt-3'>${content_desc}</p>
                                        <div class='row d-inline-block px-2'>
                                            ${getEventDate(content_date_start, content_date_end)}
                                            ${getDaysRemaining(deleted_at, dcd_range)}
                                        </div>
                                        <hr style='margin-bottom:10px; margin-top:10px;'>
                                        <div class='position-relative'>
                                            <a class='btn btn-submit me-1' role='button' title='Recover this content' data-bs-toggle='modal' data-bs-target='#recover${type}-${slug_name}'>
                                                <i class='fa-solid fa-arrow-rotate-right'></i>
                                            </a>
                                            <a class='btn btn-danger' role='button' title='Permanently delete' data-bs-toggle='modal' data-bs-target='#destroy${type}-${slug_name}'>
                                                <i class='fa-solid fa-fire-flame-curved'></i>
                                            </a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            ${getRecoverModal("Feedback", slug_name, data_from, info_type_recover_content, info_body_recover_content, getTrashModalCtx(content_title, content_desc))}
                            ${getDestroyModal("Feedback", slug_name, data_from, info_type_destroy_content, info_body_destroy_content, getTrashModalCtx(content_title, content_desc))}`;

                        $("#data-wrapper-feedback").append(elmt);
                    }
                }   
            }
            var listCat = ["event","task","tag","info","group","dictionary","feedback","question","notification"];
            listCat.forEach(e=>{
                if($("#data-wrapper-"+e).children().length === 0){
                    if($("#title_search").value != ""){
                        $("#data-wrapper-"+e).html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>Item not found</h6></div>");
                    } else {
                        $("#data-wrapper-"+e).html("<div class='err-msg-data'><img src='{{ asset('/assets/trash.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>This trash can is clean</h6></div>");
                    }
                }
            });
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load').hide();
            failResponse(jqXHR, ajaxOptions, thrownError, ".empty_item_holder", false, null, null);
        });
    }
</script>