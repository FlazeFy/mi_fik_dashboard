<div class="form-floating">
    <input type="text" class="form-control nameInput" id="titleInput_event" name="content_title" oninput="lengValidatorEvent('75', 'title')" maxlength="75"  required>
    <label for="titleInput_event">{{ __('messages.title') }}</label>
</div>
<a id="title_msg_event" class="input-warning text-danger"></a>

<script>
    //Initial variable.
    var check_title = false;

    //Validator
    function lengValidatorEvent(len, type){
        var date_start_event = $("#date_start_event").val();
        var date_end_event = $("#date_end_event").val();
        var time_start_event = $("#time_start_event").val();
        var time_end_event = $("#time_end_event").val();
        
        if(type == "title"){
            if($("#titleInput_event").val().length >= len){
                $("#title_msg_event").html("<i class='fa-solid fa-triangle-exclamation'></i> Reaching maximum character length");
                check_title = true;
            } else {
                $("#title_msg_event").text("");
            }

            if($("#titleInput_event").val().length <= 6 || slct_list.length == 0 || (!Date.parse(date_start_event) && !Date.parse(date_end_event) && !Date.parse(time_start_event) && !Date.parse(time_end_event)) || error){
                $("#btn-submit-holder-event").html(`<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button>`);
            } else {
                $("#btn-submit-holder-event").html(`<button type="submit" onclick="getRichText(); setEventPeriodBasedTimezone();" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Publish Event</button>`);
            }
        }
    }
</script>