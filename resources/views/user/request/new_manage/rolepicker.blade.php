
<div class="position-relative">
    <label class="input-title" style="margin-top:4px;">List Roles</label>
    <div class="position-absolute" style="right:0; top:0;">
        <select class="form-select" id="tag_category" title="Tag Category" onchange="setAccRoleFilter(this.value)" name="tag_category" 
            style="font-size:13px;"aria-label="Floating label select example" required>
            @php($i = 0) 
            @foreach($dct_tag as $dtag) 
                @if($i == 0) 
                    <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                    <option value="all">All</option>
                @else 
                    <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                @endif
                @php($i++) 
            @endforeach
        </select>
    </div> 
    <div class="tag-manage-holder mt-3" id="acc_role_holder">
        <div class="auto-load-acc-role text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </div>
    </div>
    <div id="empty_item_holder_acc_with_role"></div>
    <span id="load_acc_with_role" style="display: flex; justify-content:center;"></span>  
</div>

<label class="input-title">Selected Role</label>
<div id="slct_acc_role_holder"></div>

<script type="text/javascript">
    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_role = []; //Store all tag's id.
    
    //Show tag collection
    tag_list.map((val, index) => {
        $("#acc_role_holder").append("<a class='btn btn-tag' id='acc_role_coll_"+val['slug_name']+"' title='Select this tag' onclick='addSelectedAccRole("+'"'+val['slug_name']+'"'+", "+'"'+val['tag_name']+'"'+", true, "+'"'+"slct"+'"'+")'>"+val['tag_name']+"</a>");
    });

    <?php 
        if(session()->get('role_key') == 1){
            echo 'var tag_cat = "'.$dct_tag[0]["slug_name"].'";';
            echo 'var page_tag = 1;';
        }
    ?>

    function setAccRoleFilter(tag){
        tag_cat = tag;
        page_tag = 1;
        loadRolePicker(page_tag);
        $("#acc_role_holder").empty();
    }

    function loadMoreAccRole(){
        page_tag++;
        loadRolePicker(page_tag);
    }

    function loadRolePicker(page_tag) {
        var per_page = 24;
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
                $('.auto-load-acc-role').show();
            }
        })
        .done(function (response) {
            $('.auto-load-acc-role').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_tag != last){
                $('#load_acc_with_role').html('<a class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadMoreAccRole()">Show more <span id="textno"></span></a>');
            } else {
                $('#load_acc_with_role').html('<h6 class="text-secondary my-3">No more tag to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_acc_with_role').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-acc-role').html("<h5 class='text-secondary'>Woah!, You have see all the tags</h5>");
                return;
            } else {
                $("#empty_item_holder_acc_with_role").empty();
                
                for(var i = 0; i < data.length; i++){

                    //Attribute
                    var slug_name = data[i].slug_name;
                    var tag_name = data[i].tag_name;

                    var elmt = '<a class="btn btn-tag" id="acc_role_coll_' + slug_name +'" title="Select this tag" ' + 
                        'onclick="addSelectedAccRole('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'slct'+"'"+')">' + tag_name + '</a> ';

                    $("#acc_role_holder").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError, response) {
            $('.auto-load-acc-role').hide();
            $('#load_acc_with_role').empty();
            failResponse(jqXHR, ajaxOptions, thrownError, '#empty_item_holder_acc_with_role', false, null, null);
        });
    }

    function addSelectedAccRole(slug_name, tag_name, is_deleted){
        var found = false;

        //Remove selected tag from tag collection
        if(is_deleted){
            var tag = document.getElementById('acc_role_coll_'+slug_name);
            tag.parentNode.removeChild(tag);
        }

        if(slct_role.length > 0){
            //Check if tag is exist in selected tag.
            slct_role.map((val, index) => {
                if(val == slug_name){
                    found = true;
                }
            });

            if(found == false){
                slct_role.push(slug_name);
                //Check this append input value again!
                $("#slct_acc_role_holder").append("<div class='d-inline' id='roles_acc_"+slug_name+"'><input hidden name='role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedAccRole("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_role.push(slug_name);
            $("#slct_acc_role_holder").append("<div class='d-inline' id='roles_acc_"+slug_name+"'><input hidden name='role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedAccRole("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
        }
    }

    function removeSelectedAccRole(slug_name, tag_name){
        //Remove selected tag
        var tag = document.getElementById('roles_acc_'+slug_name);
        slct_role = slct_role.filter(function(e) { return e !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#acc_role_holder").append("<a class='btn btn-tag' id='acc_role_coll_"+slug_name+"' title='Select this tag' onclick='addSelectedAccRole("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+"slct"+'"'+")'>"+tag_name+"</a>");

    }

    function clean(){
        slct_role = [];
        $("#acc_role_holder").empty();
        $("#slct_acc_role_holder").empty();
    }
</script>