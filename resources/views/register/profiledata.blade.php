<script>
    let validation = [
        { id: "username", req: true, len: 35 },
        { id: "email", req: true, len: 75 },
    ];
    let validation2 = [
        { id: "password", req: true, len: 75 },
        { id: "first_name", req: true, len: 75 },
        { id: "last_name", req: false, len: 75 },
    ];
    var val1 = false; 
    var val2 = false;
</script>

<style>
    .content-image-holder{
        position: relative;
        margin-top: 6px;
        margin-bottom: 6px; 
    }
    .content-image-holder .content-image{
        margin-inline: auto;
        display: block;
        border-radius: 100% !important;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        height:200px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .image-upload{
        position: absolute;
        bottom: 5px;
        right: 30px;
    }
    .image-upload>input {
        display: none;
    }
    .btn.change-image{
        width:45px; 
        height:45px; 
        background:#F78A00;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
    .content-image-holder .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 30px;
        width:45px; 
        height:45px; 
        padding-top: 8px;
        border-radius: 100%;
        background: #e74645 !important;
        color:#ffffff !important;
    }
    .content-image-holder .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
    }
</style>

<div>
    <h4 class="text-primary">Profile Data</h4>
    <form class="d-inline" id="form-check-user">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-floating mb-1">
                    <input type="text" class="form-control nameInput" id="username" name="username" onchange="routeCheck()" oninput="validateForm(validation)" maxlength="35" required>
                    <label for="username">Username</label>
                    <a id="username_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-floating mb-1">
                    <input type="email" class="form-control nameInput" id="email" name="email" onchange="routeCheck()" oninput="validateForm(validation)" maxlength="75" required>
                    <label for="email">Email</label>
                    <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
        </div>
    </form>
    <form class="d-inline" id="form-regis">
        <input hidden name="username" id="username_final">
        <input hidden name="email" id="email_final">

        <span id="reset-uname-holder" class="d-none">
            <a class="btn btn-noline p-0 mb-2" style="color:#D5534C !important;" onclick="resetUnameEmail()"><i class="fa-solid fa-rotate-right"></i> Reset username and email</a>
        </span>
        <a id="all_user_check_msg" class="text-danger my-2" style="font-size:13px;"></a>
        <div id="prevent-data-section">
            <img class="img img-fluid d-block mx-auto mt-3" style="width: 240px;" src="{{'/assets/check_user.png'}}">
            <h6 class="text-center">Before you can fill the other form. We must validate your username and email first</h6>
        </div>
        <div id="detail-data-section" class="d-none">
            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control nameInput" id="password" name="password" oninput="validateFormSecond(validation2)" maxlength="75" required>
                        <label for="password">Password</label>
                        <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-floating mb-3" style="max-width:160px;">
                        <select class="form-select" id="valid_until" name="valid_until" aria-label="Floating label select example" onchange="validateFormSecond(validation2)" required>
                            <option value="{{date('Y')}}" selected>{{date('Y')}}</option>
                            @php($vu_list = [])
                            @php($now = (int)date('Y-m-d'))
                            @for($i = 0; $i < 6; $i++)
                                @php(array_push($vu_list, (int)date('Y', strtotime('-'.$i.' years', strtotime($now)))))
                                @php(array_push($vu_list, (int)date('Y', strtotime('+'.$i.' years', strtotime($now)))))
                            @endfor

                            @php(rsort($vu_list))
                            @php($vu_list = array_values(array_unique($vu_list)))
                            @foreach($vu_list as $vu)
                                <option value="{{$vu}}">{{$vu}}</option>
                            @endforeach
                            
                        </select>
                        <label for="valid_until">Valid Until</label>
                        <a id="valid_until_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control nameInput" id="first_name" name="first_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                        <label for="first_name">First Name</label>
                        <a id="first_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control nameInput" id="last_name" name="last_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                        <label for="last_name">Last Name</label>
                        <a id="last_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                </div>
            </div>
            <a id="input_all_profiledata_msg" class="text-danger my-2" style="font-size:13px;"></a>

            <h4 class="text-primary">Profile Image</h4>
            <div class="row">
                <div class="col-lg-6 p-4">
                    <div class="content-image-holder">
                        <img id="profile_image_info" class="content-image img img-fluid" src="{{ asset('/assets/default_lecturer.png')}}" style="width:200px; height:200px;">
                        <div class='image-upload' id='formFileImg'>
                            <label for='file-input'>
                                <img class='btn change-image shadow position-relative p-1' title='Change Image' src="{{asset('assets/change_image.png')}}"/>
                            </label>
                            <input id='file-input' type='file' accept="image/*" value="" onchange='setValueProfileImage()'/>
                        </div>
                        <input hidden type="text" name="image_url" id="profile_image_url" value="">
                        <a class="btn btn-icon-reset-image shadow" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                        <span class="status-holder shadow">
                            <a class="attach-upload-status success" id="header-progress"></a>
                            <a class="attach-upload-status danger" id="header-failed"></a>
                            <a class="attach-upload-status warning" id="header-warning"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<a class="registered-msg" id="registered-msg"></a>
<span id="btn-next-role-holder">
    <button class="btn-next-steps locked" id="btn-next-profile-role" onclick="warn('profiledata')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>

