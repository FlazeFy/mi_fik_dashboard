<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Username @include('user.all.sorting.username')</th>
                <th scope="col">Email @include('user.all.sorting.email')</th>
                <th scope="col">Full Name @include('user.all.sorting.fullname')</th>
                <th scope="col">Properties @include('user.all.sorting.joined')</th>
                <th scope="col" style="width:200px;">Role</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="user-list-holder">
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
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
        </div>
    </table>
    <div id="empty_item_holder"></div>

</div>

<script>
    var page_new_req = 1;
    infinteLoadMoreUser(page_new_req);

    function loadmore(route){
        page_new_req++;
        infinteLoadMoreUser(page_new_req);
    }

    function infinteLoadMoreUser(page_new_req) {  
        function getFind(filter, find){
            let trim = find.trim();
            if(find == null || trim === ''){
                return filter;
            } else {
                document.getElementById("title_search").value = trim;
                return trim;
            }
        }

        var name_filter = '<?= session()->get('filtering_fname')."_".session()->get('filtering_lname'); ?>';
        var order = '<?= session()->get('ordering_user_list'); ?>';

        var find = document.getElementById("title_search").value;
        document.getElementById("user-list-holder").innerHTML = "";
    
        $.ajax({
            url: "/api/v1/user/" + getFind(name_filter, find) + "/limit/100/order/" + order + "?page=" + page_new_req,
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

            if (total == 0) {
                $('#empty_item_holder').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No users found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getItemBg(date, acc){
                    if(date && acc){
                        return "normal"
                    } else if(!acc && !date){
                        return "waiting"
                    } else if(!acc && date){
                        return "suspend"
                    }
                }

                function getRole(tag){
                    if(tag){
                        var tags = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i != tag.length - 1){
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>, ';
                            } else {
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>';
                            }
                        }
                        return tags
                    } else {
                        return '<span class="text-danger fw-bold">Doesn'+"'"+'t have a role'
                    }
                }

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

                        return "<span class='text-success fw-bold'>Joined since " + elmt + "</span>"
                    } else if(!acc && !datetime){
                        return "<span class='text-danger fw-bold'>Waiting for admin approved</span>";
                    } else if(!acc && datetime){
                        return "<span class='text-danger fw-bold'>Account suspended</span>";
                    }
                }

                function getRoleArea(role){
                    var elmnt = ""

                    if(role){
                        for(var i = 0; i < role.length; i++){
                            elmnt += "<a class='btn btn-tag'>"+role[i]['tag_name']+"</a>"
                        }
                        return elmnt

                    } else {
                        return "<h6 class='text-danger'>This user has no role</h6>" ;
                    }
                }

                function getNewUser(status){
                    if(status == 0){
                        return 1
                    } else {
                        return 0
                    }
                }

                function manageRole(username, real_username){
                   
                    var elmt = 
                        '<h6 class="text-secondary mt-2 mb-4"> Manage Role</h6> ' +   
                        '<div class="position-absolute" style="right:0; top:0;"> ' +
                            '<select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value, ' + "'" + username + "'" + ')" name="tag_category" aria-label="Floating label select example" required> ' +
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
                        '<div class="tag-manage-holder" id="data_wrapper_manage_tag_'+username+'"> ' +
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
                        '<div id="empty_item_holder_manage_tag_'+username+'"></div> ' +
                        '<span id="load_more_holder_manage_tag_'+username+'" style="display: flex; justify-content:center;"></span> ' +
                        '<h6 class="text-secondary mt-3"> Selected Role</h6> ' +
                        '<form id="add_role_form_'+username+'"> ' +
                            '@csrf ' +
                            '<input hidden name="username" value="' + real_username + '"> ' +
                            '<input hidden name="user_role" id="user_role_' + username + '" value=""> ' +
                            '<div id="slct_holder_'+username+'"></div> ' + 
                        '</form> ';

                    return elmt;
                }
                $("#empty_item_holder").empty();

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var username = data[i].username;
                    var fullname = data[i].full_name;
                    var createdAt = data[i].created_at;
                    var updatedAt = data[i].updated_at;
                    var email = data[i].email;
                    var accStatus = data[i].is_accepted;
                    var accDate = data[i].accepted_at;
                    var img = data[i].image_url;
                    var role = data[i].role;
                    var unamepreg = username.replace(/[!:\\\[\/"`;.\'^£$%&*()}{@#~?><>,|=+¬\]]/, "");

                    var elmt = " " +
                        '<tr class="tabular-item ' + getItemBg(accDate, accStatus) + '"> ' +
                            '<th scope="row"> <img class="img img-fluid user-image" style="margin-top:45%;" src="' + getUserImageGeneral(img, role) + '"> </th> ' +
                            '<td>' + username + ' <input hidden id="user_tag_' + unamepreg + '" value=' + "'" + JSON.stringify(role) + "'" + '></td> ' +
                            '<td class="email" title="Send Email" onclick="window.location = '+"'"+'mailto:'+email+"'"+'" href="mailto:' + email + '">' + email + '</td> ' +
                            '<td style="width: 180px;">' + fullname + '</td> ' +
                            '<td class="properties"> ' +
                                '<h6>Joined At</h6> ' +
                                '<a>' + getDateToContext(createdAt, "datetime") + '</a> ' +
                                '<h6>Updated At</h6> ' +
                                '<a>' + getDateToContext(updatedAt, "datetime") + '</a> ' +
                            '</td> ' +
                            '<td class="tabular-role-holder"> ' +
                                getRole(role) +
                            '</td> ' +
                            '<td>'  +
                                '<button class="btn btn-info" onclick="infinteLoadMoreTag(1, ' + "'" + unamepreg + "'" + ')" data-bs-toggle="modal" data-bs-target="#manageuser-' + unamepreg + '"><i class="fa-solid fa-gear"></i></button> ' +
                                '<div class="position-relative"> ' +
                                    '<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="manageuser-' + unamepreg + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                                        '<div class="modal-dialog modal-lg"> ' +
                                            '<div class="modal-content">  ' +
                                                '<div class="modal-body pt-4" style="height:75vh;"> ' +
                                                    '<button type="button" class="custom-close-modal" onclick="clean('+"'"+unamepreg+"'"+')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                                    '<h5>User Profile</h5> ' +
                                                    '<div class=""> ' +
                                                        '<div class="row"> ' +
                                                            '<div class="col-3 p-3"> ' +
                                                                '<img class="img img-fluid rounded-circle shadow" src="' + getUserImageGeneral(img, role) + '">' +
                                                            '</div> ' +
                                                            '<div class="col-9 p-0 py-2 ps-2 position-relative"> ' +
                                                                '<h5 class="text-secondary fw-normal">' + fullname + '</h5> ' +
                                                                '<h6 class="user-box-desc">@' + username + " | " + email + '</h6> ' +
                                                                '<h6 class="user-box-date" style="font-size:14px;">' + getJoinedAt(accDate, accStatus) + '</h6> ' +
                                                                '<h6 class="text-secondary"> Role</h6> ' +
                                                                '<div> ' +
                                                                    getRoleArea(role) +
                                                                '</div> ' +
                                                            '</div> ' +
                                                        '</div> ' +
                                                        '<hr> ' +
                                                        '<div class="scroll-role" style="max-height:37.5vh;"> ' +
                                                            '<div class="position-relative"> ' +
                                                                manageRole(unamepreg, username) +
                                                            '</div> ' +
                                                        '</div> ' +
                                                        '<div class="config-btn-group">' +
                                                            '<hr> ' +
                                                            '<a class="btn btn-detail-config success" title="Send" data-bs-toggle="collapse" data-bs-target="#collapse-' + unamepreg + '"><i class="fa-solid fa-bell"></i></a>' +
                                                            '<a class="btn btn-detail-config primary" title="Send email" href="mailto:' + email + '"><i class="fa-solid fa-envelope"></i></a>' +
                                                            '<span style="position:absolute; right:-10px; bottom:0;"> ' +
                                                                '<a class="text-success" id="registered-msg_' + unamepreg + '"></a> ' + 
                                                                '<span id="btn-submit-tag-holder_' + unamepreg +'"></span> ' +
                                                            '</span> ' +
                                                        '</div> ' +
                                                    '</div> ' +
                                                '</div> ' +
                                            '</div> ' +

                                            '<div class="collapse" id="collapse-' + unamepreg + '"> ' +
                                                '<div class="modal-content">  ' +
                                                    '<div class="modal-body pt-4"> ' +
                                                        '<button type="button" class="custom-close-modal" data-bs-toggle="collapse" data-bs-target="#collapse-' + unamepreg + '" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                                        '<h5>Send Notification</h5> ' +
                                                        
                                                    '</div> ' +
                                                '</div> ' +
                                            '</div> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</td> ' +
                        '</tr> ';

                    $("#user-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#empty_item_holder").html("<div class='err-msg-data d-block mx-auto' style='margin-top:-30% !important;'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
        });
    }

    var page_tag = 1;
    function loadmoretag(username){
        page_tag++;
        infinteLoadMoreTag(page_tag, username);
    }

    var tag_cat = "<?= $dct_tag[0]['slug_name'] ?>";

    function setTagFilter(tag, username){
        tag_cat = tag;
        page_tag = 1;
        infinteLoadMoreTag(page_tag, username);
        $("#data_wrapper_manage_tag_"+username).empty();
    }

    function infinteLoadMoreTag(page_tag, username) { 
        var role = document.getElementById("user_tag_" + username).value;
        var roles = null;

        if(role != null){
            roles = JSON.parse(role);
        }

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
                $('#load_more_holder_manage_tag_'+username).html('<a class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadmoretag('+"'"+username+"'"+')">Show more <span id="textno"></span></a>');
            } else {
                $('#load_more_holder_manage_tag_'+username).html('<h6 class="text-secondary my-3">No more tag to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_manage_tag_'+username).html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-tag').html("<h5 class='text-secondary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                $("#empty_item_holder_manage_tag_"+username).empty();
                
                for(var i = 0; i < data.length; i++){
                    var d_slug_name = data[i].slug_name;
                    var d_tag_name = data[i].tag_name;
                    var found = false;

                    if(roles){
                        for(y = 0; y < roles.length; y++){
                            var r_slug_name = roles[y].slug_name;

                            if(d_slug_name == r_slug_name){
                                found = true;
                            }
                        }
                    }

                    if(found != true){
                        var elmt = '<a class="btn btn-tag" id="tag_collection_' + d_slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ d_slug_name +"'"+', '+"'"+ d_tag_name +"'"+', true, '+"'"+username+"'"+')">' + d_tag_name + '</a> ';

                        $("#data_wrapper_manage_tag_"+username).append(elmt);
                    }
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError, response) {
            if (jqXHR.status == 404) {
                $('.auto-load-tag').hide();
                $('#load_more_holder_manage_tag_'+username).empty();
                $('#empty_item_holder_manage_tag_'+username).html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:200px;'><h6 class='text-secondary text-center'>" + jqXHR.responseJSON.message + "</h6></div>");
            } else {
                // handle other errors
            }
        });
    }

    var slct_list = [];

    function addSelectedTag(slug_name, tag_name, is_deleted, username){
        var found = false;

        //Remove selected tag from tag collection
        if(is_deleted){
            var tag = document.getElementById('tag_collection_'+slug_name);
            tag.parentNode.removeChild(tag);
        }

        if(slct_list.length > 0){
            //Check if tag is exist in selected tag.
            slct_list.map((val, index) => {
                if(val['slug_name'] == slug_name){
                    found = true;
                }
            });

            if(found == false){
                slct_list.push({
                    "slug_name": slug_name,
                    "tag_name": tag_name,
                });
                //Check this append input value again!
                $("#slct_holder_"+username).append("<div class='d-inline' id='tagger_"+slug_name+"'><a class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+username+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_list.push({
                "slug_name": slug_name,
                "tag_name": tag_name,
            });
            $("#slct_holder_"+username).append("<div class='d-inline' id='tagger_"+slug_name+"'><a class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+username+'"'+")'>"+tag_name+"</a></div>");
        }

        document.getElementById("user_role_"+username).value = JSON.stringify(slct_list);
        getButtonSubmitTag(username)
    }

    function removeSelectedTag(slug_name, tag_name, username){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e['slug_name'] !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#data_wrapper_manage_tag_"+username).append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+username+'"'+")'>"+tag_name+"</a>");
        
        document.getElementById("user_role_"+username).value = JSON.stringify(slct_list);
        getButtonSubmitTag(username)
    }

    function getButtonSubmitTag(username){
        if(slct_list.length > 0){
            var tags = "";

            for(var i = 0; i < slct_list.length; i++){
                if(i != slct_list.length - 1){
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>, ';
                } else {
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>';
                }
            }
            
            $("#btn-submit-tag-holder_"+username).html(''+
                '<div class="accordion" id="accordion_'+username+'"> ' +
                    '<div class="collapse show" id="assignRoleInit_'+username+'" data-bs-parent="#accordion_'+username+'"> ' +
                        '<a class="btn btn-detail-config success float-end" title="Submit Role" data-bs-toggle="collapse" href="#assignRoleValid_'+username+'"><i class="fa-solid fa-plus"></i> Assign</a> ' +
                    '</div> ' +
                    '<div class="collapse" id="assignRoleValid_'+username+'" data-bs-parent="#accordion_'+username+'"> ' +
                        '<a class="btn btn-detail-config success float-end" onclick="add_role(' + "'" + username + "'" + ')"><i class="fa-solid fa-paper-plane"></i> Send</a> ' +
                        '<a class="btn btn-detail-config danger float-end me-2" data-bs-toggle="collapse" href="#assignRoleInit_'+username+'"><i class="fa-solid fa-xmark"></i></a> ' +
                    '</div> ' +
                '</div> ') ;
        } else {
            return $("#btn-submit-tag-holder_"+username).text('')
        }
    }

    function clean(username){
        slct_list = [];
        $("#data_wrapper_manage_tag_"+username).empty();
        $("#slct_holder_"+username).empty();
    }

    function add_role(username){
        $("registered-msg_"+username).html("");

        $.ajax({
            url: '/api/v1/user/update/role/add',
            type: 'POST',
            data: $('#add_role_form_'+username).serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response) {
                $("#slct_holder_"+username).html("");
                slct_list = [];
                getButtonSubmitTag(username);
                document.getElementById("registered-msg_"+username).innerHTML = response.responseJSON.message;
            },
            error: function(response, jqXHR, textStatus, errorThrown) {

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                   
                    
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg += response.responseJSON.errors.result[0]
                } else {
                    allMsg += errorMessage
                }
            }
        });
    }
</script>