@if($c->content_attach)
    @php($att = $c->content_attach)
    @php($image_collection = [])
    @php($video_collection = [])
    @php($doc_collection = [])
    @php($link_collection = [])

    @foreach($att as $at)
        @if($at['attach_type'] == "attachment_url")
            @php(array_push($link_collection, $at))
        @elseif($at['attach_type'] == "attachment_image")
            @php(array_push($image_collection, $at))
        @elseif($at['attach_type'] == "attachment_video")
            @php(array_push($video_collection, $at))
        @elseif($at['attach_type'] == "attachment_doc")
            @php(array_push($doc_collection, $at))
        @endif
    @endforeach

    <div class="row">
        @foreach($link_collection as $at)
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                <h6>{{$at['attach_name']}}</h6>
                <input id="copy_url_{{$at['id']}}" value="{{$at['attach_url']}}" hidden>
                <a class="btn btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at['id']; ?>)">
                    <i class="fa-solid fa-copy"></i>
                    <a class="text-link" title="Open this link" href="{{$at['attach_url']}}" target="_blank">{{$at['attach_url']}}</a>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row">
        @foreach($image_collection as $at)
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                @if($at['attach_name'])
                    <h6>{{$at['attach_name']}}</h6>
                @endif
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

                <div class="modal fade" id="zoomable_image_{{$at['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">   
                            <div class="modal-body text-center pt-4">
                                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                <img class="img img-fluid mx-auto w-100" src="{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
                                @if($at['attach_name'])
                                    <h6 class="mt-2">{{$at['attach_name']}} : </h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @foreach($video_collection as $at)
        <h6>{{$at['attach_name']}}</h6>
        <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
            <source src="{{$at['attach_url']}}">
        </video>
    @endforeach

    @foreach($doc_collection as $at)
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
    @endforeach
@else 
    <img src="{{asset('assets/attachment.png')}}" class="img nodata-icon" style="height:18vh;">
    <h6 class="text-center text-secondary">{{ __('messages.no_att') }}</h6>
@endif



