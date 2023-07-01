<style>
    #data-wrapper{
        margin: 0;
        padding: 0;
        display: flex;
        max-height: 75vh;
        flex-direction: column;
        overflow-y: scroll;
    }
</style>

<div class="mb-5 pb-2" id="role-area-picker">
    <h4 class="text-primary">Available Role</h4>
    <form class="d-inline" id="form-login-role">
        <input hidden name="username" id="username_role">
        <input hidden name="password" id="password_role">
    </form>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:20px; top:20px;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu normal dropdown-menu-end shadow" aria-labelledby="section-more-MOL">
        <a class="dropdown-item" data-bs-target="#helpRequestTag" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item text-danger" onclick="abortTagPicker()"><i class="fa-solid fa-xmark"></i> Abort</a>
    </div>

    @include('popup.mini_help', ['id' => 'helpRequestTag', 'title'=> 'Request Tag', 'location'=>'request_tag'])

    <div class="selected-role" id="slct-box" style="display:none;">
        <h6 class='mt-2 mb-0'>Selected Role</h6> 
        <div id="slct_holder"></div>
    </div>
    <a id="selected_role_msg" class="text-danger my-2" style="font-size:13px;"></a>
    <hr>
    <div class="" id="data-wrapper">
        <!-- Loading -->
        <div class="auto-load text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </div>
    </div>
   
    <div id="empty_item_holder"></div>
    <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
    <div id="modal-submit-tag"></div>
</div>

<span id="btn-next-ready-holder">
    <button class="btn-next-steps locked" id="btn-next-ready" onclick="warn('role')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>

