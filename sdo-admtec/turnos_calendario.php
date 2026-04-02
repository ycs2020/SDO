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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


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
    include '../sdo-templates/admtec-sidebar.php';
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
                            <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #03cea4;">
                                <div class="form-inline p-2">
                                    <label class="mr-2 text-white"><b>Año:</b></label>
                                    <select id="selectAnio" class="form-control mr-3"></select>

                                    <label class="mr-2 text-white"><b>Mes:</b></label>
                                    <select id="selectMes" class="form-control"></select>
                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row justify-content-center">
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-primary text-white btn-block" data-toggle="modal" data-target="#modalCrearTurno">
                                                            <i class="fas fa-plus"></i> Crear Turno
                                                        </button>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-info text-white btn-block" id="btnExportarPNG">
                                                            <i class="fas fa-image"></i> Exportar mes (PNG)
                                                        </button>
                                                    </div>
                                                    <?php
                                                    $ipCliente = $_SERVER['REMOTE_ADDR'];

                                                    if($ipCliente == '10.69.250.151A'){
                                                        $displayBTN = "";
                                                    }else{
                                                        $displayBTN = "display: none";
                                                    }
                                                    ?>
                                                    <div style="<?php echo $displayBTN; ?>" class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-warning text-white btn-block" id="btnExportarPDF">
                                                            <i class="fas fa-file-pdf"></i> Exportar mes (PDF Carta)
                                                        </button>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-danger text-white btn-block" id="btnExportarPDFOficio">
                                                            <i class="fas fa-file-pdf"></i> Exportar mes (PDF Oficio)
                                                        </button>
                                                    </div>
                                                    <div style="<?php echo $displayBTN; ?>" class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <button class="btn bg-purple text-white btn-block" id="btnExportarPDFLegal">
                                                            <i class="fas fa-file-pdf"></i> Exportar mes (PDF Legal)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenedor Calendario -->
                                <div id="contenedorCalendario" class="mt-3"></div>

                                <!-- Modal Crear Turno -->
                                <div class="modal fade" id="modalCrearTurno" tabindex="-1" role="dialog" aria-labelledby="modalLabelTurno" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form id="formCrearTurno" class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="modalLabelTurno">Crear Nuevo Turno</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label>Funcionario</label>
                                                    <select name="funcionarioTurno" id="selectFuncionarioCrear" class="form-control" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php
                                                        // Carga base de funcionarios (ajusta a tu SQL real)
                                                        $sqlFun = "SELECT nomPersonal AS nombre FROM personal WHERE activoPersonal = 1 AND departamentoPersonal = 1
                                                                     UNION
                                                                     SELECT nomJefe FROM jefes_tecnicos WHERE userActivado = 1 AND idDepartamento = 1
                                                                     ORDER BY nombre ASC";
                                                        $resFun = $conexion->query($sqlFun);
                                                        while ($f = $resFun->fetch_assoc()) {
                                                            echo '<option value="' . htmlspecialchars($f['nombre'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($f['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tipo de Turno</label>
                                                    <select name="tipoTurno" class="form-control" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Día">Día</option>
                                                        <option value="Extra Día">Extra Día</option>
                                                        <option value="Noche">Noche</option>
                                                        <option value="Extra Noche">Extra Noche</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <input type="date" name="fechaTurno" class="form-control" required>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="btnGuardarTurno" class="btn btn-primary">
                                                    <span id="spinnerGuardar" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                    <span id="textoGuardar"><i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</span>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Editar Turno -->
                                <div class="modal fade" id="modalEditarTurno" tabindex="-1" role="dialog" aria-labelledby="modalLabelEditarTurno" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form id="formEditarTurno" class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabelEditarTurno">Editar Turno</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" name="idTurno" id="editIdTurno">

                                                <div class="form-group">
                                                    <label>Funcionario</label>
                                                    <select name="funcionarioTurno" id="editFuncionarioTurno" class="form-control" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php
                                                        // misma fuente de funcionarios
                                                        $sqlFun2 = "SELECT nomPersonal AS nombre FROM personal WHERE activoPersonal = 1 AND departamentoPersonal = 1
                                                                     UNION
                                                                     SELECT nomJefe FROM jefes_tecnicos WHERE userActivado = 1 AND idDepartamento = 1
                                                                     ORDER BY nombre ASC";
                                                        $resFun2 = $conexion->query($sqlFun2);
                                                        while ($r = $resFun2->fetch_assoc()) {
                                                            echo '<option value="' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tipo de Turno</label>
                                                    <select name="tipoTurno" id="editTipoTurno" class="form-control" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Día">Día</option>
                                                        <option value="Extra Día">Extra Día</option>
                                                        <option value="Noche">Noche</option>
                                                        <option value="Extra Noche">Extra Noche</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <input type="date" name="fechaTurno" id="editFechaTurno" class="form-control" required>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="btnGuardarEdicion" class="btn btn-primary">
                                                    <span id="spinnerEditar" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                    <span id="textoEditar">Guardar cambios</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- SweetAlert2 (si no lo tienes ya) -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <style>
                        /* Altura uniforme y buen wrap */
                        #contenedorCalendario td {
                            vertical-align: top;
                            height: 110px;
                            word-wrap: break-word;
                            padding: 6px;
                        }

                        #contenedorCalendario table {
                            table-layout: fixed;
                            width: 100%;
                        }

                        /* Bloques por tipo (fondo sólido para dark-mode) */
                        .bloque-tipo {
                            font-size: 0.75em;
                            font-weight: 500;
                            border-radius: 6px;
                            padding: 6px;
                            margin-bottom: 8px;
                            color: #ffffff;
                        }

                        .item-turno {
                            background: rgba(255, 255, 255, 0.12);
                            border-radius: 4px;
                            padding: 2px 6px;
                            margin: 4px 0;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }
                    </style>

                    <script>
                        $(document).ready(function () {
                            // --- referencias a selects ---
                            const selectAnio = $('#selectAnio');
                            const selectMes  = $('#selectMes');

                            // --- fecha actual (para seleccionar por defecto) ---
                            const hoy = new Date();
                            const anioActual = hoy.getFullYear();
                            const mesActual  = hoy.getMonth() + 1; // 1..12

                            // --- almacén de datos año->meses que devuelve el endpoint ---
                            let mapaAniosMeses = {}; // { "2024": [1,2,3], "2025": [8,9,10], ... }

                            // --- función: cargar calendario del año/mes seleccionados ---
                            function cargarCalendario() {
                                const anio = selectAnio.val();
                                const mes  = selectMes.val();
                                if (!anio || !mes) return;

                                $.ajax({
                                    url: '/sdo-includes/turnos_calendario_cargar_dias_mes.php',
                                    method: 'POST',
                                    data: { anio: anio, mes: mes },
                                    dataType: 'html',
                                    success: function (tablaHTML) {
                                        $('#contenedorCalendario').html(tablaHTML);
                                    },
                                    error: function (xhr, status, error) {
                                        console.error('Error al cargar calendario:', error);
                                        $('#contenedorCalendario').html('<div class="text-danger">No se pudo cargar el calendario.</div>');
                                    }
                                });
                            }

                            // --- handler: cambio de AÑO (puebla meses y selecciona el correcto) ---
                            function onCambioAnio() {
                                const anioSel = parseInt(selectAnio.val(), 10);     // comparar como número
                                const meses = (mapaAniosMeses[String(anioSel)] || [])
                                    .map(Number)                                    // asegurar enteros
                                    .sort((a, b) => a - b);                         // 1..12

                                // poblar selectMes
                                selectMes.empty();
                                meses.forEach(function (mes) {
                                    const nombreMes = new Date(0, mes - 1).toLocaleString('es-CL', { month: 'long' });
                                    const label = nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
                                    selectMes.append('<option value="' + String(mes) + '">' + label + '</option>');
                                });

                                // Selección del mes:
                                // - Si es el año actual y el mes actual existe -> seleccionar mes actual
                                // - Si no, seleccionar el último mes disponible
                                if (anioSel === anioActual && meses.includes(mesActual)) {
                                    selectMes.val(String(mesActual));               // DOM espera string
                                } else if (meses.length > 0) {
                                    selectMes.val(String(meses[meses.length - 1])); // o meses[0] si prefieres el primero
                                }

                                cargarCalendario();
                            }

                            // --- Cargar años y meses disponibles ---
                            $.ajax({
                                url: '/sdo-includes/turnos_calendario_cargar_anios_meses.php',
                                method: 'GET',
                                dataType: 'json',
                                beforeSend: function () {
                                    selectAnio.prop('disabled', true);
                                    selectMes.prop('disabled', true);
                                },
                                success: function (data) {
                                    // Guardamos el mapa completo para uso en el cambio de año
                                    mapaAniosMeses = data || {};

                                    // Poblar años (orden ascendente)
                                    const anios = Object.keys(mapaAniosMeses).sort();
                                    selectAnio.empty();
                                    anios.forEach(function (anio) {
                                        selectAnio.append('<option value="' + anio + '">' + anio + '</option>');
                                    });

                                    // Año inicial: actual si existe; si no, último disponible
                                    const anioInicial = anios.includes(String(anioActual)) ? String(anioActual) : anios[anios.length - 1];
                                    selectAnio.val(anioInicial);

                                    // Enlazar handlers (evitar dobles)
                                    selectAnio.off('change').on('change', onCambioAnio);
                                    selectMes.off('change').on('change', cargarCalendario);

                                    // Disparar para poblar meses y cargar calendario
                                    onCambioAnio();
                                },
                                error: function () {
                                    $('#contenedorCalendario').html('<div class="text-danger">Error al cargar opciones de año y mes.</div>');
                                },
                                complete: function () {
                                    selectAnio.prop('disabled', false);
                                    selectMes.prop('disabled', false);
                                }
                            });

                            // ================== CRUD de turnos ==================

                            // Crear turno
                            $('#formCrearTurno').on('submit', function (e) {
                                e.preventDefault();

                                $('#btnGuardarTurno').prop('disabled', true);
                                $('#spinnerGuardar').removeClass('d-none');
                                $('#textoGuardar').text('Guardando...');

                                $.ajax({
                                    url: '/sdo-includes/turnos_calendario_insertar.php',
                                    method: 'POST',
                                    data: $(this).serialize(),
                                    success: function () {
                                        $('#modalCrearTurno').modal('hide');
                                        $('#formCrearTurno')[0].reset();
                                        cargarCalendario();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Turno guardado',
                                            timer: 1600,
                                            showConfirmButton: false
                                        });
                                    },
                                    error: function (xhr) {
                                        var msg = (xhr.status === 409) ? xhr.responseText : 'Error al guardar turno.';
                                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                                    },
                                    complete: function () {
                                        $('#btnGuardarTurno').prop('disabled', false);
                                        $('#spinnerGuardar').addClass('d-none');
                                        $('#textoGuardar').text('Guardar');
                                    }
                                });
                            });

                            // Utilidades edición
                            function abrirModalEditar(data) {
                                $('#editIdTurno').val(data.id);
                                $('#editFuncionarioTurno').val(data.funcionario);
                                $('#editTipoTurno').val(data.tipo);
                                $('#editFechaTurno').val(data.fecha);
                                $('#modalEditarTurno').modal('show');
                            }

                            function confirmarYEliminar(id) {
                                Swal.fire({
                                    title: '¿Eliminar turno?',
                                    text: 'Esta acción no se puede deshacer.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar'
                                }).then(function (r) {
                                    if (r.isConfirmed) {
                                        $.ajax({
                                            url: '/sdo-includes/turnos_calendario_eliminar.php',
                                            method: 'POST',
                                            data: { idTurno: id },
                                            success: function () {
                                                Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1200, showConfirmButton: false });
                                                cargarCalendario();
                                            },
                                            error: function () {
                                                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar.' });
                                            }
                                        });
                                    }
                                });
                            }

                            // Click sobre ítem de turno
                            $(document).on('click', '.item-turno', function (e) {
                                if ($(e.target).closest('button,i').length) return;

                                var $it = $(this);
                                var fechaRaw = $it.data('fecha'); // yyyy-mm-dd
                                var partes = (fechaRaw || '').split('-');
                                var fechaBonita = (partes.length === 3) ? (partes[2] + '-' + partes[1] + '-' + partes[0]) : '';

                                var data = {
                                    id: $it.data('idturno'),
                                    funcionario: $it.data('funcionario'),
                                    tipo: $it.data('tipo'),
                                    fechaRaw: fechaRaw,
                                    fechaBonita: fechaBonita
                                };

                                Swal.fire({
                                    title: '¿Qué deseas hacer?',
                                    html:
                                        '<div style="text-align:left;"><br>'
                                        + '<div><b>Funcionario:</b> ' + $('<div>').text(data.funcionario).html() + '</div><br>'
                                        + '<div><b>Tipo:</b> ' + $('<div>').text(data.tipo).html() + '</div><br>'
                                        + '<div><b>Fecha:</b> ' + $('<div>').text(data.fechaBonita).html() + '</div>'
                                        + '</div>',
                                    showCancelButton: true,
                                    showDenyButton: true,
                                    confirmButtonText: '<i class="fas fa-pen"></i> Editar',
                                    denyButtonText: '<i class="fas fa-trash"></i> Eliminar',
                                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
                                }).then(function (r) {
                                    if (r.isConfirmed) {
                                        abrirModalEditar({
                                            id: data.id,
                                            funcionario: data.funcionario,
                                            tipo: data.tipo,
                                            fecha: data.fechaRaw // yyyy-mm-dd para el input date
                                        });
                                    } else if (r.isDenied) {
                                        confirmarYEliminar(data.id);
                                    }
                                });
                            });

                            // Botones internos (si existieran en el HTML generado)
                            $(document).on('click', '.btnEditarTurno', function (e) {
                                e.stopPropagation();
                                var $it = $(this).closest('.item-turno');
                                abrirModalEditar({
                                    id: $it.data('idturno'),
                                    funcionario: $it.data('funcionario'),
                                    tipo: $it.data('tipo'),
                                    fecha: $it.data('fecha')
                                });
                            });

                            $(document).on('click', '.btnEliminarTurno', function (e) {
                                e.stopPropagation();
                                var id = $(this).closest('.item-turno').data('idturno');
                                confirmarYEliminar(id);
                            });

                            // Guardar edición
                            $('#formEditarTurno').on('submit', function (e) {
                                e.preventDefault();

                                $('#btnGuardarEdicion').prop('disabled', true);
                                $('#spinnerEditar').removeClass('d-none');
                                $('#textoEditar').text('Guardando...');

                                $.ajax({
                                    url: '/sdo-includes/turnos_calendario_actualizar.php',
                                    method: 'POST',
                                    data: $(this).serialize(),
                                    success: function () {
                                        $('#modalEditarTurno').modal('hide');
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Turno actualizado',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        cargarCalendario();
                                    },
                                    error: function (xhr) {
                                        var msg = (xhr.status === 409) ? xhr.responseText : 'Error al actualizar el turno.';
                                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                                    },
                                    complete: function () {
                                        $('#btnGuardarEdicion').prop('disabled', false);
                                        $('#spinnerEditar').addClass('d-none');
                                        $('#textoEditar').text('Guardar cambios');
                                    }
                                });
                            });
                        });

                    </script>


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
<script>
    // Utilidad: nombre de archivo usando selects
    function nombreArchivoCalendario() {
        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text() || 'mes';
        // Quita espacios acentuados para el nombre del archivo
        var limpio = mesTxt.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '-').toLowerCase();
        return 'calendario-' + limpio + '-' + anio + '.png';
    }

    // Exportar el contenido de #contenedorCalendario como PNG
    async function exportarCalendarioComoPNG() {
        var nodo = document.querySelector('#contenedorCalendario');
        if (!nodo || !nodo.firstElementChild) {
            // No hay calendario cargado aún
            Swal.fire({
                icon: 'warning',
                title: 'Sin contenido',
                text: 'Aún no hay un calendario renderizado.'
            });
            return;
        }

        // Opcional: añadir un título encima para la exportación
        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text();
        var wrapper = document.createElement('div');
        wrapper.style.background = '#ffffff';
        wrapper.style.padding = '16px';

        var titulo = document.createElement('div');
        titulo.style.fontSize = '18px';
        titulo.style.fontWeight = '700';
        titulo.style.marginBottom = '12px';
        titulo.style.textAlign = 'center';
        titulo.style.color = '#000000';
        titulo.textContent = (mesTxt ? mesTxt.charAt(0).toUpperCase() + mesTxt.slice(1) : '') + ' ' + anio;

        // Clona el calendario actual para evitar afectar el DOM
        var clon = nodo.cloneNode(true);
        // Asegura fondo blanco del calendario clonado (mejor para dark mode)
        clon.style.background = '#ffffff';

        wrapper.appendChild(titulo);
        wrapper.appendChild(clon);
        document.body.appendChild(wrapper); // necesita estar en el DOM para medir/capturar

        try {
            const canvas = await html2canvas(wrapper, {
                backgroundColor: '#ffffff',        // Fuerza fondo blanco (útil en dark mode)
                scale: Math.min(2, window.devicePixelRatio || 1) * 2, // buena resolución
                useCORS: true,                     // si hay logos o imágenes externas
                logging: false,
                windowWidth: document.documentElement.scrollWidth
            });

            const dataURL = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = nombreArchivoCalendario();
            document.body.appendChild(link);
            link.click();
            link.remove();
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo exportar la imagen.'
            });
        } finally {
            document.body.removeChild(wrapper);
        }
    }

    // Click del botón
    $(document).on('click', '#btnExportarPNG', function () {
        exportarCalendarioComoPNG();
    });
