<style>
    .event-navigator {
        position: relative;
        height: 30px;
        margin-top: 25px;
    }
    .event-navigator .navigator-link {
        color: var(--primaryColor);
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
        font-size:18px !important;
    }
</style>

@if(session()->get('role_key') == 1 || $c->user_username_created == session()->get('username_key'))
    <div class="event-navigator d-flex justify-content-between">
        <div>
            <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > Detail > {{$c->content_title}}</span>
        </div>
        @if(!$isMobile)
            <div style="white-space:nowrap;">
                <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event" data-bs-toggle="modal" data-bs-target="#deleteEvent-{{$c->slug_name}}"><i class="fa-solid fa-trash"></i> {{ __('messages.delete') }}</a>
                <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:130px" title="Switch to edit mode" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-pen-to-square"></i> {{ __('messages.edit') }}</a>
            </div>
        @endif
    </div>
    <div class="modal fade" id="deleteEvent-{{$c->slug_name}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">   
                <div class="modal-body text-center pt-4">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <form class="d-inline" action="/event/detail/delete/{{$c->slug_name}}" method="POST">
                        @csrf
                        <p style="font-weight:500;">{{ __('messages.del_validation') }} "<span class="text-primary">{{$c->content_title}}</span>" event?</p>
                        <div class="form-floating mt-3">
                            <input type="text" name="validation_name" class="form-control" id="validation_name" placeholder="Content Title" oninput="validateDelete()">
                            <label for="validation_name">{{ __('messages.title') }}</label>
                        </div>
                        <span id="msg-validation-title" class="float-start"><label style="font-size:12px;" class="text-danger fw-bold">{{ __('messages.retype') }}</label></span><br>
                        
                        @include('components.infobox', ['info' => $info, 'location'=> "delete_event"])
                        <span id="btn-delete-holder"><button class="btn btn-delete-custom float-end" disabled><i class="fa-solid fa-trash"></i> {{ __('messages.locked') }}</button></span>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateDelete(){
            var check = "<?= $c->content_title; ?>";
            var val = document.getElementById('validation_name');
            var msg = document.getElementById('msg-validation-title');
            var btn_delete = document.getElementById('btn-delete-holder');

            if(val.value != check){
                msg.innerHTML = `<label style="font-size:12px;" class="text-danger fw-bold">Please re-type the event title name</label>`;
                btn_delete.innerHTML = `<button class="btn btn-delete-custom float-end" disabled><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button>`;
            } else {
                msg.innerHTML = `<label style="font-size:12px;" class="text-success fw-bold">Event title is valid</label>`;
                btn_delete.innerHTML = `<button class="btn btn-delete-custom float-end" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>`;
            }   
        }
    </script>
@else 
    <div class="event-navigator">
        <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > Detail > {{$c->content_title}}</span>
    </div>
@endif