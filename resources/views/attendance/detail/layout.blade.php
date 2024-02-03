<style>
    .box-attd-detail{
        margin: var(--spaceLG) 0 var(--spaceLG) 0;
        min-height: 80vh;
        background: var(--whiteColor);
        margin-top: var(--spaceLG);
        margin-bottom: var(--spaceXLG);
        padding: var(--spaceXMD);
        border-radius: var(--roundedMD);
    }
    .attd-detail{
        color: var(--primaryColor) !important;
        text-decoration:none;
    }
  
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: var(--hoverBG); 
    }
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
</style>

<div class="box-attd-detail shadow">
    <div class="row p-3">
        <div class="col-lg-8 col-md-9 col-sm-6">
            <!-- PHP Helpers -->
            <?php
                use App\Helpers\Generator;
                use Carbon\Carbon;
            ?>

            <div class="container-fluid p-4 text-center" style="border:2px solid var(--greyColor); border-radius:var(--roundedLG); position:relative;">
                @php($image_profile = Generator::getUserImage($attd->admin_image_created, $attd->user_image_created, $attd->admin_username_created))
                <div class="d-flex justify-content-between" style="position:absolute; right:var(--spaceLG);">
                    <div class="">
                    </div>
                    <div class="">
                        @include('attendance.detail.toogle')
                    </div>       
                </div>  
                <form method="POST" action="/attendance/edit/info/{{$attd->id}}">
                    @csrf
                    <div class="p-0 m-0 mb-3" style="display: flex;">
                        <div class="d-inline-block me-2">
                            <img class="img rounded-circle" style="width:55px; height:55px; border:2px solid var(--primaryColor);" src="{{$image_profile}}" alt="username-profile-pic.png">
                        </div>
                        <div class="d-inline-block text-start" style="width:auto;">
                            @if(session()->get('toogle_edit_attendance') == 'false')
                                <h4 class="text-primary">{{ucwords($attd->attendance_title)}}</h4>
                            @else 
                                <div class="form-floating">
                                    <input type="text" class="form-control" value="{{$attd->content_title}}" name="attendance_title" id="floatingText">
                                    <label for="floatingText">Title</label>
                                </div><br>
                            @endif
                            @if(session()->get('toogle_edit_attendance') == 'false')
                                @if($attd->content_title)
                                    <h6 style="font-size:var(--textXMD);">{{ __('messages.from_event') }} : <a href="/event/detail/{{$attd->slug_name}}" class="btn btn-primary" style="font-size:var(--textMD);" 
                                        title="Click to view event">{{ucwords($attd->content_title)}}</a></h6>
                                @endif
                            @endif
                        </div>
                    </div>   

                @if(session()->get('toogle_edit_attendance') == 'false')
                    @if($attd->attendance_desc)
                        <span id="desc-holder">{{$attd->attendance_desc}}</span><br>
                    @else
                        <img src="{{asset('assets/nodesc.png')}}" class="img nodata-icon" style="height:18vh;">
                        <h6 class="text-center text-secondary">{{ __('messages.no_desc_attd') }}</h6>
                    @endif
                @else 
                    <div class="form-floating">
                        <textarea class="form-control" id="floatingTextarea2" name="attendance_desc" style="height: 100px">{{$attd->attendance_desc}}</textarea>
                        <label for="floatingTextarea2">Description</label>
                    </div>
                @endif

                <div class="<?php if(session()->get('toogle_edit_attendance') == 'false'){echo "mt-5";} else {echo "mt-2";}?>">
                    @if($attd->attendance_answer != null)
                        {{ __('messages.attd_confirm') }} 
                        <span class="btn 
                            <?php 
                                if($attd->attendance_answer == "presence"){
                                    echo 'btn-success'; 
                                } else {
                                    echo 'btn-danger'; 
                                }
                            ?> mx-2">{{ucFirst($attd->attendance_answer)}}</span>
                        {{ __('messages.at') }} <span class="date-event" style="font-size:var(--textMD);">{{Carbon::parse($attd->answered_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></a>
                    @endif
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-secondary text-start" style="font-size:var(--spaceMD);">{{ __('messages.created_by') }} 
                            @if(session()->get("username_key") == $attd->created_by_user)
                                {{ __('messages.you') }}
                            @else 
                                @if($attd->created_by_user)
                                    <?="@"?>{{$attd->created_by_user}}
                                @else 
                                    <?="@"?>{{$attd->created_by_admin}}
                                @endif
                            @endif
                        </h6>
                        @if(session()->get('toogle_edit_attendance') == 'true')
                            @if($attd->content_title)
                                <h6 style="font-size:var(--textXMD);">{{ __('messages.from_event') }} : <a href="/event/detail/{{$attd->slug_name}}" class="btn btn-primary" style="font-size:var(--textMD);" 
                                    title="Click to view event">{{ucwords($attd->content_title)}}</a></h6>
                            @endif
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-submit mt-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-6">
            @include('attendance.detail.attendance')
        </div>
        @if(session()->get('role_key') == 1 || session()->get("username_key") == $attd->created_by_user)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <br>
                @include('attendance.detail.totalattendancechart')
            </div>
        @endif
    </div>
</div>

<script>
    const date_holder_attd = document.querySelectorAll('.date-event');

    date_holder_attd.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });
</script>