</script>

<script>
    function nombreArchivoPDF() {
        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text() || 'mes';
        var limpio = mesTxt.normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/\s+/g,'-').toLowerCase();
        return 'calendario-' + limpio + '-' + anio + '.pdf';
    }

    function exportarCalendarioComoPDF() {
        var nodo = document.querySelector('#contenedorCalendario');
        if (!nodo || !nodo.firstElementChild) {
            Swal.fire({ icon: 'warning', title: 'Sin contenido', text: 'Aún no hay un calendario renderizado.' });
            return;
        }

        // Crear un wrapper con título
        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text();
        var wrapper = document.createElement('div');
        wrapper.style.background = '#ffffff';
        wrapper.style.padding = '16px';

        var titulo = document.createElement('div');
        titulo.style.fontSize = '18px';
        titulo.style.fontWeight = '700';
        titulo.style.marginBottom = '12px';
        titulo.style.textAlign = 'center';
        titulo.style.color = '#000000';
        titulo.textContent = (mesTxt ? mesTxt.charAt(0).toUpperCase() + mesTxt.slice(1) : '') + ' ' + anio;

        var clon = nodo.cloneNode(true);
        clon.style.background = '#ffffff';

        wrapper.appendChild(titulo);
        wrapper.appendChild(clon);

        // Opciones PDF
        var opt = {
            margin:       10,
            filename:     nombreArchivoPDF(),
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, backgroundColor: '#ffffff' },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            // landscape para que quepa más ancho, puedes usar 'portrait'
        };

        html2pdf().set(opt).from(wrapper).save();
    }

    $(document).on('click', '#btnExportarPDF', function () {
        exportarCalendarioComoPDF();
    });
