<style>
    .info-box-profile{
        background-image:linear-gradient(to right,#F78A00 20%, 65%, #5b5b5b);
        padding: 0;
        margin-top: 40px;
        padding-top: 25px;
        border-radius: 12px; 
        text-align: center;
        position: relative !important;
    }
    .info-box-profile .body-box{
        background: white;
        padding: 30px;
        margin: 0;
        width: 100%;
        height: 30vh;
        margin-top: 30px;
        border-radius: 36px 36px 12px 12px; 
    }
    .info-box-profile .body-box .sub-holder{
        margin-bottom: 20px;
    }
</style>

<div class="info-box-profile" id="info-box-profile">
    @if(!$user->image_url)
        @if(session()->get('role_key') == 0)
            <img class="img img-fluid rounded-circle shadow mx-4" style="max-width:40%;" src="{{ asset('/assets/default_lecturer.png')}}" id="profile_image_info">
        @endif
    @else
        <img class="img img-fluid rounded-circle shadow mx-4" src="{{$user->image_url}}" id="profile_image_info">
    @endif
    <div class="body-box">
        <div id="body-title">
            <h5>{{$user->first_name}} {{$user->last_name}}</h5>

            @if(session()->get('role_key') == 0)
                <h6>Lecturer / Staff</h6>
            @else
                <h6>Admin</h6>
            @endif
        </div>

        <div class="sub-holder text-start">
            <h5 class="text-secondary">My Roles</h5>
            @php($tags = $user->role)
            @foreach($tags as $tg)
                <a class="btn btn-primary mb-1 me-1">{{$tg['tag_name']}}</a>
            @endforeach
        </div>

        <div class="sub-holder text-start" id="body-eng">
            <h5 class="text-secondary">Engagement</h5>

            <h4 class="text-center">0</h4>
            <h6 class="text-secondary text-center">Created Event</h6>
        </div>
        <div class="sub-holder text-start" id="body-req">
            <form action="/profile/request/add" method="POST" id="request_add_form">
                @csrf
                <h5 class="text-secondary">Requested Tag</h5>
                <div id="slct_holder"></div>
                <span id="btn-submit-tag-holder"><a disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</a></span>
            </form>
        </div>
    </div>
</div>