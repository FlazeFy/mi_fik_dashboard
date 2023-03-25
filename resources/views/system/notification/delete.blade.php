<div class="modal fade" id="deleteModal-{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Delete Notification</h5>
                
                <form action="/system/notification/delete/{{$nt->id}}" method="POST">
                    @csrf 
                    <h6 class="text-center">Are you sure want to delete this notification</h6>
                    <div class="tip-box">
                        <label><i class="fa-solid fa-circle-info"></i> Tips</label><br>
                        This's not permanently will deleted the notification
                    </div>
                    <button type="submit" class="btn btn-danger float-end">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

