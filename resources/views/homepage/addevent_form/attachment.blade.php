<div>
    <a class="btn btn-add-att mb-2" style="float:none;" id="add_att_btn" onclick="addAttachmentForm()"><i class="fa-solid fa-plus"></i> Add Attachment</a>
    <div class="attachment-holder" id="attachment-holder">
    </div>
    <input hidden id="content_attach" name="content_attach">
</div>

<script>
    //Initial variable.
    var attach_list = []; //Store all attachment.
    var maxSizeImage = 4; // Mb
    var maxSizeVideo = 20; // Mb
    var maxSizeDoc = 15; // Mb
    var err_att = false;
    var add_att_btn = document.getElementById('add_att_btn');

    function addAttachmentForm(){
        if(isAddMoreAttachment(attach_list)){
            var id = getAttCode();
            var is_addable = true;
            let obj = {
                "id": id,
                "attach_type":null, 
                "attach_name":null, 
                "attach_url":null,
                "is_add_more":false
            };
            attach_list.push(obj);

            $("#attachment-holder").append(' ' +
                '<div class="attachment-item p-2 shadow" id="attachment_container_'+id+'" style="--circle-attach-color-var:var(--shadowColor);"> ' + 
                    '<div style="white-space:normal !important;"> ' +
                        '<span class="d-inline-block me-1"> ' +
                            '<h6 class="mt-1">Attachment Type : </h6> ' +
                        '</span> ' +
                        '<span class="d-inline-block"> ' +
                            '<select class="form-select attachment" id="attach_type_'+id+'" name="attach_type" onChange="getAttachmentGroupFun('+"'"+id+"'"+', this.value, false)" aria-label="Default select example"> ' +
                                '<option selected>---</option> ' +
                                <?php
                                    foreach($dictionary as $dct){
                                        if($dct->type_name == "Attachment"){
                                            echo "'<option value=".'"'.$dct->slug_name.'"'.">".$dct->dct_name."</option> ' +";
                                        }
                                    }
                                ?>
                            '</select> ' +
                        '</span> ' +
                    '</div> ' +
                    '<div id="attach-input-holder-'+id+'"></div> ' +
                    '<a class="btn btn-icon-delete" title="Delete" onclick="deleteAttachmentForm('+"'"+id+"'"+')"> ' +
                        '<i class="fa-solid fa-trash-can"></i></a> ' +
                    '<span id="preview_att_'+id+'"><a class="btn btn-icon-preview" title="Preview Attachment" data-bs-toggle="collapse" href="#collapsePreview-'+id+'"> ' +
                        '<i class="fa-regular fa-eye-slash"></i></a></span>' +
                    '<a class="attach-upload-status success" id="attach-progress-'+id+'"></a>' +
                    '<a class="attach-upload-status failed" id="attach-failed-'+id+'"></a>' +
                    '<a class="attach-upload-status warning" id="attach-warning-'+id+'"></a>' +
                    '<span id="preview_holder_'+id+'"></span> ' +
                '</div>');
            
            add_att_btn.setAttribute("class","btn btn-add-att disabled mb-2");
            add_att_btn.innerHTML = '<i class="fa-solid fa-lock"></i> Locked';
        }
    }

    function setValue(id, all){
        objIndex = attach_list.findIndex((obj => obj.id == id));

        let att_type = document.getElementById('attach_type_'+id).value;

        if(all){
            var att_name_val = document.getElementById('attach_name_'+id).value;
            var att_dsbld = document.getElementById('attach_url_'+id).disabled;
            //var att_url = document.getElementById('attach_url_'+id).value;
            var att_cont = document.getElementById('attachment_container_'+id);
            var submitHolder = $("#btn-submit-holder-event");
            
            if(att_type != "attachment_url" && att_dsbld != true){
                var att_file_src = document.getElementById('attach_url_'+id).files[0];

                if(att_type == "attachment_video"){
                    max = maxSizeVideo;
                } else if(att_type == "attachment_image"){
                    max = maxSizeImage;
                } else if(att_type == "attachment_doc"){
                    max = maxSizeDoc;
                }

                if(att_file_src.size <= max * 1024 * 1024){
                    var filePath = att_type + '/' + getUUID();

                    //Set upload path
                    var storageRef = firebase.storage().ref(filePath);
                    var uploadTask = storageRef.put(att_file_src);

                    //Do upload
                    uploadTask.on('state_changed',function (snapshot) {
                        var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                        document.getElementById('attach-failed-'+id).innerHTML = "";
                        document.getElementById('attach-progress-'+id).innerHTML = "File upload is " + progress + "% done";
                        document.getElementById('attach_url_'+id).disabled = true;
                        document.getElementById('attach_type_'+id).disabled = true;
                        
                        if(progress == 100){
                            attach_list[objIndex]['is_add_more'] = true;
                            document.getElementById('attach_name_'+id).disabled = false;
                            att_cont.style = "border-left: 3.5px solid #09c568 !important; --circle-attach-color-var:#09c568 !important;";
                        } else {
                            attach_list[objIndex]['is_add_more'] = false;
                            document.getElementById('attach_name_'+id).disabled = true;
                            submitHolder.html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
                        }
                    }, 
                    function (error) {
                        doErrorAttachment(id, error);
                        attach_list[objIndex]['is_add_more'] = false;
                        var att_url = null;
                        if(error.message){
                            att_cont.style = "border-left: 3.5px solid #E74645 !important; --circle-attach-color-var:#E74645 !important;";
                            submitHolder.html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
                        }
                    }, 
                    function () {
                        uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                            document.getElementById('attach_url_'+id).disabled = true;
                            var att_url = downloadUrl;
                            attach_list[objIndex]['attach_url'] =  downloadUrl;
                            
                            if(att_type == "attachment_image"){
                                var att_preview_elmt = "<img class='img img-fluid mx-auto rounded mt-2' src='" + downloadUrl + "' alt='" + downloadUrl + "'>";
                            } else if(att_type == "attachment_video"){
                                var att_preview_elmt = "<video controls class='rounded w-100 mx-auto mt-2' alt='" + downloadUrl + "'> " +
                                    "<source src='" + downloadUrl + "'> " +
                                "</video>";
                            } else if(att_type == "attachment_doc"){
                                var att_preview_elmt = "<embed class='document-grid mb-2 rounded' alt='" + downloadUrl + "' style='height: 450px;' src='" + downloadUrl + "'/>";
                            }

                            var preview_elmt = "<div class='collapse' id='collapsePreview-" + id + "'> " +
                                    "<div class='container w-100 m-0 p-0'> " +
                                        att_preview_elmt +
                                    "</div> " +
                                "</div>";
                            document.getElementById('preview_holder_' + id).innerHTML = preview_elmt;
                            document.getElementById('attach_url_holder_'+id).value = downloadUrl;

                            attach_list[objIndex]['id'] = id;
                            attach_list[objIndex]['attach_type'] = att_type;
                            attach_list[objIndex]['attach_name'] = att_name_val;
                            attach_list[objIndex]['attach_url'] = att_url;
                            validateFailedAtt();
                            lengValidatorEvent('75', 'title');

                            var modifiedList = cleanAddMoreStatus(attach_list);
                            document.getElementById('content_attach').value = JSON.stringify(modifiedList);
                        });
                    });
                } else {
                    err_att = true;
                    attach_list[objIndex]['is_add_more'] = true;
                    document.getElementById('attach_name_'+id).disabled = true;
                    add_att_btn.setAttribute("class","btn btn-add-att disabled mb-2");
                    add_att_btn.innerHTML = '<i class="fa-solid fa-lock"></i> Locked';

                    document.getElementById('attach-failed-'+id).innerHTML = "Upload failed. Maximum file size is " + max + " mb";
                    var att_url = null;
                    if(error.message){
                        att_cont.style = "border-left: 3.5px solid #E74645 !important; --circle-attach-color-var:#E74645 !important;";
                        submitHolder.html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }
            } else if(att_type == "attachment_url" && att_dsbld != true) {
                attach_list[objIndex]['is_add_more'] = true;
                var att_url = document.getElementById('attach_url_'+id).value.trim();
            
                if(att_url.length > 0){
                    warningAttMsg = document.getElementById('attach-warning-'+id);
                    attach_list[objIndex]['id'] = id;
                    attach_list[objIndex]['attach_type'] = att_type;
                    attach_list[objIndex]['attach_name'] = att_name_val;
                    attach_list[objIndex]['attach_url'] = att_url;

                    if(isValidURL(att_url)){
                        att_cont.style = "border-left: 3.5px solid #09c568 !important; --circle-attach-color-var:#09c568 !important;";
                        warningAttMsg.style = "color: #09c568 !important;";
                        warningAttMsg.innerHTML = "URL is valid";
                    } else {
                        att_cont.style = "border-left: 3.5px solid var(--primaryColor) !important; --circle-attach-color-var:var(--primaryColor) !important;";
                        warningAttMsg.style = "color: #f78a00 !important;";
                        warningAttMsg.innerHTML = "URL isn't not valid!";
                    }
                    validateFailedAtt();
                } else {
                    warningAttMsg.innerHTML = "";
                    att_cont.style = "border-left: 3.5px solid var(--shadowColor) !important; --circle-attach-color-var:var(--shadowColor) !important;";
                }   
            } else {
                attach_list[objIndex]['id'] = id;
                attach_list[objIndex]['attach_type'] = att_type;
                attach_list[objIndex]['attach_name'] = att_name_val;
            }
        } else {
            var att_name_val = null;
            var att_url = null;
        }

        lengValidatorEvent('75', 'title');
        var modifiedList = cleanAddMoreStatus(attach_list);
        document.getElementById('content_attach').value = JSON.stringify(modifiedList);
    }

    function getAttachmentGroupFun(index, val){
        getAttachmentInput(index, val);
        validateFailedAtt();
    }

    function validateFailedAtt(){
        var found = false;
        attach_list.forEach(e => {
            if(e.is_add_more == false){
                found = true;
            }
        });

        if(!found){
            add_att_btn.setAttribute("class","btn btn-add-att mb-2");
            add_att_btn.innerHTML = '<i class="fa-solid fa-plus"></i> Add Attachment';
        } else {
            add_att_btn.setAttribute("class","btn btn-add-att disabled mb-2");
            add_att_btn.innerHTML = '<i class="fa-solid fa-lock"></i> Locked';
        }
    }

    function deleteAttachmentForm(index){
        let att_type = document.getElementById('attach_type_'+index).value;
        attach_list = removeAttachment(att_type, attach_list, index);
        validateFailedAtt();

        var modifiedList = cleanAddMoreStatus(attach_list);
        document.getElementById('content_attach').value = JSON.stringify(attach_list);
        lengValidatorEvent('75', 'title');
    }
</script>