<script>
    var unameMsg = document.getElementById("username_msg");
    var emailMsg = document.getElementById("email_msg");
    var uname = document.getElementById("username");
    var email = document.getElementById("email");
    var fname = document.getElementById("first_name");
    var lname = document.getElementById("last_name");
    var pass = document.getElementById("password");
    var until = document.getElementById("valid_until");
    var unameLengMin = 6;
    var unameLengMax = 30;
    var emailLengMin = 10;
    var emailLengMax = 75;
    
    function routeCheck(){
        unameVal = uname.value.trim();
        emailVal = email.value.trim();
        uname.value = unameVal;
        email.value = emailVal;

        if(unameVal.length > unameLengMin && emailVal.length > emailLengMin && unameVal.length <= unameLengMax && emailVal.length <= emailLengMax ){
            check_user();
        } else {
            if(unameVal.length <= 6 || unameVal.length > 30){
                unameMsg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Username should be around " + unameLengMin + " until " + unameLengMax + " character";
            }
            if(emailVal.length <= 10 || emailVal.length > 75){
                emailMsg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Email should be around " + emailLengMin + " until " + emailLengMax + " character";
            }
        }
    }

    function resetUnameEmail(){
        uname.disabled = false;
        email.disabled = false;
        document.getElementById("prevent-data-section").setAttribute('class', '');
        document.getElementById("detail-data-section").setAttribute('class', 'd-none');
        document.getElementById("reset-uname-holder").setAttribute('class', 'd-none');
    }

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
                });
                setTimeout(() => {
                    document.getElementById("header-progress").innerHTML = "";
                }, 2000);
            });
        } else {
            document.getElementById('header-failed').innerHTML = "<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>Upload failed. Maximum file size is " + maxSize + " mb </h6></span>";
        }
    }

    function check_user(){
        $('#all_user_check_msg').html("");
        $('#username_msg').html("");
        $('#email_msg').html("");

        $.ajax({
            url: '/api/v1/check/user',
            type: 'POST',
            data: $('#form-check-user').serialize(),
            dataType: 'json',
            success: function(response) {
                document.getElementById("prevent-data-section").setAttribute('class', 'd-none');
                document.getElementById("reset-uname-holder").setAttribute('class', '');
                document.getElementById("detail-data-section").setAttribute('class', '');
                document.getElementById("username_final").value = uname.value;
                document.getElementById("email_final").value = email.value;
                uname.disabled = true;
                email.disabled = true;
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var usernameMsg = null;
                var emailMsg = null;
                var allMsg = null;
                var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";
                console.log(response.responseJSON);

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    //Error validation
                    if(typeof response.responseJSON.result === "string"){
                        allMsg = response.responseJSON.result;
                    } else {
                        if(response.responseJSON.result.hasOwnProperty('username')){
                            usernameMsg = response.responseJSON.result.username[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('email')){
                            emailMsg = response.responseJSON.result.email[0];
                        }
                    }
                    
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg += response.responseJSON.errors.result[0]
                } else {
                    allMsg += errorMessage
                }

                //Set to html
                if(usernameMsg){
                    $('#username_msg').html(icon + usernameMsg);
                }
                if(emailMsg){
                    $('#email_msg').html(icon + emailMsg);
                }
                if(allMsg){
                    $('#all_user_check_msg').html(icon + allMsg);
                }
            }
        });
    }

    function register(){
        $('#all_user_check_msg').html("");
        $('#username_msg').html("");
        $('#email_msg').html("");
        $('#first_name_msg').html("");
        $('#last_name_msg').html("");

        $.ajax({
            url: '/api/v1/register',
            type: 'POST',
            data: $('#form-regis').serialize(),
            dataType: 'json',
            success: function(response) {
                uname.disabled = true;
                email.disabled = true;
                fname.disabled = true;
                lname.disabled = true;
                pass.disabled = true;
                until.disabled = true;
                document.getElementById("registered-msg").innerHTML = "Your account has been registered";
                registered = true;
                validate("profiledata");
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var usernameMsg = null;
                var emailMsg = null;
                var fnameMsg = null;
                var lnameMsg = null;
                var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    //Error validation
                    if(typeof response.responseJSON.result === "string"){
                        allMsg = response.responseJSON.result;
                    } else {
                        if(response.responseJSON.result.hasOwnProperty('username')){
                            usernameMsg = response.responseJSON.result.username[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('email')){
                            emailMsg = response.responseJSON.result.email[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('first_name')){
                            fnameMsg = response.responseJSON.result.first_name[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('last_name')){
                            lnameMsg = response.responseJSON.result.last_name[0];
                        }
                    }
                    
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg += response.responseJSON.errors.result[0]
                } else {
                    allMsg += errorMessage
                }

                //Set to html
                if(usernameMsg){
                    $('#username_msg').html(icon + usernameMsg);
                }
                if(emailMsg){
                    $('#email_msg').html(icon + emailMsg);
                }
                if(fnameMsg){
                    $('#first_name_msg').html(icon + fnameMsg);
                }
                if(lnameMsg){
                    $('#last_name_msg').html(icon + lnameMsg);
                }
                if(allMsg){
                    $('#all_user_check_msg').html(icon + allMsg);
                }
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