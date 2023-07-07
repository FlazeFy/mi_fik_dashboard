<label>Set Date Start</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="task_date_start" id="date_start" onchange="validateDateTask()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="task_time_start" id="time_start" onchange="validateDateTask()" class="form-control mb-2">
    </div>
</div>
<a id="dateStart_task_msg" class="input-warning text-danger"></a>

<label>Set Date End</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="task_date_end" id="date_end" onchange="validateDateTask()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="task_time_end" id="time_end" onchange="validateDateTask()" class="form-control mb-2">
    </div>
</div>
<a id="dateEnd_task_msg" class="input-warning text-danger"></a><br>
<a id="dateStartEnd_task_msg" class="input-warning text-danger"></a>

<script>
    var error = false;

    function validateDateTask(){
        var today = new Date();
        var date_start = $("#date_start_task").val();
        var date_end = $("#date_end_task").val();
        var time_start = $("#time_start_task").val();
        var time_end = $("#time_end_task").val();
        var ds = new Date(date_start+" "+time_start);
        var de = new Date(date_end+" "+time_end);

        function finalValidate(){
            //Event date start and date end validator if all date is filled
            if(de < ds ){
                $("#dateStartEnd_task_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event's end time earlier than the start time"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStartEnd_task_msg").text("");
                error = false;
            }
        }

        //Check if empty.
        if(!date_start || !date_end || !time_start || !time_end){
            //Highlight input if empty.
            if(!date_start){
                $("#date_start_task").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_start_task").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_end){
                $("#date_end_task").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_end_task").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_start){
                $("#time_start_task").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#time_start_task").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_end){
                $("#time_end_task").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#time_end_task").css({"border":"1.5px solid #CCCCCC"});
            }

            //Event date and today validator if only one datetime is filled
            if(ds < today && time_start){
                $("#dateStart_task_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_task_msg").text("");
            }
            if(de < today && time_end){
                $("#dateEnd_task_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_task_msg").text("");
            }
        } else {
            //Event datetime and today validator

            //Unhighlight all filled input
            $("#date_start_task").css({"border":"1.5px solid #CCCCCC"});
            $("#date_end_task").css({"border":"1.5px solid #CCCCCC"});
            $("#time_start_task").css({"border":"1.5px solid #CCCCCC"});
            $("#time_end_task").css({"border":"1.5px solid #CCCCCC"});
            
            //Event date and today validator if only all datetime is filled
            if(ds < today){
                $("#dateStart_task_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_task_msg").text("");
                finalValidate();
            }
            if(de < today){
                $("#dateEnd_task_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_task_msg").text("");
                finalValidate();
            }
        }

        if(!error){
            $("#btn-submit-holder-task").html('<button type="submit" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        } else {
            $("#btn-submit-holder-task").html("");
        }
    }
</script>