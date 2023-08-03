<form class="mb-3" action="/event/edit/update/attach/add/{{$c->slug_name}}" method="POST">
    @csrf
    <input hidden name="content_title" value="{{$c->content_title}}">
    <a class="btn position-absolute text-info" style="top:-10px; right:0;" onclick="addAttachmentForm()"><i class="fa-solid fa-plus"></i> Add Attachment</a>
    <div class="attachment-holder" id="attachment-holder">
    </div>
    <input hidden id="content_attach" name="content_attach">
    <span class="mt-4" id="btn-submit-holder-event"></span>
</form> 

<script>
    //Initial variable.
    var attach_list = []; //Store all attachment.
    var maxSizeImage = 4; // Mb
    var maxSizeVideo = 20; // Mb
    var maxSizeDoc = 15; // Mb

    function addAttachmentForm(){
        if(isAddMoreAttachment(attach_list)){
            var id = getAttCode();

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
                            '<select class="form-select attachment" id="attach_type_'+id+'" name="attach_type" onChange="getAttachmentInput('+"'"+id+"'"+', this.value)" aria-label="Default select example"> ' +
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
        }
    }

    function setValue(id, all){
        objIndex = attach_list.findIndex((obj => obj.id == id));

        let att_type = document.getElementById('attach_type_'+id).value;

        if(all){
            var att_name = document.getElementById('attach_name_'+id).value;
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
                    document.getElementById('attach-failed-'+id).innerHTML = "";

                    //Set upload path
                    var storageRef = firebase.storage().ref(filePath);
                    var uploadTask = storageRef.put(att_file_src);

                    //Do upload
                    uploadTask.on('state_changed',function (snapshot) {
                        var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                        document.getElementById('attach-progress-'+id).innerHTML = "File upload is " + progress + "% done";
                        document.getElementById('attach_url_'+id).disabled = true;
                        document.getElementById('attach_type_'+id).disabled = true;

                        if(progress == 100){
                            attach_list[objIndex]['is_add_more'] = true;
                            document.getElementById('attach_name_'+id).disabled = false;
                            att_cont.style = "border-left: 3.5px solid var(--successBG) !important; --circle-attach-color-var:var(--successBG) !important;";
                            submitHolder.html('<button class="btn btn-submit mt-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>');
                        } else {
                            attach_list[objIndex]['is_add_more'] = false;
                            document.getElementById('attach_name_'+id).disabled = true;
                            submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
                        }
                    }, 
                    function (error) {
                        doErrorAttachment(id, error);
                        var att_url = null;
                        attach_list[objIndex]['is_add_more'] = false;
                        if(error.message){
                            att_cont.style = "border-left: 3.5px solid var(--warningBG) !important; --circle-attach-color-var:var(--warningBG) !important;";
                            submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
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
                            attach_list[objIndex]['attach_name'] = att_name;
                            attach_list[objIndex]['attach_url'] = att_url;

                            var modifiedList = cleanAddMoreStatus(attach_list);
                            document.getElementById('content_attach').value = JSON.stringify(modifiedList);
                        });
                    });
                } else {
                    attach_list[objIndex]['is_add_more'] = false;
                    document.getElementById('attach_name_'+id).disabled = true;
                    document.getElementById('attach-failed-'+id).innerHTML = "Maximum file size is " + max + " mb";
                    var att_url = null;
                    if(error.message){
                        att_cont.style = "border-left: 3.5px solid var(--warningBG) !important; --circle-attach-color-var:var(--warningBG) !important;";
                        submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }
            } else if(att_type == "attachment_url" && att_dsbld != true) {
                attach_list[objIndex]['is_add_more'] = true;
                var att_url = document.getElementById('attach_url_'+id).value.trim();
            
                if(att_url.length > 0){
                    warningAttMsg = document.getElementById('attach-warning-'+id);
                    attach_list[objIndex]['id'] = id;
                    attach_list[objIndex]['attach_type'] = att_type;
                    attach_list[objIndex]['attach_name'] = att_name;
                    attach_list[objIndex]['attach_url'] = att_url
                    
                    if(isValidURL(att_url)){
                        att_cont.style = "border-left: 3.5px solid var(--successBG) !important; --circle-attach-color-var:var(--successBG) !important;";
                        warningAttMsg.style = "color: var(--successBG) !important;";
                        warningAttMsg.innerHTML = "URL is valid";
                    } else {
                        att_cont.style = "border-left: 3.5px solid var(--primaryColor) !important; --circle-attach-color-var:var(--primaryColor) !important;";
                        warningAttMsg.innerHTML = "URL isn't not valid!";
                    }
                    submitHolder.html('<button class="btn btn-submit mt-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>');
                } else {
                    warningAttMsg.innerHTML = "";
                    att_cont.style = "border-left: 3.5px solid var(--shadowColor) !important; --circle-attach-color-var:var(--shadowColor) !important;";
                }   
            } else {
                attach_list[objIndex]['id'] = id;
                attach_list[objIndex]['attach_type'] = att_type;
                attach_list[objIndex]['attach_name'] = att_name;
            }
        } else {
            var att_name = null;
            var att_url = null;
        }

        var modifiedList = cleanAddMoreStatus(attach_list);
        document.getElementById('content_attach').value = JSON.stringify(modifiedList);
    }

    function deleteAttachmentForm(index){
        let att_type = document.getElementById('attach_type_'+index).value;
        attach_list = removeAttachment(att_type, attach_list, index);

        if(attach_list.length == 0){
            $("#btn-submit-holder-event").empty();
        }

        var modifiedList = cleanAddMoreStatus(attach_list);
        document.getElementById('content_attach').value = JSON.stringify(modifiedList);
    }
</script>