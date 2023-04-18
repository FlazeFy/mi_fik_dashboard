<style>
    .info-box-profile{
        background-image:linear-gradient(to right,#F78A00 20%, 65%, #5b5b5b);
        padding: 0;
        margin-top: 40px;
        padding-top: 25px;
        border-radius: 12px; 
        text-align: center;
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

<div class="info-box-profile">
    @if(!$user->image_url)
        @if(session()->get('role_key') == 0)
            <img class="img img-fluid rounded-circle shadow m-2 d-block mx-auto" style="max-width:40%;" src="{{ asset('/assets/default_lecturer.png')}}">
        @endif
    @else
        <img class="img img-fluid rounded-circle shadow m-2" src="{{$user->image_url}}">
    @endif
    <div class="body-box">
        <h5 style="color:#414141;">{{$user->first_name}} {{$user->last_name}}</h5>

        @if(session()->get('role_key') == 0)
            <h6 class="text-secondary">Lecturer / Staff</h6>
        @else
            <h6 class="text-secondary">Admin</h6>
        @endif

        <div class="sub-holder text-start">
            <h5 class="text-secondary">My Roles</h5>
            @php($tags = $user->role)
            @foreach($tags as $tg)
                <a class="btn btn-primary mb-1 me-1">{{$tg['tag_name']}}</a>
            @endforeach
        </div>

        <div class="sub-holder text-start">
            <h5 class="text-secondary">Engagement</h5>

            <h4 class="text-center">0</h4>
            <h6 class="text-secondary text-center">Created Event</h6>
        </div>
    </div>
</div>