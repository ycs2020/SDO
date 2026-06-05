<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';
include_once '../vendor/quickchart/QuickChart.php';

if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('America/Santiago');

// Validar sesión
if (!isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = mb_strtolower(trim($_SESSION['rolUsuario']), 'UTF-8');

// === Roles y rutas ===
$roles = [
    'personal' => 'sdo-personal',
    'administrativo' => 'sdo-admtec',
    'bodega pañol' => 'sdo-bodega-panol',
    'jefe tecnico' => 'sdo-jefestecnicos',
    'administrador' => 'sdo-administrador',
    'directiva' => 'sdo-directiva',
    'oficina tecnica' => 'sdo-oficinaTecnica',
];

$ruta = $roles[$rolUsuario] ?? null;
if (!$ruta) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documento Estado y Temperatura - Calderas</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

<div class="wrapper">
    <?php include '../sdo-templates/admin-sidebar.php'; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Documento Estado y T° - Calderas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="index.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div></section>

        <section class="content">
            <div class="container-fluid">
                <div class="row d-flex align-items-stretch" id="interfazPrincipal">
                    <div class="col-12">
                        <div class="card card-success card-outline card-tabs">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tabs-subir-listar-tab" data-toggle="pill" href="#tabs-subir-listar" role="tab" aria-controls="tabs-subir-listar" aria-selected="true">
                                            <i class="fas fa-file-upload"></i> Subir y Listar CSV
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tabs-dashboard-tab" data-toggle="pill" href="#tabs-dashboard" role="tab" aria-controls="tabs-dashboard" aria-selected="false">
                                            <i class="fas fa-chart-line"></i> Dashboard
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-three-tabContent">
                                    <div class="tab-pane fade show active" id="tabs-subir-listar" role="tabpanel" aria-labelledby="tabs-subir-listar-tab">
                                        <?php include '../sdo-includes/calderas/calderas_listado.php'; ?>
                                    </div>
                                    <div class="tab-pane fade" id="tabs-dashboard" role="tabpanel" aria-labelledby="tabs-dashboard-tab">
                                        <?php include '../sdo-includes/calderas/calderas_dashboard.php'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.</strong>
    </footer>
</div>
<script src="js/dark-mode.js"></script>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script src="../dist/js/datatables-config.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $(document).ready(function () {

        // 1. Manejo visual de etiquetas de archivos (Elegir archivo...)
        $(document).on('change', '.custom-file-input', function() {
            let fileName = this.files[0] ? this.files[0].name : "Elegir archivo...";
            $(this).next('.custom-file-label').html(fileName);
        });

        // 2. FUNCIÓN AJAX PARA LLENAR LA TABLA EN TIEMPO REAL
        function cargarListadoCalderas() {
            $.ajax({
                url: '../sdo-includes/calderas/calderas_cargar_datos.php',
                type: 'GET',
                dataType: 'json',
                success: function (respuesta) {
                    if (respuesta.status === 'success') {
                        let filasHtml = '';

                        if (respuesta.data.length > 0) {
                            respuesta.data.forEach(function (fila) {
                                let badge_estado = '';
                                let estado_clean = fila.estado ? fila.estado.trim().toLowerCase() : '';

                                // 🛠️ LÓGICA DE COLORES DINÁMICOS
                                if (estado_clean === 'habilitado') {
                                    badge_estado = `<span class="badge badge-success">Habilitado</span>`;
                                } else if (estado_clean === 'deshabilitado') {
                                    badge_estado = `<span class="badge badge-danger">Deshabilitado</span>`;
                                } else if (fila.estado) {
                                    badge_estado = `<span class="badge badge-secondary">${fila.estado}</span>`;
                                } else {
                                    badge_estado = `<span class="text-muted text-sm">Sin registro</span>`;
                                }

                                let texto_temp = fila.temperatura ? `<strong>${fila.temperatura} °C</strong>` : `<span class="text-muted">Sin registro</span>`;

                                // 🛠️ FORMATEAR FECHA DE "Y-m-d H:i:s" A "d-m-Y H:i"
                                let fechaFormateada = fila.fecha;
                                if (fila.fecha) {
                                    // Separar fecha de la hora
                                    let partes = fila.fecha.split(' ');
                                    if (partes.length === 2) {
                                        let fechaComponentes = partes[0].split('-'); // [Año, Mes, Día]
                                        let horaComponentes = partes[1].split(':');  // [Hora, Minuto, Segundo]

                                        if (fechaComponentes.length === 3 && horaComponentes.length >= 2) {
                                            // Reordenar a: Día-Mes-Año Hora:Minuto
                                            fechaFormateada = `${fechaComponentes[2]}-${fechaComponentes[1]}-${fechaComponentes[0]} ${horaComponentes[0]}:${horaComponentes[1]}`;
                                        }
                                    }
                                }

                                // 🛠️ AGREGAR 'text-center' A TODAS LAS CELDAS PARA CENTRALIZAR
                                filasHtml += `<tr>
                                    <td class="text-center">${fechaFormateada}</td>
                                    <td class="text-center"><span class="badge badge-dark">Caldera ${fila.caldera}</span></td>
                                    <td class="text-center">${badge_estado}</td>
                                    <td class="text-center">${texto_temp}</td>
                                </tr>`;
                            });
                        } else {
                            filasHtml = `<tr><td colspan="4" class="text-center text-muted">No existen registros en la base de datos.</td></tr>`;
                        }

                        // Destruir DataTable si ya existía una instancia previa activa
                        if ($.fn.DataTable.isDataTable('#tablaCalderas')) {
                            $('#tablaCalderas').DataTable().destroy();
                        }

                        // Inyectar las nuevas filas en el DOM
                        $('#tablaCalderas tbody').html(filasHtml);

                        // EXTENDER DATATABLE CONFIG
                        $('#tablaCalderas').DataTable({
                            ...dataTableConfig,
                            order: [[0, "desc"]]
                        });

                    } else {
                        toastr.error(respuesta.message || 'No se pudo actualizar el histórico.');
                    }
                },
                error: function (xhr) {
                    $('#tablaCalderas tbody').html('<tr><td colspan="4" class="text-center text-danger">Error de comunicación al cargar histórico.</td></tr>');
                    console.error("Error en el listado: ", xhr.responseText);
                }
            });
        }

        // Ejecutar la carga por primera vez al cargar la página
        cargarListadoCalderas();

        // 3. AJAX PARA PROCESAR EL FORMULARIO DEL MODAL
        $('#formCargaPasos').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $('#procesandoCarga').removeClass('d-none');
            $('#btnEnviarCarga').prop('disabled', true);
            $('#btnCerrarModal').prop('disabled', true);

            $.ajax({
                url: '../sdo-includes/calderas/calderas_agregar_datos.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (respuesta) {
                    if (respuesta.status === 'success') {
                        toastr.success(respuesta.message);

                        // Resetear y ocultar modal
                        $('#modalCargaCalderas').modal('hide');
                        $('#formCargaPasos')[0].reset();
                        $('.custom-file-label').html('Elegir archivo...');

                        // REFRESCAR LA TABLA AL INSTANTE CON LOS NUEVOS DATOS
                        cargarListadoCalderas();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Atención',
                            text: respuesta.message
                        });
                    }
                },
                error: function () {
                    toastr.error('Ocurrió un problema crítico en la carga.');
                },
                complete: function () {
                    $('#procesandoCarga').addClass('d-none');
                    $('#btnEnviarCarga').prop('disabled', false);
                    $('#btnCerrarModal').prop('disabled', false);
                }
            });
        });
    });
</script>
</body>
</html>