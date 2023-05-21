function deleteAfterCharacter(str, character) {
    var index = str.indexOf(character);
    if (index !== -1) {
        return str.slice(0, index);
    } else {
        return str;
    }
}

function getTag(obj, padding, sz, margin){
    var res = " ";

    if(obj != null && obj.length > 0){
        obj.forEach(e => {
            res += " " + "<a class='btn btn-primary " + padding + " " + margin + " ' style='font-size:" + sz + "'>" + e.tag_name + "</a>";
        });
    } 

    return res;
}

function getName(val1 ,val2){
    if(val1 == null || val1 == "null"){
        return val2;
    } else {
        return val1;
    }
}

function messageCopy(val){
    navigator.clipboard.writeText(val)
    .then(function() {
        document.getElementById("success_toast_msg").innerHTML = "Token has been added to clipboard";
        $('#success_toast').toast('show');
    })
    .catch(function(err) {
        document.getElementById("err_modal_msg").innerHTML = err;
        $('#error_modal').modal('show');
    });
}

function getUserImageGeneral(img, role){
    if(img){
        return img;
    } else {
        if(Array.isArray(role)){
            for(var i = 0; i < role.length; i++){
                if(role[i].slug_name == "student"){
                    return "http://127.0.0.1:8000/assets/default_student.png";
                } else if(role[i].slug_name == "lecturer" || role[i].slug_name == "staff"){
                    return "http://127.0.0.1:8000/assets/default_lecturer.png";
                }
            }
        } else {
            return "http://127.0.0.1:8000/assets/default_lecturer.png";
        }
    }
}