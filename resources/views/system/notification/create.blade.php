<style>
    .btn-quick-action{
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
    .btn-quick-action:hover{
        background: #F78A00 !important;
        background-image:none !important;
    }
    .quick-action-text{
        font-size:24px;
        color:white;
        transition: 0.5s;
        margin-top:13vh;
    }
    .quick-action-info{
        font-size:14px;
        color:white;
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action:hover .quick-action-text{
        margin-top:-4vh;
    }
    .btn-quick-action:hover .quick-action-info{
        display:block;
    }
</style>

<script>
    let validation = [
        { id: "notif_body", req: true, len: 255 },
    ];
</script>

<button class="btn btn-submit" data-bs-toggle="modal" data-bs-target="#selectTypeModal"><i class="fa-solid fa-plus"></i> Add Notification</button>
<div class="modal fade" id="selectTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Select Your Notif / Announcement Type</h5>
                
                <div class="row px-2">
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action" onclick="setType('All User')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/global.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="All User" data-bs-toggle="modal">
                            <h5 class="quick-action-text">All User</h5>
                            <p class="quick-action-info">Send announcement to all user who is registered and accepted in Mi-FIK App</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action" onclick="setType('Grouping')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/group.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="By Grouping" data-bs-toggle="modal">
                            <h5 class="quick-action-text">By Grouping</h5>
                            <p class="quick-action-info">Send announcement to specific group that containe some user</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action" onclick="setType('Person')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/person.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="By Person" data-bs-toggle="modal">
                            <h5 class="quick-action-text">By Person</h5>
                            <p class="quick-action-info">Send announcement to one or some user with searching one by one</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action" onclick="setType('Pending')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/pending.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="Pending" data-bs-toggle="modal">
                            <h5 class="quick-action-text">Or, Pending</h5>
                            <p class="quick-action-info">Make announcement and send it later with specific time and you can choose the type later</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add <span id="type-title"></span> Notif</h5>
                
                <span id="section-holder"></span>
            </div>
        </div>
    </div>
</div>

<script>
    function setType(type){
        document.getElementById("type-title").innerHTML = type;
        setFormSection(type);
        infinteLoadGroup(1);
    }

    function setFormSection(type){
        var sec = document.getElementById("section-holder");
        if(type == "All User"){
            var elmt = " " +
                '<div class="px-2"> ' +
                    '<div class="form-floating mb-2"> ' +
                        '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                        '<label for="notif_body">Notif Body</label> ' +
                        '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<div class="form-floating"> ' +
                        '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                            '@php($i = 0) ' +
                            '@foreach($dictionary as $dct) ' +
                                '@if($i == 0) ' +
                                    '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                '@else  ' +
                                    '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                '@endif ' +
                                '@php($i++) ' +
                            '@endforeach ' +
                        '</select> ' +
                        '<label for="notif_type">Type</label> ' +
                        '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                '</div> ';
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog');
        } else if(type == "Grouping"){
            var elmt = " " +
                '<div class="row px-2"> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea> ' +
                            '<label for="notif_body">Notif Body</label> ' +
                            '<a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<div class="form-floating mb-2"> ' +
                            '<select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required> ' +
                                '@php($i = 0) ' +
                                '@foreach($dictionary as $dct) ' +
                                    '@if($i == 0) ' +
                                        '<option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option> ' +
                                    '@else  ' +
                                        '<option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option> ' +
                                    '@endif ' +
                                    '@php($i++) ' +
                                '@endforeach ' +
                            '</select> ' +
                            '<label for="notif_type">Type</label> ' +
                            '<a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                        '</div> ' +
                        '<span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span> ' +
                    '</div> ' +
                    '<div class="col-lg-6 col-md-6 col-sm-6"> ' +
                        '<h6>All Group</h6> ' +
                        '<span id="group-list-holder"></span> ' +
                    '</div> ' +
                '</div> ';

            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        } 

        sec.innerHTML = elmt;
    }

    function infinteLoadGroup(page_new_req) {       
        document.getElementById("group-list-holder").innerHTML = "";

        $.ajax({
            url: "/api/v1/group/limit/100/order/group_name__DESC/find/%20?page=" + page_new_req,
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
                    var slug = data[i].slug_name;
                    var groupName = data[i].group_name;
                    var groupDesc = data[i].group_desc;
                    var total = data[i].total;

                    var elmt = " " +
                        '<a class="btn user-box py-3" style="height:80px;" onclick=""> ' +
                            '<div class="row ps-2"> ' +
                                '<h6 class="text-secondary fw-normal">' + groupName + '</h6> ' +
                                '<h6 class="text-secondary fw-bold" style="font-size:13px;">' + groupDesc + '</h6> ' +
                                '<div class="form-check position-absolute" style="right: 20px; top: 20px;"> ' +
                                    '<input class="form-check-input" name="user_username[]" value="' + slug + '" type="checkbox" style="width: 25px; height:25px;" id="check_'+ slug +'" onclick=""> ' +
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
</script>
