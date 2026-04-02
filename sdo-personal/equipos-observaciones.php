<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idEquipo = '';
$nomEquipo = '';
$forzadoEquipo = 0;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idEquipo'])) {
    $idEquipo = trim($_POST['idEquipo']);

    if (!empty($idEquipo) && $idEquipo > 0) {
        $nomEquipo = getNomEquipo($idEquipo);
        $forzadoEquipo = getStatusForzado($idEquipo);
        $forzadoEncendido = getStatusForzadoEncendido($idEquipo);
        $forzadoApagado = getStatusForzadoApagado($idEquipo);
        $forzadoVariable = getStatusForzadoVariables($idEquipo);
        $modoAutomatico = getStatusAutomatico($idEquipo);

        $_SESSION['idEquipo'] = $idEquipo;
        $getSubCategoriaEquipo = getSubCategoriaEquipo($idEquipo);

        $_SESSION['idSubCategoria'] = $getSubCategoriaEquipo;
    } else {
        $nomEquipo = '';
        $forzadoEquipo = 0;
        $forzadoEncendido = 0;
        $forzadoApagado = 0;
        $forzadoVariable = 0;
        $modoAutomatico = 0;
    }
} else if (isset($_SESSION['idEquipo'])) {
    $idEquipo = trim($_SESSION['idEquipo']);

    if (!empty($idEquipo) && $idEquipo > 0) {
        $nomEquipo = getNomEquipo($idEquipo);
        $forzadoEquipo = getStatusForzado($idEquipo);
        $forzadoEncendido = getStatusForzadoEncendido($idEquipo);
        $forzadoApagado = getStatusForzadoApagado($idEquipo);
        $forzadoVariable = getStatusForzadoVariables($idEquipo);
        $modoAutomatico = getStatusAutomatico($idEquipo);
        $getSubCategoriaEquipo = getSubCategoriaEquipo($idEquipo);

        $_SESSION['idSubCategoria'] = $getSubCategoriaEquipo;
    } else {
        $nomEquipo = '';
        $forzadoEquipo = 0;
        $forzadoEncendido = 0;
        $forzadoApagado = 0;
        $forzadoVariable = 0;
        $modoAutomatico = 0;
    }
} else {
    header('Location: index.php?errorEquipo=true');
}

