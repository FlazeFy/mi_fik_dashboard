<style>
    .groups-rel-holder, .groups-ava-holder{
        padding: 5px 16px 0 5px;
        display: inline-block;
        flex-direction: column;
        max-height: 65vh;
        overflow-y: scroll;
        white-space: normal;
    }
    .user-check .user-check-title{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>

<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Group Name @include('user.group.sorting.groupname')</th>
                <th scope="col">Description @include('user.group.sorting.groupdesc')</th>
                <th scope="col">Total @include('user.group.sorting.total')</th>
                <th scope="col">Properties @include('user.group.sorting.created')</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="group-list-holder">
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
        </tbody>
        <div id="empty_item_holder"></div>
        <span id="load_more_holder" style="position:absolute; right:20px; top:20px;"></span>
        </div>
    </table>
</div>

<script>
    var page_new_req = 1;
    infinteLoadMore(page_new_req);
    var selectedMember = []; 
    var selectedMemberRemove = []; 

    function loadmore(route){
        page_new_req++;
        infinteLoadMore(page_new_req);
    }

    function getFind(check){
        let trim = check.trim();
        if(check == null || trim === ''){
            return "%20"
        } else {
            document.getElementById("group_search").value = trim;
            return trim
        }
    }

    function infinteLoadMore(page_new_req) {    
        var order = '<?= session()->get('ordering_group_list'); ?>';
        var find = document.getElementById("group_search").value;
        document.getElementById("group-list-holder").innerHTML = "";
    
        $.ajax({
            url: "/api/v1/group/limit/100/order/" + order + "/find/" + getFind(find) + "?page=" + page_new_req,
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
                $('#load_more_holder').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            $('#total_new_req').text(total);

            if (total == 0) {
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No users found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getDateContext(datetime){
                    if(datetime){
                        const result = new Date(datetime);
                        const now = new Date(Date.now());
                        const yesterday = new Date();
                        var elmt = ""
                        yesterday.setDate(yesterday.getDate() - 1);
                        
                        if(result.toDateString() === now.toDateString()){
                            elmt = "Today at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                        } else if(result.toDateString() === yesterday.toDateString()){
                            elmt = "Yesterday at" + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                        } else {
                            elmt = result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
                        }

                        return "<span class='text-secondary'>" + elmt + "</span>"
                    } else {
                        return "-"
                    }
                }

                function deleteGroup(id, slug, name){
                    var elmt = ' ' +
                        '<div class="modal fade" id="delete-group-'+slug+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog"> ' +
                                '<div class="modal-content"> ' +
                                    '<div class="modal-body text-center pt-4"> ' +
                                        '<button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<p style="font-weight:500;">Are you sure want to delete "' + name + '" group</p> ' +
                                        '@foreach($info as $in) ' +
                                            '@if($in->info_location == "delete_group") ' +
                                                '<div class="info-box {{$in->info_type}}"> ' +
                                                    '<label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br> ' +
                                                    "{!! $in->info_body !!} " +
                                                '</div> ' +
                                            '@endif ' +
                                        '@endforeach ' +
                                        '<form class="d-inline" action="/user/group/delete/'+id+'" method="POST"> ' +
                                            '@csrf ' +
                                            '<input hidden name="group_name" value="' + name + '"> ' +
                                            '<button class="btn btn-danger float-end" type="submit">Delete</button> ' +
                                        '</form> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ';
                    
                    return elmt;
                }

                function editGroup(id, slug, name, desc, updated){
                    var elmt = ' ' +
                        '<div class="modal fade" id="edit-group-'+slug+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog"> ' +
                                '<div class="modal-content"> ' +
                                    '<div class="modal-body text-left pt-4"> ' +
                                        '<button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<h5>Edit Group</h5> ' +
                                        '<form class="d-inline" action="/user/group/edit/'+id+'" method="POST"> ' +
                                            '@csrf ' +
                                            '<input hidden name="group_name" value="' + name + '"> ' +
                                            '<div class="form-floating"> ' +
                                                '<input type="text" class="form-control nameInput" id="group_name" name="group_name" value="' + name + '" maxlength="75" oninput="" required> ' +
                                                '<label for="titleInput_event">Group Name</label> ' +
                                                '<a id="group_name_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                                            '</div> ' +
                                            '<div class="form-floating mt-2"> ' +
                                                '<textarea class="form-control" id="group_desc" name="group_desc" style="height: 140px" maxlength="255" value="' + desc + '" oninput="">' + desc + '</textarea> ' +
                                                '<label for="floatingTextarea2">Description (Optional)</label> ' +
                                                '<a id="group_desc_msg" class="input-warning text-danger"></a> ' +
                                            '</div> ' +
                                            '<p>Last Updated : ' + getDateContext(updated) + '</p> '+
                                            '@foreach($info as $in) ' +
                                                '@if($in->info_location == "edit_group") ' +
                                                    '<div class="info-box {{$in->info_type}}"> ' +
                                                        '<label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br> ' +
                                                        "{!! $in->info_body !!} " +
                                                    '</div> ' +
                                                '@endif ' +
                                            '@endforeach ' +
                                            '<input hidden name="old_group_name" value="' + name + '">' +
                                            '<button class="btn btn-submit-form" type="submit" id="btn-submit"><i class="fa-solid fa-paper-plane"></i> Submit</button> ' +
                                        '</form> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ';
                    
                    return elmt;
                }

                function manageRel(id, slug, name){
                    var elmt = ' ' +
                        '<div class="modal fade" id="manage-rel-'+slug+'" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog modal-xl"> ' +
                                '<div class="modal-content"> ' +
                                    '<div class="modal-body text-left pt-4"> ' +
                                        '<button type="button" class="custom-close-modal" onclick="clearAllNewMember(' + "'" + slug + "'" + ')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<h5>Manage Group Relation</h5> ' +                                       
                                        '<div class="row mt-4"> ' +
                                            '<div class="col-lg-8 col-md-7 col-sm-12"> ' +
                                                '<h6>Engagement</h6> ' + 

                                                '<form action="/user/group/member/remove/'+id+'" method="POST"> ' +
                                                    '@csrf ' +
                                                    '<span class="position-relative"> ' +
                                                        '<h6 class="mt-2">Available Member</h6> ' +
                                                        '<a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllRemoveMember(' + "'" + slug + "'" + ')"><i class="fa-regular fa-trash-can"></i> Clear All</a> ' +
                                                        '<span id="submit-rel-remove-btn-holder-'+slug+'"></span>' +
                                                    '</span> ' +
                                                    '<span id="manage-rel-holder-'+slug+'" class="groups-rel-holder"></span> ' +
                                                    '<input hidden name="selected_member_remove" id="selected_member_remove-'+slug+'" value=""> ' +
                                                    '<input hidden name="group_name" value="' + name + '"> ' +
                                                '</form> ' +
                                                '<span id="err-rel-holder-'+slug+'"></span> ' +

                                                '<hr> ' +
                                                '<form action="/user/group/member/add/'+id+'" method="POST"> ' +
                                                    '@csrf ' +
                                                    '<span class="position-relative"> ' +
                                                        '<h6 class="mt-2">Selected User</h6> ' +
                                                        '<a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllNewMember(' + "'" + slug + "'" + ')"><i class="fa-regular fa-trash-can"></i> Clear All</a> ' +
                                                        '<span id="submit-rel-add-btn-holder-'+slug+'"></span>' +
                                                    '</span> ' +
                                                    '<input hidden name="selected_member" id="selected_member-'+slug+'" value=""> ' +
                                                    '<input hidden name="group_name" value="' + name + '"> ' +
                                                '</form> ' +

                                                '<span id="user-selected-newmember-holder-'+slug+'"></span> ' +
                                            '</div> ' +
                                            '<div class="col-lg-4 col-md-5 col-sm-12"> ' +
                                                '<h6>All User</h6> ' + 
                                                '<span id="user-ava-holder-'+slug+'" class="groups-ava-holder"></span> ' +
                                                '<span id="err-ava-holder-'+slug+'"></span> ' +
                                            '</div> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ';
                    
                    return elmt;
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var groupName = data[i].group_name;
                    var slug = data[i].slug_name;
                    var id = data[i].id;
                    var groupDesc = data[i].group_desc;
                    var total = data[i].total;
                    var createdAt = data[i].created_at;
                    var updatedAt = data[i].updated_at;

                    var elmt = " " +
                        '<tr class="tabular-item"> ' +
                            '<td>' + groupName + '</td> ' +
                            '<td>' + groupDesc + '</td> ' +
                            '<td>' + total + '</td> ' +
                            '<td class="properties"> ' +
                                '<h6>Joined At</h6> ' +
                                '<a>' + getDateContext(createdAt) + '</a> ' +
                                '<h6>Updated At</h6> ' +
                                '<a>' + getDateContext(updatedAt) + '</a> ' +
                            '</td> ' +
                            '<td class="tabular-role-holder"> ' +
                                '<div class="position-relative"> ' +
                                    '<button class="btn btn-primary mb-2 me-1" type="button" data-bs-target="#edit-group-'+slug+'" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-pen-to-square"></i> ' +
                                    '</button> ' +
                                    editGroup(id, slug, groupName, groupDesc, updatedAt) +
                                    '<button class="btn btn-info mb-2 me-1" onclick="runManageFunc(' + "'" + slug + "'" + ')" type="button" data-bs-target="#manage-rel-'+slug+'" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-user-plus"></i> ' +
                                    '</button> ' +
                                    manageRel(id, slug, groupName) +
                                    '<button class="btn btn-danger mb-2 me-1" type="button" data-bs-target="#delete-group-'+slug+'" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-solid fa-trash"></i> ' +
                                    '</button> ' +
                                    deleteGroup(id, slug, groupName) +
                                '</div> ' +
                            '</td> ' +  
                        '</tr>';

                    $("#group-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    function runManageFunc(slug){
        load_group_detail(slug);
        load_available_user(1,slug);
    }

    function load_group_detail(slug) {        
        document.getElementById("manage-rel-holder-"+slug).innerHTML = "";

        $.ajax({
            url: "/api/v1/group/member/" + slug + "/20?page=1",
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load.group-rel').show();
            }
        })
        .done(function (response) {
            $('.auto-load.group-rel').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_new_req != last){
                $('#load_more_rel_holder').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_rel_holder').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $("#manage-rel-holder-"+slug).html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No users found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load.group-rel').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var id = data[i].id;
                    var username = data[i].username;
                    var fullName = data[i].full_name;
                    var grole = data[i].general_role;
                    var img = data[i].image_url;

                    var elmt = " " +
                        '<a class="btn card user-check action"> ' +
                            '<label title="Remove member"> ' +
                                '<input class="" name="username[]" id="check_remove_'+username+'" type="checkbox" onclick="removeMember('+"'"+slug+"'"+', '+"'"+username+"'"+', '+"'"+fullName+"'"+', this.checked, '+"'"+id+"'"+')" value="' + username + '"> ' +
                                '<div class="check-body"> ' +
                                    '<img class="img img-fluid user-image" src="'+getUserImage(img, grole)+'" alt="username-profile-pic.png"> ' +
                                    '<span class="user-check-title fw-normal">' + fullName + '</span>' +
                                    '<span class="fw-bold" style="font-size:13px;">' + grole + '</span>' +
                                '</div> ' +
                            '</label> ' +
                        '</a>';

                    $("#manage-rel-holder-"+slug).append(elmt);
                }   
                
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $("#err-rel-holder-"+slug).html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
            console.log('Server error occured');
        });
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

    function load_available_user(page_new_req,slug) {       
        var find = document.getElementById("title_search").value;
        document.getElementById("user-ava-holder-"+slug).innerHTML = "";

        $.ajax({
            url: "/api/v1/group/member/" + slug + "/" + getFindUser(find) + "/limit/100/order/first_name__DESC?page=" + page_new_req,
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
                $('#empty_item_holder_new_req').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
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
                                        '<input class="form-check-input" name="user_username[]" value="' + username + '" type="checkbox" style="width: 25px; height:25px;" id="check_new_'+ username +'" onclick="addNewMember('+"'"+slug+"'"+', '+"'"+username+"'"+', '+"'"+fullName+"'"+', this.checked)" '+ getChecked(username) +'> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</a>';

                    $("#user-ava-holder-"+slug).prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $("#err-ava-holder-"+slug).html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
            console.log('Server error occured');
        });
    }

    function addNewMember(slug, username, fullname, checked){
        var input_holder = document.getElementById("selected_member-"+slug);
        if(selectedMember.length == 0){
            selectedMember.push({
                full_name : fullname,
                username : username
            });
            input_holder.value = JSON.stringify(selectedMember);
        } else {
            if(checked === false){
                let indexToRemove = selectedMember.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedMember.splice(indexToRemove, 1);

                    // Make sure the item unchecked by remove from selected user list
                    document.getElementById("check_new_"+username).checked = false; 
                    input_holder.value = JSON.stringify(selectedMember);
                } else {
                    console.log('Item not found LOL');
                }
            } else {
                selectedMember.push({
                    full_name : fullname,
                    username : username
                });
                input_holder.value = JSON.stringify(selectedMember);
            }
        }

        refreshList(slug);
    }

    function removeMember(slug, username, fullname, checked, id){
        var input_holder = document.getElementById("selected_member_remove-"+slug);
        if(selectedMemberRemove.length == 0){
            selectedMemberRemove.push({
                full_name : fullname,
                username : username,
                id_rel : id
            });
            input_holder.value = JSON.stringify(selectedMemberRemove);
        } else {
            if(checked === false){
                let indexToRemove = selectedMemberRemove.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedMemberRemove.splice(indexToRemove, 1);
                    input_holder.value = JSON.stringify(selectedMemberRemove);
                } else {
                    console.log('Item not found LOL');
                }
            } else {
                selectedMemberRemove.push({
                    full_name : fullname,
                    username : username,
                    id_rel : id
                });
                input_holder.value = JSON.stringify(selectedMemberRemove);
            }
        }

        var submit_holder = document.getElementById('submit-rel-remove-btn-holder-'+slug);
        if(selectedMemberRemove.length > 0){
            submit_holder.innerHTML = '<button type="submit" class="btn btn-noline text-danger" style="float:right; margin-top:-35px;"><i class="fa-solid fa-xmark"></i> Remove Selected</button>';
        } else {
            submit_holder.innerHTML = '';
        }
    }

    function refreshList(slug){
        var holder = document.getElementById("user-selected-newmember-holder-"+slug);
        holder.innerHTML = "";

        selectedMember.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addNewMember('+"'"+slug+"'"+', '+"'"+e.username+"'"+', '+"'"+e.fullName+"'"+', false)" title="Remove this user"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.full_name + '</a>';
            holder.innerHTML += elmt;
        });

        var submit_holder = document.getElementById('submit-rel-add-btn-holder-'+slug);
        if(selectedMember.length > 0){
            submit_holder.innerHTML = '<button type="submit" class="btn btn-noline text-success" style="float:right; margin-top:-35px;"><i class="fa-solid fa-plus"></i> Assign All</button>';
        } else {
            submit_holder.innerHTML = '';
        }
    }

    function clearAllNewMember(slug){
        document.getElementById("user-selected-newmember-holder-"+slug).innerHTML = "";
        selectedMember.forEach((e) => {
            document.getElementById("check_new_"+e.username).checked = false; 
        });
        selectedMember = [];
    }

    function clearAllRemoveMember(){
        selectedMemberRemove.forEach((e) => {
            document.getElementById("check_remove_"+e.username).checked = false; 
        });
        selectedMember = [];
    }
</script>