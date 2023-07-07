<style>
    .content-image-holder{
        position: relative;
        margin-top: 6px;
        margin-bottom: 6px; 
    }
    .content-image-holder .content-image{
        margin-inline: auto;
        display: block;
        border-radius: var(--roundedSM) !important;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: var(--darkColor);
        height:200px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .image-upload{
        position: absolute;
        bottom: 3px;
        right: 10px;
    }
    .image-upload>input {
        display: none;
    }
    .btn.change-image{
        width:40px; 
        height:40px; 
        background:#F78A00;
        border-radius: var(--roundedCircle);
        display: block;
        margin-inline: auto;
    }
    .content-image-holder .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 10px;
        background: #e74645 !important;
        color:#FFFFFF !important;
    }
    .content-image-holder .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
    }
</style>

<div class="content-image-holder">
    <img id="frame" class="content-image img img-fluid" src="{{asset('assets/default_content.jpg')}}">
    <div class='image-upload' id='formFileImg'>
        <label for='file-input'>
            <img class='btn change-image shadow position-relative p-1' title='Change Image' src="{{asset('assets/change_image.png')}}"/>
        </label>
        <input id='file-input' type='file' accept="image/*" value="" onchange='setValueContentImage()'/>
    </div>
    <input hidden type="text" name="content_image" id="content_image_url" value="">
    <a class="btn btn-icon-reset-image shadow" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can"></i></a>
    <span class="status-holder shadow">
        <a class="attach-upload-status success" id="header-progress"></a>
        <a class="attach-upload-status danger" id="header-failed"></a>
        <a class="attach-upload-status warning" id="header-warning"></a>
    </span>
</div>

<script>
    var uploadedContentImageUrl = ""
    function clearImage() {
        document.getElementById('formFileImg').value = null;
        document.getElementById('frame').src = "{{asset('assets/default_content.jpg')}}";
        document.getElementById('content_image_url').value = "{{asset('assets/default_content.jpg')}}";

        if(uploadedContentImageUrl && uploadedContentImageUrl != ""){
            var storageRef = firebase.storage();
            var desertRef = storageRef.refFromURL(uploadedContentImageUrl);

            desertRef.delete().then(() => {
                document.getElementById('header-progress').innerHTML = "Content image has been removed";
                uploadedContentImageUrl = ""
            }).catch((error) => {
                document.getElementById('header-failed').innerHTML = "Failed to deleted the image";
            });
        }        
    }

    function setValueContentImage(){
        var cheader_file_src = document.getElementById('file-input').files[0];
        var filePath = 'content_image/' + getUUID();

        //Set upload path
        var storageRef = firebase.storage().ref(filePath);
        var uploadTask = storageRef.put(cheader_file_src);

        //Do upload
        uploadTask.on('state_changed',function (snapshot) {
            var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
            document.getElementById('header-progress').innerHTML = "File upload is " + progress + "% done";
        }, 
        function (error) {
            console.log(error.message);
            document.getElementById('header-failed').innerHTML = "File upload is " + error.message;
            var cheader_url = null;
        }, 
        function () {
            uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
               
                document.getElementById('frame').src = downloadUrl;
                document.getElementById('content_image_url').value = downloadUrl;
                uploadedContentImageUrl = downloadUrl;
            });
        });
    }
</script>

