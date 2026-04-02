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



if (isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
} else if (isset($_SESSION['idCarpeta'])) {
    $idCarpeta = $_SESSION['idCarpeta'];
} else {
    header('Location: equipamiento_hbqp.php?noIdCarpeta=true');
}

if ($idCarpeta > 0) {

    $_SESSION['idCarpeta'] = $idCarpeta;

    $idCarpeta = mysqli_real_escape_string($conexion, $idCarpeta);

// Obtener datos generales de la categoría
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    $row = $res->fetch_assoc();
    $nomCategoria = $row['nomCategoria'];
    $imagenCategoria = $row['imagenCategoria'];
    $idDepartamento = $row['idDepartamento'];

// Consultas para contar y sumar diferentes datos
    $queries = [
        "cantidadEquiposRegistrados" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta",
        "cuentaEquiposOperativos" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Operativo'",
        "cuentaEquiposConObservaciones" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'OperativoObs'",
        "cantEquiposMantenimiento" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'En Mantenimiento'",
        "cantEquiposFalla" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Fuera de Servicio'",
        "cantEquiposExtraviados" => "SELECT COUNT(*) AS cantidad FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND estadoEquipo LIKE 'Extraviado'",
        "cantidadHojasTecnicas" => "SELECT COUNT(*) AS cantidad FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta",
        "gastoTotal" => "SELECT SUM(costoTotal) AS cantidad FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta",
        "cuentaSoliCompra" => "SELECT COUNT(*) AS cantidad FROM solicitudes_compra_sdo WHERE idCategoria = $idCarpeta",
        "cuentaArchivos" => "SELECT COUNT(*) AS cantidad FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta"
    ];

    $resultados = [];

    foreach ($queries as $key => $query) {
        $res = mysqli_query($conexion, $query);
        $row = $res->fetch_assoc();
        $resultados[$key] = $row['cantidad'];
    }

// Acceder a los resultados
    $cantidadEquiposRegistrados = $resultados['cantidadEquiposRegistrados'];
    $cuentaEquiposOperativos = $resultados['cuentaEquiposOperativos'];
    $cuentaEquiposConObservaciones = $resultados['cuentaEquiposConObservaciones'];
    $cantEquiposMantenimiento = $resultados['cantEquiposMantenimiento'];
    $cantEquiposFalla = $resultados['cantEquiposFalla'];
    $cantEquiposExtraviados = $resultados['cantEquiposExtraviados'];
    $cantidadHojasTecnicas = $resultados['cantidadHojasTecnicas'];
    $gastoTotal = $resultados['gastoTotal'];
    $cuentaSoliCompra = $resultados['cuentaSoliCompra'];
    $cuentaArchivos = $resultados['cuentaArchivos'];

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">

    <style>
        /* ============================
           MODO CLARO (por defecto)
        ============================ */

        /* Texto dentro del campo seleccionado */
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #000000; /* Texto negro */
        }

        /* Opciones en el listado desplegable */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Texto negro */
        }

        /* Fondo del dropdown */
        .select2-container--bootstrap4 .select2-dropdown {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important; /* Azul */
            color: #ffffff !important; /* Texto blanco */
            border: none;
        }

        /* ============================
           MODO OSCURO (con clase .dark-mode en <body>)
        ============================ */

        /* Fondo del campo de selección */
        .dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #343a40 !important; /* Fondo oscuro */
            color: #ffffff !important; /* Texto blanco */
            border-color: #495057 !important;
        }

        /* Texto dentro del campo seleccionado */
        .dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
            color: #ffffff !important;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            color: #ffffff !important;
            border: none;
        }

        /* Dropdown fondo y texto */
        .dark-mode .select2-container--bootstrap4 .select2-dropdown {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        /* Opciones en la lista desplegable */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff !important;
        }

        /* Opción seleccionada en dropdown */
        .dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Opción resaltada con el mouse */
        .dark-mode .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: #ffffff !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
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

                                <button class="btn btn-secondary btn-block btn-md " data-toggle="modal" data-target="#exampleModal">
                                    <i class="fas fa-cog"></i>&nbsp;&nbsp;Equipos Con Observaciones:
                                    <?php

                                    echo '<b>' . $cuentaEquiposConObservaciones . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-warning btn-block btn-md " data-toggle="modal" data-target="#equiposMantenimiento">
                                    <i class="fas fa-check"></i>&nbsp;&nbsp;Equipos en Mantenimiento:
                                    <?php

                                    echo '<b>' . $cantEquiposMantenimiento . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-danger btn-block btn-md " data-toggle="modal" data-target="#modalFalla">
                                    <i class="fas fa-skull-crossbones"></i>&nbsp;&nbsp;Equipos Fuera de Servicio:
                                    <?php

                                    echo '<b>' . $cantEquiposFalla . '</b>';

                                    ?>
                                </button>

                                <button class="btn btn-info btn-block btn-md " data-toggle="modal" data-target="#modalExtraviado">
                                    <i class="fas fa-question"></i>&nbsp;&nbsp;Equipos Extraviados:
                                    <?php

                                    echo '<b>' . $cantEquiposExtraviados. '</b>';

                                    ?>
                                </button>


                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>


                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary">
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

                                        $rows = array();

                                        $sqlBuscaEquipoOBS = "SELECT id FROM equipamiento_hbqp WHERE estadoEquipo LIKE 'OperativoOBS' AND idCategoria = " . $idCarpeta . " ORDER BY nombreEquipo ASC";

                                        $resBuscaEquipoOBS = mysqli_query($conexion, $sqlBuscaEquipoOBS);

                                        if ($resBuscaEquipoOBS && mysqli_num_rows($resBuscaEquipoOBS) > 0) {

                                            while ($rowBuscaEquipoOBS = $resBuscaEquipoOBS->fetch_assoc()) {

                                                $idBuscaEquipoOBS = $rowBuscaEquipoOBS['id'];

                                                if ($idBuscaEquipoOBS > 0) {
                                                    $sqlConObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = " . $idBuscaEquipoOBS . " AND estado LIKE '%OperativoOBS%' ORDER BY fecha DESC LIMIT 1";
                                                    $resConObservaciones = mysqli_query($conexion, $sqlConObservaciones);
                                                    while ($rowConObservaciones = $resConObservaciones->fetch_assoc()) {

                                                        $rows[] = $rowConObservaciones;

                                                    }
                                                }

                                            }

                                            usort($rows, function ($a, $b) {
                                                return strtotime($b['fecha']) - strtotime($a['fecha']);
                                            });

                                            foreach ($rows as $rowConObservaciones) {
                                                $idCambioOBS = $rowConObservaciones['idCambio'];
                                                $fechaOBS = $rowConObservaciones['fecha'];
                                                $estadoOBS = $rowConObservaciones['estado'];
                                                $observacionesOBS = $rowConObservaciones['observaciones'];
                                                $idEquipoOBS = $rowConObservaciones['idEquipo'];
                                                $nombreEquipoOBS = $rowConObservaciones['nombreEquipo'];
                                                $tipoUsuarioOBS = $rowConObservaciones['tipoUsuario'];
                                                $idUsuarioCambiaOBS = $rowConObservaciones['idUsuarioCambia'];

                                                $fechaOBS = strtotime($fechaOBS);
                                                $fechaFormateadaOBS = date('d/m/Y', $fechaOBS);

                                                if ($estadoOBS == 'OperativoObs') {
                                                    $estadoOBS = 'Operativo,Observaciones';
                                                }
                                                ?>
                                                <tr>
                                                    <?php
                                                    if ($tipoUsuarioOBS == 'administrativo') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambiaOBS";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomAdmTecnicos'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }
                                                        }

                                                    } else if ($tipoUsuarioOBS == 'jefe tecnico') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambiaOBS";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomJefe'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }
                                                        }

                                                    } else if ($tipoUsuarioOBS == 'personal') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambiaOBS";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomPersonal'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }
                                                        }
                                                    } else if ($tipoUsuarioOBS == 'administrador') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambiaOBS";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomAdministrador'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }

                                                        }

                                                    } else if ($tipoUsuarioOBS == 'mesa ayuda') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambiaOBS";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomMesaAyuda'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }

                                                        }
                                                    } else if ($tipoUsuarioOBS == 'directiva') {
                                                        $sqlTipoUsuarioOBS = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambiaOBS ";
                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomDirectivo'];
                                                            if (empty($nomUsuarioOBS)) {
                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                            }
                                                        }
                                                    }

                                                    $nomUsuarioOBS2 = mb_convert_case($nomUsuarioOBS, MB_CASE_UPPER, 'UTF-8');
                                                    ?>
                                                    <td class="text-center" style="font-size: 14px">
                                                        <?php
                                                        echo $nomUsuarioOBS;
                                                        ?>

                                                    </td>

                                                    <td class="text-center" style="font-size: 14px">
                                                        <?php echo $nombreEquipoOBS ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php


                                                        $tipoMenuOBS = obtenerTipoMenu($idEquipoOBS);

                                                        if ($tipoMenuOBS == 'nuevo') {
                                                            $rutaMenuOBS = 'equipo_menuNuevo.php';
                                                        } else {
                                                            $rutaMenuOBS = 'equipo_menuAntiguo.php';

                                                        }
                                                        ?>
                                                        <form method="POST" action="<?php echo $rutaMenuOBS ?>">
                                                            <input type="hidden" value="<?php echo $idEquipoOBS ?>" name="idEquipo">
                                                            <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                    </td>
                                                    <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateadaOBS ?></td>
                                                    <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estadoOBS, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                    <td class="text-center" style="font-size: 14px"><?php echo $observacionesOBS ?></td>
                                                    <td class="text-center" style="font-size: 14px">
                                                        <form method="POST" action="quitar_observacion_de_lista.php">
                                                            <input type="hidden" value="<?php echo $idCambioOBS ?>" name="idCambio" id="idCambio">
                                                            <button type="submit" class="btn btn-info btn-xs">
                                                                <i class="fas fa-check"></i><b></b></i>
                                                            </button>
                                                        </form>

                                                    </td>
                                                    <td class="text-center" style="font-size: 14px">
                                                        <form method="POST" action="editar_observacion.php">
                                                            <input type="hidden" value="<?php echo $idCambioOBS ?>" name="idCambio" id="idCambio">
                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }

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
                                            <th scope="col" class="text-center">Recinto</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlBuscaEquipoFS = "SELECT id FROM equipamiento_hbqp WHERE estadoEquipo LIKE 'Fuera de Servicio' AND idCategoria = " . $idCarpeta . " ORDER BY nombreEquipo ASC";

                                        $resBuscaEquipoFS = mysqli_query($conexion, $sqlBuscaEquipoFS);

                                        if ($resBuscaEquipoFS && mysqli_num_rows($resBuscaEquipoFS) > 0) {

                                            while ($rowBuscaEquipoFS = $resBuscaEquipoFS->fetch_assoc()) {

                                                $idBuscaEquipoFS = $rowBuscaEquipoFS['id'];

                                                if ($idBuscaEquipoFS > 0) {
                                                    //ANTIGUAMENTE TENIA EL historial_cambios_equipos
                                                    $sqlFueraServicio = "SELECT * FROM historial_cambios_equipos_falla WHERE idEquipo = " . $idBuscaEquipoFS . " AND estado LIKE '%Fuera de Servicio%' ORDER BY fecha DESC LIMIT 1";
                                                    $resFueraServicio = mysqli_query($conexion, $sqlFueraServicio);
                                                    while ($rowFueraServicio = $resFueraServicio->fetch_assoc()) {

                                                        $idCambioFS = $rowFueraServicio['idCambio'];
                                                        $fechaFS = $rowFueraServicio['fecha'];
                                                        $estadoFS = $rowFueraServicio['estado'];
                                                        $observacionesFS = $rowFueraServicio['observaciones'];
                                                        $idEquipoFS = $rowFueraServicio['idEquipo'];
                                                        $nombreEquipoFS = $rowFueraServicio['nomEquipo'];
                                                        $tipoUsuarioFS = $rowFueraServicio['tipoUsuario'];
                                                        $idUsuarioCambiaFS = $rowFueraServicio['idUsuarioCambia'];

                                                        $fechaFS = strtotime($fechaFS);
                                                        $fechaFormateadaFS = date('d/m/Y', $fechaFS);
                                                        ?>
                                                        <tr>
                                                            <?php
                                                            if ($tipoUsuarioFS == 'administrativo') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambiaFS";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomAdmTecnicos'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }
                                                                }

                                                            } else if ($tipoUsuarioFS == 'jefe tecnico') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambiaFS";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomJefe'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }
                                                                }

                                                            } else if ($tipoUsuarioFS == 'personal') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambiaFS";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomPersonal'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }
                                                                }
                                                            } else if ($tipoUsuarioFS == 'administrador') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambiaFS";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomAdministrador'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }

                                                                }

                                                            } else if ($tipoUsuarioFS == 'mesa ayuda') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambiaFS";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomMesaAyuda'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }

                                                                }
                                                            } else if ($tipoUsuarioFS == 'directiva') {
                                                                $sqlTipoUsuarioFS = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambiaFS ";
                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomDirectivo'];
                                                                    if (empty($nomUsuarioFS)) {
                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                    }
                                                                }
                                                            }

                                                            $nomUsuarioFS2 = mb_convert_case($nomUsuarioFS, MB_CASE_UPPER, 'UTF-8');
                                                            ?>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $nomUsuarioFS2;
                                                                ?>

                                                            </td>

                                                            <td class="text-center">
                                                                <?php echo $nombreEquipoFS ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php


                                                                $tipoMenuFS = obtenerTipoMenu($idEquipoFS);

                                                                if ($tipoMenuFS == 'nuevo') {
                                                                    $rutaMenuFS = 'equipo_menuNuevo.php';
                                                                } else {
                                                                    $rutaMenuFS = 'equipo_menuAntiguo.php';

                                                                }
                                                                ?>
                                                                <form method="POST" action="<?php echo $rutaMenuFS ?>">
                                                                    <input type="hidden" value="<?php echo $idEquipoFS ?>" name="idEquipo">
                                                                    <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                                </form>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateadaFS ?></td>
                                                            <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estadoFS, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                            <td class="text-center" style="font-size: 14px"><?php echo $observacionesFS ?></td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                $sqlRecintoExtraviado = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipoFS";
                                                                $resRecintoExtraviado = mysqli_query($conexion, $sqlRecintoExtraviado);
                                                                while ($rowRecintoExtraviado = $resRecintoExtraviado->fetch_assoc()) {
                                                                    $recintoExtraviado = $rowRecintoExtraviado['recinto'];

                                                                }
                                                                echo $recintoExtraviado;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }

                                                }

                                            }

                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                            <th scope="col" class="text-center">Equipo</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                            <th scope="col" class="text-center">Recinto</th>

                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <form action="../sdo-pdf/SDO-Reporte-Equipos-Fuera-Servicio.php" method="post">
                                        <button type="submit" class="btn btn-md bg-success" style="float: right">Exportar  <i class="fas fa-file-excel"></i></button>
                                        <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-md" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalExtraviado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-purple">
                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando los equipos extraviados </b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-sm" id="tabla-equipo-extraviado">
                                        <thead class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                            <th scope="col" class="text-center">Equipo</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                            <th scope="col" class="text-center">Marca</th>
                                            <th scope="col" class="text-center">Recinto</th>
                                            <th scope="col" class="text-center">Editar</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = " . $idCarpeta . " AND estado LIKE '%Extraviado%' ORDER BY fecha DESC";
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
                                                    <?php


                                                    $tipoMenuE = obtenerTipoMenu($idEquipo);

                                                    if ($tipoMenuE == 'nuevo') {
                                                        $rutaMenuE = 'equipo_menuNuevo.php';
                                                    } else {
                                                        $rutaMenuE = 'equipo_menuAntiguo.php';

                                                    }
                                                    ?>
                                                    <form method="POST" action="<?php echo $rutaMenuE ?>">
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
                                                    <?php
                                                    $sqlRecintoExtraviado = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
                                                    $resRecintoExtraviado = mysqli_query($conexion, $sqlRecintoExtraviado);
                                                    while ($rowRecintoExtraviado = $resRecintoExtraviado->fetch_assoc()) {
                                                        $recintoExtraviado = $rowRecintoExtraviado['recinto'];

                                                    }
                                                    echo $recintoExtraviado;
                                                    ?>
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
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                            <th scope="col" class="text-center">Equipo</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                            <th scope="col" class="text-center">Marca</th>
                                            <th scope="col" class="text-center">Recinto</th>
                                            <th scope="col" class="text-center">Editar</th>

                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="equiposMantenimiento" tabindex="-1" aria-labelledby="equiposMantenimientoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title" id="equiposMantenimientoLabel"><b> Estas visualizando los equipos en Mantenimiento </b></h5>
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
                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = " . $idCarpeta . " AND estado LIKE '%En Mantenimiento%' ORDER BY fecha DESC";
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
                                                    <?php


                                                    $tipoMenuE = obtenerTipoMenu($idEquipo);

                                                    if ($tipoMenuE == 'nuevo') {
                                                        $rutaMenuE = 'equipo_menuNuevo.php';
                                                    } else {
                                                        $rutaMenuE = 'equipo_menuAntiguo.php';

                                                    }
                                                    ?>
                                                    <form method="POST" action="<?php echo $rutaMenuE ?>">
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
                                                                $totalValorUnitario = 0;

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

                                                                        if (!isset($ordenes[$idOrden])) {
                                                                            $ordenes[$idOrden] = [
                                                                                'totalValorUnitario' => 0
                                                                            ];
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
                                                                            <td class="text-center text-success text-bold">
                                                                                <?php
                                                                                // Inicialización
                                                                                $valorUnitarioTotal = 0;
                                                                                $hayMateriales = false;
                                                                                $hayInventario = false;

                                                                                // Consulta para obtener materiales
                                                                                $sqlMateriales = "SELECT nomMaterial FROM material_orden_general WHERE idOrden = $idOrden";
                                                                                $resMateriales = mysqli_query($conexion, $sqlMateriales);

                                                                                if ($resMateriales) {
                                                                                    while ($rowMateriales = $resMateriales->fetch_assoc()) {
                                                                                        $nomMaterial = $rowMateriales['nomMaterial'];
                                                                                        if (!empty($nomMaterial)) {
                                                                                            $hayMateriales = true;

                                                                                            // Consulta para obtener valor unitario
                                                                                            $sqlInventario = "SELECT valorUnitario FROM inventario_equipos WHERE equipoInventario = '$nomMaterial'";
                                                                                            $resInventario = mysqli_query($conexion, $sqlInventario);

                                                                                            if ($resInventario) {
                                                                                                while ($rowInventario = $resInventario->fetch_assoc()) {
                                                                                                    $hayInventario = true;
                                                                                                    $valorUnitarioTotal += $rowInventario['valorUnitario'];
                                                                                                    $totalValorUnitario = $totalValorUnitario + $valorUnitarioTotal;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }

                                                                                // Mostrar el valor total o mensaje de 'Sin información'
                                                                                if ($hayMateriales && $hayInventario) {
                                                                                    echo moneda_chilena($valorUnitarioTotal);
                                                                                } else {
                                                                                    echo 'Sin información';
                                                                                }
                                                                                ?>
                                                                            </td>
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


                                                                                    $tipoMenuE1 = obtenerTipoMenu($idEquipo);

                                                                                    if ($tipoMenuE1 == 'nuevo') {
                                                                                        $rutaMenuE1 = 'equipo_menuNuevo.php';
                                                                                    } else {
                                                                                        $rutaMenuE1 = 'equipo_menuAntiguo.php';

                                                                                    }
                                                                                    ?>
                                                                                    <form method="POST" action="<?php echo $rutaMenuE1 ?>">
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

                                                    if ($costoMantenimiento == 0 && $totalValorUnitario == 0) {
                                                        echo $costoMantenimiento = '$ 0';
                                                    } else {
                                                        echo moneda_chilena($costoMantenimiento + $totalValorUnitario);
                                                        //echo $costoMantenimiento = moneda_chilena($costoMantenimiento);
                                                    }
                                                    ?>
                                                </button>
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


                                    $rutaAcceso = 'carpeta-hojas-tecnicas.php';

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
                                        <form action="carpeta-gastos.php" method="POST">
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
                                                        $query = "SELECT * FROM ordenes_trabajos_general  WHERE idCategoria = $idCarpeta AND ticketAsociado != '' AND ticketAsociado != '0' ORDER BY fechaOrden DESC;";

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
                                                                    <?php



                                                                    $tipoMenuE2 = obtenerTipoMenu($idEquipo);

                                                                    if ($tipoMenuE2 == 'nuevo') {
                                                                        $rutaMenuE2 = 'equipo_menuNuevo.php';
                                                                    } else {
                                                                        $rutaMenuE2 = 'equipo_menuAntiguo.php';

                                                                    }
                                                                    ?>
                                                                    <form method="POST" target="_blank" action="<?php echo $rutaMenuE2 ?>">
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
                                                                <center>Nombre</center>
                                                            </th>
                                                            <th>
                                                                <center>Tecnico Recepcionador</center>
                                                            </th>
                                                            <th>
                                                                <center>Ticket Asociado</center>
                                                            </th>
                                                            <th>
                                                                <center>Equipo</center>
                                                            </th>
                                                            <th>
                                                                <center>Acciones</center>
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
                                                                <center>Nombre</center>
                                                            </th>
                                                            <th>
                                                                <center>Marca</center>
                                                            </th>
                                                            <th>
                                                                <center>Modelo</center>
                                                            </th>
                                                            <th>
                                                                <center>Cantidad</center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php
                                                        $query = "SELECT equipoInventario, marcaInventario, modeloInventario, SUM(cantidadInventario) AS cantidadInventario FROM inventario_equipos  WHERE categoriaInventario = $idCarpeta GROUP BY modeloInventario";

                                                        $res = mysqli_query($conexion, $query);

                                                        $numInventario = 1;

                                                        while ($row = $res->fetch_assoc()) {
                                                            $equipoInventario = $row['equipoInventario'];

                                                            $marcaInventario = $row['marcaInventario'];

                                                            $modeloInventario = $row['modeloInventario'];

                                                            $cantidadInventario = $row['cantidadInventario'];

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
                        <div class="card">
                            <div class="card-header d-flex p-0 bg-success text-white">
                                <ul class="nav nav-pills mr-auto p-2">
                                    <li class="nav-item"><a class="nav-link active text-white" href="#tabEquiposReg" data-toggle="tab">Equipos Registrados</a></li>
                                    <li class="nav-item"><a class="nav-link  text-white " href="#tabModeloEquipos" data-toggle="tab">Modelos de Equipos</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabDocumentos" data-toggle="tab">Documentos</a></li>
                                    <?php
                                        if($idCarpeta == 1){
                                            ?>
                                            <li class="nav-item"><a class="nav-link text-white" href="#tablle" data-toggle="tab">Ibernex</a></li>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                    if($idCarpeta == 2){
                                        ?>
                                        <li class="nav-item"><a class="nav-link text-white" href="#tabumas" data-toggle="tab">Nuevos Equipos</a></li>
                                        <li class="nav-item"><a class="nav-link text-white" href="#tabProgramaVAV" data-toggle="tab">Programacion VAV</a></li>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if($idCarpeta == 71){
                                       ?>
                                        <li class="nav-item"><a class="nav-link text-white" href="#tabProgramacion" data-toggle="tab">Programacion</a></li>
                                        <li class="nav-item"><a class="nav-link text-white" href="#tabFechas" data-toggle="tab">Calendario Mantenimiento</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>

                            </div>

                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tabEquiposReg">
                                        <div class="col-12">
                                            <h5>Equipos Registrados</h5>

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
                                                                            <center>Marca</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Modelo</center>
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
                                                                            <center>Marca</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Modelo</center>
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

                                                <input type="hidden" name="idCarpetaAjax" id="idCarpetaAjax" value="<?php echo $idCarpeta; ?>">
                                                <div class="table-responsive">
                                                    <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th scope="col" class="text-center">ID</th>
                                                            <th scope="col" class="text-center">Nombre Equipo</th>
                                                            <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">PMA</th>
                                                            <th scope="col" class="text-center">Piso</th>
                                                            <th scope="col" class="text-center">Estado</th>
                                                            <th scope="col" class="text-center">Hoja de Vida</th>
                                                            <th scope="col" class="text-center">Informaci&oacute;n</th>
                                                            <th scope="col" class="text-center">Eliminar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th scope="col" class="text-center">ID</th>
                                                            <th scope="col" class="text-center">Nombre Equipo</th>
                                                            <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                            <th scope="col" class="text-center">Recinto</th>
                                                            <th scope="col" class="text-center">PMA</th>
                                                            <th scope="col" class="text-center">Piso</th>
                                                            <th scope="col" class="text-center">Estado</th>
                                                            <th scope="col" class="text-center">Hoja de Vida</th>
                                                            <th scope="col" class="text-center">Informaci&oacute;n</th>
                                                            <th scope="col" class="text-center">Eliminar</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        if($idCarpeta == 2){
                                            ?>
                                            <div class="tab-pane" id="tabProgramaVAV">
                                                <div class="col-12 text-center">
                                                    <h5>Equipos VAV</h5>
                                                    <hr>
                                                    <div class="col-md-4 mb-3 text-center">
                                                        <input type="text" id="buscarVAV" class="form-control" placeholder="Buscar equipo, modelo, recinto...">
                                                    </div>
                                                    <?php
                                                    $sqlBuscarVAV = "SELECT * FROM equipamiento_hbqp 
                                                    WHERE nombreEquipo LIKE '%VAV_%' 
                                                    ORDER BY piso ASC, nombreEquipo ASC, CAST(pma AS UNSIGNED) ASC;
                                                    ";

                                                    $resBuscarVAV = mysqli_query($conexion, $sqlBuscarVAV);

                                                    $countBuscarVAV = mysqli_num_rows($resBuscarVAV);

                                                    ?>
                                                    <div class="row justify-content-center"> <!-- Aquí va la fila -->
                                                        <?php

                                                        while ($rowBuscarVAV = $resBuscarVAV->fetch_assoc()) {
                                                            $nombreEquipoVAV = $rowBuscarVAV['nombreEquipo'];
                                                            $idEquipoVAV = $rowBuscarVAV['id'];
                                                            $modeloVAV = $rowBuscarVAV['modelo'];
                                                            $marcaVAV = $rowBuscarVAV['marca'];
                                                            $modelo2vav = mb_convert_case($modeloVAV, MB_CASE_UPPER, 'UTF-8');
                                                            $marca2vav = mb_convert_case($marcaVAV, MB_CASE_UPPER, 'UTF-8');
                                                            $estadoVav = $rowBuscarVAV['estadoEquipo'];
                                                            $recintoVav = $rowBuscarVAV['recinto'];
                                                            $pmaVav = $rowBuscarVAV['pma'];

                                                            $rutaImagenVAV = getRutaImagenEquipamiento($modelo2vav, $marca2vav);
                                                            ?>
                                                            <div class="col-xl-3 col-lg-4 col-md-4 col-12 mb-3 equipoVAV"
                                                                    data-busqueda="<?php echo strtolower($nombreEquipoVAV.' '.$modelo2vav.' '.$recintoVav.' '.$pmaVav.' '.$estadoVav); ?>">                                                                <div class="card card-primary card-outline">
                                                                    <div class="card-body box-profile w ">
                                                                        <h2><?php echo htmlspecialchars($nombreEquipoVAV); ?></h2>
                                                                        <div class="left-info">
                                                                            <p><span class="label">Estado:</span> <?php echo htmlspecialchars($estadoVav); ?></p>
                                                                            <p><span class="label">Modelo:</span> <?php echo htmlspecialchars($modelo2vav); ?></p>
                                                                            <p><span class="label">Recinto:</span> <?php echo htmlspecialchars($recintoVav); ?></p>
                                                                            <p><span class="label">PMA:</span> <?php echo htmlspecialchars($pmaVav); ?></p>
                                                                        </div>
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
                                        }
                                    ?>
                                    <?php
                                    if($idCarpeta == 71){
                                        ?>
                                        <div class="tab-pane" id="tabProgramacion">
                                            <div class="col-12 text-center">
                                                <h5>UCAS</h5>
                                                <hr>
                                                <?php
                                                // Obtener los pisos distintos desde la base de datos
                                                $sqlPisos = "SELECT DISTINCT piso FROM equipamiento_hbqp WHERE nombreEquipo LIKE ('%UCA-%') ORDER BY piso";
                                                $resPisos = mysqli_query($conexion, $sqlPisos);
                                                ?>

                                                <div class="form-group text-left">
                                                    <label for="filtroPiso">Filtrar por piso:</label>
                                                    <select id="filtroPiso" class="form-control w-auto d-inline-block">
                                                        <option value="todos">Todos</option>
                                                        <?php
                                                        while ($rowPiso = mysqli_fetch_assoc($resPisos)) {
                                                            $piso = $rowPiso['piso'];
                                                            echo "<option value='piso-$piso'>Piso $piso</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                      <?php
                                                $sqlBuscarUcas = "SELECT * FROM equipamiento_hbqp 
                                                WHERE nombreEquipo LIKE '%UCA-%' 
                                                ORDER BY piso ASC, nombreEquipo ASC, CAST(pma AS UNSIGNED) ASC;
                                                ";

//                                                $sqlBuscarUcas = "
//                                                                    SELECT
//                                                                      e.id,
//                                                                      e.nombreEquipo,
//                                                                      e.piso,
//                                                                      e.pma,
//                                                                      e.estadoEquipo,
//                                                                      s.sala,
//                                                                      s.ubicacion
//                                                                    FROM equipamiento_hbqp AS e
//                                                                    LEFT JOIN salas_electricas_comunicacion AS s
//                                                                      ON e.nombreEquipo = s.uca
//                                                                    WHERE e.nombreEquipo LIKE '%UCA-%'
//                                                                    ORDER BY e.piso ASC, e.nombreEquipo ASC, e.pma ASC
//                                                                    ";


                                                $resBuscarUcas = mysqli_query($conexion, $sqlBuscarUcas);

                                                $countBuscarUcas = mysqli_num_rows($resBuscarUcas);

                                                ?>
                                                <p>Equipos visibles: <span id="contadorEquipos"><?php echo $countBuscarUcas; ?></span></p>
                                                <br>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCheckListNoTerminados">
                                                    Revisar CheckList no terminados
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModalCheckListNoTerminados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-info">
                                                                <h5 class="modal-title" id="exampleModalLabel">CheckList no terminados</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-bordered" id="tableCheckListNoTerminados">
                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th>UCA</th>
                                                                        <th>Visualizar OT</th>
                                                                        <th>Personal Asignado</th>
                                                                        <th>Estado de Check List</th>
                                                                        <th>Fecha Programada en la OT</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlBuscarUcasNUEVO = "SELECT * FROM equipamiento_hbqp 
                           WHERE nombreEquipo LIKE '%UCA-%'";

                                                                    $resBuscarUcasNUEVO = mysqli_query($conexion, $sqlBuscarUcasNUEVO);

                                                                    while($rowBuscarUcasNUEVO = mysqli_fetch_assoc($resBuscarUcasNUEVO)){
                                                                        $idEquipoUcasNUEVO = $rowBuscarUcasNUEVO['id'];
                                                                        $nombreEquipoUcasNUEVO = $rowBuscarUcasNUEVO['nombreEquipo'];

                                                                        $sqlRevisarOT = "SELECT * FROM trabajos_general 
                         WHERE estadoTG != 'Trabajo Terminado' 
                         AND idEquipamiento = $idEquipoUcasNUEVO";
                                                                        $resRevisarOT = mysqli_query($conexion,$sqlRevisarOT);

                                                                        while($rowRevisarOT = $resRevisarOT->fetch_assoc()){

                                                                            $idTGCHECKNUEVO = $rowRevisarOT['idTG'];
                                                                            $fechaTGNuevo = $rowRevisarOT['fechaProgramacion'];

                                                                            // ✔ Verificar checklist ANTES de imprimir la fila
                                                                            $sqlBuscarCheckList = "SELECT COUNT(*) AS total 
                                   FROM datos_checklist_ucas 
                                   WHERE idTG = $idTGCHECKNUEVO";

                                                                            $resBuscarCheckList = mysqli_query($conexion,$sqlBuscarCheckList);
                                                                            $rowBuscarCheckList = $resBuscarCheckList->fetch_assoc();
                                                                            $TotalUcas = $rowBuscarCheckList['total'];

                                                                            if ($TotalUcas > 0){
                                                                                // ✔ Si tiene checklist, no se muestra la fila
                                                                                continue;
                                                                            }

                                                                            // Variables de la OT
                                                                            $estadoTGCHECKNUEVO = $rowRevisarOT['estadoTG'];
                                                                            $folioTG = $rowRevisarOT['folioTG'];
                                                                            $tecnico1OTUCANUEVO = $rowRevisarOT['tecnico1'];

                                                                            if($estadoTGCHECKNUEVO == 'Trabajo Terminado'){
                                                                                $formNUEVO = "visualizardetallesterminadas.php";
                                                                            } else {
                                                                                $formNUEVO = "detalle_ordenes_trabajo.php";
                                                                            }
                                                                            ?>

                                                                            <tr>
                                                                                <td><?php echo $nombreEquipoUcasNUEVO ?></td>

                                                                                <td>
                                                                                    <?php echo $folioTG ?>
                                                                                    <form action="<?php echo $formNUEVO ?>" method="post" target="_blank">
                                                                                        <button type="submit" class="btn btn-md bg-secondary">
                                                                                            <i class="fas fa-file"></i>
                                                                                        </button>
                                                                                        <input type="hidden" name="idTG" value="<?php echo $idTGCHECKNUEVO; ?>">
                                                                                    </form>
                                                                                </td>

                                                                                <td><?php echo $tecnico1OTUCANUEVO ?></td>

                                                                                <td>Check List No Realizado</td>
                                                                                <td><?php echo $fechaTGNuevo?></td>

                                                                            </tr>

                                                                            <?php
                                                                        }  // fin while OT
                                                                    } // fin while ucas
                                                                    ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <br>
                                                <div class="row justify-content-center"> <!-- Aquí va la fila -->

                                                    <?php

                                                    while ($rowBuscarUcas = $resBuscarUcas->fetch_assoc()) {
                                                        $nombreEquipoUCAS = $rowBuscarUcas['nombreEquipo'];
                                                        $idEquipoUcas = $rowBuscarUcas['id'];
                                                        $modeloUcas = $rowBuscarUcas['modelo'];
                                                        $marcaUcas = $rowBuscarUcas['marca'];
                                                        $modelo2ucas = mb_convert_case($modeloUcas, MB_CASE_UPPER, 'UTF-8');
                                                        $marca2ucas = mb_convert_case($marcaUcas, MB_CASE_UPPER, 'UTF-8');
                                                        $estadoUcas = $rowBuscarUcas['estadoEquipo'];
                                                        $recintoUcas = $rowBuscarUcas['recinto'];
                                                        $pmaUcas = $rowBuscarUcas['pma'];

                                                        $rutaImagenUCAS = getRutaImagenEquipamiento($modelo2ucas, $marca2ucas);
                                                        ?>

                                                        <div class="col-xl-3 col-lg-4 col-md-4 col-12 mb-3 equipo-card piso-<?php echo $rowBuscarUcas['piso']; ?>">
                                                            <div class="card card-primary card-outline">
                                                                <div class="card-body box-profile w ">
                                                                    <style>
                                                                        .equipo-info {
                                                                            max-width: 400px;
                                                                            background: #f9f9f9;
                                                                            border-radius: 8px;
                                                                            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                                                                            padding: 20px 25px;
                                                                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                                                            color: #333;
                                                                            margin-bottom: 20px;
                                                                        }
                                                                        .equipo-info h2 {
                                                                            margin: 0 0 16px 0;
                                                                            font-weight: 700;
                                                                            color: #2c3e50;
                                                                        }

                                                                        .info-container {
                                                                            display: flex;
                                                                            justify-content: space-between;
                                                                            align-items: flex-start;
                                                                            gap: 20px;
                                                                        }

                                                                        .left-info {
                                                                            flex: 1;
                                                                        }

                                                                        .right-info {
                                                                            flex: 1;
                                                                            text-align: right;
                                                                        }

                                                                        .equipo-info p {
                                                                            margin: 6px 0;
                                                                            font-size: 14px;
                                                                        }
                                                                        .equipo-info .label {
                                                                            font-weight: 600;
                                                                            color: #2980b9;
                                                                        }
                                                                        .equipo-info .fecha {
                                                                            font-style: italic;
                                                                            color: #555;
                                                                        }
                                                                        hr {
                                                                            border: none;
                                                                            border-top: 1px solid #ddd;
                                                                            margin: 20px 0;
                                                                        }
                                                                    </style>

                                                                    <div class="equipo-info">
                                                                        <h2><?php echo htmlspecialchars($nombreEquipoUCAS); ?></h2>
                                                                        <div class="info-container">
                                                                            <div class="left-info">
                                                                                <p><span class="label">Estado:</span> <?php echo htmlspecialchars($estadoUcas); ?></p>
                                                                                <p><span class="label">Modelo:</span> <?php echo htmlspecialchars($modelo2ucas); ?></p>
                                                                                <p><span class="label">Recinto:</span> <?php echo htmlspecialchars($recintoUcas); ?></p>
                                                                                <p><span class="label">PMA:</span> <?php echo htmlspecialchars($pmaUcas); ?></p>
                                                                            </div>
                                                                            <div class="right-info">
                                                                                <?php
                                                                                $sqlUcasFechas = "SELECT fecha FROM `datos_checklist_ucas` 
                                                                                WHERE idEquipamiento = $idEquipoUcas 
                                                                                ORDER BY fecha DESC 
                                                                                LIMIT 1";

                                                                                $resUcasFechas = mysqli_query($conexion, $sqlUcasFechas);

                                                                                if ($resUcasFechas && $rowUcasFechas = $resUcasFechas->fetch_assoc()) {
                                                                                    $fechaRaw = $rowUcasFechas['fecha']; // fecha original
                                                                                    $fechaObj = new DateTime($fechaRaw);

                                                                                    $fecha3Meses = clone $fechaObj;
                                                                                    $fecha3Meses->modify('+3 months');

                                                                                    $fecha6Meses = clone $fechaObj;
                                                                                    $fecha6Meses->modify('+6 months');

                                                                                    $fechaActual = new DateTime(); // hoy

                                                                                    $colorProxima = 'inherit'; // color por defecto

                                                                                    if ($fechaActual > $fecha6Meses) {
                                                                                        $colorProxima = 'red'; // más de 6 meses
                                                                                    } elseif ($fechaActual > $fecha3Meses) {
                                                                                        $colorProxima = 'orange'; // entre 3 y 6 meses (amarillo/ naranja)
                                                                                    }

                                                                                    echo '<p class="fecha">Última mantención: <br>' . htmlspecialchars($fechaObj->format('d-m-Y')) . '</p>';
                                                                                    echo '<p class="fecha" style="color:' . $colorProxima . ';">Próxima mantención: <br>' . htmlspecialchars($fecha6Meses->format('d-m-Y')) . '</p>';
                                                                                } else {
                                                                                    echo '<p class="fecha">No se encontraron mantenciones para este equipo.</p>';
                                                                                }

                                                                                // 1️⃣ Obtener el último idCheckList asociado al equipo
                                                                                $sqlSHAIQL = "
    SELECT id
    FROM datos_checklist_ucas
    WHERE idEquipamiento = $idEquipoUcas
    ORDER BY id DESC
    LIMIT 1
";
                                                                                $resSHAIQL = mysqli_query($conexion, $sqlSHAIQL);

                                                                                if ($rowSHAIQL = mysqli_fetch_assoc($resSHAIQL)) {

                                                                                    $idCheckList = $rowSHAIQL['id'];

                                                                                    // 2️⃣ Obtener SOLO el último archivo donde valorImagen > 50
                                                                                    $sqlSHAI2 = "
                                                                                        SELECT *
                                                                                        FROM archivos_uca_check_list
                                                                                        WHERE idCheckList = $idCheckList
                                                                                          AND CAST(valorImagen AS UNSIGNED) > 50
                                                                                        ORDER BY id DESC
                                                                                        LIMIT 1
                                                                                    ";
                                                                                    $resSHAI2 = mysqli_query($conexion, $sqlSHAI2);

                                                                                    // 3️⃣ Mostrar el único registro permitido
                                                                                    if ($rowSHAI2 = mysqli_fetch_assoc($resSHAI2)) {

                                                                                        $pmaPuerta   = $rowSHAI2['pmaPuerta'];
                                                                                        $valorImagen = $rowSHAI2['valorImagen'];
                                                                                        $pmaPuerta   = $rowSHAI2['pmaPuerta'];
                                                                                        $valorImagen = $rowSHAI2['valorImagen'];

                                                                                        echo '
<div style="
    background:#ff4b4b;
    color:white;
    padding:10px 15px;
    border-radius:6px;
    font-weight:bold;
    margin-bottom:8px;
    border:2px solid #b10000;
    box-shadow:0 0 10px #ff0000;
">
    🚨 PMA Puerta: '.$pmaPuerta.'
</div>

<div style="
    background:#ff4b4b;
    color:white;
    padding:10px 15px;
    border-radius:6px;
    font-weight:bold;
    border:2px solid #b10000;
    box-shadow:0 0 10px #ff0000;
">
    ⚠ Valor T° MAGNETICO: '.$valorImagen.'
</div>
';


                                                                                    }

                                                                                } else {
                                                                                    echo "No existe checklist para este equipo<br>";
                                                                                }


                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                                    <img class=" img-fluid" style="width: 200px; height: 150px;" src="<?php echo $rutaImagenUCAS; ?>" alt="<?php echo $modelo2ucas; ?>">
                                                                    <br><br>
                                                                    <button type="button" class="btn btn-md bg-info" data-toggle="modal" data-target="#ucas<?php echo $idEquipoUcas ?>">
                                                                        Trabajos Programados
                                                                    </button>
                                                                    <button type="button" class="btn btn-md bg-success" data-toggle="modal" data-target="#ucasCheckList<?php echo $idEquipoUcas ?>">
                                                                        Check List
                                                                    </button>
                                                                    <br>
                                                                    <br>
                                                                    <button class="btn bg-purple btn-md" type="button" data-toggle="modal" data-target="#modalUCAOT<?php echo $idEquipoUcas ?>">
                                                                        <i class="fas fa-plus"></i> Agregar MPI UCA
                                                                    </button>
                                                                    <form method="post" style="vertical-align: middle" target="_blank" action="../sdo-funciones/planificacion_mpi_uca.php">
                                                                        <div class="modal fade" id="modalUCAOT<?php echo $idEquipoUcas ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-purple">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar MPI a UCA</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 ml-auto">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Titulo de Tarea:
                                                                                                        <code>*</code></label>
                                                                                                    <input type="text" name="tituloTareaP" id="tituloTareaP" class="form-control" required readonly="readonly" placeholder="Titulo de Tarea..." value="MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE <?php echo $nombreEquipoUCAS ?>">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 ml-auto">

                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Turno Asignado
                                                                                                        <code>*</code></label>
                                                                                                    <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                                                        <option value="0">Seleccione...</option>
                                                                                                        <option value="1">Personal Diurno</option>
                                                                                                        <option value="2">Personal Nocturno</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 ml-auto">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                                                        <code>*</code></label>
                                                                                                    <select class="custom-select" name="funcionarioMPIUCA" required>
                                                                                                        <option value="nulo">Seleccione...</option>

                                                                                                        <?php


                                                                                                        $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                                                        $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                                                        if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                                            while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                                                $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                                                ?>
                                                                                                                <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                                                <?php
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                                                                                                    </select>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 ml-auto">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n:
                                                                                                        <code>*</code></label>
                                                                                                    <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoUcas ?>">
                                                                                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                                                                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                                                                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">
                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </form>
                                                                    <div class="modal fade" id="ucas<?php echo $idEquipoUcas ?>" tabindex="-1" role="dialog" aria-labelledby="ucas<?php echo $idEquipoUcas ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-info">
                                                                                    <h5 class="modal-title">Trabajos Programados: <?php echo $nombreEquipoUCAS?></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="col-12 mb-3">
                                                                                        <?php
                                                                                        $query = 'SELECT * FROM tareas_carta_gantt WHERE idEquipamiento = ' . $idEquipoUcas . ' ORDER BY inicioPlanTareaP ASC';

                                                                                        //                                        echo '<h4>' . $query . '</h4>';
                                                                                        ?>

                                                                                        <div class="table-responsive">

                                                                                            <table id="tableCartaPlanificacion" class="table table-bordered table-hover">
                                                                                                <thead>
                                                                                                <tr class="bg-gradient-dark">
                                                                                                    <th>N°</th>
                                                                                                    <th>Detalle</th>
                                                                                                    <th>Actividad</th>
                                                                                                    <th>Personal</th>
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

                                                                                                    $porcentajeTareaP = obtenerPorcentajeTarea($idTareaP);

                                                                                                    $porcentajeTareaP = intval($porcentajeTareaP);


                                                                                                    if ($contarSubTareaP > 0) {
                                                                                                        $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                                                                                                        $contarCantidadPadre = contarCantidadPadre($idTareaP);

                                                                                                        if ($contarTotalPorcentaje > 0) {
                                                                                                            $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                                                                                                        } else {
                                                                                                            $porcentajePadre = 0;
                                                                                                        }

                                                                                                        $argumentoTR = 'data-widget="expandable-table" aria-expanded="false"';
                                                                                                    } else {
                                                                                                        $contarTotalPorcentaje = $porcentajeTareaP;

                                                                                                        if ($porcentajeTareaP > 0) {
                                                                                                            $porcentajePadre = $porcentajeTareaP;
                                                                                                        } else {
                                                                                                            $porcentajePadre = 0;
                                                                                                        }

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
                                                                                                                <button type="submit" style="text-wrap: nowrap;" id="button<?php echo $numTareaP; ?>" class="btn btn-primary" onclick="mostrarOcultarFilas('info<?php echo $numTareaP; ?>')">
                                                                                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir
                                                                                                                </button>
                                                                                                                <?php
                                                                                                            } else {
                                                                                                                ?>
                                                                                                                <button type="submit" class="btn btn-primary" disabled="disabled">Deshabilitado</button>
                                                                                                                <?php
                                                                                                            }
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td style="width: 25%; vertical-align: middle; text-align: center;">

                                                                                                            <?php

                                                                                                            if (!empty($row['tituloTarea'])) {
                                                                                                                echo mb_convert_case($row['tituloTarea'], MB_CASE_UPPER, 'UTF-8') . '<br><br>';
                                                                                                            }

                                                                                                            ?>


                                                                                                        </td>
                                                                                                        <td style="width: 10%; vertical-align: middle;">
                                                                                                            <?php
                                                                                                            $datosOTCartaGantt = obtenerOTCartaGantt($idTareaP);

                                                                                                            if (is_array($datosOTCartaGantt)) {

                                                                                                                $idTGOT = $datosOTCartaGantt['idTG'];
                                                                                                                $tecnico1OT = $datosOTCartaGantt['tecnico1'];
                                                                                                                $estadoTGOT = $datosOTCartaGantt['estadoTG'];
                                                                                                                $destinoTGOT = '#';

                                                                                                                $tecnico1OT = trim($tecnico1OT);
                                                                                                                if($idTGOT > 0 && !empty($tecnico1OT) && in_array($estadoTGOT, ['Trabajo Ingresado', 'Trabajo Terminado'])){

                                                                                                                    if($estadoTGOT == 'Trabajo Ingresado'){
                                                                                                                        $destinoTGOT = 'detalle_ordenes_trabajo.php';
                                                                                                                    }else if($estadoTGOT == 'Trabajo Terminado' ){
                                                                                                                        $destinoTGOT = 'visualizardetallesterminadas.php';
                                                                                                                    }else{
                                                                                                                        $destinoTGOT = '#';
                                                                                                                    }


                                                                                                                    ?>
                                                                                                                    <form style="margin: 0; text-align: center;" target="_blank" class="" action="<?php echo $destinoTGOT; ?>" method="post">

                                                                                                                        <input type="hidden" name="idTG" value="<?php echo $idTGOT; ?>">
                                                                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-info"><?php echo $tecnico1OT; ?></button>

                                                                                                                    </form>
                                                                                                                    <?php

                                                                                                                }else{
                                                                                                                    echo 'No Aplica';
                                                                                                                }



                                                                                                            } else {
                                                                                                                echo 'No Aplica';
                                                                                                            }


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
                                                                                                                echo $contarTotalPorcentaje;
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

                                                                                                                    <input type="hidden" name="idTareaP" value="<?php echo $row['idTareaP']; ?>">
                                                                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-info">
                                                                                                                        <i class="fas fa-eye"></i>
                                                                                                                    </button>

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
                                                                                                    <th>Personal</th>
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
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal fade" id="ucasCheckList<?php echo $idEquipoUcas ?>" tabindex="-1" role="dialog" aria-labelledby="ucasCheckList<?php echo $idEquipoUcas ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-success">
                                                                                    <h5 class="modal-title">Check List: <?php echo $nombreEquipoUCAS?></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <table class="table table-bordered">
                                                                                        <thead class="bg-secondary">
                                                                                        <tr>
                                                                                            <th>Folio</th>
                                                                                            <th>Fecha</th>
                                                                                            <th>Personal Asignado</th>
                                                                                            <th>Visualizar</th>
                                                                                            <th>Visualizar OT</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        $sqlCheckListUcas = "SELECT * FROM datos_checklist_ucas WHERE idEquipamiento = $idEquipoUcas";
                                                                                        $resCheckListUcas = mysqli_query($conexion,$sqlCheckListUcas);
                                                                                        while($rowCheckListUcas = $resCheckListUcas->fetch_assoc()){
                                                                                            $idTGUCAS = $rowCheckListUcas['idTG'];
                                                                                            ?>
                                                                                                <tr>
                                                                                                    <td><?php echo $rowCheckListUcas['folio']?></td>
                                                                                                    <td><?php echo date('d-m-Y H:i:s', strtotime($rowCheckListUcas['fecha'])); ?></td>
                                                                                                    <td>
                                                                                                        <?php
                                                                                                            $sqlOrdenTrabajo = "SELECT * FROM trabajos_general WHERE idTG = $idTGUCAS";
                                                                                                            $resOrdenTrabajo = mysqli_query($conexion,$sqlOrdenTrabajo);
                                                                                                            while($rowOrdenTrabajo = $resOrdenTrabajo->fetch_assoc()){
                                                                                                                $tecnico1OTUCA = $rowOrdenTrabajo['tecnico1'];
                                                                                                                echo $tecnico1OTUCA;
                                                                                                            }
                                                                                                        ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <form action="../sdo-pdf/SDO-CheckList-Ucas.php" method="POST" target="_blank">
                                                                                                            <button class="bg-info btn " type="submit"><i class="fas fa-eye"></i></button>
                                                                                                            <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $rowCheckListUcas['id']?>">
                                                                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $rowCheckListUcas['idTG']?>">
                                                                                                        </form>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php
                                                                                                        $sqlOrdenTrabajoCHECK = "SELECT * FROM trabajos_general WHERE idTG = $idTGUCAS";
                                                                                                        $resOrdenTrabajoCHECK = mysqli_query($conexion,$sqlOrdenTrabajoCHECK);
                                                                                                        while($rowOrdenTrabajoCHECK = $resOrdenTrabajoCHECK->fetch_assoc()){
                                                                                                            $estadoTGCHECK = $rowOrdenTrabajoCHECK['estadoTG'];
                                                                                                            $idTGCHECK = $rowOrdenTrabajoCHECK['idTG'];
                                                                                                            if($estadoTGCHECK == 'Trabajo Terminado'){
                                                                                                                $form = "visualizardetallesterminadas.php";
                                                                                                            }else{
                                                                                                                $form = "detalle_ordenes_trabajo.php";
                                                                                                            }
                                                                                                            ?>
                                                                                                              <form action="<?php echo $form?>" method="post" target="_blank">
                                                                                                                    <button type="submit" class="btn btn-md bg-secondary"><i class="fas fa-file"></i></button>
                                                                                                                  <input type="hidden" name="idTG" value="<?php echo $idTGCHECK; ?>">
                                                                                                              </form>
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        </tbody>
                                                                                        <tfoot class="bg-secondary">
                                                                                        <tr>
                                                                                            <th>Folio</th>
                                                                                            <th>Fecha</th>
                                                                                            <th>Personal Asignado</th>
                                                                                            <th>Visualizar</th>
                                                                                            <th>Visualizar OT</th>
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
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    }
                                                    ?>
                                                </div> <!-- fin row -->
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tabFechas">
                                            <div id="contenedorFechas" class="mt-4">

                                                <div class="col-12 text-center">

                                                    <!-- ============= TABS ============= -->
                                                    <div class="card">
                                                        <ul class="nav nav-tabs">
                                                            <?php
                                                            $sqlBuscarAños = "
                                                                    SELECT DISTINCT YEAR(fecha) AS anos
                                                                    FROM datos_checklist_ucas
                                                                    ORDER BY anos DESC
                                                                ";
                                                            $resBuscarAños = mysqli_query($conexion, $sqlBuscarAños);

                                                            $primero = true;
                                                            $años = []; // Guardamos los años para el contenido
                                                            while ($row = $resBuscarAños->fetch_assoc()) {
                                                                $year = $row['anos'];
                                                                $años[] = $year; // Guardamos año
                                                                ?>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?= $primero ? 'active' : '' ?>" data-toggle="tab" href="#tab<?= $year ?>"><?= $year ?></a>
                                                                </li>
                                                                <?php
                                                                $primero = false;
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>

                                                    <!-- ============= CONTENIDO DE CADA TAB ============= -->
                                                    <div class="tab-content mt-3">
                                                        <?php
                                                        $primero = true;
                                                        foreach ($años as $year):
                                                            ?>
                                                            <div id="tab<?= $year ?>" class="tab-pane fade <?= $primero ? 'show active' : '' ?>">
                                                                <div class="card mt-3">
                                                                    <div class="card-header">
                                                                        <h4 class="text-center">TABLA DEL AÑO <?= $year ?></h4>
                                                                    </div>
                                                                    <div class="table-responsive" style="max-height: 1000px; overflow-y: auto;">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">DISPOSITIVO</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">ENERO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">FEBRERO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">MARZO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">ABRIL (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">MAYO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">JUNIO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">JULIO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">AGOSTO (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">SEPTIEMBRE (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">OCTUBRE (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">NOVIEMBRE (<?= $year ?>)</th>
                                                                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">DICIEMBRE (<?= $year ?>)</th>
                                                                                <?php if ($year == date("Y")): ?>
                                                                                    <th style="position: sticky; top: 0; background: #fff; z-index: 10;">PRÓXIMO MANTENIMIENTO</th>
                                                                                <?php endif; ?>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            $sqlUcasEquipos = "
        SELECT *
        FROM equipamiento_hbqp
        WHERE nombreEquipo LIKE ('%UCA-%')
        ORDER BY piso ASC, nombreEquipo ASC
    ";
                                                                            $resUcasEquipos = mysqli_query($conexion, $sqlUcasEquipos);

                                                                            while($rowUcasEquipos = $resUcasEquipos->fetch_assoc()):
                                                                                $nombreEquipoUC = $rowUcasEquipos['nombreEquipo'];
                                                                                $nombreEquipoUC_safe = mysqli_real_escape_string($conexion, $nombreEquipoUC);

                                                                                // Traemos los mantenimientos del equipo para el año seleccionado
                                                                                $sqlDATOSUCA = "
            SELECT dcu.*, tg.tecnico1, MONTH(dcu.fecha) as mes, DATE(dcu.fecha) as fechaMant
            FROM datos_checklist_ucas dcu
            LEFT JOIN trabajos_general tg ON dcu.idTG = tg.idTG
            WHERE dcu.uca = '$nombreEquipoUC_safe'
              AND YEAR(dcu.fecha) = $year
        ";
                                                                                $resDatosUCA = mysqli_query($conexion, $sqlDATOSUCA);

                                                                                // Creamos un array para guardar técnicos por mes
                                                                                $tecnicosPorMes = array_fill(1, 12, []); // mes 1..12
                                                                                $ultimaFecha = null;

                                                                                while($row = $resDatosUCA->fetch_assoc()){
                                                                                    $mes = (int)$row['mes'];
                                                                                    $fechaMant = date("Y-m-d", strtotime($row['fechaMant'])); // fecha en formato YYYY-MM-DD
                                                                                    $tecnicosPorMes[$mes][] = $row['tecnico1'] . ' (' . date("d/m/Y", strtotime($row['fechaMant'])) . ')';

                                                                                    // Guardamos la última fecha
                                                                                    if (!$ultimaFecha || strtotime($fechaMant) > strtotime($ultimaFecha)) {
                                                                                        $ultimaFecha = $fechaMant;
                                                                                    }
                                                                                }
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?= $nombreEquipoUC ?></td>

                                                                                    <?php
                                                                                    // Recorremos los meses y mostramos los técnicos separados por <br>
                                                                                    for($m=1; $m<=12; $m++):
                                                                                        $listaTecnicos = !empty($tecnicosPorMes[$m]) ? implode('<br>', $tecnicosPorMes[$m]) : '';
                                                                                        ?>
                                                                                        <td><?= $listaTecnicos ?></td>
                                                                                    <?php endfor; ?>

                                                                                    <?php if ($year == date("Y")): ?>
                                                                                        <td>
                                                                                            <?php
                                                                                            if ($ultimaFecha) {
                                                                                                // Fecha próxima mantención (formato Y-m-d para comparar)
                                                                                                $proxMantFecha = date("Y-m-d", strtotime("+6 months", strtotime($ultimaFecha)));
                                                                                                $proxMantMostrar = date("d/m/Y", strtotime($proxMantFecha));

                                                                                                // Fecha actual
                                                                                                $hoy = date("Y-m-d");

                                                                                                // Si está vencida, texto rojo
                                                                                                $style = ($proxMantFecha < $hoy) ? 'style="color:red; font-weight:bold;"' : '';

                                                                                                echo "<span $style>$proxMantMostrar</span>";
                                                                                            } else {
                                                                                                echo "-";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    <?php endif; ?>
                                                                                </tr>
                                                                            <?php endwhile; ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <?php
                                                            $primero = false;
                                                        endforeach;
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($idCarpeta == 1){
                                        ?>
                                        <div class="tab-pane" id="tablle">
                                            <div class="row justify-content-center"> <!-- Fila principal -->

                                                <div class="col-12 text-center mb-4">
                                                    <h5>Equipos Registrados LLE</h5>
                                                    <hr>
                                                    <select id="filtroPisoLLe" class="form-control mb-3">
                                                        <option value="">Todos los pisos</option>
                                                        <option value="1">Piso 1</option>
                                                        <option value="2">Piso 2</option>
                                                        <option value="3">Piso 3</option>
                                                        <option value="4">Piso 4</option>
                                                        <option value="5">Piso 5</option>
                                                        <option value="6">Piso 6</option>
                                                    </select>
                                                </div>

                                                <div class="row"> <!-- NUEVA FILA PARA LAS CARDS -->

                                                    <?php
                                                    $sqlEquiposMostar = "SELECT DISTINCT pma
                                                    FROM equipamiento_hbqp
                                                    WHERE idCategoria = 1 ORDER BY pma ASC";

                                                    $resEquipoMostrar = mysqli_query($conexion,$sqlEquiposMostar);

                                                    while($rowEquipoMostrar = $resEquipoMostrar->fetch_assoc()){

                                                        $pmaLLE = $rowEquipoMostrar['pma'];

                                                        $partes = explode('-', $pmaLLE);

                                                        $numeroMedio = $partes[1] ?? null;


                                                        ?>

                                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3 card-lle">
                                                            <div class="card card-primary card-outline h-100 bg-white">
                                                                <div class="card-body box-profile text-center">
                                                                    <h2><?php echo $pmaLLE; ?></h2>
                                                                    <hr>
                                                                    <table>
                                                                        <thead>
                                                                        <tr>
                                                                            <td>Modelo</td>
                                                                            <td>Recinto</td>
                                                                            <td>Estado</td>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $equiposPma = "SELECT *
                                                                        FROM equipamiento_hbqp
                                                                        WHERE pma = '$pmaLLE' AND idCategoria = 1 ORDER BY pma ASC";
                                                                        $resEquiposPma = mysqli_query($conexion,$equiposPma);
                                                                        while($rowEquiposPma = $resEquiposPma->fetch_assoc()){
                                                                            $idEquipoPMA = $rowEquiposPma['id'];
                                                                            $nombreEquipoPma = $rowEquiposPma['nombreEquipo'];
                                                                            $recintoEquipoPma = $rowEquiposPma['recinto'];
                                                                            $estadoEquipoPma = $rowEquiposPma['estadoEquipo'];
                                                                            $idCategoriaEquipoPma = $rowEquiposPma['idCategoria'];

                                                                            if($estadoEquipoPma == 'OperativoObs'){
                                                                                $estadoEquipoPma = 'Operativo con Observaciones';
                                                                            }
                                                                           // $pisoEquipoPma = $rowEquiposPma['piso'];
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $nombreEquipoPma?></td>
                                                                                <td><?php echo $recintoEquipoPma?></td>
                                                                                <td>
                                                                                    <?php echo $estadoEquipoPma.'<br>'?>
                                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#estadoIbernex<?php echo $rowEquiposPma['id'] ?>">
                                                                                       <i class="fas fa-eye"></i>
                                                                                    </button>
                                                                                    <!-- Modal -->
                                                                                    <div class="modal fade" id="estadoIbernex<?php echo $rowEquiposPma['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header bg-secondary">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Cambiar el estado del equipo</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <form method="POST" action="../sdo-funciones/cambiaEstadoEquipo_equipo_2.php">
                                                                                                        <input type="hidden" name="idEquipo" value="<?php echo $idEquipoPMA?>">
                                                                                                        <input type="hidden" name="idCategoria" value="<?php echo $idCategoriaEquipoPma?>">
                                                                                                        <input type="hidden" name="nombreEquipo" value="<?php echo $nombreEquipoPma?>">

                                                                                                        <div class="form-group">
                                                                                                            <label>Nuevo Estado:<code>*</code></label>
                                                                                                            <select name="opcionEstado" required class="form-control">
                                                                                                                <option value="Operativo" <?= ($estadoEquipoPma == 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                                                                                                                <option value="Mantenimiento" <?= ($estadoEquipoPma == 'Mantenimiento') ? 'selected' : '' ?>>Mantenimiento</option>
                                                                                                                <option value="Fuera de Servicio" <?= ($estadoEquipoPma == 'Fuera de Servicio') ? 'selected' : '' ?>>Fuera de Servicio</option>
                                                                                                                <option value="OperativoObs" <?= ($estadoEquipoPma == 'OperativoObs' || $estadoEquipoPma == 'Operativo con Observaciones') ? 'selected' : '' ?>>Operativo con Observaciones</option>
                                                                                                                <option value="Extraviado" <?= ($estadoEquipoPma == 'Extraviado') ? 'selected' : '' ?>>Extraviado</option>
                                                                                                            </select>
                                                                                                        </div>

                                                                                                        <div class="form-group">
                                                                                                            <label>Observaciones:<code>*</code></label>
                                                                                                            <textarea name="observaciones" rows="3" class="form-control" required></textarea>
                                                                                                        </div>

                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                                            <button type="submit" class="btn btn-primary">Guardar Estado</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>


                                                                                </td>
                                                                            </tr>
                                                                            <?php

                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php } ?>

                                                </div>

                                            </div>

                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <div class="tab-pane" id="tabDocumentos">
                                        <div class="col-12">
                                            <div class="row">
                                                <fieldset class="border p-3 text-center" style="width: 15%">
                                                    <p><b>Listado de Documentación:</b></p>
                                                    <hr>
                                                    <button type="button" title="Agregar un manual" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarmanual"><i class="fas fa-plus"></i></button>
                                                    <hr>
                                                    <?php
                                                    $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta";
                                                    $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                                    $count = 1;
                                                    while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                                        $nomArchivo = $rowArchivos['nomArchivo'];
                                                        $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                        $idArchivo = $rowArchivos['id'];
                                                        $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
                                                        $extension = strtolower($extension);
                                                        ?>
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <?php if ($extension == 'pdf') { ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                        <i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                <?php } else if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') { ?>
                                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')">
                                                                        <i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?>
                                                                    </button>
                                                                <?php } ?>

                                                                <!-- Botón para modificar el manual -->
                                                                <button type="button" title="Modificar el manual" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modificarmanual<?php echo $idArchivo ?>">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>

                                                                <!-- Modal para modificar el nombre del manual -->
                                                                <div class="modal fade" id="modificarmanual<?php echo $idArchivo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-warning">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Nombre del Manual</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form action="../sdo-funciones/modificar-nomManueales.php" method="POST" enctype="multipart/form-data">
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-12 col-12">
                                                                                            <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                                </div>
                                                                                                <input type="text" class="form-control " required id="nomArchivoModi" name="nomArchivoModi" value="<?php echo $nomArchivo ?>">
                                                                                                <input type="hidden" name="id" id="id" value="<?php echo $idArchivo ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit" class="btn btn-success">Modificar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Botón para eliminar el manual -->
                                                                <button class="btn btn-danger btn-sm eliminarManual" id="<?php echo $idArchivo ?>">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                                <hr>
                                                            </li>
                                                        </ul>
                                                        <br>
                                                        <?php
                                                    }
                                                    ?>
                                                </fieldset>

                                                <fieldset style="width: 85%" class="border p-3 text-center">
                                                    <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px" />
                                                </fieldset>

                                                <script>
                                                    // Función para cambiar el contenido del visor según el tipo de archivo
                                                    function cambiarPDF(rutaArchivo) {
                                                        let extension = rutaArchivo.split('.').pop().toLowerCase();
                                                        let viewer = document.getElementById("pdfViewer");

                                                        if (extension === 'pdf') {
                                                            viewer.outerHTML = `<embed id="pdfViewer" src="${rutaArchivo}" type="application/pdf" width="100%" height="2000px"/>`;
                                                        } else if (extension === 'jpg' || extension === 'png' || extension === 'jpeg') {
                                                            viewer.outerHTML = `<img id="pdfViewer" src="${rutaArchivo}" width="100%" height="900px"/>`;
                                                        }
                                                    }

                                                </script>
                                            </div>
                                            <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/guardar-archivos-carpeta-general.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="0">
                                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomCategoria ?>">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12 col-12">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                            </div>
                                                                            <select class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
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
                                                                            <input type="file" class="form-control form-control-sm" required id="archivoAdjunto" name="archivoAdjunto">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                            </div>
                                                        </form>
                                                        <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoria ?>">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tabModeloEquipos">
                                        <div class="col-12 text-center">
                                            <h5>Modelos de Equipos</h5>

                                            <hr>

                                            <div class="row">

                                                <?php
                                                $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = " . $idCarpeta . " AND (modelo IS NOT NULL AND modelo NOT LIKE '')";
                                                $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);

                                                $sumaTotalSistema = 0;
                                                while ($rowInfoEquipo = $resInfoEquipo->fetch_assoc()) {

                                                    $modelo = $rowInfoEquipo['modelo'];
                                                    $marca = $rowInfoEquipo['marca'];

                                                    if (!empty($modelo)) {
                                                        $modelo2 = $modelo;
                                                    } else {
                                                        $modelo2 = 'No Aplica';
                                                    }

                                                    if (!empty($marca)) {
                                                        $marca2 = $marca;
                                                    } else {
                                                        $marca2 = 'No Aplica';
                                                    }

                                                    $modelo2 = mb_convert_case($modelo2, MB_CASE_UPPER, 'UTF-8');
                                                    $marca2 = mb_convert_case($marca2, MB_CASE_UPPER, 'UTF-8');

                                                    $rutaImagen = getRutaImagenEquipamiento($modelo2, $marca2);

                                                    $getValorEquipamiento = getValorEquipamiento($modelo, $marca);

                                                    if ($getValorEquipamiento < 1) {
                                                        $getValorEquipamiento = 'Sin Información';
                                                    } else {
                                                        $getValorEquipamiento = moneda_chilena($getValorEquipamiento);
                                                    }


                                                    ?>
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body box-profile w ">
                                                                <div class="text-center">
                                                                    <?php

                                                                    if (!empty($rutaImagen)) {
                                                                        ?>
                                                                        <img class=" img-fluid" style="width: 200px; height: 150px;" src="<?php echo $rutaImagen; ?>" alt="<?php echo $modelo2; ?>">
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <img class=" img-fluid" style="width: 200px; height: 150px;" src="img/controlCent-HBQP.png" alt="Control Centralizado">
                                                                        <?php
                                                                    }

                                                                    //                                                                    echo $rutaImagen;

                                                                    ?>


                                                                </div>

                                                                <br>

                                                                <h6 class="text-center text-bold"><?php echo 'Equipo: ' . $modelo2 . '<br>Marca: ' . $marca2 . '<br>Valor Unitario: ' . $getValorEquipamiento ?> </h6>

                                                                <!--                                                                <hr>-->
                                                                <!---->
                                                                <!--                                                                --><?php
                                                                //
                                                                //                                                                echo $rutaImagen;
                                                                //
                                                                //                                                                ?>
                                                                <!--                                <canvas id="myChart" width="300" height="300"></canvas>-->

                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabumas">
                                        <div class="row justify-content-center"> <!-- Fila principal -->
                                            <div class="col-12 text-center mb-4">
                                                <h5>Equipos Registrados UMAS</h5>
                                                <hr>

                                                <!-- 🔽 Filtro por piso -->
                                                <div class="mb-3">
                                                    <label for="filtroPiso" class="form-label fw-bold">Filtrar por piso:</label>
                                                    <select id="filtroPiso" class="form-select w-auto d-inline-block">
                                                        <option value="todos" selected>Todos</option>
                                                        <option value="-1">Piso -1</option>
                                                        <option value="2">Piso 2</option>
                                                        <option value="3">Piso 3</option>
                                                        <option value="7">Piso 7</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <?php
                                            $sqlUmasMostrar = "SELECT * FROM `equipamiento_hbqp` WHERE `idEquipoPadre` = 11142";
                                            $resUmasMostrar = mysqli_query($conexion, $sqlUmasMostrar);

                                            if (mysqli_num_rows($resUmasMostrar) > 0) {
                                                while ($rowUmasMostrar = $resUmasMostrar->fetch_assoc()) {
                                                    $idEquipoUMAS = $rowUmasMostrar['id'];
                                                    $nombreEquipoUMAS = $rowUmasMostrar['nombreEquipo'];
                                                    $codigoUMA = $rowUmasMostrar['codigo'];
                                                    $ubicacionUMA = $rowUmasMostrar['ubicacion'];
                                                    $fechaUMA = $rowUmasMostrar['fechaRegistro'];
                                                    $pisoUMA = $rowUmasMostrar['piso'];
                                                    ?>

                                                    <!-- Cada card tiene un atributo data-piso -->
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3 card-uma" data-piso="<?php echo $pisoUMA; ?>">
                                                        <div class="card card-primary card-outline h-100 bg-white">
                                                            <div class="card-body box-profile w">
                                                                <div class="equipo-info text-center">
                                                                    <h2><?php echo $nombreEquipoUMAS; ?></h2>

                                                                    <!-- Imagen centrada y ajustada -->
                                                                    <div class="equipo-img-container">
                                                                        <img class="equipo-img img-fluid"
                                                                                src="../archivos-equipamiento/Equipo-11142/UMA.JPG"
                                                                                alt="UMAS">
                                                                    </div>

                                                                    <p class="fecha mt-3">Registrado: <?php echo $fechaUMA; ?></p>
                                                                    <p><strong>Piso:</strong> <?php echo $pisoUMA; ?></p>

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <form action="equipo_menuAntiguo.php" method="POST" target="_blank" class="m-0">
                                                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipoUMAS; ?>">
                                                                            <button type="submit" class="btn btn-info btn-sm">Revisar Equipos</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div><!-- /.card-body -->
                                                        </div><!-- /.card -->
                                                    </div><!-- /.col -->

                                                    <?php
                                                }
                                            } else {
                                                echo '<div class="col-12 text-center"><p>No hay equipos UMAS registrados.</p></div>';
                                            }
                                            ?>
                                        </div><!-- /.row -->

                                        <!-- ✅ Estilos -->
                                        <style>
                                            .equipo-info {
                                                background: #ffffff;
                                                border-radius: 8px;
                                                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                                                padding: 20px;
                                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                                color: #333;
                                                height: 100%;
                                                display: flex;
                                                flex-direction: column;
                                                align-items: center;
                                                justify-content: space-between;
                                            }

                                            .equipo-info h2 {
                                                font-weight: 700;
                                                font-size: 18px;
                                                color: #2c3e50;
                                                margin-bottom: 15px;
                                            }

                                            .equipo-img-container {
                                                width: 100%;
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                margin-bottom: 10px;
                                            }

                                            .equipo-img {
                                                max-width: 90%;
                                                height: auto;
                                                border-radius: 6px;
                                                object-fit: contain;
                                                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                                            }

                                            .equipo-info .fecha {
                                                font-size: 13px;
                                                color: #555;
                                                font-style: italic;
                                                margin-top: 10px;
                                            }

                                            .btn-info {
                                                background-color: #3498db;
                                                border-color: #3498db;
                                                color: #fff;
                                                font-weight: 500;
                                            }

                                            .btn-info:hover {
                                                background-color: #2980b9;
                                                border-color: #2980b9;
                                            }
                                        </style>

                                        <!-- ✅ Script para filtrar por piso -->
                                        <script>
                                            document.getElementById('filtroPiso').addEventListener('change', function () {
                                                const valorSeleccionado = this.value;
                                                const cards = document.querySelectorAll('.card-uma');

                                                cards.forEach(card => {
                                                    const piso = card.getAttribute('data-piso');

                                                    if (valorSeleccionado === 'todos' || piso === valorSeleccionado) {
                                                        card.style.display = 'block';
                                                    } else {
                                                        card.style.display = 'none';
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>

                                    <div class="tab-pane" id="tabrelaciones">
                                        <div class="col-12 text-center">
                                            <h5>Relaciones de los Equipos</h5>
                                            <hr>
                                            <table class="table table-bordered table-hover" id="tabla">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <th scope="col" class="text-center">Visualizar Equipos Padre</th>
                                                    <th scope="col" class="text-center">Visualizar Equipos Hijos</th>
                                                    <th scope="col" class="text-center">Visualizar Puntos</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <th scope="col" class="text-center">Visualizar Equipos Padre</th>
                                                    <th scope="col" class="text-center">Visualizar Equipos Hijos</th>
                                                    <th scope="col" class="text-center">Visualizar Puntos</th>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

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

        //Initialize Select2 Elements
        $('.listadoEncInstru').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno',
            theme: 'bootstrap4',
            allowClear: true
        });



    });
</script>
<script>
    $(document).ready(function () {
        let idCarpetaCargar = $('#idCarpetaAjax').val();
        var tablaEquipos = $('#tabla-equipos').DataTable({
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
            "search": {
                "regex": true,
                "smart": false
            },
            initComplete: function() {
                this.api().columns([1]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Equipo...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([2]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Recinto...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([5]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Seleccione Estado...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            },
            "ajax": {
                "url": "sdo-includes/equipos_registrados.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idCarpetaAjax = idCarpetaCargar; // Pasa el valor seleccionado del select como parámetro
                    console.log("Revisar: "+d.idCarpetaAjax);

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
                {
                    "data": "estadoEquipo",
                    "render": function (data, type, row) {

                        // Texto legible para mostrar
                        const estadosTexto = {
                            "Operativo": "Operativo",
                            "Mantenimiento": "Mantenimiento",
                            "Fuera de Servicio": "Fuera de Servicio",
                            "OperativoObs": "Operativo con Observaciones",
                            "Extraviado": "Extraviado"
                        };

                        var modalId = 'modalEquipo_' + row.id;
                        var textoEstado = estadosTexto[data] || data;

                        return `
            <span>${textoEstado}</span><br>
            <button class="btn btn-sm btn-secondary btn-estadoEquipo mt-1" data-toggle="modal" data-target="#${modalId}">
                <i class="fas fa-edit"></i>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="${modalId}Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="${modalId}Label"><b>Cambiar el estado del equipo</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="../sdo-funciones/cambiaEstadoEquipo_equipo_2.php">
                                <input type="hidden" name="idEquipo" value="${row.id}">
                                <input type="hidden" name="idCategoria" value="${row.idCategoria}">
                                <input type="hidden" name="nombreEquipo" value="${row.nombreEquipo}">

                                <div class="form-group">
                                    <label>Nuevo Estado:<code>*</code></label>
                                    <select name="opcionEstado" required class="form-control">
                                        <option value="Operativo" ${data === 'Operativo' ? 'selected' : ''}>Operativo</option>
                                        <option value="Mantenimiento" ${data === 'Mantenimiento' ? 'selected' : ''}>Mantenimiento</option>
                                        <option value="Fuera de Servicio" ${data === 'Fuera de Servicio' ? 'selected' : ''}>Fuera de Servicio</option>
                                        <option value="OperativoObs" ${data === 'OperativoObs' ? 'selected' : ''}>Operativo con Observaciones</option>
                                        <option value="Extraviado" ${data === 'Extraviado' ? 'selected' : ''}>Extraviado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Observaciones:<code>*</code></label>
                                    <textarea name="observaciones" rows="3" class="form-control" required></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Estado</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
                    }
                },



                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-primary btn-hojaVida" data-idequipover="' + row.id + '"><i class="fas fa-file"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-info btn-infoEquipo" data-idequipoinfo="' + row.id + '" data-idcategoriainfo="' + row.idCategoria + '" data-tipomenu="' + row.tipoMenu + '"><i class="fas fa-search"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id > 0) {
                            return '<button class="btn btn-danger btn-eliminarEquipo" data-ideliminar="' + row.id + '"><i class="fas fa-trash"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                }




                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                // Aplica el estilo de centrado vertical y horizontal a todas las celdas
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center'
                });

                // Actualiza el contenido de la primera celda (columna "N°")
                $('td:eq(0)', row).html(dataIndex + 1);

                // Aplica el estilo a la columna 5 (estadoEquipo)
                var column5 = $('td:eq(5)', row);
                var estado = data.estadoEquipo; // viene limpio

                column5.css({
                    'font-weight': 'bold',
                    'text-transform': 'uppercase'
                });

                if (estado === 'Operativo') {
                    column5.addClass('text-success');
                    column5.prepend('<i class="fas fa-check"></i> ');
                }
                else if (estado === 'Mantenimiento') {
                    column5.addClass('text-warning');
                    column5.prepend('<i class="fas fa-exclamation-triangle"></i> ');
                }
                else if (estado === 'OperativoObs') {
                    column5.addClass('text-secondary');
                    column5.prepend('<i class="fas fa-exclamation-circle"></i> ');
                }
                else if (estado === 'Extraviado') {
                    column5.css('color', 'purple'); // color morado
                    column5.prepend('<i class="fas fa-question"></i> '); // icono de interrogación
                }
                else { // cualquier otro estado
                    column5.addClass('text-danger');
                    column5.prepend('<i class="fas fa-skull-crossbones"></i> ');
                }
            }

        });

        $('#tabla-equipos').on('click', '.btn-hojaVida', function () {
            var id = $(this).data('idequipover');

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

        $('#tabla-equipos').on('click', '.btn-infoEquipo', function () {
            var idEquipo = $(this).data('idequipoinfo');
            var idCategoria = $(this).data('idcategoriainfo');
            var tipomenu = $(this).data('tipomenu');
            console.log('idEquipo Capturado:', idEquipo); // Verifica si el ID está siendo capturado correctamente
            console.log('idCategoria Capturado:', idCategoria); // Verifica si el ID está siendo capturado correctamente
            console.log('tipoMenu Capturado:', tipomenu); // Verifica si el ID está siendo capturado correctamente

            if(tipomenu === 'nuevo'){

                var form = $('<form>', {
                    'action': 'equipo_menuNuevo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                });
            }else{

                var form = $('<form>', {
                    'action': 'equipo_menuAntiguo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                    'method': 'POST',
                    'target': '_blank',
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


        $('#tabla-equipos').on('click', '.btn-eliminarEquipo', function (e) {
            e.preventDefault(); // Previene la acción por defecto del botón

            var id = $(this).data('ideliminar'); // Obtiene el ID del botón

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
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('Eliminado: ' + dataString);
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
    $(document).ready(function () {
        $('#tableCheckListNoTerminados').DataTable({
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
    document.getElementById('filtroPiso').addEventListener('change', function () {
        const valorSeleccionado = this.value;
        const tarjetas = document.querySelectorAll('.equipo-card');
        let contador = 0;

        tarjetas.forEach(function (tarjeta) {
            if (valorSeleccionado === 'todos') {
                tarjeta.style.display = 'block';
                contador++;
            } else {
                if (tarjeta.classList.contains(valorSeleccionado)) {
                    tarjeta.style.display = 'block';
                    contador++;
                } else {
                    tarjeta.style.display = 'none';
                }
            }
        });

        document.getElementById('contadorEquipos').textContent = contador;
    });
</script>
<script>
    function busquedaDinamica() {

        let variableBuscar = document.getElementById('variableBuscar').value;
        let idCarpetaBuscar2 = document.getElementById('idCarpetaBuscar').value;

        var columns = [
            {"data": "nombreEquipo"},
            {"data": "marca"},
            {"data": "modelo"},
            {"data": "rutaEquipo"},
            {
                "data": null,
                "render": function (data, type, row) {
                    // Define la URL de acción del formulario basado en el valor de row.tipoMenu
                    var formAction = (row.tipoMenu === 'nuevo') ? 'equipo_menuNuevo.php' : 'equipo_menuAntiguo.php';

                    // Agrega el formulario y el botón de visualización para cada fila
                    return '' +
                        '<form style="margin: 0; text-align: center;" action="' + formAction + '" method="post">' +
                        '<input type="hidden" id="idCategoria" name="idCategoria" value="' + row.idCategoria + '">' +
                        '<input type="hidden" id="idEquipo" name="idEquipo" value="' + row.id + '">' +
                        '<center>' +
                        (row.id < 1 ?
                                '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>' :
                                '<button type="submit" title="Información del equipo" class="btn btn-primary"><i class="fa-solid fa-door-open"></i></button>'
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
                "url": "ajax-equipo-dinamico.php", // Archivo PHP que contiene la consulta SQL
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
<script>

    $(document).ready(function () {

        //Initialize Select2 Elements


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
        $('.sourceAlarmaAgregar').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });
        $('.tipoInterfazAgregar').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>

<script>
    $('#filtroPisoLle').on('change', function() {
        var piso = $(this).val();

        $('.card-lle').each(function() {
            var pma = $(this).find('h2').text(); // Ej: D-3-12
            var partes = pma.split('-');
            var pisoCard = partes[1];

            if(piso === '' || pisoCard === piso){
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
</script>
<script>
    document.getElementById("buscarVAV").addEventListener("keyup", function() {

        let filtro = this.value.toLowerCase();
        let equipos = document.querySelectorAll(".equipoVAV");

        equipos.forEach(function(equipo){

            let texto = equipo.getAttribute("data-busqueda");

            if(texto.includes(filtro)){
                equipo.style.display = "";
            }else{
                equipo.style.display = "none";
            }

        });

    });
</script>
</body>

</html>

