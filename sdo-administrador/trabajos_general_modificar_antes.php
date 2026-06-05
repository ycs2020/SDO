<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];

date_default_timezone_set('America/Santiago');

if (isset($_POST['idTG'])) {
    $idTG = $_POST['idTG'];
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $estadoTG = $row['estadoTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $folioTG = $row['folioTG'];
        $fechaProgra = $row['fechaProgramacion'];
        $ticketAsociado = $row['ticketAsociado'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $tipodeTrabajo = $row['tipoTrabajo'];
        $departamentos = $row['departamento'];
        $descripcion = $row['descripcion'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Trabajo General - <?php echo $folioTG ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            recargarListTecnicos();

            $('#departamentoSolicitante').change(function () {
                recargarListTecnicos();
            });

            function recargarListTecnicos() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosYJefes.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {
                        if (r == 0 || r == "error" || r == "ERROR" || r == "Error") {
                            var msg = (r == 0) ? "No Existen Tecnicos en este Departamento." : "Error al Obtener Datos.";
                            var cadena = '<div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span><span class="input-group-text w-100">'+msg+'</span></div>';
                            $('#htmlSelect').html(cadena);
                            $('#Guardar').attr("disabled", true);
                        } else {
                            $('#htmlSelect').html(r);
                            $('#Guardar').removeAttr("disabled");
                        }
                    }
                });
            }
        });

        function visible(valor) {
            document.getElementById('departamentosecreto').value = valor;
            var otcontrolDiv = document.getElementById("otcontrol");
            var otespecialDiv = document.getElementById("otespecial");

            verificarDIVs(parseInt(valor));

            if (valor == 1) {
                otcontrolDiv.style.display = "block";
                otespecialDiv.style.display = "none";
            } else {
                otcontrolDiv.style.display = "none";
                otespecialDiv.style.display = "block";
            }
        }

        function deshabilitarDiv(divId) {
            var miDiv = document.getElementById(divId);
            for (var i = 0; i < miDiv.children.length; i++) {
                var elemento = miDiv.children[i];
                if (["INPUT", "BUTTON", "SELECT", "TEXTAREA"].includes(elemento.tagName)) {
                    elemento.disabled = true;
                }
            }
            miDiv.disabled = true;
        }

        function habilitarDiv(divId) {
            var miDiv = document.getElementById(divId);
            for (var i = 0; i < miDiv.children.length; i++) {
                var elemento = miDiv.children[i];
                if (["INPUT", "BUTTON", "SELECT", "TEXTAREA"].includes(elemento.tagName)) {
                    elemento.disabled = false;
                }
            }
            miDiv.disabled = false;
        }

        function verificarDIVs(id) {
            if (id === 1) {
                habilitarDiv('otcontrol');
                deshabilitarDiv('otespecial');
            } else {
                habilitarDiv('otespecial');
                deshabilitarDiv('otcontrol');
            }
        }
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed" onload="verificarDIVs(<?php echo (int)$departamentos; ?>)">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <?php if (isset($_GET['exitoIngresoTG'])): ?>
            <script>Swal.fire('¡Éxito!', 'Orden de Trabajo ingresada correctamente!', 'success');</script>
        <?php endif; ?>
        <?php if (isset($_GET['error1TG'])): ?>
            <script>Swal.fire('¡Error!', 'La subida del archivo falló. Por favor reintente.', 'error');</script>
        <?php endif; ?>
        <?php if (isset($_GET['error2TG'])): ?>
            <script>Swal.fire({ icon: 'error', title: 'Error', text: '¡El archivo no se encuentra permitido!' });</script>
        <?php endif; ?>
        <?php if (isset($_GET['error3TG'])): ?>
            <script>Swal.fire({ icon: 'error', title: 'Error', text: 'No se ha subido un archivo. Por favor suba un archivo adjunto.' });</script>
        <?php endif; ?>
        <?php if (isset($_GET['errorTecnicoTG'])): ?>
            <script>Swal.fire({ icon: 'error', title: 'Error', text: '¡No se puede colocar el técnico dos veces!' });</script>
        <?php endif; ?>
        <?php if (isset($_GET['modificacionok'])): ?>
            <script>Swal.fire({ icon: 'success', title: 'Éxito', text: 'Se ha modificado correctamente' });</script>
        <?php endif; ?>

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h4 class="font-weight-bold text-dark m-0"><i class="fas fa-edit mr-2 text-primary"></i>Modificar Trabajo General: <?php echo $folioTG ?></h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="detalle_ordenes_trabajo.php" class="btn btn-sm btn-danger px-3 shadow-sm mr-2"><i class="fa fa-chevron-left mr-1"></i> Volver Atrás</a>
                        <button type="button" class="btn btn-sm btn-info px-3 shadow-sm" data-toggle="modal" data-target="#eliminar"><i class="fas fa-folder-open mr-1"></i> Gestionar Archivos</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <form action="../sdo-funciones/modificar-trabajo-general-admin.php" method="POST" enctype="multipart/form-data">

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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                        </div>
                                        <input type="text" required class="form-control" name="descripcion" placeholder="Descripción del Trabajo" value="<?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Fecha de Creación:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" readonly name="fechaOrden" value="<?php echo $fechaTG ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Fecha de Programación: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                        </div>
                                        <input type="date" class="form-control" name="fechaProgramado" value="<?php echo $fechaProgra ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Ticket Asociado:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="ticketAsociado" value="<?php echo $ticketAsociado ?>" placeholder="Solo números">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Tipo de Trabajo:</label>
                                    <select name="tipoTrabajo" class="form-control" id="tipoTrabajo">
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
                                    <select name="turno" id="turno" class="form-control" required>
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" required name="personaSolicitante" value="<?php echo htmlspecialchars($solicitanteTG, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Funcionario Asignado Actual:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                        </div>
                                        <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($tecnico1, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Cambiar Departamento Responsable: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <select name="departamentoSolicitante" id="departamentoSolicitante" onchange="visible(this.value)" class="form-control">
                                            <option value="" selected disabled>Seleccione si desea cambiar...</option>
                                            <?php
                                            $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento NOT IN (9, 11, 13) ORDER BY nomDepartamento ASC";
                                            $resDepa = mysqli_query($conexion, $sqlDepa);
                                            while ($rowDepa = $resDepa->fetch_assoc()) {
                                                echo "<option value='".$rowDepa['idDepartamento']."'>".$rowDepa['nomDepartamento']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Personal de Reemplazo / Nuevo Técnico: <span class="text-danger">*</span></label>
                                    <div id="htmlSelect">
                                        <span class="text-muted italic" style="font-size:13px;">Seleccione un departamento para desplegar los técnicos...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12 form-group">
                                    <label class="font-weight-bold">Adjuntar Nuevo Archivo Adicional:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="archivoAdjunto" name="archivoAdjunto">
                                        <label class="custom-file-label" for="archivoAdjunto">Elegir archivo...</label>
                                    </div>
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
                            <input type="hidden" id="departamentosecreto" name="departamentosecreto" value="<?php echo $departamentos ?>">
                            <input type="hidden" id="textooculto" name="textooculto" value="<?php echo $detalleTG?>">

                            <?php
                            $otcontrol = ($departamentos == 1) ? 'display:block' : 'display:none';
                            $otespecial = ($departamentos != 1 && $departamentos >= 1) ? 'display:block' : 'display:none';
                            ?>

                            <div id="otcontrol" style="<?php echo $otcontrol; ?>">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en BMS: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoBMS" id="detalleTurnoBMS" rows="3"><?php echo $detalleTG ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en CCTV: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoCCTV" id="detalleTurnoCCTV" rows="3"><?php echo $detalleTurnoCamara ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en LLE: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoLLE" id="detalleTurnoLLE" rows="3"><?php echo $detalleTurnoLLE ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en Control de Acceso: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoCA" id="detalleTurnoCA" rows="3"><?php echo $detalleTurnoCA ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-primary">Detalle en Rondas: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoRondas" id="detalleTurnoRondas" rows="3"><?php echo $detalleTurnoRonda ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold text-muted">Otros tipos de trabajos (Limpieza, Ordenar):</label>
                                        <textarea class="form-control" name="detalleOtros" id="detalleOtros" rows="3"><?php echo $detalleOtros ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="otespecial" style="<?php echo $otespecial; ?>">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="font-weight-bold text-success">Descripción Detallada del Trabajo: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="detalleTurnoNoControl" id="detalleTurnoNoControl" rows="5" placeholder="Escriba aquí las actividades detalladas..."><?php echo $detalleTG ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <?php
                        $now = date('Y-m-d H:i:s');
                        $nomCarpeta = 'ORDEN DE TRABAJO - ' . $now;
                        $letra = ($userUsuario == 'ycasimis') ? 'YC' : (($userUsuario == 'iperez') ? 'IP' : (($userUsuario == 'acantillano') ? 'AC' : 'US'));
                        $trabajoTerminado = 0;
                        ?>
                        <input type="hidden" name="fechaModi" value="<?php echo $now ?>">
                        <input type="hidden" name="letraSolicitante" value="<?php echo $letra ?>">
                        <input type="hidden" name="nomCarpeta" value="<?php echo $nomCarpeta ?>">
                        <input type="hidden" name="trabajoTerminado" value="<?php echo $trabajoTerminado ?>">
                        <input type="hidden" name="idTG" value="<?php echo $idTG ?>">
                        <input type="hidden" name="idTecnico1" value="<?php echo $tecnico1 ?>">
                        <input type="hidden" name="folioTG" value="<?php echo $folioTG ?>">

                        <div class="col-md-6 mb-2">
                            <button type="submit" name="Guardar" id="Guardar" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Guardar Modificaciones
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="detalle_ordenes_trabajo.php" class="btn btn-warning btn-block font-weight-bold py-2 text-white shadow-sm"><i class="fas fa-undo mr-1"></i> Cancelar / Volver</a>
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
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                        $sqlArchivo = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                        $resArchivo = mysqli_query($conexion, $sqlArchivo);
                        while ($rowArchivo = $resArchivo->fetch_assoc()) {
                            ?>
                            <tr>
                                <td class="align-middle pl-2"><?php echo $rowArchivo['nomArchivo']; ?></td>
                                <td class="text-center align-middle"><span class="badge badge-light border"><?php echo strtoupper($rowArchivo['extArchivo']); ?></span></td>
                                <td class="text-center align-middle"><?php echo ($rowArchivo['trabajoTerminado'] == 0) ? '<span class="badge badge-secondary">NO</span>' : '<span class="badge badge-success">SI</span>'; ?></td>
                                <td class="text-center align-middle"><?php echo $rowArchivo['fechaSubida']; ?></td>
                                <td class="text-center align-middle">
                                    <form action="../sdo-funciones/eliminar-archivo-trabajo-general.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este archivo?');">
                                        <input type="hidden" name="idArchivo" value="<?php echo $rowArchivo['idArchivo']; ?>">
                                        <button type="submit" class="btn btn-danger btn-xs px-2"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-block font-weight-bold" data-dismiss="modal">Cerrar Ventana</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer text-center">
        <strong>Copyright &copy; 2022 - 2026. SDO Plataforma de Operaciones.</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $(document).ready(function () {
        $('#tabla-equipo-falla').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "responsive": true,
            "language": {
                "zeroRecords": "No se han encontrado registros.",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "No existen registros.",
                "paginate": { "previous": "Anterior", "next": "Siguiente" }
            },
        });
    });
</script>
</body>
</html>