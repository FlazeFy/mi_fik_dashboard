<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Social | Feedback</title>
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

        <!-- Jquery DataTables -->
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

        <!--Apex Chart-->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Bootstrap dataTables Javascript -->
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

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
        <link rel="stylesheet" href="{{ asset('/css/tabular_v1.0.css') }}"/>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {
                var tableName = 'feedbackTable';
                $('#'+tableName).DataTable({
                    searching: false,
                    language: {
                        <?php if(session()->get("locale") == "id") { echo "url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',"; } ?>
                    }
                });
                let extra_control = [
                    { id: "filter-suggest"}
                ];
                modifyTableControl(tableName, extra_control);
            });
        </script>

        <script src="{{ asset('/js/global_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/response_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
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

                    @php($sort = session()->get('feedback_menu'))
                    @php($i = 0)
                    @php($count = count($sort))
                    @foreach($sort as $st)
                        <div class="content-section p-0 pt-3">
                            <header>
                                <h5 class="mx-3 text-secondary fw-bold" id="section-{{$i}}">{{ucwords($st)}}</h5><hr>
                                <script>
                                    if(sessionStorage.getItem('locale') != "en"){
                                        translator('section-{{$i}}');
                                    }
                                </script>
                                @include('components.controlsection', ['type' => "vertical"])
                            </header>
                            <div class="p-3">
                                @if($st == "list")
                                    @include('social.feedback.table')
                                @elseif($st == "most suggest")
                                    @include('social.feedback.mostsuggest')
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
        @include('popup.success_mini')
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
