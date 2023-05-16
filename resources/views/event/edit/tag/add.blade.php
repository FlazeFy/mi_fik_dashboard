
<a class="btn btn-info" data-bs-toggle="collapse" href="#collapseManageTag" title="Manage Tag" role="button" aria-expanded="false" style="margin-top:-5px;">
    <i class="fa-solid fa-gear"></i>
</a>

<div class="card p-2 my-2 collapse" id="collapseManageTag">
    <h6 class="text-secondary mt-2"> Available Tag</h6>
    <div class="position-absolute" style="right:10px; top:10px;">
        <select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value)" name="tag_category" aria-label="Floating label select example" required> 
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

    <form action="/event/edit/update/tag/add/{{$c->slug_name}}" method="POST">
        @csrf
        <div class="tag-manage-holder mt-3" id="data_wrapper_manage_tag">
            <div class="auto-load-tag text-center">
                <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    'x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                    <path fill="#000"
                        'd="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                        <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                            'from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                    </path>
                </svg>
            </div>
        </div>
        <div id="empty_item_holder_manage_tag"></div>
        <span id="load_more_holder_manage_tag" style="display: flex; justify-content:center;"></span>

        <h6 class="text-secondary"> Selected Tag</h6>
        <div id="slct_holder"></div>

        <div id="slct_tag_submit_holder"></div>
    </form>
</div>

<script>
    var page = 1;
    infinteLoadMoreTag(page);

    function loadmoretag(){
        page++;
        infinteLoadMoreTag(page);
    }

    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_list = []; //Store all tag's id.
    var tag_cat = "<?= $dct_tag[0]['slug_name'] ?>";

    function setTagFilter(tag){
        tag_cat = tag;
        infinteLoadMoreTag(1);
        $("#data_wrapper_manage_tag").empty();
    }

    function infinteLoadMoreTag(page) { 

        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/12"+ "?page=" + page,
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

            if(page != last){
                $('#load_more_holder_manage_tag').html('<a class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadmoretag()">Show more <span id="textno"></span></a>');
            } else {
                $('#load_more_holder_manage_tag').html('<h6 class="text-primary my-3">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_manage_tag').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-tag').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                $("#empty_item_holder_manage_tag").empty();

                var selected_before = [<?php 
                    if($c->content_tag){
                        $tag = $c->content_tag;
                        $count_tag = count($tag);
                        foreach($tag as $tg){
                            echo '"'.$tg['slug_name'].'",';
                        }
                    } 
                ?>]

                if(selected_before.length > 0){
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var tag_name = data[i].tag_name;
                        var found = false;
                        for(var j = 0; j < selected_before.length; j++){
                            if(selected_before[j] == slug_name){
                                found = true;
                            }
                        }

                        if(!found){
                            var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                                'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'slct'+"'"+')">' + tag_name + '</a> ';

                            $("#data_wrapper_manage_tag").append(elmt);
                        }
                    }  
                } else {
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var slug_name = data[i].slug_name;
                        var tag_name = data[i].tag_name;


                        var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'slct'+"'"+')">' + tag_name + '</a> ';

                        $("#data_wrapper_manage_tag").append(elmt);
                    }  
                }
                 
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load-tag').hide();
                $('#load_more_holder_manage_tag').empty();
                $("#empty_item_holder_manage_tag").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:200px;'><h6 class='text-secondary text-center'>" + jqXHR.responseJSON.message + "</h6></div>");
            } else {
                // handle other errors
            }
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
                $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='tag[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_list.push(slug_name);
            $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='tag[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
        }

        getButtonSubmitTag()
        console.log(slct_list)
    }

    function removeSelectedTag(slug_name, tag_name){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#data_wrapper_manage_tag").append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+"slct"+'"'+")'>"+tag_name+"</a>");

        getButtonSubmitTag()
        console.log(slct_list)
    }

    function getButtonSubmitTag(){
        if(slct_list.length > 0){
            var tags = ""

            for(var i = 0; i < slct_list.length; i++){
                if(i != slct_list.length - 1){
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>, ';
                } else {
                    tags += '<span class="text-primary fw-bold">#' + slct_list[i] + '</span>';
                }
            }
            
            $("#slct_tag_submit_holder").html(''+
                '<a class="btn btn-submit mt-3" title="Submit Tag"  data-bs-toggle="modal" href="#addTag"><i class="fa-solid fa-plus"></i> Add Tag</a> ' +
                '<div class="modal fade" id="addTag" tabindex="-1" aria-labelledby="addTagLabel" aria-hidden="true"> ' +
                '<div class="modal-dialog"> ' +
                    '<div class="modal-content"> ' +
                    '<div class="modal-header"> ' +
                        '<h5 class="modal-title" id="addTagLabel">Add Selected Tags</h5> ' +
                        '<a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a> ' +
                    '</div> ' +
                    '<div class="modal-body"> ' +
                        '<h6 class="fw-normal">Are you sure want to add ' + tags + ' to this Event</h6> ' +
                    '</div> ' +
                    '<div class="modal-footer"> ' +
                        '<button type="submit" class="btn btn-success">Submit</button> ' +
                    '</div> ' +
                    '</div> ' +
                '</div> ' +
                '</div>') ;
        } else {
            return $("#slct_tag_submit_holder").text('')
        }
    }
</script>