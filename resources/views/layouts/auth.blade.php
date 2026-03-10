<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description"
        content="viho admin is super flexible, powerful, clean & modern responsive bootstrap 4 admin template with unlimited possibilities." />
    <meta name="keywords"
        content="admin template, viho admin template, dashboard template, flat admin template, responsive admin template, web app" />
    <meta name="author" content="pixelstrap" />

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />

    <title>@yield('title')</title>

    {{-- ================= GOOGLE FONTS ================= --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    {{-- ================= ICONS ================= --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}" />

    {{-- ================= PAGE / PLUGIN CSS ================= --}}
    @stack('css')

    {{-- ================= CORE CSS ================= --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />

</head>

<body>

    {{-- ================= LOADER ================= --}}
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>

    {{-- ================= PAGE CONTENT ================= --}}
    @yield('content')

    {{-- ================= CORE JS ================= --}}
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    {{-- Feather icons --}}
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>

    {{-- Config --}}
    <script src="{{ asset('assets/js/config.js') }}"></script>

    {{-- Bootstrap --}}
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>

    {{-- PAGE / PLUGIN JS --}}
    @stack('scripts')

    {{-- Theme main JS --}}
    <script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>
