<div class="position-relative ms-2">
    <button class="btn btn-primary px-3" type="button" id="section-date-picker" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false"><i class="fa-solid fa-calendar"></i> Filter Date 
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-date-picker">
        <span class="filter-section dropdown-item p-0">
            <div class="dropdown-header">
                <h6 class="dropdown-title">Filter Date</h6>
            </div><hr>
            <div class="dropdown-body">
                <form action="/homepage/date" method="POST" class="row">
                    @csrf

                    @if(session()->get('filtering_date') && session()->get('filtering_date') != "all")
                        @php($date_full = session()->get('filtering_date'))
                        @php($date = explode("_", $date_full))
                    @endif
                    <div class="row my-2">
                        <div class="col-5">
                            <label class="form-label">From</label>
                            <input type="date" class="form-control" name="date_start" id="date_filter_start" 
                            value="<?php
                                if(session()->get('filtering_date') && session()->get('filtering_date') != "all"){
                                    echo $date[0]; 
                                } 
                            ?>" onchange="validateDateFilter()">
                            <a id="date_filter_msg_start" class="input-warning text-danger"></a>
                            <div class="mt-2" id="date-filter-submit-holder"></div>
                        </div>
                        <div class="col-5">
                            <label class="form-label">Until</label>
                            <input type="date" class="form-control" name="date_end" id="date_filter_end" 
                            value="<?php 
                                if(session()->get('filtering_date') && session()->get('filtering_date') != "all"){
                                    echo $date[1];
                                } 
                            ?>" onchange="validateDateFilter()">
                            <a id="date_filter_msg_end" class="input-warning text-danger"></a>
                        </div>
                </form>            

                        <div class="col-1">
                            <br>
                            <form action="/homepage/date/reset" method="POST">
                                @csrf
                                <button class="btn btn-danger-icon-outlined mt-2" title="Reset" type="submit"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                    </div>
                    <a id="date_filter_msg_all" class="input-warning text-danger"></a>
            </div>
        </span>

        <!-- Mini calendar -->
        <!-- <div class="calendar-section">
            <div class="calendar calendar-first" id="calendar_first">
                <div class="calendar_header">
                <button class="switch-month switch-left">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <h2></h2>
                <button class="switch-month switch-right">
                    <i class="fa fa-chevron-right"></i>
                </button>
                </div>
                <hr>
                <div class="calendar_weekdays"></div>
                <div class="calendar_content"></div>
            </div>
        </div> -->
    </div>
</div>

<script>
    var error = true;
    var mini_calendar;

    function transferMiniCalendar(start, end){
        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        }

        var dateArray = new Array();
        var currentDate = start;
        while (currentDate <= end) {
            var fulldate = new Date(currentDate)
            dateArray.push(fulldate.getDate());
            currentDate = currentDate.addDays(1);
        }

        var dateArray = {"2023":{"2":dateArray}}
        
        console.log(dateArray);

        // {"2023":{"2":[24,25,26,27,28,29,30]}}
        //For now still days only
        mini_calendar = dateArray;
    }

    function validateDateFilter(){
        var today = new Date();
        var date_filter_start = $("#date_filter_start").val();
        var date_filter_end = $("#date_filter_end").val();
        var ds = new Date(date_filter_start);
        var de = new Date(date_filter_end);

        function finalValidate(){
            //Event date start and date end validator if all date is filled
            if(de < ds ){
                $("#date_filter_msg_all").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event's end time earlier than the start time"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#date_filter_msg_all").text("");
                error = false;
            }
        }

        //Check if empty.
        if(!date_filter_start || !date_filter_end){
            //Highlight input if empty.
            if(!date_filter_start){
                $("#date_filter_start").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_filter_start").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_filter_end){
                $("#date_filter_end").css({"border":"2px solid #F85D59"});
                error = true;
            } else {
                $("#date_filter_end").css({"border":"1.5px solid #CCCCCC"});
            }

            //Event date and today validator if only one datetime is filled
            if(ds < today){
                $("#date_filter_msg_start").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#date_filter_msg_start").text("");
            }
            if(de < today){
                $("#date_filter_msg_end").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#date_filter_msg_end").text("");
            }
        } else {
            //Event datetime and today validator

            //Unhighlight all filled input
            $("#date_filter_start").css({"border":"1.5px solid #CCCCCC"});
            $("#date_filter_end").css({"border":"1.5px solid #CCCCCC"});
            
            //Event date and today validator if only all datetime is filled
            if(ds < today){
                $("#date_filter_msg_start").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#date_filter_msg_start").text("");
                finalValidate();
            }
            if(de < today){
                $("#date_filter_msg_end").html("<i class='fa-solid fa-triangle-exclamation'></i> Unable to set event to a past date"); //Check this poor grammar LOL
                error = true;
            } else {
                $("#date_filter_msg_end").text("");
                finalValidate();
            }

            transferMiniCalendar(ds, de);
        }

        if(!error){
            $("#date-filter-submit-holder").html('<button class="btn btn-submit w-75" style="width:60px;"><i class="fa-solid fa-filter"></i> Filter</button>');
        } else {
            $("#date-filter-submit-holder").html("");
        }
                    //console.log(JSON.stringify(mini_calendar));

    }
</script>