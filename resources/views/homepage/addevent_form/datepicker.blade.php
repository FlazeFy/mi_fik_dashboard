<label>Set Date Start</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="content_date_start" id="date_start_event" onchange="validateDateEvent()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="content_time_start" id="time_start_event" onchange="validateDateEvent()" class="form-control mb-2">
    </div>
</div>
<a id="dateStart_event_msg" class="input-warning text-danger"></a>

<label>Set Date End</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="content_date_end" id="date_end_event" onchange="validateDateEvent()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="content_time_end" id="time_end_event" onchange="validateDateEvent()" class="form-control mb-2">
    </div>
</div>
<a id="dateEnd_event_msg" class="input-warning text-danger"></a><br>
<a id="dateStartEnd_msg" class="input-warning text-danger"></a>

<script>
    var error = false;

    function validateDateEvent(){
        var today = new Date();
        var date_start_event = $("#date_start_event").val();
        var date_end_event = $("#date_end_event").val();
        var time_start_event = $("#time_start_event").val();
        var time_end_event = $("#time_end_event").val();
        var ds = new Date(date_start_event+" "+time_start_event);
        var de = new Date(date_end_event+" "+time_end_event);

        function finalValidate(){
            //Event date start and date end validator if all date is filled
            if(de < ds ){
                $("#dateStartEnd_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event's end time earlier than the start time"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStartEnd_msg").text("");
                error = false;
            }
        }

        //Check if empty.
        if(!date_start_event || !date_end_event || !time_start_event || !time_end_event){
            //Highlight input if empty.
            if(!date_start_event){
                $("#date_start_event").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_start_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_end_event){
                $("#date_end_event").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_end_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_start_event){
                $("#time_start_event").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#time_start_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_end_event){
                $("#time_end_event").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#time_end_event").css({"border":"1.5px solid #CCCCCC"});
            }

            //Event date and today validator if only one datetime is filled
            if(ds < today && time_start_event){
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_event_msg").text("");
            }
            if(de < today && time_end_event){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_event_msg").text("");
            }
        } else {
            //Event datetime and today validator

            //Unhighlight all filled input
            $("#date_start_event").css({"border":"1.5px solid #CCCCCC"});
            $("#date_end_event").css({"border":"1.5px solid #CCCCCC"});
            $("#time_start_event").css({"border":"1.5px solid #CCCCCC"});
            $("#time_end_event").css({"border":"1.5px solid #CCCCCC"});
            
            //Event date and today validator if only all datetime is filled
            if(ds < today){
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_event_msg").text("");
                finalValidate();
            }
            if(de < today){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_event_msg").text("");
                finalValidate();
            }
        }

        if(!error){
            $("#btn-submit-holder-event").html('<button type="submit" onclick="getRichText()" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        } else {
            $("#btn-submit-holder-event").html("");
        }
    }
</script>