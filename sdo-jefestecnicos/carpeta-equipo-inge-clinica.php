<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../vendor/quickchart/QuickChart.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
// echo '';


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');

if (isset($_POST['idCarpeta'])) {
    // Si se recibe idCarpeta a través de POST, se guarda en la sesión
    $idCarpeta = $_POST['idCarpeta'];
    $_SESSION['idCarpeta'] = $idCarpeta;
} elseif (isset($_SESSION['idCarpeta'])) {
    // Si no se recibe idCarpeta, se intenta obtenerlo de la sesión
    $idCarpeta = $_SESSION['idCarpeta'];
} else {
    // Si no hay idCarpeta en POST ni en la sesión, se redirige
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
    exit; // Asegúrate de salir después de redirigir
}

if ($idCarpeta > 0) {

    $_SESSION['idCarpeta'] = $idCarpeta;

    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS DE LA CARPETA
    $cuentaEquiposRegistrados = "SELECT COUNT(*) AS cantidadEquiposRegistrados FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaEquiposRegistrados);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadEquiposRegistrados'] = $row['cantidadEquiposRegistrados'];
        $cantidadEquiposRegistrados = $datos['cantidadEquiposRegistrados'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS DE OPERATIVOS
    $cuentaEquiposOperativos = "SELECT COUNT(*) AS cuentaEquiposOperativos FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta && estadoEquipo = 'Operativo'";
    $res = mysqli_query($conexion, $cuentaEquiposOperativos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaEquiposOperativos'] = $row['cuentaEquiposOperativos'];
        $cuentaEquiposOperativos = $datos['cuentaEquiposOperativos'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS EN MANTENIMIENTO
    $cantEquiposMantenimiento = "SELECT COUNT(*) AS cantEquiposMantenimiento FROM historial_cambios_equipos_observacion WHERE idCategoria = $idCarpeta ";
    $res = mysqli_query($conexion, $cantEquiposMantenimiento);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposMantenimiento'] = $row['cantEquiposMantenimiento'];
        $cantEquiposMantenimiento = $datos['cantEquiposMantenimiento'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS CON FALLAS
    $cantEquiposFalla = "SELECT COUNT(*) AS cantEquiposFalla FROM historial_cambios_equipos_falla WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantEquiposFalla);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposFalla'] = $row['cantEquiposFalla'];
        $cantEquiposFalla = $datos['cantEquiposFalla'];
    }


    //CUENTA LA CANTIDAD DE HOJAS TÉCNICAS DE LA CARPETA
    /*$cuentaHojasTecnicas = "SELECT COUNT(*) AS cantidadHojasTecnicas FROM hojas_tecnicas_inge_clinica WHERE idCategoria = $idCarpeta";

    $res = mysqli_query($conexion, $cuentaHojasTecnicas);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTecnicas'] = $row['cantidadHojasTecnicas'];
        $cantidadHojasTecnicas = $datos['cantidadHojasTecnicas'];
    }
    */
    //CUENTA LA CANTIDAD DE HOJAS TÉCNICAS DE LA CARPETA
    $cuentaHojasTecnicas = "SELECT COUNT(*) AS cantidadHojasTecnicas FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta";

    $res = mysqli_query($conexion, $cuentaHojasTecnicas);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTecnicas'] = $row['cantidadHojasTecnicas'];
        $cantidadHojasTecnicas = $datos['cantidadHojasTecnicas'];
    }
    //SUMA COSTO TOTAL
    $sumaGastoTotal = "SELECT SUM(costoTotal) AS gastoTotal FROM gasto_equipo_carpeta_inge_clinica WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sumaGastoTotal);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    //SUMA SOLICITUDES COMPRA
    $cuentaSoliCompra = "SELECT COUNT(*) AS cuentaSoliCompra FROM solicitudes_compra_inge_clinica WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaSoliCompra);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaSoliCompra'] = $row['cuentaSoliCompra'];
        $cuentaSoliCompra = $datos['cuentaSoliCompra'];
    }

    //SUMA SOLICITUDES COMPRA
    $cantidadArchivos = "SELECT COUNT(*) AS cuentaArchivos FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantidadArchivos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaArchivos'] = $row['cuentaArchivos'];
        $cuentaArchivos = $datos['cuentaArchivos'];
    }

    if ($rolUsuario2 == "personal") {
        $tipoUsuario = 'personal';
        $ruta = "sdo-personal";
    } else if ($rolUsuario2 == "administrativo") {
        $tipoUsuario = 'administrativo';
        $ruta = "sdo-admtec";
    } else if ($rolUsuario2 == "bodega pañol") {
        $tipoUsuario = 'bodega pañol';
        $ruta = "sdo-bodega-panol";
    } else if ($rolUsuario2 == "jefe tecnico") {
        $tipoUsuario = 'jefe tecnico';
        $ruta = "sdo-jefestecnicos";
    } else if ($rolUsuario2 == "administrador") {
        $tipoUsuario = 'administrador';
        $ruta = "sdo-administrador";
    } else if ($rolUsuario2 == "directiva") {
        $tipoUsuario = 'directiva';
        $ruta = "sdo-directiva";
    } else if ($rolUsuario2 == "oficina tecnica") {
        $tipoUsuario = 'oficina tecnica';
        $ruta = "sdo-oficinaTecnica";
    } else {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }


} else {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-gantt.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
            </div>
        </div>
        <?php
        if (isset($_GET['exitoGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se ha guardado el nuevo equipo correctamente!',
                    'success'
                )
            </script>
            <?php
        }
        if (isset($_GET['errorGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Se ha producido un error al intentar guardar el equipo!',
                    'error'
                )
            </script>
            <?php
        }
        ?>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">


                                <form method="post" action="equipamiento_hbqp.php" class="text-right">
                                    <button style="float: right" type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL INFO EQUIPO -->
                    <div class="modal fade" id="modalInfoEquipos" tabindex="-1" aria-labelledby="labelInfoEquipos" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title" id="labelInfoEquipos"><b> Informaci&oacute;n de Equipos </b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <table class="table table-bordered table-sm" id="tablaInfoEquipos">

                                        <thead class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Modelo Equipo</th>
                                            <th scope="col" class="text-center">Marca Equipo</th>
                                            <th scope="col" class="text-center">Cantidad</th>
                                            <th scope="col" class="text-center">Precio Unitario (Con IVA)</th>
                                            <th scope="col" class="text-center">Precio Total (Con IVA)</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = " . $idCarpeta . " AND (modelo IS NOT NULL AND modelo NOT LIKE '')";
                                        $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);
                                        $sumaTotalSistema = 0;
                                        while ($rowInfoEquipo = $resInfoEquipo->fetch_assoc()) {

                                            $marca = $rowInfoEquipo['marca'];

                                            if (empty($marca)) {
                                                $marca = 'No Aplica';
                                            }

                                            $modelo = $rowInfoEquipo['modelo'];

                                            if (empty($modelo)) {
                                                $modelo = 'No Aplica';
                                            }

                                            $valorConIva = obtenerMayorValorEquipamiento($modelo, $idCarpeta);

                                            if ($valorConIva > 0) {
                                                $valorConIva2 = moneda_chilena($valorConIva);
                                                $valorMultiplicar = $valorConIva;
                                            } else {
                                                $valorConIva2 = '$ 0';
                                                $valorMultiplicar = 0;
                                            }

                                            $contarModelosDistintos = contarModelosDistintos($modelo, $idCarpeta);

                                            if ($contarModelosDistintos > 0) {
                                                $contarModelosDistintos2 = $contarModelosDistintos;
                                                $valorMultiplicar2 = $contarModelosDistintos;
                                            } else {
                                                $contarModelosDistintos2 = 0;
                                                $valorMultiplicar2 = 0;
                                            }

                                            $total = $valorMultiplicar * $valorMultiplicar2;

                                            if ($total > 0) {
                                                $sumaTotalSistema = $sumaTotalSistema + $total;
                                            } else {

                                                $sumaTotalSistema = $sumaTotalSistema + 0;
                                            }

                                            if ($total > 0) {
                                                $totalFormat = moneda_chilena($total);
                                            } else {
                                                $totalFormat = '$ 0';
                                            }

                                            $marca2 = mb_convert_case($marca, MB_CASE_UPPER, 'UTF-8');
                                            $modelo2 = mb_convert_case($modelo, MB_CASE_UPPER, 'UTF-8');
                                            $valorConIva2 = mb_convert_case($valorConIva2, MB_CASE_UPPER, 'UTF-8');
                                            $contarModelosDistintos2 = mb_convert_case($contarModelosDistintos2, MB_CASE_UPPER, 'UTF-8');
                                            $totalFormat2 = mb_convert_case($totalFormat, MB_CASE_UPPER, 'UTF-8');

                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $modelo2 ?></td>
                                                <td class="text-center"><?php echo $marca2 ?></td>
                                                <td class="text-center"><?php echo $contarModelosDistintos2 ?></td>
                                                <td class="text-center"><?php echo $valorConIva2 ?></td>
                                                <td class="text-center"><?php echo $totalFormat2 ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Modelo Equipo</th>
                                            <th scope="col" class="text-center">Marca Equipo</th>
                                            <th scope="col" class="text-center">Cantidad</th>
                                            <th scope="col" class="text-center">Precio Unitario (Con IVA)</th>
                                            <th scope="col" class="text-center">Precio Total (Con IVA)</th>

                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN MODAL INFO EQUIPO -->

                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile w">
                                <div class="text-center">
                                    <?php

                                    if (!empty($imagenCategoria)) {
                                        ?>
                                        <img class=" img-fluid" src="<?php echo $imagenCategoria; ?>" width="200px" alt="<?php echo $nomCategoria ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <img class=" img-fluid" src="img/controlCent-HBQP.png" width="200px" alt="Control Centralizado">
                                        <?php
                                    }

                                    ?>


                                </div>

                                <br>
                                <h2 class="profile-username text-center text-bold">Carpeta - <?php echo $nomCategoria ?> </h2>

                                <br>

                                <button class="btn bg-purple btn-block btn-md " data-toggle="modal" data-target="#modalInfoEquipos">
                                    <i class="fas fa-money-bill-wave"></i>&nbsp;&nbsp;Valor Sistema:
                                    <?php

                                    if ($sumaTotalSistema > 0) {
                                        $sumaTotalSistema2 = moneda_chilena($sumaTotalSistema);
                                    } else {
                                        $sumaTotalSistema2 = '$ 0';
                                    }

                                    echo '<b>' . $sumaTotalSistema2 . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-primary btn-block btn-md ">
                                    <i class="fas fa-desktop"></i>&nbsp;&nbsp;Equipos Registrados:
                                    <?php

                                    echo '<b>' . $cantidadEquiposRegistrados . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-success btn-block btn-md ">
                                    <i class="fas fa-check"></i>&nbsp;&nbsp;Equipos Operativos:
                                    <?php

                                    echo '<b>' . $cuentaEquiposOperativos . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-warning btn-block btn-md " data-toggle="modal" data-target="#exampleModal">
                                    <i class="fas fa-cog"></i>&nbsp;&nbsp;Equipos Con Observaciones:
                                    <?php
                                    $cantEquiposObservacion = "SELECT COUNT(*) AS cantEquiposObservacion FROM historial_cambios_equipos WHERE idCategoria = $idCarpeta";
                                    $res1 = mysqli_query($conexion, $cantEquiposObservacion);
                                    while ($row1 = $res1->fetch_assoc()) {
                                        $datos1 = [];
                                        $datos1['cantEquiposObservacion'] = $row1['cantEquiposObservacion'];
                                        $cantEquiposObservacion = $datos1['cantEquiposObservacion'];
                                    }

                                    echo '<b>' . $cantEquiposObservacion . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-danger btn-block btn-md " data-toggle="modal" data-target="#modalFalla">
                                    <i class="fas fa-skull-crossbones"></i>&nbsp;&nbsp;Equipos Fuera de Servicio:
                                    <?php

                                    echo '<b>' . $cantEquiposFalla . '</b>';

                                    ?>
                                </button>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>


                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando los equipos con observaciones </b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <table class="table table-bordered table-sm" id="tabla-equipo-falla">

                                        <thead class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                            <th scope="col" class="text-center">Equipo</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                            <th scope="col" class="text-center">Marca</th>
                                            <th scope="col" class="text-center">Editar</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = $idCarpeta ORDER BY fecha DESC";
                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                            $datos = [];
                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                            $idCambio = $datos['idCambio'];
                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                            $fecha = $datos['fecha'];
                                            $datos['estado'] = $rowObservaciones['estado'];
                                            $estado = $datos['estado'];
                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                            $observaciones = $datos['observaciones'];
                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                            $idEquipo = $datos['idEquipo'];
                                            $datos['nombreEquipo'] = $rowObservaciones['nombreEquipo'];
                                            $nomEquipo = $datos['nombreEquipo'];
                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                            $tipoUsuario = $datos['tipoUsuario'];
                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                            $idUsuarioCambia = $datos['idUsuarioCambia'];
                                            $fecha = strtotime($fecha);
                                            $fechaFormateada = date('d/m/Y', $fecha);
                                            ?>
                                            <tr>
                                                <?php
                                                if ($tipoUsuario == 'administrativo') {
                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                    }

                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                        $nombre = $datos2['nomJefe'];
                                                    }

                                                } else if ($tipoUsuario == 'personal') {
                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                        $nombre = $datos2['nomPersonal'];
                                                    }
                                                } else if ($tipoUsuario == 'administrador') {
                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                        $nombre = $datos2['nomAdministrador'];

                                                    }

                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                        $nombre = $datos2['nomMesaAyuda'];

                                                    }
                                                } else if ($tipoUsuario == 'directiva') {
                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                        $nombre = $datos2['nomDirectivo'];
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" style="font-size: 14px">
                                                    <?php
                                                    echo $nombre;
                                                    ?>

                                                </td>

                                                <td class="text-center" style="font-size: 14px">
                                                    <?php echo $nomEquipo ?>
                                                </td>
                                                <td class="text-center">
                                                    <form method="POST" action="directorio-equipo-inge-clinica.php">
                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                        <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                                <td class="text-center" style="font-size: 14px">
                                                    <form method="POST" action="quitar_observacion_de_lista.php">
                                                        <input type="hidden" value="<?php echo $idCambio ?>" name="idCambio" id="idCambio">
                                                        <button type="submit" class="btn btn-info btn-xs">
                                                            <i class="fas fa-check"></i><b></b></i>
                                                        </button>
                                                    </form>

                                                </td>
                                                <td class="text-center" style="font-size: 14px">
                                                    <form method="POST" action="editar_observacion.php">
                                                        <input type="hidden" value="<?php echo $idCambio ?>" name="idCambio" id="idCambio">
                                                        <button type="submit" class="btn btn-warning btn-xs">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <th scope="col" class="text-center">Usuario Observador</th>
                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                        <th scope="col" class="text-center">Equipo</th>
                                        <th scope="col" class="text-center">Fecha</th>
                                        <th scope="col" class="text-center">Tipo</th>
                                        <th scope="col" class="text-center">Detalle</th>
                                        <th scope="col" class="text-center">Marca</th>
                                        <th scope="col" class="text-center">Editar</th>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalFalla" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando los equipos fuera de servicio </b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                        <thead class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                            <th scope="col" class="text-center">Equipo</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos_falla WHERE idCategoria = $idCarpeta";
                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                            $datos = [];
                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                            $idCambio = $datos['idCambio'];
                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                            $fecha = $datos['fecha'];
                                            $datos['estado'] = $rowObservaciones['estado'];
                                            $estado = $datos['estado'];
                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                            $observaciones = $datos['observaciones'];
                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                            $idEquipo = $datos['idEquipo'];
                                            $datos['nomEquipo'] = $rowObservaciones['nomEquipo'];
                                            $nomEquipo = $datos['nomEquipo'];
                                            $fecha = strtotime($fecha);
                                            $fechaFormateada = date('d/m/Y', $fecha);
                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                            $tipoUsuario1 = $datos['tipoUsuario'];
                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                            $idUsuarioCambia1 = $datos['idUsuarioCambia'];
                                            ?>
                                            <tr>
                                                <?php
                                                if ($tipoUsuario == 'administrativo') {
                                                    $sql1 = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia1";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomAdmTecnicos'] = $row3['nomAdmTecnicos'];
                                                        $nombre1 = $datos3['nomAdmTecnicos'];
                                                    }

                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                    $sql1 = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia1";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomJefe'] = $row3['nomJefe'];
                                                        $nombre1 = $datos3['nomJefe'];
                                                    }

                                                } else if ($tipoUsuario == 'personal') {
                                                    $sql1 = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia1";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomPersonal'] = $row3['nomPersonal'];
                                                        $nombre1 = $datos3['nomPersonal'];
                                                    }
                                                } else if ($tipoUsuario == 'administrador') {
                                                    $sql1 = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia1";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomAdministrador'] = $row3['nomAdministrador'];
                                                        $nombre1 = $datos3['nomAdministrador'];

                                                    }

                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                    $sql1 = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia1";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomMesaAyuda'] = $row3['nomMesaAyuda'];
                                                        $nombre1 = $datos3['nomMesaAyuda'];

                                                    }
                                                } else if ($tipoUsuario == 'directiva') {
                                                    $sql1 = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia1 ";
                                                    $res1 = mysqli_query($conexion, $sql1);
                                                    while ($row3 = $res1->fetch_assoc()) {
                                                        $datos3 = [];
                                                        $datos3['nomDirectivo'] = $row3['nomDirectivo'];
                                                        $nombre1 = $datos3['nomDirectivo'];
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" style="font-size: 14px">
                                                    <?php
                                                    echo $nombre1;
                                                    ?>

                                                </td>

                                                <td class="text-center">
                                                    <?php echo $nomEquipo ?>
                                                </td>
                                                <td class="text-center">
                                                    <form method="POST" action="equipo.php">
                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                        <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <th scope="col" class="text-center">Usuario Observador</th>
                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                        <th scope="col" class="text-center">Equipo</th>
                                        <th scope="col" class="text-center">Fecha</th>
                                        <th scope="col" class="text-center">Tipo</th>
                                        <th scope="col" class="text-center">Detalle</th>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">

                                    <?php

                                    // Array asociativo con códigos y colores de fondo
                                    $botones = array(
                                        "MPI" => array("bg-info", "MANTENIMIENTO PREVENTIVO INTERNO"),
                                        "MRI" => array("bg-danger", "MANTENIMIENTO REPARATIVO INTERNO"),
                                        "MPE" => array("bg-secondary", "MANTENIMIENTO PREVENTIVO EXTERNO"),
                                        "MRE" => array("bg-purple", "MANTENIMIENTO REPARATIVO EXTERNO"),
                                        "OM" => array("bg-primary", "OBRAS MENORES"),
                                        "IS" => array("bg-orange", "INSPECCIÓN Y SUPERVISIÓN")
                                    );

                                    // Recorrer el array y generar HTML
                                    foreach ($botones as $codigo => $info) {
                                        // Obtener las dos primeras letras del código
                                        $codigo_corto = substr($codigo, 0, 3);
                                        $color = $info[0];
                                        $descripcion = $info[1];

                                        $totalMantenimiento = obtieneCantidadTiposMantencion($idCarpeta, $codigo_corto);
                                        $costoMantenimiento = obtieneGastoTiposMantencion($idCarpeta, $codigo_corto);
                                        // Generar el HTML para el botón
                                        if ($totalMantenimiento > 0) {
                                            ?>
                                            <input type="hidden" id="costoMant<?php echo $codigo_corto; ?>" value="<?php echo $costoMantenimiento ?>">
                                            <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                                <button class="btn btn-block btn-md small-box <?php echo $color; ?>" data-toggle="modal" data-target="#<?php echo $codigo_corto; ?>">
                                                    <h4>
                                                        <i class="fas fa-file"></i>
                                                        <?php echo $codigo_corto; ?>
                                                    </h4>
                                                    <input type="hidden" id="cantidadMRI" value="<?php echo $totalMantenimiento ?>">
                                                    Cantidad: <b><?php echo $totalMantenimiento ?></b>
                                                    <br>
                                                    <?php
                                                    if ($costoMantenimiento == 0) {
                                                        echo $costoMantenimiento = '$ 0';
                                                    } else {
                                                        echo $costoMantenimiento = moneda_chilena($costoMantenimiento);
                                                    }
                                                    ?>
                                                </button>
                                            </div>

                                            <div class="modal fade" id="<?php echo $codigo_corto; ?>" tabindex="-1" aria-labelledby="<?php echo $codigo_corto; ?>Label" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header <?php echo $color; ?>">
                                                            <h5 class="modal-title" id="<?php echo $codigo_corto; ?>Label">Estas visualizando los gastos asociados a <?php echo $descripcion . ' (' . $codigo_corto . ')'; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <table id="tabla-hojas-<?php echo $codigo_corto; ?>" class="table table-hover  table-bordered table-sm">
                                                                <thead class="bg-secondary">
                                                                <tr>
                                                                    <th scope="col" class="text-center">N°</th>
                                                                    <th scope="col" class="text-center">Fecha</th>
                                                                    <th scope="col" class="text-center">Tipo</th>
                                                                    <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                    <th scope="col" class="text-center">Solicitud Compra</th>
                                                                    <th scope="col" class="text-center">Hoja Técnica</th>
                                                                    <th scope="col" class="text-center">Monto</th>
                                                                    <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                    <th scope="col" class="text-center">Equipo</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = " . $idCarpeta . "  AND tipoMantencion	 = '" . $codigo_corto . "' ORDER BY fechaGasto DESC";
                                                                $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                $num = 1;

                                                                if ($resEquipo && mysqli_affected_rows($conexion) > 0) {

                                                                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                        $datos = [];
                                                                        $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                        $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                        $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                        $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                        $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                        $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                        $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                        $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                        $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                        $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                        $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                        $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                        $idGasto = $datos['idGasto'];
                                                                        $fechaRegistro = $datos['fechaRegistro'];
                                                                        $mesGasto = $datos['mesGasto'];
                                                                        $fechaGasto = $datos['fechaGasto'];
                                                                        $nomMantenimiento = $datos['nomMantenimiento'];
                                                                        $idHojaTecnica = $datos['idHojaTecnica'];
                                                                        $tipoMantencion = $datos['tipoMantencion'];
                                                                        $solicitudCompra = $datos['solicitudCompra'];
                                                                        $codOrdenCompra = $datos['codOrdenCompra'];
                                                                        $costoTotal = $datos['costoTotal'];
                                                                        $encargadoMant = $datos['encargadoMant'];
                                                                        $idEquipo = $datos['idEquipo'];

                                                                        $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                                        $costoTotal = moneda_chilena($costoTotal);

                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo $num ?></td>
                                                                            <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                            <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                            <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                            <td class="text-center">
                                                                                <?php

                                                                                if ($solicitudCompra == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    ?>
                                                                                    <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                        <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra ?>">
                                                                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                    </form>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php

                                                                                if ($idHojaTecnica == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    ?>
                                                                                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                        <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica ?>">
                                                                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                    </form>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                if ($encargadoMant == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    echo $encargadoMant;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                if ($idEquipo == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    echo $idEquipo;
                                                                                }
                                                                                ?>
                                                                            </td>

                                                                        </tr>
                                                                        <?php
                                                                        $num++;
                                                                    }

                                                                }

                                                                $sqlHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = " . $idCarpeta . "  AND tipoMantencion	 = '" . $codigo_corto . "' ORDER BY fechaOrden DESC";
                                                                $resHT = mysqli_query($conexion, $sqlHT);

                                                                if ($resHT && mysqli_affected_rows($conexion) > 0) {

                                                                    while ($rowHT = $resHT->fetch_assoc()) {

                                                                        $datos = [];

                                                                        $datos['idOrden'] = $rowHT['idOrden'];
                                                                        $datos['fechaOrden'] = $rowHT['fechaOrden'];
                                                                        $datos['tipoMantencion'] = $rowHT['tipoMantencion'];
                                                                        $datos['folioOrden'] = $rowHT['folioOrden'];
                                                                        $datos['ticketAsociado'] = $rowHT['ticketAsociado'];
                                                                        $datos['nomTecnicoRecepcionador'] = $rowHT['nomTecnicoRecepcionador'];
                                                                        $datos['trabajoRealizado'] = $rowHT['trabajoRealizado'];
                                                                        $datos['idEquipo'] = $rowHT['idEquipo'];

                                                                        $idOrden = $datos['idOrden'];
                                                                        $idEquipo = $datos['idEquipo'];
                                                                        $fechaOrden = $datos['fechaOrden'];
                                                                        $tipoMantencion = $datos['tipoMantencion'];
                                                                        $folioOrden = $datos['folioOrden'];
                                                                        $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                        $ticketAsociado = $datos['ticketAsociado'];
                                                                        $trabajoRealizado = $datos['trabajoRealizado'];

                                                                        $trabajoRealizado = substr($trabajoRealizado, 0., 100);

                                                                        $fechaOrden = date('d/m/Y', strtotime($fechaOrden));

                                                                        if ($tipoMantencion == 'MPI') {
                                                                            $nomMantencion = 'MANTENCIÓN PREVENTIVO INTERNO';
                                                                        } else if ($tipoMantencion == 'MRI') {
                                                                            $nomMantencion = 'MANTENCIÓN REPARATIVO INTERNO';
                                                                        } else if ($tipoMantencion == 'MPE') {
                                                                            $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                        } else if ($tipoMantencion == 'MRE') {
                                                                            $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                        } else if ($tipoMantencion == 'OM') {
                                                                            $nomMantencion = 'OBRAS MENORES';
                                                                        } else if ($tipoMantencion == 'IS') {
                                                                            $nomMantencion = 'INSPECCIÓN Y/O SUPERVISIÓN';
                                                                        }

                                                                        $solicitudCompra == '';
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo $num ?></td>
                                                                            <td class="text-center"><?php echo $fechaOrden ?></td>
                                                                            <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                            <td class="text-center"><?php echo $nomMantencion ?></td>
                                                                            <td class="text-center">
                                                                                <?php

                                                                                if ($solicitudCompra == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    ?>
                                                                                    <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                        <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra ?>">
                                                                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                    </form>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php

                                                                                if ($idOrden == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    ?>
                                                                                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                        <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                    </form>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                if ($nomTecnicoRecepcionador == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, "UTF-8");
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                if ($idEquipo == '') {
                                                                                    echo '-';
                                                                                } else {
                                                                                    ?>
                                                                                    <form action="equipo.php" method="post">
                                                                                        <input type="hidden" id="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                                        <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                    </form>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>

                                                                        </tr>
                                                                        <?php
                                                                        $num++;
                                                                    }

                                                                }


                                                                ?>
                                                                </tbody>
                                                                <tfoot class="bg-secondary">
                                                                <tr>
                                                                    <th scope="col" class="text-center">N°</th>
                                                                    <th scope="col" class="text-center">Fecha</th>
                                                                    <th scope="col" class="text-center">Tipo</th>
                                                                    <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                    <th scope="col" class="text-center">Solicitud Compra</th>
                                                                    <th scope="col" class="text-center">Hoja Técnica</th>
                                                                    <th scope="col" class="text-center">Monto</th>
                                                                    <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                    <th scope="col" class="text-center">Equipo</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php
                                        }
                                    }


                                    $totalTicket = obtieneCantidadTicketCarpeta($idCarpeta);

                                    if ($totalTicket > 0) {
                                        ?>

                                        <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                            <button class="btn btn-block btn-md small-box bg-yellow" data-toggle="modal" data-target="#tickets">
                                                <h4>
                                                    <i class="fas fa-ticket-alt"></i>
                                                    Tickets
                                                </h4>
                                                Cantidad: <b><?php echo $totalTicket ?></b>
                                                <br>
                                                &nbsp;
                                            </button>
                                        </div>
                                        <?php
                                    }

                                    $obtenerInventarioEquipos = obtenerInventarioEquipos($idCarpeta);
                                    if ($obtenerInventarioEquipos >= 0) {
                                        ?>
                                        <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                            <button class="btn btn-block btn-md small-box bg-secondary" data-toggle="modal" data-target="#inventarioEquipo">
                                                <h4>
                                                    <i class="fas fa-desktop"></i>
                                                    Inventario
                                                </h4>
                                                Equipos
                                                <br>
                                                &nbsp;
                                            </button>
                                        </div>
                                        <?php
                                    }

                                    $userUsuarioMinuscula = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');

                                    if ($userUsuarioMinuscula == 'adminsdo') {
                                        $rutaAcceso = 'carpeta-hojas-tecnicas2.php';
                                    } else {
                                        //$rutaAcceso = 'carpeta-hojas-tecnicas-inge-clinica.php';
                                        $rutaAcceso = 'carpeta-hojas-tecnicas.php';

                                    }

                                    ?>

                                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                        <form action="<?php echo $rutaAcceso; ?>" method="POST">
                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                            <button class="btn btn-block btn-md small-box bg-primary">
                                                <h4>
                                                    <i class="fas fa-file-signature"></i>
                                                    HT
                                                </h4>
                                                Hojas T&eacute;cnicas<br>
                                                Cantidad: <b><?php echo $cantidadHojasTecnicas ?></b>
                                            </button>
                                        </form>

                                    </div>


                                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                        <form action="carpeta-gastos-inge-clinica.php" method="POST">
                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                            <button class="btn btn-block btn-md small-box bg-secondary">
                                                <h4>
                                                    <i class="fas fa-money-bill"></i>
                                                    Gastos
                                                </h4>
                                                Valor:<br>
                                                <b>
                                                    <?php
                                                    if ($gastoTotal > 0) {
                                                        echo $gastoTotal = moneda_chilena($gastoTotal);
                                                    } else {
                                                        echo '$ 0';
                                                    }
                                                    ?>
                                                </b>
                                            </button>
                                        </form>

                                    </div>


                                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">

                                        <!--<form action="carpeta-soliCompra-inge-clinica.php" method="POST"> -->
                                        <form action="carpeta-solicitudes-compra.php" method="POST">
                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                            <button class="btn btn-block btn-md small-box bg-danger">
                                                <h4>
                                                    <i class="fas fa-cart-plus"></i>
                                                    SC
                                                </h4>
                                                Solicitudes Compra:<br>
                                                <b>
                                                    <?php
                                                    echo $cuentaSoliCompra;
                                                    ?>
                                                </b>
                                            </button>
                                        </form>

                                    </div>


                                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                        <form action="carpeta-archivos.php" method="POST">
                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                            <button class="btn btn-block btn-md small-box bg-pink">
                                                <h4>
                                                    <i class="fas fa-download"></i>
                                                    Archivos
                                                </h4>
                                                Cantidad:<br>
                                                <b>
                                                    <?php
                                                    echo $cuentaArchivos;
                                                    ?>
                                                </b>
                                            </button>
                                        </form>

                                    </div>


                                    <!-- Modal -->
                                    <div class="modal fade" id="tickets" tabindex="-1" aria-labelledby="ticketsLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-purple">
                                                    <h5 class="modal-title" id="ticketsLabel">Estas visualizando las hojas técnicas con ticket cerrados</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <table id="tabla-tickets" class="table table-sm table-bordered table-hover">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>N°</center>
                                                            </th>
                                                            <th>
                                                                <center>Nombre
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Tecnico Recepcionador
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Ticket Asociado
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Equipo
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Acciones
                                                                    <center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php
                                                        $query = "SELECT * FROM ordenes_trabajos_general  WHERE idCategoria = $idCarpeta AND ticketAsociado != '';";

                                                        $res = mysqli_query($conexion, $query);

                                                        $num = 1;

                                                        while ($row = $res->fetch_assoc()) {

                                                            $datos['idOrden'] = $row['idOrden'];
                                                            $datos['nomOrden'] = $row['nomOrden'];
                                                            $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
                                                            $datos['ticketAsociado'] = $row['ticketAsociado'];
                                                            $datos['folioOrden'] = $row['folioOrden'];

                                                            $datos['idEquipo'] = $row['idEquipo'];

                                                            $idOrden = $datos['idOrden'];
                                                            $nomOrden = $datos['nomOrden'];
                                                            $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                            $ticketAsociado = $datos['ticketAsociado'];
                                                            $folioOrden = $datos['folioOrden'];

                                                            $idEquipo = $datos['idEquipo'];

//                                                                        //SQL EQUIPO
//                                                                        $queryEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
//                                                                        $resEquipo = mysqli_query($conexion, $queryEquipo);
//
//                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
//                                                                            $datos1 = [];
//                                                                            $datos1['id'] = $rowEquipo['id'];
//                                                                            $datos1['identificadorUnico'] = $rowEquipo['identificadorUnico'];
//                                                                            $datos1['nombreEquipo'] = $rowEquipo['nombreEquipo'];
//                                                                            $identificadorUnico = $datos1['identificadorUnico'];
//                                                                            $id = $datos1['id'];
//                                                                            $nombreEquipo = $datos1['nombreEquipo'];
//                                                                        }


                                                            ?>
                                                            <tr>
                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><?php echo $num; ?></center>
                                                                </td>
                                                                <td style="vertical-align:middle; font-size: 12px" align="justify">
                                                                    <?php echo mb_convert_case($nomOrden, MB_CASE_UPPER, 'UTF-8');; ?>
                                                                </td>
                                                                <td style="vertical-align:middle; font-size: 12px" align="center">
                                                                    <?php echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                </td>
                                                                <td style="vertical-align:middle; font-size: 12px" align="center">
                                                                    <?php
                                                                    if ($ticketAsociado == '') {
                                                                        echo "SIN INFORMACIÓN";
                                                                    } else {
                                                                        echo mb_convert_case($ticketAsociado, MB_CASE_UPPER, 'UTF-8');
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle; font-size: 12px" align="center">

                                                                    <form action="equipo.php" method="POST" target="_blank">
                                                                        <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                                            <b> <i class="far fa-eye"></i></b>
                                                                        </button>
                                                                    </form>


                                                                </td>
                                                                <td class="text-center">
                                                                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                                        <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                        <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            <?php

                                                            $num = $num + 1;
                                                        }
                                                        ?>


                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>N°</center>
                                                            </th>
                                                            <th>
                                                                <center>Nombre
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Tecnico Recepcionador
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Ticket Asociado
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Equipo
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Acciones
                                                                    <center>
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal fade" id="inventarioEquipo" tabindex="-1" aria-labelledby="inventarioLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-purple">
                                                    <h5 class="modal-title" id="inventarioLabel">Inventario de Equipos para Reemplazo</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <table id="tabla-tickets" class="table table-sm table-bordered table-hover">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>N°</center>
                                                            </th>
                                                            <th>
                                                                <center>Nombre
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Marca
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Modelo
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Cantidad
                                                                    <center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php
                                                        $query = "SELECT * FROM inventario_equipos WHERE categoriaInventario = $idCarpeta;";

                                                        $res = mysqli_query($conexion, $query);

                                                        $numInventario = 1;

                                                        while ($row = $res->fetch_assoc()) {

                                                            $idInventario = $row['idInventario'];

                                                            $equipoInventario = $row['equipoInventario'];

                                                            $marcaInventario = $row['marcaInventario'];

                                                            $modeloInventario = $row['modeloInventario'];

                                                            $cantidadInventario = $row['cantidadInventario'];

                                                            $categoriaInventario = $row['categoriaInventario'];

                                                            $fechaInventario = $row['fechaInventario'];

                                                            ?>

                                                            <tr>
                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><?php echo $numInventario; ?></center>
                                                                </td>

                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><?php echo $equipoInventario; ?></center>
                                                                </td>

                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><?php echo $marcaInventario; ?></center>
                                                                </td>

                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><b><?php echo $modeloInventario; ?></b></center>
                                                                </td>

                                                                <td style="vertical-align:middle; font-size: 12px">
                                                                    <center><?php echo $cantidadInventario; ?></center>
                                                                </td>
                                                            </tr>

                                                            <?php


                                                            $numInventario++;
                                                        }

                                                        ?>
                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>N°</center>
                                                            </th>
                                                            <th>
                                                                <center>Nombre
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Marca
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Modelo
                                                                    <center>
                                                            </th>
                                                            <th>
                                                                <center>Cantidad
                                                                    <center>
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-12">

                        <div class="card card-primary card-outline">

                            <div class="card-header bg-primary">
                                Equipos Registrados
                            </div>

                            <div class="card-body box-profile w">

                                <div class="row text-center" style="display: flex; justify-content:center;">

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <form style="margin: 0; text-align: center;" class="" action="agregar-equipo.php" method="post">

                                            <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                            <button type="submit" class="btn btn-block btn-success"><b><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;A&ntilde;adir un nuevo equipo</b></button>

                                        </form>

                                        <br>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                                        <button type="submit" class="btn btn-block btn-secondary" data-toggle="modal" data-target="#buscadorDinamico"><b><i class="fa-solid fa-eye"></i>&nbsp;&nbsp;Buscar Ruta Equipo</b></button>

                                    </div>

                                    <div class="modal fade" id="buscadorDinamico" tabindex="-1" aria-labelledby="buscadorDinamicoLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-secondary">
                                                    <h5 class="modal-title" id="buscadorDinamicoLabel">Buscador Dinamico</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <label for="variableBuscar">Busqueda:</label>
                                                    <input type="text" class="form-control" name="variableBuscar" oninput="busquedaDinamica()" id="variableBuscar">
                                                    <input type="hidden" name="idCarpetaBuscar" id="idCarpetaBuscar" value="<?php echo $idCarpeta; ?>">
                                                    <br>
                                                    <table id="tablaBuscadorDinamico" style="font-size: 10pt;" class="table table-sm table-bordered table-hover">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>Nombre</center>
                                                            </th>
                                                            <th>
                                                                <center>Numero Inventario</center>
                                                            </th>

                                                            <th>
                                                                <center>Serie</center>
                                                            </th>
                                                            <th>
                                                                <center>Ruta</center>
                                                            </th>
                                                            <th>
                                                                <center>Visualizar</center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th>
                                                                <center>Nombre</center>
                                                            </th>
                                                            <th>
                                                                <center>Numero Inventario</center>
                                                            </th>

                                                            <th>
                                                                <center>Serie</center>
                                                            </th>
                                                            <th>
                                                                <center>Ruta</center>
                                                            </th>
                                                            <th>
                                                                <center>Visualizar</center>
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="table-responsive">
                                        <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
                                            $getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();
                                            $getIDCategoriaSistemaBMS = getIDCategoriaSistemaBMS();

                                            $sqlEquipo = "SELECT nombreEquipo, id, identificadorUnico,idServicio,recinto,pma,piso,estadoEquipo FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta GROUP BY nombreEquipo ORDER BY piso ASC, nombreEquipo ASC;";

                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                            $num = 1;

                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowEquipo['id'];
                                                $datos['identificadorUnico'] = $rowEquipo['identificadorUnico'];
                                                $datos['nombreEquipo'] = $rowEquipo['nombreEquipo'];
                                                $datos['idServicio'] = $rowEquipo['idServicio'];
                                                $datos['recinto'] = $rowEquipo['recinto'];
                                                $datos['pma'] = $rowEquipo['pma'];
                                                $datos['piso'] = $rowEquipo['piso'];
                                                $datos['estadoEquipo'] = $rowEquipo['estadoEquipo'];

                                                $idEquipo = $datos['id'];
                                                $identificadorUnico = $datos['identificadorUnico'];
                                                $nombreEquipo = $datos['nombreEquipo'];
                                                $idServicio = $datos['idServicio'];
                                                $recinto = $datos['recinto'];
                                                $pma = $datos['pma'];
                                                $piso = $datos['piso'];
                                                $estadoEquipo = $datos['estadoEquipo'];

                                                if ($recinto == '') {
                                                    $recinto = '-';
                                                }

                                                if ($pma == '') {
                                                    $pma = '-';
                                                }

                                                if ($piso == '' || $piso == 0) {
                                                    $piso = '-';
                                                }

                                                 $servicio = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                 $res = mysqli_query($conexion, $servicio);
                                                 while ($rowServicio = $res->fetch_assoc()) {
                                                     $datos = [];
                                                     $datos['nomServicio'] = $rowServicio['nomServicio'];
                                                     $nombreServicio = $datos['nomServicio'];
                                                 }

                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $num; ?></td>
                                                    <td style="width: 45%" class="text-justify"><?php echo $nombreEquipo ?></td>
                                                    <td class="text-center">
                                                        <form action="directorio-equipo-inge-clinica.php" method="POST" style="display: inline">
                                                            <input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                            <button type="submit" title="Informacion del equipo" class="btn btn-info btn-sm"><i class="fa-solid fa-door-open"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php

                                                $num++;
                                            }

                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
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
<script>
    $(document).ready(function () {
        $('#tabla-equipos').DataTable({
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
            initComplete: function () {
                this.api().columns([1]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });


            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#tabla-tickets').DataTable({
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
        $('#tablaInfoEquipos').DataTable({
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

<?php

// Array asociativo con códigos y colores de fondo
$botones = array(
    "MPI" => "bg-info",
    "MRI" => "bg-danger",
    "MPE" => "bg-secondary",
    "MRE" => "bg-purple",
    "OM" => "bg-primary",
    "IS" => "bg-orange"
);

// Recorrer el array y generar HTML
foreach ($botones as $codigo => $color) {
    // Obtener las dos primeras letras del código
    $codigo_corto = substr($codigo, 0, 3);

    ?>
    <script>
        $(document).ready(function () {
            $('#tabla-hojas-<?php echo $codigo_corto; ?>').DataTable({
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
    <?php

}

?>


<script>
    $(document).ready(function () {
        $('#tabla-equipo-observacion').DataTable({
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
    $(document).ready(function () {
        $('#tabla-equipo-falla').DataTable({
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
    $(document).ready(function () {
        $('#tabla-gastos-mre').DataTable({
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
    $(document).ready(function () {
        $('#tabla-gastos-mpe').DataTable({
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
    $(document).ready(function () {
        $('#tabla_ht_mpe').DataTable({
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
    $(document).ready(function () {
        $('#tabla_ht_mre').DataTable({
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
    $(document).ready(function () {
        $('#tabla_ht_om').DataTable({
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
    $(document).ready(
        $('.eliminar').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este equipo?',
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
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del equipo eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>

<script>

    let id = document.getElementById('id').value;

    fetch('../sdo-funciones/obtener_tarea_programacion.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            let jsonData = JSON.stringify(data);
            console.log(jsonData);
            anychart.onDocumentReady(function () {
                anychart.format.inputLocale("en");

                // create a data tree
                var treeData = anychart.data.tree(jsonData, "as-tree");
                // create a chart
                var chart = anychart.ganttProject();
                // set the data
                chart.data(treeData);
                // set the container id
                chart.container("container");
                // initiate drawing the chart
                chart.draw();
                // fit elements to the width of the timeline
                chart.fitAll();
            });

        })
        .catch(error => {
            console.log('Error en la consulta Fetch API: ', error)
        })
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let gastoTotal = document.getElementById('gastoTotal').value;

    let costoMRE = document.getElementById('costoMRE').value;
    let costoMPI = document.getElementById('costoMPI').value;
    let costoMRI = document.getElementById('costoMRI').value;
    let costoMPE = document.getElementById('costoMPE').value;
    let costoOM = document.getElementById('costoOM').value;
    let costoIS = document.getElementById('costoIS').value;

    if (costoMRE === "") {
        costoMRE = "0";
    }

    costoMRE = parseInt(costoMRE);
    costoMPI = parseInt(costoMPI);
    costoMRI = parseInt(costoMRI);
    costoMPE = parseInt(costoMPE);
    costoOM = parseInt(costoOM);
    costoIS = parseInt(costoIS);

    console.log(costoMRE)

    if (costoMRE == 0) {
        console.log('entre')
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['Sin Información', 1],
            ]);

            // Set chart options
            var options = {
                'title': ' Estas visualizando un gráfico estándar, que no contiene información.',
                'width': 400,
                'height': 300,
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    } else {
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['MPI', costoMPI],
                ['MRI', costoMRI],
                ['MPE', costoMPE],
                ['MRE', costoMRE],
                ['OM', costoOM],
                ['IS', costoIS],
            ]);

            // Set chart options
            var options = {
                'title': ' Total: ' + gastoTotal,
                'width': 400,
                'height': 300,
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    }


    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.


    let cantidadMRE = document.getElementById('cantidadMRE').value;
    let cantidadMPI = document.getElementById('cantidadMPI').value;
    let cantidadMRI = document.getElementById('cantidadMRI').value;
    let cantidadMPE = document.getElementById('cantidadMPE').value;
    let cantidadOM = document.getElementById('cantidadOM').value;
    let cantidadIS = document.getElementById('cantidadIS').value;

    cantidadMRE = parseInt(cantidadMRE);
    cantidadMPI = parseInt(cantidadMPI);
    cantidadMRI = parseInt(cantidadMRI);
    cantidadMPE = parseInt(cantidadMPE);
    cantidadOM = parseInt(cantidadOM);
    cantidadIS = parseInt(cantidadIS);

    if (cantidadMRE == 0 && cantidadMPI == 0 && cantidadMRI == 0 && cantidadMPE == 0 && cantidadOM == 0 && cantidadIS == 0) {

        console.log("Hola");

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['Sin Información', 1],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {
                title: 'Estas visualizando un gráfico estándar, que no contiene información.',
                width: 400,
                height: 300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }


    } else {

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['MPI', cantidadMPI],
                ['MRI', cantidadMRI],
                ['MPE', cantidadMPE],
                ['MRE', cantidadMRE],
                ['OM', cantidadOM],
                ['IS', cantidadIS],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {
                title: 'Cantidad de tipos de mantenciones',
                width: 400,
                height: 300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }
    }
</script>

<script>
    function busquedaDinamica() {

        let variableBuscar = document.getElementById('variableBuscar').value;
        let idCarpetaBuscar2 = document.getElementById('idCarpetaBuscar').value;

        var columns = [
            {"data": "nombreEquipo"},
            {"data": "numeroInventario"},
            {"data": "numeroSerie"},
            {"data": "rutaEquipo"},
            {
                "data": null,
                "render": function (data, type, row) {
                    // Agrega el formulario y el botón de visualización para cada fila
                    return '' +
                        '<form style="margin: 0; text-align: center;" action="equipo.php" method="post">' +
                        '<input type="hidden" id="idCategoria" name="idCategoria" value="' + row.idCategoria + '">' +
                        '<input type="hidden" id="idEquipo" name="idEquipo" value="' + row.id + '">' +
                        '<center>' +
                        (row.idOrden < 1 ?
                                '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>' :
                                '<button type="submit" title="Informacion del equipo" class="btn btn-primary"><i class="fa-solid fa-door-open"></i></button>'
                        ) +
                        '</center>' +
                        '</form>';
                }

            }
            // Agregar columnas dinámicamente
        ];

        $('#tablaBuscadorDinamico').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-equipo-dinamico-inge-clinica.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.variableBuscar = variableBuscar; // Pasa el valor seleccionado del select como parámetro
                    d.idCarpeta = idCarpetaBuscar2; // Pasa el valor seleccionado del select como parámetro
                    console.log(d);
                },
                "dataSrc": ""
            },
            "columns": columns,
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

            },
            "bDestroy": true
        });
    }
</script>

</body>

</html>

