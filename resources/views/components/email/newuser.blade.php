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
                border-radius: 20px;
                width: 50vh;
                min-width: 300px !important;
                height: auto;
                padding: 15px;
                background: #FFFFFF;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                text-align: left;
                color: #414141;
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
                    <li style="margin-bottom:4px;">{{$dt->first_name}} {{$dt->last_name}} 
                        @if($dt->role == null)
                            <b style="color:#F85D59;">doesn't have</b> a role
                        @endif
                        @if($dt->accepted_at == null)
                            @if($dt->role == null)
                                @php($comb = " and ")
                                {{$comb}}
                            @endif
                            want to <b style="color:#00C363;">join</b> Mi-FIK
                        @endif
                    </li>
                @endforeach
                </ol>
            @endif   

            <h6>Properties</h6>
            <h6>Created At : {{date("Y M d H:i:s")}}</h6>
        </div>
    </body>
</html>