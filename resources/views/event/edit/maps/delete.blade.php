
<a class="btn btn-noline text-danger position-absolute" data-bs-toggle="modal" href="#addTag" style="right:0; top:-9px;"><i class="fa-regular fa-trash-can"></i> Remove Location</a>
<div class="modal fade" id="addTag" tabindex="-1" aria-labelledby="addTagLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-body text-center pt-4">
                <p class="fw-normal">Are you sure want to remove <span class="text-primary">location</span> from this Event</p>
                <form action="/event/edit/update/loc/remove/{{$c->slug_name}}" method="POST" >
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>