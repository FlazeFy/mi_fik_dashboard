<div class="modal fade" id="deleteModal-{{$at['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">{{ __('messages.del_validation') }} attachment?</p>
                @include('components.infobox', ['info' => $info, 'location'=> "delete_attachment"])
                <form class="d-inline" action="/event/edit/update/attach/remove/{{$c->slug_name}}" method="POST">
                    @csrf
                    <input hidden name="content_title" value="{{$c->content_title}}">
                    <input name="attachment_id" value="{{$at['id']}}" hidden>
                    <button class="btn btn-danger float-end" type="submit" onclick="deleteUploadedAttachmentForm('<?= $at['id']; ?>','<?= $at['attach_type']; ?>')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteUploadedAttachmentForm(index, type){
        let att_val = document.getElementById('attach_url_del_'+index).value;

        if(type != "attachment_url" && att_val){
            var storageRef = firebase.storage();
            var desertRef = storageRef.refFromURL(att_val);
            desertRef.delete().then(() => {
                msg = "Attachment has been removed";
                //Return msg not finished. i dont know what to do next LOL
            }).catch((error) => {
                msg = "Failed to deleted the Attachment";
                //Return msg not finished. i dont know what to do next LOL
            });
        } 
    }
</script>