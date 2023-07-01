<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Statistic</title>
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

        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

        <!-- Jquery -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!--Apex Chart-->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!--CSS Collection-->
        <link rel="stylesheet" href="{{ asset('/css/main/button_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/modal_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/dropdown_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/typography_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/global_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/image_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/form_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/navbar_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/profile_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/statistic_v1.0.css') }}"/>
        
        <style>
            .dropdown-menu.dropdown-menu-end hr {
                margin-top:5px !important; 
                margin-bottom:5px !important;
            }
        </style>

        <script src="{{ asset('/js/global_v1.0.js')}}"></script>    
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
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
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="container-fluid bg-white rounded my-3 mt-5 p-2">
                        @include('statistic.createdEvent')
                    </div>

                    @if(!$isMobile)
                        <div class="row p-0 m-0">
                            <div class="col-lg-4 col-md-6 col-sm-12 ps-0 py-0 my-0">
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    @include('statistic.mostLoc')
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 col-sm-12 pe-0 py-0 my-0">
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    <div class="row p-0 m-0">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            @include('statistic.mostRole')
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            @include('statistic.mostTag')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="container-fluid bg-white rounded my-3 p-2">
                            @include('statistic.mostLoc')
                        </div>
                        <div class="container-fluid bg-white rounded my-3 p-2">
                            @include('statistic.mostTag')
                            <br>
                            @include('statistic.mostRole')
                        </div>
                    @endif

                    <div class="container-fluid bg-white rounded mb-3 mt-2 p-2">
                       @include('statistic.mostViewedEvent')
                    </div>
                    <div class="container-fluid bg-white rounded mb-3 mt-2 p-2">
                        <h5 class="text-secondary fw-bold">Most Suggestion Feedback</h5>
                        @include('social.feedback.mostsuggest')
                    </div>
                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')
        @include('popup.failed')

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
    </body>
</html>
