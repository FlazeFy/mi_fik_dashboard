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
</style>

<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/event.png'); ?>"); background-color:#FB5E5B;'
    data-bs-target="
        <?php 
            if(count($mydraft) > 1 || (count($mydraft) == 1 && $mydraft[0]['slug_name'] != null)){
                echo "#browseDraftEventModal";
            } else {
                echo "#addEventModal";
            }
        ?>" data-bs-toggle="modal" onclick="setDatePickerMinNow('date_start_event'); setDatePickerMinNow('date_end_event'); infinteLoadMoreTag(1)">

    @if(count($mydraft) > 1 || (count($mydraft) == 1 && $mydraft[0]['slug_name'] != null))
        <a class="warning-draft" title="You have some draft event"><i class="fa-solid fa-triangle-exclamation"></i> {{count($mydraft)}}</a>
    @endif

    <h5 class="quick-action-text">Add Event</h5>
    <p class="quick-action-info">Event is an information about some activity that will be held in the future.</p>
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
                    <span class="text-success position-absolute" style="top:30px; right:30px;" id="draft-status"></span>
                    <button type="button" class="custom-close-modal" onclick="clean(); <?php if($isMobile){ echo 'closeControlModal()'; } ?>" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <h5>Create Event</h5>
                    <div class="row my-2">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-8 pb-2">
                                    @include('homepage.addevent_form.titleinput')
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="floatingSelect" name="content_reminder" aria-label="Floating label select example">
                                            @php($i = 0)

                                            @foreach($dictionary as $dct)
                                                @if($dct->type_name == "Reminder")
                                                    @if($i == 0)
                                                        <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                                    @else
                                                        <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                                    @endif

                                                    @php($i++)
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="floatingSelect">Reminder</label>
                                    </div>
                                </div>
                            </div>
                            <!--Event desc w/ richtext editor-->
                            @include('homepage.addevent_form.descinput')

                            <div class="row mt-2">
                                <div class="col-lg-7">
                                    @include('homepage.addevent_form.tagpicker')
                                </div>
                                <div class="col-lg-5">
                                    @include('homepage.addevent_form.datepicker')
                                </div>
                            </div>
                            @include('components.infobox',['info'=>$info, 'location'=> 'add_event'])
                        </div>
                        <div class="col-lg-4">
                            <label class="input-title">Event Image</label><br>
                            @include('homepage.addevent_form.contentimage')

                            <label class="input-title">Event Location</label><br>
                            @include('homepage.addevent_form.locationpicker')

                            <label class="input-title my-2">Attachment</label><br>
                            @include('homepage.addevent_form.attachment')
                        </div>
                    </div>
                    <span id="btn-submit-holder-event"><button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button></span><br>
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
    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyD2gQjgUllPlhU-1GKthMcrArdShT2AIPU",
        authDomain: "mifik-83723.firebaseapp.com",
        projectId: "mifik-83723",
        storageBucket: "mifik-83723.appspot.com",
        messagingSenderId: "38302719013",
        appId: "1:38302719013:web:23e7dc410514ae43d573cc",
        measurementId: "G-V13CR730JG"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>