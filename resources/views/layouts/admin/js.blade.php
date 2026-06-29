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

<!-- INPUT MASK JS-->
<script src="{{url('Admin')}}/assets/plugins/input-mask/jquery.mask.min.js"></script>

<!-- SIDE-MENU JS-->
<script src="{{url('Admin')}}/assets/plugins/sidemenu/sidemenu.js"></script>

<!-- PERFECT SCROLL BAR js-->
<script src="{{url('Admin')}}/assets/plugins/p-scroll/perfect-scrollbar.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/sidemenu/sidemenu-scroll-rtl.js"></script>

<!-- CUSTOM SCROLLBAR JS-->
<script src="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- INTERNAL CHARTJS CHART JS -->
<script src="{{url('Admin')}}/assets/plugins/chart/Chart.bundle.js"></script>
<script src="{{url('Admin')}}/assets/plugins/chart/utils.js"></script>

<!-- INTERNAL PIETY CHART JS -->
<script src="{{url('Admin')}}/assets/plugins/peitychart/jquery.peity.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/peitychart/peitychart.init.js"></script>
<!-- INTERNAL APEXCHART JS -->
<script src="{{url('Admin')}}/assets/js/apexcharts.js"></script>

<!--INTERNAL  INDEX JS -->
<script src="{{url('Admin')}}/assets/js/index1.js"></script>
<!-- SIDEBAR JS -->
<script src="{{url('Admin')}}/assets/plugins/sidebar/sidebar-rtl.js"></script>

<!-- CUSTOM JS -->
<script src="{{url('Admin')}}/assets/js/custom.js"></script>

<!-- Swicther JS -->
<script src="{{url('Admin')}}/assets/switcher/js/switcher-rtl.js"></script>


{{--=================  dropfy  ================--}}
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.min.js"></script>
@stack('admin_js')

{{--//===========================    data table  =========================--}}

<!-- INTERNAL  DATA TABLE JS-->
<script src="{{url('Admin')}}/assets/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/datatable.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/dataTables.buttons.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/jszip.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/pdfmake.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/vfs_fonts.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.html5.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.print.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/datatable/fileexport/buttons.colVis.min.js"></script>
<!-- SIDEBAR JS -->

{{--//===========================    delete element  =========================--}}

<script>
    $(document).on('keyup','.numbersOnly',function () {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
</script>

{{--=================== default toster ==============================--}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@include('layouts.admin.inc.toaster')

{{--=================== close_model ==============================--}}
<script>

    $(document).on('click','#close_model',function(e) {
        e.preventDefault();
        $('#Modal').modal('hide');
    });
</script>
{{--=================== delete all ==============================--}}
<script type="text/javascript">
    $(document).ready(function () {
        $('#master').on('click', function(e) {
            if($(this).is(':checked',true))
            {
                $(".sub_chk").prop('checked', true);
            } else {
                $(".sub_chk").prop('checked',false);
            }
        });
    });
</script>







