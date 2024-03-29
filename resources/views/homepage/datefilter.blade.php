<div class="position-relative ms-1">
    <button class="btn btn-primary px-3 py-2" type="button" id="section-date-picker" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false"><i class="fa-solid fa-calendar"></i>@if(!$isMobile) {{ __('messages.filter_date') }} @endif 
    </button>
    <div class="filter-section dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-date-picker">
        <span class="filter-section dropdown-item p-0">
            <div class="dropdown-header position-relative">
                <h6 class="dropdown-title">{{ __('messages.filter_date') }}</h6>
                @if($isMobile)
                    <form action="/homepage/date/reset" method="POST">
                        @csrf
                        <button class="btn btn-danger-icon-outlined mt-2 position-absolute" style="right:var(--spaceXMD); top:0;" title="Reset" type="submit"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                @endif
            </div><hr>
            <div class="dropdown-body">
                <form action="/homepage/date" method="POST" class="@if($isMobile) px-3 @else row @endif">
                    @csrf

                    @if(session()->get('filtering_date') && session()->get('filtering_date') != "all")
                        @php($date_full = session()->get('filtering_date'))
                        @php($date = explode("_", $date_full))
                    @endif

                    @if(!$isMobile)
                        <div class="row my-2">
                            <div class="col-5">
                    @endif

                    <label class="form-label">{{ __('messages.start_from') }}</label>
                    <input type="date" class="form-control" name="date_start" id="date_filter_start" 
                    value="<?php
                        if(session()->get('filtering_date') && session()->get('filtering_date') != "all"){
                            echo $date[0]; 
                        } 
                    ?>" onchange="validateDateFilter()">
                    <a id="date_filter_msg_start" class="input-warning text-danger"></a>
                   

                    @if(!$isMobile)
                            <div class="mt-2" id="date-filter-submit-holder"></div>
                            </div>
                        <div class="col-5">
                    @endif

                    <label class="form-label">{{ __('messages.until') }}</label>
                    <input type="date" class="form-control" name="date_end" id="date_filter_end" 
                    value="<?php 
                        if(session()->get('filtering_date') && session()->get('filtering_date') != "all"){
                            echo $date[1];
                        } 
                    ?>" onchange="validateDateFilter()">
                    <a id="date_filter_msg_end" class="input-warning text-danger"></a>

                    @if(!$isMobile)
                        </div>
                    @endif
                    
                    @if($isMobile)
                        <div class="mt-3 text-center" id="date-filter-submit-holder"></div>
                    @endif
                </form>            
                    @if(!$isMobile)
                        <div class="col-1">
                            <br>
                            <form action="/homepage/date/reset" method="POST">
                                @csrf
                                <button class="btn btn-danger-icon-outlined mt-2" title="Reset" type="submit"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <a id="date_filter_msg_all" class="input-warning text-danger"></a>
            </div>
        </span>
    </div>
</div>

<script>
    var error = true;

    function validateDateFilter(){
        var today = new Date();
        var date_filter_start = $("#date_filter_start").val();
        var date_filter_end = $("#date_filter_end").val();
        var ds = new Date(date_filter_start);
        var de = new Date(date_filter_end);

        function finalValidate(){
            if(de < ds ){
                $("#date_filter_msg_all").html(`<i class='fa-solid fa-triangle-exclamation'></i> ${messages('end_date_invalid')}`);
                error = true;
            } else {
                $("#date_filter_msg_all").text("");
                error = false;
            }
        }

        if(!date_filter_start || !date_filter_end){
            if(!date_filter_start){
                $("#date_filter_start").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_filter_start").css({"border":"1.5px solid #CCCCCC"});
            }

            if(!date_filter_end){
                $("#date_filter_end").css({"border":"2px solid var(--warningBG)"});
                error = true;
            } else {
                $("#date_filter_end").css({"border":"1.5px solid #CCCCCC"});
            }

            if(ds < today){
                $("#date_filter_msg_start").html(`<i class='fa-solid fa-triangle-exclamation'></i> ${messages('end_date_now_invalid')}`); 
                error = true;
            } else {
                $("#date_filter_msg_start").text("");
            }
            if(de < today){
                $("#date_filter_msg_end").html(`<i class='fa-solid fa-triangle-exclamation'></i> ${messages('end_date_now_invalid')}`); 
                error = true;
            } else {
                $("#date_filter_msg_end").text("");
            }
        } else {
            $("#date_filter_start").css({"border":"1.5px solid #CCCCCC"});
            $("#date_filter_end").css({"border":"1.5px solid #CCCCCC"});
            
            if(ds < today){
                $("#date_filter_msg_start").html(`<i class='fa-solid fa-triangle-exclamation'></i> ${messages('end_date_now_invalid')}`); 
                error = true;
            } else {
                $("#date_filter_msg_start").text("");
                finalValidate();
            }
            if(de < today){
                $("#date_filter_msg_end").html(`<i class='fa-solid fa-triangle-exclamation'></i> ${messages('end_date_now_invalid')}`); 
                error = true;
            } else {
                $("#date_filter_msg_end").text("");
                finalValidate();
            }
        }

        if(!error){
            $("#date-filter-submit-holder").html('<button class="btn btn-submit w-75" style="width:60px;"><i class="fa-solid fa-filter"></i> Filter</button>');
        } else {
            $("#date-filter-submit-holder").html("");
        }
    }
</script>