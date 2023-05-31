<style>
    .btn-quick-action-notif{
        border-radius:6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:20vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        /* background-size: cover; */
        transition: 0.5s;
        text-align:left;
        padding:10px;
        background-size: contain;
    }
    .btn-quick-action-notif:hover{
        /* background: #F78A00 !important; */
        background-image:linear-gradient(to bottom right,#F78A00 20%, 70%, #5b5b5b) !important;
    }
    .quick-action-text-notif{
        font-size:24px;
        color:#FFFFFF;
        transition: 0.5s;
        margin-top:13vh;
    }
    .quick-action-info-notif{
        font-size:14px;
        color:#FFFFFF;
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action-notif:hover .quick-action-text-notif{
        margin-top:-4vh;
    }
    .btn-quick-action-notif:hover .quick-action-info-notif{
        display:block;
    }

    #user-list-holder, #group-list-holder{
        padding: 5px 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 65vh;
        overflow-y: scroll;
    }
</style>

<script>
    let validation = [
        { id: "notif_body", req: true, len: 255 },
        { id: "notif_title", req: true, len: 35 }
    ];
</script>

<div class="modal fade" id="selectTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Select Your Notif / Announcement Type</h5>
                
                <div class="row px-2">
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('All User')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/global.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="All User" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">All User</h5>
                            <p class="quick-action-info-notif">Send announcement to all user who is registered and accepted in Mi-FIK App</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Role')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/tag.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="By Role" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">By Role</h5>
                            <p class="quick-action-info-notif">Send announcement to specific role that containe some user</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Grouping')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/group.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="By Grouping" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">By Grouping</h5>
                            <p class="quick-action-info-notif">Send announcement to specific group that containe some user</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Person')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/person.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="By Person" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">By Person</h5>
                            <p class="quick-action-info-notif">Send announcement to one or some user with searching one by one</p>
                        </button>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Pending')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/pending.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="Pending" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">Or, Pending</h5>
                            <p class="quick-action-info-notif">Make announcement and send it later with specific time and you can choose the type later</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <form action="/system/notification/add" method="POST">
                    @csrf
                    <h5>Add <span id="type-title"></span> Notif</h5>
                    
                    <span id="section-holder"></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var selectedUser = []; 
    var selectedGroup = []; 

    function setType(type){
        document.getElementById("type-title").innerHTML = type;
        setFormSection(type);

        if(type == "Grouping"){
            infinteLoadGroup(1);
        } else if(type == "Person"){
            infinteLoadUser(1);
        } else if(type == "Role"){
            infinteLoadRole(1);
        }
    }

    function setFormSection(type){
        var sec = document.getElementById("section-holder");
        if(type == "All User"){
            var elmt = " " +
                '<div class="px-2"> ' +
                    '<input name="send_to" value="all" hidden> ' +
                    '<div class="form-floating mb-2"> ' +
                        '<input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35"> ' +
                        '<label for="notif_title">Title</label> ' +
                        '<a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<div class="form-floating mb-2"> ' +
                        '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                        '<label for="notif_body">Body</label> ' +
                        '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<div class="row mb-2"> ' +
                        '<div class="col-lg-6"> ' +
                            '<div class="form-floating"> ' +
                                '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                                    '@php($i = 0) ' +
                                    '@foreach($dictionary as $dct) ' +
                                        '@if($dct->type_name == "Notification") ' +
                                            '@if($i == 0) ' +
                                                '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                            '@else  ' +
                                                '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                            '@endif ' +
                                            '@php($i++) ' +
                                        '@endif ' +
                                    '@endforeach ' +
                                '</select> ' +
                                '<label for="notif_type">Type</label> ' +
                                '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                            '</div> ' +
                        '</div> ' +
                        '<div class="col-lg-6"> ' +
                            '<div class="form-floating"> ' +
                                '<select class="form-select" id="send_time" name="send_time" aria-label="Floating label select example" onchange="toogleTimePicker()" required> ' +
                                    '<option value="now" selected>Now</option> ' +
                                    '<option value="manual">Manual</option> ' +
                                '</select> ' +
                                '<label for="send_time">Send Time</label> ' +
                            '</div> ' +
                        '</div> ' +
                        '<div id="datetime-picker-box"></div> ' +
                    '</div> ' +
                    '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                '</div> ';
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog');
        } else if(type == "Grouping"){
            var elmt = " " +
                '<div class="row px-2"> ' +
                    '<input name="send_to" value="grouping" hidden> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35"> ' +
                            '<label for="notif_title">Title</label> ' +
                            '<a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                            '<label for="notif_body">Body</label> ' +
                            '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="row mb-2"> ' +
                            '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                                        '@php($i = 0) ' +
                                        '@foreach($dictionary as $dct) ' +
                                            '@if($dct->type_name == "Notification") ' +
                                                '@if($i == 0) ' +
                                                    '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                                '@else  ' +
                                                    '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                                '@endif ' +
                                                '@php($i++) ' +
                                            '@endif ' +
                                        '@endforeach ' +
                                    '</select> ' +
                                    '<label for="notif_type">Type</label> ' +
                                    '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="send_time" name="send_time" aria-label="Floating label select example" onchange="toogleTimePicker()" required> ' +
                                        '<option value="now" selected>Now</option> ' +
                                        '<option value="manual">Manual</option> ' +
                                    '</select> ' +
                                    '<label for="send_time">Send Time</label> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="datetime-picker-box"></div> ' +
                        '</div> ' +
                        '<hr> ' +
                        '<span class="position-relative"> ' + 
                            '<h6>Selected Group</h6> ' +
                            '<a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllGroup()"><i class="fa-regular fa-trash-can"></i> Clear All</a> ' +
                        '</span> ' +
                        '<div id="slct-group-list-holder"></div> ' +
                        '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                    '</div> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<input name="list_context" id="list_context_group"  value="" hidden> ' +
                        '<h6>All Group</h6> ' +
                        '<span id="group-list-holder"></span> ' +
                    '</div> ' +
                '</div> ';

            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        } else if(type == "Role"){
            var elmt = " " +
                '<div class="row px-2"> ' +
                    '<input name="send_to" value="role" hidden> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35"> ' +
                            '<label for="notif_title">Title</label> ' +
                            '<a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                            '<label for="notif_body">Body</label> ' +
                            '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="row mb-2"> ' +
                            '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                                        '@php($i = 0) ' +
                                        '@foreach($dictionary as $dct) ' +
                                            '@if($dct->type_name == "Notification") ' +
                                                '@if($i == 0) ' +
                                                    '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                                '@else  ' +
                                                    '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                                '@endif ' +
                                                '@php($i++) ' +
                                            '@endif ' +
                                        '@endforeach ' +
                                    '</select> ' +
                                    '<label for="notif_type">Type</label> ' +
                                    '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="send_time" name="send_time" aria-label="Floating label select example" onchange="toogleTimePicker()" required> ' +
                                        '<option value="now" selected>Now</option> ' +
                                        '<option value="manual">Manual</option> ' +
                                    '</select> ' +
                                    '<label for="send_time">Send Time</label> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="datetime-picker-box"></div> ' +
                        '</div> ' +
                        '<hr> ' +
                        '<span class="position-relative"> ' + 
                            '<h6>Selected Role</h6> ' +
                            '<a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllRole()"><i class="fa-regular fa-trash-can"></i> Clear All</a> ' +
                        '</span> ' +
                        '<div id="slct-role-list-holder"></div> ' +
                        '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                    '</div> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<input name="list_context" id="list_context_role"  value="" hidden> ' +
                        '<h6>All Role</h6> ' +
                        '<span id="role-list-holder"></span> ' +
                    '</div> ' +
                '</div> ';

            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        } else if(type == "Pending"){
            var elmt = " " +
                '<div class="px-2"> ' +
                    '<input name="send_to" value="pending" hidden> ' +
                    '<div class="form-floating mb-2"> ' +
                        '<input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35"> ' +
                        '<label for="notif_title">Title</label> ' +
                        '<a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<div class="form-floating mb-2"> ' +
                        '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                        '<label for="notif_body">Body</label> ' +
                        '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<div class="form-floating"> ' +
                        '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                            '@php($i = 0) ' +
                            '@foreach($dictionary as $dct) ' +
                                '@if($dct->type_name == "Notification") ' +
                                    '@if($i == 0) ' +
                                        '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                    '@else  ' +
                                        '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                    '@endif ' +
                                    '@php($i++) ' +
                                '@endif ' +
                            '@endforeach ' +
                        '</select> ' +
                        '<label for="notif_type">Type</label> ' +
                        '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                '</div> ';
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog');
        } else if(type == "Person"){
            var elmt = " " +
                '<div class="row px-2"> ' +
                    '<input name="send_to" value="person" hidden> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35"> ' +
                            '<label for="notif_title">Title</label> ' +
                            '<a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                            '<label for="notif_body">Body</label> ' +
                            '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="row mb-2"> ' +
                           '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                                        '@php($i = 0) ' +
                                        '@foreach($dictionary as $dct) ' +
                                            '@if($dct->type_name == "Notification") ' +
                                                '@if($i == 0) ' +
                                                    '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                                '@else  ' +
                                                    '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                                '@endif ' +
                                                '@php($i++) ' +
                                            '@endif ' +
                                        '@endforeach ' +
                                    '</select> ' +
                                    '<label for="notif_type">Type</label> ' +
                                    '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div class="col-lg-6"> ' +
                                '<div class="form-floating"> ' +
                                    '<select class="form-select" id="send_time" name="send_time" aria-label="Floating label select example" onchange="toogleTimePicker()" required> ' +
                                        '<option value="now" selected>Now</option> ' +
                                        '<option value="manual">Manual</option> ' +
                                    '</select> ' +
                                    '<label for="send_time">Send Time</label> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="datetime-picker-box"></div> ' +
                        '</div> ' +
                        '<hr> ' +
                        '<span class="position-relative"> ' + 
                            '<h6>Selected User</h6> ' +
                            '<a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllUser()"><i class="fa-regular fa-trash-can"></i> Clear All</a> ' +
                        '</span> ' +
                        '<div id="slct-user-list-holder"></div> ' +
                        '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                    '</div> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<input name="list_context" id="list_context"  value="" hidden> ' +
                        '<h6>All User</h6> ' +
                        '<span id="user-list-holder"></span> ' +
                    '</div> ' +
                '</div> ';

            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        }

        sec.innerHTML = elmt;
    }

    function toogleTimePicker(){
        var time = document.getElementById("send_time").value;

        var elmt = " "+
            '<div class="row"> ' +
                '<div class="col-lg-6"> ' +
                    '<label>Set Date</label> ' +
                    '<input type="date" name="sended_date" id="sended_date" onchange="" class="form-control"> ' +
                '</div> ' +
                '<div class="col-lg-6"> ' +
                    '<label>Set Time</label> ' +
                    '<input type="time" name="sended_time" id="sended_time" onchange="" class="form-control"> ' +
                    '<a id="dateEnd_event_msg" class="input-warning text-danger"></a> ' +
                '</div> ' +
            '</div> ';

        document.getElementById("datetime-picker-box").innerHTML = elmt;
    }

    function infinteLoadGroup(page_group_list) {       
        document.getElementById("group-list-holder").innerHTML = "";

        $.ajax({
            url: "/api/v1/group/limit/100/order/group_name__DESC/find/%20?page=" + page_group_list,
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

            if(page_group_list != last){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the group</h5>");
                return;
            } else {                
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug = data[i].slug_name;
                    var groupName = data[i].group_name;
                    var groupDesc = data[i].group_desc;
                    var total = data[i].total;

                    var elmt = " " +
                        '<a class="btn user-box py-3" style="height:80px;" onclick=""> ' +
                            '<div class="position-relative ps-2"> ' +
                                '<h6 class="text-secondary fw-normal">' + groupName + '</h6> ' +
                                '<h6 class="text-secondary fw-bold" style="font-size:13px;">' + groupDesc + '</h6> ' +
                                '<div class="form-check position-absolute" style="right: 20px; top: 10px;"> ' +
                                    '<input class="form-check-input" name="user_username[]" value="' + slug + '" type="checkbox" style="width: 25px; height:25px;" id="check_group_'+ slug +'" onclick="addSelectedGroup('+"'"+slug+"'"+', '+"'"+groupName+"'"+', this.checked)"> ' +
                                '</div> ' +
                            '</div> ' +
                        '</a>';

                    $("#group-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    function infinteLoadRole(page_role_list) {       
        document.getElementById("role-list-holder").innerHTML = "";

        $.ajax({
            url: "/api/v1/tag/10?page=" + page_role_list,
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

            if(page_role_list != last){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the role</h5>");
                return;
            } else {                
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug = data[i].slug_name;
                    var tagName = data[i].tag_name;
                    if(data[i].tag_category){
                        var category = data[i].tag_category;
                    } else {
                        var category = "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> No Category</span>";
                    }

                    var elmt = " " +
                        '<a class="btn user-box py-3" style="height:80px;" onclick=""> ' +
                            '<div class="position-relative ps-2"> ' +
                                '<h6 class="text-secondary fw-normal">' + tagName + '</h6> ' +
                                '<h6 class="text-secondary fw-bold" style="font-size:13px;">' + category + '</h6> ' +
                                '<div class="form-check position-absolute" style="right: 20px; top: 10px;"> ' +
                                    '<input class="form-check-input" name="slug_name[]" value="' + slug + '" type="checkbox" style="width: 25px; height:25px;" id="check_role_'+ slug +'" onclick="addSelectedRole('+"'"+slug+"'"+', '+"'"+tagName+"'"+', this.checked)"> ' +
                                '</div> ' +
                            '</div> ' +
                        '</a>';

                    $("#role-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
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

    function infinteLoadUser(page_new_req) {       
        document.getElementById("user-list-holder").innerHTML = "";

        $.ajax({
            url: "/api/v1/user/%20/limit/100/order/first_name__DESC?page=" + page_new_req,
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
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the user :)</h5>");
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
                        '<a class="btn user-box" style="height:80px;"> ' +
                            '<div class="row ps-2"> ' +
                                '<div class="col-2 p-0 py-2 ps-2"> ' +
                                    '<img class="img img-fluid user-image" src="'+getUserImage(img, grole)+'" alt="username-profile-pic.png"> ' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + fullName + '</h6> ' +
                                    '<h6 class="text-secondary fw-bold" style="font-size:13px;">' + grole + '</h6> ' +
                                    '<div class="form-check position-absolute" style="right: 20px; top: 20px;"> ' +
                                        '<input class="form-check-input" name="user_username[]" value="' + username + '" type="checkbox" style="width: 25px; height:25px;" id="check_'+ username +'" onclick="addSelectedUser('+"'"+username+"'"+', '+"'"+fullName+"'"+', this.checked)"> ' +
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

    function addSelectedUser(username, fullname, checked){
        var input_holder = document.getElementById("list_context");
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
        refreshListUser();
    }

    function addSelectedGroup(slug, groupName, checked){
        var input_holder = document.getElementById("list_context_group");
        if(selectedGroup.length == 0){
            selectedGroup.push({
                slug : slug,
                groupName : groupName
            });
            input_holder.value = JSON.stringify(selectedGroup);
        } else {
            if(checked === false){
                let indexToRemove = selectedGroup.findIndex(obj => obj.slug == slug);
                if (indexToRemove !== -1) {
                    selectedGroup.splice(indexToRemove, 1);

                    // Make sure the item unchecked by remove from selected user list
                    document.getElementById("check_group_"+slug).checked = false; 
                    input_holder.value = JSON.stringify(selectedGroup);
                } else {
                    console.log('Item not found LOL');
                }
            } else {
                selectedGroup.push({
                    slug : slug,
                    groupName : groupName
                });
                input_holder.value = JSON.stringify(selectedGroup);
            }
        }
        refreshListGroup();
    }

    function clearAllUser(){
        document.getElementById("slct-user-list-holder").innerHTML = "";
        selectedUser.forEach((e) => {
            document.getElementById("check_"+e.username).checked = false; 
        });
        selectedUser = [];
    }

    function clearAllGroup(){
        document.getElementById("slct-group-list-holder").innerHTML = "";
        selectedUser.forEach((e) => {
            document.getElementById("check_"+e.username).checked = false; 
        });
        selectedUser = [];
    }

    function refreshListUser(){
        var holder = document.getElementById("slct-user-list-holder");
        holder.innerHTML = " ";

        selectedUser.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelectedUser('+"'"+e.username+"'"+', '+"'"+e.fullName+"'"+', false)" title="Remove this user"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.full_name + '</a>';
            holder.innerHTML += elmt;
        });
    }

    function refreshListGroup(){
        var holder = document.getElementById("slct-group-list-holder");
        holder.innerHTML = " ";

        selectedGroup.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelectedGroup('+"'"+e.slug+"'"+', '+"'"+e.groupName+"'"+', false)" title="Remove this group"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.groupName + '</a>';
            holder.innerHTML += elmt;
        });
    }
</script>
