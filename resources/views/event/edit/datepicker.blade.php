<form action="/event/edit/update/date/{{$c->slug_name}}" method="POST">
    @csrf
    <label>Date Start</label>
    <div class="row mt-2">
        <div class="col-6">
            <input type="date" name="content_date_start" id="date_start_event" min="{{date('Y-m-d')}}" value="{{date('Y-m-d',strtotime($c->content_date_start))}}" onchange="validateDateEvent()" class="form-control">
        </div>
        <div class="col-6">
            <input type="time" name="content_time_start" id="time_start_event" value="{{date('H:i',strtotime($c->content_date_start))}}" onchange="validateDateEvent()" class="form-control mb-2">
        </div>
    </div>
    <a id="dateStart_event_msg" class="input-warning text-danger"></a><br>

    <label>Date End</label>
    <div class="row mt-2">
        <div class="col-6">
            <input type="date" name="content_date_end" id="date_end_event" min="{{date('Y-m-d')}}" value="{{date('Y-m-d',strtotime($c->content_date_end))}}" onchange="validateDateEvent()" class="form-control">
        </div>
        <div class="col-6">
            <input type="time" name="content_time_end" id="time_end_event" value="{{date('H:i',strtotime($c->content_date_end))}}" onchange="validateDateEvent()" class="form-control mb-2">
        </div>
    </div>
    <a id="dateEnd_event_msg" class="input-warning text-danger"></a><br>
    <a id="dateStartEnd_msg" class="input-warning text-danger"></a>
    <div id="btn-submit-holder-date"></div>
</form>

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

        var val_ds = document.getElementById("date_start_event");
        var val_ts = document.getElementById("time_start_event");
        var val_de = document.getElementById("date_end_event");
        var val_te = document.getElementById("time_end_event");

        if(val_ds.value != null){
            if(getDateToContext(val_ds.value, "date") == getDateToContext(today, "date")){
                val_ts.setAttribute("min",getDateToContext(today, "24h"));
                if(val_ts.value === ''){
                    val_ts.value = getDateToContext(today, "24h");
                }
            }

            // Set minimal date 
            // val_de.removeAttribute("min");
            // val_de.setAttribute("min",getDateToContext(val_ds.value, "date"));

            if(val_ds.value > val_de.value){
                val_de.value = getDateToContext(val_ds.value, "date");
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
            $("#btn-submit-holder-date").html('<button type="submit" class="btn btn-submit"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        } else {
            $("#btn-submit-holder-date").html("");
        }
    }
</script>