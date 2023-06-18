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
        width:40px; 
        height:40px; 
        background:#F78A00;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
    .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 10px;
        background: #e74645 !important;
        color:#FFFFFF !important;
    }
    .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
    }
</style>

<span class="position-relative">
    @if(session()->get('profile_pic') == null)
        @if(session()->get('role_key') == 0)
            <img class="img img-fluid rounded-circle shadow mx-4" style="max-width:40%;" src="{{ asset('/assets/default_lecturer.png')}}" id="profile_image_info">
        @elseif(session()->get('role_key') == 1)
            <img class="img img-fluid rounded-circle shadow mx-4" style="max-width:40%;" src="{{ asset('/assets/default_admin.png')}}" id="profile_image_info">
        @endif
    @else
        <img class="img img-fluid rounded-circle shadow mx-4" style="max-width:40%;" src="{{session()->get('profile_pic')}}" alt="{{session()->get('profile_pic')}}" id="profile_image_info">
    @endif
    <div class='image-upload' id='formFileImg'>
        <label for='file-input'>
            <span class="btn-change-image" id="btn-change-image"><img class="img img-fluid" src="{{ asset('/assets/camera.png')}}"></span>
        </label>
        <input id='file-input' type='file' accept="image/*" value="" onchange='setValueProfileImage()'/>
    </div>
    <form id="form-image" class="d-inline">
        <input hidden type="text" name="image_url" id="profile_image_url" value="">
    </form>
    @if(session()->get("profile_pic") != null)
        <a class="btn btn-reset-image" id="btn-reset-image" style="top: 47.5px; right: -27.5px;" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can fa-lg"></i></a>
    @endif
    <span class="status-holder">
        <span class="attach-upload-status success" id="header-progress"></span>
        <a class="attach-upload-status failed" id="header-failed"></a>
        <a class="attach-upload-status warning" id="header-warning"></a>
    </span>
</span>

<script>        
    function setValueProfileImage(){
        var file_src = document.getElementById('file-input').files[0];
        var maxSize = 4; // Mb

        if(file_src.size <= maxSize * 1024 * 1024){
            var filePath = 'profile_image/' + getUUID();

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
                    document.getElementById('profile_image_info').src = downloadUrl;
                    document.getElementById('profile_image_url').value = downloadUrl;
                    edit_image();
                });
            });
        } else {
            document.getElementById('header-failed').innerHTML = "<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>Upload failed. Maximum file size is " + maxSize + " mb </h6></span>";
        }
    }

    function clearImage() {
        var storageRef = firebase.storage();
        var desertRef = storageRef.refFromURL('<?= session()->get("profile_pic"); ?>');

        desertRef.delete().then(() => {
            document.getElementById("header-progress").innerHTML = '<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Success.png"><h6>Profile image has been set to default</h6></span>';
        }).catch((error) => {
            document.getElementById("header-failed").innerHTML = '<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Failed.png"><h6>'+error+'</h6></span>';
        });

        document.getElementById("profile_image_url").value = null;
        edit_image();
    }

    function edit_image(){
        $.ajax({
            url: '/api/v1/user/update/image',
            type: 'PUT',
            data: $('#form-image').serialize(),
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
                        if(response.responseJSON.result.hasOwnProperty('image_url')){
                            errorMsg = response.responseJSON.result.image_url[0];
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
</script>

<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<script>
    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyD2gQjgUllPlhU-1GKthMcrArdShT2AIPU",
        authDomain: "mifik-83723.firebaseapp.com",
        projectId: "mifik-83723",
        storageBucket: "mifik-83723.appspot.com",
        messagingSenderId: "38302719013",
        appId: "1:38302719013:web:23e7dc410514ae43d573cc",
        measurementId: "G-V13CR730JG"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>