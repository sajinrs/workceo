<!DOCTYPE html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>@lang('app.adminPanel') | {{ __($pageTitle) }}</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/fontawesome_5.min.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->

    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/admin/style.css?ver=1.0.3')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/dev_style_b.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('themes/wceo/assets/css/light-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/floating-labels.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/responsive.css?ver=1.0.1')}}">



    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/select2.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('themes/wceo/assets/css/date-picker.css') }}">

    @stack('head-script')

    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">

    @if($global->rounded_theme)
        <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">
    @endif

    @if(file_exists(public_path().'/css/admin-custom.css'))
        <link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
    @endif


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3PGLE7QTT6"></script>

</head>
<body>
<!-- Loader starts-->
<div class="loader-wrapper">
<img src="{{ asset('loader.gif') }}" />

</div>


<div class="page-wrapper compact-wrapper wceo-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body">
                    @yield('content')
        </div>
    </div>

</div>


<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<!-- Bootstrap js-->
<script src="{{ asset('themes/wceo/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{ asset('themes/wceo/assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('themes/wceo/assets/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
{{--<script src="{{ asset('themes/wceo/assets/js/chart/chartist/chartist.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/prism/prism.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/counter/counter-custom.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/custom-card/custom-card.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/notify/bootstrap-notify.min.js')}}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/dashboard/default.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/notify/index.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.bundle.js')}}"></script>
{{--<script src="{{ asset('themes/wceo/assets/js/typeahead/typeahead.custom.js')}}"></script>--}}
<script src="{{ asset('themes/wceo/assets/js/chat-menu.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/height-equal.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('themes/wceo/assets/js/script.js')}}"></script>

</body>
</html>
