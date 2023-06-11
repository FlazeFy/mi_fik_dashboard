<div>
    <h4 class="text-primary">Available Role</h4>
    <div class="selected-role" id="slct-box" style="display:none;">
        <h6 class='mt-2 mb-0'>Selected Role</h6> 
        <div id="slct_holder"></div>
    </div>
    <a id="selected_role_msg" class="text-danger my-2" style="font-size:13px;"></a>
    <hr>
    <div class="" id="data-wrapper"></div>
    <!-- Loading -->
    <div class="auto-load text-center">
        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
    </div>
    <div id="empty_item_holder"></div>
    <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
</div>

<span id="btn-next-ready-holder">
    <button class="btn-next-steps locked" id="btn-next-ready" onclick="warn('role')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>

<script type="text/javascript">
    var page = 1;

    function loadTag() {        
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
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#empty_item_holder").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>Sorry but we not found any tag category</h6></div>");
            } else {
                // handle other errors
            }
        });
    }

    function loadTagByCat(cat) {        
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
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load-'+cat).hide();
                $("#empty_item_holder_" + cat).html("<h6 class='text-secondary text-center'>No role available</h6>");
            } else {
                // handle other errors
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