<style>
    .event-navigator {
        position: relative;
        height: 30px;
        margin-top: 25px;
    }
    .event-navigator .navigator-link {
        color: #F78A00;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
        font-size:18px !important;
    }
    .event-navigator .navigator-right {
        position: absolute;
    }
</style>

@if(session()->get('role_key') == 1 || $c->user_username_created == session()->get('username_key'))
    <div class="event-navigator">
        <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > Detail > {{$c->content_title}}</span>
        <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event" data-bs-toggle="modal" data-bs-target="#deleteEvent-{{$c->slug_name}}"><i class="fa-solid fa-trash"></i> Delete</a>
        <a class="btn btn-info navigator-right rounded-pill px-4 py-2" style="right:130px" title="Switch to edit mode" onclick="location.href='/event/edit/{{$c->slug_name}}'"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
    </div>

    <div class="modal fade" id="deleteEvent-{{$c->slug_name}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">   
                <div class="modal-body text-center pt-4">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <form class="d-inline" action="/event/detail/delete/{{$c->slug_name}}" method="POST">
                        @csrf
                        <p style="font-weight:500;">Are you sure want to remove "<span class="text-primary">{{$c->content_title}}</span>" event?</p>
                        <div class="form-floating mt-3">
                            <input type="text" name="validation_name" class="form-control" id="validation_name" placeholder="Content Title" oninput="validateDelete()">
                            <label for="validation_name">Content Title</label>
                        </div>
                        <span id="msg-validation-title" class="float-start"><label style="font-size:12px;" class="text-danger fw-bold">Please re-type the event title name</label></span><br>
                        
                        @foreach($info as $in)
                            @if($in->info_location == "delete_event")
                                <div class="info-box {{$in->info_type}}">
                                    <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                    <?php echo $in->info_body; ?>
                                </div>
                            @endif
                        @endforeach
                        <span id="btn-delete-holder"><button class="btn btn-delete-custom float-end" disabled><i class="fa-solid fa-trash"></i> Locked</button></span>
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
                msg.innerHTML = '<label style="font-size:12px;" class="text-danger fw-bold">Please re-type the event title name</label>';
                btn_delete.innerHTML = '<button class="btn btn-delete-custom float-end" disabled><i class="fa-solid fa-lock"></i> Locked</button>';
            } else {
                msg.innerHTML = '<label style="font-size:12px;" class="text-success fw-bold">Event title is valid</label>';
                btn_delete.innerHTML = '<button class="btn btn-delete-custom float-end" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>';
            }   
        }
    </script>
@else 
    <div class="event-navigator">
        <span><a class="navigator-link" onclick="location.href='/homepage'">Event</a> > Detail > {{$c->content_title}}</span>
    </div>
@endif