</script>
<script>
    function nombreArchivoPDF() {
        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text() || 'mes';
        var limpio = mesTxt.normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/\s+/g,'-').toLowerCase();
        return 'calendario-' + limpio + '-' + anio + '.pdf';
    }

    function prepararWrapperParaExportar() {
        var cont = document.querySelector('#contenedorCalendario');
        if (!cont || !cont.firstElementChild) return null;

        var anio = $('#selectAnio').val();
        var mesTxt = $('#selectMes option:selected').text() || '';

        var wrapper = document.createElement('div');
        wrapper.style.background = '#ffffff';
        wrapper.style.padding = '16px';

        var titulo = document.createElement('div');
        titulo.style.fontSize = '18px';
        titulo.style.fontWeight = '700';
        titulo.style.marginBottom = '12px';
        titulo.style.textAlign = 'center';
        titulo.style.color = '#000000';
        titulo.textContent = (mesTxt ? mesTxt.charAt(0).toUpperCase() + mesTxt.slice(1) : '') + ' ' + anio;

        var clon = cont.cloneNode(true);
        clon.style.background = '#ffffff';

        wrapper.appendChild(titulo);
        wrapper.appendChild(clon);
        document.body.appendChild(wrapper);
        return wrapper;
    }

    async function exportarCalendarioPDF(formato, orientacion) {
        const wrapper = prepararWrapperParaExportar();
        if (!wrapper) {
            alert('Aún no hay un calendario renderizado.');
            return;
        }
        try {
            const canvas = await html2canvas(wrapper, {
                backgroundColor: '#ffffff',
                scale: Math.min(2, (window.devicePixelRatio || 1) * 2),
                useCORS: true,
                logging: false
            });

            const { jsPDF } = window.jspdf;
            // Forzamos landscape aquí
            const pdf = new jsPDF({ unit: 'mm', format: formato, orientation: orientacion });

            const margin = 10;
            const pageWidth  = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const maxW = pageWidth  - margin * 2;
            const maxH = pageHeight - margin * 2;

            const imgData = canvas.toDataURL('image/jpeg', 0.98);

            // Escalar para que quepa en UNA sola página
            let renderW = maxW;
            let renderH = (canvas.height * renderW) / canvas.width;
            if (renderH > maxH) {
                renderH = maxH;
                renderW = (canvas.width * renderH) / canvas.height;
            }

            const posX = (pageWidth - renderW) / 2;
            const posY = (pageHeight - renderH) / 2;

            pdf.addImage(imgData, 'JPEG', posX, posY, renderW, renderH);
            pdf.save(nombreArchivoPDF());
        } finally {
            document.body.removeChild(wrapper);
        }
    }

    // Oficio horizontal: 216 x 330 mm
    $(document).on('click', '#btnExportarPDFOficio', function () {
        exportarCalendarioPDF([216, 330], 'landscape'); // <-- horizontal
    });

    // Legal horizontal (predefinido por jsPDF)
    $(document).on('click', '#btnExportarPDFLegal', function () {
        exportarCalendarioPDF('legal', 'landscape'); // <-- horizontal
    });
</script>





</body>

</html>

