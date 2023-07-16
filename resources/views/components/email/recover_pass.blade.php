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
            h2 {
                font-size:26px;
            }
            h6{
                font-size:14px;
                margin: 0;
            }
            i{
                color: var(--primaryColor);
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
            <h5 style="margin-bottom:10px;">Hello {{$uname}}, you have requested password recovery</h5>
            <h6 style="color:#F78A00;">Here's the token</h6>
            <h2>{{$token}}</h2>
            <h6 style="color:#F85D59;">Leave this message if this not your request</h6>
        </div>
    </body>
</html>