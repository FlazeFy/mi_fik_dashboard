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
                    <li style="margin-bottom:4px;">{{$dt->first_name}} {{$dt->last_name}} aka "{{$dt->username}}" want to 
                        @if($dt->request_type == "remove")
                            @php($color = "color:#F85D59;")
                        @elseif($dt->request_type == "add")
                            @php($color = "color:#00C363;")
                        @endif
                        <b style="{{$color}}">{{ucfirst($dt->request_type)}}</b>
                        @if($dt->tag_slug_name)
                            @php($tag = $dt->tag_slug_name)
                            @php($total = count($tag))
                            @php($i = 0)

                            @if($total > 1)
                                @foreach($tag as $tg)
                                    @if($i != $total - 1)
                                        <b>#{{$tg['tag_name']}}, </b>
                                    @else
                                        and <b>#{{$tg['tag_name']}}</b>
                                    @endif
                                    @php($i++)
                                @endforeach
                            @else 
                                <b>#{{$tag[0]['tag_name']}}</b>
                            @endif
                        @endif
                    </li>
                @endforeach
                </ol>
            @endif   

            <h6>Properties</h6>
            <h6>Created at : {{date("Y M d H:i:s")}}</h6>
        </div>
    </body>
</html>