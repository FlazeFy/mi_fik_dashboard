<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 503</title>
    <link rel="icon" type="image/png" href="{{asset('/assets/mifik_logo_launch.png')}}"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>
</head>
<body style="background:#FFFFFF;">
    <div class="container mt-4 pt-3 text-center">
        <img class="w-50 d-block mx-auto" src="{{asset('assets/503_error.png')}}">
        <p class="display-5" style="color:#F85D59;">Oops! Something is wrong.</p>
    </div>
    <div class="container p-4 w-25 d-block mx-auto text-center" style="background: #F85D59;">
        <p><i class="fa-solid fa-circle-info"></i> The server is unable to handle the user's request at this time because it is temporarily unavailable</p>
    </div>
</body>
</html>