<script type="text/javascript">
    var page = 1;
    //loadTag();

    function abortTagPicker(){
        slct_role = [];
        $("#slct_holder").empty();
        $("#slct-box").css("display","none");
        loadTag();
        validate("role");
    }

    function loadTag() {  
        $("#data-wrapper").empty();
        if(!document.getElementById("data-wrapper").hasChildNodes()){   
            function fetchData(data){
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var dct_name = data[i].dct_name;
                    var cls = "";

                    if(slug_name == "general-role"){
                        cls = "important-category";
                    }

                    var elmt = " " +
                        "<div class='" + cls + "'> " +
                            "<h6 class='mt-2 mb-0'>" + dct_name + "</h6> " +
                            "<div class='' id='tag-cat-holder-" + slug_name + "'></div> " +
                            "<div class='auto-load-" + slug_name + " text-center'> " +
                                '<lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> ' +
                            "</div> " +
                            "<div id='empty_item_holder_" + slug_name + "'></div> " +
                            "<span id='load_more_holder_" + slug_name + "' style='display: flex; justify-content:end;'></span> " +
                        "</div>";

                    loadTagByCat(slug_name);

                    $("#data-wrapper").append(elmt);   
                }
            }

            $.ajax({
                url: "/api/v1/dictionaries/type/TAG-001",
                datatype: "json",
                type: "get",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Accept", "application/json");
                    $('.auto-load').show();
                }
            })
            .done(function (response) {
                $('.auto-load').hide();
                var data =  response.data;
                sessionStorage.setItem('tag_cat_sess', JSON.stringify(data));

                fetchData(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                $('.auto-load').hide();

                if(sessionStorage.getItem('tag_cat_sess') != null && jqXHR.status == 429){
                    fetchData(JSON.parse(sessionStorage.getItem('tag_cat_sess')));
                } else {
                    failResponse(jqXHR, ajaxOptions, thrownError, "#empty_item_holder", false, null, null);
                }
            });
        } 
    }

    function loadTagByCat(cat) {        
        function fetchData(data){
            for(var i = 0; i < data.length; i++){
                //Attribute
                var slug_name = data[i].slug_name;
                var tag_name = data[i].tag_name;

                if(slug_name != "student"){
                    var elmt = " " +
                        '<button class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this role" onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+cat+"'"+')">' + tag_name + '</button>';

                    $("#tag-cat-holder-" + cat).append(elmt); 
                }
            }
        }
        
        $.ajax({
            url: "/api/v1/tag/cat/" + cat + "/20?page="+page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                $('.auto-load-' + cat).show();
            }
        })
        .done(function (response) {
            $('.auto-load-' + cat).hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder-' + cat).html('<button class="btn content-more-floating my-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more</button>');
            } else {
                $('#load_more_holder-' + cat).html('<h6 class="btn content-more-floating my-3 p-2">No more role to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder-' + cat).html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-'+cat).html("<h5 class='text-primary'>Woah!, You have see all the role</h5>");
                return;
            } else {
                sessionStorage.setItem('tag_bycat_'+cat+'_sess', JSON.stringify(data));
                fetchData(data);
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load-'+cat).hide();
                $("#empty_item_holder_" + cat).html("<h6 class='text-secondary text-center'>No role available</h6>");
            } else if(sessionStorage.getItem('tag_bycat_'+cat+'_sess') != null && jqXHR.status == 429){
                $('.auto-load-'+cat).hide();
                fetchData(JSON.parse(sessionStorage.getItem('tag_bycat_'+cat+'_sess')));
            } else {
                $('.auto-load-'+cat).hide();
                $("#empty_item_holder_" + cat).html("<h6 class='text-secondary text-center'>No role available</h6>");
            }
        });
    }

    function addSelectedTag(slug_name, tag_name, is_deleted, cat){
        var found = false;

        //Remove selected tag from tag collection
        if(is_deleted){
            var tag = document.getElementById('tag_collection_'+slug_name);
            tag.parentNode.removeChild(tag);
        }

        if(slct_role.length > 0){
            //Check if tag is exist in selected tag.
            slct_role.map((val, index) => {
                if(val.slug_name == slug_name){
                    found = true;
                }
            });

            if(found == false){
                slct_role.push({
                    "slug_name" : slug_name,
                    "tag_name" : tag_name
                });
                //Check this append input value again!
                $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Select this tag' " +
                    " onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+cat+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_role.push({
                "slug_name" : slug_name,
                "tag_name" : tag_name
            });
            $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Unselect this tag' " +
                " onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+cat+'"'+")'>"+tag_name+"</a></div>");
        }
        validate("role");
    }

    function getSubmitButton(){
        var tags = "";
        $("#modal-submit-tag").empty();

        for(var i = 0; i < slct_role.length; i++){
            if(i != slct_role.length - 1){
                tags += '<span class="text-success fw-bold">#' + slct_role[i]['tag_name'] + '</span>, ';
            } else {
                tags += '<span class="text-success fw-bold">#' + slct_role[i]['tag_name'] + '</span>';
            }
        }
        
        $("#modal-submit-tag").html(''+
            '<div class="modal fade" id="requestRoleAdd" tabindex="-1" aria-labelledby="requestRoleAddLabel" aria-hidden="true"> ' +
            '<div class="modal-dialog"> ' +
                '<div class="modal-content"> ' +
                '<div class="modal-header"> ' +
                    '<h5 class="modal-title" id="requestRoleAddLabel">Request Selected Tags</h5> ' +
                    '<a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a> ' +
                '</div> ' +
                '<div class="modal-body"> ' +
                    '<h6 class="fw-normal">Are you sure want to request ' + tags + '</h6> ' +
                    '<form id="form-role-req"> ' +
                        '<input name="req_type" value="add" hidden> ' +
                        "<input name='user_role' value='"+JSON.stringify(slct_role)+"' hidden> " +
                    '</form> ' +
                '</div> ' +
                '<div class="modal-footer"> ' +
                    '<button class="btn btn-submit-form" onclick="submitAddReq()"><i class="fa-solid fa-paper-plane"></i> Send</button> ' +
                '</div> ' +
                '</div> ' +
            '</div> ' +
            '</div>') ;
    }

    function submitAddReq(){
        var msg_error = "<i class='fa-solid fa-triangle-exclamation'></i> Something wrong when sending request. Try contact the Admin";
        $.ajax({
            url: '/api/v1/login',
            type: 'POST',
            data: $('#form-login-role').serialize(),
            dataType: 'json',
            success: function(response) {
                token = response.token;

                if(token != null){
                    $.ajax({
                        url: '/api/v1/user/request/role',
                        type: 'POST',
                        data: $('#form-role-req').serialize(),
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            const auth = "Bearer "+token+"";
                            xhr.setRequestHeader("Accept", "application/json");
                            xhr.setRequestHeader("Authorization", auth);
                        },
                        success: function(response) {
                            is_requested = true;
                            $('#role-area-picker').html('<lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 400px; height: 400px;" autoplay></lottie-player> ' +
                                '<h6 class="text-primary text-center" style="font-size:26px;">You have successfully request role</h6>');
                            btn_ready_holder.innerHTML = "<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#ready' onclick='routeStep("+'"'+"next"+'"'+", "+'"'+"role"+'"'+")'><i class='fa-solid fa-arrow-right'></i> Next</button>";

                        },
                        error: function(response, jqXHR, textStatus, errorThrown) {
                            msg_all_role.innerHTML = msg_error;
                        }
                    });
                    
                    $('#requestRoleAdd').modal({ backdrop: 'static' }).modal('hide');
                } else {
                    msg_all_role.innerHTML = msg_error;
                    validate("role");
                }
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                msg_all_role.innerHTML = msg_error;
                validate("role");
            }
        });
    }

    function removeSelectedTag(slug_name, tag_name, cat){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_role = slct_role.filter(function(e) { return e.slug_name !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#tag-cat-holder-" + cat).append("<button class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+cat+'"'+")'>"+tag_name+"</button>");

        validate("role");
    }
</script>