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

<button class="btn btn-submit" data-bs-toggle="modal" style="height:40px; padding:0 15px !important;" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Group</button>
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Grouping</h5>
                
                <form action="/user/group/add" method="POST">
                    @csrf 
                    <div class="row mt-4">
                        <div class="col-lg-4 col-md-6 col-sm-12">
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
                                <a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAll()"><i class="fa-regular fa-trash-can"></i> Clear All</a>
                            </span>
                            <span id="user-selected-holder"></span>
                            @foreach($info as $in)
                                @if($in->info_location == "add_group")
                                    <div class="info-box {{$in->info_type}}">
                                        <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                        <?php echo $in->info_body; ?>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <h6>All User</h6>
                            @include("user.searchbar")
                            <span id="user-list-holder"></span>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <h6>User Detail</h6>
                            <span id="detail-holder"></span>
                        </div>
                    </div>
                    <input hidden name="selected_user" id="selected_user" value="">
                    <span id="submit_holder" class="float-end"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var page_new_req = 1;
    var selectedUser = []; 
    infinteLoadMoreUser(page_new_req);

    function loadmore_new_req(route){
        page_new_req++;
        infinteLoadMoreUser(page_new_req);
    }

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

    function infinteLoadMoreUser(page_new_req) {       
        var find = document.getElementById("title_search").value;
        document.getElementById("user-list-holder").innerHTML = "";

        $.ajax({
            url: "/api/v1/user/" + getFindUser(find) + "/limit/100/order/first_name__DESC?page=" + page_new_req,
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

            if(page_new_req != last){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
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

                    var elmt = " " +
                        '<a class="btn user-box" style="height:80px;" onclick="loadDetailGroup(' + "'" + img + "'" + ',' + "'" + grole + "'" + ', ' + "'" + fullName + "'" + ',' + "'" + username + "'" + ',' + "'" + email + "'" + ',' + "'" + joined + "'" + ')"> ' +
                            '<div class="row ps-2"> ' +
                                '<div class="col-2 p-0 py-2 ps-2"> ' +
                                    '<img class="img img-fluid user-image" src="'+getUserImage(img, grole)+'" alt="username-profile-pic.png"> ' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + fullName + '</h6> ' +
                                    '<h6 class="text-secondary fw-bold" style="font-size:13px;">' + grole + '</h6> ' +
                                    '<div class="form-check position-absolute" style="right: 20px; top: 20px;"> ' +
                                        '<input class="form-check-input" name="user_username[]" value="' + username + '" type="checkbox" style="width: 25px; height:25px;" id="check_'+ username +'" onclick="addSelected('+"'"+username+"'"+', '+"'"+fullName+"'"+', this.checked)" '+ getChecked(username) +'> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</a>';

                    $("#user-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
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
                } else {
                    console.log('Item not found LOL');
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
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelected('+"'"+e.username+"'"+', '+"'"+e.fullName+"'"+', false)" title="Remove this user"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.full_name + '</a>';
            holder.innerHTML += elmt;
        });
    }

    function getRoleArea(role){
        var elmnt = "";

        if(role){
            for(var i = 0; i < role.length; i++){
                elmnt += "<a class='btn btn-tag'>"+role[i]['tag_name']+"</a>"
            }
            return elmnt;

        } else {
            return "<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-role'> " +
                "<h6 class='text-center'>This user has no tag</h6>" ;
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

        var elmt_detail = " " +
            "<div class='m-2 p-3 text-center'> " +
                '<img class="img img-fluid rounded-circle shadow" style="max-width:140px;" src="'+getUserImage(img, grole)+'"> ' +
                '<h5 class="mt-3">'+fname+'</h5>' +
                '<h6 class="mt-1 text-secondary">@'+uname+', <span style="font-size:13px;">Joined since ' + getDateToContext(join, "full") + '</span></h6>' +
                '<a class="mt-1 text-secondary link-external" title="Send email" href="mailto:' + email + '">'+email+'</a>' +
            "</div>";
        
        document.getElementById("detail-holder").innerHTML = elmt_detail;
    }
</script>

