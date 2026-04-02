<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
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

        $sqlHT = "SELECT idOrden FROM ordenes_trabajos_general WHERE idSolicitudSoporte  = $idSolicitud";
        $resHT = mysqli_query($conexion, $sqlHT);

        while ($rowHT = $resHT->fetch_assoc()) {
            $datos = [];
            $datos['idOrden'] = $rowHT['idOrden'];
            $idOrden = $datos['idOrden'];

        }

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

        $sqlHT = "SELECT idOrden FROM ordenes_trabajos_general WHERE idSolicitudSoporte  = $idSolicitud";
        $resHT = mysqli_query($conexion, $sqlHT);

        while ($rowHT = $resHT->fetch_assoc()) {
            $datos = [];
            $datos['idOrden'] = $rowHT['idOrden'];
            $idOrden = $datos['idOrden'];
        }

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
    } else if ($volverAtras == 'Finalizadas') {

        $textoInputAtras = 'solicitudes_finalizadas.php';
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
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function () {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function (r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
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
                            <li class="breadcrumb-item">
                                <a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
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

        if (isset($_GET['exitoFinalizarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    'Solicitud Finalizada con &Eacute;xito.',
                    '',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorFinalizarSolicitud'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Finalizar Solicitud. Reintente.',
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

                                <a href="<?php echo $textoInputAtras; ?>" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>
                            <div style="font-size:85%" class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 style="font-size:150%">
                                            <b>Visualizando Solicitud: </b><?php echo $folioSolicitud; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Funcionario Solicitante: </b><?php echo $nomSolicitante; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Unidad Solicitante: </b><?php echo $ultima_deptoSolicitante; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Correo Solicitante: </b><?php echo $emailSolicitante; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Telefono Solicitante: </b><?php echo $telefonoSolicitante; ?>
                                        </h5>
                                        <br>
                                        <h5 style="font-size:150%">
                                            <b>Ubicaci&oacute;n (PMA): </b><?php echo $ubicacionSolicitud; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Tipo de Ayuda: </b><?php echo $deptoResponsable; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Tipo de Observaci&oacute;n: </b><?php echo $tipoProblemaSolicitud; ?>
                                        </h5>
                                        <h5 style="font-size:150%">
                                            <b>Estado Solicitud: </b><?php echo $situacionSolicitud; ?>
                                        </h5>
                                        <?php
                                        if (!empty($tecnicoSolicitud)) {
                                            ?>
                                            <h5 style="font-size:150%">
                                                <b>Personal Asignado: </b><?php echo $tecnicoSolicitud; ?>
                                            </h5>
                                            <?php
                                        } else {
                                            ?>
                                            <h5 style="font-size:150%">
                                                <b>Personal Asignado: </b>Sin Asignar
                                            </h5>
                                            <?php
                                        }
                                        ?>
                                        <br>
                                        <br>

                                    </div>

                                    <?php
                                    

                                    $_SESSION['dataFinalizador'] = $nomUsuario . ' ( Tecnico de ' . $departamentoPersonal . ' )';

                                    if ($canceladaSolicitud == 0 && $finalizadaSolicitud == 0) {
                                        ?>
                                        <hr>
                                        <div style="font-size:85%" class="col-md-3">

                                            <!-- ASIGNAR TECNICO -->
                                            <div class="col-md-12">

                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                    ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_asignarTecnico.php" id="asignarTecnico" name="asignarTecnico">
                                                        <button type="button" data-toggle="modal" data-target="#modalTecnicoAsignar" id="inputMozi" class="btn bg-primary btn-block" style="float:right;">
                                                            <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Asignar a Otro T&eacute;cnico
                                                        </button>

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
                                                                            <label for="exampleInputBorder">Nombre de Usuario:
                                                                                <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomPersonal" name="nomPersonal" placeholder="Nombre de Personal" value="<?php echo $nomUsuario . ' ( Tecnico de ' . $departamentoPersonal . ' )'; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Departamento Responsable:
                                                                                <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="departamentoResponsable" name="departamentoResponsable" placeholder="Departamento Responsable" value="<?php echo $deptoResponsable; ?>" required>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Personal:
                                                                                <code>*</code></label>

                                                                            <select name="tecnicoResponsable" id="tecnicoResponsable" class="form-control">
                                                                                <option id="idOptionTecnicoAdmin" value="0">Seleccione...</option>
                                                                                <option id="idOptionTecnicoAdmin" value="0">&nbsp;</option>

                                                                                <?php
                                                                                $queryPersonal = "SELECT idPersonal, nomPersonal FROM personal WHERE departamentoPersonal = '" . getIDDepartamento($deptoResponsable) . "' AND activoPersonal = 1 ORDER BY nomPersonal ASC";

                                                                                $resPersonal = mysqli_query($conexion, $queryPersonal);

                                                                                while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                                                                                    ?>
                                                                                    <option id="idOptionTecnicoAdmin" value="<?php echo 'Personal' . $rowPersonal['idPersonal'] ?>"><?php echo $rowPersonal['nomPersonal'] . " ( Tecnico del Departamento )" ?></option>
                                                                                    <?php
                                                                                }

                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <button type="submit" class="btn btn-primary" id="asignarGuardar" onclick="cambiarTipo()">Asignar Tecnico</button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-primary btn-block" style="float:right;">
                                                        <i class="fa fa-user"></i>&nbsp;&nbsp;Asignar T&eacute;cnico
                                                    </button>

                                                    <?php
                                                }
                                                ?>

                                            </div>
                                            <!-- FIN ASIGNAR TECNICO -->

                                            <br>

                                            <br>

                                            <br>

                                            <!-- AGREGAR OBSERVACIÓN -->
                                            <div class="col-md-12">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                    ?>
                                                    <form method="post" action="../sdo-funciones/solicitudes_agregarObservacion.php">
                                                        <button type="button" data-toggle="modal" data-target="#modalObservacionSolicitud" id="inputMozi" class="btn bg-navy btn-block" style="float:right;">
                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion
                                                        </button>
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
                                                                            <label for="exampleInputBorder">Nombre del Personal:
                                                                                <code>*</code></label>
                                                                            <input type="text" readonly="readonly" class="form-control " id="nomPersonal" name="nomPersonal" placeholder="Nombre de Personal" value="<?php echo $nomUsuario . ' ( Tecnico de ' . $departamentoPersonal . ' )'; ?>" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                <input type="checkbox" class="custom-control-input" id="customSwitch4" onclick="privada()">
                                                                                <input type="hidden" value="NO" id="obsPrivada" name="obsPrivada">
                                                                                <script type="text/javascript">
                                                                                    function privada() {
                                                                                        let obsPrivada = document.getElementById("obsPrivada");

                                                                                        if (obsPrivada.value == "NO") {
                                                                                            obsPrivada.value = "SI";
                                                                                        } else {
                                                                                            obsPrivada.value = "NO";
                                                                                        }
                                                                                    }
                                                                                </script>
                                                                                <label class="custom-control-label" for="customSwitch4">Observación Privada</label>
                                                                            </div>
                                                                        </div>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Observaciones:
                                                                                <code>*</code></label>
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
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-navy btn-block" style="float:right;">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion
                                                    </button>

                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN AGREGAR OBSERVACIÓN -->

                                            <br>

                                            <br>

                                            <br>

                                            <!-- AGREGAR OBSERVACIÓN -->
                                            <div class="col-md-12">
                                                <?php
                                                if ($canceladaSolicitud == 0) {
                                                    if ($idDepartamento == 6) {
                                                        $activo = "../sdo-personal/solicitudes_cerrarSolicitud_inge_clinica.php";
                                                    } else {
                                                        $activo = "../sdo-personal/solicitudes_cerrarSolicitud.php";
                                                    }
                                                    ?>
                                                    <form method="post" action="<?php echo $activo ?>">
                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                        <button type="submit" id="inputMozi" class="btn bg-warning btn-block" style="float:right;">
                                                            <i class="fa fa-file"></i>&nbsp;&nbsp;Finalizar Solicitud
                                                        </button>
                                                    </form>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button type="button" disabled="disabled" id="inputMozi" class="btn bg-navy btn-block" style="float:right;">
                                                        <i class="fa fa-file"></i>&nbsp;&nbsp;Finalizar Solicitud
                                                    </button>

                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- FIN AGREGAR OBSERVACIÓN -->


                                        </div>
                                        <?php
                                    }

                                    if ($finalizadaSolicitud == 1) {
                                        if ($idDepartamento == 6) {
                                            $activoFinal = "../sdo-pdf/SDO-OrdenTrabajoGeneral-Inge-Clinica.php";
                                        } else {
                                            $activoFinal = "../sdo-pdf/SDO-OrdenTrabajoGeneral.php";
                                        }
                                        ?>
                                        <hr>
                                        <div style="font-size:85%" class="col-md-3">

                                            <!--                                                <div class="col-md-12">-->
                                            <!--                                                    <form method="post" target="_blank" action="../sdo-pdf/SDO-SolicitudSoporte.php">-->
                                            <!--                                                        <input type="hidden" name="idSolicitud" id="idSolicitud" value="--><?php //echo $idSolicitud; ?><!--">-->
                                            <!--                                                        <button type="submit" id="inputMozi" class="btn bg-primary btn-block" style="float:right;"><i class="fa fa-file"></i>&nbsp;&nbsp;&nbsp;&nbsp;Revisar Informe de Finalizaci&oacute;n</button>-->
                                            <!---->
                                            <!--                                                    </form>-->
                                            <!--                                                </div>-->
                                            <?php
                                            //                                                if($resHT && mysqli_affected_rows($conexion) > 0){
                                            //                                                    ?>
                                            <!--                                                    <div class="col-md-12 ">-->
                                            <!--                                                        <form method="post" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" class="mt-5">-->
                                            <!--                                                            <input type="hidden" name="idOrden" id="idOrden" value="--><?php //echo $idOrden; ?><!--">-->
                                            <!--                                                            <input type="hidden" name="idSolicitud" id="idSolicitud" value="--><?php //echo $idSolicitud; ?><!--">-->
                                            <!--                                                            <button type="submit" id="inputMozi" class="btn bg-pink btn-block" style="float:right;"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;&nbsp;&nbsp;Descargar Hoja Técnica</button>-->
                                            <!---->
                                            <!--                                                        </form>-->
                                            <!--                                                    </div>-->
                                            <!--                                                    --><?php
                                            //                                                }

                                            $sqlHTFinal = "SELECT idOrden FROM ordenes_trabajos_general WHERE ticketAsociado LIKE '$folioSolicitud'";

                                            $resHTFinal = mysqli_query($conexion, $sqlHTFinal);

                                            $cantidadHTFinal = mysqli_num_rows($resHTFinal);

                                            switch ($cantidadHTFinal) {
                                                case 0:
                                                    // No hay órdenes asociadas
                                                    ?>
                                                    <div class="col-md-12 ">
                                                        <button class="btn btn-block btn-primary" disabled="disabled">Sin HT Asociada</button>
                                                    </div>
                                                    <?php
                                                    break;

                                                case 1:
                                                    // Una sola OT, mostrar botón directo
                                                    $fila = mysqli_fetch_assoc($resHTFinal);
                                                    $idOrden = $fila['idOrden'];
                                                    ?>
                                                    <div class="col-md-12 ">
                                                        <button class="btn btn-block btn-primary" onclick="abrirHTSoporte('<?php echo $idOrden; ?>')"><i class="fas fa-eye"></i> Visualizar HT</button>
                                                    </div>
                                                    <?php
                                                    break;

                                                default:
                                                    // Más de una OT, mostrar botón para abrir modal
                                                    ?>
                                                    <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalListadoOT">
                                                        Visualizar HTs (<?= $cantidadHTFinal ?>)
                                                    </button>
                                                    <?php
                                                    break;
                                            }

                                            ?>
                                        </div>

                                        <?php
                                    }

                                    ?>


                                    <div class="modal fade" id="modalListadoOT" tabindex="-1" aria-labelledby="modalListadoOTLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalListadoOTLabel">Hojas T&eacute;cnicas Asociadas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php while ($fila = mysqli_fetch_assoc($resHTFinal)) {
                                                        $idOrden = $fila['idOrden']; ?>
                                                        <button class="btn btn-outline-primary w-100 mb-2" onclick="abrirHTSoporte(<?= $idOrden ?>)">
                                                            <i class="fas fa-eye"></i> Abrir HT #<?= $idOrden ?>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-12">

                                        <h5 style="text-align:justify">
                                            <b>Detalle Solicitud: </b>
                                            <br><br>
                                            <textarea id="detalleSolicitud" readonly style="resize:none; background-color:white;" name="detalleSolicitud" class="form-control" rows="12" placeholder="Detalle del Problema que Presenta..."><?php echo $problemaSolicitud; ?></textarea>

                                        </h5>
                                    </div>
                                </div>


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
    $(function () {
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
<!-- DARK MODE-->
<script src="js/dark-mode.js"></script>
<script>
    function cambiarTipo() {
        const boton = document.querySelector('button[id="asignarGuardar"]');
        boton.type = 'button';  // Cambia el tipo del botón a "button"
        boton.textContent = 'Enviando...';  // Cambia el texto del botón
        boton.disabled = true;  // Deshabilita el botón para evitar envíos repetidos

        // // Simula un tiempo de carga antes de enviar el formulario
        // setTimeout(function() {
        //     document.getElementById('soporteAgregar').submit();  // Envía el formulario
        // }, 3000); // Simula 3 segundos de tiempo de carga


        document.getElementById('asignarTecnico').submit();  // Envía el formulario
    }
</script>
<script>
    function abrirHTSoporte(idOrden) {
        // Crear el formulario dinámicamente
        var form = document.createElement("form");
        form.style.margin = "0";
        form.style.textAlign = "center";
        form.target = "_blank";
        form.action = "../sdo-pdf/SDO-OrdenTrabajoGeneral.php";
        form.method = "post";

        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "idOrden";
        input.value = idOrden;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
</body>

</html>