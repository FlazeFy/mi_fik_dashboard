<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>System | Dictionary</title>
        
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

        <!-- Jquery DataTables -->
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

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

    </head>

    <body>
        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="content-section">
                       
                    </div>
                </div>
            </div>
        </div>

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