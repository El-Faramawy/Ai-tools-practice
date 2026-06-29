<!-- META DATA -->
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="{{setting()->name}}">
<meta name="author" content="Ahmed Samir">
<meta name="csrf-token" content="{{csrf_token()}}">
<meta name="keywords" content="{{setting()->name}}, Hardy, hardy, Hardy CX, hardy cx, cx stations">

<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="{{get_file(setting()->fav_icon)}}" />

<!-- TITLE -->
<title>{{setting()->name}}</title>

<!-- BOOTSTRAP CSS -->
<link href="{{url('Admin')}}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<!-- STYLE CSS -->
<link href="{{url('Admin')}}/assets/css-rtl/style.css" rel="stylesheet"/>
<link href="{{url('Admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet"/>
<link href="{{url('Admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet"/>

<!-- SIDE-MENU CSS -->
<link href="{{url('Admin')}}/assets/css-rtl/sidemenu.css" rel="stylesheet">

<!--PERFECT SCROLL CSS-->
<link href="{{url('Admin')}}/assets/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet"/>

<!-- CUSTOM SCROLL BAR CSS-->
<link href="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>

<!--- FONT-ICONS CSS -->
<link href="{{url('Admin')}}/assets/css/icons.css" rel="stylesheet"/>

<!-- SIDEBAR CSS -->
<link href="{{url('Admin')}}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('Admin')}}/assets/colors/color1.css" />

<!-- Switcher CSS -->
<link href="{{url('Admin')}}/assets/switcher/css/switcher-rtl.css" rel="stylesheet">
<link href="{{url('Admin')}}/assets/switcher/demo.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


<!-- INTERNAL  DATA TABLE CSS-->
<link href="{{url('Admin')}}/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
<link href="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css" rel="stylesheet" />
<!-- SIDEBAR CSS -->

{{--Fonts--}}
<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
<style>
    *{
        font-family: 'Cairo', sans-serif;
        /*font-size: 16px;*/
    }
    /*.form-control{*/
    /*    font-family: 'Cairo',sans-serif !Important;*/
    /*}*/

    /*.btn{*/
    /*    font-family: "Cairo" ,sans-serif !important;*/
    /*    font-size: 1.2rem !important;*/
    /*    border-radius: 7px!important;*/
    /*}*/
    /*input{*/
    /*    font-family: "Cairo" ,sans-serif !important;*/
    /*}*/
</style>

<style>
    .img:hover{
        transform: scale(3)
    }
    .img{
        width: 100px!important;
        height: 70px!important;
        border-radius: 5px;
    }
    div#exportexample_filter {
        display: inline-block;
        float: left;
    }
    /*button.btn.btn-light.buttons-collection.dropdown-toggle.buttons-colvis {*/
    /*    width: 220px;*/
    /*}*/
    .dt-button span {
        padding-right: 38px;
    }

    #widgetChart1{
        width: 104% !important;
        border-radius: 20%;
    }
    .side-menu .side-menu__icon {
        font-size: 22px;
        font-weight: 500;
    }
    div#exportexample_wrapper {
        width: 99%;
    }
</style>

