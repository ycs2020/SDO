
<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Turnos - Control Centralizado</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        .fc-event {
            border: none !important;
        }

        .fc-event .fc-event-title {
            white-space: normal !important;
        }
    </style>
    <!-- html2canvas para capturar como imagen -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- jsPDF para generar el PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

</head>


<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Turnos - Control Centralizado</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <button id="btnExportarCalendario" class="btn btn-success btn-block">
                                            <i class="fas fa-file-pdf"></i> Exportar Calendario PDF
                                        </button>
                                        <div id="loaderExport" style="display:none; text-align:center;">
                                            <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>

                                            <p class="text-muted mt-2">Generando PDF...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3">
                                    <b>Turnos - Control Centralizado</b>
                                </h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link active " href="#tab2025" data-toggle="tab">
                                            <b>2025</b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tab2025">

                                        <div class="row">

                                            <div class="col-12">

                                                <div id="calendar"></div>

                                                <style>
                                                    /* Forzar fondo blanco en días fuera del mes actual */
                                                    .fc-daygrid-day.fc-day-other {
                                                        background-color: white !important;
                                                    }

                                                    /* Ocultar footer al exportar */
                                                    body.sin-footer .main-footer {
                                                        display: none !important;
                                                    }
                                                </style>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const calendarEl = document.getElementById('calendar');
                                                        const loader = document.getElementById("loaderExport");
                                                        const exportBtn = document.getElementById("btnExportarCalendario");

                                                        let calendar = new FullCalendar.Calendar(calendarEl, {
                                                            locale: 'es',
                                                            firstDay: 1,
                                                            height: 'auto',
                                                            headerToolbar: {
                                                                left: 'prev,next',
                                                                center: 'title',
                                                                right: 'today,month'
                                                            },
                                                            events: '/sdo-includes/turnos_cargar_datos.php',
                                                            showNonCurrentDates: false,
                                                            eventContent: function (arg) {
                                                                return {html: arg.event.title};
                                                            },
                                                            eventColor: function (info) {
                                                                return info.event.extendedProps.color || '#32CD32';
                                                            },
                                                            buttonText: {
                                                                today: 'HOY',
                                                                month: 'MES',
                                                                week: 'SEMANA',
                                                                day: 'DÍA'
                                                            },
                                                            titleFormat: {
                                                                month: 'long',
                                                                year: 'numeric'
                                                            },
                                                            eventsSet: function () {
                                                                const titleEl = document.querySelector('.fc-header-title');
                                                                if (titleEl) {
                                                                    titleEl.innerHTML = titleEl.innerHTML.toUpperCase();
                                                                }
                                                            }
                                                        });

                                                        calendar.render();

                                                        exportBtn.addEventListener("click", function () {
                                                            exportBtn.style.display = "none";
                                                            loader.style.display = "block";
                                                            document.body.classList.add('sin-footer');

                                                            const btnsOcultar = document.querySelectorAll('.fc-prev-button, .fc-next-button, .fc-today-button');
                                                            btnsOcultar.forEach(btn => btn.style.visibility = "hidden");

                                                            const vista = calendar.view;
                                                            const fechaInicio = vista.currentStart;
                                                            const opciones = { year: 'numeric', month: 'long' };
                                                            const tituloMesAnio = fechaInicio.toLocaleDateString('es-CL', opciones)
                                                                .replace(' de ', ' ')
                                                                .toUpperCase()
                                                                .replace(/\s+/g, '_');

                                                            html2canvas(calendarEl, {
                                                                useCORS: true,
                                                                scale: 3, // Escala razonable para buena calidad, pero sin desbordar
                                                                scrollY: -window.scrollY,
                                                                backgroundColor: "#ffffff"
                                                            }).then(canvas => {
                                                                const imgData = canvas.toDataURL("image/png");

                                                                // Tamaño oficio horizontal
                                                                const pdfWidth = 936;
                                                                const pdfHeight = 612;
                                                                const pdf = new jspdf.jsPDF({
                                                                    orientation: "landscape",
                                                                    unit: "pt",
                                                                    format: [pdfWidth, pdfHeight]
                                                                });

                                                                // Márgenes reducidos
                                                                const marginTop = 10;
                                                                const marginBottom = 10;
                                                                const marginLeft = 10;
                                                                const marginRight = 10;

                                                                const usableWidth = pdfWidth - marginLeft - marginRight;
                                                                const usableHeight = pdfHeight - marginTop - marginBottom;

                                                                const imgProps = pdf.getImageProperties(imgData);
                                                                const imgRatio = imgProps.width / imgProps.height;
                                                                const pdfRatio = usableWidth / usableHeight;

                                                                let width, height;

                                                                // 🔍 Escalar la imagen para que quepa COMPLETA en una hoja
                                                                if (imgRatio > pdfRatio) {
                                                                    // Imagen es más ancha que alta → ajustar por ancho
                                                                    width = usableWidth;
                                                                    height = width / imgRatio;
                                                                } else {
                                                                    // Imagen es más alta que ancha → ajustar por alto
                                                                    height = usableHeight;
                                                                    width = height * imgRatio;
                                                                }

                                                                const x = marginLeft + (usableWidth - width) / 2;
                                                                const y = marginTop + (usableHeight - height) / 2;

                                                                pdf.addImage(imgData, 'PNG', x, y, width, height);
                                                                pdf.save(`Calendario_Turnos_Control_Centralizado_${tituloMesAnio}.pdf`);

                                                                Swal.fire({
                                                                    icon: 'success',
                                                                    title: '¡PDF generado!',
                                                                    text: `Se exportó el calendario de ${tituloMesAnio.replace('_', ' ')} correctamente.`,
                                                                    timer: 2500,
                                                                    showConfirmButton: false
                                                                });

                                                            }).catch(err => {
                                                                console.error(err);
                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: 'Error',
                                                                    text: 'No se pudo generar el PDF del calendario.'
                                                                });

                                                            }).finally(() => {
                                                                loader.style.display = "none";
                                                                exportBtn.style.display = "inline-block";
                                                                document.body.classList.remove('sin-footer');
                                                                btnsOcultar.forEach(btn => btn.style.visibility = "visible");
                                                            });
                                                        });


                                                    });
                                                </script>


                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="js/dark-mode.js"></script>

<!-- Page specific script -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

</body>

</html>