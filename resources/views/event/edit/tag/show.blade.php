@if($c->content_tag)
    @php($tag = $c->content_tag)
    @php($count_tag = count($tag))
    @foreach($tag as $tg)
        @if($count_tag > 1)
            <a class="btn event-tag-box hover-danger mb-1" title="Remove tag" data-bs-toggle="modal" data-bs-target="#deleteTag-{{$tg['slug_name']}}">{{$tg['tag_name']}}</a>

            <div class="modal fade" id="deleteTag-{{$tg['slug_name']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">   
                        <div class="modal-body text-center pt-4">
                            <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                            <p style="font-weight:500;">{{ __('messages.del_validation') }} "<span class="text-primary">{{$tg['tag_name']}}</span>" tag?</p>
                            @include('components.infobox', ['info' => $info, 'location'=> "delete_tag"])
                            <form class="d-inline" action="/event/edit/update/tag/remove/{{$c->slug_name}}" method="POST">
                                @csrf
                                <input hidden name="content_title" value="{{$c->content_title}}">
                                <input name="slug_name" value="{{$tg['slug_name']}}" hidden>
                                <button class="btn btn-danger float-end" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <a class="btn event-tag-box hover-danger mb-1" title="Remove tag" data-bs-toggle="modal" data-bs-target="#deleteTagLast-{{$tg['slug_name']}}">{{$tg['tag_name']}}</a>

            <div class="modal fade" id="deleteTagLast-{{$tg['slug_name']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">   
                        <div class="modal-body text-center pt-4">
                            <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                            <p style="font-weight:500;">Sorry but you can't delete "<span class="text-primary">{{$tg['tag_name']}}</span>" tag. Because this is the last tag attached in this event</p>
                            @include('components.infobox', ['info' => $info, 'location'=> "delete_tag_last"])
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif