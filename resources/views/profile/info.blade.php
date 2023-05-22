<style>
    .info-box-profile{
        background-image:linear-gradient(to right,#F78A00 20%, 65%, #5b5b5b);
        padding: 0;
        margin-top: 40px;
        padding-top: 25px;
        border-radius: 10px; 
        text-align: center;
        position: relative !important;
    }
    .info-box-profile .body-box{
        background: #FFFFFF;
        padding: 30px;
        padding-bottom: 10px;
        margin: 0;
        width: 100%;
        min-height: 30vh;
        margin-top: 30px;
        border-radius: 36px 36px 10px 10px; 
    }
    .info-box-profile .body-box .sub-holder{
        margin-bottom: 20px;
    }
    .sub-holder h6{
        font-size:13px;
    }
    .sub-holder h4{
        font-size:20px;
    }
</style>

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
            <div class="sub-holder text-start position-relative">
                <h5 class="text-secondary">My Roles</h5>
                <a class="btn btn-link-danger position-absolute" style="right:0; top:-10px;" onclick="getRequestRemove()"><i class="fa-solid fa-trash-can"></i> Remove</a>

                <div id="my_tag_list">
                    @php($tags = $user->role)
                    @foreach($tags as $tg)
                        <a class="btn btn-primary mb-2 me-1" id="tag_collection_{{$tg['slug_name']}}" style="cursor: default;">{{$tg['tag_name']}}</a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="sub-holder text-start" id="body-eng">
            <h5 class="text-secondary">Engagement</h5>
            <div class="text-center mt-4">
                <div class="d-inline-block mx-2">
                    <h4 class="text-center">{{$totalEvent}}</h4>
                    <h6 class="text-secondary text-center">Posted Event</h6>
                </div>

                @if(session()->get('role_key') == 1)
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalNotif}}</h4>
                        <h6 class="text-secondary text-center">Posted Notification</h6>
                    </div>
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalAcc}}</h4>
                        <h6 class="text-secondary text-center">Accepted Request</h6>
                    </div>
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalQue}}</h4>
                        <h6 class="text-secondary text-center">Answered Question</h6>
                    </div>
                @endif

                @if(session()->get('role_key') == 0)
                    <div class="d-inline-block mx-2">
                        <h4 class="text-center">{{$totalTask}}</h4>
                        <h6 class="text-secondary text-center">Created Task</h6>
                    </div>
                @endif
            </div>
        </div>

        @if(session()->get('role_key') == 0)
            <div class="sub-holder text-start" id="body-req">
                <form action="/profile/request" method="POST" id="request_add_form">
                    @csrf
                    <h5 class="text-secondary">Requested Tag</h5>
                    <div id="slct_holder"></div>
                    <span id="btn-submit-tag-holder"><a disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</a></span>
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
            
            $(tag_div).attr({
                "class": "btn btn-danger mb-2 me-1",
                "title": "Remove tag " + name,
                "style": "cursor: pointer;",
                "onclick": "addSelectedTag("+'"'+ slug +'"'+", "+'"'+name+'"'+", true, 'remove')"
            });
        }
    }
</script>