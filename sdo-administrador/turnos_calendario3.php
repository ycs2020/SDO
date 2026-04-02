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
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #03cea4;">
                                <div class="form-inline p-2">
                                    <label class="mr-2 text-white"><b>Año:</b></label>
                                    <select id="selectAnio" class="form-control mr-3"></select>

                                    <label class="mr-2 text-white"><b>Mes:</b></label>
                                    <select id="selectMes" class="form-control">
                                        <option value="0">Enero</option>
                                        <option value="1">Febrero</option>
                                        <option value="2">Marzo</option>
                                        <option value="3">Abril</option>
                                        <option value="4">Mayo</option>
                                        <option value="5">Junio</option>
                                        <option value="6">Julio</option>
                                        <option value="7">Agosto</option>
                                        <option value="8">Septiembre</option>
                                        <option value="9">Octubre</option>
                                        <option value="10">Noviembre</option>
                                        <option value="11">Diciembre</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="calendarGeneral"></div>
                            </div>
                        </div>

                        <style>
                            .fc-daygrid-day.fc-day-other {
                                background-color: white !important;
                            }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const calendarEl = document.getElementById("calendarGeneral");
                                const selectAnio = document.getElementById("selectAnio");
                                const selectMes = document.getElementById("selectMes");

                                fetch('/sdo-includes/turnos_obtener_anios.php')
                                    .then(response => response.json())
                                    .then(aniosRaw => {
                                        const anios = aniosRaw.map(a => parseInt(a));
                                        const anioActual = new Date().getFullYear();
                                        const mesActual = new Date().getMonth();

                                        anios.forEach(anio => {
                                            const opt = document.createElement('option');
                                            opt.value = anio;
                                            opt.textContent = anio;
                                            if (anio === anioActual) opt.selected = true;
                                            selectAnio.appendChild(opt);
                                        });

                                        selectMes.value = mesActual;

                                        const calendar = new FullCalendar.Calendar(calendarEl, {
                                            initialView: 'dayGridMonth',
                                            showNonCurrentDates: false,
                                            fixedWeekCount: false,
                                            locale: 'es',
                                            firstDay: 1,
                                            height: 'auto',
                                            headerToolbar: {
                                                left: 'prev,next today',
                                                center: 'title',
                                                right: 'month'
                                            },
                                            events: function (info, successCallback, failureCallback) {
                                                const anio = parseInt(selectAnio.value);
                                                fetch(`/sdo-includes/turnos_cargar_datos.php?anio=${anio}`)
                                                    .then(r => r.json())
                                                    .then(data => successCallback(data))
                                                    .catch(err => failureCallback(err));
                                            },
                                            eventContent: function (arg) {
                                                return { html: arg.event.title };
                                            },
                                            eventColor: function (info) {
                                                return info.event.extendedProps.color || '#03cea4';
                                            },
                                            buttonText: {
                                                today: 'HOY',
                                                month: 'MES'
                                            },
                                            titleFormat: {
                                                month: 'long',
                                                year: 'numeric'
                                            },
                                            eventClick: function (info) {
                                                const evento = info.event;
                                                const props = evento.extendedProps;

                                                const funcionario1Actual = props.funcionario_1?.trim();
                                                const funcionario2Actual = props.funcionario_2?.trim();

                                                // console.log("🧠 Funcionario 1 desde BD:", funcionario1Actual);
                                                // console.log("🧠 Funcionario 2 desde BD:", funcionario2Actual);

                                                fetch('/sdo-includes/turnos_obtener_funcionarios.php')
                                                    .then(res => res.json())
                                                    .then(nombres => {
                                                        const select1 = document.getElementById("funcionario1");
                                                        const select2 = document.getElementById("funcionario2");

                                                        select1.innerHTML = '<option value="">Seleccione...</option>';
                                                        select2.innerHTML = '<option value="">Seleccione...</option>';

                                                        nombres.forEach(nombre => {
                                                            const nombreTrim = nombre.trim();

                                                            // Para select 1
                                                            const opt1 = document.createElement('option');
                                                            opt1.value = nombreTrim;
                                                            opt1.textContent = nombreTrim;

                                                            if (nombreTrim === funcionario1Actual) {
                                                                opt1.selected = true;
                                                                // console.log(`✅ Funcionario 1 seleccionado: ${nombreTrim}`);
                                                            } else {
                                                                // console.log(`⛔ Funcionario 1 NO coincide: ${nombreTrim}`);
                                                            }
                                                            select1.appendChild(opt1);

                                                            // Para select 2
                                                            const opt2 = document.createElement('option');
                                                            opt2.value = nombreTrim;
                                                            opt2.textContent = nombreTrim;

                                                            if (nombreTrim === funcionario2Actual) {
                                                                opt2.selected = true;
                                                                // console.log(`✅ Funcionario 2 seleccionado: ${nombreTrim}`);
                                                            } else {
                                                                // console.log(`⛔ Funcionario 2 NO coincide: ${nombreTrim}`);
                                                            }
                                                            select2.appendChild(opt2);
                                                        });
                                                    });

                                                // Rellenar otros campos
                                                document.getElementById("turnoId").value = props.idTurno;
                                                document.getElementById("turnoFecha").value = evento.startStr;
                                                document.getElementById("turnoHorario").value = props.turno;

                                                $('#modalEditarTurno').modal('show');
                                            }
                                        });

                                        calendar.render();
                                        calendarEl._calendarInstance = calendar; // ✅ guardar la instancia manualmente

                                        // Ir al mes/año actual al inicio
                                        calendar.gotoDate(`${anioActual}-${(mesActual + 1).toString().padStart(2, '0')}-01`);
                                    });

                                // Cambio dinámico de mes/año
                                [selectAnio, selectMes].forEach(select => {
                                    select.addEventListener('change', () => {
                                        const anio = selectAnio.value;
                                        const mes = (parseInt(selectMes.value) + 1).toString().padStart(2, '0');
                                        const calendar = calendarEl._calendarInstance;
                                        calendar.gotoDate(`${anio}-${mes}-01`);
                                        calendar.refetchEvents();
                                    });
                                });
                            });
                        </script>

                        <!-- Modal de edición de turno -->
                        <div class="modal fade" id="modalEditarTurno" tabindex="-1" role="dialog" aria-labelledby="tituloModalEditar" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form id="formEditarTurno">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title" id="tituloModalEditar">Editar Turno</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="turnoId" name="turnoId">
                                            <div class="form-group">
                                                <label>Fecha:</label>
                                                <input type="text" class="form-control" id="turnoFecha" name="fecha" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Turno:</label>
                                                <select class="form-control" id="turnoHorario" name="turno">
                                                    <option value="">Seleccione...</option>
                                                    <option value="Día">Día</option>
                                                    <option value="Noche">Noche</option>
                                                    <option value="Extra Día">Extra Día</option>
                                                    <option value="Extra Noche">Extra Noche</option>
                                                </select>
                                            </div>
                                            <!-- Funcionario 1 -->
                                            <div class="form-group d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <label>Funcionario 1:</label>
                                                    <select class="form-control" id="funcionario1" name="funcionario_1">
                                                        <option value="">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <button type="button" class="btn btn-danger ml-2" onclick="document.getElementById('funcionario1').value = ''" title="Eliminar Funcionario 1">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <!-- Funcionario 2 -->
                                            <div class="form-group d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <label>Funcionario 2:</label>
                                                    <select class="form-control" id="funcionario2" name="funcionario_2">
                                                        <option value="">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <button type="button" class="btn btn-danger ml-2" onclick="document.getElementById('funcionario2').value = ''" title="Eliminar Funcionario 2">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </form>
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

