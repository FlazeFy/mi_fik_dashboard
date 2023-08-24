<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Forget Password | Mi-FIK</title>
        <link rel="icon" type="image/png" href="{{asset('/assets/mifik_logo_launch.png')}}"/>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
        <link rel="stylesheet" href="{{ asset('/css/register_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/pin_v1.0.css') }}"/>

        <!--Scroll reveal-->
        <script src="https://unpkg.com/scrollreveal"></script>
        <script>
            ScrollReveal({ reset: true });
        </script>

        <!-- JS Collection -->
        <script src="{{ asset('/js/global_v1.0.js')}}"></script>
        <script src="{{ asset('/js/validator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/response_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
    </head>

    <body>
        <!-- PHP Helpers -->
        <?php
            use App\Helpers\Generator;
        ?>  
        @php($isMobile = Generator::isMobileDevice())   
        
        <div class="d-block mx-auto @if(!$isMobile) p-0 pt-5 @else p-3 pt-3 @endif" style="max-width:1080px; width:100%;">
            <div class="accordion" id="accordionExample">
                <div class="d-flex justify-content-between">
                    <div>
                        <a class="btn btn-close-register" href="/"><i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_sign_in') }}</a>
                    </div>
                    <div>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-recovery"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-validate"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-finish"></button>
                    </div>
                </div><hr>

                <div class="collapse show" id="recovery" data-bs-parent="#accordionExample">
                    @include('forget.recovery')
                </div>
                <div class="collapse" id="validate" data-bs-parent="#accordionExample">
                    @include('forget.validate')
                </div>
                <div class="collapse" id="finish" data-bs-parent="#accordionExample">
                    @include('forget.finish')
                </div>
            </div>
        </div>

        @include('landing.footer')

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')

        <!-- JS Collection -->
        <script src="{{ asset('/js/pin_v1.0.js')}}"></script>

        <script>
            //Popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })

            var input_username = document.getElementById("username");
            var input_username_msg = document.getElementById("username_msg");
            var input_email = document.getElementById("email");
            var input_email_msg = document.getElementById("email_msg");
            var all_user_check_msg = document.getElementById("all_user_check_msg");

            var is_start = false;
            var is_finished = false;

            var btn_steps_recovery = document.getElementById("btn-steps-recovery");
            var btn_steps_validate = document.getElementById("btn-steps-validate");
            var btn_steps_finish = document.getElementById("btn-steps-finish");

            var btn_next_recovery = document.getElementById("btn-next-validate-holder");

            window.addEventListener('beforeunload', function(event) {
                if(is_finished == false){
                    event.preventDefault();
                    event.returnValue = '';
                }
            });

            function routeStep(nav, now){
                if(now == "recovery"){
                    now = "validate";
                    btn_steps_recovery.setAttribute('data-bs-target', '#recovery');
                    btn_steps_recovery.style = "background: var(--successBG);";
                } else if(now == "validate"){
                    now = "finish";
                    btn_steps_finish.setAttribute('data-bs-target', '#finish');
                    btn_steps_finish.style = "background: var(--successBG);";
                }
            }

            function validate(now){
                if(now == "recovery"){
                    if(val1 == true){
                        btn_next_recovery.innerHTML = "<button class='btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#validate' onclick='routeStep("+'"'+"next"+'"'+", "+'"'+"recovery"+'"'+"); startTimer(900);'><i class='fa-solid fa-arrow-right'></i> Next</button>";
                    } else {
                        btn_next_recovery.innerHTML = "<button class='btn-next-steps locked' id='btn-next-validate' onclick='warn("+'"'+"recovery"+'"'+"); is_start = true;'><i class='fa-solid fa-lock'></i> Next</button>";
                    }   
                } 
            }

            function warn(now){
                if(now == "recovery"){
                    all_user_check_msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Please validate your account first";
                } else if(now == "finish"){
                    msg_all_input.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i>";
                } 
            }
        </script>

        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    </body>
</html>
