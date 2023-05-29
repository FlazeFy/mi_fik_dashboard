<style>
    .attachment-item .attachment-edit-header{
        white-space:nowrap !important; 
        position:relative;
        margin-bottom:10px;
    }
    .attachment-edit-header .end-section{
        position:absolute; 
        right:0; 
        top:0;
    }
</style>

@if($c->content_attach)
    @php($att = $c->content_attach)
    @foreach($att as $at)
        <div class="attachment-item mb-3 p-3 shadow"> 
            <div class="attachment-edit-header">
                <label class="mt-1 fw-bold">Attachment Type : {{ucfirst(trim($at['attach_type']." ", "attachment_"))}}</label>
                <div class="end-section">
                    <a class="btn btn-icon-delete" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$at['id']}}"><i class="fa-solid fa-trash-can"></i></a>
                    @if($at['attach_type'] != "attachment_url")
                        <a class="btn btn-icon-preview" title="Preview Attachment" data-bs-toggle="collapse" 
                            <?= ' href="#collapsePreview'.$at["id"].'"'; ?>>
                            <i class="fa-regular fa-eye-slash"></i></a>
                    @endif
                </div>
            </div>

            @if($at['attach_type'] == "attachment_image")
                <div class='collapse show' id="collapsePreview{{$at['id']}}">
                    <h6>{{$at['attach_name']}}</h6>
                    <img class="img img-fluid mx-auto rounded mb-2" src="{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
                </div>
            @elseif($at['attach_type'] == "attachment_video")
                <div class='collapse show' id="collapsePreview{{$at['id']}}">
                    <h6>{{$at['attach_name']}}</h6>
                    <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
                        <source src="{{$at['attach_url']}}">
                    </video>
                </div>
            @elseif($at['attach_type'] == "attachment_url")
                <h6>{{$at['attach_name']}}</h6>
                <a>{{$at['attach_url']}}</a><br>
            @elseif($at['attach_type'] == "attachment_doc")
                <div class='collapse show' id="collapsePreview{{$at['id']}}">
                    <h6>{{$at['attach_name']}}</h6>
                    <embed class="document-grid mb-2 rounded" alt="{{$at['attach_url']}}" style="height: 600px;" src="{{$at['attach_url']}}"/>
                </div>
            @endif
            
            @include('event.edit.attachment.delete')
        </div>

        @if($at['attach_type'] != "attachment_url")
            <input hidden value="{{$at['attach_url']}}" id="attach_url_del_{{$at['id']}}">
        @endif
    @endforeach
@endif

<script>
    function deleteAttachmentForm(index, type){
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