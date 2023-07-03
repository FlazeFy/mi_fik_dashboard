<style>
    
</style>

<div class="detail-box">
    <form action="/user/request/manage_role_acc" method="POST">
        @csrf
        <h5 class="section-title"><span class="text-primary" id="detail_body"></span> Detail</h5>
        <div class="user-req-holder" id="data_wrapper_user_detail">
            <!-- Loading -->
            <div class="auto-load text-center">
                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
            </div>
            <span id="load_more_holder_user_detail" style="display: flex; justify-content:center;"></span>
        </div>
        <div id="empty_item_holder_user_detail"></div>
    </form>

    <span id="acc-user-holder"></span>
    <span id="suspend-user-holder"></span>
    <span id="recover-user-holder"></span>

</div>

<script>
    var page_tag = 1;
    load_user_detail("");

    function load_user_detail(username_search, type, id) {      
        $("#empty_item_holder_user_detail").html("");

        $.ajax({
            url: "/api/v1/user/" + username_search,
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
            var data =  response.data;

            if (data.length == 0) {
                $('#empty_item_holder_user_detail').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No detail's found</h6>");
                return;
            } else {
                function getJoinedAt(datetime, acc){
                    if(datetime && acc){
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

                        return "<span class='text-success fw-bold'>Joined since " + elmt + "</span>";
                    } else if(!acc && !datetime){
                        return "<span class='text-danger fw-bold'>Waiting for admin approved</span>";
                    } else if(!acc && datetime){
                        return "<span class='text-danger fw-bold'>Account suspended</span>";
                    }
                }

                function getRoleArea(role){
                    var elmnt = "";

                    if(role){
                        for(var i = 0; i < role.length; i++){
                            elmnt += "<a class='btn btn-tag'>"+role[i]['tag_name']+"</a>"
                        }
                        return elmnt

                    } else {
                        return "<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-role'> " +
                            "<h6 class='text-center'>This user has no tag</h6>" ;
                    }
                }

                function getLifeButton(acc, acc_date, type, id, username, fullname){
                    if(type == "new"){
                        if(!acc && !acc_date){
                            return '<a class="btn btn-detail-config success" title="Approve Account" data-bs-toggle="modal" href="#acc_user"><i class="fa-solid fa-check"></i></a>';
                        } else if(!acc && acc_date){
                            return '<a class="btn btn-detail-config success" title="Recover Account" data-bs-toggle="modal" href="#recover_user"><i class="fa-solid fa-rotate-right"></i></a>';
                        } else if(acc && acc_date){
                            return '<a class="btn btn-detail-config danger" title="Suspend Account" data-bs-toggle="modal" href="#suspend_user"><i class="fa-solid fa-power-off"></i></a>';
                        }
                    } else if(type == "old"){
                        var req_type = document.getElementById("type_holder_" + username + id).value;
                        
                        if(req_type == "add"){
                            return '<a class="btn btn-detail-config success" onclick="cleanReq(); addSelected('+"'"+id+"'"+','+"'"+username+"'"+','+"'"+req_type+"'"+', '+"'"+fullname+"'"+', true)" title="Accept Request" data-bs-toggle="modal" data-bs-target="#accOldReqModal"><i class="fa-solid fa-check"></i></a>';
                        } else if(req_type == "remove"){
                            return '<a class="btn btn-detail-config danger" onclick="cleanReq(); addSelected('+"'"+id+"'"+','+"'"+username+"'"+','+"'"+req_type+"'"+', '+"'"+fullname+"'"+', true)" title="Reject Request" data-bs-toggle="modal" data-bs-target="#rejOldReqModal"><i class="fa-solid fa-xmark"></i></a>';
                        }
                    }
                }

                function getNewUser(status){
                    if(status == 0){
                        return 1;
                    } else {
                        return 0;
                    }
                }

                function getAccUser(slug, fullname){  
                    isFormSubmitted = true;
                    $("#acc-user-holder").html('<div class="modal fade" id="acc_user" tabindex="-1" aria-labelledby="accLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"> ' +
                        '<div class="modal-dialog"> ' +
                            '<div class="modal-content"> ' +
                            '<form action="/user/request/manage_acc" method="POST"> ' +
                                '@csrf ' +
                                '<input hidden name="username" value="'+slug+'"> ' +
                                '<div class="modal-header"> ' +
                                    '<h5 class="modal-title" id="accLabel">Accept New User</h5> ' +
                                    '<a type="button" class="btn-close" data-bs-dismiss="modal" onclick="isFormSubmitted = false;" aria-label="Close"></a> ' +
                                '</div> ' +
                                '<div class="modal-body"> ' +
                                    '<h6 class="fw-normal">Are you sure want to give access to <span class="text-primary fw-bold">' + fullname + '</span></h6> ' +
                                '</div> ' +
                                '<div class="modal-footer"> ' +
                                    '<button type="submit" class="btn btn-success">Accept</button> ' +
                                '</div> ' +
                                '</div> ' +
                            '</form> ' +
                        '</div> ' +
                    '</div>');
                }

                function getSuspendUser(slug, fullname){    
                    isFormSubmitted = true;
                    $("#suspend-user-holder").html('<div class="modal fade" id="suspend_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="susLabel" aria-hidden="true"> ' +
                        '<div class="modal-dialog"> ' +
                            '<div class="modal-content"> ' +
                            '<form action="/user/request/manage_suspend" method="POST"> ' +
                                '@csrf ' +
                                '<input hidden name="username" value="'+slug+'"> ' +
                                '<div class="modal-header"> ' +
                                    '<h5 class="modal-title" id="susLabel">Suspend User</h5> ' +
                                    '<a type="button" class="btn-close" data-bs-dismiss="modal" onclick="isFormSubmitted = false;" aria-label="Close"></a> ' +
                                '</div> ' +
                                '<div class="modal-body"> ' +
                                    '<h6 class="fw-normal">Are you sure want to suspend <span class="text-danger fw-bold">' + fullname + '</span> account</h6> ' +
                                '</div> ' +
                                '<div class="modal-footer"> ' +
                                    '<button type="submit" class="btn btn-danger">Suspend</button> ' +
                                '</div> ' +
                                '</div> ' +
                            '</form> ' +
                        '</div> ' +
                    '</div>');
                }

                function getRecoverUser(slug, fullname){    
                    isFormSubmitted = true;
                    $("#recover-user-holder").html('<div class="modal fade" id="recover_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="recLabel" aria-hidden="true"> ' +
                        '<div class="modal-dialog"> ' +
                            '<div class="modal-content"> ' +
                            '<form action="/user/request/manage_recover" method="POST"> ' +
                                '@csrf ' +
                                '<input hidden name="username" value="'+slug+'"> ' +
                                '<div class="modal-header"> ' +
                                    '<h5 class="modal-title" id="recLabel">Recover User</h5> ' +
                                    '<a type="button" class="btn-close" data-bs-dismiss="modal" onclick="isFormSubmitted = false;" aria-label="Close"></a> ' +
                                '</div> ' +
                                '<div class="modal-body"> ' +
                                    '<h6 class="fw-normal">Are you sure want to recover <span class="text-primary fw-bold">' + fullname + '</span> account</h6> ' +
                                '</div> ' +
                                '<div class="modal-footer"> ' +
                                    '<button type="submit" class="btn btn-submit">Recover</button> ' +
                                '</div> ' +
                                '</div> ' +
                            '</form> ' +
                        '</div> ' +
                    '</div>');
                }

                function manageRole(type, username, id){
                    if(type == "new"){
                        var elmt = 
                        '<h6 class="text-secondary my-4"> Manage Role</h6> ' +   
                        '<div class="position-absolute" style="right:0; top:10px;"> ' +
                            '<select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value)" name="tag_category" aria-label="Floating label select example" required> ' +
                                '@php($i = 0) ' +
                                '@foreach($dct_tag as $dtag) ' +
                                    '@if($i == 0) ' +
                                        '<option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option> ' +
                                        '<option value="all">All</option> ' +
                                    '@else ' +
                                        '<option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option> ' +
                                    '@endif ' +
                                    '@php($i++) ' +
                                '@endforeach ' +
                            '</select> ' +
                        '</div> ' + 
                        '<div class="tag-manage-holder" id="data_wrapper_manage_tag"> ' +
                            '<div class="auto-load-tag text-center"> ' +
                                '<svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' +
                                    'x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve"> ' +
                                    '<path fill="#000" ' +
                                        'd="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"> ' +
                                        '<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" ' +
                                            'from="0 50 50" to="360 50 50" repeatCount="indefinite" /> ' +
                                    '</path> ' +
                                '</svg> ' +
                            '</div> ' +
                        '</div> ' +
                        '<div id="empty_item_holder_manage_tag"></div> ' +
                        '<span id="load_more_holder_manage_tag" style="display: flex; justify-content:center;"></span> ' +
                        '<h6 class="text-secondary mt-3"> Selected Role</h6> ' +
                        '<div id="slct_holder"></div> ';
                    } else if(type == "old"){
                        var passing_tag = document.getElementById("tag_holder_" + username + id).value;
                        var list_request = JSON.parse(passing_tag);
                        var req_hold = "";

                        list_request.forEach(e => {
                            req_hold += '<a class="btn btn-tag" id="tag_collection_' + e.slug_name +'" title="Select this tag" ' + 
                                'onclick="addSelectedTag('+"'"+ e.slug_name +"'"+', '+"'"+e.tag_name+"'"+', true)">' + e.tag_name + '</a> ';
                        });

                        var elmt = '<h6 class="text-secondary mt-3"> Requested Role</h6> ' +
                            '<div class="tag-manage-holder" id="manage-request-tag">'+req_hold+'</div> ' +
                            '<h6 class="text-secondary mt-3"> Request Revision</h6> ' +
                            '<div id="slct_holder"></div> ';
                    }

                    return elmt;
                }

                for(var i = 0; i < data.length; i++){
                    $("#data_wrapper_user_detail").empty();

                    //Attribute
                    var username = data[i].username;
                    var full_name = data[i].full_name;
                    var email = data[i].email;
                    var username = data[i].username;
                    var role = data[i].role;
                    var img = data[i].image_url;
                    var created_at = data[i].created_at;
                    var accepted_at = data[i].accepted_at;
                    var is_accepted = data[i].is_accepted;

                    var elmt = " " +
                        '<input hidden name="username" value="' + username + '"> ' +
                        '<input hidden name="is_new" value="' + getNewUser(is_accepted) + '"> ' +
                        '<div class=""> ' +
                            '<div class="row"> ' +
                                '<div class="col-2 p-0 py-3 ps-2"> ' +
                                    '<img class="img img-fluid user-image" src="' + getUserImageGeneral(img, role) + '">' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + full_name + '</h6> ' +
                                    '<h6 class="user-box-desc">' + username + " | " + email + '</h6> ' +
                                    '<h6 class="user-box-date">' + getJoinedAt(accepted_at, is_accepted) + '</h6> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div class="scroll-role"> ' +
                                '<h6 class="text-secondary"> Role</h6> ' +
                                '<div> ' +
                                    getRoleArea(role) +
                                '</div> ' +
                                '<div class="position-relative"> ' +
                                    manageRole(type,username, id) +
                                '</div> ' +
                            '</div> ' +
                            '<div class="config-btn-group">' +
                                '<hr> ' +
                                '<a class="btn btn-detail-config primary" title="Manage role" onclick="infinteLoadMoreTag()"><i class="fa-solid fa-hashtag"></i></a>' +
                                '<a class="btn btn-detail-config primary" title="Send email" href="mailto:' + email + '"><i class="fa-solid fa-envelope"></i></a>' +
                                getLifeButton(is_accepted, accepted_at, type, id, username, full_name) +
                                '<span id="btn-submit-tag-holder"></span> ' +
                            '</div> ' +
                        '</div>';

                        if(!is_accepted && !accepted_at){
                            getAccUser(username, full_name);
                        } else if(is_accepted && accepted_at){ 
                            getSuspendUser(username, full_name);
                        } else if(!is_accepted && accepted_at){ 
                            getRecoverUser(username, full_name);
                        }

                    $("#data_wrapper_user_detail").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            failResponse(jqXHR, ajaxOptions, thrownError, "#data_wrapper_user_detail", false, "You haven't selected any user", "http://127.0.0.1:8000/assets/nodata3.png");
        });
    }

    function cleanReq(){
        selectedOldUser = [];
    }

    // infinteLoadMoreTag(page_tag);

    function loadmoretag(route){
        page_tag++;
        infinteLoadMoreTag(page_tag);
    }

    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_list = []; //Store all tag's id.
    var tag_cat = "<?= $dct_tag[0]['slug_name'] ?>";

    function setTagFilter(tag){
        tag_cat = tag;
        page_tag = 1;
        infinteLoadMoreTag(page_tag);
        $("#data_wrapper_manage_tag").empty();
    }

    function infinteLoadMoreTag(page_tag) {         
        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/12"+ "?page=" + page_tag,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-tag').show();
            }
        })
        .done(function (response) {
            $('.auto-load-tag').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_tag != last){
                $('#load_more_holder_manage_tag').html('<a class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadmoretag()">Show more <span id="textno"></span></a>');
            } else {
                $('#load_more_holder_manage_tag').html('<h6 class="text-secondary my-3">No more tag to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_manage_tag').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-tag').html("<h5 class='text-secondary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                $("#empty_item_holder_manage_tag").empty();
                
                for(var i = 0; i < data.length; i++){

                    //Attribute
                    var slug_name = data[i].slug_name;
                    var tag_name = data[i].tag_name;

                    var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                        'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'slct'+"'"+')">' + tag_name + '</a> ';

                    $("#data_wrapper_manage_tag").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError, response) {
            $('#load_more_holder_manage_tag').empty();
            failResponse(jqXHR, ajaxOptions, thrownError, "#data_wrapper_manage_tag", false, null, null);
        });
    }

    function addSelectedTag(slug_name, tag_name, is_deleted){
        var found = false;

        //Remove selected tag from tag collection
        if(is_deleted){
            var tag = document.getElementById('tag_collection_'+slug_name);
            tag.parentNode.removeChild(tag);
        }

        if(slct_list.length > 0){
            //Check if tag is exist in selected tag.
            slct_list.map((val, index) => {
                if(val == slug_name){
                    found = true;
                }
            });

            if(found == false){
                slct_list.push(slug_name);
                //Check this append input value again!
                $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_list.push(slug_name);
            $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
        }

        getButtonSubmitTag()
    }

    function removeSelectedTag(slug_name, tag_name){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#data_wrapper_manage_tag").append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+"slct"+'"'+")'>"+tag_name+"</a>");

        getButtonSubmitTag()
    }

    function getButtonSubmitTag(){
        if(slct_list.length > 0){
            var tags = "";

            for(var i = 0; i < slct_list.length; i++){
                if(i != slct_list.length - 1){
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>, ';
                } else {
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>';
                }
            }
            
            $("#btn-submit-tag-holder").html(''+
                '<a class="btn btn-detail-config success float-end" title="Submit Role"  data-bs-toggle="modal" href="#assignRoleAcc"><i class="fa-solid fa-plus"></i> Assign</a> ' +
                '<div class="modal fade" id="assignRoleAcc" tabindex="-1" aria-labelledby="assignRoleAccLabel" aria-hidden="true"> ' +
                '<div class="modal-dialog"> ' +
                    '<div class="modal-content"> ' +
                    '<div class="modal-header"> ' +
                        '<h5 class="modal-title" id="assignRoleAccLabel">Assign Selected Tags</h5> ' +
                        '<a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a> ' +
                    '</div> ' +
                    '<div class="modal-body"> ' +
                        '<h6 class="fw-normal">Are you sure want to assign ' + tags + ' to this User</h6> ' +
                    '</div> ' +
                    '<div class="modal-footer"> ' +
                        '<button type="submit" class="btn btn-success">Submit</button> ' +
                    '</div> ' +
                    '</div> ' +
                '</div> ' +
                '</div>') ;
        } else {
            return $("#btn-submit-tag-holder").text('')
        }
    }
</script>