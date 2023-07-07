<style>
    .attachment-item .attachment-edit-header{
        white-space: nowrap !important; 
        position: relative;
        margin-bottom:10px;
    }
    .attachment-edit-header .end-section{
        position: absolute; 
        right: 0; 
        top: 0;
    }
    
</style>

<div class="attachment-holder">
    @if($c->content_attach)
        @php($att = $c->content_attach)
        @foreach($att as $at)
            <div class="attachment-item mb-3 p-3 shadow" style="--circle-attach-color-var:var(--infoBG); border-left: 3.5px solid var(--infoBG);"> 
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
                        <span id="attach_image_holder_{{$at['id']}}" class="d-block mx-auto">
                            <lottie-player id="lottie_animation_{{$at['id']}}" src="https://assets8.lottiefiles.com/packages/lf20_tsxbtrcu.json" background="transparent" speed="1" style="width: 250px; height: 250px;" loop autoplay></lottie-player>
                        </span>
                        <script>
                            var imgHolder<?= $at['id'] ?> = document.getElementById("attach_image_holder_{{$at['id']}}");
                            var imgURL<?= $at['id'] ?> = "{{$at['attach_url']}}";
                            var image<?= $at['id'] ?> = new Image();

                            image<?= $at['id'] ?>.onload = function() {
                                var loading<?= $at['id'] ?> = document.getElementById("lottie_animation_{{$at['id']}}");
                                loading<?= $at['id'] ?>.style.display = "none"; 
                                imgHolder<?= $at['id'] ?>.appendChild(image<?= $at['id'] ?>); 
                            };

                            image<?= $at['id'] ?>.src = imgURL<?= $at['id'] ?>;
                            image<?= $at['id'] ?>.classList.add("img", "img-fluid", "mx-auto", "image-att-zoomable", "mb-2");
                            image<?= $at['id'] ?>.title = "Zoom image";
                            image<?= $at['id'] ?>.alt = "{{$at['attach_url']}}";
                            image<?= $at['id'] ?>.setAttribute("data-bs-toggle", "modal");
                            image<?= $at['id'] ?>.setAttribute("data-bs-target", "#zoomable_image_{{$at['id']}}");
                        </script>
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
                        <span id="attach_doc_holder_{{$at['id']}}" class="d-block mx-auto">
                            <lottie-player id="lottie_animation_{{$at['id']}}" src="https://assets8.lottiefiles.com/packages/lf20_tsxbtrcu.json" background="transparent" speed="1" style="width: 250px; height: 250px;" loop autoplay></lottie-player>
                            <embed class="document-grid mb-2 rounded" id="embed_holder_{{$at['id']}}" alt="{{$at['attach_url']}}" style="height: 250px;" src=""/>
                        </span>
                        <script>
                            var docHolder<?= $at['id'] ?> = document.getElementById("attach_doc_holder_{{$at['id']}}");
                            var docURL<?= $at['id'] ?> = "{{$at['attach_url']}}";
                            var doc<?= $at['id'] ?> = document.getElementById("embed_holder_{{$at['id']}}");

                            doc<?= $at['id'] ?>.onload = function() {
                                var loading<?= $at['id'] ?> = document.getElementById("lottie_animation_{{$at['id']}}");
                                loading<?= $at['id'] ?>.style.display = "none"; 
                                docHolder<?= $at['id'] ?>.appendChild(image<?= $at['id'] ?>); 
                            };

                            doc<?= $at['id'] ?>.src = docURL<?= $at['id'] ?>;
                            doc<?= $at['id'] ?>.style = "height: 700px;";
                        </script>
                    </div>
                @endif
                
                @include('event.edit.attachment.delete')
            </div>

            @if($at['attach_type'] != "attachment_url")
                <input hidden value="{{$at['attach_url']}}" id="attach_url_del_{{$at['id']}}">
            @endif
        @endforeach
    @endif
</div>