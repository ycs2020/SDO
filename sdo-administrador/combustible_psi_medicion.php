<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mediciones PSI - Combustible</title>
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

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3">
                        <div class="card bg-gradient-primary p-3 text-white h-100 d-flex justify-content-center mb-0">
                            <h5 class="mb-0"><b>Mediciones PSI - Combustible</b>
                            </h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="index.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center">
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

                        <div class="card card-primary card-outline card-tabs shadow-sm">
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
                    </div>

                </div>
            </div>
        </section>
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


</body>
</html>