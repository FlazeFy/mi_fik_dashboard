@if($c->content_attach)
    @php($att = $c->content_attach)
    <div class="row">
    @foreach($att as $at)
        <!-- Show attachment title or name  -->
        @if($at['attach_name'] && $at['attach_name'] != "" && $at['attach_type'] != "attachment_image")
            <h6>{{$at['attach_name']}} : </h6>
        @endif

        <!-- Show file -->
        @if($at['attach_type'] == "attachment_url")
            <input id="copy_url_{{$at['id']}}" value="{{$at['attach_url']}}" hidden>
            <a class="btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at['id']; ?>)"><i class="fa-solid fa-copy"></i></a>
            <a class="text-link" title="Open this link" href="{{$at['attach_url']}}" target="_blank">{{$at['attach_url']}}</a><br><br>
        @elseif($at['attach_type'] == "attachment_image")
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                <h6>{{$at['attach_name']}} : </h6>
                <img class="img img-fluid mx-auto image-att-zoomable mb-2" src="{{$at['attach_url']}}" title="Zoom Image" alt="{{$at['attach_url']}}" data-bs-toggle="modal" data-bs-target="#zoomable_image_{{$at['id']}}">
                <div class="modal fade" id="zoomable_image_{{$at['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">   
                            <div class="modal-body text-center pt-4">
                                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                <img class="img img-fluid mx-auto w-100" src="{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
                                <h6 class="mt-2">{{$at['attach_name']}} : </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($at['attach_type'] == "attachment_video")
            <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
                <source src="{{$at['attach_url']}}">
            </video>
        @elseif($at['attach_type'] == "attachment_doc")
            <embed class="document-grid mb-2 rounded" alt="{{$at['attach_url']}}" style="height: 600px;" src="{{$at['attach_url']}}"/>
        @endif
    @endforeach
    </div>
@else 
    <img src="{{asset('assets/attachment.png')}}" class="img nodata-icon" style="height:18vh;">
    <h6 class="text-center text-secondary">This Event doesn't have attachment</h6>
@endif

