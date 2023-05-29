<div class="modal fade" id="deleteModal-{{$at['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">Are you sure want to delete this attachment?</p>
                @foreach($info as $in)
                    @if($in->info_location == "delete_attachment")
                        <div class="info-box {{$in->info_type}}">
                            <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                            <?php echo $in->info_body; ?>
                        </div>
                    @endif
                @endforeach
                <form class="d-inline" action="/event/edit/update/attach/remove/{{$c->slug_name}}" method="POST">
                    @csrf
                    <input name="attachment_id" value="{{$at['id']}}" hidden>
                    <button class="btn btn-danger float-end" type="submit" onclick="deleteAttachmentForm('<?= $at['id']; ?>','<?= $at['attach_type']; ?>')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>