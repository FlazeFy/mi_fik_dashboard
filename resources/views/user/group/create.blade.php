<script>
    let validation = [
        { id: "group_name", req: true, len: 75 },
        { id: "group_desc", req: false, len: 255 },
    ];
</script>

<style>
    #user-list-holder{
        padding: 5px 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 65vh;
        overflow-y: scroll;
    }
</style>

@if(!$isMobile)
    <button class="btn btn-submit" data-bs-toggle="modal" style="height:40px; padding:0 15px !important;" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Group</button>
@else 
    <button type="button" class="btn btn-mobile-control bg-success" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i>
    </button>
@endif

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                @if($isMobile && $info)
                <button type="button" class="custom-close-modal bg-info" data-bs-toggle="collapse" href="#collapseInfo" style="right:65px;" title="Info"><i class="fa-solid fa-info"></i></button>
                @endif
                <h5>Add Grouping</h5>
                
                <form action="/user/group/add" method="POST" id="form-add-group">
                    @csrf 
                    <div class="row mt-4 pb-2">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            @if($isMobile && $info)
                            <div class="collapse" id="collapseInfo">
                                @include('components.infobox',['info'=>$info, 'location'=> 'add_group'])           
                            </div>
                            @endif

                            <div class="form-floating">
                                <input type="text" class="form-control nameInput" id="group_name" name="group_name" maxlength="75" oninput="validateForm(validation)" required>
                                <label for="titleInput_event">Group Name</label>
                                <a id="group_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                            </div>
                            <div class="form-floating mt-2">
                                <textarea class="form-control" id="group_desc" name="group_desc" style="height: 140px" maxlength="255" oninput="validateForm(validation)"></textarea>
                                <label for="floatingTextarea2">Description (Optional)</label>
                                <a id="group_desc_msg" class="input-warning text-danger"></a>
                            </div>

                            <span class="position-relative">
                                <h6 class="mt-2">Selected User</h6>
                                <a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAll()"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</a>
                            </span>
                            <span id="user-selected-holder"></span>

                            @if($info && !$isMobile)
                                @include('components.infobox',['info'=>$info, 'location'=> 'add_group'])           
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 position-relative">
                            <h6>All User</h6>
                            @include("user.searchbar")
                            <span id="user-list-holder"></span>
                            <span class="position-absolute">
                                <h6 class="mt-1">Page</h6> 
                                <div id="all-user-page" class="mt-2"></div> 
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <h6>User Detail</h6>
                            <span id="detail-holder"></span>
                        </div>
                    </div>
                    <input hidden name="selected_user" id="selected_user" value="">
                    <span id="submit_holder" class="float-end"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var page_new_req = 1;
    var lastPageAllUser = 1;
    var selectedUser = []; 
    infinteLoadMoreUser(page_new_req);

    function loadmore_new_req(route){
        page_new_req++;
        infinteLoadMoreUser(page_new_req);
    }

    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-group');
            const inputs = form.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token" && input.name != "user_username[]") {
                    is_editing = true;
                    console.log(input.name)
                    break;
                }
            }

            if(is_editing || selectedUser.length > 0 || selectedMember.length > 0 || selectedMemberRemove.length > 0){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });

    function getUserImage(img, role){
        if(img != null && img != "null"){
            return img;
        } else {
            if(role == "Lecturer"){
                return "{{ asset('/assets/default_lecturer.png')}}";
            } else {
                return "{{ asset('/assets/default_student.png')}}";
            }
        } 
    }

    function getFindUser(check){
        let trim = check.trim();
        if(check == null || trim === ''){
            return "all_all";
        } else {
            document.getElementById("title_search").value = trim;
            return trim;
        }
    }

    function getChecked(username){
        let find = selectedUser.findIndex(obj => obj.username == username);
        if(find != -1){
            return "checked";
        } else {
            return "";
        }
    }

    function infinteLoadMoreUser(page) {       
        page_new_req = page;
        var find = document.getElementById("title_search").value;
        document.getElementById("user-list-holder").innerHTML = "";

        var per_page = 24;
        if(isMobile()){
            per_page = 12;
        } 

        $.ajax({
            url: "/api/v1/user/" + getFindUser(find) + "/limit/"+per_page+ "/order/first_name__DESC/slug/all?page=" + page,
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
            lastPageAllUser = response.data.last_page;

            if(page != lastPageAllUser){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event</h5>");
                return;
            } else {                
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var username = data[i].username;
                    var fullName = data[i].full_name;
                    var grole = data[i].general_role;
                    var img = data[i].image_url;
                    var role = data[i].role;
                    var email = data[i].email;
                    var joined = data[i].accepted_at;

                    const elmt = `
                            <a class="btn user-box" style="height:80px;" onclick="loadDetailGroup('${img}', '${grole}', '${fullName}', '${username}', '${email}', '${joined}')">
                                <div class="row ps-2">
                                    <div class="col-2 p-0 py-2 ps-2">
                                        <img class="img img-fluid user-image" src="${getUserImage(img, grole)}" alt="username-profile-pic.png">
                                    </div>
                                    <div class="col-10 p-0 py-2 ps-2 position-relative">
                                        <h6 class="text-secondary fw-normal">${fullName}</h6>
                                        <h6 class="text-secondary fw-bold" style="font-size:13px;">${getRole(grole)}</h6>
                                        <div class="form-check position-absolute" style="right: 20px; top: 20px;">
                                            <input class="form-check-input" name="user_username[]" value="${username}" type="checkbox" style="width: 25px; height:25px;" id="check_${username}" onclick="addSelected('${username}', '${fullName}', this.checked)" ${getChecked(username)}>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;


                    $("#user-list-holder").prepend(elmt);
                }   
            }
            generatePageAllUser();
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            failResponse(jqXHR, ajaxOptions, thrownError, "#user-list-holder", false, null, null);
            lastPageAllUser = 1;
            generatePageAllUser();
        });
    }

    function generatePageAllUser(){
        $("#all-user-page").empty();
        for(var i = 1; i <= lastPageAllUser; i++){
            if(i == page_new_req){
                var elmt = `<a class='page-holder active'>${i}</a>`;
            } else {
                var elmt = `<a class='page-holder' onclick='infinteLoadMoreUser("${i}")'>${i}</a>`;
            }
            $("#all-user-page").append(elmt);
        }
    }

    function addSelected(username, fullname, checked){
        var input_holder = document.getElementById("selected_user");
        if(selectedUser.length == 0){
            selectedUser.push({
                full_name : fullname,
                username : username
            });
            input_holder.value = JSON.stringify(selectedUser);
        } else {
            if(checked === false){
                let indexToRemove = selectedUser.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedUser.splice(indexToRemove, 1);

                    // Make sure the item unchecked by remove from selected user list
                    document.getElementById("check_"+username).checked = false; 
                    input_holder.value = JSON.stringify(selectedUser);
                }
            } else {
                selectedUser.push({
                    full_name : fullname,
                    username : username
                });
                input_holder.value = JSON.stringify(selectedUser);
            }
        }
        //console.log(input_holder);
        // console.log(selectedUser);
        refreshList();
    }

    function refreshList(){
        var holder = document.getElementById("user-selected-holder");
        holder.innerHTML = "";

        selectedUser.forEach((e) => {
            var elmt = `
                <a class="remove_suggest" onclick="addSelected('${e.username}', '${e.fullName}', false)" title="Remove this user"> ' +
                <i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                <a>${e.full_name}</a>`;
            holder.innerHTML += elmt;
        });
    }

    function getRoleArea(role){
        var elmnt = "";

        if(role){
            for(var i = 0; i < role.length; i++){
                elmnt += `<a class='btn btn-tag'>${role[i]['tag_name']}</a>`
            }
            return elmnt;

        } else {
            return `<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-role'>
                <h6 class='text-center'>This user has no tag</h6>` ;
        }
    }

    function clearAll(){
        document.getElementById("user-selected-holder").innerHTML = "";
        selectedUser.forEach((e) => {
            document.getElementById("check_"+e.username).checked = false; 
        });
        selectedUser = [];
    }

    function loadDetailGroup(img, grole, fname, uname, email, join){
        document.getElementById("detail-holder").innerHTML = "";

        const elmt_detail = `
            <div class="m-2 p-3 text-center">
                <img class="img img-fluid rounded-circle shadow" style="width:180px; height:180px;" src="${getUserImage(img, grole)}">
                <h5 class="mt-3">${fname}</h5>
                <h6 class="mt-1 text-secondary">@${uname}, <span style="font-size:13px;">Joined since ${getDateToContext(join, "full")}</span></h6>
                <a class="mt-1 text-secondary link-external" title="Send email" href="mailto:${email}">${email}</a>
            </div>
        `;

        
        document.getElementById("detail-holder").innerHTML = elmt_detail;
    }
</script>

