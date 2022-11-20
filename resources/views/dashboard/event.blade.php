<style>
    .event-holder{
        
    }
    .event-box{
        border-radius:14px;
        height:240px;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
        cursor:pointer;
        width: 100%;
        padding:0px;
        text-align:left;
    }
    .event-box:hover{
        transform: translateY(15px);
    }
    .event-box .event-title{
        font-weight:bold;
        font-size:14px;
        color:#404040 !important;
        margin:0px;
    }
    .event-box .event-subtitle{
        font-weight:500;
        font-size:12.5px;
        color:#5B5B5B !important;
        margin:0px;
    }
    .event-box .event-desc{
        font-weight:400;
        font-size:12px;
        color:#989898 !important;
        margin:0px;
        overflow: hidden; 
        text-overflow: ellipsis; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        line-clamp: 2; 
        -webkit-box-orient: vertical;
    }
    .btn-detail{
        text-decoration: none !important;
        border-radius: 6px;
        font-size:12px;
        font-weight:500;
        padding: 5px;
        color:#F78A00 !important;
        cursor:pointer;
    }
    .user-image-content{
        border:2px solid #F78A00;
        width:40px;
        height:40px;
        cursor:pointer; /*if we can view other user profile*/
        border-radius:30px;
        margin-inline:auto;
        display: block;
    }
    .header-image{
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: black;
        height:110px;
        width: 100%;
        border-radius: 14px 14px 0px 0px !important;
    }
    .loc-limiter{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 70%;
    }

    @media screen and (max-width: 1000px) {
        .user-image-content{ /*Need to be fixed*/
            position:absolute;
            margin-top:-37.5px;
            margin-left:35%;
        }
    }
    /* @media screen and (max-width: 768px) {
        
    } */
</style>

<div class="position-relative">
    <h5 class="text-secondary fw-bold">Today's Event</h5>
    <a class="content-more position-absolute" style="right:0px; top:0px;" href="/event/all">See More <i class="fa-solid fa-arrow-right"></i></a>
    <div class="event-holder row mt-3">
        @foreach($event as $e)
            <div class="col-4">
                <button class="card shadow event-box">
                    <div class="card-header header-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/content-2.jpg')}});"></div>
                    <div class="card-body p-2 w-100">
                        <div class="row px-2">
                            <div class="col-lg-2 px-1">
                                <img class="img img-fluid user-image-content" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                            </div>
                            <div class="col-lg-9 p-0 py-1">
                                <h6 class="event-title">{{$e->content_title}}</h6>
                                <h6 class="event-subtitle">[username]</h6>
                            </div>
                        </div>
                        <div style="height:45px;">
                            <p class="event-desc my-1">{{$e->content_desc}}</p>
                        </div>
                        <div class="row d-inline-block px-2">
                            <!--Get event location-->
                            @if($e->content_loc != null)
                                @php($loc = json_decode($e->content_loc))
                                <span class="loc-limiter px-0 m-0">
                                    <a class="btn-detail" title="Event Location"><i class="fa-solid fa-location-dot"></i> {{$loc[0]->detail}}</a>
                                </span>
                            @endif

                            <!--Get event date start-->
                            @if($e->content_date_start != null && $e->content_date_end != null)
                                <a class="btn-detail" title="Event Started Date"><i class="fa-regular fa-clock"></i> {{date('h:i A', strtotime($e->content_date_start))}} - {{date('h:i A', strtotime($e->content_date_end))}}</a>
                            @endif

                            <!--Get event tag-->
                            @if($e->content_tag != null)
                                @php($tag = json_decode($e->content_tag))
                                <a class="btn-detail" title="
                                    <?php 
                                        $i = 1;
                                        foreach($tag as $tg){
                                            if($i != count($tag)){
                                                echo $tg->tag_name.", ";
                                            } else {
                                                echo $tg->tag_name;
                                            }
                                            $i++;
                                        }
                                    ?>
                                "><i class="fa-solid fa-hashtag"></i> {{count($tag)}}</a>
                            @endif
                        </div>
                    </div>
                </button>
            </div>
        @endforeach
    </div>
</div>