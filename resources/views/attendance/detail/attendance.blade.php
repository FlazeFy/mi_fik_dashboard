<h5 class="mt-2">{{ __('messages.attendance_response') }}</h5><hr>

<!-- PHP Helpers -->
<?php
    use Carbon\Carbon;
?>
@if(session()->get("role_key") == 1 || session()->get("username_key") == $attd->created_by_user)
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
@else 
    <div class="">
        <h2 class="text-primary">{{count($atrs)}}</h2>
        <h6>{{ __('messages.people') }}</h6>
    </div>
@endif