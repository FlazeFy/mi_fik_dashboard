<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Register | Mi-FIK</title>
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
        
        <div class="d-block mx-auto p-0 pt-5" style="max-width:1080px; width:100%;">
            <div class="accordion" id="accordionExample">
                <div class="d-flex justify-content-between">
                    <div>
                        <a class="btn btn-close-register" href="/"><i class="fa-solid fa-arrow-left"></i> Back to Sign In</a>
                    </div>
                    <div>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-welcome"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-terms"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-profiledata"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-role"></button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-ready"></button>
                    </div>
                </div><hr>
               
                <div class="collapse show" id="welcoming" data-bs-parent="#accordionExample">
                    @include('register.welcoming')
                </div>
                <div class="collapse" id="terms" data-bs-parent="#accordionExample">
                    @include('register.terms')
                </div>
                <div class="collapse" id="profiledata" data-bs-parent="#accordionExample">
                    @include('register.profiledata')
                </div>
                <div class="collapse" id="role" data-bs-parent="#accordionExample">
                    <script>
                        let is_show_all_guidelines = true;
                        let guidelines = [
                            { 
                                holder: "holder-steps-1", 
                                target: "general-role-area", 
                                title: "General Role", 
                                body: "Please choose based on your academic situation right now. This role is required and you can choose one or maybe two", 
                                image: null, 
                                direction: "bottom"
                            },
                            { 
                                holder: "holder-steps-2", 
                                target: "selected-role-area", 
                                title: "{{ __('messages.slct_role') }}", 
                                body: "This section will show all the role you have picked. To remove the role you can click the selected role, or reset to remove all the selected", 
                                image: 'assets/steps/steps_regis_role_1.gif', 
                                direction: "right"
                            },
                            { 
                                holder: "holder-steps-3", 
                                target: "secondary-role-area", 
                                title: "Secondary Role", 
                                body: "This role is optional, but the event you will see in the future based on this role. But dont worry, you can change it in the future to", 
                                image: null, 
                                direction: "right"
                            },
                        ];
                    </script>
                    @include('register.role')
                </div>
                <div class="collapse" id="ready" data-bs-parent="#accordionExample">
                    @include('register.ready')
                </div>
            </div>
        </div>

        @include('landing.footer')

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')

        <script>
            //Popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
            //Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            $(document).ready(function() {
                tidyUpRichText("about-app-holder");
            });

            var nextStep = "welcoming";
            var slct_role = [];
            var registered = false;
            var btn_profile_holder = document.getElementById("btn-next-profile-data-holder");
            var btn_role_holder = document.getElementById("btn-next-role-holder");
            var btn_ready_holder = document.getElementById("btn-next-ready-holder");

            var msg_check_terms = document.getElementById("check_terms_msg");
            var msg_all_input = document.getElementById("input_all_profiledata_msg");
            var msg_all_role = document.getElementById("selected_role_msg");

            var btn_steps_welcome = document.getElementById("btn-steps-welcome");
            var btn_steps_profiledata = document.getElementById("btn-steps-profiledata");
            var btn_steps_terms = document.getElementById("btn-steps-terms");
            var btn_steps_role = document.getElementById("btn-steps-role");
            var btn_steps_ready = document.getElementById("btn-steps-ready");
            var is_finished = false;
            var is_requested = false;
            var token = null;

            function routeStep(nav, now){
                if(now == "welcoming"){
                    now = "terms";
                    btn_steps_welcome.setAttribute('data-bs-target', '#welcoming');
                    btn_steps_welcome.style = "background: var(--successBG);";
                } else if(now == "terms"){
                    now = "profiledata";
                    btn_steps_terms.setAttribute('data-bs-target', '#terms');
                    btn_steps_terms.style = "background: var(--successBG);";
                } else if(now == "profiledata"){
                    now = "role";
                    // if(is_requested == false){
                    //     loadTag();
                    // }
                    btn_steps_profiledata.setAttribute('data-bs-target', '#profiledata');
                    btn_steps_profiledata.style = "background: var(--successBG);";
                } else if(now == "role"){
                    now = "ready";
                    btn_steps_role.setAttribute('data-bs-target', '#role');
                    btn_steps_ready.style = "background: var(--successBG);";
                }
            }

            function validate(now){
                if (now == "terms") {
                    if (document.getElementById("check-terms").checked) {
                        msg_check_terms.innerHTML = "";
                        btn_profile_holder.innerHTML = `<button class='btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#profiledata' onclick='routeStep("next", "terms")'><i class='fa-solid fa-arrow-right'></i> Next</button>`;
                    } else {
                        btn_profile_holder.innerHTML = `<button class='btn-next-steps locked' id='btn-next-profile-data' onclick='warn("terms")'><i class='fa-solid fa-lock'></i> {{ __('messages.locked') }}</button>`;
                    }
                } else if (now == "profiledata") {
                    if (val1 && val2 && !registered) {
                        msg_all_input.innerHTML = "";
                        btn_role_holder.innerHTML = `<button class='btn btn-next-steps' onclick='register()'><i class='fa-solid fa-arrow-up'></i> Register Now</button>`;
                    } else if (val1 && val2 && registered) {
                        msg_all_input.innerHTML = "";
                        btn_role_holder.innerHTML = `<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#role' onclick='routeStep("next", "profiledata")'><i class='fa-solid fa-arrow-right'></i> Next</button>`;
                    } else {
                        btn_role_holder.innerHTML = `<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("profiledata")'></i> {{ __('messages.locked') }}</button>`;
                    }
                } else if (now == "role") {
                    let valid = slct_role.some(val => val.slug_name === "lecturer" || val.slug_name === "staff");

                    if (slct_role.length > 0) {
                        document.getElementById("no-tag-selected-msg").style.display = "none";
                        if (valid && is_requested) {
                            msg_all_input.innerHTML = "";
                            btn_ready_holder.innerHTML = `<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#ready' onclick='routeStep("next", "role")'><i class='fa-solid fa-arrow-right'></i> Next</button>`;
                        } else if (valid && !is_requested) {
                            msg_all_input.innerHTML = "";
                            btn_ready_holder.innerHTML = `<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='modal' data-bs-target='#requestRoleAdd'><i class='fa-solid fa-paper-plane'></i> Send Request</button>`;
                        } else {
                            btn_ready_holder.innerHTML = `<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("role")'></i> {{ __('messages.locked') }}</button>`;
                        }
                        getSubmitButton();
                    } else {
                        document.getElementById("no-tag-selected-msg").style.display = "normal";
                        btn_ready_holder.innerHTML = `<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("role")'></i> {{ __('messages.locked') }}</button>`;
                    }
                }
            }

            function warn(now){
                if(now == "terms"){
                    msg_check_terms.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You must check this checkbox";
                } else if(now == "profiledata"){
                    msg_all_input.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Some input may be empty or have reached maximum character";
                } else if(now == "role"){
                    msg_all_role.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> You cant use Mi-FIK without a tag. And you must select one tag from 'General Role'";
                } 
            }

            window.addEventListener('beforeunload', function(event) {
                if(is_finished == false){
                    event.preventDefault();
                    event.returnValue = '';
                }
            });
        </script>

        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    </body>
</html>
