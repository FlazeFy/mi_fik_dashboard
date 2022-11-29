<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Dashboard</title>
        
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
        </style>
    </head>

    <body>
        <div class="row m-0 p-0">
            <div class="col-lg-10 col-md-9 col-sm-12 p-0">
                <div class="wrapper d-flex align-items-stretch">
                    <!--Sidebar.-->
                    @include('sidebar.leftbar')

                    <!-- Page Content  -->
                    <div id="content" class="p-4">
                        <button type="button" id="sidebarCollapse" class="btn btn-primary">
                            <i class="fa fa-bars"></i>
                            <span class="sr-only">Toggle Menu</span>
                        </button>

                        <div class="container-fluid bg-transparent my-3 py-2 px-0">
                            @include('dashboard.event')
                        </div>
                        <div class="row p-0 m-0">
                            <div class="col-lg-5 p-1">
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    @include('dashboard.mostTag')
                                    <?php
                                        //For testing the most used tag chart only
                                        // $val = [];
                                        // foreach($mostTag as $mt){
                                        //     $tag = json_decode($mt->content_tag);
                                            
                                        //     foreach($tag as $tg){
                                        //         //Insert tag name to new array
                                        //         array_push($val, $tg->tag_name);
                                        //     }   
                                        // }
                                        // foreach($val as $v){
                                        //     echo "<a class='fw-bold text-decoration-none mx-1'>".$v."  </a>";
                                        // }
                                        // echo "<br><br>";
                                        // $result = array_count_values($val);

                                        // arsort($result);
                                        // $new_arr = array_keys($result);
                                        // $i = 0;
                                        // foreach($result as $v){
                                        //     echo "<a class='fw-bold text-decoration-none mx-1'>".$new_arr[$i]."  </a>";
                                        //     $i++;
                                        // }
                                    ?>
                                </div>
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    @include('dashboard.mostLoc')
                                </div>
                            </div>
                            <div class="col-lg-7 p-1">
                                <div class="container-fluid mt-2 p-2">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 px-1 pb-2">
                                            @include('dashboard.addEvent')
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid bg-white rounded p-2">
                                    @include('dashboard.createdEvent')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 p-2">
                <!--Sidebar.-->
                @include('sidebar.rightbar')
            </div>
        </div>

        <!--Sidebar-->
        <script src="http://127.0.0.1:8000/js/sidebar.js"></script>

        <!-- Main Quill library -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            var quill = new Quill('#rich_box', {
                theme: 'snow'
            });
        </script>

        <!--Maps API Key.-->
        <!--need billing!!!-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>
        <script>
            let map;

            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: -34.397, lng: 150.644 },
                    zoom: 8,
                });
            }
            
            window.initMap = initMap;
        </script>
    </body>
</html>
