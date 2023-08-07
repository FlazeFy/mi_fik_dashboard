<style>
    .form-floating {
        display: block;
        margin-inline: auto;
    }
</style>

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

<div class="pb-4 text-center d-block mx-auto" style="min-height:60vh;">
    <h2 class="text-primary text-center">Profile Data</h2><br>

    <form id="form-check-user">
        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="username" name="username" oninput="validateForm(validation)" maxlength="35" required>
            <label for="username">Username</label>
            <a id="username_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating mb-2">
            <input type="email" class="form-control nameInput" id="email" name="email" oninput="validateForm(validation)" maxlength="75" required>
            <label for="email">Email</label>
            <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
    </form>
    <div id="success-check"></div>

    <form class="d-block mx-auto justify-content-center" id="form-regis">
        <input hidden name="username" id="username_final">
        <input hidden name="email" id="email_final">

        <span id="reset-uname-holder" class="d-none">
            <a class="btn btn-noline p-0 my-2" style="color:#D5534C !important;" onclick="resetUnameEmail()"><i class="fa-solid fa-rotate-right"></i> Reset username and email</a>
        </span>
        <a id="all_user_check_msg" class="text-danger my-2" style="font-size:13px;"></a>
        <div id="prevent-data-section">
            <img class="img img-fluid d-block mx-auto mt-3" style="width: 240px;" src="{{'/assets/check_user.png'}}">
            <h6 class="text-center">Before you can fill the other form. We must validate your username and email first</h6>
        </div><br>
        <div id="validate-available-section">
            <a class="btn btn-primary d-block mx-auto" onclick="routeCheck()" id="validate-recovery-btn" style="border-radius:var(--roundedLG); width:160px;"><i class="fa-solid fa-paper-plane"></i> Validate</a>
        </div>

        <div id="detail-data-section" class="d-none">
            <div class="form-floating my-2">
                <input type="password" class="form-control nameInput" id="password" name="password" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="password">Password</label>
                <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        
            <div class="form-floating mb-2">
                <input type="text" class="form-control nameInput" id="first_name" name="first_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="first_name">First Name</label>
                <a id="first_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control nameInput" id="last_name" name="last_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="last_name">Last Name</label>
                <a id="last_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
               
            <a id="input_all_profiledata_msg" class="text-danger my-2" style="font-size:13px;"></a>
            <a id="all_user_regis_msg" class="text-danger my-2" style="font-size:13px;"></a>

            <h2 class="text-primary mt-4">Profile Image</h2><br>
            <div class="position-relative d-flex justify-content-center">
                <div class="content-image-holder p-0 position-relative">
                    <img id="profile_image_info" class="content-image img img-fluid" src="{{ asset('/assets/default_lecturer.png')}}" style="width:200px; height:200px;">
                    <div class='image-upload' id='formFileImg'>
                        <label for='file-input'>
                            <img class='btn change-image shadow position-absolute p-1' title='Change Image' src="{{asset('assets/change_image.png')}}"/>
                        </label>
                        <input id='file-input' type='file' accept="image/*" value="" onchange='setValueProfileImage()'/>
                    </div>
                    <input hidden type="text" name="image_url" id="profile_image_url" value="">
                    <span id="reset_image_holder"></span>
                    <span class="status-holder shadow">
                        <a class="attach-upload-status success" id="header-progress"></a>
                        <a class="attach-upload-status danger" id="header-failed"></a>
                        <a class="attach-upload-status warning" id="header-warning"></a>
                    </span>
                </div>
                <div class="position-absolute" style="top:0;">
                    <div id="success-image-check" style="height:300px;"></div>
                </div>
            </div>
            <div class='info-box tips mx-0 my-2 pt-3 d-inline-flex'>
                <label class="fw-normal"><i class='fa-solid fa-circle-info'></i> Image's size must below 4 mb and have JPEG, JPG, PNG, or GIF format</label>
            </div>
        </div>
        <a class="registered-msg" id="registered-msg"></a>
    </form>
</div>
<span id="btn-next-role-holder" class="d-flex justify-content-end">
    <button class="btn-next-steps locked" id="btn-next-profile-role" onclick="warn('profiledata')"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button>
</span>

