<div class="incoming-req-box">
    <h5 class="section-title"><span class="text-primary" id="total_old_req"></span> {{ __('messages.role_req') }}</h5>

    @if(!$isMobile)
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="@if(!$isMobile) right:var(--spaceXMD); @else right:var(--spaceJumbo); @endif top:0;" type="button" id="section-more-old-req" data-bs-toggle="dropdown" aria-haspopup="true"
        onclick="cleanReq()" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    @else 
    <button type="button" class="btn btn-mobile-control bg-info" id="section-more-old-req" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa-solid fa-gear"></i>
    </button>
    @endif

    <div class="dropdown-menu dropdown-menu-end @if($isMobile) mobile-control @endif" aria-labelledby="section-more-old-req">
        <a class="dropdown-item" data-bs-target="#roleRequest" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> {{ __('messages.help') }}</a>
        <a class="dropdown-item" href="" data-bs-toggle="modal" id="acc_all_btn" data-bs-target="#preventModal"><i class="fa-solid fa-check text-success"></i> <span class="text-success" id="total_acc">Accept Selected</span></a>
        <a class="dropdown-item" href="" data-bs-toggle="modal" id="rej_all_btn" data-bs-target="#preventModal"><i class="fa-solid fa-xmark text-danger"></i> <span class="text-danger" id="total_reject">Reject Selected</span></a>
    </div>

    @if($isMobile)
        @include('user.request.control.searchbarold')
    @endif

    @include('popup.mini_help', ['id' => 'roleRequest', 'title'=> 'Role Request', 'location'=>'role_request'])

    <div class="@if(!$isMobile) user-req-holder @else pt-2 @endif">
        <div id="data_wrapper_old_req">
            <!-- Loading -->
            <div class="auto-load-old text-center">
                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
            </div>
        </div>
        <span id="load_more_holder_old_req" style="display: flex; justify-content:center;"></span>
    </div>

    <div id="empty_item_holder_old_req"></div>
</div>

@include('user.request.old_manage.acc')
@include('user.request.old_manage.rej')
@include('user.request.old_manage.prevent')

<script>
    var page_old_req = 1;
    var selectedOldUser = []; 

    infinteLoadMore_old_req(page_old_req);

    function loadmore_old_req(){
        page_old_req++;
        infinteLoadMore_old_req(page_old_req);
    }

    function getFindOld(check){
        let trim = check.trim();
        if(check == null || trim === ''){
            return "%20"
        } else {
            document.getElementById("fullname_search_old").value = trim;
            return trim
        }
    }

    function infinteLoadMore_old_req(page) { 
        var find = document.getElementById("fullname_search_old").value;
        //document.getElementById("data_wrapper_old_req").innerHTML = "";

        $.ajax({
            url: "/api/v1/user/request/old/" + getFindOld(find) + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-old').show();
            }
        })
        .done(function (response) {
            $('.auto-load-old').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder_old_req').html(`<button class="btn content-more-floating" onclick="loadmore_old_req()"><i class="fa-solid fa-magnifying-glass"></i> Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more_holder_old_req').html(`<h6 class="content-last">{{ __('messages.no_more') }}</h6>`);
            }

            $('#total_old_req').text(total);

            if (total == 0) {
                $('#empty_item_holder_old_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Request found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-old').html(`<h5 class='text-primary'>{{ __('messages.all_viewed') }}</h5>`);
                return;
            } else {
                function getContext(type, tag){
                    if(type == "add"){
                        var color = "success";
                        var ctx = `{{ __('messages.requested') }} `;
                    } else if(type == "remove"){
                        var color = "danger";
                        var ctx = `{{ __('messages.want_remove') }} `;
                    }

                    var tags = "";

                    for(var i = 0; i < tag.length; i++){
                        if(i != tag.length - 1){
                            tags += `<span class="text-${color} fw-bold">#${tag[i].tag_name}</span>, `;
                        } else {
                            tags += `<span class="text-${color} fw-bold">#${tag[i].tag_name}</span>`;
                        }
                    }

                    return ctx + tags;
                }

                for(var i = 0; i < data.length; i++){
                    var id = data[i].id;
                    var username = data[i].username;
                    var img = data[i].image_url;
                    var role = data[i].role;
                    var full_name = data[i].full_name;
                    var created_at = data[i].created_at;
                    var tag = data[i].tag_slug_name;
                    var type = data[i].request_type;

                    const elmt = `
                        <button class="btn user-box request" onclick="loadDetailGroup('${username}', 'old', '${id}'); slct_list = [];"> 
                            <div class="row ps-2"> 
                                <div class="col-2 p-0 ps-1"> 
                                    <img class="img img-fluid user-image" style="margin-top:45%;" src="${getUserImageGeneral(img, role)}">
                                </div> 
                                <div class="col-10 p-0 py-2 ps-2 position-relative"> 
                                    <h6 class="text-secondary fw-normal">${full_name}</h6>
                                    <div style="width: 80%;">
                                        <h6 class="user-box-desc">${getContext(type, tag)}</h6>
                                        <h6 class="user-box-date">${getDateToContext(created_at, "full")}</h6>
                                    </div>
                                    <div class="form-check position-absolute" style="right: 20px; top: 20px;"> 
                                        <input hidden id="tag_holder_${username + id}" value='${JSON.stringify(tag)}'>
                                        <input hidden id="type_holder_${username + id}" value="${type}">
                                        <input class="form-check-input" type="checkbox" style="width: 25px; height:25px;" id="check_${username}" onclick="addSelected('${id}','${username}','${type}', '${full_name}', this.checked)"> 
                                    </div>
                                </div>
                            </div>
                        </button>
                    `;

                    $("#data_wrapper_old_req").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('#total_old_req').text("0");
            failResponse(jqXHR, ajaxOptions, thrownError, "#data_wrapper_old_req", false, null, null);
        });
    }

    function addSelected(id, username, type, fullname, checked){
        var tag = document.getElementById("tag_holder_" + username + id).value;
        var ddItemAcc = document.getElementById("acc_all_btn");
        var ddItemRej = document.getElementById("rej_all_btn");
       
        if(selectedOldUser.length == 0){
            selectedOldUser.push({
                id : id,
                full_name : fullname,
                username : username,
                request_type : type,
                tag_list : tag,
            });
        } else {
            if(checked === false){
                let indexToRemove = selectedOldUser.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedOldUser.splice(indexToRemove, 1);
                } 
            } else {
                selectedOldUser.push({
                    id : id,
                    full_name : fullname,
                    username : username,
                    request_type : type,
                    tag_list : tag,
                });
            }
        }
        
        if(selectedOldUser.length > 0){
            ddItemAcc.setAttribute('data-bs-target', '#accOldReqModal');
            ddItemRej.setAttribute('data-bs-target', '#rejOldReqModal');
            
            document.getElementById("total_acc").innerHTML = selectedOldUser.length + " <i class='fa-solid fa-circle fa-2xs'></i> Accept All";
            document.getElementById("total_reject").innerHTML = selectedOldUser.length + " <i class='fa-solid fa-circle fa-2xs'></i> Reject All";
        } else {
            ddItemAcc.setAttribute('data-bs-target', '#preventModal');
            ddItemRej.setAttribute('data-bs-target', '#preventModal');

            document.getElementById("total_acc").innerHTML = " Accept All";
            document.getElementById("total_reject").innerHTML = " Reject All";
        }

        refreshListAcc()
        refreshListRej()
    }

    function loadDetailGroup(username, type, id){
        load_user_detail(username, type, id)
        infinteLoadMoreTag(1)
    }
</script>