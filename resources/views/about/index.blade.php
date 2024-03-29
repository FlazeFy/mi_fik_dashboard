<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>About</title>
        <link rel="icon" type="image/png" href="{{asset('/assets/mifik_logo_launch.png')}}"/>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

        <!--Bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script>  

        <!-- Include stylesheet -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        
        <!-- Quills Richtext -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

        <!-- Jquery -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!-- CSS Collection -->
        <link rel="stylesheet" href="{{ asset('/css/main/button_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/modal_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/typography_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/global_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/image_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/form_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/navbar_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/dropdown_v1.0.css') }}"/>

        <link rel="stylesheet" href="{{ asset('/css/profile_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/richtext_v1.0.css') }}"/>

        <!-- JS Collection -->
        <script src="{{ asset('/js/global_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/button_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/validator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/response_v1.0.js')}}"></script>
    </head>

    <body>
        <!-- PHP Helpers -->
        <?php
            use App\Helpers\Generator;
        ?>  
        @php($isMobile = Generator::isMobileDevice())   
        
        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content">
                <div class="content-body">
                    @include('sidebar.navbar')

                    @php($sort = session()->get('about_menu'))
                    @php($i = 0)
                    @php($count = count($sort))
                    @foreach($sort as $st)
                        <div class="content-section p-0 pt-3">
                            <header>
                                @if($st == "helps editor" && session()->get('role_key') == 0)
                                    <h5 class="mx-3 text-secondary fw-bold" id="section-{{$i}}">Help Center</h5><hr>
                                    <script>
                                        if(sessionStorage.getItem('locale') != "en"){
                                            translator('section-{{$i}}');
                                        }
                                    </script>
                                @else
                                    <h5 class="mx-3 text-secondary fw-bold" id="section-{{$i}}">{{ucwords($st)}}</h5><hr>
                                    <script>
                                        if(sessionStorage.getItem('locale') != "en"){
                                            translator('section-{{$i}}');
                                        }
                                    </script>
                                @endif

                                @include('components.controlsection', ['type' => "vertical"])
                            </header>
                            <div class="@if(!$isMobile) p-3 @else px-2 @endif">
                                <div class="row">
                                    @if($st == "about us")
                                        @if(session()->get('role_key') == 1)
                                            @if(!$isMobile)
                                                <div class="col-lg-9 col-md-8 col-sm-12">
                                                    @include('about.app')
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-12">
                                                    <h5 class="text-secondary fw-bold">{{ __('messages.history') }}</h5>
                                                    @include('components.history', ['history' => $h_about])
                                                </div>
                                            @else 
                                                <div class="px-2">
                                                    @include('about.app')
                                                </div>
                                            @endif
                                        @else
                                            @include('about.app')
                                        @endif
                                    @elseif($st == "helps editor")
                                        @if(session()->get('role_key') == 1)
                                            @if(!$isMobile)
                                                <div class="col-lg-4 col-md-5 col-sm-12">
                                                    @include('about.help.list')
                                                    <h5 class="text-secondary fw-bold mt-2">{{ __('messages.history') }}</h5>
                                                    @include('components.history', ['history' => $h_help, 'second'=> true])
                                                </div>
                                                <div class="col-lg-8 col-md-7 col-sm-12">
                                                    @include('about.help.context')
                                                </div>
                                            @else 
                                                <div class="px-2">
                                                    @include('about.help.listncontext')
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-lg-4 col-md-5 col-sm-12">
                                                @include('about.help.list')
                                            </div>
                                            <div class="col-lg-8 col-md-7 col-sm-12">
                                                <div class="position-absolute text-center" id="no_cat_selected" style="top:100px; left:45%;">
                                                    <img src="{{ asset('/assets/editor.png')}}" class='img nodata-icon-req' style="width:30vh; height:30vh;">
                                                    <h6 class='text-secondary text-center'>{{ __('messages.see_help_type') }}</h6>
                                                </div>
                                                <span id="desc_holder_view"></span>
                                            </div>
                                        @endif
                                    @elseif($st == "contact us")
                                        @if(session()->get('role_key') == 1)
                                            @if(!$isMobile)
                                                <div class="col-lg-9 col-md-8 col-sm-12">
                                                    @include('about.contact')
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-12">
                                                    <h5 class="text-secondary fw-bold">{{ __('messages.history') }}</h5>
                                                    @include('components.history', ['history' => $h_contact, 'third'=> true])
                                                </div>
                                            @else 
                                                <div class="px-2">
                                                    @include('about.contact')
                                                </div>
                                            @endif
                                        @else
                                            @include('about.contact')
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @php($i++)
                    @endforeach
                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')
        @include('popup.success_mini')
        @include('popup.failed')

        <script>
            $(document).ready(function() {
                tidyUpRichText("about-app-holder");
            });

            //Popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })

            var isFormSubmitted = false;
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].addEventListener('submit', function() {
                    isFormSubmitted = true;
                });
            }
        </script>

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    </body>
</html>
