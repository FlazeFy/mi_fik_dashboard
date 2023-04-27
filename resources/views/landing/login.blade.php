<style>
    .form-floating{
        max-width: 400px;
    }
    .form-floating .form-control{
        border: none;
        border-bottom: 3px solid #212121;
        border-radius: 0;
    }
    .btn-sign-in{
        background: #F78A00;
        border-radius: 30px;
        color: whitesmoke !important;
        font-weight: 500;
        max-width: 400px;
        padding: 8px;
    }
    .btn-forgot-password{
        font-weight: 500;
        max-width: 160px;
        float: right !important;
    }
    .form-control:focus{
        box-shadow: none !important;
    }
</style>

<div class="container-fluid shadow rounded my-4 py-5 text-start d-block mx-auto" style="max-width:450px;">
    <form action="/v2/login/" method="POST" id="form-login">
        @csrf
        <h4 class="fw-bold mt-4">Welcome Administrator</h4>
        <h6 class="fw-bold mb-5">Silahkan login dan mulai mengatur data MI-FIK</h6>
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" id="username" required>
            <label for="floatingUsername">Username</label>
            <a class="error_input" id="username_msg"></a>
        </div>
        <div class="form-floating mt-3">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" id="password" required>
            <label for="floatingPassword">Password</label>
            <a class="error_input" id="pass_msg"></a>
        </div>
        <a class="error_input" id="all_msg"></a>
        <input hidden name="token" value="" id="token">
        <input hidden name="role" value="" id="role">
        <input hidden name="email" value="" id="email">
        <input hidden name="profile_pic" value="" id="profile_pic">
        <a class="btn btn-forgot-password w-100 mt-5">Forgot Password ?</a>
        <a onclick="login()" class="btn btn-sign-in w-100 mt-3 mb-5">Sign In</a>
    </form>
</div>

@include('popup.sorry')

<script>
    function login(){
        $('#username_msg').html("");
        $('#pass_msg').html("");
        $('#all_msg').html("");

        $.ajax({
            url: '/api/v1/login',
            type: 'POST',
            data: $('#form-login').serialize(),
            dataType: 'json',
            success: function(response) {
                //console.log(response.token);
                var found = false;

                if(response.result.hasOwnProperty('role')){
                    let arr_role = response.result.role;
                    arr_role.forEach(e => {
                        if(e.slug_name === "lecturer" || e.slug_name === "staff"){
                            found = true;
                        }
                    });
                } else {
                    found = true;
                }
                
                if(found){
                    $('#token').val(response.token);
                    $('#role').val(response.role);
                    $('#email').val(response.result.email);
                    $('#profile_pic').val(response.result.image_url);
                    $('#form-login').submit();
                } else {
                    $('#username_msg').html("");
                    $('#pass_msg').html("");
                    $('#all_msg').html("");

                    $('#text-sorry').text("Sorry, but only admin, lecturer, and staff who can access MI-FIK Web");
                    $('#sorry_modal').modal('show');
                }
            },
            error: function(response) {
                var errorMessage = "Unknown error occurred";
                var usernameMsg = null;
                var passMsg = null;
                var allMsg = null;
                var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    //Error validation
                    if(response.responseJSON.result.hasOwnProperty('username')){
                        usernameMsg = response.responseJSON.result.username[0];
                    }
                    if(response.responseJSON.result.hasOwnProperty('password')){
                        passMsg = response.responseJSON.result.password[0];
                    }
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg = response.responseJSON.errors.result[0]
                } else {
                    allMsg = errorMessage
                }

                //Set to html
                if(usernameMsg){
                    $('#username_msg').html(icon + usernameMsg);
                }
                if(passMsg){
                    $('#pass_msg').html(icon + passMsg);
                }
                if(allMsg){
                    $('#all_msg').html(icon + allMsg);
                }
            }
        });
    }
</script>