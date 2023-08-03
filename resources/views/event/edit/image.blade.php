<style>
    .image-upload{
        position: absolute;
        bottom: 3px;
        right: 10px;
    }
    .image-upload>input {
        display: none;
    }
    .btn.change-image{
        width: 40px; 
        height: 40px; 
        background:var(--primaryColor);
        border-radius: var(--roundedCircle);
        position: absolute;
        right: 10px;
        bottom: 20px;
    }
    .btn-reset-image{
        position: absolute; 
        right: 100px;
        bottom: -25px;
        background: var(--warningBG) !important;
        color:var(--whiteColor) !important;
    }
    .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
        top: 20px !important;
    }
</style>

<div class="position-relative">
    @if($c->content_image)
        <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('{{$c->content_image}}');" id="event-header-image">
            <div class="d-flex justify-content-between py-3 px-2">
                <div>
                    <a class="event-header-size-toogle" title="Resize image" onclick="resize('<?php echo $c->content_image; ?>')"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></a>
                </div>
                @if($isMobile)
                    <div style="white-space:nowrap;">
                        <form action="/event/edit/update/draft/{{$c->slug_name}}" method="POST" class="d-inline">
                            <input hidden name="content_title" value="{{$c->content_title}}">
                            @csrf
                            @if($c->is_draft == 1)
                                <input hidden name="is_draft" value="0">
                                <button class="btn btn-success navigator-right rounded-pill px-4 py-2" style="right:0" title="Unset draft" type="submit"><i class="fa-regular fa-eye"></i> Public</button>
                            @else 
                                <input hidden name="is_draft" value="1">
                                <button class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:0" title="Set draft" type="submit"><i class="fa-solid fa-eye-slash"></i> Draft</button>
                            @endif
                        </form>
                        <a class="btn btn-danger navigator-right rounded-pill px-4 py-2" style="right:170px" onclick="location.href='/event/detail/{{$c->slug_name}}'" title="Close" ><i class="fa-solid fa-xmark fa-lg"></i></a>
                    </div>
                @endif
            <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
        </div>
    @else
        <div class="event-detail-img-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/default_content.jpg')}});" id="event-header-image">
            <div class="d-flex justify-content-between py-3 px-2">
                <div>
                    <a class="event-header-size-toogle" title="Resize image" onclick="resize(null)"> <i class="fa-solid fa-up-right-and-down-left-from-center fa-lg"></i></a>
                </div>
                @if($isMobile)
                    <div style="white-space:nowrap;">
                        <form action="/event/edit/update/draft/{{$c->slug_name}}" method="POST" class="d-inline">
                            <input hidden name="content_title" value="{{$c->content_title}}">
                            @csrf
                            @if($c->is_draft == 1)
                                <input hidden name="is_draft" value="0">
                                <button class="btn btn-success navigator-right rounded-pill px-4 py-2" style="right:0" title="Unset draft" type="submit"><i class="fa-regular fa-eye"></i> Set as Public</button>
                            @else 
                                <input hidden name="is_draft" value="1">
                                <button class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:0" title="Set draft" type="submit"><i class="fa-solid fa-eye-slash"></i> Set as draft</button>
                            @endif
                        </form>
                        <a class="btn btn-danger navigator-right rounded-pill px-4 py-2" style="right:170px" onclick="location.href='/event/detail/{{$c->slug_name}}'" title="Close" ><i class="fa-solid fa-xmark fa-lg"></i></a>
                    </div>
                @endif
            </div>
            <div class="content-detail-views"><i class='fa-solid fa-eye'></i> {{$c->total_views}}</div>
        </div>
    @endif
        <div class='image-upload' id='formFileImg'>
            <label for='file-input'>
                <span class="btn-change-image" style="top:0 !important;" id="btn-change-image"><img class="img img-fluid" src="{{ asset('/assets/camera.png')}}"></span>
            </label>
            <input id='file-input' type='file' accept="image/*" value="" onchange='setContentImage()'/>
        </div> 
        <form id="form-image-content" class="d-inline">
            <input hidden type="text" name="content_image" id="content_image_url" value="">
        </form>
    @if($c->content_image != null)
        <a class="btn btn-reset-image" id="btn-reset-image" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can fa-lg"></i></a>
    @endif
    <span class="status-holder">
        <span class="attach-upload-status success" id="header-progress"></span>
        <a class="attach-upload-status failed" id="header-failed"></a>
        <a class="attach-upload-status warning" id="header-warning"></a>
    </span>
</div>

<script>        
    function setContentImage(){
        var file_src = document.getElementById('file-input').files[0];
        var maxSize = 4; // Mb

        if(file_src.size <= maxSize * 1024 * 1024){
            var filePath = 'content_image/' + getUUID();

            var storageRef = firebase.storage().ref(filePath);
            var uploadTask = storageRef.put(file_src);

            uploadTask.on('state_changed',function (snapshot) {
                var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                document.getElementById('header-progress').innerHTML = '<span class="box-loading"><div role="progressbar" aria-valuenow="'+progress+'" aria-valuemin="0" aria-valuemax="'+progress+'" style="--value: '+progress+'"></div></span>';
            }, 
            function (error) {
                console.log(error.message);
                document.getElementById('header-failed').innerHTML = "<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>File upload is " + error.message + "</h6></span>";
            }, 
            function () {
                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                    document.getElementById('content_image_url').value = downloadUrl;
                    edit_image();
                });
            });
        } else {
            document.getElementById('header-failed').innerHTML = "<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>Upload failed. Maximum file size is " + maxSize + " mb </h6></span>";
        }
    }

    function clearImage() {
        var storageRef = firebase.storage();
        var desertRef = storageRef.refFromURL("{{$c->content_image}}");

        desertRef.delete().then(() => {
            document.getElementById("header-progress").innerHTML = '<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Success.png"><h6>Content image has been set to default</h6></span>';
        }).catch((error) => {
            document.getElementById("header-failed").innerHTML = '<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Failed.png"><h6>'+error+'</h6></span>';
        });

        document.getElementById("content_image_url").value = null;
        edit_image();
    }

    function edit_image(){
        $.ajax({
            url: '/api/v1/content/edit/image/{{$c->slug_name}}',
            type: 'PUT',
            data: $('#form-image-content').serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response) {
                document.getElementById("header-progress").innerHTML = '<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Success.png"><h6>'+response.message+'</h6></span>';
                setTimeout(() => {
                    document.getElementById("header-progress").innerHTML = "";
                    location.reload();
                }, 3000);
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    //Error validation
                    if(typeof response.responseJSON.result === "string"){
                        errorMsg = response.responseJSON.result;
                    } else {
                        if(response.responseJSON.result.hasOwnProperty('content_image')){
                            errorMsg = response.responseJSON.result.content_image[0];
                        }
                    }
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    errorMsg = response.responseJSON.errors.result[0]
                } else {
                    errorMsg = errorMessage
                }
                document.getElementById("header-failed").innerHTML = errorMsg;
            }
        });
    }

    function resize(img){
        if(img){
            var img_url = "background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('" + img + "');";
        } else {
            var img_url = "background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url('http://127.0.0.1:8000/assets/default_content.jpg');";
        }

        if(i % 2 == 0){
            document.getElementById('event-header-image').style = "height: 100vh; " + img_url;
        } else {
            document.getElementById('event-header-image').style = "height: 30vh; " + img_url;
        }
        i++;
    }
</script>