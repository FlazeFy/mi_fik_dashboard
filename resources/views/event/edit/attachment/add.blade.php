<form class="mb-3" action="/event/edit/update/attach/add/{{$c->slug_name}}" method="POST">
    @csrf
    <a class="content-add mb-2" style="float:none;" onclick="addAttachmentForm()"><i class="fa-solid fa-plus"></i> Add Attachment</a>
    <div class="attachment-holder" id="attachment-holder">
    </div>
    <input hidden id="content_attach" name="content_attach">
    <span id="btn-submit-holder-event"></span>
</form> 

<script>
    //Initial variable.
    var attach_list = []; //Store all attachment.

    function addAttachmentForm(){
        var id = getAttCode()

        let obj = {
            "id": id,
            "attach_type":null, 
            "attach_name":null, 
            "attach_url":null
        };

        attach_list.push(obj);

        $("#attachment-holder").append(' ' +
            '<div class="attachment-item p-2 shadow" id="attachment_container_'+id+'" style="--circle-attach-color-var:#808080;"> ' + 
                '<div style="white-space:normal !important;"> ' +
                    '<span class="d-inline-block me-1"> ' +
                        '<h6 class="mt-1">Attachment Type : </h6> ' +
                    '</span> ' +
                    '<span class="d-inline-block"> ' +
                        '<select class="form-select attachment" id="attach_type_'+id+'" name="attach_type" onChange="getAttachmentInput('+"'"+id+"'"+', this.value, false)" aria-label="Default select example"> ' +
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
                '<a class="btn btn-icon-preview" title="Preview Attachment" data-bs-toggle="collapse" href="#collapsePreview-'+id+'"> ' +
                    '<i class="fa-regular fa-eye-slash"></i></a> ' +
                '<a class="attach-upload-status success" id="attach-progress-'+id+'"></a>' +
                '<a class="attach-upload-status danger" id="attach-failed-'+id+'"></a>' +
                '<a class="attach-upload-status warning" id="attach-warning-'+id+'"></a>' +
                '<span id="preview_holder_'+id+'"></span> ' +
            '</div>');
    }

    function setValue(id, all){
        objIndex = attach_list.findIndex((obj => obj.id == id));

        let att_type = document.getElementById('attach_type_'+id).value;

        if(all){
            var att_name = document.getElementById('attach_name_'+id).value;
            //var att_url = document.getElementById('attach_url_'+id).value;
            var att_cont = document.getElementById('attachment_container_'+id);
            var submitHolder = $("#btn-submit-holder-event");
            
            if(att_type != "attachment_url"){
                var att_file_src = document.getElementById('attach_url_'+id).files[0];
                var filePath = att_type + '/' + getUUID();

                //Set upload path
                var storageRef = firebase.storage().ref(filePath);
                var uploadTask = storageRef.put(att_file_src);

                //Do upload
                uploadTask.on('state_changed',function (snapshot) {
                    var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                    document.getElementById('attach-progress-'+id).innerHTML = "File upload is " + progress + "% done";
                    if(progress == 100){
                        att_cont.style = "border-left: 3.5px solid #09c568 !important; --circle-attach-color-var:#09c568 !important;";
                        submitHolder.html('<button class="btn btn-submit mt-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>');
                    } else {
                        submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }, 
                function (error) {
                    console.log(error.message);
                    document.getElementById('attach-failed-'+id).innerHTML = "File upload is " + error.message;
                    var att_url = null;
                    if(error.message){
                        att_cont.style = "border-left: 3.5px solid #E74645 !important; --circle-attach-color-var:#E74645 !important;";
                        submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }, 
                function () {
                    uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                        var att_url = downloadUrl;
                        attach_list[objIndex]['attach_url'] =  downloadUrl;
                        if(att_type == "attachment_image"){
                            var att_preview_elmt = "<img class='img img-fluid mx-auto rounded mt-2' src='" + downloadUrl + "' alt='" + downloadUrl + "'>";
                        } else if(att_type == "attachment_video"){
                            var att_preview_elmt = "<video controls class='rounded w-100 mx-auto mt-2' alt='" + downloadUrl + "'> " +
                                "<source src='" + downloadUrl + "'> " +
                            "</video>";
                        }
                        var preview_elmt = "<div class='collapse' id='collapsePreview-" + id + "'> " +
                                "<div class='container w-100 m-0 p-0'> " +
                                    att_preview_elmt +
                                "</div> " +
                            "</div>";
                        document.getElementById('preview_holder_' + id).innerHTML = preview_elmt;
                        document.getElementById('attach_url_holder_'+id).value = downloadUrl;
                    });
                });
            } else {
                var att_url = document.getElementById('attach_url_'+id).value.trim();
            
                if(att_url.length > 0){
                    att_cont.style = "border-left: 3.5px solid #09c568 !important; --circle-attach-color-var:#09c568 !important;";
                    warningAttMsg = document.getElementById('attach-warning-'+id);
                    if(isValidURL(att_url)){
                        warningAttMsg.style = "color: #09c568 !important;"
                        warningAttMsg.innerHTML = "URL is valid";
                        submitHolder.html('<button class="btn btn-submit mt-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>');
                    } else {
                        warningAttMsg.innerHTML = "URL isn't not valid!";
                        submitHolder.html('<button disabled class="btn btn-submit mt-2"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                } else {
                    warningAttMsg.innerHTML = "";
                    att_cont.style = "border-left: 3.5px solid #808080 !important; --circle-attach-color-var:#808080 !important;";
                }   
            }
            
            // att_url = att_url.replace(/\\/g, '');
            // att_url = att_url.replace("C:fakepath", "");
        } else {
            var att_name = null;
            var att_url = null;
        }
        
        attach_list[objIndex] = {
            "id": id,
            "attach_type": att_type, 
            "attach_name": att_name, 
            "attach_url": att_url
        };

        console.log(attach_list);

        document.getElementById('content_attach').value = JSON.stringify(attach_list);
    }

    function isValidURL(urlString){
        try { 
            return Boolean(new URL(urlString)); 
        }
        catch(e){ 
            return false; 
        }
    }

    function getUUID() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    function getAttCode() {
        let col = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let code = '';
        for (let i = 0; i < 6; i++) {
            let index = Math.floor(Math.random() * col.length);
            code += col[index];
        }
        return code;
    }

    function deleteAttachmentForm(index){
        let att_type = document.getElementById('attach_type_'+index).value;

        attach_list = attach_list.filter(object => {
            if(att_type != "attachment_url"){
                var filePath = object.attach_url;
                if(filePath){
                    var storageRef = firebase.storage();
                    var desertRef = storageRef.refFromURL(filePath);
                    var msg = ""

                    desertRef.delete().then(() => {
                        msg = "Attachment has been removed";
                        //Return msg not finished. i dont know what to do next LOL
                    }).catch((error) => {
                        msg = "Failed to deleted the Attachment";
                        //Return msg not finished. i dont know what to do next LOL
                    });
                }
            } 

            $("#attachment_container_"+index).remove();

            return object.id !== index;
        });
    }

    function getAttachmentInput(index, val){
        $("#attach-input-holder-"+index).html("");
        setValue(index);

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
                '<input type="text" id="attach_url_'+index+'" name="attach_url" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)" required> ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)">');
        } else {
            $("#attach-input-holder-"+index).append(' ' +
                '<input type="file" id="attach_url_'+index+'" name="attach_input[]" class="form-control m-2" '+allowed+' onblur="setValue('+"'"+index+"'"+', true)"> ' +
                '<input type="text" id="attach_url_holder_'+index+'" hidden required> ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)">');
        }
    }
</script>