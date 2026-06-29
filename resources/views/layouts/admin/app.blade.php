<!doctype html>
<html lang="en" dir="rtl">

<head>

    @include('layouts.admin.css')

</head>

<body class="app sidebar-mini">


<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="{{url('Admin')}}/assets/images/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

    @include('layouts.admin.sidebar')
    <!-- Header -->
    @include('layouts.admin.header')

    <!--Content-area open-->
        <div class="app-content">
            <div class="side-app">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">@yield('page_title')</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page_title')</li>
                        </ol>
                    </div>

                </div>
                <!-- PAGE-HEADER END -->
                @yield('content')

            </div><!-- End Page -->
        </div>
        <!-- CONTAINER END -->
    </div>

    <!-- FOOTER -->
@include('layouts.admin.footer')

<!-- FOOTER END -->
</div>
<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

@include('layouts.admin.js')


</body>

</html>
