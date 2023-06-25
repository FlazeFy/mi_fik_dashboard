<div class="position-relative" id="tag-manage-control">
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:20px; top:20px;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOL">
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item text-danger" onclick="abortTagPicker()"><i class="fa-solid fa-xmark"></i> Abort</a>
    </div>
    <div class="position-absolute" style="right:60px; top:10px;">
        <select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value)" name="tag_category" 
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
    <hr>
    <h5><span id="cat-selected-title">Organization</span>'s Tag</h5><br>
    <div class="user-req-holder" id="data_wrapper_manage_tag">
        <!-- Loading -->
        <div id="start-load" class="d-none">
            <div class="auto-load text-center">
                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
            </div>
        </div>
    </div>
    <div id="empty_item_holder"></div>
    <span id="load_more" style="display: flex; justify-content:center;"></span>
</div>


<script>
    var slct_list = [];
    var start_section = document.getElementById("start-section-manage");
    var load_section = document.getElementById("start-load");
    var myTag = [<?php
        if(session()->get('role_key') == 0){
            $tag = $user->role;
            if($tag){
                foreach($tag as $tg){
                    echo "{".
                            '"'."slug_name".'":"'.$tg['slug_name'].'",'.
                            '"'."tag_name".'":"'.$tg['tag_name'].'"'
                        ."},";
                }
            }
        }
    ?>];
    
    $("#tag-manage-control").css({"display":"none"});

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

    window.addEventListener('beforeunload', function(event) {
        if(slct_list.length > 0 && !isFormSubmitted){
            event.preventDefault();
            event.returnValue = '';
        }
    });

    function stylingTagManage(){
        $("#tag-manage-control").css({"display":"block"});
    }

    <?php 
        if(session()->get('role_key') == 0){
            echo 'var tag_cat = "'.$dct_tag[0]["slug_name"].'";';
            echo 'var page_tag = 1;';
        }
    ?>

    function setTagFilter(tag){
        page = 1;
        tag_cat = tag;
        infinteLoadMore(page);
        $("#cat-selected-title").html(ucFirst(tag));
        $("#data_wrapper_manage_tag").empty();
    }

    function infinteLoadMore(page) {  
        stylingTagManage();  
        start_section.setAttribute('class', 'd-none');
        load_section.setAttribute('class', '');
        
        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/50?page=" + page,
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
            var start = 0;

            if(page != last){
                $('#load_more').html('<button class="btn content-more-floating mt-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more').html('<h6 class="text-secondary my-3">No more tag to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Tag found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the tags</h5>");
                return;
            } else {
                if(myTag.length == 0){
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var tag_name = data[i].tag_name;

                        var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'add'+"'"+')">' + tag_name + '</a> ';

                        $("#data_wrapper_manage_tag").append(elmt);
                    } 
                } else {
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var found = false;
                        
                        myTag.forEach(e => {
                            if(e['slug_name'] === slug_name){
                                found = true;
                            }
                        });

                        if(!found){
                            var tag_name = data[i].tag_name;

                            var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'add'+"'"+')">' + tag_name + '</a> ';

                            $("#data_wrapper_manage_tag").append(elmt);
                            start++;
                        }
                    } 
                }

                if(start == 0 && myTag.length > 0){
                    $('#load_more').empty();
                    $("#data_wrapper_manage_tag").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:200px;'><h6 class='text-secondary text-center'>You have already pick all tag in this category</h6></div>");
                }
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load-tag').hide();
            $('#load_more').empty();
            failResponse(jqXHR, ajaxOptions, thrownError, "#data_wrapper_manage_tag", false, null, null);
        });
    }

    function addSelectedTag(slug_name, tag_name, is_deleted, type){
        var found = false;

        //Remove selected tag from tag collection
        if(is_deleted){
            var tag = document.getElementById('tag_collection_'+slug_name);
            tag.parentNode.removeChild(tag);
        }

        if(type == "add"){
            var bg = "bg-success";
        } else if(type == "remove"){
            var bg = "bg-danger";
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
                    "type": type
                });
                //Check this append input value again!
                $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='req_type[]' value='"+type+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected "+bg+"' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+type+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_list.push({
                "slug_name": slug_name,
                "tag_name": tag_name,
                "type": type
            });
            $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='req_type[]' value='"+type+"'><input hidden name='user_role[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected "+bg+"' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", "+'"'+type+'"'+")'>"+tag_name+"</a></div>");
        }

        getButtonSubmitTag();
    }

    function getButtonSubmitTag(){
        if(slct_list.length > 0){
            var tags = "";

            for(var i = 0; i < slct_list.length; i++){
                if(slct_list[i]['type'] == "add"){
                    var color = "text-success";
                } else if(slct_list[i]['type'] == "remove"){
                    var color = "text-danger";
                }

                if(i != slct_list.length - 1){
                    tags += '<span class="' + color + ' fw-bold">#' + slct_list[i]['tag_name'] + '</span>, ';
                } else {
                    tags += '<span class="' + color + ' fw-bold">#' + slct_list[i]['tag_name'] + '</span>';
                }
            }
            
            $("#btn-submit-tag-holder").html(''+
                '<a class="btn btn-submit-form mt-3" title="Submit Role"  data-bs-toggle="modal" href="#requestRoleAdd"><i class="fa-solid fa-paper-plane"></i> Request</a> ' +
                '<div class="modal fade" id="requestRoleAdd" tabindex="-1" aria-labelledby="requestRoleAddLabel" aria-hidden="true"> ' +
                '<div class="modal-dialog"> ' +
                    '<div class="modal-content"> ' +
                    '<div class="modal-header"> ' +
                        '<h5 class="modal-title" id="requestRoleAddLabel">Request Selected Tags</h5> ' +
                        '<a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a> ' +
                    '</div> ' +
                    '<div class="modal-body"> ' +
                        '<h6 class="fw-normal">Are you sure want to request ' + tags + '</h6> ' +
                    '</div> ' +
                    '<div class="modal-footer"> ' +
                        '<button type="submit" class="btn btn-submit-form" onclick="submitAddForm()"><i class="fa-solid fa-paper-plane"></i> Send</button> ' +
                    '</div> ' +
                    '</div> ' +
                '</div> ' +
                '</div>') ;
        } else {
            return $("#btn-submit-tag-holder").text('')
        }
    }

    function removeSelectedTag(slug_name, tag_name, type){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e['slug_name'] !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        if(type == "add"){
            $("#data_wrapper_manage_tag").append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+type+'"'+")'>"+tag_name+"</a>");
        } else if(type == "remove"){
            $("#my_tag_list").append("<a class='btn btn-danger mb-2 me-1' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+type+'"'+")'>"+tag_name+"</a>");
        }

        getButtonSubmitTag();
    }

    function abortTagPicker(){
        location.reload();
    }

    function submitAddForm(){
        var form = document.getElementById('request_add_form');
        form.addEventListener('submit', (event) => {
            form.submit(); 
        });
    }
</script>