<?php
    use Carbon\Carbon;
?>

<div class="p-0 m-0">
    <!--Get event tag-->
    <h6 class="mt-2">{{ __('messages.event_tag') }}</h6>
    @if($c->content_tag)
        @php($tag = $c->content_tag)
        @foreach($tag as $tg)
            <a class="btn event-tag-box mb-1">{{$tg['tag_name']}}</a>
        @endforeach
    @endif
    <hr>
    <h6 class="mt-2">{{ __('messages.datetime') }}</h6>

    <!--Get event date start-->
    @if($c->content_date_start && $c->content_date_end)
        <a class="event-detail" title="Event date start"><i class="fa-regular fa-clock"></i> 
            <span class="date-event">{{Carbon::parse($c->content_date_start)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
        <a class="event-detail" title="Event date end"> -
            <span class="date-event">{{Carbon::parse($c->content_date_end)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
    @else
        <img src="{{asset('assets/nodate.png')}}" class="img nodata-icon" style="height:18vh;">
        <h6 class="text-center text-secondary">{{ __('messages.no_date') }}</h6>
    @endif

    <hr>
    <h6 class="text-secondary">{{ __('messages.posted_at') }} : <span class="date-event">{{Carbon::parse($c->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></h6>
    @if($c->updated_at)
        <h6 class="text-secondary">{{ __('messages.last_updated') }} : <span class="date-event">{{Carbon::parse($c->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></h6>
    @endif
    
    @include('components.infobox', ['info' => $info, 'location'=> "show_date"])
</div>

<script>
    const date_holder_evt = document.querySelectorAll('.date-event');
    const hour_holder_evt = document.querySelectorAll('.hour-event');

    date_holder_evt.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });
    hour_holder_evt.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "24h");
    });
</script>