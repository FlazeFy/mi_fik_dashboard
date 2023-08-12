<style>
    .input-warning{
        font-size: 14px;
    }
    .btn-quick-action{
        border-radius: var(--roundedMini);
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height: 15vh;
        border: none;
        width: 100%;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        background-size: cover;
        transition: 0.5s;
        text-align: left;
        padding: 10px;
    }
    .btn-quick-action:hover{
        background: var(--primaryColor) !important;
        background-image: none !important;
    }
    .quick-action-text{
        font-size: 24px;
        color:var(--whiteColor);
        transition: 0.5s;
        margin-top: 9vh;
    }
    .quick-action-info{
        font-size: var(--textXMD);
        color:var(--whiteColor);
        transition: 0.5s;
        display: none;
    }
    .btn-quick-action:hover .quick-action-text{
        margin-top:-10px;
    }
    .btn-quick-action:hover .quick-action-info{
        display: block;
    }
    .btn-tag{
        background:var(--whiteColor);
        padding: 6px 8px;
        border-radius: var(--roundedSM);
        margin:4px;
        color:var(--darkColor);
        font-weight:400;
        border:1.5px solid var(--primaryColor);
    }
    .btn-tag:hover, .btn-tag-selected{
        background:var(--primaryColor);
        padding: 6px 8px;
        border-radius: var(--roundedSM);
        margin:4px;
        color:var(--whiteColor) !important;
    }
    .custom-submit-modal {
        position: relative !important; 
        width: 100%;
        bottom:0;
    }
</style>

<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/event.png'); ?>"); background-color:#FB5E5B;'
    data-bs-target="
        <?php 
            if(count($mydraft) > 1 || (count($mydraft) == 1 && $mydraft[0]['slug_name'] != null)){
                echo "#browseDraftEventModal";
            } else {
                echo "#addEventModal";
            }
        ?>" data-bs-toggle="modal" onclick="setDatePickerMinNow('date_start_event'); setDatePickerMinNow('date_end_event'); infinteLoadMoreTag(1); loadReminder(null, null);">

    @if(count($mydraft) > 1 || (count($mydraft) == 1 && $mydraft[0]['slug_name'] != null))
        <a class="warning-draft" title="You have some draft event"><i class="fa-solid fa-triangle-exclamation"></i> {{count($mydraft)}}</a>
    @endif

    <h5 class="quick-action-text"><i class="fa-solid fa-plus"></i> {{ __('messages.add_event') }}</h5>
    <p class="quick-action-info">{{ __('messages.add_event_desc') }}</p>
</button>

@if(count($mydraft) > 1 || (count($mydraft) == 1 && $mydraft[0]['slug_name'] != null))
    @include('homepage.addevent_form.draftevent')
@endif

<div class="modal fade" id="addEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">  
            <form action="/homepage/add_event" method="POST" enctype="multipart/form-data" id="form-add-event">
                @csrf 
                <div class="modal-body pt-4 position-relative">
                    <input hidden id="slug_name" name="slug_name">
                    <button type="button" class="custom-close-modal" onclick="clean(); <?php if($isMobile){ echo 'closeControlModal()'; } ?>" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <h5>{{ __('messages.add_event') }}</h5>
                    <div class="row my-2">
                        <div class="col-lg-8">
                            @include('homepage.addevent_form.titleinput')
            
                            @include('homepage.addevent_form.descinput')

                            <br><label class="input-title">{{ __('messages.event_loc') }}</label><br>
                            @include('homepage.addevent_form.locationpicker')

                            <label class="input-title my-2">{{ __('messages.att') }}</label><br>
                            @include('homepage.addevent_form.attachment')

                            @include('components.infobox',['info'=>$info, 'location'=> 'add_event'])
                        </div>
                        <div class="col-lg-4">
                            <span id="btn-submit-holder-event"><button disabled class="custom-submit-modal w-100 m-0" style="position:relative !important; bottom:0;"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span><br><br>

                            <label class="input-title">{{ __('messages.event_image') }}</label><br>
                            @include('homepage.addevent_form.contentimage')

                            @include('homepage.addevent_form.datepicker')
                            <label class="input-title">{{ __('messages.set_reminder') }}</label>
                            <select class="form-select" id="selectReminder" name="content_reminder" aria-label="Floating label select example"></select>

                            <br>@include('homepage.addevent_form.tagpicker')
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('form-add-event').addEventListener('keydown', function(event) {
        if (event.keyCode === 13) { // 13 is the key code for Enter key
            event.preventDefault(); 
        }
    });

    const reminderOpt = [<?php 
        foreach($dictionary as $dct) {
            if($dct->type_name == "Reminder") {
                echo "{slug_name: '".$dct->slug_name."', dct_name: '".$dct->dct_name."'},";
            }
        }
    ?>];
    var selectedReminder = "reminder_none";

    function loadReminder(ds, now){
        var ctx = "";
        $("#selectReminder").empty();
        if(ds != null && now != null){
            const nowDate = new Date(now.setHours(now.getHours() + 1));
            const startDate = new Date(ds);
            const remain = getMinutesDifference(nowDate, startDate);
            
            $("#selectReminder").append(`<option value="reminder_none" selected>None</option>`);
            if(remain > 0){
                $("#selectReminder").append(`<option value="reminder_1_hour_before">1 hour before</option>`);
            } 
            if(remain > 180){ 
                $("#selectReminder").append(`<option value="reminder_3_hour_before">3 hour before</option>`);
            } 
            if(remain > 1440){
                $("#selectReminder").append(`<option value="reminder_1_day_before">1 day before</option>`);
            } 
            if(remain > 4320){
                $("#selectReminder").append(`<option value="reminder_3_day_before">3 day before</option>`);
            }   
        } else {
            reminderOpt.forEach(e => {
                selectedReminder == e.slug_name ? ctx = "selected" : ctx = "";

                $("#selectReminder").append(`<option value="${e.slug_name}" ${ctx}>${e.dct_name}</option>`);
            });
        }
    }

    function closeControlModal(){
        $('#browseDraftEventModal').modal({ backdrop: 'static' }).modal('hide');
        $('#controlModal').modal('hide');
    }
    
    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-event');
            const inputs = form.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token" && document.getElementById("slug_name").value.trim() == "") {
                    is_editing = true;
                    break;
                }
            }

            if(is_editing || attach_list.length > 0){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });
</script>

<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyD2gQjgUllPlhU-1GKthMcrArdShT2AIPU",
        authDomain: "mifik-83723.firebaseapp.com",
        projectId: "mifik-83723",
        storageBucket: "mifik-83723.appspot.com",
        messagingSenderId: "38302719013",
        appId: "1:38302719013:web:23e7dc410514ae43d573cc",
        measurementId: "G-V13CR730JG"
    };
    firebase.initializeApp(firebaseConfig);
</script>