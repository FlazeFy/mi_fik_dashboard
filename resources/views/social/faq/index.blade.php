<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="description" content="" />

        <title>Social | FAQ</title>
        
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

        <!-- JS Collection -->
        <script src="{{ asset('/js/typography_v1.0.js')}}"></script>
        <script src="{{ asset('/js/converter_v1.0.js')}}"></script>
        <script src="{{ asset('/js/button_v1.0.js')}}"></script>
        <script src="{{ asset('/js/validator_v1.0.js')}}"></script>
    </head>

    <body>
        <script>
            var answer_id = " ";
        </script>

        <div class="wrapper d-flex align-items-stretch">
            <!--Sidebar.-->
            @include('sidebar.leftbar')

            <!-- Page Content  -->
            <div id="content" class="p-4">
                <div class="content-body">
                    @include('sidebar.navbar')

                    <div class="row">
                        @php($sort = session()->get('faq_menu'))
                        @php($i = 0)
                        @php($count = count($sort))
                        @foreach($sort as $st)
                            @php($style = "")
                            @if($st == "answer")
                                @php($style = "position: sticky; !important; position: -webkit-sticky; top:120px;")
                                @php($style2 = "position: sticky; !important; position: -webkit-sticky; top:600px;")
                            @endif
                            <div class="col-lg-6 col-md-6 col-sm-12 " >
                                <div class="content-section p-0 pt-3" style="{{$style}}">
                                    <header>
                                        <h5 class="mx-3 text-secondary fw-bold">
                                            @if($st == "question")
                                                <span id="total" class="text-primary"></span> 
                                            @endif
                                        {{ucwords($st)}}</h5><hr>
                                        @include('components.infosection', ['type' => $st])
                                        @include('components.controlsection', ['type' => "horizontal"])
                                    </header>
                                    <div class="p-3">
                                        @if($st == "question")
                                            @include('social.faq.question', ['question' => []])
                                        @elseif($st == "answer")
                                            @include('social.faq.answer', ['answer' => []])
                                        @endif
                                    </div>
                                </div>

                                @if($st == "answer")
                                    <div class="content-section p-0 p-3" style="{{$style2}}">
                                        <h5 class="text-secondary fw-bold">History</h5>
                                        @include('components.history', ['history' => $history])
                                    </div>
                                @endif
                            </div>
                            @php($i++)
                        @endforeach
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

        </script>

        <!--Sidebar-->
        <script src="{{ asset('/js/sidebar_v1.0.js')}}"></script>

    </body>
</html>
