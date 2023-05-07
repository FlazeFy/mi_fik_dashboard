<div class="modal fade" id="deleteModal-{{$fb->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">Are you sure want to delete "{{$fb->feedback_body}}"</p>
                <p style="font-weight:400;"><i class="fa-solid fa-circle-info text-primary"></i> 
                
                <form class="d-inline" action="/social/feedback/delete/{{$fb->id}}" method="POST">
                    @csrf
                    <button class="btn btn-danger float-end" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>