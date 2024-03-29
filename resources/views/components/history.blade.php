<style>
    .history-holder {
        display: flex;
        flex-direction: column;
        height: auto;
        max-height: 300px;
        overflow-y: auto !important;
    }
    .history-holder {
        font-size: var(--textMD) !important;
    }
    .history-date {
        font-size: var(--textSM) !important;
        color: var(--shadowColor);
    }
</style>
<?php
    use Carbon\Carbon;
?>

<div class="history-holder">
    @if(count($history) != 0)
        @foreach($history as $hs)
            <div class="d-flex justify-content-start p-1 mb-1">
                <span class="">
                    @if($hs->history_type == "about" || $hs->history_type == "help" || $hs->history_type == "group" || $hs->history_type == "tag" || $hs->history_type == "info" || $hs->history_type == "notification" || $hs->history_type == "faq" || $hs->history_type == "feedback" || $hs->history_type == "event" || $hs->history_type == "contact")
                        @if($hs->admin_image)
                            <img class="img img-fluid user-image" style="min-width:45px;" src="{{$hs->admin_image}}" alt="{{$hs->admin_image}}">
                        @else 
                            <img class="img img-fluid user-image" style="min-width:45px;" src="{{ asset('/assets/default_admin.png')}}" alt="{{ asset('/assets/default_admin.png')}}">
                        @endif            
                    @endif
                </span>
                <span class="ps-2">
                    <span><b>{{$hs->admin_username}}{{$hs->user_username}}</b> {{$hs->history_body}}</span><br>
                    <span class="history-date">{{Carbon::parse($hs->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span>
                </span>
            </div>
        @endforeach
    @else
        <div class="text-center" id="no_cat_selected" >
            <img src="{{ asset('/assets/editor.png')}}" class='img nodata-icon-req' style="width:75%; height:90%; max-width:200px;">
            <h6 class='text-secondary text-center'>{{ __('messages.no_history') }}</h6>
        </div>
    @endif
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