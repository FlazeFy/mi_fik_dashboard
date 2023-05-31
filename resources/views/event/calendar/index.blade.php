<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Event | Calendar</title>
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

        <!-- Jquery -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!--Full calendar.-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.11.2/main.min.css,npm/fullcalendar@5.11.2/main.min.css" />
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/locales-all.min.js'></script>
        <script type='text/javascript' src='https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.11.2,npm/fullcalendar@5.11.2/locales-all.min.js,npm/fullcalendar@5.11.2/locales-all.min.js,npm/fullcalendar@5.11.2/main.min.js'></script>

        <!--CSS Collection-->
        <link rel="stylesheet" href="{{ asset('/css/main/button_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/modal_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/typography_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/global_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/image_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/form_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/navbar_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/main/dropdown_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/profile_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/calendar_v1.0.css') }}"/>

        <link rel="stylesheet" href="{{ asset('/css/event_box_v1.0.css') }}"/>

        <!-- JS Collection -->
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

                    @php($sort = session()->get('calendar_menu'))
                    @php($i = 0)
                    @php($count = count($sort))
                    @foreach($sort as $st)
                        <div class="content-section p-0 pt-3">
                            <header>
                                <h5 class="mx-3 text-secondary fw-bold">{{ucwords($st)}}</h5><hr>
                                @if($st == "finished")
                                    @include("event.calendar.searchbar")
                                    @include("event.calendar.sorting")
                                @endif
                                @include('components.controlsection', ['type' => "vertical"])
                            </header>
                            <div class="p-3">
                                @if($st == "calendar")
                                    <div class="calendar-tag-holder">
                                        @include('event.calendar.filter_tag')
                                    </div>
                                    @include('event.calendar.calendar')
                                @elseif($st == "finished")
                                    @include('event.calendar.finished')
                                @endif
                            </div>
                        </div>
                        @php($i++)
                    @endforeach
    
                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
    </body>
</html>
