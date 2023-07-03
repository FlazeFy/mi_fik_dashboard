function validateForm(rules){
    var input, msg;
    var res = true
    var btn = document.getElementById("submit_holder"); 

    rules.forEach(e => {
        input = document.getElementById(e.id);
        msg = document.getElementById(e.id+"_msg");

        if(e.id != "selected_item"){
            if(input.value.trim().length >= e.len){
                msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Reaching maximum character length";
                res = false
            } else if(input.value.trim().length == 0 && e.req === true){
                msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Field can't be empty";
                res = false
            } else {
                msg.innerHTML = " "
            }
        } else if(e.id == "selected_item" && e.len != null){
            msg = document.getElementById(e.len+"_msg");
            if(document.getElementById(e.len).hasChildNodes()){
                msg.innerHTML = " ";
            } else {
                msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You have not select any item";
                res = false
            }
        }
    });

    if(res){
        if(typeof val1 !== 'undefined'){ 
            val1 = true;
            validate("profiledata");
        } else {
            btn.innerHTML = " ";
            btn.innerHTML = "<button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button>";
        }
    } else {
        if(typeof val1 === 'undefined'){ 
            btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
        } else {
            val1 = false;
            validate("profiledata");
        }
    }
}

// Check this 
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
        if(typeof val2 !== 'undefined'){ 
            val2 = true;
            validate("profiledata");
        } else {
            btn.innerHTML = " ";
            btn.innerHTML = "<button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button>";
        }
    } else {
        if(typeof val2 === 'undefined'){ 
            btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
        } else {
            val2 = false;
            validate("profiledata");
        }
    }
}

function validateFull(rules, id){
    var input, msg;
    var res = true
    var btn = document.getElementById("submit_holder_"+id); 

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
        if(typeof val1 !== 'undefined'){ 
            val1 = true;
            // validate("profiledata");
        } else {
            btn.innerHTML = " ";
            btn.innerHTML = "<button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button>";
        }
    } else {
        if(typeof val1 === 'undefined'){ 
            btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
        } else {
            val1 = false;
            // validate("profiledata");
        }
    }
}