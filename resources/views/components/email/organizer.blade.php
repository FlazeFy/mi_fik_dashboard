<!DOCTYPE html>
<html>
    <head>
        <style>
            .bg{
                background: #FFF1DF;
                width: 100vh;
                padding: 30px 20px;
            }
            .container{
                display: block !important;
                margin-inline: auto !important;
                border-radius: var(--roundedXLG);
                width: 50vh;
                min-width: 300px !important;
                height: auto;
                padding: 15px;
                background: #FFFFFF;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                text-align: left;
                color: var(--darkColor);
            }
            h5{
                font-size:22px;
                margin: 0;
            }
            h6{
                font-size:14px;
                margin: 0;
            }
            i{
                color: var(--primaryColor);
            }
            .btn-link{
                border: none;
                text-decoration: none;
                padding: 10px 0;
                margin: 6px;
                cursor: pointer;
                font-size: 13px;
                color: var(--primaryColor) !important; 
            }
            .btn-tag{
                background : var(--primaryColor);
                color: var(--whiteColor);
                font-size: 15px;
                padding: 8px;
                margin-right: 5px;
                margin-bottom: 4px;
                border-radius: var(--roundedSM);
                text-decoration: none;
            }
            .btn-success{
                background : #00C363;
                color: var(--whiteColor);
                justify-content: center;
                text-align: center; 
                font-size: 16px;
                padding: 10px;
                border-radius: var(--roundedSM);
                text-decoration: none;
                margin-top:20px; 
                margin-bottom:20px; 
            }
            hr{
                margin-top: 10px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body class="bg">
        <div class="container">
            <img class="w-100" src="{{asset('assets/logo.png')}}" alt='logo'
                style='display: block; margin-left: auto; margin-right: auto;'>
            <h5 style="margin-bottom:10px;">Hello {{session()->get('username_key')}}, you just created a new event</h5>
            <h6 style="color:#F78A00;">Here's the detail</h6>
            <h6>Title : {{$header['content_title']}}</h6>
            <h6><i class="fa-regular fa-calendar"></i> Date : {{date("Y M d H:i", strtotime($header['content_date_start']))}} until {{date("Y M d H:i", strtotime($header['content_date_end']))}}</h6>

            @if($detail['content_loc'])
                @php($loc = json_decode($detail['content_loc']))
                @if($loc[0]->detail != null)
                    <h6><i class="fa-regular fa-calendar"></i> Location : {{$loc[0]->detail}}</h6>
                    <a class="btn btn-link" title="Visit on google maps" onclick="location.href'https://www.google.com/maps/dir/Current+Location/{{$loc[1]->detail}}'"></a>
                @endif
            @endif

            <br><span><?php echo $header['content_desc']; ?></span>

            @if($detail['content_tag'])
                @php($tag = json_decode($detail['content_tag']))
                @foreach($tag as $tg)
                    <a class="btn btn-tag">{{$tg->tag_name}}</a>
                @endforeach
            @endif
            
            <br><a class="btn btn-success" title="Open detail in web" onclick="location.href'mifik.id/<?= $header['slug_name']; ?>'">See Detail</a>

            <hr>
            <h6>Properties</h6>
            <h6>Created At : {{date("Y M d H:i:s", strtotime($header["created_at"]))}}</h6>
        </div>
    </body>
</html>