date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando: <?php echo $nomEquipo; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
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
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><b>Visualizando Equipo: </b><?php echo $nomEquipo; ?></h4>
                                <br>

                                <?php

                                if ($forzadoEncendido == 1) {
                                    ?>
                                    <h4>
                                        <b>Situaci&oacute;n de Equipo: </b>Equipo se encuentra en Modo: <b>Forzado Encendido.</b>
                                    </h4>
                                    <?php
                                } else if ($forzadoApagado == 1) {
                                    ?>
                                    <h4>
                                        <b>Situaci&oacute;n de Equipo: </b>Equipo se encuentra en Modo: <b>Forzado Apagado.</b>
                                    </h4>
                                    <?php
                                } else if ($modoAutomatico == 1) {
                                    ?>
                                    <h4>
                                        <b>Situaci&oacute;n de Equipo: </b>Equipo se encuentra en Modo: <b>Automatico.</b>
                                    </h4>
                                    <?php
                                } else if ($forzadoVariable == 1) {
                                    ?>
                                    <h4>
                                        <b>Situaci&oacute;n de Equipo: </b>Equipo se encuentra con <b>Variables Forzadas</b>
                                    </h4>
                                    <?php
                                } else {
                                    ?>
                                    <h4>
                                        <b>Situaci&oacute;n de Equipo: </b>Equipo se encuentra en Modo: <b>Automatico.</b>
                                    </h4>
                                    <?php
                                }

                                ?>


                                <a href="sistemas-visualizar.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body" style="text-align:center;">

                                                <div class="row text-center" style="display: flex; justify-content:center;">
                                                    <!-- FORZAR ENCENDIDO -->
                                                    <div class="col-lg-2 col-12">
                                                        <?php

                                                        if ($forzadoEncendido == 0) {
                                                            ?>
                                                            <form method="post" action="../sdo-funciones/equipos-forzadoEncendido.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalForzarEncendido" id="inputMozi" class="btn btn-success btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Encendido</button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalForzarEncendido" name="modalForzarEncendido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Encendido de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzar Encendido: <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzar Encendido de Equipo: <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Encendido de Equipo..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="encendidoEquipo" id="encendidoEquipo" value="Encender">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Forzar Encendido Equipo</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <?php
                                                        } else if ($forzadoEncendido == 1) {
                                                            ?>
                                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-toggle-on"></i>&nbsp;&nbsp;Forzado - Encendido</button>

                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                    <!-- FIN FORZAR ENCENDIDO -->

                                                    <!-- FORZAR APAGADO -->
                                                    <div class="col-lg-2 col-12">
                                                        <?php

                                                        if ($forzadoApagado == 0) {
                                                            ?>
                                                            <form method="post" action="../sdo-funciones/equipos-forzadoApagado.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalForzarApagado" id="inputMozi" class="btn bg-pink btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Apagado</button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalForzarApagado" name="modalForzarApagado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Apagado de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzar Apagado: <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzar Apagado de Equipo: <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Apagado de Equipo..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="apagadoEquipo" id="apagadoEquipo" value="Apagar">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Forzar Apagado Equipo</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <?php
                                                        } else if ($forzadoApagado == 1) {
                                                            ?>
                                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-toggle-on"></i>&nbsp;&nbsp;Forzado - Apagado</button>

                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                    <!-- FIN FORZAR APAGADO -->

                                                    <!-- MODO AUTOMATICO -->
                                                    <div class="col-lg-2 col-12">
                                                        <?php

                                                        if ($modoAutomatico == 0) {
                                                            ?>
                                                            <form method="post" action="../sdo-funciones/equipos-forzarAutomatico.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalAutomatico" id="inputMozi" class="btn btn-info btn-block" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Modo Automatico</button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalAutomatico" name="modalAutomatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Modo Automatico de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Modo Automatico: <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Modo Automatico: <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Modo Automatico..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="modoAutomatico" id="modoAutomatico" value="Automatico">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            <?php
                                                        } else if ($modoAutomatico == 1) {
                                                            ?>
                                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Modo Automatico Activado</button>

                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                    <!-- FIN MODO AUTOMATICO -->

                                                    <!-- FORZAR VARIABLES -->
                                                    <div class="col-lg-2 col-12">
                                                        <?php

                                                        if ($forzadoVariable == 0) {
                                                            ?>
                                                            <form method="post" action="../sdo-funciones/equipos-forzadoVariable.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalVariables" id="inputMozi" class="btn bg-yellow btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Variables</button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalVariables" name="modalVariables" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Variables de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Forzado de Variables: <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Razones de Forzado de Variables: <code>*</code></label>
                                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzado de Variables..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="forzadoVariable" id="forzadoVariable" value="Variables">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Forzar Variables</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            <?php
                                                        } else if ($forzadoVariable == 1) {
                                                            ?>
                                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-yellow btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Variables</button>

                                                            <?php
                                                        }

                                                        ?>

                                                    </div>
                                                    <!-- FIN FORZAR VARIABLES -->

                                                    <!-- AGREGAR OBSERVACIÓN -->
                                                    <div class="col-lg-2 col-12">
                                                        <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                                            <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn bg-navy btn-block"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                            </div>
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="exampleInputBorder">Fecha de Observacion: <code>*</code></label>
                                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                            </div>
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="exampleInputBorder">Observacion: <code>*</code></label>
                                                                                <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="12" placeholder="Observacion..."></textarea>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                    <!-- FIN AGREGAR OBSERVACIÓN -->

                                                    <?php

                                                    $verificarEsPabellon = verificarEsPabellon($idEquipo);
                                                    if ($verificarEsPabellon == 1) {
                                                        ?>

                                                        <!-- CAMBIAR SET POINT TEMPERATURA -->
                                                        <div class="col-lg-2 col-12">
                                                            <form method="post" action="../sdo-funciones/pabellones_cambiarSetPoint.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalCambiarSP" id="inputMozi" class="btn btn-block bg-indigo"><i class="fa fa-pen"></i>&nbsp;&nbsp;Cambiar Set-Point T°</button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalCambiarSP" name="modalCambiarSP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Cambiar Set-Point de Temperatura</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Fecha de Observacion: <code>*</code></label>
                                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Nuevo Set-Point de Temperatura: <code>*</code></label>
                                                                                    <label for="exampleInputBorder" style="color: red">Para Decimales debe agregar una coma ' , '</label>
                                                                                    <input style="text-align:left;" type="number" style="text-align:center;" class="form-control" min="0" step=".1" id="nuevoValorSP" name="nuevoValorSP" required>
                                                                                </div>
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">Observacion: <code>*</code></label>
                                                                                    <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="8" placeholder="Observacion..."></textarea>

                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                        <!-- FIN CAMBIAR SET POINT TEMPERATURA -->
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div>

                                        </div>

                                        <span>
                                                <?php
                                                if (isset($_GET['exitoForzarEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Forzado de Equipo Activado con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['exitoQuitarForzarEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Forzado de Equipo Desactivado con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['errorForzarEquipoSinObservacion'])) {
                                                    echo '<br><div class="alert alert-warning alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Registrar Observaci&oacute;n de Equipo.</h5></div>';
                                                }

                                                if (isset($_GET['errorQuitarForzarEquipoSinObservacion'])) {
                                                    echo '<br><div class="alert alert-warning alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Desactivar el Forzado del Equipo. No se ha registrado Observaci&oacute;n</h5></div>';
                                                }

                                                if (isset($_GET['errorEstadoForzarEquipo'])) {
                                                    echo '<br><div class="alert alert-warning alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Estado de Forzado de Equipo. Reintente.</h5></div>';
                                                }

                                                // AVISOS - EXITO FORZADOS

                                                if (isset($_GET['exitoForzarEncendidoEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;<b>Forzado Encendido </b> de Equipo Activado con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['exitoForzarApagadoEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;<b>Forzado Apagado </b> de Equipo Activado con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['exitoForzarVariablesEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;<b>Forzado de Variables </b> de Equipo Activado con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['exitoForzarAutomaticoEquipo'])) {
                                                    echo '<br><div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;<b>Modo Automatico </b> de Equipo Activado con &Eacute;xito.</h5></div>';
                                                }

                                                // AVISOS - EXITO FORZADOS

                                                // AVISOS - ERROR FORZADOS

                                                if (isset($_GET['errorForzarEncendidoEquipo'])) {
                                                    echo '<br><div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Forzar Encendido de Equipo. Reintente.</h5></div>';
                                                }

                                                if (isset($_GET['errorForzarApagadoEquipo'])) {
                                                    echo '<br><div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Forzar Apagado de Equipo. Reintente.</h5></div>';
                                                }

                                                if (isset($_GET['errorForzarVariablesEquipo'])) {
                                                    echo '<br><div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Forzar Variables de Equipo. Reintente.</h5></div>';
                                                }

                                                if (isset($_GET['errorForzarAutomaticoEquipo'])) {
                                                    echo '<br><div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Estado de Equipo. Reintente.</h5></div>';
                                                }

                                                // AVISOS - ERROR FORZADOS

                                                //AVISOS AGREGAR
                                                if (isset($_GET['exitoAgregarObservacion'])) {
                                                    echo '<div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observaci&oacute;n Creada con &Eacute;xito.</h5></div>';
                                                }

                                                if (isset($_GET['errorAgregarObservacion'])) {
                                                    echo '<div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Observaci&oacute;n. Reintente.</h5></div>';
                                                }
                                                ?>
                                            </span>

                                        <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                        <ul>
                                            <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido</li>

                                            <li><i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado</li>

                                            <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables</li>

                                            <li><i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado</li>

                                            <li><i style="color: #138496; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico</li>

                                            <li><i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal</li>
                                        </ul>
                                    </div>
                                </div>
                                <table id="example2" style="border: 1px solid" class="table table-bordered">
                                    <thead class="">
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>N°</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha Observaciones:</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Nombre de Quien Registra:
                                                <center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Observaciones:
                                                <center>
                                        </th>
                                        <?php
                                        $verificarEsPabellon = verificarEsPabellon($idEquipo);

                                        if ($verificarEsPabellon == 1) {
                                            ?>
                                            <th nowrap="nowrap" style="vertical-align:middle;">
                                                <center>Set-Point de T°:
                                                    <center>
                                            </th>
                                            <?php
                                        }
                                        ?>
                                        <th style="vertical-align:middle;">
                                            <center>Tipo de Observaci&oacute;n:
                                                <center>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT * FROM equipos_observaciones WHERE idEquipo = '" . $idEquipo . "' ORDER BY fechaObservaciones DESC";

                                    $res = mysqli_query($conexion, $query);

                                    $numObservacion = 1;

                                    while ($row = $res->fetch_assoc()) {
                                        ?>

                                        <?php

                                        $classObservacion = "";

                                        if ($row['esForzado'] == "Forzado - Encendido") {

                                            $classObservacion = "bg-success";
                                        } else if ($row['esForzado'] == "Forzado - Apagado") {

                                            $classObservacion = "bg-pink ";
                                        } else if ($row['esForzado'] == "Forzado - Variables") {

                                            $classObservacion = "bg-yellow";
                                        } else if ($row['esForzado'] == "Modo Automatico") {

                                            $classObservacion = "bg-info";
                                        } else if ($row['esForzado'] == "Observación Normal") {

                                            $classObservacion = "bg-navy";
                                        } else if ($row['esForzado'] == "Forzado") {

                                            $classObservacion = "bg-gray";
                                        } else if ($row['esForzado'] == "Cambio de Set Point") {

                                            $classObservacion = "bg-indigo";
                                        } else {

                                            $classObservacion = "bg-light";
                                        }
                                        ?>

                                        <tr class="<?php echo $classObservacion; ?>">
                                            <td style="vertical-align:middle;">
                                                <center>
                                                    <?php echo $numObservacion; ?>
                                                </center>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <center>
                                                    <?php if ($row['fechaObservaciones'] == 0) {
                                                        echo "No registra fecha";
                                                    } else {
                                                        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                        $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                        echo $inicioFecha . '&nbsp;&nbsp;' . $inicioHora;
                                                    } ?>
                                                </center>
                                            </td>
                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                <center>
                                                    <?php
                                                    $personalObservacion = $row['personalObservacion'];

                                                    $partes = explode(' (', $personalObservacion, 2);
                                                    $nombre = $partes[0];
                                                    $profesion = rtrim($partes[1], ')');

                                                    echo $nombre . '<br>(' . $profesion . ')';
                                                    ?>
                                                </center>
                                            </td>
                                            <td style="vertical-align:middle; text-align:justify;">
                                                <?php echo nl2br($row['detalleObservaciones']); ?>
                                            </td>
                                            <?php
                                            $verificarEsPabellon = verificarEsPabellon($idEquipo);

                                            if ($verificarEsPabellon == 1) {
                                                ?>
                                                <td nowrap="nowrap" style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $porcentajeObservacion = $row['porcentajeObservacion'];

                                                        if ($porcentajeObservacion > 0) {
                                                            echo $porcentajeObservacion;
                                                        } else {
                                                            echo 'No Aplica';
                                                        }

                                                        ?>
                                                    </center>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                            <td style="vertical-align:middle; text-align:center;">
                                                <?php echo $row['esForzado']; ?>
                                            </td>
                                        </tr>

                                        <?php

                                        $numObservacion++;
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>N°</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha Observaciones:
                                                <center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Nombre de Quien Registra:
                                                <center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Observaciones:
                                                <center>
                                        </th>
                                        <?php
                                        $verificarEsPabellon = verificarEsPabellon($idEquipo);

                                        if ($verificarEsPabellon == 1) {
                                            ?>
                                            <th nowrap="nowrap" style="vertical-align:middle;">
                                                <center>Set-Point de T°:
                                                    <center>
                                            </th>
                                            <?php
                                        }
                                        ?>
                                        <th style="vertical-align:middle;">
                                            <center>Tipo de Observaci&oacute;n:
                                                <center>
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
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

<script src="js/dark-mode.js"></script>
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
            "buttons": ["Excel", "pdf"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": false,
            "info": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
</body>

</html>