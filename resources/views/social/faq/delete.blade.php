<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">{{ __('messages.del_validation') }} question about</p>
                <p class="text-secondary" id="question-delete-verify"></p>
                @include('components.infobox',['info'=>$info, 'location'=> 'delete_question'])           
                <form class="d-inline" action="" id="form-delete-faq" method="POST">
                    @csrf
                    <button class="btn btn-danger float-end" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>