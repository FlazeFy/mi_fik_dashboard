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
                <img class="img img-fluid user-image" style="margin-bottom: -8px;" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
            </span>
            <span class="d-inline-block">
                <span><b>{{$hs->admin_username}}{{$hs->user_username}}</b> {{$hs->history_body}}<span><br>
                <span class="history-date">{{getItemTimeString($hs->created_at)}}</span>
            </span>
        </div>
    @endforeach
</div>