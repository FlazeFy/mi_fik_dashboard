<div class="modal fade" id="deleteModal-{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Delete Notification</h5>
                
                <form action="/system/notification/delete/{{$nt['id']}}" method="POST">
                    @csrf 
                    <h6 class="text-center">{{ __('messages.del_validation') }} notification</h6>
                    @include('components.infobox',['info'=>$info, 'location'=> 'delete_notification'])           
                    <button type="submit" class="btn btn-danger float-end">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

