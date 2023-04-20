<div>  
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:20px; top:20px;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOL">
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item text-danger" onclick="abortTagPicker()"><i class="fa-solid fa-xmark"></i> Abort</a>
    </div>

    @if(!$myreq)
        <div class="" id="start-section-manage">
            <img class="img img-fluid d-block mx-auto" style="max-width: 70%;" src="{{ asset('/assets/picker.png')}}">
            <h6 class="text-secondary text-center">In this section, you can request some tag you want to add to your role or maybe you want to remove the tag 
                <button class="btn btn-link py-1 px-2" onclick="infinteLoadMore(1)"><i class="fa-solid fa-magnifying-glass"></i> Browse Available Tag</button>
            </h6>
        </div>

        <div class="user-req-holder" id="data_wrapper_manage_tag">
            <!-- Loading -->
            <div id="start-load" class="d-none">
                <div class="auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
        </div>
        <div id="empty_item_holder"></div>
        <span id="load_more" style="display: flex; justify-content:center;"></span>
    @else 
        <div class="" id="start-section-manage">
            <img class="img img-fluid d-block mx-auto" style="max-width: 70%;" src="{{ asset('/assets/sorry.png')}}">
            <h6 class="text-secondary text-center">You can't request to modify your tag, because you still have 
                <a class="text-danger" title="Awaiting Request" data-bs-toggle="popover" title="Popover title" style="cursor: pointer;"
                data-bs-content="You have requested to {{$myreq[0]['request_type']}} 
                    <?php 
                        $tag = $myreq[0]['tag_slug_name'];
                        $count = count($tag);

                        for($i = 0; $i < $count; $i++){
                            if($i == $count - 1){
                                echo "#".$tag[$i]['tag_name'];
                            } else {
                                echo "#".$tag[$i]['tag_name'].", ";
                            }
                        }
                    ?>
                ">Awaiting request</a>. Please wait some moment or try to contact the 
                <a class="text-primary text-decoration-none" title="Send email" href="mailto:hello@mifik.id">Admin</a>
            </h6>
        </div>
    @endif
</div>

<script>
    var page = 1;
    var slct_list = [];
    var start_section = document.getElementById("start-section-manage");
    var load_section = document.getElementById("start-load");
    $("#body-req").css({"display":"none"});

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

    function stylingTagManage(){
        $("#info-box-profile").css({"text-align":"left"});

        $("#body-title").css({"position":"absolute", "color": "white"});
        $("#body-eng").css({"display":"none"});
        $("#body-req").css({"display":"block"});

        $("#body-title").animate({
            "top": "40px",
            "left": "33%",
        }, 400); 

        $("#profile_image_info").animate({
            "width": "80px",
            "height": "80px",
        }, 400); 
    }

    function infinteLoadMore(page) {  
        stylingTagManage();  
        start_section.setAttribute('class', 'd-none');
        load_section.setAttribute('class', '');
        
        $.ajax({
            url: "/api/v1/tag/20" + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more').html('<h6 class="btn content-more-floating mt-3 p-2">No more tag to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Tag found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the tags :)</h5>");
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
                        }
                    } 
                }
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
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