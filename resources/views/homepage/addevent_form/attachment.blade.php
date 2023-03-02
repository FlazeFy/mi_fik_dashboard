<style>
    .attachment-holder .attachment-item {
        padding: 12px 20px 12px 12px !important;
        margin-top: 15px !important;
        margin-left:15px;
        position: relative;
        border-radius: 0 10px 10px 0;
        min-height: 80px;
        border-left: 3.5px solid #808080;
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
        border: 3px solid var(--circle-attach-color-var);
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
    .attach-upload-status{
        text-decoration: none;
        font-style: italic;
        font-size: 12px;
        font-weight: 400;
    }
    .success{
        color: #00c363 !important;
    }
    .failed{
        color: #e74645 !important;
    }
</style>

<div>
    <a class="content-add mb-2" style="float:none;" onclick="addAttachmentForm()"><i class="fa-solid fa-plus"></i> Add Attachment</a>
    <div class="attachment-holder" id="attachment-holder">
    </div>
    <input hidden id="content_attach" name="content_attach">
</div>

<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyCN3J8nXpP1NuHwX7NjfYpMWkNGPzfV0X0",
        authDomain: "mifik-ad2d9.firebaseapp.com",
        projectId: "mifik-ad2d9",
        storageBucket: "mifik-ad2d9.appspot.com",
        messagingSenderId: "96469457737",
        appId: "1:96469457737:web:f70e18e5dcfe41c66bd898",
        measurementId: "G-PZDGL9X7T1"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>

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
            '<div class="attachment-item p-2 shadow" id="attachment_container_'+i+'" style="--circle-attach-color-var:#808080;"> ' + 
                '<div class="row mb-1"> ' +
                    '<div class="col-6"> ' +
                        '<h6 class="mt-1">Attachment Type : </h6> ' +
                    '</div> ' +
                    '<div class="col-6"> ' +
                        '<select class="form-select attachment" id="attach_type_'+i+'" name="attach_type" onChange="getAttachmentInput('+i+', this.value, false)" aria-label="Default select example"> ' +
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
                '<div id="attach-input-holder-'+i+'"></div> ' +
                '<a class="btn btn-icon-delete" title="Delete" onclick="deleteAttachmentForm('+i+')"> ' +
                    '<i class="fa-solid fa-trash-can"></i></a> ' +
                '<a class="btn btn-icon-preview" title="Preview Attachment" onclick=""> ' +
                    '<i class="fa-regular fa-eye-slash"></i></a> ' +
                '<a class="attach-upload-status success" id="attach-progress-'+i+'"></a>' +
                '<a class="attach-upload-status danger" id="attach-failed-'+i+'"></a>' +
            '</div>');
        i++;
    }

    function setValue(id, all){
        objIndex = attach_list.findIndex((obj => obj.id == id));

        let att_type = document.getElementById('attach_type_'+id).value;

        if(all){
            var att_name = document.getElementById('attach_name_'+id).value;
            //var att_url = document.getElementById('attach_url_'+id).value;
            var att_cont = document.getElementById('attachment_container_'+id);
            
            if(att_type != "attachment_url"){
                var att_file_src = document.getElementById('attach_url_'+id).files[0];
                var fileName = att_file_src.name;

                //Set upload path
                var storageRef = firebase.storage().ref(att_type + '/' + fileName);
                var uploadTask = storageRef.put(att_file_src);

                //Do upload
                uploadTask.on('state_changed',function (snapshot) {
                    var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                    document.getElementById('attach-progress-'+id).innerHTML = "File upload is " + progress + "% done";
                    if(progress == 100){
                        att_cont.style = "border-left: 3.5px solid #09c568 !important; --circle-attach-color-var:#09c568 !important;";
                        $("#btn-submit-holder-event").html('<button type="submit" onclick="getRichText()" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
                    } else {
                        $("#btn-submit-holder-event").html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }, 
                function (error) {
                    console.log(error.message);
                    document.getElementById('attach-failed-'+id).innerHTML = "File upload is " + error.message;
                    var att_url = null;
                    if(error.message){
                        att_cont.style = "border-left: 3.5px solid #E74645 !important; --circle-attach-color-var:#E74645 !important;";
                        $("#btn-submit-holder-event").html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
                    }
                }, 
                function () {
                    uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                        var att_url = downloadUrl;
                        attach_list[objIndex]['attach_url'] =  downloadUrl;
                    });
                });
            } else {
                var att_url = document.getElementById('attach_url_'+id).value;
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

    function deleteAttachmentForm(index){
        var att_url = document.getElementById('attach_url_'+index).value;
            let att_type = document.getElementById('attach_type_'+index).value;
        $("#attachment_container_"+index).remove();

        attach_list = attach_list.filter(object => {
            

            att_url = att_url.replace(/\\/g, '');
            att_url = att_url.replace("C:fakepath", "");

            const storage = getStorage();

            // Create a reference to the file to delete
            const desertRef = firebase.storage().ref(att_type + '/' + att_url);

            // Delete the file
            deleteObject(desertRef).then(() => {
                // File deleted successfulls
                console.log("success");
            }).catch((error) => {
                // Uh-oh, an error occurred!
                console.log("failed");
            });

            return object.id !== index;
        });

        lengValidatorEvent('75', 'title');
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
                '<input type="text" id="attach_url_'+index+'" name="attach_url" class="form-control m-2" onblur="setValue('+index+', true)" required> ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+index+', true)">');
        } else {
            $("#attach-input-holder-"+index).append(' ' +
                '<input type="file" id="attach_url_'+index+'" name="attach_input[]" class="form-control m-2" '+allowed+' onblur="setValue('+index+', true)"> ' +
                '<h6 class="mt-1">Attachment Name</h6> ' +
                '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+index+', true)">');
        }
    }
</script>