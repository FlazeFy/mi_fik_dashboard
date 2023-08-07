<div>  
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:20px; top:20px;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu normal dropdown-menu-end shadow" aria-labelledby="section-more-MOL">
        <a class="dropdown-item" data-bs-target="#helpRequestTag" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> {{ __('messages.help') }}</a>
        <a class="dropdown-item text-danger" onclick="abortTagPicker()"><i class="fa-solid fa-xmark"></i> {{ __('messages.abort') }}</a>
    </div>

    @include('popup.mini_help', ['id' => 'helpRequestTag', 'title'=> 'Request Tag', 'location'=>'request_tag'])

    @if(!$myreq)
        <div class="" id="start-section-manage">
            <img class="img img-fluid d-block mx-auto image-msg-md" src="{{ asset('/assets/picker.png')}}">
            <h6 class="text-secondary text-center">In this section, you can request some tag you want to add to your role or maybe you want to remove the tag 
                <button class="btn btn-link py-1 px-2" onclick="infinteLoadMore(1)"><i class="fa-solid fa-magnifying-glass"></i> {{ __('messages.browse_tag') }}</button>
            </h6>
        </div>
        @if(session()->get('role_key') != 1)
            <div class="position-absolute" style="right:60px; top:20px;" id="cat-picker-holder">
                <select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value)" name="tag_category" 
                    style="font-size:13px;"aria-label="Floating label select example" required>
                    @php($i = 0) 
                    @foreach($dct_tag as $dtag) 
                        @if($dtag->slug_name != "general-role")
                            @if($i == 0) 
                                <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                                <option value="all">{{ __('messages.all') }}</option>
                            @else 
                                <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                            @endif
                            @php($i++)
                        @endif
                    @endforeach
                </select>
            </div> 
        @endif

        <div class="user-req-holder mt-4" id="data_wrapper_manage_tag">
            <!-- Loading -->
            <div id="start-load" class="d-none">
                <div class="auto-load text-center">
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
                </div>
            </div>
        </div>
        <div id="empty_item_holder"></div>
        <span id="load_more" style="display: flex; justify-content:center;"></span>
    @else 
        <div class="" id="start-section-manage">
            <img class="img img-fluid d-block mx-auto image-msg-md" src="{{ asset('/assets/sorry.png')}}">
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
    var slct_list = [];
    var page = 0;
    var start_section = document.getElementById("start-section-manage");
    var load_section = document.getElementById("start-load");
    $("#body-req").css({"display":"none"});
    $("#cat-picker-holder").css({"display":"none"});

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
        $("#info-box-profile").css({"text-align":"left"});

        $("#body-title").css({"position":"absolute", "color": "white"});
        $("#body-eng").css({"display":"none"});
        $("#body-req").css({"display":"block"});
        $("#cat-picker-holder").css({"display":"block"});

        $("#body-title").animate({
            "top": "40px",
            "left": "33%",
        }, 400); 

        $("#profile_image_info").animate({
            "width": "80px",
            "height": "80px",
        }, 400); 

        $("#btn-change-image").css({"top":"65px", "right": "40px", "height":"50px", "width":"50px", "padding": "7.5px"});
        $("#btn-reset-image").css({"top":"57.5px", "right": "-10px", "height":"50px", "width":"50px", "padding": "11px"});
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
        $("#data_wrapper_manage_tag").empty();
    }

    function infinteLoadMore(page) {  
        stylingTagManage();  
        start_section.setAttribute('class', 'd-none');
        load_section.setAttribute('class', '');

        var per_page = 24;
        if(isMobile()){
            per_page = 12;
        } 
        
        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/"+per_page+ "?page=" + page,
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
                $('#load_more').html(`<button class="btn content-more-floating mt-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more').html(`<h6 class="text-secondary my-3">No more tag to show</h6>`);
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Tag found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html(`<h5 class='text-secondary'>Woah!, You have see all the tags</h5>`);
                return;
            } else {
                if(myTag.length == 0){
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var tag_name = data[i].tag_name;

                        if(slug_name != "lecturer" && slug_name != "staff" && slug_name != "student"){
                            const elmt = `
                                <a class="btn btn-tag" id="tag_collection_${slug_name}" title="Select this tag" 
                                    onclick="addSelectedTag('${slug_name}', '${tag_name}', true, '${type}')">
                                    ${tag_name}
                                </a>
                            `;

                            $("#data_wrapper_manage_tag").append(elmt);
                        }
                    } 
                } else {
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var found = false;
                        
                        if(slug_name != "lecturer" && slug_name != "staff" && slug_name != "student"){
                            myTag.forEach(e => {
                                if(e['slug_name'] === slug_name){
                                    found = true;
                                }
                            });

                            if(!found){
                                var tag_name = data[i].tag_name;

                                const elmt = `
                                    <a class="btn btn-tag" id="tag_collection_${slug_name}" title="Select this tag" 
                                        onclick="addSelectedTag('${slug_name}', '${tag_name}', true, '${type}')">
                                        ${tag_name}
                                    </a>
                                `;

                                $("#data_wrapper_manage_tag").append(elmt);
                                start++;
                            }
                        }
                    } 
                }

                if(start == 0){
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
                $("#slct_holder").append(`
                    <div class="d-inline" id="tagger_${slug_name}">
                        <input hidden name="req_type[]" value="${type}">
                        <input hidden name="user_role[]" value='{"slug_name":"${slug_name}", "tag_name":"${tag_name}"}'>
                        <a class="btn btn-tag-selected ${bg}" title="Select this tag" onclick="removeSelectedTag('${slug_name}', '${tag_name}', '${type}')">
                            <i class="fa-solid fa-xmark"></i> ${tag_name}
                        </a>
                    </div>
                `);
            }
        } else {
            slct_list.push({
                "slug_name": slug_name,
                "tag_name": tag_name,
                "type": type
            });
            $("#slct_holder").append(`
                <div class="d-inline" id="tagger_${slug_name}">
                    <input hidden name="req_type[]" value="${type}">
                    <input hidden name="user_role[]" value='{"slug_name":"${slug_name}", "tag_name":"${tag_name}"}'>
                    <a class="btn btn-tag-selected ${bg}" title="Unselect this tag" onclick="removeSelectedTag('${slug_name}', '${tag_name}', '${type}')">
                        <i class="fa-solid fa-xmark"></i> ${tag_name}
                    </a>
                </div>
            `);
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
                    tags += `<span class="${color} fw-bold">#${slct_list[i]['tag_name']}</span>, `;
                } else {
                    tags += `<span class="${color} fw-bold">#${slct_list[i]['tag_name']}</span>`;
                }
            }
            
            $("#btn-submit-tag-holder").html(`
                <a class="btn btn-submit-form mt-3" title="Submit Role" data-bs-toggle="modal" href="#requestRoleAdd">
                    <i class="fa-solid fa-paper-plane"></i> Request
                </a>
                <div class="modal fade" id="requestRoleAdd" tabindex="-1" aria-labelledby="requestRoleAddLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="requestRoleAddLabel">Request Selected Tags</h5>
                                <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                            </div>
                            <div class="modal-body">
                                <h6 class="fw-normal">Are you sure want to request ${tags}</h6>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-submit-form" id="btn-submit-form">
                                    <i class="fa-solid fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        } else {
            return $("#btn-submit-tag-holder").text('')
        }
    }

    function removeSelectedTag(slug_name, tag_name, type){
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e['slug_name'] !== slug_name })
        tag.parentNode.removeChild(tag);

        if(type == "add"){
            $("#data_wrapper_manage_tag").append(`
                <a class='btn btn-tag' id='tag_collection_${slug_name}' title='Select this tag' onclick='addSelectedTag("${slug_name}", "${tag_name}", true, "${type}")'>${tag_name}</a>
            `);
        } else if(type == "remove"){
            $("#my_tag_list").append(`
                <a class='btn btn-danger mb-2 me-1' id='tag_collection_${slug_name}' title='Select this tag' onclick='addSelectedTag("${slug_name}", "${tag_name}", true, "${type}")'>${tag_name}</a>
            `);
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