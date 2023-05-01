<div class="container-fluid rounded my-4 py-5 text-start" style="max-width:450px;">
    <form action="/v2/login/" method="POST" id="form-login">
        @csrf
        <h1 class="fw-bold mt-4 text-primary">Welcome to Mi-Fik</h1>
        <h6 class="mb-4">Mi-Fik is an app made for event organizing and announcement that will be used for lecturer, staff, and student of 
            <a class="link-external" href="https://ifik.telkomuniversity.ac.id/">School of Creative Industries Telkom University</a></h6>
        <div class="form-floating mt-1">
            <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" id="username" required>
            <label for="floatingUsername">Username</label>
            <a class="error_input" id="username_msg"></a>
        </div>
        <div class="form-floating mt-3">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" id="password" required>
            <label for="floatingPassword">Password</label>
            <a class="error_input" id="pass_msg"></a>
        </div>
        <a class="btn btn-forgot-password mt-4">Forgot Password ?</a>

        <a class="error_input" id="all_msg"></a>
        <input hidden name="token" value="" id="token">
        <input hidden name="role" value="" id="role">
        <input hidden name="email" value="" id="email">
        <input hidden name="profile_pic" value="" id="profile_pic">
        <div class="position-relative mt-2 mb-2">
            <a onclick="login()" class="btn btn-submit-form px-5 rounded-pill">Sign In</a>
            <a onclick="" class="btn btn-primary-outlined position-absolute px-5 rounded-pill" style="right:0; top:7.5px;">Register</a>
        </div>
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
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var usernameMsg = null;
                var passMsg = null;
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
                        if(response.responseJSON.result.hasOwnProperty('password')){
                            passMsg = response.responseJSON.result.password[0];
                        }
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