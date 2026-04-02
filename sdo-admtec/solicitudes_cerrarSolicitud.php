<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

$nomUsuario = $_SESSION['nomUsuario'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_SESSION['dataFinalizador'])) {

    $dataFinalizador = trim($_SESSION['dataFinalizador']);

    if (empty($dataFinalizador)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    }
} else {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
}

if (isset($_POST['idSolicitud'])) {
    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));

        $_SESSION['idSolicitud'] = $idSolicitud;
    }
} else if (isset($_SESSION['idSolicitud'])) {
    $idSolicitud = trim($_SESSION['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));
    }
} else {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Solicitud de Soporte</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicios').change(function() {
                recargarNomDepartamento();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicios').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini ">
<!-- Site wrapper -->
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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Administrar Solicitud de Soporte</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php
        if (isset($_GET['exitoAsignarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    'Asignaci&oacute;n de Personal con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorAsignarPersonal'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Asignar Personal a la Solicitud.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoCambiarDepartamento'])) {
            ?>
            <script>
                Swal.fire(
                    'Cambio de Departamento Responsable con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorCambiarDepartamento'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Cambiar Departamento Responsable.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoCancelarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    'Solicitud Cancelada con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorCancelarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Cancelar Solicitud. Reintente.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoAgregarObservacion'])) {
            ?>
            <script>
                Swal.fire(
                    'Observaci&oacute;n Agregada con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorAgregarObservacion'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Agregar Observaci&oacute;n. Reintente.',
                    'error'
                )
            </script>
            <?php
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="solicitudes_detalle.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <br>
                                <h4 align="justify"><b>Finalizando Solicitud N° : </b><?php echo $folioSolicitud; ?></h4>
                            </div>
                            <div class="card-body">

                                <form action="../sdo-funciones/solicitudes_finalizar_nuevo_formato.php" method="post" enctype="multipart/form-data" id="terminarFinalizar"  name="terminarFinalizar">

                                    <h4 class="card-title">
                                        <b>
                                            <u>Informaci&oacute;n de la Solicitud</u>
                                        </b>
                                    </h4>
                                    <br>
                                    <br>
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputFecha">Fecha Actual:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <?php
                                                date_default_timezone_set('America/Santiago');
                                                ?>
                                                <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y H:i'); ?>" />

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Persona Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" value="<?php echo $nomSolicitante ?>" placeholder="Persona Solicitante">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputQuienSolicita">Departamento Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="deptoSolicitante" name="deptoSolicitante" value="<?php echo $ultima_deptoSolicitante ?>" placeholder="Departamento Solicitante">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Departamento Responsable: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-building"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="departamentoResponsable" readonly="readonly" required name="departamentoResponsable" class="form-control" placeholder="Departamento Responsable" value="<?php echo $deptoResponsable; ?>">

                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputRounded0">Tipo de Problema: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-building"></span>
                                                    </div>
                                                </div>
                                                <input type="text" id="tipoObservacion" readonly="readonly" required name="tipoObservacion" class="form-control" placeholder="Tipo de Problema" value="<?php echo $tipoProblemaSolicitud; ?>">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Ubicaci&oacute;n donde se encuentra el Problema (PMA): <code>*</code></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-map"></span>
                                                </div>
                                            </div>
                                            <input type="text" id="pmaSolicitud" readonly="readonly" required name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="<?php echo $ubicacionSolicitud; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Detalle de Problema: <code>*</code></label>
                                        <div class="input-group mb-2">
                                            <textarea id="detalleSolicitud" readonly="readonly" style="resize:none;" name="detalleSolicitud" class="form-control" rows="8" placeholder="Detalle del Problema que Presenta..."><?php echo $problemaSolicitud; ?></textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <br>

                                    <h5 class="card-title">
                                        <b>
                                            <u>Conclusi&oacute;n de la Solicitud</u>
                                        </b>
                                    </h5>

                                    <br>

                                    <br>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Inicio Fecha/Hora:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioInicio" name="horarioInicio" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Termino Fecha/Hora:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioTermino" name="horarioTermino" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="noOrdenTrabajo">N° Orden de Trabajo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                </div>
                                                <input type="text" list="noOrdenTrabajos" style="text-transform:capitalize"
                                                       class="form-control" id="noOrdenTrabajo"
                                                       name="noOrdenTrabajo" value=""
                                                       placeholder="Escriba el N° de Orden de Trabajo">
                                                <datalist id="noOrdenTrabajos">
                                                    <?php
                                                    $sqlOrden = "SELECT * FROM trabajos_general WHERE tecnico1 = '$nomUsuario' AND estadoTG != 'Trabajo Terminado'";
                                                    $resOrden = mysqli_query($conexion,$sqlOrden);
                                                    while($rowOrden = $resOrden->fetch_assoc()){
                                                        $folioTG = $rowOrden['folioTG'];
                                                        $turnoTG = $rowOrden['turnoTG'];
                                                        $fechaProgramacion = $rowOrden['fechaProgramacion'];
                                                        ?>
                                                        <option value="<?php echo $folioTG?>"><?php echo $turnoTG?> / <?php echo $fechaProgramacion?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </datalist>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Tipo de Matención Realizada: <code>*</code></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-map"></span>
                                                </div>
                                            </div>
                                            <select name="tipoMantencion" class="form-control" required id="tipoMantencion">
                                                <option value="" disabled selected>-- Seleccione --</option>
                                                <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                <option value="OM">OM - Obras Menores</option>
                                                <option value="IS">IS - Inspección/Supervisión</option>
                                                <?php
                                                if($idDepartamento == 1){
                                                    ?>
                                                    <option value="MS">MS - Mejoras del Sistema</option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Unidad o Servicio: <code>*</code></label>
                                            <div class="input-group mb-2">
                                                <select name="servicios" id="servicios" class="form-control select2" required>
                                                    <option value="" disabled selected>Seleccione un servicio</option>
                                                    <?php
                                                    $sqlServicio = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                    $resServicio = mysqli_query($conexion,$sqlServicio);
                                                    while($rowServicio = $resServicio->fetch_assoc()){
                                                        $idServicio = $rowServicio['idServicio'];
                                                        $nomServicio = $rowServicio['nomServicio'];
                                                        ?>
                                                        <option value="<?php echo $idServicio?>"><?php echo $nomServicio?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    <option value="Otro">Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group col-md-6">

                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Recinto:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                </div>
                                                <input type="text" id="recintoOrden"  class="form-control" name="recintoOrden">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputRounded0">Conclusi&oacute;n del Problema: </label>
                                        <div class="input-group mb-2">
                                            <textarea id="conclusionProblema" style="resize:none;" name="conclusionProblema" class="form-control" rows="8" placeholder="Conclusión del Problema."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-row fieldGroup">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputRecinto">Materiales Usados:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputPMA">Unidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span><b>Agregar Material Usado</b> </a>
                                            </div>
                                        </div>
                                    </div>

                                    <br>


                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Imagenes: </label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="archivoImagenOrden[]" name="archivoImagenOrden[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10 Archivos)</label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf, .xlsx(Excel), .docx(Word) </label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="archivosOrden[]" name="archivosOrden[]" accept=".pdf, .xlsx, .docx">
                                            <label class="custom-file-label" for="customFile"><b>Seleccionar Archivo...</b> </label>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                    <div class="row col-md-12 col-12 text-center justify-content-center">
                                        <button type="button" class="btn btn-primary text-center mb-5 ml-2" id="botonAddHT" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fas fa-plus"></i>&nbsp;&nbsp;<b>Añadir Equipo a la Hoja Técnica</b> </button>
                                    </div>
                                    <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalHojasLabel">Escoja el equipamiento</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">N°</th>
                                                            <th scope="col" class="text-center">Fecha Registro</th>
                                                            <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                            <th scope="col" class="text-center">Marca</th>
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">Servicio</th>
                                                            <th scope="col" class="text-center">PMA</th>
                                                            <th scope="col" class="text-center">Seleccionar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if($idDepartamento == 2){
                                                            $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento OR idDepaPertenece = 12 ORDER BY nombreEquipo ASC";
                                                            $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                        }elseif($idDepartamento == 12){
                                                            $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento OR idDepaPertenece = 2 ORDER BY nombreEquipo ASC";
                                                            $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                        }else{
                                                            $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = $idDepartamento ORDER BY nombreEquipo ASC";
                                                            $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);
                                                        }

                                                        $num = 1;
                                                        while($rowHojaTec1 = $resHojaTec1->fetch_assoc()){
                                                            $datos = [];
                                                            $datos['id'] = $rowHojaTec1['id'];
                                                            $datos['fechaRegistro'] = $rowHojaTec1['fechaRegistro'];
                                                            $datos['nombreEquipo'] = $rowHojaTec1['nombreEquipo'];
                                                            $datos['marca'] = $rowHojaTec1['marca'];
                                                            $datos['recinto'] = $rowHojaTec1['recinto'];
                                                            $datos['idServicio'] = $rowHojaTec1['idServicio'];
                                                            $datos['pma'] = $rowHojaTec1['pma'];
                                                            $id = $datos['id'];
                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                            $nombreEquipo = $datos['nombreEquipo'];
                                                            $marca = $datos['marca'];
                                                            $recinto = $datos['recinto'];
                                                            $idServicio = $datos['idServicio'];
                                                            $pma = $datos['pma'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $num ?> </td>
                                                                <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                <td>
                                                                    <?php
                                                                    echo $marca;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if($recinto == ''){
                                                                        echo '-';
                                                                    } else{
                                                                        echo $recinto;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $sql = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                                    $res = mysqli_query($conexion,$sql);
                                                                    while($row = $res->fetch_assoc()){
                                                                        $datos1 = [];
                                                                        $datos1['nomServicio'] = $row['nomServicio'];
                                                                        $nomServicio = $datos1['nomServicio'];
                                                                        echo $nomServicio;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $pma ?>
                                                                </td>
                                                                <td>
                                                                    <button type="button" onclick="obtenerid(<?php echo $id?>)" class="btn btn-danger">Añadir Este Equipo</button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $num++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th scope="col" class="text-center">N°</th>
                                                            <th scope="col" class="text-center">Fecha Registro</th>
                                                            <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                            <th scope="col" class="text-center">Marca</th>
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">Servicio</th>
                                                            <th scope="col" class="text-center">PMA</th>
                                                            <th scope="col" class="text-center">Seleccionar</th>

                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="border p-3" id="fieldset2" name="fieldset2">

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Equipo o Instrumento:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-box"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="nomEquipo" name="nomEquipo"
                                                           value="" placeholder="Nombre del equipo">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Numero Inventario:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-barcode"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="numInventario"
                                                           name="numInventario" value=""
                                                           placeholder="Número de inventario del equipo">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Marca:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="marcaEquipo" name="marcaEquipo"
                                                           value="" placeholder="Marca del equipo">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">Modelo:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-copyright"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="modeloEquipo"
                                                           name="modeloEquipo" value=""
                                                           placeholder="Modelo del equipo">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputQuienSolicita">N° de Serie:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-code"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" readonly
                                                           class="form-control" id="numSerie" name="numSerie"
                                                           value="" placeholder="N° de serie del equipo">
                                                </div>
                                            </div>
                                    </fieldset>
                                    <hr>

                                    <br>
                                    <br>
                                    <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalHojasLabel">Escoja el equipamiento</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="hoja_tecnicas" class="table table-sm table-hover  table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">N°</th>
                                                            <th scope="col" class="text-center">Fecha Registro</th>
                                                            <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                            <th scope="col" class="text-center">Marca</th>
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">Servicio</th>
                                                            <th scope="col" class="text-center">Seleccionar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlHojaTec1 = "SELECT * FROM equipamiento_hbqp ORDER BY nombreEquipo ASC";
                                                        $resHojaTec1 = mysqli_query($conexion, $sqlHojaTec1);

                                                        $num = 1;
                                                        while($rowHojaTec1 = $resHojaTec1->fetch_assoc()){
                                                            $datos = [];
                                                            $datos['id'] = $rowHojaTec1['id'];
                                                            $datos['fechaRegistro'] = $rowHojaTec1['fechaRegistro'];
                                                            $datos['nombreEquipo'] = $rowHojaTec1['nombreEquipo'];
                                                            $datos['marca'] = $rowHojaTec1['marca'];
                                                            $datos['recinto'] = $rowHojaTec1['recinto'];
                                                            $datos['idServicio'] = $rowHojaTec1['idServicio'];
                                                            $id = $datos['id'];
                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                            $nombreEquipo = $datos['nombreEquipo'];
                                                            $marca = $datos['marca'];
                                                            $recinto = $datos['recinto'];
                                                            $idServicio = $datos['idServicio'];

                                                            ?>
                                                            <tr>
                                                                <td><?php echo $num ?> </td>
                                                                <td><?php echo mb_convert_case($fechaRegistro, MB_CASE_UPPER, 'UTF-8');  ?></td>
                                                                <td><?php echo mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                <td>
                                                                    <?php
                                                                    echo $marca;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if($recinto == ''){
                                                                        echo '-';
                                                                    } else{
                                                                        echo $recinto;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $sql = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                                    $res = mysqli_query($conexion,$sql);
                                                                    while($row = $res->fetch_assoc()){
                                                                        $datos1 = [];
                                                                        $datos1['nomServicio'] = $row['nomServicio'];
                                                                        $nomServicio = $datos1['nomServicio'];
                                                                        echo $nomServicio;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <button type="button" onclick="obtenerid(<?php echo $id?>)" class="btn btn-danger">Añadir Este Equipo</button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $num++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th scope="col" class="text-center">N°</th>
                                                            <th scope="col" class="text-center">Fecha Registro</th>
                                                            <th scope="col" class="text-center">Nombre del Equipamiento</th>
                                                            <th scope="col" class="text-center">Marca</th>
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">Servicio</th>
                                                            <th scope="col" class="text-center">Seleccionar</th>

                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="datosFinalizador" id="datosFinalizador" value="<?php echo $dataFinalizador; ?>">
                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                        <input type="hidden" name="idCategoria" id="idCategoria" value="">
                                        <input type="hidden" name="idEquipo" id="idEquipo" value="">
                                        <input type="hidden" id="idHoja" name="idHoja" value="">
                                        <input type="hidden" id="piso" name="piso" value="">
                                        <input type="hidden" id="idServicio" name="idServicio" value="">
                                        <input type="hidden" id="idDepartamento" name="idDepartamento" value="<?php echo $idDepartamento?>">
                                        <input type="submit" class="btn btn-block btn-primary" id="finalizarGuardar" name="finalizarGuardar" onclick="cambiarTipo()" value="Guardar" />
                                    </div>
                                    <div class="form-group">
                                        <a href="solicitudes_detalle.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>


                                </form>
                                <div class="form-row fieldGroupCopy" style="display: none;">
                                    <div class="form-group col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="materialUsado[]" name="materialUsado[]" placeholder="Materiales Usados">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            </div>
                                            <input type="text" style="text-transform:capitalize" class="form-control" id="unidad[]" name="unidad[]" placeholder="Unidad">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                            </div>
                                            <input type="text" style="text-transform:capitalize" class="form-control" id="cantidad[]" name="cantidad[]" placeholder="Cantidad">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> <b>Eliminar</b> </a>
                                        </div>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        //group add limit
                                        var maxGroup = "100";
                                        //add more fields group
                                        $(".addMore").click(function() {
                                            if ($('body').find('.fieldGroup').length < maxGroup) {
                                                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                                                $('body').find('.fieldGroup:last').after(fieldHTML);
                                            } else {
                                                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                                            }
                                        });
                                        //remove fields group
                                        $("body").on("click", ".remove", function() {
                                            $(this).parents(".fieldGroup").remove();
                                        });
                                    });
                                </script>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.timeline -->

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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="js/dark-mode.js"></script>
<!-- Page specific script -->
<!-- Page specific script -->
<script>
    $(document).ready(function () {
        // Inicializar el select2
        $('.select2').select2();

    });
</script>
<script>
    $(document).ready(function() {
        $('#hoja_tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    function cambiarTipo() {
        const boton = document.querySelector('button[id="finalizarGuardar"]');
        boton.type = 'button';  // Cambia el tipo del botón a "button"
        boton.textContent = 'Enviando...';  // Cambia el texto del botón
        boton.disabled = true;  // Deshabilita el botón para evitar envíos repetidos

        // // Simula un tiempo de carga antes de enviar el formulario
        // setTimeout(function() {
        //     document.getElementById('soporteAgregar').submit();  // Envía el formulario
        // }, 3000); // Simula 3 segundos de tiempo de carga


        document.getElementById('terminarFinalizar').submit();  // Envía el formulario
    }
</script>
<script>
    function obtenerid(id){
        console.log(id);
        idHoja = document.getElementById('idHoja');
        idHoja.value = id;
        $("#modalHojas").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHT = document.getElementById('botonAddHT');

        botonAddHT.classList.remove('btn-primary');
        botonAddHT.classList.add('btn-danger');
        botonAddHT.innerText = 'Equipo Seleccionado';

        var dataId = id;
        console.log(dataId);
        var elementos = {};
        //AJAX
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtener-datos-equipo.php',
            data: {
                'id': dataId
            },
            success: function(response) {
                console.log(response);
                var datos = JSON.parse(response);

                elementos.nombreEquipo = datos.nombreEquipo;
                elementos.marca = datos.marca;
                elementos.modelo = datos.modelo;
                elementos.numeroInventario = datos.numeroInventario;
                elementos.numeroSerie = datos.numeroSerie;
                elementos.idCategoria = datos.idCategoria;
                elementos.idServicio = datos.idServicio;
                elementos.idDepaPertenece = datos.idDepaPertenece;
                elementos.piso = datos.piso;
                elementos.id = datos.id;

                $('#nomEquipo').val(elementos.nombreEquipo);
                $('#marcaEquipo').val(elementos.marca);
                $('#modeloEquipo').val(elementos.modelo);
                $('#numInventario').val(elementos.numeroInventario);
                $('#numSerie').val(elementos.numeroSerie);
                $('#idCategoria').val(elementos.idCategoria);
                $('#idEquipo').val(elementos.id);
                $('#idServicio').val(elementos.idServicio);
                $('#idDepaPertenece').val(elementos.idDepaPertenece);
                $('#piso').val(elementos.piso);

            }
        })
    }
</script>
</body>

</html>