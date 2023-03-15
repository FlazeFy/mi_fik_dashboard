@if($c->content_attach)
    @php($att = $c->content_attach)
    @foreach($att as $at)
        <!-- Show attachment title or name  -->
        @if($at['attach_name'] || $at['attach_name'] == "")
            <h6>{{$at['attach_name']}} : </h6>
        @endif

        <!-- Show file -->
        @if($at['attach_type'] == "attachment_url")
            <input id="copy_url_{{$at['id']}}" value="{{$at['attach_url']}}" hidden>
            <a class="btn-copy-link" title="Copy this link" onclick="copylink(<?php echo $at['id']; ?>)"><i class="fa-solid fa-copy"></i> </a><a class="text-link" title="Open this link" href="{{$at['attach_url']}}" target="_blank">{{$at['attach_url']}}</a>
        @elseif($at['attach_type'] == "attachment_image")
            <img class="img img-fluid mx-auto rounded mb-2" src="{{$at['attach_url']}}" alt="{{$at['attach_url']}}">
        @elseif($at['attach_type'] == "attachment_video")
            <video controls class="rounded w-100 mx-auto mb-2" alt="{{$at['attach_url']}}">
                <source src="{{$at['attach_url']}}">
            </video>
        @elseif($at['attach_type'] == "attachment_doc")
            <!-- ??? -->
        @endif
    @endforeach
@endif