<label class="input-title">Set Date Start</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="content_date_start" id="date_start_event" onchange="validateDateEvent()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="content_time_start" id="time_start_event" onchange="validateDateEvent()" class="form-control mb-2">
    </div>
</div>
<p id="dateStart_event_msg" class="input-warning text-danger"></p>

<label class="input-title">Set Date End</label>
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
    var val_ds = document.getElementById("date_start_event");
    var val_ts = document.getElementById("time_start_event");
    var val_de = document.getElementById("date_end_event");
    var val_te = document.getElementById("time_end_event");    

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
            if(de <= ds ){
                $("#dateStartEnd_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event's end time earlier than the start time");
                error = true;
            } else {
                $("#dateStartEnd_msg").text("");
                error = false;
            }
            lengValidatorEvent('75', 'title');
        }

        if(val_ds.value != null){
            if(getDateToContext(val_ds.value, "date") == getDateToContext(today, "date")){
                var min_ts = subtractOffsetFromTime(getDateToContext(today, "date"));
                val_ts.setAttribute("min",getDateToContext(min_ts, "24h"));
                if(val_ts.value === ''){
                    val_ts.value = getDateToContext(min_ts, "24h");
                }
            } 

            if(val_ds.value > val_de.value){
                val_de.value = getDateToContext(val_ds.value, "date");
                setDatePickerMin("date_end_event", val_ds.value);
            }
        } 

        //Check if empty.
        if(!date_start_event || !date_end_event || !time_start_event || !time_end_event){
            //Highlight input if empty.
            if(!date_start_event){
                $("#date_start_event").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_start_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_end_event){
                $("#date_end_event").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_end_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_start_event){
                $("#time_start_event").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#time_start_event").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_end_event){
                $("#time_end_event").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#time_end_event").css({"border":"1.5px solid #CCCCCC"});
            }

            //Event date and today validator if only one datetime is filled
            if(ds < today && time_start_event){
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date");
                error = true;
            } else {
                $("#dateStart_event_msg").text("");
            }
            if(de < today && time_end_event){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date");
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
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date");
                error = true;
                finalValidate();
            } else {
                $("#dateStart_event_msg").text("");
                finalValidate();
            }
            if(de < today){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date");
                error = true;
                finalValidate();
            } else {
                $("#dateEnd_event_msg").text("");
                finalValidate();
            }

            if(getDateToContext(ds, "datetime") === getDateToContext(de, "datetime")){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event with same date start and end");
                error = true;
            } else {
                loadReminder(ds, today);
                $("#dateStart_event_msg").text("");
                finalValidate();
            }
        }
    }

    function setEventPeriodBasedTimezone(){
        var res_end = subtractOffsetFromTime(val_te.value);
        var res_start = subtractOffsetFromTime(val_ts.value);

        val_te.value = getDateToContext(res_end, "24h");
        val_ts.value = getDateToContext(res_start, "24h");    
    }
</script>