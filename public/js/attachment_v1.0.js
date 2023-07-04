function getAttachmentInput(index, val){
    $("#attach-input-holder-"+index).html("");
    setValue(index);

    //Allowed type
    if(val == 'attachment_image'){
        var allowed = 'accept="image/*"'
    } else if(val == 'attachment_video'){
        var allowed = 'accept="video/*"'
    } else if(val == 'attachment_doc'){
        var allowed = 'accept="application/pdf"' //Check this again...
    }

    if(val == "attachment_url"){
        $("#attach-input-holder-"+index).append(' ' +
            '<h6 class="mt-1">Attachment URL</h6> ' +
            '<input type="text" id="attach_url_'+index+'" name="attach_url" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)" required> ' +
            '<h6 class="mt-1">Attachment Name</h6> ' +
            '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)">');
    } else {
        $("#attach-input-holder-"+index).append(' ' +
            '<input type="file" id="attach_url_'+index+'" name="attach_input" class="form-control m-2" '+allowed+' onblur="setValue('+"'"+index+"'"+', true)"> ' +
            '<input type="text" id="attach_url_holder_'+index+'" hidden required> ' +
            '<h6 class="mt-1">Attachment Name</h6> ' +
            '<input type="text" id="attach_name_'+index+'" name="attach_name" class="form-control m-2" onblur="setValue('+"'"+index+"'"+', true)">');
    }
}

function cleanAddMoreStatus(list){
    var final = list.map(function(obj) {
        delete obj.is_add_more;
        return obj;
    });

    return final;
}

function removeAttachment(type, list, idx){
    var new_list = list.filter(object => {
        if(type != "attachment_url" && object.id == idx){
            var filePath = object.attach_url;
            if(filePath){
                var storageRef = firebase.storage();
                var desertRef = storageRef.refFromURL(filePath);
                var msg = ""

                desertRef.delete().then(() => {
                    msg = "Attachment has been removed";
                    //Return msg not finished. i dont know what to do next LOL
                }).catch((error) => {
                    msg = "Failed to deleted the Attachment";
                    //Return msg not finished. i dont know what to do next LOL
                });
            }
        } 

        $("#attachment_container_"+idx).remove();

        return object.id !== idx;
    });

    return new_list
}