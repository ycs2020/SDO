<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario      = $_SESSION['idUsuario'];
$userUsuario    = $_SESSION['userUsuario'];
$rolUsuario     = $_SESSION['rolUsuario'];
$rolUsuario2    = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];

date_default_timezone_set('America/Santiago');

// Variables por defecto por si fallase el POST
$solicitanteTG = $detalleTG = $fechaTG = $estadoTG = $turnoTG = $tecnico1 = $folioTG = "";
$fechaProgra = $ticketAsociado = $detalleTurnoCamara = $detalleTurnoLLE = $detalleTurnoCA = "";
$detalleTurnoRonda = $detalleOtros = $tipodeTrabajo = $descripcion = "";
$departamentos = 0;

if (isset($_POST['idTG']) || isset($_GET['idTG'])) {
    $idTG = (int)($_POST['idTG'] ?? $_GET['idTG']);

    $sql = "SELECT * FROM trabajos_general WHERE idTG = ?";
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $idTG);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = $res->fetch_assoc()) {
            $solicitanteTG      = $row['solicitanteTG'];
            $detalleTG          = $row['detalleTG'];
            $fechaTG            = $row['fechaTG'];
            $estadoTG           = $row['estadoTG'];
            $turnoTG            = $row['turnoTG'];
            $tecnico1           = $row['tecnico1'];
            $folioTG            = $row['folioTG'];
            $fechaProgra        = $row['fechaProgramacion'];
            $ticketAsociado     = $row['ticketAsociado'];
            $detalleTurnoCamara = $row['detalleTurnoCamara'];
            $detalleTurnoLLE    = $row['detalleTurnoLLE'];
            $detalleTurnoCA     = $row['detalleTurnoCA'];
            $detalleTurnoRonda  = $row['detalleTurnoRondas'];
            $detalleOtros       = $row['detalleOtros'];
            $tipodeTrabajo      = $row['tipoTrabajo'];
            $departamentos      = (int)$row['departamento'];
            $descripcion        = $row['descripcion'];
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Trabajo General - <?php echo htmlspecialchars($folioTG); ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <style>
        .select2-container .select2-selection--single { height: calc(2.25rem + 2px); border: 1px solid #ced4da; border-radius: 0.25rem; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h4 class="font-weight-bold text-dark m-0"><i class="fas fa-edit mr-2 text-primary"></i>Modificar Trabajo General: <?php echo htmlspecialchars($folioTG); ?></h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="detalle_ordenes_trabajo.php?idTG=<?php echo $idTG; ?>" class="btn btn-sm btn-danger px-3 shadow-sm mr-2"><i class="fa fa-chevron-left mr-1"></i> Volver Atrás</a>
                        <button type="button" class="btn btn-sm btn-info px-3 shadow-sm" data-toggle="modal" data-target="#eliminar"><i class="fas fa-folder-open mr-1"></i> Gestionar Archivos</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <form id="formModificarTG" enctype="multipart/form-data">

                    <div class="card card-light border shadow-sm mb-4">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 font-weight-bold text-secondary">
                                <i class="fas fa-info-circle mr-1"></i> Información General del Trabajo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="font-weight-bold">Descripción: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-pencil-alt"></i></span></div>
                                        <input type="text" required class="form-control" name="descripcion" placeholder="Descripción del Trabajo" value="<?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Fecha de Creación:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                                        <input type="text" class="form-control" readonly value="<?php echo $fechaTG; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Fecha de Programación: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-check"></i></span></div>
                                        <input type="date" class="form-control" name="fechaProgramado" value="<?php echo $fechaProgra; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Ticket Asociado:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-ticket-alt"></i></span></div>
                                        <input type="text" class="form-control" name="ticketAsociado" value="<?php echo htmlspecialchars($ticketAsociado); ?>" placeholder="Solo números">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Tipo de Trabajo:</label>
                                    <select name="tipoTrabajo" class="form-control select2" id="tipoTrabajo">
                                        <option value="" disabled <?php echo empty($tipodeTrabajo) ? 'selected' : ''; ?>>-- Seleccione --</option>
                                        <?php
                                        $opcionesTrabajo = [
                                            'MPE' => 'MPE - Mantenimiento Preventivo Externo',
                                            'MRE' => 'MRE - Mantenimiento Reparativo Externo',
                                            'MPI' => 'MPI - Mantenimiento Preventivo Interno',
                                            'MRI' => 'MRI - Mantenimiento Reparativo Interno',
                                            'OM'  => 'OM - Obras Menores',
                                            'IS'  => 'IS - Inspección/Supervisión'
                                        ];
                                        foreach ($opcionesTrabajo as $key => $value) {
                                            $selected = ($tipodeTrabajo == $key) ? 'selected' : '';
                                            echo "<option value='$key' $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Turno: <span class="text-danger">*</span></label>
                                    <select name="turno" id="turno" class="form-control select2" required>
                                        <option value="Personal Diurno" <?php echo ($turnoTG == 'Personal Diurno') ? 'selected' : ''; ?>>Personal Diurno</option>
                                        <option value="Personal Nocturno" <?php echo ($turnoTG == 'Personal Nocturno') ? 'selected' : ''; ?>>Personal Nocturno</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-light border shadow-sm mb-4">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 font-weight-bold text-secondary">
                                <i class="fas fa-users mr-1"></i> Personal y Ubicación
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Persona Solicitante: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                        <input type="text" class="form-control" required name="personaSolicitante" value="<?php echo htmlspecialchars($solicitanteTG, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Funcionario Asignado Actual:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-shield"></i></span></div>
                                        <input type="text" class="form-control font-weight-bold text-primary" readonly value="<?php echo htmlspecialchars($tecnico1, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Cambiar Departamento Responsable:</label>
                                    <select name="departamentoSolicitante" id="departamentoSolicitante" class="form-control select2">
                                        <option value="" selected disabled>-- Mantener departamento actual --</option>
                                        <?php
                                        $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento NOT IN (9, 11, 13) ORDER BY nomDepartamento ASC";
                                        $resDepa = mysqli_query($conexion, $sqlDepa);
                                        while ($rowDepa = $resDepa->fetch_assoc()) {
                                            $selected = ($rowDepa['idDepartamento'] == $departamentos) ? 'selected' : '';
                                            echo "<option value='".$rowDepa['idDepartamento']."' $selected>".$rowDepa['nomDepartamento']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Personal de Reemplazo / Nuevo Técnico:</label>
                                    <select name="tecnico1" id="tecnico1" class="form-control select2">
                                        <option value="<?php echo htmlspecialchars($tecnico1, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tecnico1, ENT_QUOTES, 'UTF-8'); ?> (Actual)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-light border shadow-sm mb-4">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 font-weight-bold text-secondary">
                                <i class="fas fa-file-alt mr-1"></i> Detalle de los Trabajos a Realizar
                            </h6>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="departamentosecreto" name="departamentosecreto" value="<?php echo $departamentos; ?>">

                            <div id="otcontrol" style="<?php echo ($departamentos === 1) ? 'display:block;' : 'display:none;'; ?>">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en BMS:</label>
                                        <textarea class="form-control" name="detalleTurnoBMS" id="detalleTurnoBMS" rows="3"><?php echo htmlspecialchars($detalleTG); ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en CCTV:</label>
                                        <textarea class="form-control" name="detalleTurnoCCTV" id="detalleTurnoCCTV" rows="3"><?php echo htmlspecialchars($detalleTurnoCamara); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en LLE:</label>
                                        <textarea class="form-control" name="detalleTurnoLLE" id="detalleTurnoLLE" rows="3"><?php echo htmlspecialchars($detalleTurnoLLE); ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en Control de Acceso:</label>
                                        <textarea class="form-control" name="detalleTurnoCA" id="detalleTurnoCA" rows="3"><?php echo htmlspecialchars($detalleTurnoCA); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en Rondas:</label>
                                        <textarea class="form-control" name="detalleTurnoRondas" id="detalleTurnoRondas" rows="3"><?php echo htmlspecialchars($detalleTurnoRonda); ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-muted">Otros tipos de trabajos:</label>
                                        <textarea class="form-control" name="detalleOtros" id="detalleOtros" rows="3"><?php echo htmlspecialchars($detalleOtros); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="otespecial" style="<?php echo ($departamentos !== 1 && $departamentos > 0) ? 'display:block;' : 'display:none;'; ?>">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="font-weight-bold text-success">Descripción Detallada del Trabajo:</label>
                                        <textarea class="form-control" name="detalleTurnoNoControl" id="detalleTurnoNoControl" rows="5" placeholder="Escriba aquí las actividades detalladas..."><?php echo htmlspecialchars($detalleTG); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                    <input type="hidden" name="folioTG" value="<?php echo htmlspecialchars($folioTG); ?>">
                    <input type="hidden" name="idTecnico1" value="<?php echo htmlspecialchars($tecnico1, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="row mb-5">
                        <div class="col-md-6 mb-2">
                            <button type="submit" id="btnGuardarModi" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Guardar Modificaciones
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="detalle_ordenes_trabajo.php?idTG=<?php echo $idTG; ?>" class="btn btn-warning btn-block font-weight-bold py-2 text-white shadow-sm"><i class="fas fa-undo mr-1"></i> Cancelar / Volver</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <div class="modal fade" id="eliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title font-weight-bold text-white" id="modalEliminarLabel"><i class="fas fa-file-excel mr-2"></i> Documentación Adjunta de la OT</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted">Lista de archivos asociados. Al presionar el botón de basurero se eliminará de forma permanente.</p>
                    <table class="table table-bordered table-striped table-hover table-sm w-100" id="tabla-equipo-falla">
                        <thead class="bg-secondary text-white">
                        <tr>
                            <th scope="col" class="text-center">Nombre del Archivo</th>
                            <th scope="col" class="text-center">Tipo</th>
                            <th scope="col" class="text-center">¿Es avance?</th>
                            <th scope="col" class="text-center">Fecha Subida</th>
                            <th scope="col" class="text-center">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlArchivo = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = ?";
                        if ($stmtA = mysqli_prepare($conexion, $sqlArchivo)) {
                            mysqli_stmt_bind_param($stmtA, "i", $idTG);
                            mysqli_stmt_execute($stmtA);
                            $resArchivo = mysqli_stmt_get_result($stmtA);
                            while ($rowArchivo = $resArchivo->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="align-middle pl-2"><?php echo htmlspecialchars($rowArchivo['nomArchivo']); ?></td>
                                    <td class="text-center align-middle"><span class="badge badge-light border"><?php echo strtoupper($rowArchivo['extArchivo']); ?></span></td>
                                    <td class="text-center align-middle"><?php echo ($rowArchivo['trabajoTerminado'] == 0) ? '<span class="badge badge-secondary">NO</span>' : '<span class="badge badge-success">SI</span>'; ?></td>
                                    <td class="text-center align-middle"><?php echo $rowArchivo['fechaSubida']; ?></td>
                                    <td class="text-center align-middle">
                                        <form action="../sdo-funciones/eliminar-archivo-trabajo-general.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este archivo?');">
                                            <input type="hidden" name="idArchivo" value="<?php echo $rowArchivo['idArchivo']; ?>">
                                            <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                            <button type="submit" class="btn btn-danger btn-xs px-2"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }
                            mysqli_stmt_close($stmtA);
                        } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cerrar Ventana</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer text-center">
        <strong>Copyright &copy; 2022 - <?php echo date('Y'); ?>. SDO Plataforma de Operaciones.</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="js/dark-mode.js"></script>

<script>
    $(document).ready(function () {

        // 1. Inicializar Select2 con el tema de Bootstrap 4
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // 2. Inicializar DataTable del Modal de Archivos
        $('#tabla-equipo-falla').DataTable({
            "paging": true, "lengthChange": false, "searching": false, "ordering": false, "info": true, "responsive": true,
            "language": {
                "zeroRecords": "No se han encontrado registros.",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "No existen registros.",
                "paginate": { "previous": "Anterior", "next": "Siguiente" }
            }
        });

        // 3. Control dinámico del label para input file
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName || 'Elegir archivo...');
        });

        /**
         * Función unificada para cargar técnicos vía AJAX de forma dinámica
         * @param {number} idDepa - ID del Departamento seleccionado
         * @param {string|null} tecnicoSeleccionado - Nombre del técnico que debe quedar marcado por defecto (opcional)
         */
        function cargarTecnicosDinamicos(idDepa, tecnicoSeleccionado = null) {
            var $selectPersonal = $('#tecnico1');
            var idDepaInt = parseInt(idDepa);

            if (!idDepa || isNaN(idDepaInt)) return;

            // Actualizar el input oculto con el ID del departamento en edición
            $('#departamentosecreto').val(idDepaInt);

            // Conmutación visual de bloques según el departamento
            if (idDepaInt === 1) {
                $('#otcontrol').fadeIn('fast');
                $('#otespecial').hide();
            } else {
                $('#otespecial').fadeIn('fast');
                $('#otcontrol').hide();
            }

            // Petición AJAX segura a tu archivo 'personal_buscar_nombres.php'
            $selectPersonal.html('<option value="">Cargando técnicos...</option>').prop('disabled', true);

            $.ajax({
                url: '../sdo-includes/ordenes_trabajos/personal_buscar_nombres.php',
                type: 'POST',
                data: { idDepartamento: idDepaInt },
                dataType: 'json',
                success: function (response) {
                    $selectPersonal.html('<option value="" disabled>Seleccione un técnico...</option>').prop('disabled', false);

                    if (response.status === 'success' && response.data.length > 0) {
                        $.each(response.data, function (index, usuario) {
                            // Si coincide con el técnico guardado, le añadimos el atributo 'selected'
                            var isSelected = (tecnicoSeleccionado && tecnicoSeleccionado.trim() === usuario.nombre.trim()) ? 'selected' : '';
                            $selectPersonal.append('<option value="' + usuario.nombre + '" ' + isSelected + '>' + usuario.nombre + '</option>');
                        });
                    } else {
                        $selectPersonal.html('<option value="">No hay personal activo en este departamento</option>');
                    }
                    // Refrescar Select2 para que renderice los nuevos options cargados
                    $selectPersonal.trigger('change.select2');
                },
                error: function () {
                    $selectPersonal.html('<option value="">Error al cargar datos</option>').prop('disabled', false).trigger('change.select2');
                }
            });
        }

        // 4. DISPARADOR AL CARGAR LA PÁGINA (Pre-cargar los técnicos del departamento actual de la OT)
        var depaInicial = parseInt($('#departamentosecreto').val());
        var tecnicoInicial = <?php echo json_encode($tecnico1); ?>.trim();

        if (depaInicial > 0) {
            cargarTecnicosDinamicos(depaInicial, tecnicoInicial);
        }

        // 5. EVENTO CHANGE (Cuando el usuario cambia manualmente el departamento en el formulario)
        $('#departamentoSolicitante').on('change', function () {
            var idDepa = $(this).val();
            // Al cambiar de departamento manualmente, pasamos null para que el usuario elija desde cero
            cargarTecnicosDinamicos(idDepa, null);
        });

        // 6. CAPTURAR EL ENVÍO DEL FORMULARIO PRINCIPAL VÍA AJAX (SWEETALERT2)
        $('#formModificarTG').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '../sdo-includes/ordenes_trabajos/ot_funcion_modificar.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#btnGuardarModi').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando cambios...');
                },
                success: function (response) {
                    $('#btnGuardarModi').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Modificaciones');

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Modificación Exitosa!',
                            text: response.message,
                            timer: 2500,
                            showConfirmButton: false
                        }).then(function() {
                            // Redirección limpia al detalle de la orden modificada
                            window.location.href = 'detalle_ordenes_trabajo.php?idTG=<?php echo $idTG; ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se pudo guardar',
                            text: response.message || 'Error desconocido en el servidor.'
                        });
                    }
                },
                error: function (xhr) {
                    $('#btnGuardarModi').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Modificaciones');
                    console.error("Respuesta cruda del servidor:", xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Red',
                        text: 'Ocurrió un problema de comunicación con el servidor.'
                    });
                }
            });
        });
    });
</script>
</body>
</html>