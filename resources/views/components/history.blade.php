<style>
    .history-holder{
        display: flex;
        flex-direction: column;
        max-height: 300px;
        overflow-y: auto !important;
    }
    .history-holder .container-fluid{
        font-size: 14px !important;
        white-space:nowrap !important; 
    }
    .history-date{
        font-size: 12px !important;
        color: grey;
    }
</style>
<?php
    use Carbon\Carbon;
?>

<div class="history-holder">
    @foreach($history as $hs)
        <div class="container-fluid p-1 mb-1">
            <span class="d-inline-block">
                @if($hs->history_type == "about" || $hs->history_type == "help" || $hs->history_type == "group" || $hs->history_type == "tag" || $hs->history_type == "info" || $hs->history_type == "notification" || $hs->history_type == "faq" || $hs->history_type == "feedback" || $hs->history_type == "event")
                    @if($hs->admin_image)
                        <img class="img img-fluid user-image" style="margin-bottom: -8px;" src="{{$hs->admin_image}}" alt="{{$hs->admin_image}}">
                    @else 
                        <img class="img img-fluid user-image" style="margin-bottom: -8px;" src="{{ asset('/assets/default_admin.png')}}" alt="{{ asset('/assets/default_admin.png')}}">
                    @endif
                @else 
        
                @endif
            </span>
            <span class="d-inline-block">
                <span><b>{{$hs->admin_username}}{{$hs->user_username}}</b> {{$hs->history_body}}</span><br>
                <span class="history-date">{{Carbon::parse($hs->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span>
            </span>
        </div>
    @endforeach
</div>

<script>
    <?php
        if(isset($third)){
            echo "const date_holder_th = document.querySelectorAll('.history-date');

            date_holder_th.forEach(e => {
                const date = new Date(e.textContent);
                e.textContent = getDateToContext(e.textContent, 'datetime');
            });";
        } else if(isset($second)){
            echo "const date_holder_hs = document.querySelectorAll('.history-date');

            date_holder_hs.forEach(e => {
                const date = new Date(e.textContent);
                e.textContent = getDateToContext(e.textContent, 'datetime');
            });";
        } else {
            echo "const date_holder_sc = document.querySelectorAll('.history-date');

            date_holder_sc.forEach(e => {
                const date = new Date(e.textContent);
                e.textContent = getDateToContext(e.textContent, 'datetime');
            });";
        }
       
    ?>
</script>