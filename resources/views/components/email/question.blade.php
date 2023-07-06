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
            <h5 style="margin-bottom:10px;">Hello {{$admin}}, {{$body}}</h5>
            <h6 style="color:#F78A00;">Here's the detail</h6>
            <h6>Context : {{$context}}</h6>

            @if($detail)
                <ol>  
                @foreach($detail as $dt)
                    <li style="margin-bottom:4px;"><b>[{{ucfirst($dt->question_type)}}]</b> "{{$dt->question_body}}" from <span style="color:#F78A00;"><?= "@"; ?>{{$dt->username}}</span></li>
                @endforeach
                </ol>
            @endif   

            <h6>Properties</h6>
            <h6>Created At : {{date("Y M d H:i:s")}}</h6>
        </div>
    </body>
</html>