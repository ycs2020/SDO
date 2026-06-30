<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Rescatar y validar el ID enviado por POST desde el JS
$idAnalizador = isset($_POST['idAnalizador']) ? (int)$_POST['idAnalizador'] : 0;

if ($idAnalizador <= 0) {
    header("Location: index.php");
    exit;
}

date_default_timezone_set('America/Santiago');

// 2. Consultar los datos específicos del analizador seleccionado
$datosAnalizador = null;
$query = "SELECT idAnalizador, nomAnalizador, pisoAnalizador, sectorAnalizador 
          FROM analizadores_red 
          WHERE idAnalizador = ?";

if ($stmt = $conexion->prepare($query)) {
    $stmt->bind_param("i", $idAnalizador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $datosAnalizador = $resultado->fetch_assoc();
    }
    $stmt->close();
}

if (!$datosAnalizador) {
    echo "<script>alert('El analizador solicitado no existe.'); window.location.href='index.php';</script>";
    exit;
}

// Consultar sub-analizadores
$subAnalizadores = [];
$querySubAnalizador = "SELECT idSubAnalizador, nomSubAnalizador 
                       FROM analizadores_red_sub
                       WHERE idAnalizador = ?
                       ORDER BY nomSubAnalizador ASC";

if ($stmtSubAnalizador = $conexion->prepare($querySubAnalizador)) {
    $stmtSubAnalizador->bind_param("i", $idAnalizador);
    $stmtSubAnalizador->execute();
    $resultSubAnalizador = $stmtSubAnalizador->get_result();

    if ($resultSubAnalizador->num_rows > 0) {
        while ($fila = $resultSubAnalizador->fetch_assoc()) {
            $subAnalizadores[] = [
                'id' => $fila['idSubAnalizador'],
                'nombre' => $fila['nomSubAnalizador']
            ];
        }
    }
    $stmtSubAnalizador->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Analizador - <?= htmlspecialchars($datosAnalizador['nomAnalizador']) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/user-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3">
                        <div class="card bg-gradient-primary p-3 text-white h-100 d-flex justify-content-center mb-0">
                            <h5 class="mb-0">Analizadores de Red -
                                <b><?= htmlspecialchars($datosAnalizador['nomAnalizador']) ?></b>
                            </h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="analizadores_red_listado.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center">
                            <i class="fa fa-chevron-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-12 mb-3">
                        <div class="card card-primary card-outline shadow-sm bg-white">
                            <div class="card-body p-3">
                                <div class="row align-items-center">

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-12 d-flex flex-wrap align-items-center mb-xl-0 mb-lg-0 mb-md-3 mb-3">
                                        <div class="mr-4 text-primary d-none d-sm-block">
                                            <i class="fas fa-charging-station fa-2x"></i>
                                        </div>
                                        <div class="mr-4">
                                            <span class="text-muted text-xs d-block">ANALIZADOR</span>
                                            <h4 class="font-weight-bold text-dark mb-0"><?= htmlspecialchars($datosAnalizador['nomAnalizador']) ?></h4>
                                        </div>
                                        <div class="mr-4 border-left pl-4">
                                            <span class="text-muted text-xs d-block">UBICACIÓN</span>
                                            <span class="font-weight-bold text-secondary text-sm">
                            <i class="fas fa-layer-group text-muted mr-1"></i> Piso <?= htmlspecialchars($datosAnalizador['pisoAnalizador']) ?>
                        </span>
                                        </div>
                                        <div class="border-left pl-4">
                                            <span class="text-muted text-xs d-block">SECTOR / SALA</span>
                                            <span class="font-weight-bold text-secondary text-sm">
                            <i class="fas fa-map-marker-alt text-muted mr-1"></i> <?= htmlspecialchars($datosAnalizador['sectorAnalizador']) ?>
                        </span>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-12 text-xl-right text-lg-right text-md-left text-left">
                                        <div class="d-inline-flex flex-wrap gap-2 w-100 justify-content-xl-end justify-content-lg-end">

                                            <form action="../sdo-pdf/SDO-Analizadores-Red-Informe.php" method="POST" target="_blank" class="d-inline mr-2 mb-2 mb-sm-0">
                                                <input type="hidden" name="idAnalizadorEspecifico" value="<?= $idAnalizador ?>">
                                                <input type="hidden" name="origen" value="detalle_analizador">
                                                <button type="submit" class="btn btn-danger shadow-sm">
                                                    <i class="fas fa-file-pdf mr-1"></i> Informe de Anomalías
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-primary mr-2 mb-2 mb-sm-0 shadow-sm" data-toggle="modal" data-target="#modalCrearSubAnalizador">
                                                <i class="fas fa-plus-circle mr-1"></i> Nuevo Sub-Analizador
                                            </button>

                                            <button type="button" class="btn btn-success mb-2 mb-sm-0 shadow-sm" data-toggle="modal" data-target="#modalSubirCSV">
                                                <i class="fas fa-file-upload mr-1"></i> Importar Datos (CSV)
                                            </button>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalCrearSubAnalizador" tabindex="-1" role="dialog" aria-labelledby="modalCrearSubAnalizadorLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalCrearSubAnalizadorLabel">
                                        <i class="fas fa-plus-circle mr-2"></i> Crear Nuevo Sub-Analizador
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="formCrearSubAnalizador">
                                    <div class="modal-body">
                                        <input type="hidden" name="idAnalizador" value="<?= $idAnalizador ?>">

                                        <div class="form-group mb-3">
                                            <label class="form-label font-weight-bold" for="nomSubAnalizador">
                                                Nombre del Sub-Analizador / Parámetro: <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-bolt text-primary"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="nomSubAnalizador" id="nomSubAnalizador" placeholder="Ej: BME1, TGBT-Clima, etc." required maxlength="200">
                                            </div>
                                            <small class="text-muted d-block mt-1">Se creará vinculado automáticamente a la cabecera de este analizador.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary" id="btnGuardarSub">
                                            <i class="fas fa-save mr-1"></i> Guardar Sub-Analizador
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            <i class="fas fa-times mr-1"></i> Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <?php
                        if (!empty($subAnalizadores)) {
                            $defectoSubAnalizador = $subAnalizadores[0];
                            ?>
                            <div class="card card-primary card-outline card-tabs shadow-sm">
                                <div class="card-header p-0 pt-1 border-bottom-0 bg-gradient-primary">
                                    <ul class="nav nav-tabs" id="tabSubAnalizadores" role="tablist">
                                        <?php
                                        foreach ($subAnalizadores as $sub) {
                                            $activeClass = ($sub['id'] == $defectoSubAnalizador['id']) ? 'active' : '';
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link tab-sub-ajax <?= $activeClass ?>"
                                                    id="tab-sub-<?= $sub['id'] ?>"
                                                    data-toggle="tab"
                                                    data-subid="<?= $sub['id'] ?>"
                                                    href="#content-sub-<?= $sub['id'] ?>"
                                                    role="tab">
                                                    <i class="fas fa-bolt mr-1"></i> <?= htmlspecialchars($sub['nombre']) ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">
                                        <?php
                                        foreach ($subAnalizadores as $sub) {
                                            $activeClass = ($sub['id'] == $defectoSubAnalizador['id']) ? ' show active' : '';
                                            ?>
                                            <div class="tab-pane fade <?= $activeClass ?>" id="content-sub-<?= $sub['id'] ?>" role="tabpanel">

                                                <ul class="nav nav-pills mb-3" id="pills-tab-<?= $sub['id'] ?>" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="pill" href="#panel-tabla-<?= $sub['id'] ?>">
                                                            <i class="fas fa-table mr-1"></i> Historial (Tabla)
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link btn-cargar-grafico" data-toggle="pill" data-subid="<?= $sub['id'] ?>" href="#panel-grafico-<?= $sub['id'] ?>">
                                                            <i class="fas fa-chart-area mr-1"></i> Gráficos Separados (7 Días)
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="panel-tabla-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="contenedor-tabla-sub" id="tabla-sub-<?= $sub['id'] ?>">
                                                            <div class="text-center p-4">
                                                                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                                                <p class="mt-2">Cargando mediciones de <?= htmlspecialchars($sub['nombre']) ?>...</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="panel-grafico-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="row" id="contenedor-grilla-graficos-<?= $sub['id'] ?>">
                                                            <div class="col-12 text-center p-4 contenedor-loading-grafico">
                                                                <i class="fas fa-circle-notch fa-spin fa-2x text-success"></i>
                                                                <p class="mt-2 text-muted">Construyendo paneles individuales de análisis...</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle mr-2'></i>No se encontraron sub-analizadores asociados a este analizador.</div>";
                        }
                        ?>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modalSubirCSV" tabindex="-1" role="dialog" aria-labelledby="modalSubirCSVLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSubirCSVLabel">
                    <i class="fas fa-file-csv mr-2"></i> Subir Lecturas de Medición (CSV)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCargaLecturas" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="idAnalizador" value="<?= $idAnalizador ?>">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card card-outline card-success p-3 mb-2 bg-light">
                                <label class="form-label font-weight-bold text-success" for="idSubAnalizador">
                                    <i class="fas fa-list mr-1"></i> Seleccione el Sub-Analizador:
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2bs4" name="idSubAnalizador" id="idSubAnalizador" style="width: 100%;" required>
                                    <option value="" selected disabled>-- Seleccione un Sub-Analizador --</option>
                                    <?php foreach ($subAnalizadores as $sub): ?>
                                        <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning mb-4">
                        <h5>
                            <i class="icon fas fa-exclamation-triangle"></i> Formato de Archivos
                        </h5>
                        Sube archivos
                        <code>.csv</code> válidos. Las lecturas se indexarán bajo el sub-analizador seleccionado.
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-1 mb-3">
                                <i class="fas fa-bolt mr-1"></i> Voltajes de Fase (L-N)
                            </h6>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L1-N:</label><input type="file" class="form-control" name="csv_l1n" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L2-N:</label><input type="file" class="form-control" name="csv_l2n" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L3-N:</label><input type="file" class="form-control" name="csv_l3n" accept=".csv">
                        </div>
                        <div class="col-12 mt-3">
                            <h6 class="text-success border-bottom pb-1 mb-3">
                                <i class="fas fa-network-wired mr-1"></i> Voltajes de Línea (L-L)
                            </h6>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L1-L2:</label><input type="file" class="form-control" name="csv_l1l2" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L2-L3:</label><input type="file" class="form-control" name="csv_l2l3" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo L3-L1:</label><input type="file" class="form-control" name="csv_l3l1" accept=".csv">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Procesar Archivos
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/datatables-config.js"></script>
<script src="js/dark-mode.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8/hammer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>

<script>
    $(document).ready(function () {
        // Objeto global de persistencia para indexar las instancias activas de gráficos por Sub-Analizador
        var graficosInstanciados = {};

        $('#modalSubirCSV').on('shown.bs.modal', function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: "-- Seleccione un Sub-Analizador --",
                allowClear: false,
                dropdownParent: $('#modalSubirCSV')
            });
        });

        $('#modalSubirCSV').on('hidden.bs.modal', function () {
            $('#formCargaLecturas')[0].reset();
            $('#idSubAnalizador').val('').trigger('change');
        });

        function cargarContenidoSub(tabElement) {
            var subId = tabElement.data('subid');
            var contenedor = $('#tabla-sub-' + subId);

            if (contenedor.hasClass('cargado') || !subId) return;

            $.ajax({
                url: '../sdo-includes/analizadores/sub_analizadores_cargar_mediciones.php',
                type: 'POST',
                data: {subId: subId},
                success: function (response) {
                    contenedor.html(response);
                    $('#tabla-data-sub-' + subId).DataTable({
                        ...dataTableConfig
                    });
                    contenedor.addClass('cargado');
                },
                error: function () {
                    contenedor.html('<div class="alert alert-danger">Error al cargar datos.</div>');
                }
            });
        }

        var subActivo = $('#tabSubAnalizadores a.active');
        if (subActivo.length > 0) {
            cargarContenidoSub(subActivo);
        }

        $('#tabSubAnalizadores a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            cargarContenidoSub($(e.target));
        });

        // =========================================================================
        // CONTROL ASÍNCRONO: CONSTRUCCIÓN GRÁFICA AVANZADA CON ZOOM & DESPLAZAMIENTO
        // =========================================================================
        $(document).on('click', '.btn-cargar-grafico', function (e) {
            e.preventDefault();
            var subId = $(this).data('subid');
            var grilla = $('#contenedor-grilla-graficos-' + subId);

            if (grilla.hasClass('renderizado')) {
                setTimeout(function () {
                    if (graficosInstanciados[subId]) {
                        Object.keys(graficosInstanciados[subId]).forEach(function (key) {
                            graficosInstanciados[subId][key].resize();
                            graficosInstanciados[subId][key].update();
                        });
                    }
                }, 180);
                return;
            }

            $.ajax({
                url: '../sdo-includes/analizadores/grafico_sub_analizador_7dias.php',
                type: 'POST',
                data: {subId: subId},
                dataType: 'json',
                success: function (json) {
                    if (json.status === 'error' || !json.datasets || json.datasets.length === 0) {
                        grilla.html('<div class="col-12"><div class="alert alert-warning">No hay lecturas registradas en los últimos 7 días.</div></div>');
                        return;
                    }

                    grilla.empty();
                    graficosInstanciados[subId] = {};

                    json.datasets.forEach(function (dataset, index) {
                        var canvasId = 'canvas-sub-' + subId + '-' + index;
                        var btnResetId = 'btn-reset-zoom-' + subId + '-' + index;

                        var HTMLCard = `
                            <div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-3">
                                <div class="card card-outline card-primary shadow-sm bg-white border">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <h6 class="card-title font-weight-bold text-dark mb-0">
                                            <i class="fas fa-chart-area mr-2 text-primary"></i>Parámetro: ${dataset.label}
                                        </h6>
                                        <button class="btn btn-xs btn-outline-secondary btn-reset-zoom-instancia shadow-sm font-weight-bold"
                                                id="${btnResetId}"
                                                data-subid="${subId}"
                                                data-idx="${index}"
                                                title="Restaurar escala original de 7 días">
                                            <i class="fas fa-search-minus mr-1 text-danger"></i> Reset Zoom
                                        </button>
                                    </div>
                                    <div class="card-body p-2">
                                        <div style="position: relative; height: 220px; width: 100%;">
                                            <canvas id="${canvasId}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        grilla.append(HTMLCard);

                        var ctx = document.getElementById(canvasId).getContext('2d');

                        setTimeout(function () {
                            graficosInstanciados[subId][index] = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: json.labels,
                                    datasets: [{
                                        label: dataset.label,
                                        data: dataset.data,
                                        borderColor: dataset.borderColor,
                                        backgroundColor: dataset.backgroundColor,
                                        borderWidth: 2,
                                        pointRadius: 0,
                                        hitRadius: 6,
                                        hoverRadius: 4,
                                        tension: 0.15,
                                        spanGaps: true,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {display: false},
                                        // CONFIGURACIÓN DINÁMICA DE LA API DE ZOOM INTERACTIVO
                                        zoom: {
                                            pan: {
                                                enabled: true,
                                                mode: 'x', // Restringido al eje X (línea temporal cronológica)
                                                modifierKey: 'ctrl' // Evita romper el scroll vertical nativo del SDO
                                            },
                                            zoom: {
                                                wheel: {
                                                    enabled: true, // Zoom dinámico con rueda del ratón
                                                    speed: 0.08
                                                },
                                                pinch: {
                                                    enabled: true // Soporte multi-touch para tablets en terreno hospitalario
                                                },
                                                mode: 'x'
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {color: '#eef2f5'},
                                            ticks: {
                                                color: '#495057',
                                                maxTicksLimit: 6,
                                                font: {size: 10}
                                            }
                                        },
                                        y: {
                                            grid: {color: '#eef2f5'},
                                            ticks: {
                                                color: '#495057',
                                                font: {size: 11}
                                            },
                                            title: {
                                                display: true,
                                                text: 'Voltaje (V)',
                                                color: '#495057',
                                                font: {weight: 'bold'}
                                            }
                                        }
                                    }
                                }
                            });
                        }, 150);
                    });

                    grilla.addClass('renderizado');
                },
                error: function () {
                    grilla.html('<div class="col-12"><div class="alert alert-danger">Error crítico al estructurar los paneles gráficos.</div></div>');
                }
            });
        });

        // =========================================================================
        // DELEGACIÓN DE EVENTOS: DISPARADOR INTERNO PARA RESETEAR EL ZOOM
        // =========================================================================
        $(document).on('click', '.btn-reset-zoom-instancia', function(e) {
            e.preventDefault();
            var subId = $(this).data('subid');
            var idx = $(this).data('idx');

            if (graficosInstanciados[subId] && graficosInstanciados[subId][idx]) {
                // Ejecuta la llamada interna del plugin de Chart.js para remover la escala de aumentos
                graficosInstanciados[subId][idx].resetZoom();
            }
        });

        // Formulario de envío CSV heredado
        $('#formCargaLecturas').on('submit', function (e) {
            e.preventDefault();
            if (!$('#idSubAnalizador').val()) {
                return;
            }
            var archivosSeleccionados = false;
            $(this).find('input[type="file"]').each(function () {
                if ($(this).val()) archivosSeleccionados = true;
            });
            if (!archivosSeleccionados) {
                return;
            }

            var formData = new FormData(this);
            Swal.fire({
                title: 'Procesando...',
                text: 'Espere un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '../sdo-includes/analizadores/procesar_csv_analizador.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (res) {
                    Swal.close();
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: res.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                    }
                }
            });
        });

        // Formulario AJAX para crear sub-analizadores
        $('#formCrearSubAnalizador').on('submit', function(e) {
            e.preventDefault();

            var nomSub = $.trim($('#nomSubAnalizador').val());
            if (nomSub === '') {
                Swal.fire({ icon: 'warning', title: 'Campo vacío', text: 'Por favor, ingrese un nombre válido.' });
                return;
            }

            $.ajax({
                url: '../sdo-includes/analizadores/crear_sub_analizador.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Creado!',
                            text: res.message,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $('#formCrearSubAnalizador')[0].reset();
                            $('#modalCrearSubAnalizador').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error Crítico', text: 'No se pudo registrar el parámetro en el servidor.' });
                }
            });
        });

        $('#modalCrearSubAnalizador').on('hidden.bs.modal', function () {
            $('#formCrearSubAnalizador')[0].reset();
        });
    });
</script>
</body>
</html>