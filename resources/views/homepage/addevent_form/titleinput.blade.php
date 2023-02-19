<div class="form-floating">
    <input type="text" class="form-control nameInput" id="titleInput" name="content_title" oninput="lengValidator('75', 'title')" maxlength="75"  required>
    <label for="titleInput">Event Title</label>
</div>
<a id="title_msg" class="input-warning text-danger"></a>

<script>
    //Initial variable.
    var check_title = false;

    //Validator
    function lengValidator(len, type){
        if(type == "title"){
            if($("#titleInput").val().length >= len){
                $("#title_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> You reaches the maximum character");
                check_title = true;
            } else {
                $("#title_msg").text("");
            }

            if($("#titleInput").val().length <= 6){
                $("#btn-submit-holder").html("");
            } else {
                $("#btn-submit-holder").html('<button type="submit" onclick="getRichText()" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
            }
        }
    }
</script>