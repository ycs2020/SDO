<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idEquipo = '';
$nomEquipo = '';
$forzadoEquipo = 0;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idEquipo']) || isset($_SESSION['idEquipo'])) {
    $idEquipo = isset($_POST['idEquipo']) ? trim($_POST['idEquipo']) : trim($_SESSION['idEquipo']);

    if (!empty($idEquipo) && $idEquipo > 0) {
        $nomEquipo = getNomEquipo($idEquipo);
        $forzadoEquipo = getStatusForzado($idEquipo);
        $forzadoEncendido = getStatusForzadoEncendido($idEquipo);
        $forzadoApagado = getStatusForzadoApagado($idEquipo);
        $forzadoVariable = getStatusForzadoVariables($idEquipo);
        $modoAutomatico = getStatusAutomatico($idEquipo);
        $getSubCategoriaEquipo = getSubCategoriaEquipo($idEquipo);

        $_SESSION['idEquipo'] = $idEquipo;
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
    exit();
}


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$verificarEsPabellon = verificarEsPabellon($idEquipo);

if ($verificarEsPabellon == 1) {
    $nomEquipoCompleto = cambiarNomPabellon($idEquipo);
} else {
    $nomEquipoCompleto = $nomEquipo;
}
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
    include '../sdo-templates/jefes-sidebar.php';
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

                                <a href="sistemas-visualizar.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="../dist/img/controlCent-HBQP.png" alt="User profile picture">
                                </div>
                                <br>
                                <h3 class="profile-username text-center"><?php echo $nomEquipoCompleto ?></h3>
                                <br>

                                <?php
                                $modos = [
                                    'forzadoEncendido' => 'Forzado Encendido',
                                    'forzadoApagado' => 'Forzado Apagado',
                                    'modoAutomatico' => 'Automático',
                                    'forzadoVariable' => 'Forzado Variables'
                                ];

                                $modoActual = 'Automatico'; // Valor predeterminado

                                foreach ($modos as $variable => $descripcion) {
                                    if ($$variable == 1) {
                                        $modoActual = $descripcion;
                                        break;
                                    }
                                }
                                ?>

                                <ul class="list-group list-group-unbordered mb-3">

                                    <li class="list-group-item">
                                        <b>Estado: </b> <a class="float-right font-weight-bold"><?php echo $modoActual; ?></a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /.card-body -->
                        </div>


                    </div>
                    <!-- /.col -->

                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body card-profile">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item text-center font-weight-bold">
                                        <b>Acciones: </b>
                                    </li>

                                    <?php


                                    $acciones = [
                                        ['estado' => $forzadoEncendido, 'accion' => 'encendido', 'btnClass' => 'success', 'titulo' => 'Forzar Encendido', 'archivo' => 'equipos-forzadoEncendido.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzado - Encendido'],
                                        ['estado' => $forzadoApagado, 'accion' => 'apagado', 'btnClass' => 'pink', 'titulo' => 'Forzar Apagado', 'archivo' => 'equipos-forzadoApagado.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzado - Apagado'],
                                        ['estado' => $modoAutomatico, 'accion' => 'automatico', 'btnClass' => 'info', 'titulo' => 'Modo Automatico', 'archivo' => 'equipos-forzarAutomatico.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Modo Automatico Activado'],
                                        ['estado' => $forzadoVariable, 'accion' => 'variables', 'btnClass' => 'yellow', 'titulo' => 'Forzar Variables', 'archivo' => 'equipos-forzadoVariable.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzar Variables'],
                                    ];

                                    foreach ($acciones as $accion) {
                                        $estado = $accion['estado'];
                                        $btnClass = $accion['btnClass'];
                                        $titulo = $accion['titulo'];
                                        $archivo = $accion['archivo'];
                                        $iconOn = $accion['iconOn'];
                                        $iconOff = $accion['iconOff'];
                                        $msg = $accion['msg'];
                                        $pasa = 'no';

                                        if ($titulo == 'Forzar Variables') {

                                            if (strpos($nomEquipo, "UMA") === false) {
                                                $pasa = 'si';
                                            }
                                        }

                                        if ($pasa == 'no') {
                                            ?>
                                            <li class="list-group-item">
                                                <?php
                                                if ($estado == 0) {
                                                    ?>
                                                    <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block"><i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?></button>


                                                    <?php
                                                } else {

                                                    if ($titulo == 'Forzar Variables') {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block"><i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?></button>


                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-<?php echo $iconOn; ?>"></i>&nbsp;&nbsp;<?php echo $msg; ?></button>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                        <div class="modal fade" id="modal<?php echo ucfirst($accion['accion']) ?>" name="modal<?php echo ucfirst($accion['accion']) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-<?php echo $btnClass; ?>">
                                                        <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $titulo ?> de Equipo: </b><?php echo $nomEquipo ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form method="post" action="../sdo-funciones/<?php echo $archivo; ?>">
                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control" id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha de <?php echo $titulo ?>: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control" id="addFecha" name="addFecha" value="<?php echo $fecha ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Razones de <?php echo $titulo ?>: <code>*</code></label>
                                                                <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de <?php echo $titulo ?>..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="<?php echo $accion['accion'] ?>Equipo" id="<?php echo $accion['accion'] ?>Equipo" value="<?php echo ucfirst($accion['accion']) ?>">
                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                            <button type="submit" class="btn btn-primary"><?php echo $titulo ?> Equipo</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }


                                    if ($verificarEsPabellon == 1) {
                                        ?>

                                        <li class="list-group-item">
                                            <form method="post" action="../sdo-funciones/pabellones_cambiarSetPoint.php">
                                                <button type="button" data-toggle="modal" data-target="#modalCambiarSP" id="inputMozi" class="btn btn-block bg-indigo"><i class="fa fa-pen"></i>&nbsp;&nbsp;Cambiar Set-Point T°</button>

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
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </li>
                                        <?php
                                    }
                                    ?>

                                    <li class="list-group-item">
                                        <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn bg-navy btn-block" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

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
                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoJefe . ' de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body card-profile">
                                <ul class="list-group list-group-unbordered mb-3">

                                    <li class="list-group-item text-center">
                                        <b>Colores para identificar</b>
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #138496; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal
                                    </li>
                                    <li class="list-group-item">
                                        <i style="color: #6610f2; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Cambio de Set-Point T°
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body card-profile">

                                <br>
                                <div class="table-responsive">
                                    <table id="example2" style="border: 1px solid; font-size: 11pt" class="table table-bordered">
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
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
        </section>
    </div>
    <!-- /.container-fluid -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2022. Derechos Reservados.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                },
                "search": "Buscar:"
            }
        });
    });
</script>

</body>

</html>
