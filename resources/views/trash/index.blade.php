<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Trash</title>
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
        <link rel="stylesheet" href="{{ asset('/css/main/navbar_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/dropdown_v1.0.css') }}"/>

        <link rel="stylesheet" href="{{ asset('/css/profile_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/event_box_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/task_box_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/trash_v1.0.css') }}"/>

        <!-- JS Collection -->
        <script src="{{ asset('/js/isotope_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
    </head>

    <body>
        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="position-relative mt-3">
                        @include('trash.sorting')
                        @include('trash.searchbar')
                        @if(session()->get('role_key') == 1)
                            <div class="carousel-control-holder">
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <i class="fa-solid fa-angle-left fa-xl"></i><span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <i class="fa-solid fa-angle-right fa-xl"></i><span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @endif
                    </div>
    
                    @if(session()->get('role_key') == 1)
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="container-fluid bg-transparent my-3 py-2 px-0">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">    
                                                    @include('trash.category', ["category" => "event"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">
                                                    @include('trash.category', ["category" => "tag"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">
                                                    @include('trash.category', ["category" => "group"])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="container-fluid bg-transparent my-3 py-2 px-0">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">    
                                                    @include('trash.category', ["category" => "info"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">
                                                    @include('trash.category', ["category" => "feedback"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">
                                                    @include('trash.category', ["category" => "question"])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="container-fluid bg-transparent my-3 py-2 px-0">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">    
                                                    @include('trash.category', ["category" => "notification"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="content-section-small mt-2">
                                                    @include('trash.category', ["category" => "dictionary"])
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else 
                        <div class="container-fluid bg-transparent my-3 py-2 px-0">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="content-section-small mt-2">    
                                        @include('trash.category', ["category" => "event"])
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="content-section-small mt-2">
                                        @include('trash.category', ["category" => "task"])
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @include('trash.content')

                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')

        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>

        <script>
            //Popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
        </script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    </body>
</html>
