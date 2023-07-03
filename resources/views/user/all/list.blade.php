<h5 class="section-title">All User</h5>
<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Username @include('user.all.sorting.username')</th>
                <th scope="col">Email @include('user.all.sorting.email')</th>
                <th scope="col">Full Name @include('user.all.sorting.fullname')</th>
                <th scope="col" style="min-width:140px;">Properties @include('user.all.sorting.joined')</th>
                <th scope="col" style="width:200px;">Role</th>
                <th scope="col">Detail</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="user-list-holder">
            <!-- Loading -->
            <div class="auto-load text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </tbody>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
        </div>
    </table>
    <div id="empty_item_holder"></div>
</div>
<h6 class="mt-1">Page</h6>
<div id="user_navigate"></div>

<script>
    var pageUser = 1;
    var lastPageUser = 0;
    var slct_list = [];
    var toogleManage = "";

    infinteLoadMoreUser(pageUser);

    function infinteLoadMoreUser(page) {  
        pageUser = page;
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

        var tagFind = <?php
            $tags = session()->get('selected_role_user');
            
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
    
        $.ajax({
            url: "/api/v1/user/" + getFind(name_filter, find) + "/limit/25/order/" + order + "/slug/" + tagFind + "?page=" + page,
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
            lastPageUser = response.data.last_page;

            // if(page_new_req != lastPageUser){
            //     $('#load_more_holder').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            // } else {
            //     $('#load_more_holder').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            // }

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
                    if(isMobile()){
                        var max = 4;
                    } else {
                        var max = 8;
                    }

                    if(tag){
                        var tags = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i < max){
                                if(i != tag.length - 1){
                                    tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>, ';
                                } else {
                                    tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>';
                                }
                            } else {
                                tags += '<span class="text-primary fw-bold">#...</span>';

                                return tags
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
                        return "<h6 class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> This user has no role</h6>" ;
                    }
                }

                function getNewUser(status){
                    if(status == 0){
                        return 1
                    } else {
                        return 0
                    }
                }

                function getImageStyleBasedDevice(){
                    if(isMobile()){
                        return 'width:60px; height:60px;';
                    } else {
                        return 'width:170px; height:170px;';
                    }
                }

                function manageRole(username, real_username){
                   
                    var elmt = 
                        '<div id="section-role-picker-'+username+'"> ' +
                            '<h6 class="text-secondary mt-2 mb-4"> Manage Role</h6> ' +   
                            '<div class="position-absolute" style="right:10px; top:0;"> ' +
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
                                    '<lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="empty_item_holder_manage_tag_'+username+'"></div> ' +
                            '<span id="load_more_holder_manage_tag_'+username+'" style="display: flex; justify-content:center;"></span> ' +
                        '</div> ' +
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
                            '<td>@' + username + ' <input hidden id="user_tag_' + unamepreg + '" value=' + "'" + JSON.stringify(role) + "'" + '></td> ' +
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
                                '<button class="btn btn-info" onclick="infinteLoadMoreTag(1, ' + "'" + unamepreg + "'" + '); loadUserRole(' + "'" + unamepreg + "'" + ', ' + "'" + username + "'" + ');" data-bs-toggle="modal" data-bs-target="#manageuser-' + unamepreg + '"><i class="fa-solid fa-gear"></i></button> ' +
                                '<div class="position-relative"> ' +
                                    '<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="manageuser-' + unamepreg + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                                        '<div class="modal-dialog modal-lg"> ' +
                                            '<div class="modal-content">  ' +
                                                '<div class="modal-body pt-4 p-0" style=height:90vh;""> ' +
                                                    '<button type="button" class="custom-close-modal" onclick="clean('+"'"+unamepreg+"'"+'); infinteLoadMoreUser('+"'"+pageUser+"'"+');" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                                    '<div class="px-3 position-relative"> ' +
                                                        '<h5>User Profile</h5> ' +
                                                        '<div class="row"> ' +
                                                            '<div class="col-3 p-3"> ' +
                                                                '<img class="img rounded-circle shadow" style="' + getImageStyleBasedDevice() + '" src="' + getUserImageGeneral(img, role) + '">' +
                                                            '</div> ' +
                                                            '<div class="col-9 p-0 py-2 ps-2 position-relative"> ' +
                                                                '<h5 class="text-secondary fw-normal">' + fullname + '</h5> ' +
                                                                '<h6 class="user-box-desc">@' + username + " | " + email + '</h6> ' +
                                                                '<h6 class="user-box-date mb-1">' + getJoinedAt(accDate, accStatus) + '</h6> ' +
                                                                '<div id="section-my-role-'+unamepreg+'"> ' +
                                                                    '<h6 class="text-secondary"> Role</h6> ' +
                                                                    '<div id="role-holder-'+unamepreg+'"></div> ' +
                                                                '</div> ' +
                                                            '</div> ' +
                                                        '</div> ' +
                                                        '<hr> ' +
                                                        '<div class="scroll-role" > ' +
                                                            '<div class="position-relative"> ' +
                                                                manageRole(unamepreg, username) +
                                                            '</div> ' +
                                                        '</div> ' +
                                                    '</div> ' +
                                                    '<div class="config-btn-group">' +
                                                        '<hr class="my-2"> ' +
                                                        // '<a class="btn btn-detail-config success" title="Send" data-bs-toggle="collapse" data-bs-target="#collapse-' + unamepreg + '"><i class="fa-solid fa-bell"></i></a>' +
                                                        '<a class="btn btn-detail-config primary" title="Send email" href="mailto:' + email + '"><i class="fa-solid fa-envelope"></i></a>' +
                                                        '<span style="position:absolute; right:10px; bottom:10px;"> ' +
                                                            '<a class="text-success" id="registered-msg_' + unamepreg + '"></a> ' + 
                                                            '<span id="btn-submit-tag-holder_' + unamepreg +'"></span> ' +
                                                        '</span> ' +
                                                    '</div> ' +
                                                '</div> ' +
                                                '<span class="position-absolute text-danger" style="bottom:20px; left: 75px;" id="msg-error-all"></span> ' +
                                            '</div> ' +

                                            // '<div class="collapse" id="collapse-' + unamepreg + '"> ' +
                                            //     '<div class="modal-content">  ' +
                                            //         '<div class="modal-body pt-4"> ' +
                                            //             '<button type="button" class="custom-close-modal" data-bs-toggle="collapse" data-bs-target="#collapse-' + unamepreg + '" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                            //             '<h5>Send Notification</h5> ' +
                                                        
                                            //         '</div> ' +
                                            //     '</div> ' +
                                            // '</div> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</td> ' +
                        '</tr> ';

                    $("#user-list-holder").prepend(elmt);
                }   
            }

            generatePageNav();
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load').hide();
            failResponse(jqXHR, ajaxOptions, thrownError, "#user-list-holder", false, null, null);
            lastPageUser = 1;
            generatePageUserNav();
        });
    }

    function loadUserRole(upreg, username) { 
        $.ajax({
            url: "/api/v1/user/" + username + "/role",
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            }
        })
        .done(function (response) {
            var data =  response.data;

            $("#role-holder-"+upreg).empty();
            
            if(data != null){
                for(var i = 0; i < data.length; i++){
                    var slug_name = data[i].slug_name;
                    var tag_name = data[i].tag_name;

                    var elmt = '<span class="btn btn-tag" title="Remove this role" id="tag_collection_remove_' + slug_name +'" ' +
                        'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+ tag_name +"'"+', true, '+"'"+upreg+"'"+', true)">' + tag_name + '</span>';

                    $("#role-holder-"+upreg).append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError, response) {
            $('.auto-load-tag').hide();
            $('#load_more_holder_manage_tag_'+upreg).empty();
            failResponse(jqXHR, ajaxOptions, thrownError, '#empty_item_holder_manage_tag_'+upreg, false, null, null);
        });
    }

    function generatePageNav(){
        $("#user_navigate").empty();
        for(var i = 1; i <= lastPageUser; i++){
            if(i == pageUser){
                var elmt = "<a class='page-holder active'>"+i+"</a>";
            } else {
                var elmt = "<a class='page-holder' onclick='infinteLoadMoreUser("+'"'+i+'"'+")'>"+i+"</a>";
            }
            $("#user_navigate").append(elmt);
        }
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
        var per_page = 24;

        if(role != null){
            roles = JSON.parse(role);
        }

        if(isMobile()){
            per_page = 12;
        } 

        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/"+per_page+ "?page=" + page_tag,
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
            var available = 0;
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
                        available++;
                        var elmt = '<a class="btn btn-tag" id="tag_collection_picker_' + d_slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ d_slug_name +"'"+', '+"'"+ d_tag_name +"'"+', true, '+"'"+username+"'"+',false)">' + d_tag_name + '</a> ';

                        $("#data_wrapper_manage_tag_"+username).append(elmt);
                    }
                }   

                if(available == 0){
                    var elmt = "<div class='err-msg-data d-block mx-auto text-center'><img src='http://127.0.0.1:8000/assets/nodata3.png' class='img' style='width:200px;'><h6 class='text-secondary text-center'>All role in this category have been assigned</h6></div>";
                    $("#data_wrapper_manage_tag_"+username).append(elmt);
                }
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError, response) {
            $('.auto-load-tag').hide();
            $('#load_more_holder_manage_tag_'+username).empty();
            failResponse(jqXHR, ajaxOptions, thrownError, '#empty_item_holder_manage_tag_'+username, false, null, null);
        });
    }

    function addSelectedTag(slug_name, tag_name, is_deleted, username, is_added){
        var found = false;

        //Remove selected tag from tag collection
        if(is_added){
            now = "remove";
            var bg = "bg-danger";
            var tag = document.getElementById('tag_collection_remove_'+slug_name);
        } else {
            now = "add";
            var bg = "bg-success";
            var tag = document.getElementById('tag_collection_picker_'+slug_name);
        }

        if(toogleManage == "" || toogleManage == now){
            toogleManage = now;
            tag.parentNode.removeChild(tag);

            if(toogleManage == "remove"){
                $("#section-my-role-"+username).css({
                    "border":"",
                    "border-radius":"",
                    "padding":""
                });
            } else if(toogleManage == "add"){
                $("#section-role-picker-"+username).css({
                    "border":"",
                    "border-radius":"",
                    "padding":""
                });
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
                    $("#slct_holder_"+username).append("<div class='d-inline' id='tagger_"+slug_name+"'><a class='btn btn-tag-selected "+bg+"' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+username+'"'+", "+is_added+")'>"+tag_name+"</a></div>");
                }
            } else {
                slct_list.push({
                    "slug_name": slug_name,
                    "tag_name": tag_name,
                });
                $("#slct_holder_"+username).append("<div class='d-inline' id='tagger_"+slug_name+"'><a class='btn btn-tag-selected "+bg+"' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+username+'"'+", "+is_added+")'>"+tag_name+"</a></div>");
            }
            document.getElementById("msg-error-all").innerHTML = "";

            document.getElementById("user_role_"+username).value = JSON.stringify(slct_list);
            getButtonSubmitTag(username, is_added)
        } else {
            if(toogleManage == "remove"){
                $("#section-my-role-"+username).css({
                    "border":"2px solid #D5534C",
                    "border-radius":"6px",
                    "padding":"6px"
                });
            } else if(toogleManage == "add"){
                $("#section-role-picker-"+username).css({
                    "border":"2px solid #D5534C",
                    "border-radius":"6px",
                    "padding":"6px"
                });
            }
            document.getElementById("msg-error-all").innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> You must select same tag type as previous select';
        }
    }

    function removeSelectedTag(slug_name, tag_name, username, is_added){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e['slug_name'] !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        if(is_added){
            $("#role-holder-"+username).append("<a class='btn btn-tag' id='tag_collection_remove_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+username+'"'+","+is_added+")'>"+tag_name+"</a>");
        } else {
            $("#data_wrapper_manage_tag_"+username).append("<a class='btn btn-tag' id='tag_collection_picker_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+username+'"'+","+is_added+")'>"+tag_name+"</a>");
        }
        
        document.getElementById("user_role_"+username).value = JSON.stringify(slct_list);
        getButtonSubmitTag(username,is_added)

        if(slct_list.length == 0){
            if(toogleManage == "remove"){
                $("#section-my-role-"+username).css({
                    "border":"",
                    "border-radius":"",
                    "padding":""
                });
            } else if(toogleManage == "add"){
                $("#section-role-picker-"+username).css({
                    "border":"",
                    "border-radius":"",
                    "padding":""
                });
            }
            toogleManage = "";
        }
    }

    function getButtonSubmitTag(username,is_added){
        if(is_added){
            var ctx = "<i class='fa-solid fa-trash'></i> Remove";
            var bg = "danger";
            var fun = 'onclick="remove_role(' + "'" + username + "'" + ')"';
        } else {
            var ctx = "<i class='fa-solid fa-plus'></i> Assign";
            var bg = "success";
            var fun = 'onclick="add_role(' + "'" + username + "'" + ')"';
        }

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
                        '<a class="btn btn-detail-config '+bg+' float-end" title="Submit Role" data-bs-toggle="collapse" href="#assignRoleValid_'+username+'">'+ctx+'</a> ' +
                    '</div> ' +
                    '<div class="collapse" id="assignRoleValid_'+username+'" data-bs-parent="#accordion_'+username+'"> ' +
                        '<a class="btn btn-detail-config '+bg+' float-end" '+fun+'><i class="fa-solid fa-paper-plane"></i> Send</a> ' +
                        '<a class="btn btn-detail-config danger float-end me-2" data-bs-toggle="collapse" href="#assignRoleInit_'+username+'"><i class="fa-solid fa-xmark"></i></a> ' +
                    '</div> ' +
                '</div> ') ;
        } else {
            return $("#btn-submit-tag-holder_"+username).text('');
        }
    }

    function clean(username){
        slct_list = [];
        toogleManage = "";
        $("#data_wrapper_manage_tag_"+username).html("");
        $("#slct_holder_"+username).html("");
    }

    function add_role(username){
        $("registered-msg_"+username).html("");
        isFormSubmitted = true;
        var upreg = username.replace(/[!:\\\[\/"`;.\'^£$%&*()}{@#~?><>,|=+¬\]]/, "");

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
                clean(upreg);
                getButtonSubmitTag(username);
                loadUserRole(upreg, username);
                infinteLoadMoreTag(page_tag, username);
                //document.getElementById("registered-msg_"+username).innerHTML = response.responseJSON.message;
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                console.log(response)
                // if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                   
                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    
                // } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                //     allMsg += response.responseJSON.errors.result[0]
                // } else {
                //     allMsg += errorMessage
                // }
                }
            }
        });
    }

    function remove_role(username){
        $("registered-msg_"+username).html("");
        isFormSubmitted = true;
        var upreg = username.replace(/[!:\\\[\/"`;.\'^£$%&*()}{@#~?><>,|=+¬\]]/, "");

        $.ajax({
            url: '/api/v1/user/update/role/remove',
            type: 'POST',
            data: $('#add_role_form_'+username).serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response) {
                clean(upreg);
                getButtonSubmitTag(username);
                loadUserRole(upreg, username);
                infinteLoadMoreTag(page_tag, username);
                //document.getElementById("registered-msg_"+username).innerHTML = response.responseJSON.message;
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                console.log(response)
                // if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                   
                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    
                // } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                //     allMsg += response.responseJSON.errors.result[0]
                // } else {
                //     allMsg += errorMessage
                // }
                }
            }
        });
    }
</script>