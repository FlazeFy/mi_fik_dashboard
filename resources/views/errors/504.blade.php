<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 504</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/328b2b4f87.js" crossorigin="anonymous"></script>

    <!-- CSS Collection -->
    <link rel="stylesheet" href="{{ asset('/css/main/global_v1.0.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/main/button_v1.0.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/main/typography_v1.0.css') }}"/>
</head>
<body style="background:var(--whiteColor);">
    <div class="container mt-4 pt-3 text-center">
        <img class="w-50 d-block mx-auto" src="{{asset('assets/504_error.png')}}">
        <p class="display-5 text-danger">Oops! Something is wrong</p>
    </div>
    <div class="error-page-info">
        <p><i class="fa-solid fa-circle-info"></i> The session or token used by the user has expired</p>
        <a class="btn btn-primary" href="/">Try to Sign-In again</a>
    </div>
</body>
</html>