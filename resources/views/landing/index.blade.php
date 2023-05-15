<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>MI-FIK Dashboard</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://kit.fontawesome.com/12801238e9.js" crossorigin="anonymous"></script>

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
    </head>

    <body>
        <div class="d-block mx-auto p-0 pt-5" style="max-width:1360px;">
            <div class="row w-100">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    @include('landing.login')
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 py-5">
                   
                </div>
            </div>
            <hr>
            @include('landing.features')
            <hr>
            @include('landing.roles')
            <hr>
            @include('landing.faq')
            <hr>
            @include('landing.feedback')
            <hr>
            @include('landing.rate')
        </div>

        @include('landing.footer')

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')
    </body>

    <script>        
        ScrollReveal().reveal('.welcome-container', { delay: 500, distance: '80px', origin: 'left', easing: 'ease-in-out' });
        ScrollReveal().reveal('.faq-first', { delay: 500, distance: '80px', origin: 'left', easing: 'ease-in-out' });
        ScrollReveal().reveal('.faq-second', { delay: 500, distance: '80px', origin: 'right', easing: 'ease-in-out' });
        ScrollReveal().reveal('.features-left', { delay: 500, distance: '80px', origin: 'left', easing: 'ease-in-out' });
        ScrollReveal().reveal('.features-right', { delay: 500, distance: '80px', origin: 'right', easing: 'ease-in-out' });
        ScrollReveal().reveal('.features-top', { delay: 500, distance: '80px', origin: 'top', easing: 'ease-in-out' });
        ScrollReveal().reveal('.features-bottom', { delay: 500, distance: '80px', origin: 'bottom', easing: 'ease-in-out' });
        ScrollReveal().reveal('.feedback-first', { delay: 500, distance: '80px', origin: 'top', easing: 'ease-in-out' });
        ScrollReveal().reveal('.feedback-second', { delay: 500, distance: '80px', origin: 'bottom', easing: 'ease-in-out' });
        ScrollReveal().reveal('.roles-left', { delay: 500, distance: '80px', origin: 'left', easing: 'ease-in-out'  });
        ScrollReveal().reveal('.roles-right', { delay: 500, distance: '80px', origin: 'right', easing: 'ease-in-out'  });
        ScrollReveal().reveal('.rate-container', { delay: 500, distance: '80px', origin: 'bottom', easing: 'ease-in-out'  });
    </script>
</html>
