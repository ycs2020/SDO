<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');
//
//$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
//
//$ipCliente = $_SERVER['REMOTE_ADDR'];
//
//if ($ipCliente === $ipPermitida) {
//    $valorDesarrollo = 'Pasaste';
//} else {
//    header('Location: plataforma_mantenimiento.php');
//}

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_GET['exitoGenerarPlanificador']) && isset($_GET['idEquipo'])) {
    $idEquipo = $_GET['idEquipo'];

}

if (isset($_POST['idEquipo']) || isset($_SESSION['idEquipo'])) {

    if (isset($_POST['idEquipo'])) {
        $idEquipo = $_POST['idEquipo'];
        $_SESSION['idEquipo'] = $idEquipo;
    } else if (isset($_SESSION['idEquipo'])) {
        $idEquipo = $_SESSION['idEquipo'];
    } else {
        header('Location: ../' . $rutaDireccion . '/carpeta-equipo.php?errorCargarEquipo=true');
    }

    //$_SESSION['idEquipo'] = $idEquipo;

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = " . $idEquipo;

    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {

        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
        $idEquipoPadre = $row['idEquipoPadre'];
        $rutaImagen = $row['rutaImagen'];
        $equipo_controla = $row['equipo_controla'];

        $equipoSource = $row['sourceAlarma'];
        $tipoMenu = $row['tipoMenu'];

        $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomCategoria'] = $row['nomCategoria'];
            $nomCategoria = $datos['nomCategoria'];
        }

        $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
            $cantidadHojasTec = $datos['cantidadHojasTec'];
        }

        $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
        $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

        while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
            $datosSolicitud = [];
            $datosSolicitud['total'] = $rowSolicitud['total'];
            $total = $datosSolicitud['total'];
        }

        $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
            $cantidadSubEquipos = $datos['cantidadSubEquipos'];
        }

        $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['gastoTotal'] = $row['gastoTotal'];
            $gastoTotal = $datos['gastoTotal'];
        }

        $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadArchivos'] = $row['cantidadArchivos'];
            $cantidadArchivos = $datos['cantidadArchivos'];
        }

        $sqlx = "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp";
        $resx = mysqli_query($conexion, $sqlx);
        while ($rowx = $resx->fetch_assoc()) {
            $ultimo_id = $rowx['ultimo_id'];
            $ultimo_id = $ultimo_id + 1;
        }

        $idGenerado = 'EQUIP-HBQP-' . $ultimo_id;

    }
}


$porcionesAntes = explode("ECB", $nombreEquipo);

$variableParaTablaAntes = 'ECB ' . $porcionesAntes[1];

$porcionesAntes2 = explode("_", $variableParaTablaAntes);

$variableParaTablaFinalAntes = $porcionesAntes2[0] . '-' . $porcionesAntes2[1];


//CONTAR DIGITAL INPUT Y ANALOG INPUT
$contardigitalinput = contardigitalinput($variableParaTablaFinalAntes);
$contaranaloginput = contaranaloginput($variableParaTablaFinalAntes);
//FIN DEL CONTEO INPUT

//CONTAR DIGITAL Y ANALOG OUTPUT
$contardigitaloutput = contardigitaloutput($variableParaTablaFinalAntes);

$contaranalogoutput = contaranalogoutput($variableParaTablaFinalAntes);
//FIN CONTEO OUTPUT
$contarambosinput = $contardigitalinput + $contaranaloginput;
$contarambosoutput = $contardigitaloutput + $contaranalogoutput;

$buscarequipoPadre = buscarequipoPadre($idEquipoPadre);

