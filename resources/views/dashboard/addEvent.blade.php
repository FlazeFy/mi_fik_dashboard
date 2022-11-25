<style>
    .btn-quick-action{
        border-radius:6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:15vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        transition: 0.5s;
        text-align:left;
        padding:10px;
    }
    .btn-quick-action:hover{
        background: #F78A00 !important;
        background-image:none !important;
    }
    .quick-action-text{
        font-size:24px;
        color:white;
        transition: 0.5s;
        margin-top:9vh;
    }
    .quick-action-info{
        font-size:16px;
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
    .btn-tag{
        background:white;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:#414141;
        font-weight:400;
        border:1.5px solid #F78A00;
    }
    .btn-tag:hover, .btn-tag-selected{
        background:#F78A00;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:whitesmoke !important;
    }

    /*Richtext header*/
    .ql-toolbar.ql-snow{
        border:1.7px solid #F78A00;
        margin-top:10px;
        border-radius:10px 10px 0 0;
    }
    .ql-snow .ql-stroke {
        stroke:#414141;
    }
    .ql-snow.ql-toolbar button{
        border-radius:6px;
        height:25px;
        width:25px;
        padding-left:3px;
        margin-right:10px;
        transition: all 0.4s;
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke{
        stroke:#F78A00;
    }
    button.ql-active{
        background:#F78A00 !important;
    }
    button.ql-active svg .ql-stroke{
        stroke:white !important;
    }

    /*Richtext body*/
    .ql-toolbar.ql-snow + .ql-container.ql-snow{
        height:30vh;
    }
</style>

@if(session()->get('active_nav') == "dashboard")
    <button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("http://127.0.0.1:8000/assets/event.png"); background-color:#FB5E5B;'
        data-bs-target="#addEventModal" data-bs-toggle="modal">
        <h5 class="quick-action-text">Add Event</h5>
        <p class="quick-action-info">Event is a bla bla....</p>
    </button>
@endif

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">   
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Create Event</h5>
                <div class="row my-2">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control nameInput" id="tagNameInput" name="event_title" oninput="validateInput()" maxlength="35" required>
                                    <label for="tagNameInput">Event Title</label>
                                </div>
                                <a id="tagName_msg" class="text-danger"></a>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating">
                                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                        <option value="0">None</option>
                                        <option value="1">1 hr before</option>
                                        <option value="2">3 hr before</option>
                                        <option value="3">1 day before</option>
                                        <option value="4">3 day before</option>
                                    </select>
                                    <label for="floatingSelect">Reminder</label>
                                </div>
                            </div>
                        </div>
                        <div id="rich_box">
                            <p>Hello World!</p>
                            <p>Some initial <strong>bold</strong> text</p>
                            <p><br></p>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-8">
                                <label>Event Tag</label>
                                <div id="tag_holder"></div>

                                <label>Selected Tag</label>
                                <div id="slct_holder"></div>
                            </div>
                            <div class="col-lg-4">
                                <label>Set Date Start</label>
                                <input type="date" name="date_start" class="form-control mb-2">

                                <label>Set Date End</label>
                                <input type="date" name="date_end" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-8">
                                
                            </div>
                            <div class="col-lg-4">
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label>Event Location</label>
                        <div id="map"></div>

                        <label>Attachment</label>
                    </div>
                </div>
                <p style="font-weight:400;"><i class="fa-solid fa-circle-info text-primary"></i> ...</p>
            
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Initial variable.
    var tag_list = []; //Store all tag from db to js arr.
    var slct_list = []; //Store all tag's id.

    tag_list = [
        <?php 
            foreach($tag as $tg){
                //Insert tag name to new array.
                echo '{"id":'.$tg->id.', "tag_name":"'.$tg->tag_name.'"},';
            }
        ?>];
    
    tag_list.map((val, index) => {
        $("#tag_holder").append("<button class='btn btn-tag' title='Select this tag' onclick='addSelectedTag("+val['id']+", "+'"'+val['tag_name']+'"'+")'>"+val['tag_name']+"</button>");
    });

    function addSelectedTag(id, tag_name){
        var found = false;

        if(slct_list.length > 0){
            //Check if tag is exist in selected tag.
            slct_list.map((val, index) => {
                if(val == id){
                    found = true;
                }
            });

            if(found == false){
                slct_list.push(id);
                $("#slct_holder").append("<div class='d-inline' id='tagger"+id+"'><input hidden name='tag[]' value='{"+'"'+"id"+'"'+":"+id+", "+'"'+"tag_name"+'"'+":"+tag_name+"}'><button class='btn btn-tag-selected' title='Select this tag' onclick='removeSelectedTag("+id+", "+'"'+tag_name+'"'+")'>"+tag_name+"</button></div>");
            }
        } else {
            slct_list.push(id);
            $("#slct_holder").append("<div class='d-inline' id='tagger"+id+"'><input hidden name='tag[]' value='{"+'"'+"id"+'"'+":"+id+", "+'"'+"tag_name"+'"'+":"+tag_name+"}'><button class='btn btn-tag-selected' title='Unselect this tag' onclick='removeSelectedTag("+id+")'>"+tag_name+"</button></div>");
        }

    }

    function removeSelectedTag(id){
        var tag = document.getElementById('tagger'+id);
        slct_list = slct_list.filter(function(e) { return e !== id })
        tag.parentNode.removeChild(tag);
    }
</script>