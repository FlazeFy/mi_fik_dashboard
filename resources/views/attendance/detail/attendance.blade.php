<h6 class="mt-2">{{ __('messages.attendance_response') }}</h6>
<!-- PHP Helpers -->
<?php
    use Carbon\Carbon;
?>
<div class="">
    @foreach($atrs as $atr)
        <div class="row ps-3">
            <div class="col-2 p-0 ps-1">
                <img class="img img-fluid user-image" style="margin-top:30%;" src="{{$atr->user_image}}">
            </div> 
            <div class="col-10 p-0 py-2 ps-2 position-relative">
                <h6 class="text-secondary fw-normal"><?="@"?>{{$atr->user_username}}</h6>
                <a class="btn <?php 
                    if($atr->attendance_answer == "presence"){
                        echo "btn-success"; 
                    } else if($atr->attendance_answer == "absence"){
                        echo "btn-danger"; 
                    } else {
                        echo "btn-secondary"; 
                    }
                ?> px-2 py-1" style="font-size:var(--textSM);">
                    @if($atr->attendance_answer)
                        {{$atr->attendance_answer}}
                    @else 
                        Not response yet
                    @endif
                </a> 
                @if($atr->attendance_answer)
                    <a style="font-size:var(--textXSM);">{{ __('messages.at') }} <span class="date-event">{{Carbon::parse($atr->answered_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                @endif
            </div>
        </div>
    @endforeach
</div>