<style>
    .attachment-holder .attachment-item {
        padding: 12px 20px 12px 12px !important;
        margin-top: 15px !important;
        margin-left:15px;
        position: relative;
        border-radius: 0 10px 10px 0;
        min-height: 80px;
        border-left: 3px solid #808080;
        border-top: 1.75px solid #CED4DA;
        border-right: 1.75px solid #CED4DA;
        border-bottom: 1.75px solid #CED4DA;
    }
    .attachment-holder .attachment-item ul {
        padding-left: 12px;
    }
    .attachment-holder .attachment-item ul li {
        padding-bottom: 10px;
    }
    .attachment-holder .attachment-item:last-child {
        padding-bottom: 0;
    }
    .attachment-holder .attachment-item::before {
        content: "";
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50px;
        left: -13px;
        top: 36%;
        background: white;
        border: 3px solid #808080;
    }
    .btn-icon-delete{
        color: #F85D59 !important;
        padding: 4px 8px;
    }
    .btn-icon-delete:hover{
        background: #e74645 !important;
        color:white !important;
    }
    .btn-icon-preview{
        color: #808080 !important;
        padding: 4px 8px;
    }
    .form-select.attachment{
        padding: 4px !important;
        font-size: 13px;
        width: 100px;
    }
</style>

<div>
    <a class="content-add mb-2" style="float:none;" onclick="addAttachmentForm()"><i class="fa-solid fa-plus"></i> Add Attachment</a>
    <div class="attachment-holder" id="attachment-holder">
        

    </div>
</div>

<script>
    //Initial variable.
    var attach_list = []; //Store all attachment.
    var i = 1;

    function addAttachmentForm(){
        let obj = {
            "id": i,
            "attach_type":null, 
            "attach_name":null, 
            "attach_url":null
        };

        attach_list.push(obj);

        $("#attachment-holder").append(' ' +
            '<div class="attachment-item p-2 shadow" id="attachment_container_'+attach_list.length+'"> ' +
                '<form class="d-inline" id="attachment_form_'+attach_list.length+'"> ' +
                    '@csrf ' +
                    '<div class="row mb-1"> ' +
                        '<div class="col-6"> ' +
                            '<h6 class="mt-1">Attachment Type : </h6> ' +
                        '</div> ' +
                        '<div class="col-6"> ' +
                            '<select class="form-select attachment" id="attach_type_'+attach_list.length+'" name="attach_type" onChange="getAttachmentInput('+attach_list.length+', this.value)" aria-label="Default select example"> ' +
                                '<option selected>---</option> ' +
                                <?php
                                    foreach($dictionary as $dct){
                                        if($dct->type_name == "Attachment"){
                                            echo "'<option value=".'"'.$dct->slug_name.'"'.">".$dct->dct_name."</option> ' +";
                                        }
                                    }
                                ?>
                            '</select> ' +
                        '</div> ' +
                    '</div> ' +
                    '<div id="attach-input-holder-'+attach_list.length+'"></div> ' +
                '</form> ' +
                '<a class="btn btn-icon-delete" title="Delete" onclick="deleteAttachmentForm('+i+')"> ' +
                    '<i class="fa-solid fa-trash-can"></i></a> ' +
                '<a class="btn btn-icon-preview" title="Preview Attachment" onclick=""> ' +
                    '<i class="fa-regular fa-eye-slash"></i></a> ' +
            '</div>');

        i++;
    }

    function deleteAttachmentForm(index){
        $("#attachment_container_"+index).remove();

        attach_list = attach_list.filter(object => {
            return object.id !== index;
        });

        console.log(attach_list);
    }

    function getAttachmentInput(index, val){
        $("#attach-input-holder-"+index).html("");

        //Allowed type
        if(val == 'attachment_image'){
            var allowed = 'accept="image/*"'
        } else if(val == 'attachment_video'){
            var allowed = 'accept="video/*"'
        } else if(val == 'attachment_doc'){
            var allowed = 'accept="MIME_type/*"' //Check this again...
        }

        if(val == "attachment_url"){
            $("#attach-input-holder-"+index).append(' ' +
                '<h6 class="mt-1">Attachment URL</h6> ' +
                '<input type="text" id="attach_url" name="attach_url" class="form-control m-2"> ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name" name="attach_name" class="form-control m-2">');
        } else {
            $("#attach-input-holder-"+index).append(' ' +
                '<input type="file" id="attach_input" name="attach_input" class="form-control m-2" '+allowed+' > ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name" name="attach_name" class="form-control m-2">');
        }
    }
</script>