<style>
    #timer {
        color: var(--primaryColor);
        font-weight: bold;
        font-size: calc(var(--textJumbo) + var(--textLG)); 
        margin: var(--spaceXMD);
    }
</style>
<script>
    let validation2 = [
        { id: "password", req: true, len: 75 },
        { id: "password_valid", req: true, len: 75 }
    ];
    var val1 = false; 
</script>

<div class="text-center justify-center" style="min-height:50vh;">
    <h4 class="text-primary text-center mb-4">{{ __('messages.set_new_pass') }}</h4><br>

    <div id="token-validation-holder">
        <label for="pin-code" style="font-size:var(--textXMD);">Token</label>
        <div class="pin-code mt-3" id="pin-holder">
            <input type="text" maxlength="1" oninput="validatePin()" autofocus>
            <input type="text" maxlength="1" oninput="validatePin()">
            <input type="text" maxlength="1" oninput="validatePin()">
            <input type="text" maxlength="1" oninput="validatePin()">
            <input type="text" maxlength="1" oninput="validatePin()">
            <input type="text" maxlength="1" oninput="validatePin()">
        </div>
        <div id="token_validate_msg" class="text-danger my-2" style="font-size:13px;"></div>
        <div id="timer">15:00</div>
        <p id="token_msg">{{ __('messages.time_token_validation') }}</p>
        <p>{{ __('messages.dont_receive_email') }} <a class="btn btn-success px-4 py-2 mx-2" style="border-radius:var(--roundedLG);" onclick="postValdRecover(true)" id="regenerate-btn"><i class="fa-solid fa-envelope"></i> Resend Email</a></p>
    </div>
    <div id="success-validation-token"></div>
    <div id="success-validation-msg"></div>

    <div id="new-pass-holder" class="d-none">
        <form class="d-inline" id="form-edit-pass">
            <input hidden name="username" id="final_username">
            <input hidden name="validation_token" id="final_token">
            <div class="form-floating mb-1">
                <input type="password" class="form-control nameInput" id="password" name="password" onchange="validateFormSecond(validation2)" maxlength="75" required>
                <label for="password">Password</label>
                <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="form-floating my-2">
                <input type="password" class="form-control nameInput" id="password_valid" name="password_valid" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="password">{{ __('messages.pass_valid') }}</label>
                <a id="password_valid_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </form>
        <div id="token_validate_msg_2" class="text-danger my-2" style="font-size:13px;"></div>
    </div>
</div>

<form class="d-inline" id="form-validate-token">
    <input hidden name="username" id="validate_username">
    <input hidden name="email" id="validate_email">
    <input hidden name="validation_token" id="validate_token" value="AAA123">
    <input hidden name="type" id="validate_type">
</form>
<span id="btn-next-finish-holder" class="d-flex justify-content-end mt-3">
    <button class="btn-next-steps locked" id="btn-next-finish" onclick="warn('validate')"><i class="fa-solid fa-lock"></i> Next</button>
</span>

