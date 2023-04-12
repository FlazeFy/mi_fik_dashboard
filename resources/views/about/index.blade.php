<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>About</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://kit.fontawesome.com/12801238e9.js" crossorigin="anonymous"></script>

        <!--Bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script>  

        <!-- Include stylesheet -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        
        <!-- Main Quill library -->
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
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/button_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
    </head>

    <body>
        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    @php($sort = session()->get('about_menu'))
                    @php($i = 0)
                    @php($count = count($sort))
                    @foreach($sort as $st)
                        <div class="content-section p-0 pt-3">
                            <header>
                                <h5 class="mx-3">{{ucwords($st)}}</h5><hr>
                                @include('components.controlsection')
                            </header>
                            <div class="p-3">
                                <div class="row">
                                    @if($st == "about us")
                                        <div class="col-lg-9 col-md-8 col-sm-12">
                                            @include('about.app')
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <h6 class="text-primary mt-3">History</h6>
                                            @include('components.history', ['history' => $h_about])
                                        </div>
                                    @elseif($st == "helps editor")
                                        <div class="col-lg-4 col-md-5 col-sm-12">
                                            @include('about.help.list')
                                            <h6 class="text-primary mt-3">History</h6>
                                            @include('components.history', ['history' => $h_help])
                                        </div>
                                        <div class="col-lg-8 col-md-7 col-sm-12">
                                            @include('about.help.context')
                                        </div>
                                    @elseif($st == "contact us")
                                        <div class="col-lg-9 col-md-8 col-sm-12">
                                            @include('about.contact')
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <h6 class="text-primary mt-3">History</h6>
                                            @include('components.history', ['history' => []])
                                        </div>
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
        @include('popup.failed')

        <script>
            //Popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })

        </script>

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
    </body>
</html>
