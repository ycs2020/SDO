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

                        // ==========================================
                        // 🛠️ SECCIÓN: PROCESAR RESUMEN SUPERIOR
                        // ==========================================
                        let resumenHtml = '';
                        if (respuesta.resumen && respuesta.resumen.length > 0) {
                            respuesta.resumen.forEach(function (info) {
                                let estado_clean = info.estado ? info.estado.trim().toLowerCase() : '';
                                let bg_card = 'bg-secondary';
                                let icon_estado = 'fa-dot-circle';

                                // Definir colores basados en el estado
                                if (estado_clean === 'habilitado') {
                                    bg_card = 'bg-success';
                                    icon_estado = 'fa-check-circle';
                                } else if (estado_clean === 'deshabilitado') {
                                    bg_card = 'bg-danger';
                                    icon_estado = 'fa-industry';
                                }

                                let temp_display = info.temperatura ? `${info.temperatura} °C` : 'N/A';
                                let columnasGrid = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12'; // Grid más estrecho

                                resumenHtml += `
                                    <div class="${columnasGrid} mb-2">
                                        <div class="info-box ${bg_card} p-2" style="min-height: 50px; shadow: sm;">
                                            <span class="info-box-icon text-sm" style="width: 40px; background: rgba(0,0,0,0.1); border-radius: 4px;">
                                                <i class="fas ${icon_estado} fa-sm"></i>
                                            </span>
                                            <div class="info-box-content p-0 pl-2 d-flex flex-column justify-content-center">
                                                <span class="info-box-text text-xs text-bold mb-0" style="line-height: 1.1;">Caldera ${info.caldera}</span>
                                                <span class="info-box-number text-sm mb-0" style="font-size: 0.85rem !important; font-weight: 600; line-height: 1.2;">
                                                    ${info.estado || 'S/R'}
                                                </span>
                                                <span class="text-xs" style="opacity: 0.9;">
                                                    <i class="fas fa-thermometer-half"></i> ${temp_display}
                                                </span>
                                            </div>
                                        </div>
                                    </div>`;
                            });
                        } else {
                            resumenHtml = `<div class="col-12"><div class="alert alert-light text-muted text-center"><i class="fas fa-info-circle"></i> No hay lecturas registradas para calcular el último estado.</div></div>`;
                        }

                        // Inyectar las tarjetas en la parte superior
                        $('#contenedorResumenCalderas').html(resumenHtml);


                        // ==========================================
                        // 🛠️ SECCIÓN: PROCESAR TABLA HISTÓRICA
                        // ==========================================
                        let filasHtml = '';

                        if (respuesta.data.length > 0) {
                            respuesta.data.forEach(function (fila) {
                                let badge_estado = '';
                                let estado_clean = fila.estado ? fila.estado.trim().toLowerCase() : '';

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

                                let fechaFormateada = fila.fecha;
                                if (fila.fecha) {
                                    let partes = fila.fecha.split(' ');
                                    if (partes.length === 2) {
                                        let fechaComponentes = partes[0].split('-');
                                        let horaComponentes = partes[1].split(':');

                                        if (fechaComponentes.length === 3 && horaComponentes.length >= 2) {
                                            fechaFormateada = `${fechaComponentes[2]}-${fechaComponentes[1]}-${fechaComponentes[0]} ${horaComponentes[0]}:${horaComponentes[1]}`;
                                        }
                                    }
                                }

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

                        if ($.fn.DataTable.isDataTable('#tablaCalderas')) {
                            $('#tablaCalderas').DataTable().destroy();
                        }

                        $('#tablaCalderas tbody').html(filasHtml);

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
                        inicializarDashboard()

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

        // ==========================================
// 📊 SECCIÓN: DASHBOARD & CHART MANAGEMENT
// ==========================================
        let chartTempInstance = null;

        function inicializarDashboard() {
            let selectorFiltro = $('#filtroCalderaDashboard');
            let calderaSeleccionada = (selectorFiltro.length && selectorFiltro.val()) ? selectorFiltro.val() : 1;

            if (!document.getElementById('graficoTemperatura')) return;

            let mostrarNumeros = $('#chkMostrarNumeros').length ? $('#chkMostrarNumeros').prop('checked') : true;

            $.ajax({
                url: '../sdo-includes/calderas/calderas_dashboard_datos.php',
                type: 'GET',
                data: { id_caldera: calderaSeleccionada },
                dataType: 'json',
                success: function(respuesta) {
                    if (respuesta.status === 'success') {

                        if (chartTempInstance) chartTempInstance.destroy();

                        // 🛠️ GENERAR MAPA DE COLORES DINÁMICOS POR PUNTO (Mapeo estricto)
                        let coloresPuntos = [];
                        let coloresBordes = [];

                        if (respuesta.lineas.estados && respuesta.lineas.estados.length > 0) {
                            respuesta.lineas.estados.forEach(function(estado) {
                                let est = estado.toLowerCase();
                                if (est === 'habilitado') {
                                    coloresPuntos.push('#28a745'); // Punto Verde
                                    coloresBordes.push('#1e7e34'); // Borde Verde oscuro
                                } else if (est === 'deshabilitado') {
                                    coloresPuntos.push('#dc3545'); // Punto Rojo
                                    coloresBordes.push('#bd2130'); // Borde Rojo oscuro
                                } else {
                                    coloresPuntos.push('#6c757d'); // Gris de respaldo
                                    coloresBordes.push('#fff');
                                }
                            });
                        }

                        const ctxTemp = document.getElementById('graficoTemperatura').getContext('2d');
                        chartTempInstance = new Chart(ctxTemp, {
                            type: 'line',
                            plugins: [ChartDataLabels],
                            data: {
                                labels: respuesta.lineas.labels,
                                datasets: [{
                                    label: 'Temperatura',
                                    data: respuesta.lineas.datos,
                                    backgroundColor: 'rgba(60,141,188,0.05)', // Relleno translúcido bajo la línea
                                    borderColor: 'rgba(210, 214, 222, 0.7)',  // Línea gris neutra / azulada suave para destacar los puntos
                                    borderWidth: 2,
                                    tension: 0.2,
                                    fill: true,

                                    // 🛠️ ASIGNACIÓN DE COLORES DINÁMICOS POR REGISTRO
                                    pointBackgroundColor: coloresPuntos,
                                    pointBorderColor: coloresBordes,
                                    pointRadius: 5,               // Un poco más grandes para apreciar el color del estado
                                    pointHoverRadius: 7,
                                    pointBorderWidth: 1.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    datalabels: {
                                        display: mostrarNumeros,
                                        align: 'top',
                                        anchor: 'end',
                                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                        borderRadius: 4,
                                        borderWidth: 1,
                                        borderColor: 'rgba(60,141,188,0.2)',
                                        padding: 4,
                                        font: {
                                            weight: 'bold',
                                            size: 10
                                        },
                                        formatter: function(value) {
                                            return value !== null ? value + '°C' : '';
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: { callback: value => value + ' °C' },
                                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                        grace: '10%' // Margen extra para que las etiquetas no toquen el borde superior
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            maxRotation: 45, // Inclinación elegante si el texto de la fecha es muy largo
                                            minRotation: 0,
                                            font: { size: 10 }
                                        }
                                    }
                                }
                            }
                        });

                    } else {
                        console.error("Error al procesar los datos del gráfico:", respuesta.message);
                    }
                },
                error: function(xhr) {
                    console.error("Error de comunicación con el servidor: ", xhr.responseText);
                }
            });
        }

        // 🛠️ ESCUCHAR CAMBIOS EN EL SELECTOR DE CALDERAS
        $(document).on('change', '#filtroCalderaDashboard', function() {
            inicializarDashboard();
        });

        // 🛠️ NUEVO: ESCUCHAR CAMBIOS EN EL SWITCH DE NÚMEROS
        // En vez de hacer un viaje al servidor por AJAX, modificamos la propiedad directamente y actualizamos el gráfico al instante
        $(document).on('change', '#chkMostrarNumeros', function() {
            if (chartTempInstance) {
                // Cambiamos el estado de visibilidad directamente en las opciones de la instancia actual
                chartTempInstance.options.plugins.datalabels.display = $(this).prop('checked');
                chartTempInstance.update(); // Renderiza los cambios en milisegundos sin parpadeos
            } else {
                inicializarDashboard();
            }
        });

        // RENDERIZAR CUANDO SE INGRESA A LA PESTAÑA DEL DASHBOARD
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            let targetTab = $(e.target).attr("href");
            if (targetTab === "#tabs-dashboard") {
                inicializarDashboard();
            }
        });
    });
</script>
</body>
</html>