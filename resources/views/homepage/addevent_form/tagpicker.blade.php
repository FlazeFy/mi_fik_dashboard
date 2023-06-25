
<div class="position-relative">
    <label class="input-title" style="margin-top:4px;">Event Tag</label>
    @if(session()->get('role_key') != 1)
        <div id="tag_holder"></div>
    @else 
        <div class="position-absolute" style="right:0; top:0;">
            <select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilterAddEvent(this.value)" name="tag_category" 
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
        <div class="tag-manage-holder mt-3" id="tag_holder">
            <div class="auto-load-tag text-center">
                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
            </div>
        </div>
        <div id="empty_item_holder_manage_tag"></div>
        <span id="load_more_holder_manage_tag" style="display: flex; justify-content:center;"></span>
    @endif
</div>

<label class="input-title">Selected Tag</label>
<div id="slct_holder"></div>

<script type="text/javascript">
    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_list = []; //Store all tag's id.

    //Tag collection
    tag_list = [
        <?php 
            if(session()->get('role_key') != 1){
                foreach($mytag as $tg){
                    if(is_array($mytag)){
                        echo '{"slug_name":"'.$tg['slug_name'].'", "tag_name":"'.$tg['tag_name'].'"},';
                    } else {
                        echo '{"slug_name":"'.$tg->slug_name.'", "tag_name":"'.$tg->tag_name.'"},';
                    }
                }
            }
        ?>];
    
    //Show tag collection
    tag_list.map((val, index) => {
        $("#tag_holder").append("<a class='btn btn-tag' id='tag_collection_"+val['slug_name']+"' title='Select this tag' onclick='addSelectedTag("+'"'+val['slug_name']+'"'+", "+'"'+val['tag_name']+'"'+", true, "+'"'+"slct"+'"'+")'>"+val['tag_name']+"</a>");
    });

    <?php 
        if(session()->get('role_key') == 1){
            echo 'var tag_cat = "'.$dct_tag[0]["slug_name"].'";';
            echo 'var page_tag = 1;';
        }
    ?>

    function setTagFilterAddEvent(tag){
        tag_cat = tag;
        page_tag = 1;
        infinteLoadMoreTag(page_tag);
        $("#tag_holder").empty();
    }

    function loadmoretag(){
        page_tag++;
        infinteLoadMoreTag(page_tag);
    }

    function infinteLoadMoreTag(page_tag) {    
        if(1 == <?= session()->get('role_key')?>){     
            $.ajax({
                url: "/api/v1/tag/cat/" + tag_cat + "/12?page=" + page_tag,
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
                    $('#load_more_holder_manage_tag').html('<a class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadmoretag()">Show more <span id="textno"></span></a>');
                } else {
                    $('#load_more_holder_manage_tag').html('<h6 class="text-secondary my-3">No more tag to show</h6>');
                }

                if (total == 0) {
                    $('#empty_item_holder_manage_tag').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                    return;
                } else if (data.length == 0) {
                    $('.auto-load-tag').html("<h5 class='text-secondary'>Woah!, You have see all the tags</h5>");
                    return;
                } else {
                    $("#empty_item_holder_manage_tag").empty();
                    
                    for(var i = 0; i < data.length; i++){

                        //Attribute
                        var slug_name = data[i].slug_name;
                        var tag_name = data[i].tag_name;

                        var elmt = '<a class="btn btn-tag" id="tag_collection_' + slug_name +'" title="Select this tag" ' + 
                            'onclick="addSelectedTag('+"'"+ slug_name +"'"+', '+"'"+tag_name+"'"+', true, '+"'"+'slct'+"'"+')">' + tag_name + '</a> ';

                        $("#tag_holder").append(elmt);
                    }   
                }
            })
            .fail(function (jqXHR, ajaxOptions, thrownError, response) {
                $('.auto-load-tag').hide();
                $('#load_more_holder_manage_tag').empty();
                failResponse(jqXHR, ajaxOptions, thrownError, "#empty_item_holder_manage_tag", false, null, null);
            });
        }
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
                $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='content_tag[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
            }
        } else {
            slct_list.push(slug_name);
            $("#slct_holder").append("<div class='d-inline' id='tagger_"+slug_name+"'><input hidden name='content_tag[]' value='{"+'"'+"slug_name"+'"'+":"+'"'+slug_name+'"'+", "+'"'+"tag_name"+'"'+":"+'"'+tag_name+'"'+"}'><a class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+")'>"+tag_name+"</a></div>");
        }

        lengValidatorEvent('75', 'title');
    }

    function removeSelectedTag(slug_name, tag_name){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#tag_holder").append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+"slct"+'"'+")'>"+tag_name+"</a>");

        lengValidatorEvent('75', 'title');
    }

    function clean(){
        slct_list = [];
        $("#tag_holder").empty();
        $("#slct_holder").empty();
    }
</script>