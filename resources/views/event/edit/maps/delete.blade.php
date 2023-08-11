
<a class="btn btn-noline text-danger position-absolute" data-bs-toggle="modal" href="#removeLoc" style="right:0; top:-9px;"><i class="fa-regular fa-trash-can"></i> {{ __('messages.remove_loc') }}</a>
<div class="modal fade" id="removeLoc" tabindex="-1" aria-labelledby="removeLocLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-body text-center pt-4">
                <p class="fw-normal">{{ __('messages.del_validation') }} <span class="text-primary">{{ __('messages.location') }}</span> {{ __('messages.from_this_event') }}</p>
                <form action="/event/edit/update/loc/remove/{{$c->slug_name}}" method="POST" >
                    @csrf
                    <input hidden name="content_title" value="{{$c->content_title}}">
                    <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>