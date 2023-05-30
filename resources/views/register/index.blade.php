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
        <link rel="stylesheet" href="{{ asset('/css/register_v1.0.css') }}"/>

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
            <div class="accordion" id="accordionExample">
                <div class="row w-100">
                    <div class="col-lg-4 col-md-5 col-sm-12">
                        <a class="btn btn-close-register" href="/"><i class="fa-solid fa-arrow-left"></i> Back to Sign In</a>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-welcome">
                            Hello, welcome to Mi-FIK
                            <h6 class="text-secondary">Before begin, let us to introduce ourself</h6>
                        </button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-terms">
                            Our Terms & Condition
                            <h6 class="text-secondary">Please read and accept our rules</h6>
                        </button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-profiledata">
                            Let Us know you
                            <h6 class="text-secondary">Please provide some of information about you</h6>
                        </button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-role">
                            Choose your role
                            <h6 class="text-secondary">As we tell you before. We need you to pick some role for our event's grouping</h6>
                        </button>
                        <button class="btn btn-register-steps" data-bs-toggle="collapse" id="btn-steps-ready">
                            I'm ready to join!
                            <h6 class="text-secondary">Finally, you can finished your register steps. And waiting for admin approval</h6>
                        </button>
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-12 p-5">
                        <div class="section-register">
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
                                @include('register.role')
                            </div>
                            <div class="collapse" id="ready" data-bs-parent="#accordionExample">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('landing.footer')

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')

        <script>
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

            function routeStep(nav, now){
                if(now == "welcoming"){
                    now = "terms";
                    btn_steps_welcome.setAttribute('data-bs-target', '#welcoming');
                    btn_steps_welcome.style = "border-left: 6px solid #58C06E;";
                } else if(now == "terms"){
                    now = "profiledata";
                    btn_steps_terms.setAttribute('data-bs-target', '#terms');
                    btn_steps_terms.style = "border-left: 6px solid #58C06E;";
                } else if(now == "profiledata"){
                    now = "role";
                    loadTag();
                    btn_steps_profiledata.setAttribute('data-bs-target', '#profiledata');
                    btn_steps_profiledata.style = "border-left: 6px solid #58C06E;";
                } else if(now == "role"){
                    now = "ready";
                    btn_steps_role.setAttribute('data-bs-target', '#role');
                    btn_steps_role.style = "border-left: 6px solid #58C06E;";
                }
            }

            function validate(now){
                if(now == "terms"){
                    if(document.getElementById("check-terms").checked == true){
                        msg_check_terms.innerHTML = "";
                        btn_profile_holder.innerHTML = "<button class='btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#profiledata' onclick='routeStep("+'"'+"next"+'"'+", "+'"'+"terms"+'"'+")'><i class='fa-solid fa-arrow-right'></i> Next</button>";
                    } else {
                        btn_profile_holder.innerHTML = "<button class='btn-next-steps locked' id='btn-next-profile-data' onclick='warn("+'"'+"terms"+'"'+")'><i class='fa-solid fa-lock'></i> Locked</button>";
                    }   
                } else if(now == "profiledata"){
                    if(val1 == true && val2 == true && registered == false){
                        msg_all_input.innerHTML = "";
                        btn_role_holder.innerHTML = "<button class='btn btn-next-steps' onclick='register()'><i class='fa-solid fa-arrow-up'></i> Register Now</button>";
                    } else if(val1 == true && val2 == true && registered == true){
                        msg_all_input.innerHTML = "";
                        btn_role_holder.innerHTML = "<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#role' onclick='routeStep("+'"'+"next"+'"'+", "+'"'+"profiledata"+'"'+")'><i class='fa-solid fa-arrow-right'></i> Next</button>";
                    } else {
                        btn_role_holder.innerHTML = "<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("+'"'+"profiledata"+'"'+")'></i> Locked</button>";
                    }
                } else if(now == "role"){
                    valid = false;
                    slct_role.map((val, index) => {
                        if(val.slug_name == "lecturer" || val.slug_name == "staff"){
                            valid = true;
                        }
                    });

                    if(slct_role.length > 0){
                        document.getElementById("slct-box").style= "display:normal;";
                        if(valid == true){
                            msg_all_input.innerHTML = "";
                            btn_ready_holder.innerHTML = "<button class='btn btn-next-steps' id='btn-next-terms' data-bs-toggle='collapse' data-bs-target='#ready' onclick='routeStep("+'"'+"next"+'"'+", "+'"'+"role"+'"'+")'><i class='fa-solid fa-arrow-right'></i> Next</button>";
                        } else {
                            btn_ready_holder.innerHTML = "<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("+'"'+"role"+'"'+")'></i> Locked</button>";
                        }
                    } else {
                        document.getElementById("slct-box").style= "display:none;";
                        btn_ready_holder.innerHTML = "<button class='btn btn-next-steps locked'><i class='fa-solid fa-lock' onclick='warn("+'"'+"role"+'"'+")'></i> Locked</button>";
                    }                    
                }
            }

            function warn(now){
                if(now == "terms"){
                    msg_check_terms.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. You must check this checkbox";
                } else if(now == "profiledata"){
                    msg_all_input.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Some input may be empty or have reached maximum character";
                } else if(now == "role"){
                    msg_all_role.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. You cant register without a tag. And you must select one tag from 'General Role'";
                } 
            }
        </script>
    </body>
</html>
