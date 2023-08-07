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
    var val1 = false; 
</script>

<div class="text-center" style="min-height:50vh;">
    <h4 class="text-primary">Validate your account</h4>
    <form class="d-inline" id="form-check-user">
        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="username" name="username" onchange="validateForm(validation)" maxlength="35" required>
            <label for="username">Username</label>
            <a id="username_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
    
        <div class="form-floating mb-2">
            <input type="email" class="form-control nameInput" id="email" name="email" onchange="validateForm(validation)" maxlength="75" required>
            <label for="email">Email</label>
            <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
    </form>

    <div id="all_user_check_msg" class="text-danger my-2" style="font-size:13px;"></div>
    <div id="success-check"></div>
    <div id="prevent-validation">
        <img class="img img-fluid d-block mx-auto mt-3" style="width: 240px;" src="{{'/assets/check_user.png'}}">
        <h6 class="text-center">Please tell us your email and username</h6>
    </div>
    <div id="success-validate-section" class="d-none">
        <img class="img img-fluid d-block mx-auto mt-3" style="width: 240px;" src="{{'/assets/send_email.png'}}">
        <h6 class="text-center mb-3">We've send you password recovery token to your email. Please check it and move to next step</h6>
    </div>
    <a class="btn btn-primary d-block mx-auto mt-4" onclick="check_user()" id="validate-recovery-btn" style="border-radius:var(--roundedLG); width:160px;"><i class="fa-solid fa-paper-plane"></i> Validate</a>
</div>
<span id="btn-next-validate-holder" class="d-flex justify-content-end mt-3">
    <button class="btn-next-steps locked" id="btn-next-validate" onclick="warn('recovery')"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button>
</span>

<script>
    function check_user(){
        $('#all_user_check_msg').html("");
        $('#username_msg').html("");
        $('#email_msg').html("");

        $.ajax({
            url: '/api/v1/check/pass/recover',
            type: 'POST',
            data: $('#form-check-user').serialize(),
            dataType: 'json',
            success: function(response) {
                document.getElementById("validate-recovery-btn").setAttribute('class', 'd-none');
                setTimeout(() => {
                    document.getElementById("success-check").innerHTML = '<lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 420px; height: 420px;" autoplay></lottie-player>';
                }, 500);
                document.getElementById("prevent-validation").setAttribute('class', 'd-none');
                setTimeout(() => {
                    document.getElementById("success-check").innerHTML = "";
                    document.getElementById("success-validate-section").setAttribute('class', '');
                }, 3000);
                validate("recovery");
                input_username.disabled = true;
                input_email.disabled = true;
                validate_username.value = input_username.value;
                validate_email.value = input_email.value;
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
                    input_username_msg.innerHTML = icon + usernameMsg;
                }
                if(emailMsg){
                    input_email_msg.innerHTML = icon + emailMsg;
                }
                if(allMsg){
                    $('#all_user_check_msg').html(icon + allMsg);
                }
            }
        });
    }
</script>