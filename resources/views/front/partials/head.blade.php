<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{{ config('app.name') }} - @yield('title') </title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="#" />

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="{{ asset('front-assets') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('front-assets') }}/css/LineIcons.3.0.css" />
    <link rel="stylesheet" href="{{ asset('front-assets') }}/css/tiny-slider.css" />
    <link rel="stylesheet" href="{{ asset('front-assets') }}/css/glightbox.min.css" />
    <link rel="stylesheet" href="{{ asset('front-assets') }}/css/main.css" />
    @stack('styles')
</head>