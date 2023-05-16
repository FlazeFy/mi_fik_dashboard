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

<div class="history-holder">
    <?php 
        if(!function_exists('getItemTimeString')) {
            function getItemTimeString($date) {
                // Initial variables
                $dateItem = date('Y-m-d', strtotime($date)); 
                $dateNow = date('Y-m-d'); 
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $timeItem = date('H:i', strtotime($date));
                $hourItem = date('h', strtotime($date));
                $hourNow = date('h');
                $minItem = date('i', strtotime($date));
                $minNow = date('i');
            
                $result = "";
            
                if ($dateItem == $dateNow) {
                    if ($hourItem == $hourNow) {
                        $diff = (int)$minNow - (int)$minItem;
                        if ($diff > 10) {
                            $result = "$diff min ago";
                        } else {
                            $result = "Just Now";
                        }
                    } else {
                        $result = "Today at ".$timeItem;
                    }
                } else if ($dateItem == $yesterday) {
                    $result = "Yesterday at ".$timeItem;
                } else {
                    $result = date('Y-m-d H:i:s', strtotime($date)); 
                }
            
                return $result;
            }
        }
    ?>
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
                <span><b>{{$hs->admin_username}}{{$hs->user_username}}</b> {{$hs->history_body}}<span><br>
                <span class="history-date">{{getItemTimeString($hs->created_at)}}</span>
            </span>
        </div>
    @endforeach
</div>