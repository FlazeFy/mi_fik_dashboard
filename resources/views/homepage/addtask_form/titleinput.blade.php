<div class="form-floating">
    <input type="text" class="form-control nameInput" id="titleInput_task" name="task_title" oninput="lengValidatorTask('75', 'title')" maxlength="75"  required>
    <label for="titleInput_task">Task Title</label>
</div>
<a id="title_msg_task" class="input-warning text-danger"></a>

<script>
    //Initial variable.
    var check_title = false;

    //Validator
    function lengValidatorTask(len, type){
        if(type == "title"){
            if($("#titleInput_task").val().length >= len){
                $("#title_msg_task").html("<i class='fa-solid fa-triangle-exclamation'></i> You reaches the maximum character");
                check_title = true;
            } else {
                $("#title_msg_task").text("");
            }

            if($("#titleInput_task").val().length == 0){
                $("#btn-submit-holder-task").html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
            } else {
                $("#btn-submit-holder-task").html('<button type="submit" onclick="getRichText()" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
            }
        }
    }
</script>