@if(session()->get('role_key') == 0)
    <style>
        .info-box.profile .body-box {
            min-height: 30vh;
        }
    </style>
@endif

<div class="info-box-profile" id="info-box-profile">
    @include('profile.editimage')

    <div class="body-box">
        <div id="body-title">
            <h5>{{$user->first_name}} {{$user->last_name}}</h5>

            @if(session()->get('role_key') == 0)
                <h6>Lecturer / Staff</h6>
            @else
                <h6>Admin</h6>
            @endif
        </div>

        @if(session()->get('role_key') == 0)
            <div class="sub-holder text-start position-relative mt-2">
                <h5 class="text-secondary">{{ __('messages.my_roles') }}</h5><br>
                @if(!$myreq)
                    <a class="btn btn-link-danger position-absolute" style="right:0; top:-10px;" onclick="getRequestRemove()"><i class="fa-solid fa-trash-can"></i> Remove</a>
                @else 
                    <a class="btn btn-link-danger position-absolute" style="right:0; top:-10px;" data-bs-toggle="popover" title="Info" 
                        data-bs-content="You can't request to modify your tag, because you still have awaiting request
                        <?php 
                            $tag = $myreq[0]['tag_slug_name'];
                            $count = count($tag);

                            for($i = 0; $i < $count; $i++){
                                if($i == $count - 1){
                                    echo "#".$tag[$i]['tag_name'];
                                } else {
                                    echo "#".$tag[$i]['tag_name'].", ";
                                }
                            }
                        ?>
                        Awaiting request. Please wait some moment or try to contact the Admin"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</a>  
                @endif

                <div id="my_tag_list">
                    @php($tags = $user->role)
                    @foreach($tags as $tg)
                        <a class="btn btn-primary mb-2 me-1" id="tag_collection_{{$tg['slug_name']}}" style="cursor: default;">{{$tg['tag_name']}}</a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="sub-holder text-start" id="body-eng">
            <h5 class="text-secondary">{{ __('messages.engagement') }}</h5>
            <div class="text-center mt-4">
                <div class="d-inline-block mx-2">
                    <h4 class="text-center">{{$totalEvent}}</h4>
                    <h6 class="text-secondary text-center">{{ __('messages.posted_event') }}</h6>
                </div>

                @if(session()->get('role_key') == 1)
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalNotif}}</h4>
                        <h6 class="text-secondary text-center">{{ __('messages.sended_ann') }}</h6>
                    </div>
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalAcc}}</h4>
                        <h6 class="text-secondary text-center">{{ __('messages.acc_req') }}</h6>
                    </div>
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalQue}}</h4>
                        <h6 class="text-secondary text-center">{{ __('messages.answered_que') }}</h6>
                    </div>
                @endif

                @if(session()->get('role_key') == 0)
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalTask}}</h4>
                        <h6 class="text-secondary text-center">{{ __('messages.created_task') }}</h6>
                    </div>
                @endif
            </div>
        </div>

        @if(session()->get('role_key') == 0)
            <div class="sub-holder text-start" id="body-req">
                <form action="/profile/request" method="POST" id="request_add_form">
                    @csrf
                    <h5 class="text-secondary">{{ __('messages.reqed_tag') }}</h5>
                    <div id="slct_holder"></div>
                    <span id="btn-submit-tag-holder"><a disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</a></span>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    var myTag = [<?php
        if(session()->get('role_key') == 0){
            $tag = $user->role;
            foreach($tag as $tg){
                echo "{".
                        '"'."slug_name".'":"'.$tg['slug_name'].'",'.
                        '"'."tag_name".'":"'.$tg['tag_name'].'"'
                    ."},";
            }
        }
    ?>];

    function getRequestRemove(){
        stylingTagManage();
        for(var i = 0; i < myTag.length; i++){
            var slug = myTag[i]['slug_name'];
            var name = myTag[i]['tag_name'];
            var tag_div = document.getElementById("tag_collection_"+slug);

            if(slug != "staff" && slug != "lecturer" && slug != "student"){
                
                $(tag_div).attr({
                    "class": "btn btn-danger mb-2 me-1",
                    "title": "Remove tag " + name,
                    "style": "cursor: pointer;",
                    "onclick": "addSelectedTag("+'"'+ slug +'"'+", "+'"'+name+'"'+", true, 'remove')"
                });
            } else {
                $(tag_div).hide();
            }
        }
    }
</script>