<!-- EXPORTAR PDF -->
<script>
    document.getElementById("btnExportarCalendario").addEventListener("click", async function () {
        const exportBtn = this;
        const loader = document.getElementById("loaderExport");
        const calendarEl = document.getElementById("calendarGeneral");
        const { jsPDF } = window.jspdf;

        exportBtn.style.display = "none";
        loader.style.display = "block";

        try {
            const calendarApi = calendarEl._calendarInstance;
            if (!calendarApi) throw new Error("No se encontró la instancia del calendario");

            const fechaInicio = calendarApi.view.currentStart;
            if (!fechaInicio) throw new Error("No se pudo obtener la fecha actual");

            const opciones = { year: 'numeric', month: 'long' };
            const tituloMesAnio = fechaInicio.toLocaleDateString('es-CL', opciones)
                .replace(' de ', ' ')
                .toUpperCase()
                .replace(/\s+/g, '_');

            await new Promise(resolve => setTimeout(resolve, 100));

            const canvas = await html2canvas(calendarEl, {
                useCORS: true,
                scale: 2,
                scrollY: 0,
                backgroundColor: "#ffffff"
            });

            const imgData = canvas.toDataURL("image/png");

            const pdf = new jsPDF({
                orientation: "landscape",
                unit: "pt",
                format: [936, 612] // Tamaño OFICIO landscape (13"x8.5")
            });

            const pageWidth = pdf.internal.pageSize.getWidth();    // 936pt
            const pageHeight = pdf.internal.pageSize.getHeight();  // 612pt

            const margin = 43; // ≈ 1.5 cm
            const usableWidth = pageWidth - margin * 2;
            const usableHeight = pageHeight - margin * 2;

            pdf.addImage(imgData, 'PNG', margin, margin, usableWidth, usableHeight);

            const pdfBlob = pdf.output("blob");
            const blobUrl = URL.createObjectURL(pdfBlob);
            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = `Calendario_Turnos_${tituloMesAnio}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: '¡PDF generado!',
                text: `Se exportó el calendario de ${tituloMesAnio.replace('_', ' ')} correctamente.`,
                timer: 2500,
                showConfirmButton: false
            });

        } catch (error) {
            console.error("❌ Error real en generación PDF:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo generar el PDF del calendario.'
            });
        } finally {
            loader.style.display = "none";
            exportBtn.style.display = "inline-block";
        }
    });
</script>

<!-- ACTUALIZAR TURNO -->
<script>
    $(document).ready(function () {
        $('#formEditarTurno').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: '/sdo-includes/turnos_actualizar.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (respuesta) {
                    if (respuesta.debug) {
                        console.log("🛠️ SQL ejecutada (modo debug):");
                        console.log(respuesta.sql);
                    } else {
                        $('#modalEditarTurno').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Turno actualizado',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        const calendar = document.getElementById("calendarGeneral")._calendarInstance;
                        if (calendar) calendar.refetchEvents();
                    }
                },
                error: function (xhr) {
                    console.error("❌ Error al actualizar:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar el turno.'
                    });
                }
            });
        });

    });
</script>








</body>

</html>