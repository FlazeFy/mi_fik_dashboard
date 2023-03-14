<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>User | Manage</title>
        
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

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {
                $('#notifTable').DataTable();
            });
        </script>

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
            .content-more-floating{
                font-weight:500;
                color:#F78A00;
                background:white;
                border:1.5px solid #F78A00;
                text-decoration:none !important;
                cursor: pointer;
                padding:3px 9px;
                margin:0px;
                border-radius:6px;
                font-size: 12.5px;
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            }
            .content-more-floating:hover{
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
            
            .btn-link{
                text-decoration:none;
                padding:10px 16px;
                border-radius:5px;
                color:#F78A00;
                background:transparent;
                font-weight:500;
                font-size:16px;
            }

            .btn-link:hover{
                color:white;
                background:#F78A00;
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

            .incoming-req-box{
                position: relative;
                height: 400px;
            }
            .user-req-holder{
                margin-top: 20px;
                padding: 5px 16px 0 5px;
                display: flex;
                flex-direction: column;
                max-height: 90%;
                overflow-y: scroll;
            }
            .user-box{
                border-left: 3px solid #808080;
                border-radius: 10px;
                box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
                padding: 5px;
                width: 100%;
                border: none;
                text-align: start;
                background: white;
                margin-bottom: 15px;
            }
            .user-box-desc{
                font-size: 11.5px;
                font-weight: normal;
                margin: 0;
            }
            .user-box-date{
                font-size: 11.5px;
                font-weight: normal;
                color: #808080;
                margin: 0;
            }


            /*Global*/
            .btn-icon-rounded-danger{
                color: #D5534C;
                border-radius: 100%;
                width: 40px;
                height: 40px;
            }
            .btn-icon-rounded-danger:hover{
                background: #D5534C;
                color: whitesmoke;
            }

            .btn-icon-rounded-success{
                color: #58C06E;
                border-radius: 100%;
                width: 40px;
                height: 40px;
            }
            .btn-icon-rounded-success:hover{
                background: #58C06E;
                color: whitesmoke;
            }

            .btn-icon-rounded-primary{
                color: #F78A00;
                border-radius: 100%;
                width: 40px;
                height: 40px;
            }
            .btn-icon-rounded-primary:hover{
                background: #F78A00;
                color: whitesmoke;
            }
            .nodata-icon-req{
                display: block;
                margin-inline: auto;
                height: 100px;
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

                    <div class="container-fluid bg-transparent my-3 py-2 px-0">
                        <div class="row">
                            <div class="col-lg-8 col-md-7 col-sm-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="container-fluid bg-white rounded my-3 p-2">
                                            @include('user.manage.old_user_req')
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="container-fluid bg-white rounded my-3 p-2">
                                            @include('user.manage.new_user_req')
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 col-sm-12">
                                <div class="container-fluid bg-white rounded my-3 p-2">
                                    
                                </div>
                            </div>
                        </div>
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
        <script src="http://127.0.0.1:8000/js/sidebar.js"></script>

    </body>
</html>
