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
                <label class="mt-1">Attachment Type : </label>
                <select class="form-select attachment d-inline-block" id="attach_type" name="attach_type" aria-label="Default select example">
                    <?php
                        foreach($dictionary as $dct){
                            if($dct->type_name == "Attachment"){
                                if($dct->slug_name == $at['attach_type']){
                                    echo "'<option value=".'"'.$dct->slug_name.'"'." selected>".$dct->dct_name."</option>";
                                } else {
                                    echo "'<option value=".'"'.$dct->slug_name.'"'.">".$dct->dct_name."</option>";
                                }
                            }
                        }
                    ?>
                </select>
                <div class="end-section">
                    <a class="btn btn-icon-delete" title="Delete"><i class="fa-solid fa-trash-can"></i></a>
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
            @elseif($at['attach_type'] == "attachment_document")
                <!-- ...... -->
            @endif
            
        </div>
    @endforeach
@endif