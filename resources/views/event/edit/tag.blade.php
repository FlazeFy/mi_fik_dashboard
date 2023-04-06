@if($c->content_tag)
    @php($tag = $c->content_tag)
    @foreach($tag as $tg)
        <a class="btn event-tag-box">{{$tg['tag_name']}}</a>
    @endforeach
@endif