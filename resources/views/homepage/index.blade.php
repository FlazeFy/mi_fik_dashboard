<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Homepage</title>
        <link rel="icon" type="image/png" href="{{asset('/assets/mifik_logo_launch.png')}}"/>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

        <!--Bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script>  

        <!-- Quills Richtext -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
        <link rel="stylesheet" href="{{ asset('/css/attachment_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/event_box_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/detail_user_v1.0.css') }}"/>

        <!-- JS Collection -->
        <script src="{{ asset('/js/global_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/attachment_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
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
            <div id="content" class="@if(!$isMobile) p-4 @endif">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="container-fluid bg-transparent @if(!$isMobile) my-3 @endif py-2 px-0">
                        <div class="position-relative @if($isMobile) px-2 @endif">
                            @if(!$isMobile)
                                <div class="row mt-3"> 
                                    @if(session()->get("role_key") == 0)
                                        <div class="col-lg-6 col-md-6 col-sm-6 pb-2">
                                            @include('homepage.addevent_form.layout')
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 pb-2">
                                            @include('homepage.myevent.layout')
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-6 pb-2">
                                            @include('homepage.addevent_form.layout')
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 pb-2">
                                            @include('homepage.addAnnouncement')
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 pb-2">
                                            @include('homepage.myevent.layout')
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 btn-config-holder">
                                    @include('event.calendar.filter_tag')
                                    @include('homepage.sorting')
                                    @include('homepage.datefilter')
                                    @include('homepage.searchbar')
                                </div>
                            @else 
                                <button type="button" class="btn btn-mobile-control" data-bs-toggle="modal" data-bs-target="#controlModal">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                <div class="modal fade" id="controlModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content p-4"> 
                                            @if(session()->get("role_key") == 0)
                                                <div class="mb-3">
                                                    @include('homepage.addevent_form.layout')
                                                </div>
                                                @include('homepage.myevent.layout')
                                            @else
                                                @include('homepage.addevent_form.layout')
                                                @include('homepage.addAnnouncement')
                                                @include('homepage.myevent.layout')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 btn-config-holder">
                                    <div class="d-inline-block w-100">
                                        @include('homepage.searchbar')
                                    </div>
                                    <div class="d-inline-block mt-2">
                                        @include('event.calendar.filter_tag')
                                    </div>
                                    <div class="d-inline-block">
                                        @include('homepage.sorting')
                                    </div>
                                    <div class="d-inline-block">
                                        @include('homepage.datefilter')
                                    </div>
                                </div>
                            @endif
                           
                            @include('components.activefilter')
                
                            @include('homepage.event')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')
        @if(session()->get('role_key') == 1)
            @include('components.recatch')
        @else 
            @include('popup.granted')
        @endif

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
        <script>
            var isFormSubmitted = false;
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].addEventListener('submit', function() {
                    isFormSubmitted = true;
                });
            }
        </script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    </body>
</html>
