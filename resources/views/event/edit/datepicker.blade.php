<?php
    use Carbon\Carbon;
?>

<input id="content_date_start_check" value="{{Carbon::parse($c->content_date_start)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}" hidden>
<input id="content_date_end_check" value="{{Carbon::parse($c->content_date_end)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}" hidden>

<div id="edit-date-holder">
    <form action="/event/edit/update/date/{{$c->slug_name}}" id="form-edit-date" method="POST">
        @csrf
        <input name="is_end_only" id="is_end_only" value="false" hidden>
        <label>{{ __('messages.set_date_start') }}</label>    
        <div class="row mt-2">
            <div class="col-6">
                <input type="date" name="content_date_start" id="date_start_event" value="{{date('Y-m-d',strtotime($c->content_date_start))}}" onchange="validateDateEvent()" class="form-control">
            </div>
            <div class="col-6">
                <input type="time" name="content_time_start" id="time_start_event" value="{{date('H:i',strtotime($c->content_date_start))}}" onchange="validateDateEvent()" class="form-control mb-2">
            </div>
        </div>
        <a id="dateStart_event_msg" class="input-warning text-danger"></a>

        <label>{{ __('messages.set_date_end') }}</label>
        <div class="row mt-2">
            <div class="col-6">
                <input type="date" name="content_date_end" id="date_end_event" value="{{date('Y-m-d',strtotime($c->content_date_end))}}" onchange="validateDateEvent()" class="form-control">
            </div>
            <div class="col-6">
                <input type="time" name="content_time_end" id="time_end_event" value="{{date('H:i',strtotime($c->content_date_end))}}" onchange="validateDateEvent()" class="form-control mb-2">
            </div>
        </div>
        <a id="dateEnd_event_msg" class="input-warning text-danger"></a>
        <a id="dateStartEnd_msg" class="input-warning text-danger"></a>
        <a id="summary_msg" class="input-warning text-danger"></a>
        <a id="invalid_period_msg" class="input-warning text-danger"></a>
        <div id="btn-submit-holder-date"></div>
    </form>
    @include('components.infobox', ['info' => $info, 'location'=> "edit_date"])
</div>
<div id="prevent-date-holder">
    <img src="{{asset('assets/pending.png')}}" class="img nodata-icon" style="height:18vh;">
    <h6 class="text-center text-secondary">This Event is Finished</h6>
</div>

<script>
    var error = false;
    var ds_event = document.getElementById("date_start_event");
    var ts_event = document.getElementById("time_start_event");
    var de_event = document.getElementById("date_end_event");
    var te_event = document.getElementById("time_end_event");
    var is_end = document.getElementById("is_end_only");
    var editable_date_start = true;

    validateEditDate();

    function validateEditDate(){
        var date_start = document.getElementById("content_date_start_check").value;
        var date_end = document.getElementById("content_date_end_check").value;
        const now = new Date(Date.now());
        const res_start = new Date(date_start);
        const res_end = new Date(date_end);

        ds_event.value = getDateToContext(res_start, "date");
        
        ts_event.value = getDateToContext(res_start, "24h");
        de_event.value = getDateToContext(res_end, "date");
        de_event.min = getDateToContext(now, "date");
        te_event.value = getDateToContext(res_end, "24h");

        setDatePickerMin("date_end_event", ds_event.value);

        if(now < res_start){
            ds_event.min = getDateToContext(now, "date");
        } else {
            ds_event.min = getDateToContext(res_start, "date");
        }

        if(res_start.toDateString() == now.toDateString()){
            ds_event.disabled = true;
            editable_date_start = false;
            if(now > res_start){
                ts_event.disabled = true;
                is_end.value = "true";
            }
            is_end.value = "false";
        } else {
            if(now > res_start){
                editable_date_start = false;
                ds_event.disabled = true;
                ts_event.disabled = true;
                is_end.value = "true";
            }
        }

        const remain_min = getMinutesDifference(now, res_end);
        if(remain_min < 30){
            document.getElementById("summary_msg").innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> This event is about to end in " + remain_min + " minutes";
        }
        
        if(now > res_end){
            document.getElementById("edit-date-holder").hidden = true;
            document.getElementById("prevent-date-holder").hidden = false;
        } else {
            document.getElementById("edit-date-holder").hidden = false;
            document.getElementById("prevent-date-holder").hidden = true;
        }
    }

    function submitEdit(){
        if(getHourFromTime(ts_event.value) < getUTCHourOffset()){
            const ds_date = new Date(ds_event.value);
            ds_event.value = getDateToContext(ds_date.setDate(ds_date.getDate() - 1), "date"); 
        }
        
        ds_event.disabled = false;

        var res_end = subtractOffsetFromTime(te_event.value);
        if(getHourFromTime(te_event.value) < getUTCHourOffset()){
            const de_date = new Date(de_event.value);
            de_event.value = getDateToContext(de_date.setDate(de_date.getDate() - 1), "date"); 
        }
       
        te_event.value = getDateToContext(res_end, "24h");

        if(is_end.value != "true"){
            var res_start = subtractOffsetFromTime(ts_event.value);
            ts_event.value = getDateToContext(res_start, "24h");
        }
        document.getElementById("form-edit-date").submit();
    }

    function validateDateEvent(){
        var now = new Date();
        var date_start_event = $("#date_start_event").val();
        var date_end_event = $("#date_end_event").val();
        var time_start_event = $("#time_start_event").val();
        var time_end_event = $("#time_end_event").val();
        var ds = new Date(date_start_event+" "+time_start_event);
        var de = new Date(date_end_event+" "+time_end_event);

        var val_ds = document.getElementById("date_start_event");
        var val_ts = document.getElementById("time_start_event");
        var val_de = document.getElementById("date_end_event");
        var val_te = document.getElementById("time_end_event");

        if(val_ds.value != null){
            if(getDateToContext(val_ds.value, "date") == getDateToContext(now, "date")){
                val_ts.setAttribute("min",getDateToContext(now, "24h"));
                if(val_ts.value === ''){
                    val_ts.value = getDateToContext(now, "24h");
                }
            }

            if(val_ds.value > val_de.value){
                val_de.value = getDateToContext(val_ds.value, "date");
            }
            setDatePickerMin("date_end_event", ds_event.value);
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
            if(ds < now && time_start_event){
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date <br>");
                error = true;
            } else {
                $("#dateStart_event_msg").text("");
            }
            if(de < now && time_end_event){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date <br>");
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
            if(ds < now){
                $("#dateStart_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date <br>");
                error = true;
            } else {
                $("#dateStart_event_msg").text("");
            }
            if(de < now){
                $("#dateEnd_event_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date <br>");
                error = true;
            } else {
                $("#dateEnd_event_msg").text("");
            }
            if(getDateToContext(ds, "datetime") === getDateToContext(de, "datetime")){
                $("#invalid_period_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event with same date start and end");
                error = true;
            } else {
                $("#invalid_period_msg").text("");
            }
            if(de <= ds){
                $("#dateStartEnd_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event's end time earlier than the start time");
                error = true;
            } else {
                $("#dateStartEnd_msg").text("");
            }

            if(de > ds && ds > now && de > now){
                error = false;
            }
        }
     

        if(!error){
            $("#btn-submit-holder-date").html(`<a onclick="submitEdit()" class="btn btn-submit"><i class="fa-solid fa-paper-plane"></i> {{ __('messages.submit') }}</a>`);
        } else {
            $("#btn-submit-holder-date").html("");
        }
    }
</script>