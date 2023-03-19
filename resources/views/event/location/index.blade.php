<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Event | Location</title>
        
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

        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

        <!-- Jquery -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!--Apex Chart-->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!--Full calendar.-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.11.2/main.min.css,npm/fullcalendar@5.11.2/main.min.css" />
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js'></script>
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/locales-all.min.js'></script>
        <script type='text/javascript' src='https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.11.2,npm/fullcalendar@5.11.2/locales-all.min.js,npm/fullcalendar@5.11.2/locales-all.min.js,npm/fullcalendar@5.11.2/main.min.js'></script>

        <style>
            #content{
                background:#D9D9D9;
                height:100vh;
                width: 100%;
            }
            .text-danger{
                color:#F85D59 !important;
                text-decoration:none;
            }
            .text-secondary{
                color: #5B5B5B !important;
            }
            .btn-primary{
                background: #F78A00 !important;
                border:none;
            }
            .content-title{
                font-weight:500;
                color:#414141;
            }
            .content-add, .content-more{
                font-weight:500;
                color:#F78A00;
                float:right;
                background:none;
                border:none;
                text-decoration:none !important;
                cursor: pointer;
                padding:3px 9px;
                margin:0px;
                border-radius:6px;
            }
            .content-add:hover, .content-more:hover{
                color:whitesmoke;
                background:#F78A00;
            }
            .text-primary{
                color: #F78A00 !important;
            }
            .bg-primary{
                background: #F78A00 !important;
            }

            /*Custom checkbox*/
            .form-check-input{
                height:23px;
                width:23px;
                cursor:pointer;
            }
            .form-check-input:checked{
                background-color:#F78A00 !important;
                border:none;
            }
            #content{
                overflow:auto;
            }
            .content-body{
                max-width:1080px;
                display:block;
                margin-inline:auto;
            }

            .modal-content{
                margin-top:7vh;
            }
            .modal-body{
                position:relative;
            }

            .custom-close-modal{
                position:absolute !important;
                top:-20px;
                background:white;
                width:45px;
                right:10px;
                height:45px;
                padding:6px;
                color:#F85D59;
                border-radius:100%;
                border:none;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                transition: all 0.4s;
            }
            .custom-submit-modal{
                position:absolute !important;
                bottom:-20px;
                background:white;
                right:10px;
                height:45px;
                padding:6px 12px;
                color:#00C363;
                border-radius:6px;
                border:none;
                box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                transition: all 0.4s;
            }

            .custom-close-modal:hover{
                background:#F85D59;
                color:whitesmoke;
            }
            .custom-submit-modal:hover{
                background:#00C363;
                color:whitesmoke;
            }
            .nodata-icon{
                display: block;
                margin-inline: auto;
                min-height: 80px;
            }
            .btn-submit{
                background: #00c363;
                color: whitesmoke !important;
            }
        </style>
    </head>

    <body>
        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="container-fluid bg-white rounded my-5 p-3">
                        @include('event.location.discover')
                    </div>
                </div>
            </div>
        </div>

        <!--Modal-->
        @include('popup.success')

        <!--Sidebar-->
        <script src="http://127.0.0.1:8000/js/sidebar.js"></script>
    </body>
</html>