<script>
    var timer = document.getElementById("timer");
    var validate_type = document.getElementById("validate_type");
    var validate_token = document.getElementById("validate_token");
    var validate_username = document.getElementById("validate_username");
    var validate_email = document.getElementById("validate_email");
    var password = document.getElementById("password");
    var password_valid = document.getElementById("password_valid");
    var regenerate_btn = document.getElementById("regenerate-btn");
    var pin_holder = document.getElementById('pin-holder');
    var new_pass_holder = document.getElementById('new-pass-holder');
    var token_msg = document.getElementById("token_msg");
    var remain = 900;
    var is_almost_out = false;

    function formatTime(seconds){
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds % 60;
        return minutes + ':' + remainingSeconds.toString().padStart(2, '0');
    }

    function controlPin(type) {
        var pins = pin_holder.querySelectorAll('input');
        var result = "";

        pins.forEach(function(e) {
            if(type == "time_out"){
                e.disabled = true;
                e.style = "background: var(--hoverBG);";
            } else if(type == "regenerate"){
                e.disabled = false;
                e.value = "";
                e.style = "background: var(--whiteColor);";
            } else if(type == "invalid"){
                e.value = "";
                e.style = "border: 1.5px solid var(--warningBG); ";
            } else if(type == "fetch"){
                result += e.value;
            }
        });

        return result;
    }

    function validatePin(){
        var pins = pin_holder.querySelectorAll('input');
        var is_empty = false;

        pins.forEach(function(e) {
            if(e.value == "" || e.value == null){
                is_empty = true;
                return;
            }
        });

        if(is_empty == false){
            postValdRecover(false);
        }
    }
    
    function startTimer(duration) {
        var remain = duration;

        function updateTimer() {
            timer.innerHTML = formatTime(remain);

            if (remain > 0) {
                remain--;
                setTimeout(updateTimer, 1000);

                if (remain <= 180) {
                    timer.style = "color: var(--warningBG);";
                }
            } else {
                token_msg.innerHTML = "<span class='text-danger'>Time's up, please try again</span>";
                controlPin("time_out");
            }
        }

        updateTimer();
    }

    function postValdRecover(is_try_again){
        $("#token_validate_msg").html("");
        if(is_try_again == true){
            validate_type.value = "try_again";
            validate_token.value = "AAA123";
        } else {
            validate_type.value = "first";
            validate_token.value = controlPin("fetch");
        }

        $.ajax({
            url: '/api/v1/check/pass/validate',
            type: 'POST',
            data: $('#form-validate-token').serialize(),
            dataType: 'json',
            success: function(response) {
                if(is_try_again == true){
                    setTimeout(() => {
                        regenerate_btn.innerHTML = '<i class="fa-solid fa-check"></i> Sended!';
                    }, 500);
                    setTimeout(() => {
                        regenerate_btn.innerHTML = '<i class="fa-solid fa-envelope"></i> Resend Email</a>';
                    }, 10000);
                    controlPin("regenerate");
                } else {
                    setTimeout(() => {
                        document.getElementById("success-validation-token").innerHTML = '<lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 420px; height: 420px;" autoplay></lottie-player>';
                    }, 500);
                    setTimeout(() => {
                        document.getElementById("success-validation-token").innerHTML = '';
                        new_pass_holder.setAttribute('class', '');
                    }, 3000);
                    btn_steps_validate.setAttribute('data-bs-target', '#validate');
                    btn_steps_validate.style = "background: var(--successBG);";
                    document.getElementById("final_username").value = validate_username.value;
                    document.getElementById("final_token").value = validate_token.value;
                    document.getElementById("token-validation-holder").setAttribute('class', 'd-none');
                    document.getElementById("btn-next-finish-holder").innerHTML = `<button class='btn-next-steps' id='btn-next-terms' onclick='newPass()'><i class='fa-solid fa-paper-plane'></i> {{ __('messages.submit') }}</button>`;
                }
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var tokenMsg = null;
                var allMsg = null;
                var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    if(typeof response.responseJSON.result === "string"){
                        allMsg = response.responseJSON.result;
                    } else {
                        if(response.responseJSON.result.hasOwnProperty('validation_token')){
                            tokenMsg = response.responseJSON.result.validation_token[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('username')){
                            allMsg += response.responseJSON.result.username[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('email')){
                            allMsg += response.responseJSON.result.email[0];
                        }
                        if(response.responseJSON.result.hasOwnProperty('type')){
                            allMsg += response.responseJSON.result.type[0];
                        }
                    }
                    
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg += response.responseJSON.errors.result[0]
                } else {
                    allMsg += errorMessage
                }

                if(tokenMsg){
                    controlPin("invalid");
                    $('#token_validate_msg').html(icon + tokenMsg);
                }
                if(allMsg){
                    controlPin("invalid");
                    $('#token_validate_msg').html(icon + allMsg);
                }
            }
        });
    }

    function newPass(){
        if(password_valid.value == password.value){
            $.ajax({
                url: '/api/v1/check/pass/edit',
                type: 'PUT',
                data: $('#form-edit-pass').serialize(),
                dataType: 'json',
                success: function(response) {
                    is_finished = true;
                    document.getElementById("password").disabled = true;
                    document.getElementById("btn-next-finish-holder").innerHTML = "<button class='btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#finish'><i class='fa-solid fa-arrow-right'></i> Next</button>";
                    routeStep("next", "validate");
                    $('#finish').collapse('show');
                },
                error: function(response, jqXHR, textStatus, errorThrown) {
                    var errorMessage = "Unknown error occurred";
                    var tokenMsg = null;
                    var allMsg = null;
                    var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";

                    if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                        if(typeof response.responseJSON.result === "string"){
                            allMsg = response.responseJSON.result;
                        } else {
                            if(response.responseJSON.result.hasOwnProperty('validation_token')){
                                tokenMsg = response.responseJSON.result.validation_token[0];
                            }
                            if(response.responseJSON.result.hasOwnProperty('username')){
                                allMsg += response.responseJSON.result.username[0];
                            }
                            if(response.responseJSON.result.hasOwnProperty('password')){
                                allMsg += response.responseJSON.result.password[0];
                            }
                        }
                        
                    } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                        allMsg += response.responseJSON.errors.result[0]
                    } else {
                        allMsg += errorMessage
                    }

                    if(tokenMsg){
                        $('#token_validate_msg_2').html(icon + tokenMsg);
                    }
                    if(allMsg){
                        $('#token_validate_msg_2').html(icon + allMsg);
                    }
                }
            });
        } else {
            $('#password_valid_msg').html(`<i class='fa-solid fa-triangle-exclamation'></i> {{ __('messages.err_pass_valid') }}`);
        }
    }
</script>