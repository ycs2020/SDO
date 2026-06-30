<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'] ?? null;
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = $rolUsuario ? strtolower($rolUsuario) : null;
$nomUsuario = $_SESSION['nomUsuario'] ?? null;

date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analizadores de Red</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/user-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-8 col-lg-7 col-md-12 col-12 mb-3">
                        <div class="card bg-gradient-primary p-3 text-white h-100 d-flex justify-content-center mb-0">
                            <h5 class="mb-0">Listado de Analizadores de Red</h5>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-4 mb-3">
                        <button type="button" class="btn btn-success btn-block h-100 d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modalAgregarAnalizador">
                            <i class="fa fa-plus-circle mr-2"></i> Agregar Analizador
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-4 mb-3">
                        <a href="index.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center">
                            <i class="fa fa-chevron-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <?php
                $queryPisos = "SELECT pisoAnalizador 
                       FROM analizadores_red 
                       GROUP BY pisoAnalizador
                       ORDER BY pisoAnalizador ASC";

                if ($stmtPisos = $conexion->prepare($queryPisos)) {
                    $stmtPisos->execute();
                    $resultPisos = $stmtPisos->get_result();

                    if ($resultPisos->num_rows > 0) {
                        $pisos = [];
                        while ($fila = $resultPisos->fetch_assoc()) {
                            $pisos[] = $fila['pisoAnalizador'];
                        }

                        $primerPiso = $pisos[0];
                        ?>
                        <div class="card card-primary card-outline card-tabs shadow-sm">
                            <div class="card-header p-0 pt-1 border-bottom-0 bg-gradient-primary">
                                <ul class="nav nav-tabs" id="tabPisosAnalizadores" role="tablist">
                                    <?php
                                    foreach ($pisos as $pisoNum) {
                                        $activeClass = ($pisoNum == $primerPiso) ? 'active' : '';
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link tab-piso-ajax <?= $activeClass ?>"
                                                id="tab-piso<?= $pisoNum ?>"
                                                data-toggle="tab"
                                                data-piso="<?= $pisoNum ?>"
                                                href="#content-listPiso<?= $pisoNum ?>"
                                                role="tab">
                                                <i class="fas fa-list-alt mr-1"></i> Piso <?= $pisoNum ?>
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
                                    foreach ($pisos as $pisoNum) {
                                        $activeClass = ($pisoNum == $primerPiso) ? ' show active' : '';
                                        ?>
                                        <div class="tab-pane fade <?= $activeClass ?>" id="content-listPiso<?= $pisoNum ?>" role="tabpanel">
                                            <div class="contenedor-tabla" id="tabla-piso-<?= $pisoNum ?>">
                                                <div class="text-center p-4">
                                                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                                    <p class="mt-2">Cargando analizadores del piso <?= $pisoNum ?>...</p>
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
                        echo "<div class='alert alert-info'>No se encontraron analizadores de red registrados.</div>";
                    }
                    $stmtPisos->close();
                } else {
                    echo "<div class='alert alert-danger'>Error al preparar la consulta a la base de datos.</div>";
                }
                ?>
            </div>
        </section>
    </div>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/datatables-config.js"></script>
<script src="js/dark-mode.js"></script>

<script>
    $(document).ready(function() {
        function cargarContenidoPiso(tabElement) {
            var piso = tabElement.data('piso');
            var contenedor = $('#tabla-piso-' + piso);

            if (contenedor.hasClass('cargado')) {
                return;
            }

            $.ajax({
                url: '../sdo-includes/analizadores/analizadores_cargar_listado.php',
                type: 'POST',
                data: { piso: piso },
                success: function(response) {
                    contenedor.html(response);

                    $('#tabla-data-piso-' + piso).DataTable({
                        ...dataTableConfig
                    });

                    contenedor.addClass('cargado');
                },
                error: function() {
                    contenedor.html('<div class="alert alert-danger">Error al cargar los datos del piso ' + piso + '.</div>');
                }
            });
        }

        var tabActivoAlInicio = $('#tabPisosAnalizadores a.active');
        if (tabActivoAlInicio.length > 0) {
            cargarContenidoPiso(tabActivoAlInicio);
        }

        $('#tabPisosAnalizadores a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var tabSeleccionado = $(e.target);
            cargarContenidoPiso(tabSeleccionado);
        });

        $('.tab-content').on('click', '.btVerAnalizador', function() {
            var idAnalizador = $(this).data('id');
            var urlDestino = 'analizadores_red_detalle.php';

            var $form = $('<form>', {
                action: urlDestino,
                method: 'POST'
            }).append($('<input>', {
                type: 'hidden',
                name: 'idAnalizador',
                value: idAnalizador
            }));

            $form.appendTo('body').submit();
        });

        // Guardar el analizador mediante AJAX
        $('#formNuevoAnalizador').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '../sdo-includes/analizadores/analizadores_guardar_nuevo.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#btnGuardarAnalizador').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
                },
                success: function(response) {
                    $('#btnGuardarAnalizador').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Registrar Equipo');

                    if (response.status === 'success') {
                        $('#modalAgregarAnalizador').modal('hide');
                        $('#formNuevoAnalizador')[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Creado!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    $('#btnGuardarAnalizador').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Registrar Equipo');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Comunicación',
                        text: 'No se pudo procesar la solicitud en el servidor.'
                    });
                }
            });
        });
    });
</script>

<div class="modal fade" id="modalAgregarAnalizador" role="dialog" aria-labelledby="modalAgregarAnalizadorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAgregarAnalizadorLabel"><i class="fas fa-microchip mr-2"></i> Registrar Nuevo Analizador de Red</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoAnalizador">
                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="modalNomAnalizador" class="form-label font-weight-bold">Nombre del Analizador *</label>
                        <input type="text" class="form-control" id="modalNomAnalizador" name="nomAnalizador" placeholder="Ej: Analizador General Centrifuga" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="modalPisoAnalizador" class="form-label font-weight-bold">Piso *</label>
                                <input type="number" class="form-control" id="modalPisoAnalizador" name="pisoAnalizador" placeholder="Ej: 1" min="0" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="modalSectorAnalizador" class="form-label font-weight-bold">Sector *</label>
                                <input type="text" class="form-control" id="modalSectorAnalizador" name="sectorAnalizador" placeholder="Ej: Sector A / Sala Eléctrica" required>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnGuardarAnalizador">
                        <i class="fas fa-save mr-1"></i> Registrar Equipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>