<label class="input-title">Event Tag</label>
<div id="tag_holder"></div>

<label class="input-title">Selected Tag</label>
<div id="slct_holder"></div>

<script type="text/javascript">
    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_list = []; //Store all tag's id.

    //Tag collection
    tag_list = [
        <?php 
            foreach($tag as $tg){
                echo '{"slug_name":"'.$tg->slug_name.'", "tag_name":"'.$tg->tag_name.'"},';
            }
        ?>];
    
    //Show tag collection
    tag_list.map((val, index) => {
        $("#tag_holder").append("<a class='btn btn-tag' id='tag_collection_"+val['slug_name']+"' title='Select this tag' onclick='addSelectedTag("+'"'+val['slug_name']+'"'+", "+'"'+val['tag_name']+'"'+", true, "+'"'+"slct"+'"'+")'>"+val['tag_name']+"</a>");
    });

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

        lengValidator('75', 'title');
    }

    function removeSelectedTag(slug_name, tag_name){
        //Remove selected tag
        var tag = document.getElementById('tagger_'+slug_name);
        slct_list = slct_list.filter(function(e) { return e !== slug_name })
        tag.parentNode.removeChild(tag);

        //Return selected tag to tag collection
        $("#tag_holder").append("<a class='btn btn-tag' id='tag_collection_"+slug_name+"' title='Select this tag' onclick='addSelectedTag("+'"'+slug_name+'"'+", "+'"'+tag_name+'"'+", true, "+'"'+"slct"+'"'+")'>"+tag_name+"</a>");

        lengValidator('75', 'title');
    }
</script>