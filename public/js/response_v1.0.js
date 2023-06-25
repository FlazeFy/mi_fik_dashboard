function failResponse(jqXHR, ajaxOptions, thrownError, holder, is_modal, add_text, add_img){
    if(is_modal == false){
        if (jqXHR.status == 404 && add_text === null) {
            $('.auto-load').hide();
            $(holder).html("<div class='err-msg-data d-block mx-auto text-center'><img src='http://127.0.0.1:8000/assets/nodata.png' class='img' style='width:250px;'><h6 class='text-secondary text-center'>"+jqXHR.responseJSON.message+"</h6></div>");
        } else if (jqXHR.status == 423) {
            $('.auto-load').hide();
            $(holder).html("<div class='err-msg-data d-block mx-auto text-center'><img src='http://127.0.0.1:8000/assets/429_error.png' class='img' style='width:250px;'><h6 class='text-secondary text-center'>Too many request. Please wait some moment</h6></div>");
        } else if (jqXHR.status == 401 || jqXHR.status == 403) {
            $('.auto-load').hide();
            $(holder).html("<div class='err-msg-data d-block mx-auto text-center'><img src='http://127.0.0.1:8000/assets/403_error.png' class='img' style='width:250px;'><h6 class='text-secondary text-center'>Sorry. But you don't have access</h6></div>");
        } else if (add_text != null){
            $('.auto-load').hide();
            $(holder).html("<div class='err-msg-data d-block mx-auto text-center'><img src='"+add_img+"' class='img' style='width:250px;'><h6 class='text-secondary text-center'>"+add_text+"</h6></div>");
        } else {
            $('.auto-load').hide();
            $(holder).html("<div class='err-msg-data d-block mx-auto text-center'><img src='http://127.0.0.1:8000/assets/nodata3.png' class='img' style='width:250px;'><h6 class='text-secondary text-center'>Unknown error. Please contact Admin</h6></div>");
        }
    } else {
        // Do something stupid
    }
}