<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>MI-FIK Dashboard</title>
        <link rel="icon" type="image/png" href="{{asset('/assets/mifik_logo_launch.png')}}"/>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

        <!--Bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script>  

        <!-- Jquery -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!-- CSS Collection -->
        <link rel="stylesheet" href="{{ asset('/css/main/button_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/modal_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/typography_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/global_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/image_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/form_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/landing_v1.0.css') }}"/>

        <!--Scroll reveal-->
        <script src="https://unpkg.com/scrollreveal"></script>
        <script>
            ScrollReveal({ reset: true });
        </script>

        <!-- JS Collection -->
        <script src="{{ asset('/js/validator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/response_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
    </head>

    <body>
        <div class="d-block mx-auto p-0 pt-5" style="max-width:1080px;">
            <div class="container mt-4 pt-3 text-center">
                <img class="logo-big-res" src="{{asset('assets/usermanage.png')}}">
                <p class="text-primary" style="font-size:26px;">Sorry {{session()->get('username_key')}}. But you don't have access to Mi-FIK</p>
                <p style="font-size:18px;">You either not have this <span class="btn btn-tag" title="General Tag">Lecturer</span> or <span class="btn btn-tag" title="General Tag">Staff</span> as your <b>Role</b></p>
                <div class="" id="start-section-manage">
                    <button class="btn btn-link py-1 px-2" onclick="infinteLoadMore(1)"><i class="fa-solid fa-magnifying-glass"></i> Browse Available Tag</button> to send request to Admin.
                </div>
            </div>
        </div>

        @if($myreq)
            <div class="d-block mx-auto p-0" style="max-width:1080px;">
                <div class="container mt-4 pt-3 text-center">
                    <h6 class="text-secondary text-center">Sorry your <a class="text-danger" title="Awaiting Request" data-bs-toggle="popover" title="Popover title" style="cursor: pointer;"
                        data-bs-content="You have requested to {{$myreq[0]['request_type']}} 
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
                        "> request</a> is still in process by our Admin. Please wait some moment or try to contact the 
                        <a class="text-primary text-decoration-none" title="Send email" href="mailto:hello@mifik.id">Admin</a>
                    </h6>
                </div>
            </div>
        @else 
            <div class="d-block mx-auto p-0" style="max-width:1080px;">
                <div class="container mt-4 pt-3 text-center">
                    @include('waiting.tagpicker')
                </div>
            </div>
        @endif

        @include('landing.footer')
    </body>

    <script>        
        //Popover
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        ScrollReveal().reveal('.logo-big-res', { delay: 500, distance: '80px', origin: 'top', easing: 'ease-in-out' });
    </script>
</html>
