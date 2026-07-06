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

    <style>
        /* Reducir tamaño de la paginación en las tablas pequeñas */
        .tabla-sub-linea_wrapper .pagination {
            font-size: 0.75rem;
            margin-bottom: 0;
        }
        .tabla-sub-linea_wrapper .page-link {
            padding: 0.25rem 0.5rem;
        }

        /* Ajustar el input del buscador */
        .tabla-sub-linea_wrapper .dataTables_filter input {
            width: 150px !important;
            font-size: 0.85rem;
            padding: 0.2rem 0.5rem;
            text-align: center;
        }

        /* Estilo base para los botones de Fases cuando no están seleccionados */
        .nav-pills .nav-link.pill-fase {
            color: #28a745 !important; /* Verde estándar */
            background-color: transparent;
            transition: all 0.2s ease-in-out;
        }

        /* Efecto Hover (pasar el mouse por encima) */
        .nav-pills .nav-link.pill-fase:hover {
            background-color: rgba(40, 167, 69, 0.1); /* Fondo verde sutil */
            color: #218838 !important;
        }

        /* Estilo CRÍTICO: Cuando la pestaña de Fases está activa/seleccionada */
        .nav-pills .nav-link.pill-fase.active {
            background-color: #28a745 !important; /* Fondo Verde */
            color: #ffffff !important; /* Texto Blanco Puro para contraste óptimo */
            border-color: #28a745;
        }
    </style>
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
                                                        <a class="nav-link active btn-cargar-tabla pill-voltaje" data-tipo="voltaje" data-subid="<?= $sub['id'] ?>" data-toggle="pill" href="#panel-tabla-voltaje-<?= $sub['id'] ?>">
                                                            <i class="fas fa-table mr-1"></i> Voltajes (Tabla)
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link btn-cargar-grafico pill-voltaje" data-tipo="voltaje" data-subid="<?= $sub['id'] ?>" data-toggle="pill" href="#panel-grafico-voltaje-<?= $sub['id'] ?>">
                                                            <i class="fas fa-chart-area mr-1"></i> Voltajes (Gráficos)
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link btn-cargar-tabla pill-fase" data-tipo="fase" data-subid="<?= $sub['id'] ?>" data-toggle="pill" href="#panel-tabla-fase-<?= $sub['id'] ?>">
                                                            <i class="fas fa-bolt mr-1"></i> Fases (Tabla)
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link btn-cargar-grafico pill-fase" data-tipo="fase" data-subid="<?= $sub['id'] ?>" data-toggle="pill" href="#panel-grafico-fase-<?= $sub['id'] ?>">
                                                            <i class="fas fa-chart-line mr-1"></i> Fases (Gráficos)
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="panel-tabla-voltaje-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="contenedor-tabla-sub" id="tabla-voltaje-<?= $sub['id'] ?>">
                                                            <div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Cargando voltajes...</p></div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="panel-grafico-voltaje-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="row" id="grafico-voltaje-<?= $sub['id'] ?>">
                                                            <div class="col-12 text-center p-4"><i class="fas fa-circle-notch fa-spin fa-2x text-primary"></i><p class="mt-2">Construyendo gráficos de voltaje...</p></div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="panel-tabla-fase-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="contenedor-tabla-sub" id="tabla-fase-<?= $sub['id'] ?>">
                                                            <div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i><p class="mt-2">Cargando fases...</p></div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="panel-grafico-fase-<?= $sub['id'] ?>" role="tabpanel">
                                                        <div class="row" id="grafico-fase-<?= $sub['id'] ?>">
                                                            <div class="col-12 text-center p-4"><i class="fas fa-circle-notch fa-spin fa-2x text-warning"></i><p class="mt-2">Construyendo gráficos de fases...</p></div>
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
                        <div class="col-12 mt-3">
                            <h6 class="text-warning border-bottom pb-1 mb-3">
                                <i class="fas fa-charging-station mr-1"></i> Mediciones por Fase (F1 - F2 - F3)
                            </h6>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo Fase 1:</label>
                            <input type="file" class="form-control" name="csv_fase1" id="csv_fase1" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo Fase 2:</label>
                            <input type="file" class="form-control" name="csv_fase2" id="csv_fase2" accept=".csv">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label font-weight-bold">Archivo Fase 3:</label>
                            <input type="file" class="form-control" name="csv_fase3" id="csv_fase3" accept=".csv">
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

        // Lógica actualizada para las tablas
        function cargarContenidoSub(subId, tipo) {
            if(!tipo) tipo = 'voltaje'; // Por defecto voltaje
            var contenedor = $('#tabla-' + tipo + '-' + subId);

            if (contenedor.hasClass('cargado') || !subId) return;

            $.ajax({
                url: '../sdo-includes/analizadores/sub_analizadores_cargar_mediciones.php',
                type: 'POST',
                data: {subId: subId, tipo: tipo},
                success: function (response) {
                    contenedor.html(response);

                    // Inicialización de DataTables optimizada para columnas estrechas
                    contenedor.find('.tabla-data-dinamica').DataTable({
                        ...dataTableConfig,
                        // Sobrescribimos el DOM para apilar y centrar los controles
                        dom: "<'row mb-2'<'col-12 d-flex justify-content-center'f>>" +
                            "<'row'<'col-12'tr>>" +
                            "<'row mt-2'<'col-12 text-center text-xs text-muted mb-1'i><'col-12 d-flex justify-content-center'p>>",
                        pagingType: "simple_numbers",
                        language: {
                            ...dataTableConfig.language,
                            info: "_START_ - _END_ de _TOTAL_ regs", // Texto de info abreviado
                            paginate: {
                                previous: "◄", // Flechas en lugar de "Anterior/Siguiente"
                                next: "►"
                            },
                            search: "", // Quitamos la etiqueta "Buscar:" para ahorrar espacio
                            searchPlaceholder: "Buscar..."
                        }
                    });

                    contenedor.addClass('cargado');
                },
                error: function () {
                    contenedor.html('<div class="alert alert-danger">Error al cargar datos.</div>');
                }
            });
        }

        // Cargar primera pestaña al iniciar
        var subActivo = $('#tabSubAnalizadores a.active');
        if (subActivo.length > 0) {
            cargarContenidoSub(subActivo.data('subid'), 'voltaje');
        }

        // Evento cambio de pestaña principal (Cabeceras BME1, etc)
        $('#tabSubAnalizadores a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var subId = $(e.target).data('subid');
            // Al cambiar, cargamos la tabla de voltaje por defecto
            cargarContenidoSub(subId, 'voltaje');
        });

        // Evento cambio de sub-pestañas (Las Pills internas)
        $(document).on('click', '.btn-cargar-tabla', function (e) {
            var subId = $(this).data('subid');
            var tipo = $(this).data('tipo');
            cargarContenidoSub(subId, tipo);
        });

        // =========================================================================
        // CONTROL ASÍNCRONO: CONSTRUCCIÓN GRÁFICA
        // =========================================================================
        $(document).on('click', '.btn-cargar-grafico', function (e) {
            e.preventDefault();
            var subId = $(this).data('subid');
            var tipo = $(this).data('tipo');
            var grilla = $('#grafico-' + tipo + '-' + subId);
            var identificadorInstancia = subId + '_' + tipo; // Separar instancias por tipo

            if (grilla.hasClass('renderizado')) {
                setTimeout(function () {
                    if (graficosInstanciados[identificadorInstancia]) {
                        Object.keys(graficosInstanciados[identificadorInstancia]).forEach(function (key) {
                            graficosInstanciados[identificadorInstancia][key].resize();
                            graficosInstanciados[identificadorInstancia][key].update();
                        });
                    }
                }, 180);
                return;
            }

            $.ajax({
                url: '../sdo-includes/analizadores/grafico_sub_analizador_7dias.php',
                type: 'POST',
                data: {subId: subId, tipo: tipo},
                dataType: 'json',
                success: function (json) {
                    if (json.status === 'error' || !json.datasets || json.datasets.length === 0) {
                        grilla.html('<div class="col-12"><div class="alert alert-warning">No hay lecturas registradas en los últimos 7 días.</div></div>');
                        return;
                    }

                    grilla.empty();
                    graficosInstanciados[identificadorInstancia] = {};

                    json.datasets.forEach(function (dataset, index) {
                        var canvasId = 'canvas-sub-' + subId + '-' + tipo + '-' + index;
                        var btnResetId = 'btn-reset-zoom-' + subId + '-' + tipo + '-' + index;

                        var HTMLCard = `
                            <div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-3">
                                <div class="card card-outline card-primary shadow-sm bg-white border">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                        <h6 class="card-title font-weight-bold text-dark mb-0">
                                            <i class="fas fa-chart-area mr-2 ${tipo === 'fase' ? 'text-warning' : 'text-primary'}"></i>Parámetro: ${dataset.label}
                                        </h6>
                                        <button class="btn btn-xs btn-outline-secondary btn-reset-zoom-instancia shadow-sm font-weight-bold"
                                                data-instancia="${identificadorInstancia}"
                                                data-idx="${index}"
                                                title="Restaurar escala">
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
                            graficosInstanciados[identificadorInstancia][index] = new Chart(ctx, {
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
                                        zoom: {
                                            pan: { enabled: true, mode: 'x', modifierKey: 'ctrl' },
                                            zoom: { wheel: { enabled: true, speed: 0.08 }, pinch: { enabled: true }, mode: 'x' }
                                        }
                                    },
                                    scales: {
                                        x: { grid: {color: '#eef2f5'}, ticks: { color: '#495057', maxTicksLimit: 6, font: {size: 10} } },
                                        y: {
                                            grid: {color: '#eef2f5'},
                                            ticks: { color: '#495057', font: {size: 11} },
                                            title: { display: true, text: json.ejeY, color: '#495057', font: {weight: 'bold'} }
                                        }
                                    }
                                }
                            });
                        }, 150);
                    });
                    grilla.addClass('renderizado');
                }
            });
        });

        $(document).on('click', '.btn-reset-zoom-instancia', function(e) {
            e.preventDefault();
            var instancia = $(this).data('instancia');
            var idx = $(this).data('idx');
            if (graficosInstanciados[instancia] && graficosInstanciados[instancia][idx]) {
                graficosInstanciados[instancia][idx].resetZoom();
            }
        });

        // Formulario de envío CSV heredado
        // Formulario de envío CSV restaurado para producción
        $('#formCargaLecturas').on('submit', function (e) {
            e.preventDefault();

            if (!$('#idSubAnalizador').val()) {
                Swal.fire({ icon: 'warning', title: 'Atención', text: 'Por favor, seleccione un Sub-Analizador.' });
                return;
            }

            var archivosSeleccionados = false;
            $(this).find('input[type="file"]').each(function () {
                if ($(this).val()) archivosSeleccionados = true;
            });

            if (!archivosSeleccionados) {
                Swal.fire({ icon: 'warning', title: 'Sin archivos', text: 'Debe cargar al menos un archivo CSV.' });
                return;
            }

            // Aseguramos el multipart/form-data nativo para empaquetar el $_FILES
            var formData = new FormData(this);

            Swal.fire({
                title: 'Procesando...',
                text: 'Analizando estructuras e indexando mediciones en la BD.',
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
                },
                error: function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Crítico',
                        text: 'Error de comunicación con el controlador de base de datos.'
                    });
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