<script>
    var unameMsg = document.getElementById("username_msg");
    var emailMsg = document.getElementById("email_msg");
    var passMsg = document.getElementById("password_msg");
    var uname = document.getElementById("username");
    var email = document.getElementById("email");
    var fname = document.getElementById("first_name");
    var lname = document.getElementById("last_name");
    var pass = document.getElementById("password");
    var btn_reset_image = document.getElementById("reset_image_holder");
    var img_url = document.getElementById("profile_image_url");
    var img_src = document.getElementById('profile_image_info');
    var img_file = document.getElementById('file-input');
    var btn_add_image = document.getElementById('formFileImg');
    var success_img_check = document.getElementById('success-image-check');
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
                unameMsg.innerHTML = `<i class='fa-solid fa-triangle-exclamation'></i> Username should be around ${unameLengMin} until ${unameLengMax} character`;
            }
            if(emailVal.length <= 10 || emailVal.length > 75){
                emailMsg.innerHTML = `<i class='fa-solid fa-triangle-exclamation'></i> Email should be around ${emailLengMin} until ${emailLengMax} character`;
            }
        }
    }

    function clearImage() {
        var storageRef = firebase.storage();
        var url = img_url.value;
        var desertRef = storageRef.refFromURL(url);

        desertRef.delete().then(() => {
            document.getElementById("header-progress").innerHTML = `<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Success.png"><h6>Profile image has been set to default</h6></span>`;
        }).catch((error) => {
            document.getElementById("header-failed").innerHTML = `<span class="box-loading"><img class="d-inline mx-auto img img-fluid" src="http://127.0.0.1:8000/assets/Failed.png"><h6>${error}</h6></span>`;
        });

        setTimeout(() => {
            document.getElementById("header-progress").innerHTML = "";
        }, 1500);
        img_src.src = "http://127.0.0.1:8000/assets/default_lecturer.png";
        btn_reset_image.innerHTML = "";
        btn_add_image.innerHTML = `
            <label for='file-input'>
                <img class='btn change-image shadow position-relative p-1' style='bottom:50px; right:-150px;' title='Change Image' src='<?= asset("assets/change_image.png"); ?>'/>
            </label> 
            <input id='file-input' type='file' accept='image/*' value='' onchange='setValueProfileImage()'/>
        `;
        img_url.value = "";
        img_file.value = "";
    }

    function resetUnameEmail(){
        uname.disabled = false;
        email.disabled = false;
        document.getElementById("validate-available-section").innerHTML = `<a class="btn btn-primary d-block mx-auto" onclick="routeCheck()" id="validate-recovery-btn" style="border-radius:var(--roundedLG); width:160px;"><i class="fa-solid fa-paper-plane"></i> Validate</a>`;
        document.getElementById("prevent-data-section").setAttribute('class', '');
        document.getElementById("detail-data-section").setAttribute('class', 'd-none');
        document.getElementById("reset-uname-holder").setAttribute('class', 'd-none');
    }

    function setValueProfileImage(){
        var img_file = document.getElementById('file-input');
        var file_src = img_file.files[0];
        var maxSize = 4; // Mb

        if(file_src.size <= maxSize * 1024 * 1024){
            var filePath = 'profile_image/' + getUUID();

            var storageRef = firebase.storage().ref(filePath);
            var uploadTask = storageRef.put(file_src);

            uploadTask.on('state_changed',function (snapshot) {
                var progress = Math.round((snapshot.bytesTransferred/snapshot.totalBytes)*100);
                document.getElementById('header-progress').innerHTML = `<span class="box-loading"><div role="progressbar" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="${progress}" style="--value: ${progress}"></div></span>`;
            }, 
            function (error) {
                document.getElementById('header-failed').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>File upload is ${error.message}</h6></span>`;
            }, 
            function () {
                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadUrl) {
                    img_src.src = downloadUrl;
                    img_url.value = downloadUrl;
                });
                setTimeout(() => {
                    document.getElementById("header-progress").innerHTML = "";
                }, 1500);
                setTimeout(() => {
                    success_img_check.innerHTML = `<lottie-player src="https://assets7.lottiefiles.com/packages/lf20_lg6lh7fp.json" background="transparent" speed="0.5"  style="width: 230px; height:230px;" autoplay></lottie-player>`;
                }, 250);
                success_img_check.innerHTML = "";
                btn_reset_image.innerHTML = `<a class="btn btn-icon-reset-image shadow" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can fa-lg"></i></a>`;
                btn_add_image.innerHTML = '';
            });
        } else {
            document.getElementById('header-failed').innerHTML = `<span class='box-loading'><img class='d-inline mx-auto img img-fluid' src='http://127.0.0.1:8000/assets/Failed.png'><h6>Upload failed. Maximum file size is ${maxSize} mb </h6></span>`;
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
                document.getElementById("validate-available-section").innerHTML = "";
                setTimeout(() => {
                    document.getElementById("success-check").innerHTML = '<lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 420px; height: 420px;" autoplay></lottie-player>';
                }, 500);
                document.getElementById("prevent-data-section").setAttribute('class', 'd-none');
                setTimeout(() => {
                    document.getElementById("success-check").innerHTML = "";
                    document.getElementById("reset-uname-holder").setAttribute('class', '');
                    document.getElementById("detail-data-section").setAttribute('class', '');
                }, 3000);
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
        $('#all_user_regis_msg').html("");
        $('#username_msg').html("");
        $('#email_msg').html("");
        $('#first_name_msg').html("");
        $('#last_name_msg').html("");
        $('#password_msg').html("");

        $.ajax({
            url: '/api/v1/register',
            type: 'POST',
            data: $('#form-regis').serialize(),
            dataType: 'json',
            success: function(response) {
                document.getElementById("username_role").value = uname.value;
                document.getElementById("password_role").value = pass.value;
                uname.disabled = true;
                email.disabled = true;
                fname.disabled = true;
                lname.disabled = true;
                pass.disabled = true;
                btn_reset_image.innerHTML = "";
                btn_add_image.innerHTML = "";
                document.getElementById("reset-uname-holder").innerHTML = "";
                document.getElementById("registered-msg").innerHTML = `<i class='fa-solid fa-check'></i> Your account has been registered`;
                registered = true;
                validate("profiledata");
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var usernameMsg = null;
                var emailMsg = null;
                var fnameMsg = null;
                var lnameMsg = null;
                var passMsg = null;
                var allMsg = null;
                var icon = `<i class='fa-solid fa-triangle-exclamation'></i> `;
                console.log(response.responseJSON)

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
                        if(response.responseJSON.result.hasOwnProperty('password')){
                            passMsg = response.responseJSON.result.password[0];
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
                if(passMsg){
                    $('#password_msg').html(icon + passMsg);
                }
                if(allMsg){
                    $('#all_user_regis_msg').html(icon + allMsg);
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