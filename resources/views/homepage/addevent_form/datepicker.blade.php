<label>Set Date Start</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="content_date_start" id="date_start" onchange="validateDate()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="content_time_start" id="time_start" onchange="validateDate()" class="form-control mb-2">
    </div>
</div>
<a id="dateStart_msg" class="input-warning text-danger"></a>

<label>Set Date End</label>
<div class="row mt-2">
    <div class="col-6">
        <input type="date" name="content_date_end" id="date_end" onchange="validateDate()" class="form-control">
    </div>
    <div class="col-6">
        <input type="time" name="content_time_end" id="time_end" onchange="validateDate()" class="form-control mb-2">
    </div>
</div>
<a id="dateEnd_msg" class="input-warning text-danger"></a><br>
<a id="dateStartEnd_msg" class="input-warning text-danger"></a>

<script>
    var error = false;

    function validateDate(){
        var today = new Date();
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var time_start = $("#time_start").val();
        var time_end = $("#time_end").val();
        var ds = new Date(date_start+" "+time_start);
        var de = new Date(date_end+" "+time_end);

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
        if(!date_start || !date_end || !time_start || !time_end){
            //Highlight input if empty.
            if(!date_start){
                $("#date_start").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_start").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_end){
                $("#date_end").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_end").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_start){
                $("#time_start").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#time_start").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!time_end){
                $("#time_end").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#time_end").css({"border":"1.5px solid #CCCCCC"});
            }

            //Event date and today validator if only one datetime is filled
            if(ds < today && time_start){
                $("#dateStart_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_msg").text("");
            }
            if(de < today && time_end){
                $("#dateEnd_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_msg").text("");
            }
        } else {
            //Event datetime and today validator

            //Unhighlight all filled input
            $("#date_start").css({"border":"1.5px solid #CCCCCC"});
            $("#date_end").css({"border":"1.5px solid #CCCCCC"});
            $("#time_start").css({"border":"1.5px solid #CCCCCC"});
            $("#time_end").css({"border":"1.5px solid #CCCCCC"});
            
            //Event date and today validator if only all datetime is filled
            if(ds < today){
                $("#dateStart_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateStart_msg").text("");
                finalValidate();
            }
            if(de < today){
                $("#dateEnd_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#dateEnd_msg").text("");
                finalValidate();
            }
        }

        if(!error){
            $("#btn-submit-holder").html('<button type="submit" onclick="getRichText()" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        } else {
            $("#btn-submit-holder").html("");
        }
    }
</script>