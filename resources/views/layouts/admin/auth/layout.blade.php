<!doctype html>
<html lang="en" dir="rtl">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Yoha –  HTML5 Bootstrap Admin Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin dashboard html template, admin dashboard template bootstrap 4, analytics dashboard templates, best admin template bootstrap 4, best bootstrap admin template, bootstrap 4 template admin, bootstrap admin template premium, bootstrap admin ui, bootstrap basic admin template, cool admin template, dark admin dashboard, dark admin template, dark dashboard template, dashboard template bootstrap 4, ecommerce dashboard template, html5 admin template, light bootstrap dashboard, sales dashboard template, simple dashboard bootstrap 4, template bootstrap 4 admin">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{get_file(setting()->fav_icon)}}" />

    <!-- TITLE -->
    <title>{{setting()->name}} –  تسجيل الدخول</title>

    <!-- BOOTSTRAP CSS -->
    <link href="{{url('Admin')}}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{url('Admin')}}/assets/css-rtl/style.css" rel="stylesheet"/>
    <link href="{{url('Admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet"/>
    <link href="{{url('Admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet"/>

    <!-- CUSTOM SCROLL BAR CSS-->
    <link href="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>

    <!--- FONT-ICONS CSS -->
    <link href="{{url('Admin')}}/assets/css/icons.css" rel="stylesheet"/>

    <!-- INTERNAL SINGLE-PAGE CSS -->
    <link href="{{url('Admin')}}/assets/plugins/single-page/css/main.css" rel="stylesheet" type="text/css">

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('Admin')}}/assets/colors/color1.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

</head>

<body class="app sidebar-mini">

<!-- BACKGROUND-IMAGE -->
<div class="login-img">

    <!-- GLOABAL LOADER -->
    <div id="global-loader">
        <img src="{{url('Admin')}}/assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End GLOABAL LOADER -->
    <!-- PAGE -->
    <div class="page">
        <div class="">
            @yield('content')
        </div>
    </div>
    <!-- END PAGE -->
</div>
<!-- BACKGROUND-IMAGE CLOSED -->
<!-- JQUERY JS -->
<script src="{{url('Admin')}}/assets/js/jquery-3.4.1.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/popper.min.js"></script>

<!-- SPARKLINE JS-->
<script src="{{url('Admin')}}/assets/js/jquery.sparkline.min.js"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{url('Admin')}}/assets/js/circle-progress.min.js"></script>

<!-- RATING STARJS -->
<script src="{{url('Admin')}}/assets/plugins/rating/jquery.rating-stars.js"></script>

<!-- EVA-ICONS JS -->
<script src="{{url('Admin')}}/assets/iconfonts/eva.min.js"></script>

<!-- CUSTOM SCROLLBAR JS-->
<script src="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>


<!-- CUSTOM JS -->
<script src="{{url('Admin')}}/assets/js/custom.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@include('layouts.admin.inc.toaster')

@include('layouts.admin.inc.my-form')

</body>

</html>
