<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>System | Notification</title>
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

        <!-- Bootstrap dataTables Javascript -->
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {
                var tableName = "notifTable";
                $('#notifTable').DataTable({
                    "ordering": false,
                    columnDefs: [
                        { targets: 0, searchable: false},
                        { targets: 1, searchable: true},
                        { targets: 2, searchable: false},
                        { targets: 3, searchable: false},
                        { targets: 4, searchable: false},
                        { targets: 5, searchable: false},
                    ],
                    language: {
                        searchPlaceholder: "By Content",
                        <?php if(session()->get("locale") == "id") { echo "url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',"; } ?>
                    },
                });
                modifyTableControl(tableName, null);
            });
        </script>

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
        <link rel="stylesheet" href="{{ asset('/css/detail_user_v1.0.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/group_v1.0.css') }}"/>

        <!--Sidebar-->
        <script src="{{ asset('/js/global_v1.0.js')}}"></script>
        <script src="{{ asset('/js/validator_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/generator_v1.0.js')}}"></script>
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

                    @if(!$isMobile)
                        <button class="btn btn-submit mt-4" data-bs-toggle="modal" data-bs-target="#selectTypeModal"><i class="fa-solid fa-plus"></i> {{ __('messages.add_announcement') }}</button>
                    @else 
                        <button type="button" class="btn btn-mobile-control bg-success" data-bs-toggle="modal" data-bs-target="#selectTypeModal"><i class="fa-solid fa-plus"></i></button>
                    @endif
                    <div class="content-section mt-3">
                        @include('system.notification.create')
                        @include('system.notification.table')
                    </div>
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
            var popover = new bootstrap.Popover(document.querySelector('.popover-dismiss'), {
                trigger: 'focus'
            })

            var isFormSubmitted = false;
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].addEventListener('submit', function() {
                    isFormSubmitted = true;
                });
            }
        </script>

        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>
    </body>
</html>
