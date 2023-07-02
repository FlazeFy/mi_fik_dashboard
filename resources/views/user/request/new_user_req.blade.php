<style>
    #load_more_holder_new_req{
        position: absolute;
        bottom: -10px;
        right: 15px;
    }
</style>

<div class="incoming-req-box">
    <h5 class="text-secondary fw-bold"><span class="text-primary" id="total_new_req"></span> New User</h5>
    
    @if(!$isMobile)
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="@if(!$isMobile) right:var(--spaceXMD); @else right:var(--spaceJumbo); @endif top:0px;" type="button" id="section-more-new-req" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    @else 
    <button type="button" class="btn btn-mobile-control bg-info" id="section-more-new-req" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa-solid fa-gear"></i>
    </button>
    @endif

    <div class="dropdown-menu dropdown-menu-end @if($isMobile) mobile-control @endif" aria-labelledby="section-more-new-req">
        <a class="dropdown-item" data-bs-target="#newUserRequest" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" data-bs-toggle="modal" id="acc_all_new_btn" data-bs-target="#preventModalNew"><i class="fa-solid fa-check text-success"></i> <span class="text-success" id="total_acc_new">Accept Selected</span></a>
        <a class="dropdown-item" data-bs-toggle="modal" id="rej_all_new_btn" data-bs-target="#preventModalNew"><i class="fa-solid fa-xmark text-danger"></i> <span class="text-danger" id="total_rej_new">Reject Selected</span></a>
        <a class="dropdown-item" data-bs-toggle="modal" id="acc_all_new_tag_btn" data-bs-target="#preventModalNew"><i class="fa-solid fa-hashtag text-success"></i> <span class="text-success" id="total_acc_new_tag">Accept Selected & With Tag</span></a>
    </div>

    @if($isMobile)
        @include('user.request.control.searchbarnew')
    @endif

    @include('popup.mini_help', ['id' => 'newUserRequest', 'title'=> 'New User Request', 'location'=>'new_role_request'])

    <div class="@if(!$isMobile) user-req-holder @else pt-2 @endif" id="data_wrapper_new_req">
        <div class="auto-load-new text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </div>
    </div>
    <span id="load_more_holder_new_req" style="display: flex; justify-content:center;"></span>
    <div id="empty_item_holder_new_req"></div>
</div>

@include('user.request.new_manage.acc')
@include('user.request.new_manage.withtag')
@include('user.request.new_manage.rej')
@include('user.request.new_manage.prevent')

<script>
    var page_new_req = 1;
    var selectedNewUser = []; 
    infinteLoadMore_new_req(page_new_req);

    //Fix the sidebar & content page_new_req FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page_new_req++;
    //         infinteLoadMore(page_new_req);
    //     } 
    // };

    function loadmore_new_req(route){
        page_new_req++;
        infinteLoadMore(page_new_req);
    }

    function getFindNew(check){
        let trim = check.trim();
        if(check == null || trim === ''){
            return "%20"
        } else {
            document.getElementById("fullname_search_new").value = trim;
            return trim
        }
    }

    function infinteLoadMore_new_req(page_new_req) {       
        var find = document.getElementById("fullname_search_new").value;
        document.getElementById("data_wrapper_new_req").innerHTML = "";

        $.ajax({
            url: "/api/v1/user/request/new/" + getFindNew(find) + "?page=" + page_new_req,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-new').show();
            }
        })
        .done(function (response) {
            $('.auto-load-new').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_new_req != last){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            $('#total_new_req').text(total);

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Request found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-new').html("<h5 class='text-primary'>Woah!, You have see all the newest request :)</h5>");
                return;
            } else {
                function getApprovedButton(acc){
                    if(!acc){
                        return '<a class="btn btn-icon-rounded-success" style="position:absolute; right: 55px; top:15px;" title="Accept Request"><i class="fa-solid fa-check"></i></a>'
                    } else {
                        return ''
                    }
                }

                function getContext(acc){
                    if(!acc){
                        return 'Want to join Mi-FIK'
                    } else {
                        return "Doesn't have a tag"
                    }
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var username = data[i].username;
                    var full_name = data[i].full_name;
                    var img = data[i].image_url;
                    var role = data[i].role;
                    var created_at = data[i].created_at;
                    var is_accepted = data[i].is_accepted;
                    var accepted_at = data[i].accepted_at;

                    var elmt = " " +
                        '<button class="btn user-box" onclick="loadDetailGroup(' + "'" + username + "'" + ', ' + "'new'" + ', null)"> ' +
                            '<div class="row ps-3"> ' +
                                '<div class="col-2 p-0 ps-1"> ' +
                                    '<img class="img img-fluid user-image" style="margin-top:30%;" src="' + getUserImageGeneral(img, role) + '">' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + full_name + '</h6> ' +
                                    '<h6 class="user-box-desc">' + getContext(is_accepted) + '</h6> ' +
                                    '<h6 class="user-box-date">' + getDateToContext(created_at, "full") + '</h6> ' +
                                    '<div class="form-check position-absolute" style="right: 20px; top: 20px;"> ' +
                                        '<input class="form-check-input" type="checkbox" style="width: 25px; height:25px;" id="check_'+ username +'" onclick="addSelectedNew('+"'"+username+"'"+', '+"'"+full_name+"'"+', this.checked)""> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</button>';

                    $("#data_wrapper_new_req").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('#total_new_req').text("0");
            failResponse(jqXHR, ajaxOptions, thrownError, "#data_wrapper_new_req", false, null, null);
        });
    }

    function addSelectedNew(username, fullname, checked){
        var ddItemAcc = document.getElementById("acc_all_new_btn");
        var ddItemRej = document.getElementById("rej_all_new_btn");
        var ddItemAccTag = document.getElementById("acc_all_new_tag_btn");
       
        if(selectedNewUser.length == 0){
            selectedNewUser.push({
                full_name : fullname,
                username : username,
            });
        } else {
            if(checked === false){
                let indexToRemove = selectedNewUser.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedNewUser.splice(indexToRemove, 1);
                } else {
                    console.log('Item not found LOL');
                }
            } else {
                selectedNewUser.push({
                    full_name : fullname,
                    username : username,
                });
            }
        }
        console.log(selectedNewUser);
        
        if(selectedNewUser.length > 0){
            ddItemAcc.setAttribute('data-bs-target', '#accNewReqModal');
            ddItemRej.setAttribute('data-bs-target', '#rejNewReqModal');
            ddItemAccTag.setAttribute('data-bs-target', '#accNewReqTagModal');
            
            document.getElementById("total_acc_new").innerHTML = selectedNewUser.length + " <i class='fa-solid fa-circle fa-2xs'></i> Accept All";
            document.getElementById("total_acc_new_tag").innerHTML = selectedNewUser.length + " <i class='fa-solid fa-circle fa-2xs'></i> Accept All with New Tag";
            document.getElementById("total_rej_new").innerHTML = selectedNewUser.length + " <i class='fa-solid fa-circle fa-2xs'></i> Reject All";
        } else {
            ddItemAcc.setAttribute('data-bs-target', '#preventModalNew');
            ddItemRej.setAttribute('data-bs-target', '#preventModalNew');
            ddItemAccTag.setAttribute('data-bs-target', '#preventModalNew');

            document.getElementById("total_acc_new").innerHTML = " Accept All";
            document.getElementById("total_acc_new_tag").innerHTML = " Accept All with New Tag";
            document.getElementById("total_rej_new").innerHTML = " Reject All";
        }

        refreshListAccNew();
        refreshListAccNewTag();
        refreshListRejNew();
    }
</script>