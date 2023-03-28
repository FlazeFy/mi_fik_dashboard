<style>
    .input-warning{
        font-size:14px;
    }
    .btn-quick-action{
        border-radius:6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:15vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        transition: 0.5s;
        text-align:left;
        padding:10px;
    }
    .btn-quick-action:hover{
        background: #F78A00 !important;
        background-image:none !important;
    }
    .quick-action-text{
        font-size:24px;
        color:white;
        transition: 0.5s;
        margin-top:9vh;
    }
    .quick-action-info{
        font-size:16px;
        color:white;
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action:hover .quick-action-text{
        margin-top:-4vh;
    }
    .btn-quick-action:hover .quick-action-info{
        display:block;
    }
    .btn-tag{
        background:white;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:#414141;
        font-weight:400;
        border:1.5px solid #F78A00;
    }
    .btn-tag:hover, .btn-tag-selected{
        background:#F78A00;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:whitesmoke !important;
    }

    /*Richtext header*/
    .ql-toolbar.ql-snow{
        border:1.7px solid #F78A00;
        margin-top:10px;
        border-radius:10px 10px 0 0;
    }
    .ql-snow .ql-stroke {
        stroke:#414141;
    }
    .ql-snow.ql-toolbar button{
        border-radius:6px;
        height:25px;
        width:25px;
        padding-left:3px;
        margin-right:10px;
        transition: all 0.4s;
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke{
        stroke:#F78A00;
    }
    button.ql-active{
        background:#F78A00 !important;
    }
    button.ql-active svg .ql-stroke{
        stroke:white !important;
    }

    /*Richtext body*/
    .ql-toolbar.ql-snow + .ql-container.ql-snow{
        height:30vh;
    }
    .input-title{
        font-weight: 500;
    }
</style>

<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("http://127.0.0.1:8000/assets/event.png"); background-color:#FB5E5B;'
    data-bs-target="#addEventModal" data-bs-toggle="modal">
    <h5 class="quick-action-text">Add Event</h5>
    <p class="quick-action-info">Event is a bla bla....</p>
</button>

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">  
            <form action="/homepage/add_event" method="POST" enctype="multipart/form-data">
                @csrf 
                <div class="modal-body pt-4">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
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
                            @foreach($info as $in)
                                @if($in->info_location == "add_event")
                                    <div class="info-box {{$in->info_type}}">
                                        <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                        <?php echo $in->info_body; ?>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-lg-4">
                            <label class="input-title">Event Image</label><br>
                            @include('homepage.addevent_form.contentimage')

                            <label class="input-title">Event Location</label><br>
                            @include('homepage.addevent_form.locationpicker')

                            <label class="input-title mb-2">Attachment</label><br>
                            @include('homepage.addevent_form.attachment')
                        </div>
                    </div>
                    <span id="btn-submit-holder-event"><button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </div>
            </form>
        </div>
    </div>
</div>

