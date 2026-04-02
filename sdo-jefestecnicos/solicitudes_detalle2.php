<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

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
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

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
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));
    }
} else {
    header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
}

$textoInputAtras = '';

if (isset($_SESSION['volverAtras'])) {

    $volverAtras = $_SESSION['volverAtras'];

    if ($volverAtras == 'Recibidas') {

        $textoInputAtras = 'solicitudes_recibidas.php';
    } else if ($volverAtras == 'Canceladas') {

        $textoInputAtras = 'solicitudes_canceladas.php';
    } else {

        $textoInputAtras = 'solicitudes_recibidas.php';
    }
} else {

    $textoInputAtras = 'solicitudes_recibidas.php';
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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

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

<body class="hold-transition sidebar-mini dark-mode">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/jefes-sidebar.php';
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

                                    <div class="form-group">
                                        <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                            <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                        </div>
                                    </div>

                                    <script>
                                        function toggleDark() {

                                            document.body.classList.toggle("dark-mode");
                                            var element2 = document.getElementById("hrefInicio");

                                            const color = element2.style.color;

                                            if(color == 'white'){
                                                element2.style.color = 'blue';
                                            }else{
                                                element2.style.color = 'white';
                                            }

                                        }
                                    </script>
                                    <br>
                                    <br>
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><b>Visualizando Solicitud: </b><?php echo $folioSolicitud; ?></h5>
                                            <h5><b>Funcionario Solicitante: </b><?php echo $nomSolicitante; ?></h5>
                                            <h5><b>Unidad Solicitante: </b><?php echo $ultima_deptoSolicitante; ?></h5>
                                            <br>
                                            <h5><b>Departamento Responsable: </b><?php echo $deptoResponsable; ?></h5>
                                            <h5><b>Tipo de Observaci&oacute;n: </b><?php echo $tipoProblemaSolicitud; ?></h5>
                                            <h5><b>Estado Solicitud: </b><?php echo $situacionSolicitud; ?></h5>
                                            <?php
                                            if (!empty($tecnicoSolicitud)) {
                                            ?>
                                                <h5><b>Personal Asignado: </b><?php echo $tecnicoSolicitud; ?></h5>
                                            <?php
                                            } else {
                                            ?>
                                                <h5><b>Personal Asignado: </b>Sin Asignar</h5>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div style="font-size:85%" class="col-md-12">

                                            <h5 style="text-align:justify; font-size:150%">
                                                <b>Detalle Solicitud: </b>
                                                <br><br>
                                                <textarea id="detalleSolicitud" style="resize:none; background-color: white; font-size:85%" readonly="readonly" name="detalleSolicitud" class="form-control" rows="12" placeholder="Detalle del Problema que Presenta..."><?php echo $problemaSolicitud; ?></textarea>

                                            </h5>
                                        </div>
                                    </div>

                                    <?php


                                    $_SESSION['dataFinalizador'] = $nomUsuario . ' ( Jefe de ' . $nomDepartamento . ' )';

                                    if ($canceladaSolicitud == 0 && $finalizadaSolicitud == 0) {
                                    ?>
                                        <hr>
                                        <div class="row justify-content-md-center">

                                            <!-- ASIGNAR TECNICO -->
                                            <div class="col-md-2">

                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_asignarTecnico.php">
                                                        <button type="button" data-toggle="modal" data-target="#modalTecnicoAsignar" id="inputMozi" class="btn bg-primary btn-block" style="float:right;"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Asignar T&eacute;cnico</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalTecnicoAsignar" name="modalTecnicoAsignar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Asignar T&eacute;cnico a Solicitud</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Nombre de Usuario: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomPersonal" name="nomPersonal" placeholder="Nombre de Personal" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . $nomDepartamento . ' )'; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Departamento Responsable: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="departamentoResponsable" name="departamentoResponsable" placeholder="Departamento Responsable" value="<?php echo $deptoResponsable; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Tecnico: <code>*</code></label>

                                                                            <select name="tecnicoResponsable" id="tecnicoResponsable" class="form-control">
                                                                                <option id="idOptionTecnicoAdmin" value="0">Seleccione...</option>
                                                                                <option id="idOptionTecnicoAdmin" value="0">&nbsp;</option>
                                                                                <?php
                                                                                $queryJefe = "SELECT idJefe, nomJefe, cargoJefe FROM jefes_tecnicos  WHERE idDepartamento = '" . getIDDepartamento($deptoResponsable) . "' AND nomJefe NOT LIKE '%" . $nomUsuario . "%' ORDER BY cargoJefe ASC, nomJefe ASC";

                                                                                $resJefe = mysqli_query($conexion, $queryJefe);

                                                                                while ($rowJefe = mysqli_fetch_array($resJefe)) {
                                                                                ?>
                                                                                    <option id="idOptionTecnicoAdmin" value="<?php echo $rowJefe['nomJefe'] . " ( " . $rowJefe['cargoJefe'] . " ) " ?>"><?php echo $rowJefe['nomJefe'] . " ( " . $rowJefe['cargoJefe'] . " ) " ?></option>
                                                                                <?php
                                                                                }
                                                                                ?>

                                                                                <option id="idOptionTecnicoAdmin" value="0">&nbsp;</option>

                                                                                <?php
                                                                                $queryPersonal = "SELECT idPersonal, nomPersonal FROM personal WHERE departamentoPersonal = '" . getIDDepartamento($deptoResponsable) . "' AND activoPersonal = 1 ORDER BY nomPersonal ASC";

                                                                                $resPersonal = mysqli_query($conexion, $queryPersonal);

                                                                                while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                                                                                ?>
                                                                                    <option id="idOptionTecnicoAdmin" value="<?php echo $rowPersonal['nomPersonal'] . " ( Tecnico del Departamento )" ?>"><?php echo $rowPersonal['nomPersonal'] . " ( Tecnico del Departamento )" ?></option>
                                                                                <?php
                                                                                }

                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <button type="submit" class="btn btn-primary">Asignar Tecnico</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-primary btn-block" style="float:right;"><i class="fa fa-user"></i>&nbsp;&nbsp;Asignar T&eacute;cnico</button>

                                                <?php
                                                }
                                                ?>

                                            </div>
                                            <!-- FIN ASIGNAR TECNICO -->

                                            &nbsp;
                                            &nbsp;

                                            <!-- AGREGAR OBSERVACIÓN -->
                                            <div class="col-md-2">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_agregarObservacion.php">
                                                        <button type="button" data-toggle="modal" data-target="#modalObservacionSolicitud" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalObservacionSolicitud" name="modalObservacionSolicitud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Nombre del Personal: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomPersonal" name="nomPersonal" placeholder="Nombre de Personal" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . $nomDepartamento . ' )'; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Observaciones: <code>*</code></label>
                                                                            <textarea id="observacionSolicitud" style="resize:none;" name="observacionSolicitud" class="form-control" rows="12" placeholder="Observaciones..."></textarea>

                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <button type="submit" class="btn btn-primary">Agregar Observaci&oacute;n</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN AGREGAR OBSERVACIÓN -->

                                            &nbsp;
                                            &nbsp;

                                            <!-- CAMBIAR DESTINO SOLICITUD -->
                                            <div class="col-md-2">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_cambiar_destino.php">
                                                        <button type="button" data-toggle="modal" data-target="#modalCambiarDestino" id="inputMozi" class="btn bg-green btn-block" style="float:right;"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp;Cambiar Destino Solicitud</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalCambiarDestino" name="modalCambiarDestino" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Cambiar Destino de Solicitud:</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Nombre de Usuario: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomCreador" name="nomCreador" placeholder="Nombre de Personal" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . $nomDepartamento . ' )'; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Departamento Anterior Responsable: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="deptoAnteriorResponsable" name="deptoAnteriorResponsable" placeholder="Departamento Anterior Responsable" value="<?php echo $deptoResponsable; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Nuevo Departamento Responsable: <code>*</code></label>
                                                                            <select class="form-control" name="departamentoNuevoResponsable" id="departamentoNuevoResponsable">
                                                                                <option value="0">Seleccione...</option>
                                                                                <?php
                                                                                $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                                $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                                while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {
                                                                                ?>
                                                                                    <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                                        <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                    </option>
                                                                                <?php

                                                                                }


                                                                                ?>
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group" id="dataTipoSolicitud" name="dataTipoSolicitud">

                                                                        </div>

                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Razones de Cambio de Departamento: <code>*</code></label>
                                                                            <textarea id="razonesCambio" style="resize:none;" name="razonesCambio" class="form-control" rows="12" placeholder="Razones de Cambio de Departamento..."></textarea>

                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-green btn-block" style="float:right;"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp;Cambiar Destino Solicitud</button>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN CAMBIAR DESTINO SOLICITUD -->

                                            &nbsp;
                                            &nbsp;

                                            <!-- CANCELAR SOLICITUD -->
                                            <div class="col-md-2">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_cancelarSolicitud.php">
                                                        <button type="button" data-toggle="modal" data-target="#modalCancelarSolicitud" id="inputMozi" class="btn bg-red btn-block" style="float:right;"><i class="fa fa-times"></i>&nbsp;&nbsp;Cancelar Solicitud</button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalCancelarSolicitud" name="modalCancelarSolicitud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-red">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Cancelar Solicitud</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomQuienCancela" name="nomQuienCancela" placeholder="Nombre de Usuario" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . $nomDepartamento . ' )'; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Razones de Cancelaci&oacute;n: <code>*</code></label>
                                                                            <textarea id="razonesCancelar" style="resize:none;" name="razonesCancelar" class="form-control" rows="12" placeholder="Razones de Cancelaci&oacute;n..."></textarea>

                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-red btn-block" style="float:right;"><i class="fa fa-times"></i>&nbsp;&nbsp;Cancelar Solicitud</button>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN CANCELAR SOLICITUD -->

                                            &nbsp;
                                            &nbsp;

                                            <!-- FINALIZAR SOLICITUD -->
                                            <div class="col-md-2">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                ?>
                                                    <form method="post" action="../sdo-jefestecnicos/solicitudes_cerrarSolicitud.php">
                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                        <button type="submit" id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fa fa-file"></i>&nbsp;&nbsp;Finalizar Solicitud</button>

                                                    </form>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fa fa-file"></i>&nbsp;&nbsp;Finalizar Solicitud</button>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN FINALIZAR SOLICITUD -->

                                        </div>
                                    <?php
                                    }
                                    if ($finalizadaSolicitud == 1) {
                                    ?>
                                        <hr>
                                        <div class="row justify-content-md-center">

                                            <!-- ASIGNAR TECNICO -->
                                            <div class="col-md-3">
                                                <form method="post" action="../sdo-pdf/SDO-SolicitudSoporte.php">
                                                    <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                    <button type="submit" id="inputMozi" class="btn bg-primary btn-block" style="float:right;"><i class="fa fa-file"></i>&nbsp;&nbsp;&nbsp;&nbsp;Revisar Informe de Finalizaci&oacute;n</button>

                                                </form>
                                            </div>
                                        </div>
                                    <?php
                                    }

                                    ?>


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">


                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha Observaci&oacute;n:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Creador Observaci&oacute;n:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Tipo Observaci&oacute;n:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Detalle Observaci&oacute;n:</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = "SELECT * FROM observaciones_tickets WHERE idSolicitud = '" . $idSolicitud . "' ORDER BY fechaObservaSolicitudes DESC";

                                            $res = mysqli_query($conexion, $query);

                                            $numSolicitud = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php if ($row['fechaObservaSolicitudes'] == 0) {
                                                                echo "No registra fecha";
                                                            } else {

                                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaSolicitudes'])));
                                                                $inicioHora = date('H:i', strtotime($row['fechaObservaSolicitudes']));

                                                                echo $inicioFecha . '<br>' . $inicioHora;
                                                            } ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $creadorObservaSolicitudes = mb_strtoupper($row['creadorObservaSolicitudes']);
                                                        echo $creadorObservaSolicitudes;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $tipoObservaSolicitudes = mb_strtoupper($row['tipoObservaSolicitudes']);
                                                        echo $tipoObservaSolicitudes;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $detalleObservaSolicitudes = nl2br($row['detalleObservaSolicitudes']);
                                                        echo $detalleObservaSolicitudes;

                                                        ?>
                                                    </td>


                                                <?php

                                                $numSolicitud = $numSolicitud + 1;
                                            }
                                                ?>



                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <th style="vertical-align:middle;">
                                                <center>Fecha Observaci&oacute;n:</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Creador Observaci&oacute;n:</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tipo Observaci&oacute;n:</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalle Observaci&oacute;n:</center>
                                            </th>
                                        </tfoot>
                                    </table>

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
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
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
</body>

</html>