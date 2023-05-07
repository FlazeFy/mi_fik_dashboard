function validateForm(rules){
    var input, msg;
    var res = true
    var btn = document.getElementById("submit_holder"); 

    rules.forEach(e => {
        input = document.getElementById(e.id);
        msg = document.getElementById(e.id+"_msg");

        if(input.value.trim().length >= e.len){
            msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Reaching maximum character length";
            res = false
        } else if(input.value.trim().length == 0 && e.req === true){
            msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Field can't be empty";
            res = false
        } else {
            msg.innerHTML = " "
        }
    });

    if(res){
        btn.innerHTML = " ";
        btn.innerHTML = "<button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button>";
    } else {
        btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
    }
}

// Check this shit
function validateFormSecond(rules){
    var input, msg;
    var res = true
    var btn = document.getElementById("submit_holder_second"); 

    rules.forEach(e => {
        input = document.getElementById(e.id);
        msg = document.getElementById(e.id+"_msg");

        if(input.value.trim().length >= e.len){
            msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Reaching maximum character length";
            res = false
        } else if(input.value.trim().length == 0 && e.req === true){
            msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Field can't be empty";
            res = false
        } else {
            msg.innerHTML = " "
        }
    });

    if(res){
        btn.innerHTML = " ";
        btn.innerHTML = "<button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button>";
    } else {
        btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
    }
}