$contarsiguientes = 1;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipo - Administraci&oacute;n</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script>
        $(document).ready(function () {
            $(".show-agregar").click(function () {
                $("#agregarnuevodiv").show();
                $("#tablaequipos").hide();
                $("#divshowlistar").show();
                $("#divshowagregar").hide();
            });
            $(".show-listar").click(function () {
                $("#agregarnuevodiv").hide();
                $("#tablaequipos").show();
                $("#divshowlistar").hide();
                $("#divshowagregar").show();
            });
        });

    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';

    $messages = [
        'exitoEditarEquipo' => ['Exito!', 'Se guardo los cambios correctamente', 'success'],
        'errorEditarEquipo' => ['Error!', 'Se produjo un error al guardar los cambios', 'error'],
        'exitoHijos' => ['Exito!', 'Se agrego el sub-equipo exitosamente', 'success'],
        'errorHijos' => ['Error!', 'No fue posible agregar el sub equipo seleccionado', 'error'],
        'exitoCambioEstado' => ['Exito!', 'El cambio de estado del equipo resulto exitoso', 'success'],
        'errorCambioEstado' => ['Error!', 'Se genero un problema al cambiar el estado del equipo', 'error'],
        'exitoCambiarImagen' => ['Exito!', 'Cambio de Imagen Realizado', 'success'],
        'errorCambiarImagen' => ['Error!', 'Error al Cambiar Imagen.', 'error'],
        'exitoModificarSP' => ['Exito!', 'Cambio de Set-Point Realizado', 'success'],
        'errorModificarSP' => ['Error!', 'Error al Cambiar Set-Point.', 'error'],
        'errorModificarSPSinObservacion' => ['Error!', 'Error al Agregar Observacion de Cambio de Set-Point.', 'error'],
        'exitoForzarEncendidoEquipo' => ['Exito!', 'Forzado Encendido Realizado', 'success'],
        'errorForzarEncendidoEquipo' => ['Error!', 'Error al Forzar Encendido.', 'error'],
        'errorForzarEquipoSinObservacion' => ['Error!', 'Error al Agregar Observacion de Forzado.', 'error'],
        'exitoForzarApagadoEquipo' => ['Exito!', 'Forzado Apagado Realizado', 'success'],
        'errorForzarApagadoEquipo' => ['Error!', 'Error al Forzar Apagado.', 'error'],
        'exitoForzarAutomaticoEquipo' => ['Exito!', 'Modo Automatico Activado', 'success'],
        'errorForzarAutomaticoEquipo' => ['Error!', 'Error al Activar Modo Automatico.', 'error'],
        'exitoForzarVariablesEquipo' => ['Exito!', 'Variables Forzadas', 'success'],
        'errorForzarVariablesEquipo' => ['Error!', 'Error al Forzar Variables.', 'error'],
        'exitoAgregarObservacion' => ['Exito!', 'Observación Agregada.', 'success'],
        'errorAgregarObservacion' => ['Error!', 'Error al Agregar Observación.', 'error'],
    ];

    foreach ($messages as $param => list($title, $text, $icon)) {
        if (isset($_GET[$param])) {
            ?>
            <script>
                Swal.fire(
                    '<?php echo $title; ?>',
                    '<?php echo $text; ?>',
                    '<?php echo $icon; ?>'
                )
            </script>
            <?php
        }
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">

                    <!-- VOLVER ATRAS -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?></h1>
                                <?php
                                if ($idEquipoPadre > 0) {

                                    $tipoMenuEquipoPadre = obtenerTipoMenu($idEquipoPadre);

                                    if ($tipoMenuEquipoPadre == 'nuevo') {

                                        ?>
                                        <form method="post" action="equipo_menuNuevo.php" class="text-right">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoPadre; ?>"/>
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                        </form>
                                        <?php
                                    } else {

                                        ?>
                                        <form method="post" action="equipo_menuAntiguo.php" class="text-right">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoPadre; ?>"/>
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                        </form>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <form method="post" action="carpeta-equipo.php" class="text-right">
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                    </form>
                                    <?php
                                }

                                $nomDetalleEquipo = $nombreEquipo;

                                $nomDetalleCategoria = $nomCategoria;

                                $nomDetalleCompleto = $nomDetalleEquipo . ' - ' . $nomDetalleCategoria;

                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- FIN VOLVER ATRAS -->

                    <!-- EQUIPOS HIJOS -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary d-flex p-0">
                                <?php
                                $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo ORDER BY piso ASC, pma ASC, nombreEquipo ASC";
                                $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                if ($resEquipo && mysqli_affected_rows($conexion) > 0) {
                                    ?>
                                    <ul class="nav nav-pills mr-auto ml-auto p-2">
                                        <?php
                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                            $idEquipoSE = $rowEquipo['id'];
                                            $nombreEquipoEquiSE = $rowEquipo['nombreEquipo'];
                                            $nombreEquipoEquiSEEspacios = preg_replace('/\s+/', '', $nombreEquipoEquiSE);
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link text-white" href="#<?php echo $nombreEquipoEquiSEEspacios ?>" data-toggle="tab" onclick="loadEquipoData('<?php echo $idEquipoSE; ?>')">
                                                    <?php echo $nombreEquipoEquiSE; ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <?php
                                    mysqli_data_seek($resEquipo, 0); // Reset the result set to loop again
                                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                        $nombreEquipoEquiSE = $rowEquipo['nombreEquipo'];
                                        $nombreEquipoEquiSEEspacios = preg_replace('/\s+/', '', $nombreEquipoEquiSE);
                                        ?>
                                        <div class="tab-pane" id="<?php echo $nombreEquipoEquiSEEspacios ?>">
                                            <div id="equipoData<?php echo $rowEquipo['id']; ?>">
                                                <?php
                                                $idEquipoCarga = $rowEquipo['id'];

                                                if ($idEquipoCarga > 0) {
                                                    $sqlSubEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipoCarga";
                                                    $resultSubEquipo = mysqli_query($conexion, $sqlSubEquipo);

                                                    if ($resultSubEquipo && mysqli_num_rows($resultSubEquipo) > 0) {
                                                        $rowSubEquipo = mysqli_fetch_assoc($resultSubEquipo);

                                                        $idSubEquipo = $rowSubEquipo['id'];
                                                        $nombreSubEquipo = $rowSubEquipo['nombreEquipo'];
                                                        $identificadorSubEquipo = $rowSubEquipo['identificadorUnico'];
                                                        $equipo_controlaSubEquipo = $rowSubEquipo['equipo_controla'];
                                                        $sourceAlarmaSubEquipo = $rowSubEquipo['sourceAlarma'];
                                                        $numVista = 'Eq' . $idSubEquipo;
                                                        $estadoSubEquipo = $rowSubEquipo['estadoEquipo'];
                                                        $idEquipoPadreSubEquipo = $rowSubEquipo['idEquipoPadre'];
                                                        $idCategoriaSubEquipo = $rowSubEquipo['idCategoria'];
                                                        $colorSubEquipo = '';

                                                        ?>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-green d-flex p-0">
                                                                        <ul class="nav nav-pills mr-auto ml-auto p-2">
                                                                            <li class="nav-item">
                                                                                <a class="nav-link active text-white" href="#infoGeneral<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Informaci&oacute;n
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#modEquipos<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Modificar Equipo
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#subEquipos<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Sub-Equipos
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#documentacion<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Documentaci&oacute;n
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#planificacion<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Planificaci&oacute;n
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#hallazgos<?php echo $numVista; ?>" data-toggle="tab">
                                                                                    Hallazgos
                                                                                </a>
                                                                            </li>

                                                                            <?php
                                                                            if ($equipo_controlaSubEquipo > 0) {
                                                                                ?>
                                                                                <li class="nav-item"><a class="nav-link text-white" href="#sistemas<?php echo $numVista; ?>" data-toggle="tab">Observaciones Sistemas</a></li>
                                                                                <?php
                                                                            }


                                                                            if (!empty($sourceAlarmaSubEquipo)) {
                                                                                ?>
                                                                                <li class="nav-item"><a class="nav-link text-white" href="#alarmasBMS<?php echo $numVista; ?>" data-toggle="tab">Alarmas BMS<?php echo ' -- ' . $sourceAlarmaSubEquipo ?></a></li>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                            <li class="nav-item">
                                                                                <a class="nav-link text-white" href="#bms<?php echo $numVista; ?>" data-toggle="tab">BMS</a>
                                                                            </li>

                                                                        </ul>
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <div class="tab-content">
                                                                            <!-- INFORMACIÓN GENERAL -->
                                                                            <div class="tab-pane active" id="infoGeneral<?php echo $numVista; ?>">
                                                                                <div class="row">
                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body box-profile">
                                                                                                <h3 class="profile-username text-center"><?php echo $nombreSubEquipo; ?></h3>
                                                                                                <br>

                                                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                                                    <li class="list-group-item">
                                                                                                        <b>Identificador: </b><?php echo $identificadorSubEquipo; ?>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!-- /.card-body -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                                                                                        <div class="row">

                                                                                            <?php
                                                                                            if ($estadoSubEquipo == 'Operativo' || $estadoSubEquipo == 'operativo' || $estadoSubEquipo == 'OPERATIVO') {
                                                                                                $colorSubEquipo = 'bg-gradient-success';
                                                                                            } else if ($estadoSubEquipo == 'Mantenimiento') {
                                                                                                $colorSubEquipo = 'bg-gradient-warning';
                                                                                            } else if ($estadoSubEquipo == 'OperativoObs') {
                                                                                                $estadoSubEquipo = 'Operativo, Observacion';
                                                                                                $colorSubEquipo = 'bg-gradient-orange';
                                                                                            } else {
                                                                                                $colorSubEquipo = 'bg-gradient-danger';
                                                                                            }

                                                                                            ?>

                                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                                <button class="btn btn-block <?php echo $colorSubEquipo ?> btn-lg" style="font-size: medium" data-toggle="modal" data-target="#cambiarOperatividad<?php echo $idSubEquipo; ?>">
                                                                                                    <i class="fas fa-edit"></i> <br> Estado Actual: <b><?php echo $estadoSubEquipo ?></b>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                                <button class="btn btn-block btn-lg btn-primary" style="font-size: medium" data-toggle="modal" data-target="#observaciones<?php echo $idSubEquipo; ?>">
                                                                                                    <i class="fas fa-list"></i> <br>Observaciones del Equipo<br> </i>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                                <button class="btn btn-block btn-lg bg-purple" style="font-size: medium" data-toggle="modal" data-target="#tiempoFuncionamiento<?php echo $idSubEquipo; ?>">
                                                                                                    <i class="fas fa-clock"></i> <br> Tiempo de Funcionamiento<br> </i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- FIN INFORMACIÓN GENERAL -->

                                                                            <!-- MODIFICAR EQUIPO -->
                                                                            <div class="tab-pane " id="modEquipos<?php echo $numVista; ?>">
                                                                                <div class="col-12">
                                                                                    <form action="../sdo-funciones/guardar-edicion-equipo.php" method="post">
                                                                                        <input type="hidden" name="idEquipo" value="<?php echo $idSubEquipo ?>">
                                                                                        <br>
                                                                                        <fieldset>
                                                                                            <?php

                                                                                            $sqlModSubEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idSubEquipo";
                                                                                            $resultModSubEquipo = mysqli_query($conexion, $sqlModSubEquipo);

                                                                                            if ($resultModSubEquipo && mysqli_num_rows($resultModSubEquipo) > 0) {
                                                                                                $rowModSubEquipo = mysqli_fetch_assoc($resultModSubEquipo);

                                                                                                // Asignar a las variables
                                                                                                $subEquipoId = $rowModSubEquipo['id'];
                                                                                                $subEquipoFechaRegistro = $rowModSubEquipo['fechaRegistro'];
                                                                                                $subEquipoIdentificadorUnico = $rowModSubEquipo['identificadorUnico'];
                                                                                                $subEquipoNumeroInventario = $rowModSubEquipo['numeroInventario'];
                                                                                                $subEquipoFechaRecepcion = $rowModSubEquipo['fechaRecepcion'];
                                                                                                $subEquipoNombreEquipo = $rowModSubEquipo['nombreEquipo'];
                                                                                                $subEquipoMarca = $rowModSubEquipo['marca'];
                                                                                                $subEquipoModelo = $rowModSubEquipo['modelo'];
                                                                                                $subEquipoIdServicio = $rowModSubEquipo['idServicio'];
                                                                                                $subEquipoRecinto = $rowModSubEquipo['recinto'];
                                                                                                $subEquipoPma = $rowModSubEquipo['pma'];
                                                                                                $subEquipoCoordenadas = $rowModSubEquipo['coordenadas'];
                                                                                                $subEquipoFechaCompra = $rowModSubEquipo['fechaCompra'];
                                                                                                $subEquipoValorConIva = $rowModSubEquipo['valorConIva'];
                                                                                                $subEquipoIdLicitacion = $rowModSubEquipo['idLicitacion'];
                                                                                                $subEquipoOrdenCompra = $rowModSubEquipo['ordenCompra'];
                                                                                                $subEquipoFechaMarcha = $rowModSubEquipo['fechaMarcha'];
                                                                                                $subEquipoClase = $rowModSubEquipo['clase'];
                                                                                                $subEquipoSubclase = $rowModSubEquipo['subclase'];
                                                                                                $subEquipoComplejidadTec = $rowModSubEquipo['complejidadTec'];
                                                                                                $subEquipoNivelRiesgo = $rowModSubEquipo['nivelRiesgo'];
                                                                                                $subEquipoAhnoFrabricacion = $rowModSubEquipo['AhnoFrabricacion'];
                                                                                                $subEquipoVidaUtil = $rowModSubEquipo['vidaUtil'];
                                                                                                $subEquipoVersionSoftware = $rowModSubEquipo['versionSoftware'];
                                                                                                $subEquipoPaisOrigen = $rowModSubEquipo['paisOrigen'];
                                                                                                $subEquipoTerminoGarantia = $rowModSubEquipo['terminoGarantia'];
                                                                                                $subEquipoCostoMantAnual = $rowModSubEquipo['costoMantAnual'];
                                                                                                $subEquipoFrecuenciaMantenimiento = $rowModSubEquipo['frecuenciaMantenimiento'];
                                                                                                $subEquipoNumeroSerie = $rowModSubEquipo['numeroSerie'];
                                                                                                $subEquipoPiso = $rowModSubEquipo['piso'];
                                                                                                $subEquipoIdCategoria = $rowModSubEquipo['idCategoria'];
                                                                                                $subEquipoIdEquipoPadre = $rowModSubEquipo['idEquipoPadre'];
                                                                                                $subEquipoEstadoEquipo = $rowModSubEquipo['estadoEquipo'];
                                                                                                $subEquipoOrdenEquipo = $rowModSubEquipo['ordenEquipo'];
                                                                                                $subEquipoIdDepaPertenece = $rowModSubEquipo['idDepaPertenece'];
                                                                                                $subEquipoEjesECBoss = $rowModSubEquipo['ejesECBoss'];
                                                                                                $subEquipoEjesRack = $rowModSubEquipo['ejesRack'];
                                                                                                $subEquipoRack = $rowModSubEquipo['rack'];
                                                                                                $subEquipoPuerto = $rowModSubEquipo['puerto'];
                                                                                                $subEquipoDireccionIP = $rowModSubEquipo['direccionIP'];
                                                                                                $subEquipoDeviceId = $rowModSubEquipo['device_id'];
                                                                                                $subEquipoNetwork = $rowModSubEquipo['network'];
                                                                                                $subEquipoMacAddress = $rowModSubEquipo['mac_address'];
                                                                                                $subEquipoTcc = $rowModSubEquipo['tcc'];
                                                                                                $subEquipoEjesTCC = $rowModSubEquipo['ejesTCC'];
                                                                                                $subEquipoAlimentacionTCC = $rowModSubEquipo['alimentacionTCC'];
                                                                                                $subEquipoTipoControlador = $rowModSubEquipo['tipoControlador'];
                                                                                                $subEquipoRutaImagen = $rowModSubEquipo['rutaImagen'];
                                                                                                $subEquipoEquipoControla = $rowModSubEquipo['equipo_controla'];
                                                                                                $subEquipoSourceAlarma = $rowModSubEquipo['sourceAlarma'];
                                                                                                $subEquipoTipoMenu = $rowModSubEquipo['tipoMenu'];


                                                                                            }

                                                                                            ?>
                                                                                            <legend>Información Técnica</legend>


                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                                                                    <input type="text" class="form-control " required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $subEquipoIdentificadorUnico ?>" placeholder="Escriba el Identificador unico del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                                                                    <input type="text" class="form-control " id="inputInventario" name="inputInventario" value="<?php echo $subEquipoNumeroInventario ?>" placeholder="Escriba el numero de inventario del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                                                                    <input type="text" class="form-control " id="IDLicitacion" name="IDLicitacion" value="<?php echo $subEquipoIdLicitacion ?>" placeholder="Escriba el id de litación del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                                                                    <input type="text" class="form-control " id="ordenCompra" name="ordenCompra" value="<?php echo $subEquipoOrdenCompra ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                                                                    <input type="text" class="form-control " name="inputMantenimiento" value="<?php echo $subEquipoFrecuenciaMantenimiento ?>" id="inputMantenimiento">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                                                                    <input type="date" class="form-control " name="fechaRecepcion" value="<?php echo $subEquipoFechaRecepcion ?>" id="fechaRecepcion">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                                                                    <input type="date" class="form-control " name="fechaCompra" value="<?php echo $subEquipoFechaCompra ?>" id="fechaCompra" name="fechaCompra">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                                                                    <input type="date" class="form-control " name="fechaMarcha" value="<?php echo $subEquipoFechaMarcha ?>" id="fechaMarcha" name="fechaMarcha">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="clase" style="font-size: 12px;">Clase:</label>
                                                                                                    <input type="text" class="form-control " id="clase" value="<?php echo $subEquipoClase ?>" name="clase">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                                                                    <input type="text" class="form-control " id="subClase" value="<?php echo $subEquipoSubclase ?>" name="subClase">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                                                                    <input type="text" class="form-control " id="complejidad" value="<?php echo $subEquipoComplejidadTec ?>" name="complejidad">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                                                                    <input type="text" class="form-control " id="riesgo" value="<?php echo $subEquipoNivelRiesgo ?>" name="riesgo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                                                                    <input type="text" class="form-control " id="costoMantencion" value="<?php echo $subEquipoCostoMantAnual ?>" name="costoMantencion">
                                                                                                </div>
                                                                                                <?php
                                                                                                if ($subEquipoIdCategoria == 2) {
                                                                                                    ?>
                                                                                                    <div class="form-group col-md-3 col-12">
                                                                                                        <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                                                                        <select class="form-control  equipoQueControlaMod1" name="equipoQueControlaMod" style="width: 100%">
                                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                                            <?php
                                                                                                            $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                                                                            $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                                                                            $categoriasEquipos = array();
                                                                                                            if ($resCategoriasEquipos->num_rows > 0) {
                                                                                                                while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                                                                                    $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                                                                                    $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                                                                                    $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                                                                                    $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                                                                                    // Agregar el ID y el nombre de la subcategoría al array
                                                                                                                    $categoriasEquipos[] = array(
                                                                                                                        'id' => $idSubCatEquipo,
                                                                                                                        'nombre' => $nomSubCatEquipo,
                                                                                                                        'idPadre' => $getCategoriaSegunSubcategoria,
                                                                                                                        'nomPadre' => $nomCatEquipo
                                                                                                                    );
                                                                                                                }
                                                                                                            }

                                                                                                            usort($categoriasEquipos, function ($a, $b) {
                                                                                                                // Primero ordenar por nomPadre y luego por nombre
                                                                                                                $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                                                                                if ($cmp == 0) {
                                                                                                                    return strcmp($a['nombre'], $b['nombre']);
                                                                                                                }
                                                                                                                return $cmp;
                                                                                                            });


                                                                                                            // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                                                            foreach ($categoriasEquipos as $categoria) {
                                                                                                                if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                                                                                    echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                                                                                    $idCategoriaEquipo = $categoria['id'];
                                                                                                                    $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                                                                                    $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                                                                    $idSubCategoria = 0;
                                                                                                                    if ($resEquipoSistema->num_rows > 0) {
                                                                                                                        while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                                                                            if ($rowEquipoSistema['idEquipo'] == $subEquipoEquipoControla) {
                                                                                                                                echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                                                            } else {
                                                                                                                                echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                    echo '</optgroup>';
                                                                                                                }
                                                                                                            }

                                                                                                            ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-3 col-12">
                                                                                                        <label for="sourceAlarma" style="font-size: 12px;">Source de Alarmas:</label>
                                                                                                        <select class="form-control sourceAlarmaMod" name="sourceAlarmaMod" style="width: 100%">
                                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                                            <?php
                                                                                                            $sqlSourceAlarmas = "SELECT DISTINCT nameSource FROM bms_sourceAlarmas";
                                                                                                            $resSourceAlarmas = mysqli_query($conexion, $sqlSourceAlarmas);
                                                                                                            $dataSource = array();
                                                                                                            if ($resSourceAlarmas->num_rows > 0) {
                                                                                                                while ($rowSourceAlarmas = $resSourceAlarmas->fetch_assoc()) {
                                                                                                                    $nomSourceAlarmas = $rowSourceAlarmas['nameSource'];

                                                                                                                    // Agregar el ID y el nombre de la subcategoría al array
                                                                                                                    $dataSource[] = array(
                                                                                                                        'nombre' => $nomSourceAlarmas
                                                                                                                    );
                                                                                                                }
                                                                                                            }

                                                                                                            usort($dataSource, function ($a, $b) {
                                                                                                                // Primero ordenar por nomPadre y luego por nombre
                                                                                                                $cmp = strcmp($a['nombre'], $b['nombre']);
                                                                                                                return $cmp;
                                                                                                            });


                                                                                                            // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                                                            foreach ($dataSource as $data) {
                                                                                                                if (!empty($data['nombre'])) {
                                                                                                                    $nombre = $data['nombre'];
                                                                                                                    if ($nombre == $subEquipoSourceAlarma) {
                                                                                                                        echo '<option value="' . $nombre . '" selected>' . $nombre . '</option>';
                                                                                                                    } else {
                                                                                                                        echo '<option value="' . $nombre . '">' . $nombre . '</option>';
                                                                                                                    }
                                                                                                                }
                                                                                                            }

                                                                                                            ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="costoMantencion" style="font-size: 12px;">Tipo de Interfaz:</label>
                                                                                                    <select class="form-control " name="tipoInterfaz" style="width: 100%">
                                                                                                        <?php
                                                                                                        if ($tipoMenu == 'nuevo') {
                                                                                                            ?>
                                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                                            <option value="nuevo" selected>Interfaz Compacta</option>
                                                                                                            <option value="antiguo">Interfaz Detallada</option>
                                                                                                            <?php
                                                                                                        } else if ($tipoMenu == 'antiguo') {
                                                                                                            ?>
                                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                                            <option value="nuevo" >Interfaz Compacta</option>
                                                                                                            <option value="antiguo" selected>Interfaz Detallada</option>
                                                                                                            <?php
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <option value="Seleccione..." selected>Seleccione...</option>
                                                                                                            <option value="nuevo" >Interfaz Compacta</option>
                                                                                                            <option value="antiguo" >Interfaz Detallada</option>
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>

                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                        <hr>
                                                                                        <fieldset>
                                                                                            <legend>Información Básica</legend>

                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                                                                    <input type="text" class="form-control " required id="nombreEquipo" name="nombreEquipo" value="<?php echo $subEquipoNombreEquipo ?>" placeholder="Escriba el nombre del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                                                                    <input type="number" class="form-control " id="valorConIva" value="<?php echo $subEquipoValorConIva ?>" name="valorConIva">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                                                                    <input type="text" class="form-control " id="marcaEquipo" name="marcaEquipo" value="<?php echo $subEquipoMarca ?>" placeholder="Escriba la marca del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                                                                    <input type="text" class="form-control " id="modeloEquipo" name="modeloEquipo" value="<?php echo $subEquipoModelo ?>" placeholder="Escriba el modelo del equipo">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                                                                    <input type="text" class="form-control " id="fechaFabricación" value="<?php echo $subEquipoAhnoFrabricacion ?>" name="fechaFabricación">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                                                                    <input type="text" class="form-control " id="vidaUtil" value="<?php echo $subEquipoVidaUtil ?>" name="vidaUtil">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                                                                    <input type="text" class="form-control " id="versionSoftware" value="<?php echo $subEquipoVersionSoftware ?>" name="versionSoftware">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                                                                    <input type="text" class="form-control " id="paisOrigen" value="<?php echo $subEquipoPaisOrigen ?>" name="paisOrigen">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                                                                    <input type="text" class="form-control " id="terminoGarantia" value="<?php echo $subEquipoTerminoGarantia ?>" name="terminoGarantia">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                                                                    <input type="text" class="form-control " id="numSerie" value="<?php echo $subEquipoNumeroSerie ?>" name="numSerie">
                                                                                                </div>
                                                                                                <!--                                        --><?php
                                                                                                //                                        if ($tipoControlador == 'esclavo') {
                                                                                                //                                            ?>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                                                                    <input type="text" class="form-control " id="device" value="<?php echo $subEquipoDeviceId ?>" name="device">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Network:</label>
                                                                                                    <input type="text" class="form-control " id="network" value="<?php echo $subEquipoNetwork ?>" name="network">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Mac Address:</label>
                                                                                                    <input type="text" class="form-control " id="mac" value="<?php echo $subEquipoMacAddress ?>" name="mac">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">TCC:</label>
                                                                                                    <input type="text" class="form-control " id="tcc" value="<?php echo $subEquipoTcc ?>" name="tcc">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Ejes TCC:</label>
                                                                                                    <input type="text" class="form-control " id="ejestcc" value="<?php echo $subEquipoEjesTCC ?>" name="ejestcc">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="numSerie" style="font-size: 12px;">Alimentacion TCC:</label>
                                                                                                    <input type="text" class="form-control " id="alimentaciontcc" value="<?php echo $subEquipoAlimentacionTCC ?>" name="alimentaciontcc">
                                                                                                </div>
                                                                                                <!---->
                                                                                                <!--                                            --><?php
                                                                                                //                                        }
                                                                                                //                                        if ($tipoControlador == 'maestro') {
                                                                                                //                                            ?>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="ejesECBOSS" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                                                                    <input type="text" class="form-control " id="ejesECBOSS" value="<?php echo $subEquipoEjesECBoss ?>" name="ejesECBOSS">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="ejesRack" style="font-size: 12px;">Ejes Rack:</label>
                                                                                                    <input type="text" class="form-control " id="ejesRack" value="<?php echo $subEquipoEjesRack ?>" name="ejesRack">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="rack" style="font-size: 12px;">Rack:</label>
                                                                                                    <input type="text" class="form-control " id="rack" value="<?php echo $subEquipoRack ?>" name="rack">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="puerto" style="font-size: 12px;">Puerto:</label>
                                                                                                    <input type="text" class="form-control " id="puerto" value="<?php echo $subEquipoPuerto ?>" name="puerto">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="direccionIP" style="font-size: 12px;">Direccion IP:</label>
                                                                                                    <input type="text" class="form-control " id="direccionIP" value="<?php echo $subEquipoDireccionIP ?>" name="direccionIP">
                                                                                                </div>
                                                                                                <!--                                            --><?php
                                                                                                //                                        }
                                                                                                //
                                                                                                //                                        ?>

                                                                                            </div>
                                                                                        </fieldset>
                                                                                        <hr>
                                                                                        <fieldset>
                                                                                            <legend>Ubicación</legend>
                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                                                                    <select class="form-control " id="servicio" required name="servicio">
                                                                                                        <option value="" disabled selected>-- Seleccione --</option>

                                                                                                        <?php

                                                                                                        $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                                                                        $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                                                        while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                                                            $datos = [];

                                                                                                            $datos['idServicio'] = $rowServicios['idServicio'];
                                                                                                            $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                                                            $idServicio = $datos['idServicio'];
                                                                                                            $nomServicio = $datos['nomServicio'];

                                                                                                            if ($idServicio == $subEquipoIdServicio) {
                                                                                                                ?>
                                                                                                                <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                                                                <?php
                                                                                                            } else {
                                                                                                                ?>
                                                                                                                <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                                                                <?php
                                                                                                            }
                                                                                                            ?>
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                                                                    <input type="text" class="form-control " id="recinto" name="recinto" value="<?php echo $subEquipoRecinto ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="pma" style="font-size: 12px;">PMA:</label>
                                                                                                    <input type="text" class="form-control " id="pma" name="pma" value="<?php echo $subEquipoPma ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                                                    <input type="text" class="form-control " id="coordenadas" name="coordenadas" value="<?php echo $subEquipoCoordenadas ?>">
                                                                                                </div>
                                                                                                <div class="form-group col-md-3 col-12">
                                                                                                    <label for="coordenadas" style="font-size: 12px;">Piso:</label>
                                                                                                    <input type="text" class="form-control " id="piso" name="piso" value="<?php echo $subEquipoPiso ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                        <hr>
                                                                                        <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $subEquipoTipoControlador ?>">
                                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre ?>">
                                                                                        <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN MODIFICAR EQUIPO -->

                                                                            <!-- SUB EQUIPOS EQUIPO -->
                                                                            <div class="tab-pane " id="subEquipos<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold"><?php echo 'Sub-Equipos Asociados a : ' . $nombreSubEquipo; ?></h4>


                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <br>
                                                                                        <div class="row">
                                                                                            <button class="btn bg-info text-center" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Asociar un nuevo sub-equipo</b></button>&nbsp;
                                                                                            <div id="divshowagregar">
                                                                                                <button class="btn bg-gradient-success text-center show-agregar" id="agregarnuevo"><i class="fas fa-plus"></i><b> Añadir un nuevo equipo y asociarlo como sub-equipo</b></button>
                                                                                            </div>
                                                                                            <div id="divshowlistar" style="display: none">
                                                                                                <button class="btn bg-gradient-success text-center show-listar" id="listarsub-equipos"><i class="fas fa-clipboard-list"></i><b> Listar sub-equipos</b></button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <hr>
                                                                                        <!-- Modal -->
                                                                                        <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                                            <div class="modal-dialog modal-lg">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header bg-info">
                                                                                                        <h5 class="modal-title" id="staticBackdropLabel">Seleccione el equipo a añadir</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <table class="table table-sm table-bordered table-hover" id="tablaSubequipos<?php echo $idSubEquipo ?>">
                                                                                                            <thead>
                                                                                                            <tr>
                                                                                                                <th scope="col" class="text-center">N°</th>
                                                                                                                <th scope="col" class="text-center">ID Unico</th>
                                                                                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                                                                                <th scope="col" class="text-center">Estado</th>
                                                                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                                                            </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                            <?php
                                                                                                            $sqlhijo = "SELECT * FROM equipamiento_hbqp WHERE (idEquipoPadre is null OR idEquipoPadre = 0) AND idCategoria = $idCategoria";
                                                                                                            $resHijo = mysqli_query($conexion, $sqlhijo);
                                                                                                            $num = 1;
                                                                                                            while ($rowHijo = $resHijo->fetch_assoc()) {
                                                                                                                $datos = [];
                                                                                                                $datos['id'] = $rowHijo['id'];
                                                                                                                $datos['identificadorUnico'] = $rowHijo['identificadorUnico'];
                                                                                                                $datos['nombreEquipo'] = $rowHijo['nombreEquipo'];
                                                                                                                $datos['estadoEquipo'] = $rowHijo['estadoEquipo'];
                                                                                                                $idAsociar = $datos['id'];
                                                                                                                $identificadorUnicoAsociar = $datos['identificadorUnico'];
                                                                                                                $nombreEquipoAsociar = $datos['nombreEquipo'];
                                                                                                                $estadoEquipoAsociar = $datos['estadoEquipo'];
                                                                                                                ?>
                                                                                                                <tr>
                                                                                                                    <td class="text-center"><?php echo $num ?></td>
                                                                                                                    <td class="text-center"><?php echo $identificadorUnicoAsociar ?></td>
                                                                                                                    <td class="text-center"><?php echo $nombreEquipoAsociar ?></td>
                                                                                                                    <td class="text-center"><?php echo $estadoEquipoAsociar ?></td>
                                                                                                                    <td>
                                                                                                                        <form action="../sdo-funciones/agregarEquipoHijo.php" method="POST">
                                                                                                                            <input type="hidden" name="idEquipoPadre" value="<?php echo $idSubEquipo ?>">
                                                                                                                            <input type="hidden" name="isMenuHijo" value="hijo">
                                                                                                                            <input type="hidden" name="idEquipoHijo" value="<?php echo $idAsociar ?>">
                                                                                                                            <input type="submit" value="Añadir Equipo" class="btn bg-danger">
                                                                                                                        </form>
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
                                                                                                                <th scope="col" class="text-center">ID Unico</th>
                                                                                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                                                                                <th scope="col" class="text-center">Estado</th>
                                                                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                                                            </tr>
                                                                                                            </tfoot>
                                                                                                        </table>

                                                                                                        <script>
                                                                                                            $(document).ready(function () {

                                                                                                                $('#tablaSubequipos<?php echo $idSubEquipo ?>').DataTable({
                                                                                                                    "paging": true,
                                                                                                                    "iDisplayLength": 15,
                                                                                                                    "lengthChange": false,
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
                                                                                                                    "createdRow": function (row, data, dataIndex) {
                                                                                                                        $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                                        $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                                        $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

                                                                                                                    }
                                                                                                                });
                                                                                                            });
                                                                                                        </script>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                                        <button type="button" class="btn btn-primary">Guardar Equipo</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div id="agregarnuevodiv" style="display:none;">
                                                                                            <p class="text-bold text-danger">(*) Campos Obligatorios</p>
                                                                                            <form action="../sdo-funciones/guardar-equipo.php" method="post">
                                                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                                                                <input type="hidden" name="idEquipoPadre" value="<?php echo $idSubEquipo ?>">
                                                                                                <fieldset>
                                                                                                    <legend>Información Técnica</legend>
                                                                                                    <div class="form-row">
                                                                                                        <div class="form-group col-md-2 col-12">
                                                                                                            <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                                                                            <input type="text" class="form-control" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $idGenerado ?>" placeholder="Escriba el Identificador unico del equipo">
                                                                                                        </div>
                                                                                                        <div class="col-md-1">
                                                                                                            <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                                                                                <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID Único es generado aleatoriamente y es la identificación del equipo dentro del sistema, guarde este código." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                                                                            <input type="text" class="form-control" id="inputInventario" name="inputInventario" placeholder="Escriba el numero de inventario del equipo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                                                                            <input type="text" class="form-control" id="IDLicitacion" name="IDLicitacion" placeholder="Escriba el id de litación del equipo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                                                                            <input type="text" class="form-control" id="ordenCompra" name="ordenCompra">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                                                                            <input type="text" class="form-control" name="inputMantenimiento" id="inputMantenimiento">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                                                                            <input type="date" class="form-control" name="fechaRecepcion" id="fechaRecepcion">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                                                                            <input type="date" class="form-control" id="fechaCompra" name="fechaCompra">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                                                                            <input type="date" class="form-control" id="fechaMarcha" name="fechaMarcha">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="clase" style="font-size: 12px;">Clase:</label>
                                                                                                            <input type="text" class="form-control" id="clase" name="clase">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                                                                            <input type="text" class="form-control" id="subClase" name="subClase">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                                                                            <input type="text" class="form-control" id="complejidad" name="complejidad">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                                                                            <input type="text" class="form-control" id="riesgo" name="riesgo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                                                                            <input type="text" class="form-control" id="costoMantencion" name="costoMantencion">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                                                                            <select class="form-control equipoQueControlaAgregar" name="equipoQueControlaAgregar" style="width: 100%">
                                                                                                                <option value="Seleccione...">Seleccione...</option>
                                                                                                                <?php
                                                                                                                $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                                                                                $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                                                                                if ($resCategoriasEquipos->num_rows > 0) {
                                                                                                                    while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                                                                                        $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                                                                                        $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                                                                                        $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                                                                                        $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                                                                                        // Agregar el ID y el nombre de la subcategoría al array
                                                                                                                        $categoriasEquiposParaSubEquipo[] = array(
                                                                                                                            'id' => $idSubCatEquipo,
                                                                                                                            'nombre' => $nomSubCatEquipo,
                                                                                                                            'idPadre' => $getCategoriaSegunSubcategoria,
                                                                                                                            'nomPadre' => $nomCatEquipo
                                                                                                                        );
                                                                                                                    }
                                                                                                                }

                                                                                                                usort($categoriasEquiposParaSubEquipo, function ($a, $b) {
                                                                                                                    // Primero ordenar por nomPadre y luego por nombre
                                                                                                                    $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                                                                                    if ($cmp == 0) {
                                                                                                                        return strcmp($a['nombre'], $b['nombre']);
                                                                                                                    }
                                                                                                                    return $cmp;
                                                                                                                });


                                                                                                                // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                                                                foreach ($categoriasEquiposParaSubEquipo as $categoria) {
                                                                                                                    if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                                                                                        echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                                                                                        $idCategoriaEquipo = $categoria['id'];
                                                                                                                        $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                                                                                        $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                                                                        $idSubCategoria = 0;
                                                                                                                        if ($resEquipoSistema->num_rows > 0) {
                                                                                                                            while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                                                                                if ($rowEquipoSistema['idEquipo'] == $equipo_controla) {
                                                                                                                                    echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                                                                } else {
                                                                                                                                    echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                        echo '</optgroup>';
                                                                                                                    }
                                                                                                                }

                                                                                                                ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                                <hr>
                                                                                                <fieldset>
                                                                                                    <legend>Información Básica</legend>
                                                                                                    <div class="form-row">
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                                                                            <input type="text" class="form-control" required id="nombreEquipo" name="nombreEquipo" placeholder="Escriba el nombre del equipo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                                                                            <input type="number" class="form-control" id="valorConIva" name="valorConIva">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                                                                            <input type="text" class="form-control" id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                                                                            <input type="text" class="form-control" id="modeloEquipo" name="modeloEquipo" placeholder="Escriba el modelo del equipo">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                                                                            <input type="text" class="form-control" id="fechaFabricación" name="fechaFabricación">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                                                                            <input type="text" class="form-control" id="vidaUtil" name="vidaUtil">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                                                                            <input type="text" class="form-control" id="versionSoftware" name="versionSoftware">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                                                                            <input type="text" class="form-control" id="paisOrigen" name="paisOrigen">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                                                                            <input type="text" class="form-control" id="terminoGarantia" name="terminoGarantia">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                                                                            <input type="text" class="form-control" id="numSerie" name="numSerie">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                                <hr>
                                                                                                <fieldset>
                                                                                                    <legend>Ubicación</legend>
                                                                                                    <div class="form-row">
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                                                                            <select class="form-control" id="servicio" required name="servicio">
                                                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                                                <?php
                                                                                                                $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                                                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                                                                    $datos = [];

                                                                                                                    $datos['idServicio'] = $rowServicios['idServicio'];
                                                                                                                    $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                                                                    $idServicio = $datos['idServicio'];
                                                                                                                    $nomServicio = $datos['nomServicio'];

                                                                                                                    ?>

                                                                                                                    <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>

                                                                                                                    <?php
                                                                                                                }
                                                                                                                ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                                                                            <input type="text" class="form-control" id="recinto" name="recinto">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="pma" style="font-size: 12px;">PMA:</label>
                                                                                                            <input type="text" class="form-control" id="pma" name="pma">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                                                            <input type="text" class="form-control" id="coordenadas" name="coordenadas">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="coordenadas" style="font-size: 12px;">Piso: <code>*</code> <code>Coloque un 0 si no conoce el piso</code></label>
                                                                                                            <input type="text" required class="form-control" id="piso" name="piso">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-3 col-12">
                                                                                                            <label for="coordenadas" style="font-size: 12px;">Departamento al que pertenece: <code>*</code></label>
                                                                                                            <select name="idDepaCategoria" id="idDepaCategoria" class="form-control" required>
                                                                                                                <option value="selected">
                                                                                                                    Seleccione un departamento
                                                                                                                </option>
                                                                                                                <?php
                                                                                                                $sqlDepa = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') and nomDepartamento NOT LIKE ('%Informatica%') AND nomDepartamento NOT LIKE ('%Operacional%')";
                                                                                                                $resDepa = mysqli_query($conexion, $sqlDepa);
                                                                                                                while ($rowDepa = $resDepa->fetch_assoc()) {
                                                                                                                    $datos1 = [];
                                                                                                                    $datos1['nomDepartamento'] = $rowDepa['nomDepartamento'];
                                                                                                                    $datos1['idDepartamento'] = $rowDepa['idDepartamento'];
                                                                                                                    $idDepartamento1 = $datos1['idDepartamento'];
                                                                                                                    $nomDepartamento = $datos1['nomDepartamento'];

                                                                                                                    ?>
                                                                                                                    <option value="<?php echo $idDepartamento1 ?>">
                                                                                                                        <?php echo $nomDepartamento ?>
                                                                                                                    </option>
                                                                                                                    <?php
                                                                                                                }

                                                                                                                ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                                <hr>
                                                                                                <button type="submit" class="btn btn-success btn-block text-center"><b> Guardar Equipo</b></button>
                                                                                            </form>

                                                                                            <br>
                                                                                            <br>
                                                                                        </div>
                                                                                        <div id="tablaequipos" class="table-responsive">
                                                                                            <input type="hidden" name="nomTableSubEquipos" id="nomTableSubEquipos" value="<?php echo 'tableSE' . $idSubEquipo ?>">
                                                                                            <input type="hidden" name="" id="<?php echo 'idSubEquipoBuscarPost' . $idSubEquipo ?>" value="<?php echo $idSubEquipo ?>">
                                                                                            <table id="<?php echo 'tableSE' . $idSubEquipo ?>" class="table table-bordered text-center">
                                                                                                <thead class="bg-secondary">
                                                                                                <tr>
                                                                                                    <th>ID</th>
                                                                                                    <th>Nombre Equipo</th>
                                                                                                    <th>Recinto</th>
                                                                                                    <th>PMA</th>
                                                                                                    <th>Piso</th>
                                                                                                    <th>Estado</th>
                                                                                                    <th>Hoja de Vida</th>
                                                                                                    <th>Informaci&oacute;n</th>
                                                                                                    <th>Desvincular</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tfoot class="bg-secondary">
                                                                                                <tr>
                                                                                                    <th>ID</th>
                                                                                                    <th>Nombre Equipo</th>
                                                                                                    <th>Recinto</th>
                                                                                                    <th>PMA</th>
                                                                                                    <th>Piso</th>
                                                                                                    <th>Estado</th>
                                                                                                    <th>Hoja de Vida</th>
                                                                                                    <th>Informaci&oacute;n</th>
                                                                                                    <th>Desvincular</th>
                                                                                                </tr>
                                                                                                </tfoot>
                                                                                            </table>
                                                                                        </div>

                                                                                        <script>
                                                                                            $(document).ready(function () {

                                                                                                $('#tableSE<?php echo $idSubEquipo; ?>').DataTable({
                                                                                                    "paging": true,
                                                                                                    "iDisplayLength": 15,
                                                                                                    "lengthChange": false,
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
                                                                                                    "ajax": {
                                                                                                        "url": "sdo-includes/equipamiento_cargarSubEquipos.php", // Archivo PHP que contiene la consulta SQL
                                                                                                        "type": "POST",
                                                                                                        "data": function (d) {
                                                                                                            d.idSubEquipoBuscar = $('#<?php echo 'idSubEquipoBuscarPost' . $idSubEquipo ?>').val();
                                                                                                            console.log(d);
                                                                                                        },
                                                                                                        "dataSrc": function (json) {
                                                                                                            console.log("Response from server:", json);
                                                                                                            return json;
                                                                                                        },
                                                                                                        "error": function (xhr, error, thrown) {
                                                                                                            console.error("Error occurred during AJAX request:", error, thrown);
                                                                                                            console.log("XHR object:", xhr);
                                                                                                        }
                                                                                                    },
                                                                                                    "columns": [
                                                                                                        {
                                                                                                            "data": null,
                                                                                                            "render": function (data, type, row, meta) {
                                                                                                                return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                                                                            }
                                                                                                        },
                                                                                                        {"data": "nombreEquipo"},
                                                                                                        {"data": "recinto"},
                                                                                                        {"data": "pma"},
                                                                                                        {"data": "piso"},
                                                                                                        {"data": "estadoEquipo"},
                                                                                                        {
                                                                                                            "data": null,
                                                                                                            "render": function (data, type, row) {
                                                                                                                if (row.id > 0) {
                                                                                                                    return '<button class="btn btn-primary btn-hojaVida-sub" data-idequipoversub="' + row.id + '"><i class="fas fa-file"></i></button>';
                                                                                                                } else {
                                                                                                                    return 'Deshabilitado';
                                                                                                                }
                                                                                                            }
                                                                                                        },
                                                                                                        {
                                                                                                            "data": null,
                                                                                                            "render": function (data, type, row) {
                                                                                                                if (row.id > 0) {
                                                                                                                    return '<button class="btn btn-info btn-infoEquipo-sub" data-idequipoinfosub="' + row.id + '" data-idcategoriainfosub="' + row.idCategoria + '" data-tipomenusub="' + row.tipoMenu + '"><i class="fas fa-search"></i></button>';
                                                                                                                } else {
                                                                                                                    return 'Deshabilitado';
                                                                                                                }
                                                                                                            }
                                                                                                        },
                                                                                                        {
                                                                                                            "data": null,
                                                                                                            "render": function (data, type, row) {
                                                                                                                if (row.id > 0) {
                                                                                                                    return '<button class="btn btn-danger btn-eliminarEquipo-sub" data-ideliminarsub="' + row.id + '"><i class="fas fa-trash"></i></button>';
                                                                                                                } else {
                                                                                                                    return 'Deshabilitado';
                                                                                                                }
                                                                                                            }
                                                                                                        }


                                                                                                        // Agrega más columnas según sea necesario
                                                                                                    ],
                                                                                                    "createdRow": function (row, data, dataIndex) {
                                                                                                        $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                        $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                        $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

                                                                                                    }


                                                                                                });

                                                                                                $('#tableSE<?php echo $idSubEquipo; ?>').on('click', '.btn-hojaVida-sub', function () {
                                                                                                    var id = $(this).data('idequipoversub');

                                                                                                    var form = $('<form>', {
                                                                                                        'action': '../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                                                        'method': 'POST',
                                                                                                        'target': '_blank',
                                                                                                    });

                                                                                                    // Agregar un campo oculto para el ID
                                                                                                    form.append($('<input>', {
                                                                                                        'type': 'hidden',
                                                                                                        'name': 'idCarpeta',
                                                                                                        'value': id
                                                                                                    }));

                                                                                                    // Agregar el formulario al documento y enviarlo
                                                                                                    form.appendTo('body').submit();
                                                                                                });

                                                                                                $('#tableSE<?php echo $idSubEquipo; ?>').on('click', '.btn-infoEquipo-sub', function () {
                                                                                                    var idEquipo = $(this).data('idequipoinfosub');
                                                                                                    var idCategoria = $(this).data('idcategoriainfosub');
                                                                                                    var tipomenu = $(this).data('tipomenusub');
                                                                                                    console.log('idEquipo Capturado:', idEquipo); // Verifica si el ID está siendo capturado correctamente
                                                                                                    console.log('idCategoria Capturado:', idCategoria); // Verifica si el ID está siendo capturado correctamente
                                                                                                    console.log('tipoMenu Capturado:', tipomenu); // Verifica si el ID está siendo capturado correctamente

                                                                                                    if (tipomenu === 'nuevo') {

                                                                                                        var form = $('<form>', {
                                                                                                            'action': 'equipo_menuNuevo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                                                            'method': 'POST',
                                                                                                        });
                                                                                                    } else {

                                                                                                        var form = $('<form>', {
                                                                                                            'action': 'equipo_menuAntiguo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                                                            'method': 'POST',
                                                                                                        });
                                                                                                    }


                                                                                                    // Agregar un campo oculto para el ID
                                                                                                    form.append($('<input>', {
                                                                                                        'type': 'hidden',
                                                                                                        'name': 'idEquipo',
                                                                                                        'value': idEquipo
                                                                                                    }));

                                                                                                    // Agregar un campo oculto para el ID
                                                                                                    form.append($('<input>', {
                                                                                                        'type': 'hidden',
                                                                                                        'name': 'idCategoria',
                                                                                                        'value': idCategoria
                                                                                                    }));

                                                                                                    // Agregar el formulario al documento y enviarlo
                                                                                                    form.appendTo('body').submit();
                                                                                                });

                                                                                                $('#tableSE<?php echo $idSubEquipo; ?>').on('click', '.btn-eliminarEquipo-sub', function (e) {
                                                                                                    e.preventDefault(); // Previene la acción por defecto del botón

                                                                                                    var id = $(this).data('ideliminarsub'); // Obtiene el ID del botón

                                                                                                    var dataString = 'id=' + id; // Prepara los datos para enviar

                                                                                                    Swal.fire({
                                                                                                        icon: 'question',
                                                                                                        title: '¿Eliminar este equipo?',
                                                                                                        text: 'Esta acción no se puede revertir',
                                                                                                        showConfirmButton: true,
                                                                                                        confirmButtonText: 'Sí, eliminar',
                                                                                                        confirmButtonColor: '#3085d6',
                                                                                                        showCancelButton: true,
                                                                                                        cancelButtonText: 'No, lo pensaré',
                                                                                                        cancelButtonColor: '#d33',
                                                                                                        buttonsStyling: true
                                                                                                    }).then((result) => {
                                                                                                        if (result.isConfirmed) {
                                                                                                            $.ajax({
                                                                                                                url: '../sdo-funciones/equipamiento_desvincularHijo.php',
                                                                                                                type: 'POST',
                                                                                                                data: dataString,
                                                                                                                success: function (response) {
                                                                                                                    console.log('Eliminado: ' + response);

                                                                                                                    if (response === 'confirmo') {

                                                                                                                        Swal.fire({
                                                                                                                            icon: 'success',
                                                                                                                            title: 'Registro del equipo eliminado',
                                                                                                                            showConfirmButton: false,
                                                                                                                            timer: 2000,
                                                                                                                        });

                                                                                                                        // Recargar la página después de mostrar el mensaje de éxito
                                                                                                                        setTimeout(function () {
                                                                                                                            location.reload();
                                                                                                                        }, 2100);

                                                                                                                    } else {
                                                                                                                        console.error('Error: ID Desconocido');
                                                                                                                        Swal.fire({
                                                                                                                            icon: 'error',
                                                                                                                            title: 'Error',
                                                                                                                            text: 'No se pudo eliminar el equipo. Inténtelo de nuevo.',
                                                                                                                            confirmButtonColor: '#3085d6'
                                                                                                                        });
                                                                                                                    }

                                                                                                                },
                                                                                                                error: function (xhr, status, error) {
                                                                                                                    console.error('Error al eliminar el equipo:', error);
                                                                                                                    Swal.fire({
                                                                                                                        icon: 'error',
                                                                                                                        title: 'Error',
                                                                                                                        text: 'No se pudo eliminar el equipo. Inténtelo de nuevo.',
                                                                                                                        confirmButtonColor: '#3085d6'
                                                                                                                    });
                                                                                                                }
                                                                                                            });
                                                                                                        }
                                                                                                    });
                                                                                                });
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN SUB EQUIPO EQUIPO -->


                                                                            <!-- DOCUMENTACION EQUIPO -->
                                                                            <div class="tab-pane " id="documentacion<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold"><?php echo 'Documentacion: ' . $nombreSubEquipo; ?></h4>
                                                                                <br><br>
                                                                                <div class="row">
                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <button type="button" title="Agregar un manual" class="btn btn-primary btn-block" data-toggle="modal" data-target="#agregarmanual<?php echo htmlspecialchars($idSubEquipo); ?>">
                                                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Documentación
                                                                                                </button>
                                                                                                <hr>
                                                                                                <?php
                                                                                                $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp WHERE idEquipo = $idSubEquipo;";
                                                                                                $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                                                                                while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                                                                                    $nomArchivo = $rowArchivos['nomArchivo'];
                                                                                                    $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                                                                    $idArchivo = $rowArchivos['id'];
                                                                                                    $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
                                                                                                    ?>
                                                                                                    <ul class="nav flex-column">
                                                                                                        <li class="nav-item">
                                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                                <?php if ($extension == 'pdf') { ?>
                                                                                                                    <button class="btn btn-block bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                                                                    </button>
                                                                                                                <?php } else if ($extension == 'jpg' || $extension == 'png') { ?>
                                                                                                                    <button class="btn btn-block bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                                                                    </button>
                                                                                                                <?php } ?>
                                                                                                                <button class="btn btn-danger eliminarManual" id="<?php echo $idArchivo ?>">
                                                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <hr>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <?php
                                                                                                if ($extension == 'pdf') {
                                                                                                    ?>
                                                                                                    <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px"/>
                                                                                                    <?php
                                                                                                } else if ($extension == 'jpg' || $extension == 'png') {
                                                                                                    ?>
                                                                                                    <img id="pdfViewer" src=""/>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>

                                                                                                <script>
                                                                                                    function cambiarPDF(ruta) {
                                                                                                        document.getElementById('pdfViewer').setAttribute('src', ruta);
                                                                                                    }
                                                                                                </script>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="modal fade" id="agregarmanual<?php echo htmlspecialchars($idSubEquipo); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header bg-info">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                                                                                    <div class="modal-body">
                                                                                                        <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre; ?>">
                                                                                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nombreSubEquipo ?>">
                                                                                                        <div class="row">
                                                                                                            <div class="form-group col-md-12 col-12">
                                                                                                                <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                                                                <div class="input-group mb-3">
                                                                                                                    <div class="input-group-prepend">
                                                                                                                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                                                    </div>
                                                                                                                    <input type="text" class="form-control" required id="nomArchivo" name="nomArchivo">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="form-group col-md-12 col-12">
                                                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                                                                <div class="input-group mb-3">
                                                                                                                    <div class="input-group-prepend">
                                                                                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                                                                    </div>
                                                                                                                    <select class="form-control" required id="tipoArchivo" name="tipoArchivo">
                                                                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                                                                        <?php
                                                                                                                        $sql = "SELECT * FROM tiposarchivos";
                                                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                                                            $datos = [];
                                                                                                                            $datos['id '] = $row['id'];
                                                                                                                            $datos['nomTipo'] = $row['nomTipo'];
                                                                                                                            $id = $datos['id'];
                                                                                                                            $nomTipo = $datos['nomTipo'];
                                                                                                                            ?>
                                                                                                                            <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case($nomTipo, MB_CASE_UPPER, 'UTF-8') ?></option>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <option value="Otros">Otro</option>
                                                                                                                        <?php
                                                                                                                        ?>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                                                                            </div>
                                                                                                            <div class="form-group col-md-12 col-12">
                                                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                                                                                <div class="input-group mb-3">
                                                                                                                    <input type="file" class="form-control" required id="archivoAdjunto" name="archivoAdjunto">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                                                                        <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                                                                    </div>
                                                                                                    <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoriaSubEquipo ?>">
                                                                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idCategoriaSubEquipo ?>">
                                                                                                </form>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN DOCUMENTACION EQUIPO -->


                                                                            <!-- PLANIFICACION EQUIPO -->
                                                                            <div class="tab-pane " id="planificacion<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold"><?php echo 'PLANIFICACION: ' . $nombreSubEquipo; ?></h4>
                                                                                <br><br>

                                                                                <button style="float: right" class="btn bg-primary" type="button" data-toggle="modal" data-target="#modalAddSubTarea"><i class="fas fa-plus"></i> Agregar Tarea</button>
                                                                                <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_agregarTarea_Equipamiento.php">
                                                                                    <div class="modal fade" id="modalAddSubTarea" name="modalAddSubTarea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header bg-primary">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12 ml-auto">
                                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                                <label for="exampleInputBorder">Actividad: <code>*</code></label>
                                                                                                                <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="3" placeholder="Actividad..."></textarea>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-4 ml-auto">
                                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                                <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                                                                                <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-4 ml-auto">
                                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                                <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                                                                                <input type="date" name="finPlanTareaP" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required/>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-4 ml-auto">
                                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                                <label for="finPlanTareaP">Tipo de Días a Trabajar: <code>*</code></label>
                                                                                                                <select name="tipoDiasHabil" id="tipoDiasHabil" class="form-control">
                                                                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                                                                    <option value="Habiles">Habiles</option>
                                                                                                                    <option value="Inhabiles">Inhabiles</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                    <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre; ?>">
                                                                                                    <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                                                    <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                                                    <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                                                    <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="<?php echo getIDControlCentra(); ?>">
                                                                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                                <br>
                                                                                <br>

                                                                                <?php
                                                                                $query = 'SELECT * FROM tareas_carta_gantt WHERE (deptoCreadorTareaP = ' . getIDControlCentra() . ' OR deptoCreadorTareaP = ' . getIDInstrumentacion() . ') AND (actividadTareaP LIKE "%' . $subEquipoNombreEquipo . '%" OR idEquipamiento = ' . $idSubEquipo . ') ORDER BY inicioPlanTareaP ASC';

                                                                                //                                                                                                                        echo '<h4>' . $query . '</h4>';
                                                                                ?>

                                                                                <div class="table-responsive">

                                                                                    <table id="tableCartaPlanificacion<?php echo $numVista; ?>" class="table table-bordered table-hover">
                                                                                        <thead>
                                                                                        <tr class="bg-gradient-dark">
                                                                                            <th>N°</th>
                                                                                            <th>Detalle</th>
                                                                                            <th>Actividad</th>
                                                                                            <th>Inicio Planificado</th>
                                                                                            <th>Termino Planificado</th>
                                                                                            <th>Inicio Real</th>
                                                                                            <th>Termino Real</th>
                                                                                            <th>Porcentaje</th>
                                                                                            <th>Detalle</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php


                                                                                        $res = mysqli_query($conexion, $query);

                                                                                        $numTareaP = 1;
                                                                                        $numFila = 0;

                                                                                        while ($row = $res->fetch_assoc()) {

                                                                                            $idTareaP = $row['idTareaP'];

                                                                                            $contarSubTareaP = contarSubTareaP($idTareaP);

                                                                                            $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                                                                                            $contarCantidadPadre = contarCantidadPadre($idTareaP);

                                                                                            if ($contarTotalPorcentaje > 0) {
                                                                                                $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                                                                                            } else {
                                                                                                $porcentajePadre = 0;
                                                                                            }

                                                                                            if ($contarSubTareaP > 0) {
                                                                                                $argumentoTR = 'data-widget="expandable-table" aria-expanded="false"';
                                                                                            } else {
                                                                                                $argumentoTR = '';
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="fila-clickeable">
                                                                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                                                    <?php
                                                                                                    $numFila++;
                                                                                                    echo $numFila;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                                                    <?php
                                                                                                    if ($contarSubTareaP > 0) {
                                                                                                        ?>
                                                                                                        <button type="submit" style="text-wrap: nowrap;" id="button<?php echo $numTareaP; ?>" class="btn btn-primary" onclick="mostrarOcultarFilas('info<?php echo $numTareaP; ?>')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir</button>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        <button type="submit" class="btn btn-primary" disabled="disabled">Deshabilitado</button>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 25%; vertical-align: middle;">
                                                                                                    <?php
                                                                                                    echo mb_convert_case($row['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 10%; vertical-align: middle;">
                                                                                                    <?php
                                                                                                    $inicioPlanTareaP = $row['inicioPlanTareaP'];
                                                                                                    $inicioPlanTareaP2 = date("d-m-Y", strtotime($inicioPlanTareaP));
                                                                                                    echo $inicioPlanTareaP2;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 10%; vertical-align: middle;">
                                                                                                    <?php
                                                                                                    $finPlanTareaP = $row['finPlanTareaP'];
                                                                                                    $finPlanTareaP2 = date("d-m-Y", strtotime($finPlanTareaP));
                                                                                                    echo $finPlanTareaP2;
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 10%; vertical-align: middle;">
                                                                                                    <?php
                                                                                                    $inicioRealTareaP = $row['inicioRealTareaP'];

                                                                                                    if (empty($inicioRealTareaP)) {
                                                                                                        echo 'No Iniciado';
                                                                                                    } else {
                                                                                                        $inicioRealTareaP2 = date("d-m-Y", strtotime($inicioRealTareaP));
                                                                                                        echo $inicioRealTareaP2;
                                                                                                    }

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 10%; vertical-align: middle;">
                                                                                                    <?php
                                                                                                    $finRealTareaP = $row['finRealTareaP'];

                                                                                                    if (empty($inicioRealTareaP)) {
                                                                                                        echo 'No Iniciado';
                                                                                                    } else {
                                                                                                        if (empty($finRealTareaP)) {
                                                                                                            echo 'No Finalizado';
                                                                                                        } else {
                                                                                                            $finRealTareaP2 = date("d-m-Y", strtotime($finRealTareaP));
                                                                                                            echo $finRealTareaP2;
                                                                                                        }
                                                                                                    }

                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                                                                    <?php
                                                                                                    if ($contarTotalPorcentaje == 0) {
                                                                                                        echo "No Iniciado";
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        <div class="form-group">

                                                                                                            <?php
                                                                                                            $porcentajeTareaP2 = round($porcentajePadre, 1);

                                                                                                            ?>
                                                                                                            <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                                                            <div class="progress">
                                                                                                                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                                                                    <?php
                                                                                                    if ($row['idTareaP'] > 0) {
                                                                                                        ?>

                                                                                                        <form style="margin: 0; text-align: center;" class="" action="planificador_detalle.php" method="post">


                                                                                                            <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                            <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre; ?>">
                                                                                                            <input type="hidden" name="idTareaP" value="<?php echo $row['idTareaP']; ?>">
                                                                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i></button>

                                                                                                        </form>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo 'Deshabilitado';
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <?php

                                                                                            if ($contarSubTareaP > 0) {
                                                                                                $queryPadre = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = ' . $row['idTareaP'] . ' ORDER BY inicioPlanTareaP ASC';

                                                                                                $resPadre = mysqli_query($conexion, $queryPadre);

                                                                                                $numTareaPadre = 1;

                                                                                                while ($rowPadre = $resPadre->fetch_assoc()) {
                                                                                                    ?>
                                                                                                    <tr class="info<?php echo $numTareaP; ?> fila-oculta" style="display: none; background-color: lightgrey;">
                                                                                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                                                            <?php
                                                                                                            echo '';
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                                                                            <?php echo $numFila . '.' . $numTareaPadre; ?>
                                                                                                        </td>
                                                                                                        <td style="width: 25%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            echo mb_convert_case($rowPadre['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            $inicioPlanTareaPadre = $rowPadre['inicioPlanTareaP'];
                                                                                                            $inicioPlanTareaPadre2 = date("d-m-Y", strtotime($inicioPlanTareaPadre));
                                                                                                            echo $inicioPlanTareaPadre2;
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            $finPlanTareaPadre = $rowPadre['finPlanTareaP'];
                                                                                                            $finPlanTareaPadre2 = date("d-m-Y", strtotime($finPlanTareaPadre));
                                                                                                            echo $finPlanTareaPadre2;
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            $inicioRealTareaPadre = $row['inicioRealTareaP'];

                                                                                                            if (empty($inicioRealTareaPadre)) {
                                                                                                                echo 'No Iniciado';
                                                                                                            } else {
                                                                                                                $inicioRealTareaPadre2 = date("d-m-Y", strtotime($inicioRealTareaPadre));
                                                                                                                echo $inicioRealTareaPadre2;
                                                                                                            }

                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            $finRealTareaPadre = $row['finRealTareaP'];

                                                                                                            if (empty($inicioRealTareaPadre)) {
                                                                                                                echo 'No Iniciado';
                                                                                                            } else {
                                                                                                                if (empty($finRealTareaPadre)) {
                                                                                                                    echo 'No Finalizado';
                                                                                                                } else {
                                                                                                                    $finRealTareaPadre2 = date("d-m-Y", strtotime($finRealTareaPadre));
                                                                                                                    echo $finRealTareaPadre2;
                                                                                                                }
                                                                                                            }

                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                                                                            <div class="form-group">
                                                                                                                <?php
                                                                                                                $porcentajeTareaP2 = round($rowPadre['porcentajeTareaP'], 1);

                                                                                                                ?>
                                                                                                                <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                                                                                <div class="progress">
                                                                                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajeTareaP2; ?>%" aria-valuenow="<?php echo $porcentajeTareaP2; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                                                                            <?php
                                                                                                            echo '';
                                                                                                            ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                    $numTareaPadre++;
                                                                                                }
                                                                                            }
                                                                                            $numTareaP++;
                                                                                        }


                                                                                        ?>


                                                                                        </tbody>
                                                                                        <tfoot>

                                                                                        <tr class="bg-gradient-dark">
                                                                                            <th>N°</th>
                                                                                            <th>Detalle</th>
                                                                                            <th>Actividad</th>
                                                                                            <th>Inicio Planificado</th>
                                                                                            <th>Termino Planificado</th>
                                                                                            <th>Inicio Real</th>
                                                                                            <th>Termino Real</th>
                                                                                            <th>Porcentaje</th>
                                                                                            <th>Detalle</th>
                                                                                        </tr>
                                                                                        </tfoot>
                                                                                    </table>

                                                                                    <!--                            <h5>--><?php //echo $query; ?><!--</h5>-->
                                                                                </div>
                                                                                <script>
                                                                                    $(document).ready(function () {


                                                                                        $('#tableCartaPlanificacion<?php echo $numVista; ?>').DataTable({
                                                                                            "paging": true,
                                                                                            "lengthChange": false,
                                                                                            "searching": true,
                                                                                            "ordering": false,
                                                                                            "info": false,
                                                                                            "autoWidth": false,
                                                                                            "responsive": true,
                                                                                            "pageLength": 30,
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
                                                                                            }
                                                                                        });

                                                                                    });
                                                                                </script>
                                                                            </div>
                                                                            <!-- FIN PLANIFICACION EQUIPO -->


                                                                            <!-- HALLAZGOS EQUIPO -->
                                                                            <div class="tab-pane " id="hallazgos<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold"><?php echo 'Hallazgos: ' . $nombreSubEquipo; ?></h4>
                                                                                <br><br>
                                                                                <div class="row">
                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <button type="button" title="Agregar un Hallazgo" class="btn btn-primary btn-block" data-toggle="modal" data-target="#agregarhallazgo<?php echo htmlspecialchars($idSubEquipo); ?>">
                                                                                                    <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Hallazgo
                                                                                                </button>
                                                                                                <hr>
                                                                                                <?php
                                                                                                $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp_hallazgos WHERE idEquipo = $idSubEquipo;";
                                                                                                $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                                                                                while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                                                                                    $nomArchivo = $rowArchivos['nomArchivo'];
                                                                                                    $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                                                                    $idArchivo = $rowArchivos['id'];
                                                                                                    $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
                                                                                                    ?>
                                                                                                    <ul class="nav flex-column">
                                                                                                        <li class="nav-item">
                                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                                <?php if ($extension == 'pdf') { ?>
                                                                                                                    <button class="btn btn-block bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                                                                    </button>
                                                                                                                <?php } else if ($extension == 'jpg' || $extension == 'png') { ?>
                                                                                                                    <button class="btn btn-block bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                                                                    </button>
                                                                                                                <?php } ?>
                                                                                                                <button class="btn btn-danger eliminarhallazgo" id="<?php echo $idArchivo ?>">
                                                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <hr>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <?php
                                                                                                if ($extension == 'pdf') {
                                                                                                    ?>
                                                                                                    <embed id="pdfHallazgo" src="" type="application/pdf" width="100%" height="900px"/>
                                                                                                    <?php
                                                                                                } else if ($extension == 'jpg' || $extension == 'png') {
                                                                                                    ?>
                                                                                                    <img id="pdfHallazgo" src=""/>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>

                                                                                                <script>
                                                                                                    function cambiarPDF(ruta) {
                                                                                                        document.getElementById('pdfHallazgo').setAttribute('src', ruta);
                                                                                                    }
                                                                                                </script>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="modal fade" id="agregarhallazgo<?php echo htmlspecialchars($idSubEquipo); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header bg-info">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Formulario Ingreso Hallazgos</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <form action="../sdo-funciones/subir-hallazgos.php" method="POST" enctype="multipart/form-data">
                                                                                                    <div class="modal-body">
                                                                                                        <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre; ?>">
                                                                                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nombreSubEquipo ?>">
                                                                                                        <p><u><code>Solo se permiten fotos o archivos pdf</code></u></p>
                                                                                                        <div class="row">
                                                                                                            <div class="form-group col-md-12 col-12">
                                                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:15px">Adjunto el archivo:<code>*</code></label>
                                                                                                                <div class="input-group mb-3">
                                                                                                                    <input type="file" class="form-control" required id="archivoAdjuntoHallazgo" name="archivoAdjuntoHallazgo">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir</button>
                                                                                                    </div>
                                                                                                    <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoriaSubEquipo ?>">
                                                                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idCategoriaSubEquipo ?>">
                                                                                                </form>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN HALLAZGOS EQUIPO -->


                                                                            <!-- SISTEMA EQUIPO -->
                                                                            <div class="tab-pane " id="sistemas<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold">Observaciones de <?php echo $nombreSubEquipo; ?> en Sistema</h4>

                                                                                <br>

                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered">
                                                                                        <thead class="text-center bg-primary">
                                                                                        <tr>
                                                                                            <th style="width: 50%;">EQUIPO</th>
                                                                                            <th style="width: 50%;">ESTADO ACTUAL</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        if (!empty($equipo_controlaSubEquipo) && $equipo_controlaSubEquipo > 0) {
                                                                                            $nomEquipoControla = getNomEquipo($equipo_controlaSubEquipo);
                                                                                            $forzadoEquipo = getStatusForzado($equipo_controlaSubEquipo);
                                                                                            $forzadoEncendido = getStatusForzadoEncendido($equipo_controlaSubEquipo);
                                                                                            $forzadoApagado = getStatusForzadoApagado($equipo_controlaSubEquipo);
                                                                                            $forzadoVariable = getStatusForzadoVariables($equipo_controlaSubEquipo);
                                                                                            $modoAutomatico = getStatusAutomatico($equipo_controlaSubEquipo);

                                                                                            $getSubCategoriaEquipo = getSubCategoriaEquipo($equipo_controlaSubEquipo);

                                                                                            $_SESSION['idSubCategoria'] = $getSubCategoriaEquipo;
                                                                                        } else {
                                                                                            $nomEquipoControla = 'NO APLICA';
                                                                                            $forzadoEquipo = 0;
                                                                                            $forzadoEncendido = 0;
                                                                                            $forzadoApagado = 0;
                                                                                            $forzadoVariable = 0;
                                                                                            $modoAutomatico = 0;
                                                                                        }


                                                                                        if ($forzadoEncendido == 1) {
                                                                                            $estadoEquipoControla = 'Forzado Encendido';
                                                                                        } else if ($forzadoApagado == 1) {
                                                                                            $estadoEquipoControla = 'Forzado Apagado';
                                                                                        } else if ($modoAutomatico == 1) {
                                                                                            $estadoEquipoControla = 'Modo Automatico';
                                                                                        } else if ($forzadoVariable == 1) {
                                                                                            $estadoEquipoControla = 'Variables Forzadas';
                                                                                        } else {
                                                                                            $estadoEquipoControla = 'Modo Automatico';
                                                                                        }

                                                                                        $nomEquipoControla2 = mb_convert_case($nomEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                                                                        $estadoEquipoControla2 = mb_convert_case($estadoEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                                                                        ?>

                                                                                        <tr>
                                                                                            <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                                                                                <?php echo $nomEquipoControla; ?>
                                                                                            </td>
                                                                                            <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                                                                                <?php echo $estadoEquipoControla2; ?>
                                                                                            </td>
                                                                                        </tr>

                                                                                        </tbody>

                                                                                    </table>
                                                                                </div>

                                                                                <div class="row justify-content-center text-center">

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

                                                                                            if (strpos($nombreSubEquipo, "UMA") === false) {
                                                                                                $pasa = 'si';
                                                                                            }
                                                                                        }

                                                                                        if ($pasa == 'no') {
                                                                                            ?>
                                                                                            <?php
                                                                                            if ($estado == 0) {
                                                                                                ?>
                                                                                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                                                    <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block">
                                                                                                        <i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?>
                                                                                                    </button>
                                                                                                </div>

                                                                                                <?php
                                                                                            } else {

                                                                                                if ($titulo == 'Forzar Variables') {
                                                                                                    ?>

                                                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                                                        <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block">
                                                                                                            <i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } else {
                                                                                                    ?>
                                                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                                        <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-<?php echo $iconOn; ?>"></i>&nbsp;&nbsp;<?php echo $msg; ?></button>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        <div class="modal fade" id="modal<?php echo ucfirst($accion['accion']) ?>" name="modal<?php echo ucfirst($accion['accion']) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                            <div class="modal-dialog" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <form method="post" action="../sdo-funciones/<?php echo $archivo; ?>">
                                                                                                        <div class="modal-header bg-<?php echo $btnClass; ?>">
                                                                                                            <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $titulo ?> de Equipo: </b><?php echo $nombreSubEquipo ?></h5>
                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                            </button>
                                                                                                        </div>
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

                                                                                                            <?php

                                                                                                            $nomUsuarioMayus = mb_convert_case($nomUsuario, MB_CASE_UPPER, 'UTF-8');

                                                                                                            if ($nomUsuarioMayus == 'JORGE CANTILLANO') {
                                                                                                                $nombreConCargo = $nomUsuario . " ( Jefe de Control Centralizado )";
                                                                                                            } else {
                                                                                                                $nombreConCargo = $nomUsuario . " ( Administrador )";

                                                                                                            }

                                                                                                            ?>
                                                                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controlaSubEquipo; ?>">
                                                                                                            <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                            <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre; ?>">
                                                                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nombreConCargo; ?>">
                                                                                                            <button type="submit" class="btn btn-primary"><?php echo $titulo ?> Equipo</button>
                                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                    ?>

                                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                                        <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn btn-block bg-navy" style="float:right;">
                                                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion
                                                                                        </button>
                                                                                    </div>

                                                                                    <form method="post" action="../sdo-funciones/observaciones-agregar.php">
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
                                                                                                            <label for="exampleInputBorder">Nombre: <code>*</code></label>
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
                                                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controlaSubEquipo; ?>">
                                                                                                        <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre; ?>">
                                                                                                        <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nombreConCargo; ?>">
                                                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>


                                                                                </div>

                                                                                <br>

                                                                                <div class="row justify-content-center text-center">
                                                                                    <div class="col-12">
                                                                                        <div class="table-responsive">
                                                                                            <table id="<?php echo 'tableAnotacionesSistema' . $idSubEquipo ?>" class="table table-bordered table-hover">
                                                                                                <thead class="bg-info">
                                                                                                <tr>
                                                                                                    <th style="vertical-align:middle;">N°</th>
                                                                                                    <th style="vertical-align:middle;">Fecha Observaciones:</th>
                                                                                                    <th style="vertical-align:middle;">Nombre de Quien Registra:</th>
                                                                                                    <th style="vertical-align:middle;">Observaciones:</th>
                                                                                                    <?php
                                                                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controlaSubEquipo);
                                                                                                    if ($verificarEsPabellon == 1) {
                                                                                                        ?>
                                                                                                        <th nowrap="nowrap" style="vertical-align:middle;">Set-Point de T°:</th>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                    <th style="vertical-align:middle;">Tipo de Observación:</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <?php
                                                                                                $query = "SELECT * FROM equipos_observaciones WHERE idEquipo = '" . $equipo_controlaSubEquipo . "' ORDER BY fechaObservaciones DESC";
                                                                                                $res = mysqli_query($conexion, $query);
                                                                                                $numObservacion = 1;

                                                                                                while ($row = $res->fetch_assoc()) {
                                                                                                    $classObservacion = "";
                                                                                                    switch ($row['esForzado']) {
                                                                                                        case "Forzado - Encendido":
                                                                                                            $classObservacion = "bg-success";
                                                                                                            break;
                                                                                                        case "Forzado - Apagado":
                                                                                                            $classObservacion = "bg-pink";
                                                                                                            break;
                                                                                                        case "Forzado - Variables":
                                                                                                            $classObservacion = "bg-yellow";
                                                                                                            break;
                                                                                                        case "Modo Automatico":
                                                                                                            $classObservacion = "bg-info";
                                                                                                            break;
                                                                                                        case "Observación Normal":
                                                                                                            $classObservacion = "bg-navy";
                                                                                                            break;
                                                                                                        case "Forzado":
                                                                                                            $classObservacion = "bg-gray";
                                                                                                            break;
                                                                                                        case "Cambio de Set Point":
                                                                                                            $classObservacion = "bg-indigo";
                                                                                                            break;
                                                                                                        default:
                                                                                                            $classObservacion = "bg-light";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <tr class="<?php echo $classObservacion; ?>">
                                                                                                        <td style="vertical-align:middle;"><?php echo $numObservacion; ?></td>
                                                                                                        <td style="vertical-align:middle;">
                                                                                                            <?php
                                                                                                            if ($row['fechaObservaciones'] == 0) {
                                                                                                                echo "No registra fecha";
                                                                                                            } else {
                                                                                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                                                                                $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                                                                                echo $inicioFecha . '&nbsp;&nbsp;' . $inicioHora;
                                                                                                            }
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                                                                                            <?php
                                                                                                            $personalObservacion = $row['personalObservacion'];
                                                                                                            $partes = explode(' (', $personalObservacion, 2);
                                                                                                            $nombre = $partes[0];
                                                                                                            $profesion = rtrim($partes[1], ')');
                                                                                                            echo $nombre . '<br>(' . $profesion . ')';
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="vertical-align:middle; text-align:justify;"><?php echo nl2br($row['detalleObservaciones']); ?></td>
                                                                                                        <?php
                                                                                                        if ($verificarEsPabellon == 1) {
                                                                                                            ?>
                                                                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                                                                <?php
                                                                                                                $porcentajeObservacion = $row['porcentajeObservacion'];
                                                                                                                echo $porcentajeObservacion > 0 ? $porcentajeObservacion : 'No Aplica';
                                                                                                                ?>
                                                                                                            </td>
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                        <td style="vertical-align:middle; text-align:center;"><?php echo $row['esForzado']; ?></td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                    $numObservacion++;
                                                                                                }
                                                                                                ?>
                                                                                                </tbody>
                                                                                                <tfoot class="bg-info">
                                                                                                <tr>
                                                                                                    <th style="vertical-align:middle;">N°</th>
                                                                                                    <th style="vertical-align:middle;">Fecha Observaciones:</th>
                                                                                                    <th style="vertical-align:middle;">Nombre de Quien Registra:</th>
                                                                                                    <th style="vertical-align:middle;">Observaciones:</th>
                                                                                                    <?php
                                                                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controlaSubEquipo);
                                                                                                    if ($verificarEsPabellon == 1) {
                                                                                                        ?>
                                                                                                        <th nowrap="nowrap" style="vertical-align:middle;">Set-Point de T°:</th>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                    <th style="vertical-align:middle;">Tipo de Observación:</th>
                                                                                                </tr>
                                                                                                </tfoot>
                                                                                            </table>

                                                                                            <script>

                                                                                                $(document).ready(function () {
                                                                                                    $('#<?php echo 'tableAnotacionesSistema' . $idSubEquipo ?>').DataTable({
                                                                                                        "paging": true,
                                                                                                        "iDisplayLength": 10,
                                                                                                        "searching": true,
                                                                                                        "ordering": false,
                                                                                                        "info": true,
                                                                                                        "processing": true,
                                                                                                        "autoWidth": false,
                                                                                                        "responsive": false,
                                                                                                        "language": {
                                                                                                            "lengthMenu": "Visualizando _MENU_ Registros por Página",
                                                                                                            "zeroRecords": "No se han encontrado Registros.",
                                                                                                            "info": "Mostrando página _PAGE_ de _PAGES_",
                                                                                                            "infoEmpty": "No existen Registros.",
                                                                                                            "search": "Buscar:",
                                                                                                            "processing": "Procesando...",
                                                                                                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                                                                            "paginate": {
                                                                                                                "previous": "Anterior",
                                                                                                                "next": "Siguiente"
                                                                                                            }
                                                                                                        }
                                                                                                    });
                                                                                                });
                                                                                            </script>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN SISTEMA EQUIPO -->


                                                                            <!-- BMS EQUIPO -->

                                                                            <div class="tab-pane " id="bms<?php echo $numVista; ?>">
                                                                                <h4 class="text-center font-weight-bold"><?php echo 'BMS: ' . $nombreSubEquipo; ?></h4>
                                                                                <br><br>

                                                                                <div class="row">
                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <h3 class="profile-username text-center">Sources Asociados</h3>
                                                                                                <br>


                                                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                                                    <li class="list-group-item">
                                                                                                        <button type="button" title="Asociar Sources" class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#asociarSource<?php echo htmlspecialchars($idSubEquipo); ?>"><i class="fas fa-plus"></i>&nbsp;&nbsp;Asociar Sources</button>
                                                                                                    </li>

                                                                                                    <?php
                                                                                                    $sqlObtenerSource = "SELECT sourceAsocia FROM bms_asocia_source WHERE idEquipoAsocia = $idSubEquipo ORDER BY sourceAsocia ASC";

                                                                                                    //                                                    echo $sqlObtenerSource;
                                                                                                    $resObtenerSource = mysqli_query($conexion, $sqlObtenerSource);

                                                                                                    $sourcesArray = array();

                                                                                                    if (mysqli_num_rows($resObtenerSource) > 0) {
                                                                                                        while ($rowObtenerSource = $resObtenerSource->fetch_assoc()) {
                                                                                                            $sourceAsocia = $rowObtenerSource['sourceAsocia'];
                                                                                                            $sourcesArray[] = $rowObtenerSource['sourceAsocia'];
                                                                                                            ?>

                                                                                                            <li style="vertical-align: middle" class="list-group-item">
                                                                                                                <?php echo $sourceAsocia; ?>
                                                                                                                <a href="#" onclick="confirmDelete('<?php echo $sourceAsocia; ?>'); return false;" class="btn btn-danger btn-sm float-right"><i class="fas fa-times"></i></a>
                                                                                                            </li>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </ul>

                                                                                                <div class="modal fade" id="asociarSource<?php echo htmlspecialchars($idSubEquipo); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                                    <div class="modal-dialog" role="document">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header bg-info">
                                                                                                                <h5 class="modal-title" id="exampleModalLabel">Asociar Sources</h5>
                                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <form action="../sdo-funciones/bms_asociarSourcesEquipos.php" method="POST" enctype="multipart/form-data">
                                                                                                                <div class="modal-body">
                                                                                                                    <?php

                                                                                                                    $SourceAlarmasEquiposArray = array();

                                                                                                                    $nombreEquipoSinEspacios = preg_replace('/\s+/', '', $nombreEquipo); // Quitar todos los espacios y tabulaciones

                                                                                                                    // Construye la consulta SQL
                                                                                                                    $sqlSourceAlarmasEquipos = "SELECT sourceAlarma FROM bms_alarmas_equipamiento WHERE REPLACE(sourceAlarma, ' ', '') LIKE '%$nombreEquipoSinEspacios%' GROUP BY sourceAlarma;";

                                                                                                                    // Ejecuta la consulta
                                                                                                                    $resSourceAlarmasEquipos = mysqli_query($conexion, $sqlSourceAlarmasEquipos);

                                                                                                                    // Verifica si la consulta se ejecutó correctamente
                                                                                                                    if ($resSourceAlarmasEquipos && mysqli_num_rows($resSourceAlarmasEquipos) > 0) {
                                                                                                                        while ($rowSourceAlarmasEquipos = $resSourceAlarmasEquipos->fetch_assoc()) {
                                                                                                                            // Agrega cada fila al array
                                                                                                                            $SourceAlarmasEquiposArray[] = $rowSourceAlarmasEquipos['sourceAlarma'];
                                                                                                                        }
                                                                                                                    }
                                                                                                                    //
                                                                                                                    //                                                                                                                    echo $sqlObtenerSource.'<br><br>';
                                                                                                                    //                                                                                                                    var_dump($SourceAlarmasEquiposArray);
                                                                                                                    ?>
                                                                                                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $subEquipoId; ?>">
                                                                                                                    <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre ?>">
                                                                                                                    <div class="form-group">
                                                                                                                        <label for="exampleFormControlSelect1">Seleccione Sources</label>
                                                                                                                        <select class="listDataSource" name="sourcesParaAsociar[]" multiple="multiple">
                                                                                                                            <?php
                                                                                                                            // Supongamos que $arrayFuentesExistentes contiene las fuentes que no quieres mostrar

                                                                                                                            $sqlListadoSource = "SELECT sourceAlarma FROM bms_alarmas_equipamiento GROUP BY sourceAlarma";
                                                                                                                            $resListadoSource = mysqli_query($conexion, $sqlListadoSource);

                                                                                                                            // Verifica si la consulta se ejecutó correctamente
                                                                                                                            if ($resListadoSource && mysqli_num_rows($resListadoSource) > 0) {
                                                                                                                                while ($rowListadoSource = $resListadoSource->fetch_assoc()) {
                                                                                                                                    $sourceAlarma = $rowListadoSource['sourceAlarma'];

                                                                                                                                    // Mostrar solo si el valor no está en el array $arrayFuentesExistentes
                                                                                                                                    if (!in_array($sourceAlarma, $sourcesArray)) {
                                                                                                                                        ?>
                                                                                                                                        <option value="<?php echo $sourceAlarma; ?>"><?php echo $sourceAlarma; ?></option>
                                                                                                                                        <?php
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="submit" class="btn btn-success"><i class="fas fa-arrow-circle-up"></i>&nbsp;&nbsp;Asociar</button>
                                                                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                                                                                </div>
                                                                                                            </form>

                                                                                                            <div class="modal-body bg-info">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                            <!-- /.card-body -->
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xl-9 col-lg-9 col-12">

                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body">
                                                                                                <h3 class="profile-username text-center">Tabla de Alarmas</h3>
                                                                                                <br>
                                                                                                <br>
                                                                                                <div class="table-responsive">
                                                                                                    <input type="hidden" name="idSubEquipoCargaAlarmas" id="idSubEquipoCargaAlarmas" value="<?php echo $idSubEquipo; ?>">
                                                                                                    <table id="bms_alarmas_equipos<?php echo $idSubEquipo; ?>" class="table table-bordered">
                                                                                                        <thead class="bg-secondary text-center">
                                                                                                        <tr>
                                                                                                            <th>Fecha</th>
                                                                                                            <th>Source</th>
                                                                                                            <th>Estado</th>
                                                                                                            <th>Clase</th>
                                                                                                            <th>Mensaje</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        <?php
                                                                                                        // Escapa y formatea los valores del array para la consulta SQL
                                                                                                        $escapedSources = array_map(function ($value) use ($conexion) {
                                                                                                            return "'" . mysqli_real_escape_string($conexion, $value) . "'";
                                                                                                        }, $sourcesArray);

                                                                                                        // Convierte el array de valores en una cadena separada por comas
                                                                                                        $sourceList = implode(', ', $escapedSources);

                                                                                                        // Construye la consulta SQL
                                                                                                        $sqlTableAlarmasEquipos = "SELECT * FROM bms_alarmas_equipamiento WHERE sourceAlarma IN ($sourceList)";

                                                                                                        // Ejecuta la consulta
                                                                                                        $resTableAlarmas = mysqli_query($conexion, $sqlTableAlarmasEquipos);

                                                                                                        // Verifica si la consulta se ejecutó correctamente
                                                                                                        if ($resTableAlarmas && mysqli_num_rows($resTableAlarmas) > 0) {
                                                                                                            while ($rowTableAlarmas = $resTableAlarmas->fetch_assoc()) {
                                                                                                                $fechaAlarmas = $rowTableAlarmas['fechaAlarma'];

                                                                                                                $fechaAlarmas2 = strtotime($fechaAlarmas);

                                                                                                                $fechaAlarmas3 = fechaEspañol2($fechaAlarmas2);

                                                                                                                $horaAlarmas = date('H:i:s', $fechaAlarmas2);

                                                                                                                ?>
                                                                                                                <tr>
                                                                                                                    <td><?php echo $fechaAlarmas3 . '<br>' . $horaAlarmas ?></td>
                                                                                                                    <td><?php echo $rowTableAlarmas['sourceAlarma'] ?></td>
                                                                                                                    <td><?php echo $rowTableAlarmas['stateAlarma'] ?></td>
                                                                                                                    <td><?php echo $rowTableAlarmas['classAlarma'] ?></td>
                                                                                                                    <td><?php echo $rowTableAlarmas['mensajeAlarma'] ?></td>
                                                                                                                </tr>
                                                                                                                <?php
                                                                                                            }
                                                                                                        }

                                                                                                        ?>
                                                                                                        </tbody>
                                                                                                        <tfoot class="bg-secondary text-center">
                                                                                                        <tr>
                                                                                                            <th>Fecha</th>
                                                                                                            <th>Source</th>
                                                                                                            <th>Estado</th>
                                                                                                            <th>Clase</th>
                                                                                                            <th>Mensaje</th>
                                                                                                        </tr>
                                                                                                        </tfoot>
                                                                                                    </table>
                                                                                                    <script>
                                                                                                        $(document).ready(function () {
                                                                                                            $('#bms_alarmas_equipos<?php echo $idSubEquipo; ?>').DataTable({
                                                                                                                "paging": true,
                                                                                                                "iDisplayLength": 10,
                                                                                                                "lengthChange": false,
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
                                                                                                                "createdRow": function (row, data, dataIndex) {
                                                                                                                    $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                                    $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                                                                }
                                                                                                            });
                                                                                                        });
                                                                                                    </script>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- FIN BMS EQUIPO -->


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <?php

                                                    } else {

                                                        echo '';
                                                    }
                                                }

                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- FIN EQUIPOS HIJOS -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
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
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listDataSource').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.equipoQueControlaMod1').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.sourceAlarmaMod').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.equipoQueControlaAgregar').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>


<script>
    $(document).ready(function () {
        $('.eliminarManual').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');  // Obtén el id del archivo

            var dataString = 'id=' + encodeURIComponent(id);  // Codifica el id para la solicitud

            console.log(dataString);

            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este documento?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Sí, eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensaré',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-manual.php',
                        type: 'POST',
                        data: {
                            'id': id
                        },
                        dataType: 'json',  // Asegúrate de que la respuesta se maneje como JSON
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                setTimeout(function () {
                                    location.reload();  // Recarga la página para reflejar los cambios
                                }, 2100);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al procesar la solicitud.',
                            });
                        }
                    });
                }
            });
        });

    });
</script>
<script>

    $(document).ready(function () {
        $('.eliminarhallazgo').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            console.log(dataString);
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este hallazgo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-hallazgo.php',
                        type: 'POST',
                        data: {
                            'id': id
                        },
                        dataType: 'json',  // Esto asegura que la respuesta se maneje como JSON
                        success: function (response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                setTimeout(function () {
                                    location.reload();  // Recarga la página para reflejar los cambios
                                }, 2100);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error1',
                                    text: response.message,
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al procesar la solicitud.',
                            });
                        }
                    })
                }
            })
        })
    });
</script>
</body>

</html>

