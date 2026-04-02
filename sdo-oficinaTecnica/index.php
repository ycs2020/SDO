<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anio = date('Y');


$icono = '<i class="far fa-smile-beam fa-lg"></i> !';
if ($horaActual >= 8 && $horaActual <= 11) {
    $mensajeBienvenida = '¡ Buenos Dias';
} elseif ($horaActual >= 12 && $horaActual <= 19) {
    $mensajeBienvenida = '¡ Buenas Tardes';
} elseif ($horaActual >= 20 || $horaActual <= 7) {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
}

// Array con unidades de compra y sus respectivas variables
$unidadesCompra = [
    'INSTRUMENTACIÓN' => 'totalInst',
    'Electricidad' => 'totalElect',
    'Equipos Industriales' => 'totalIndustrial',
    'Inst. Térmicas, Climatización y Refrigeración' => 'totalClima',
    'Logística' => 'totalLogistica',
    'Infraestructura' => 'totalInfraestructura',
    'Movilización' => 'totalMovilizacion',
    'Subdirección de Operaciones' => 'totalOperacional'
];

foreach ($unidadesCompra as $unidad => $variable) {
    $sql = "SELECT SUM(valor) as total FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = '$unidad'";
    $res = mysqli_query($conexion, $sql);
    $row = $res->fetch_assoc();
    $$variable = $row['total'] ? moneda_chilena($row['total']) : '$ 0';
}

$sqlContador = "SELECT 
    (SELECT COUNT(*) FROM solicitudes_sc_sdo_resumen) as totalSolicitudes,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra IN ('Autorizado por la S.D.A', 'Derivado a Abastecimiento')) as totalAprobadas,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Rechazado por la S.D.A') as totalRechazadas,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Anulada') as totalAnuladas,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'SOLICITADA A SECRETARIA SDO') as totalSecretaria,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Revisada por Secretaría SDO') as totalRevisados,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra IN ('ENTREGADO A LA S.D.A', 'Autorizado por la S.D.A')) as totalSDA,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'DERIVADO A ABASTECIMIENTO') as totalAbastecimiento,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Recibido por pañol') as totalPanol,
    (SELECT COUNT(*) FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Completada') as totalCompletadas
";
$resContador = mysqli_query($conexion, $sqlContador);
$datos = $resContador->fetch_assoc();

$totalSolicitudes = $datos['totalSolicitudes'];
$totalAprobadas = $datos['totalAprobadas'];
$totalRechazadas = $datos['totalRechazadas'];
$totalAnuladas = $datos['totalAnuladas'];
$totalSecretaria = $datos['totalSecretaria'];
$totalRevisados = $datos['totalRevisados'];
$totalSDA = $datos['totalSDA'];
$totalAbastecimiento = $datos['totalAbastecimiento'];
$totalPanol = $datos['totalPanol'];
$totalCompletadas = $datos['totalCompletadas'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/oficinaTecnica-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " . $nomUsuario . " " . $icono ?> </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = " . $anio;
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Hojas Tecnicas Año <?php echo $anio; ?></span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = " . $anio;
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Solicitudes de Compra Año <?php echo $anio; ?></span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM equipamiento_hbqp";
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-purple">
                            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Equipamiento</span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <!-- small box -->
                        <?php
                        $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM reportes_generales WHERE YEAR(fechaReporte) = " . $anio;
                        $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                        while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                            $datos = [];
                            $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                            $TOTAL = $datos['TOTAL'];
                        }

                        ?>
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reportes Turno Año <?php echo $anio; ?></span>
                                <span class="info-box-number"><?php echo $TOTAL; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tab_4" data-toggle="tab"> <b>Menu</b> </a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab"> <b>Hojas Técnicas</b> </a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab"><b>Solicitudes de Compra</b></a></li>
                                </ul>
                                <?php
                                if (isset($_GET['loginExito'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Ingreso Exitoso a la Plataforma.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        }).then((result) => {
                                            /* Read more about isConfirmed, isDenied below */
                                            <?php
                                            if ($obtenerSolicitudesSegunNombre > 0) {
                                            ?>
                                            Swal.fire({
                                                title: 'Existen nuevas Solicitudes Creadas.',
                                                confirmButtonText: 'Cerrar',
                                                icon: 'info',
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                <?php
                                                if (isset($_GET['exitoCambioPassword'])) {
                                                ?>

                                                Swal.fire(
                                                    'Contrase&ntilde;a Modificada con Exito.',
                                                    '',
                                                    'success'
                                                )

                                                <?php
                                                }

                                                ?>
                                            })

                                            <?php
                                            } else {

                                            if (isset($_GET['exitoCambioPassword'])) {
                                            ?>

                                            Swal.fire(
                                                'Contrase&ntilde;a Modificada con Exito.',
                                                '',
                                                'success'
                                            )
                                            <?php
                                            }
                                            }

                                            ?>
                                        })
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['exitoCambioPassword'])) {
                                    ?>
                                    <script>
                                        Swal.fire(
                                            'Contrase&ntilde;a Modificada con Exito.',
                                            '',
                                            'success'
                                        )
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_4">
                                        <h5><b>Orden de Trabajo (OT)</b></h5>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos</a>
                                                </div>
                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="trabajos_general_listado_terminado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>
                                                </div>
                                            </div>
                                        <hr>
                                        <h5><b>Listado de Equipos</b></h5>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Listado de Equipos</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Informes de los Departamentos</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="reportes-visualizar.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Reportes Generales</a>

                                            </div>
                                            &nbsp;
                                            <!--
                                            <div class="col-lg-3 col-12">

                                                <a href="ordenes-visualizar.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Recepciones de Trabajo</a>

                                            </div>
                                            -->
                                        </div>

                                        <hr>
                                        <?php

                                        $mostrarOcultar = deshabilitarOpciones();

                                        if ($mostrarOcultar == 'Mostrar') {
                                            ?>
                                            <h5><b>Hojas T&eacute;cnicas</b></h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-book"></i><br>Historial Hojas T&eacute;cnicas</a>

                                                </div>
                                            </div>

                                            <hr>
                                            <?php
                                        }
                                        ?>


                                        <h5><b>Informaci&oacute;n de Funcionarios de SDO</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="jefes-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-users"></i><br>Encargados de Departamento</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="profesionales-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-users"></i><br>Profesionales del Departamento</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="tecnicos-listado.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-users"></i><br>T&eacute;cnicos del Departamento</a>

                                            </div>
                                        </div>

                                        <hr>

                                        <h5><b>Sistema de Tickets</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>

                                            </div>
                                        </div>

                                        <hr>

                                        <h5><b>Solicitudes de Compra</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>Administrar SC - Puesta en Marcha</a>

                                            </div>
                                            &nbsp;
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-shopping-bag"></i><br>Administrar SC - SDO</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Viajes</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-car"></i><br>Viajes</a>
                                                &nbsp;
                                            </div>

                                            <div class="col-lg-3 col-12">
                                                <a href="administrar-moviles.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-ambulance"></i><br>Equipamiento-Moviles</a>

                                            </div>

                                        </div>
                                        <hr>
                                        <h5><b>Consumos Básicos </b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Combustible Mediciones</a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_1">
                                        <div class="col-12">
                                            <br>
                                            <div class="row">
                                                <div class="col-12">

                                                    <div class="row">
                                                        <?php

                                                        date_default_timezone_set('America/Santiago');
                                                        $nowT = date('Y-m', time());
                                                        $anioT = date('Y', time());

                                                        $titulo1 = "Cantidad de Hojas Técnicas Realizadas en Año " . $anioT . " por Departamento";

                                                        $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                        ?>
                                                        <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead class="bg-success">
                                                                <tr>
                                                                    <th scope="col">Departamento</th>
                                                                    <th scope="col" class="text-center">Enero</th>
                                                                    <th scope="col" class="text-center">Febrero</th>
                                                                    <th scope="col" class="text-center">Marzo</th>
                                                                    <th scope="col" class="text-center">Abril</th>
                                                                    <th scope="col" class="text-center">Mayo</th>
                                                                    <th scope="col" class="text-center">Junio</th>
                                                                    <th scope="col" class="text-center">Julio</th>
                                                                    <th scope="col" class="text-center">Agosto</th>
                                                                    <th scope="col" class="text-center">Septiembre</th>
                                                                    <th scope="col" class="text-center">Octubre</th>
                                                                    <th scope="col" class="text-center">Noviembre</th>
                                                                    <th scope="col" class="text-center">Diciembre</th>
                                                                    <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anioT; ?></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php

                                                                $sqlDeptoT = "SELECT * FROM departamentos where nomDepartamento NOT IN ('Control Centralizado') ORDER BY nomDepartamento ASC";

                                                                $resDeptoT = mysqli_query($conexion, $sqlDeptoT);

                                                                $cantPorDepto = 0;

                                                                $cantPorMesTotal = 0;

                                                                $arrayCantTotal = array();

                                                                $totalMes1 = 0;
                                                                $totalMes2 = 0;
                                                                $totalMes3 = 0;
                                                                $totalMes4 = 0;
                                                                $totalMes5 = 0;
                                                                $totalMes6 = 0;
                                                                $totalMes7 = 0;
                                                                $totalMes8 = 0;
                                                                $totalMes9 = 0;
                                                                $totalMes10 = 0;
                                                                $totalMes11 = 0;
                                                                $totalMes12 = 0;


                                                                while ($rowDeptoT = $resDeptoT->fetch_assoc()) {

                                                                    $cantTotalFinal = 0;

                                                                    $idDepartamentoT = $rowDeptoT['idDepartamento'];
                                                                    $nomDepartamentoT = $rowDeptoT['nomDepartamento'];

                                                                    $contarHojasTecnicasAnio = contarHojasTecnicasAnio($idDepartamentoT, $anioT);


                                                                    if ($nomDepartamentoT == 'Informática' || $nomDepartamentoT == 'Ingeniería Operacional') {
                                                                    } else {
                                                                        $cantTotalDepto = 0;
                                                                        ?>

                                                                        <tr>

                                                                            <th scope="row">
                                                                                <?php echo $nomDepartamentoT; ?>
                                                                            </th>

                                                                            <?php

                                                                            for ($i = 1; $i < 13; $i++) {
                                                                                ?>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    $mesHT = '';
                                                                                    if ($i < 13) {
                                                                                        $mesHT = '0' . $i;
                                                                                    }

                                                                                    $fechaT = $anioT . '-' . $mesHT;

                                                                                    $contarHojasTecnicasMesAnio = contarHojasTecnicasMesAnio($idDepartamentoT, $fechaT);

                                                                                    $cantTotalDepto = $cantTotalDepto + $contarHojasTecnicasMesAnio;

                                                                                    if ($contarHojasTecnicasMesAnio < 1) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $contarHojasTecnicasMesAnio;
                                                                                    }


                                                                                    ?>
                                                                                </td>
                                                                                <?php

                                                                                if ($i == 1) {
                                                                                    $totalMes1 = $totalMes1 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 2) {
                                                                                    $totalMes2 = $totalMes2 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 3) {
                                                                                    $totalMes3 = $totalMes3 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 4) {
                                                                                    $totalMes4 = $totalMes4 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 5) {
                                                                                    $totalMes5 = $totalMes5 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 6) {
                                                                                    $totalMes6 = $totalMes6 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 7) {
                                                                                    $totalMes7 = $totalMes7 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 8) {
                                                                                    $totalMes8 = $totalMes8 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 9) {
                                                                                    $totalMes9 = $totalMes9 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 10) {
                                                                                    $totalMes10 = $totalMes10 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 11) {
                                                                                    $totalMes11 = $totalMes11 + $contarHojasTecnicasMesAnio;
                                                                                } else if ($i == 12) {
                                                                                    $totalMes12 = $totalMes12 + $contarHojasTecnicasMesAnio;
                                                                                }


                                                                                $cantTotalFinal = $cantTotalFinal + $cantTotalDepto;
                                                                            }
                                                                            ?>

                                                                            <td class="text-center text-bold">
                                                                                <?php echo $cantTotalDepto; ?>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                }


                                                                array_push($arrayCantTotal, $totalMes1);
                                                                array_push($arrayCantTotal, $totalMes2);
                                                                array_push($arrayCantTotal, $totalMes3);
                                                                array_push($arrayCantTotal, $totalMes4);
                                                                array_push($arrayCantTotal, $totalMes5);
                                                                array_push($arrayCantTotal, $totalMes6);
                                                                array_push($arrayCantTotal, $totalMes7);
                                                                array_push($arrayCantTotal, $totalMes8);
                                                                array_push($arrayCantTotal, $totalMes9);
                                                                array_push($arrayCantTotal, $totalMes10);
                                                                array_push($arrayCantTotal, $totalMes11);
                                                                array_push($arrayCantTotal, $totalMes12);


                                                                ?>

                                                                <tr class="bg-info">
                                                                    <th scope="row">Totales</th>

                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        ?>
                                                                        <td class="text-center text-bold"><?php echo $arrayCantTotal[$i] ?></td>
                                                                        <?php
                                                                        $cantPorMesTotal = $cantPorMesTotal + $arrayCantTotal[$i];
                                                                    }
                                                                    ?>

                                                                    <td class="text-center text-bold"><?php echo $cantPorMesTotal ?></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <hr>

                                            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                                <?php
                                                $activeDash = '';
                                                for ($i = 1; $i < 13; $i++) {

                                                    $mesNow = date('m', time());
                                                    if ($i == $mesNow) {
                                                        $activeDash = 'active';
                                                    } else {
                                                        $activeDash = '';
                                                    }
                                                    ?>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-homea<?php echo $i; ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>

                                            <div class="tab-content" id="pills-tabContent">
                                                <?php
                                                $activeDash2 = '';
                                                for ($i = 1; $i < 13; $i++) {
                                                    $mesAnioTab = date('Y', time());

                                                    if ($i < 10) {
                                                        $mesAnioTab = $mesAnioTab . '-0' . $i;
                                                    } else {
                                                        $mesAnioTab = $mesAnioTab . '-' . $i;
                                                    }

                                                    $mesNow = date('m', time());
                                                    $mesEspañol2Dash = mesEspañol2($mesNow);
                                                    if ($i == $mesNow) {
                                                        $activeDash2 = 'show active';
                                                    } else {
                                                        $activeDash2 = '';
                                                    }

                                                    ?>
                                                    <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
                                                        <div class="row">

                                                            <div class="col-12 col-md-6 h-100">
                                                                <div class="card card-purple collapsed-card">
                                                                    <div class="card-header">
                                                                        <h3 class="card-title"><i class="fas fa-briefcase"></i>&nbsp;&nbsp;<b>Cantidad de Hojas Técnicas por Tipo de Mantenimiento</b></h3>

                                                                        <div class="card-tools">
                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <!-- /.card-tools -->
                                                                    </div>
                                                                    <!-- /.card-header -->
                                                                    <div class="card-body">
                                                                        <table class="table table-hover">
                                                                            <thead style="font-size: 12px">
                                                                            <tr>
                                                                                <th scope="col" class="text-center ">ESPECIALIDAD</th>
                                                                                <th scope="col" class="text-center">MPI</th>
                                                                                <th scope="col" class="text-center">MRI</th>
                                                                                <th scope="col" class="text-center">MPE</th>
                                                                                <th scope="col" class="text-center">MRE</th>
                                                                                <th scope="col" class="text-center">OM</th>
                                                                                <th scope="col" class="text-center">IS</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody style="font-size: 12px">
                                                                            <?php
                                                                            $sqlDeptoT2 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN ('Control Centralizado') ORDER BY nomDepartamento ASC";

                                                                            $resDeptoT2 = mysqli_query($conexion, $sqlDeptoT2);

                                                                            $mesEspañol2Dash2 = mesEspañol2($i);


                                                                            while ($rowDeptoT2 = $resDeptoT2->fetch_assoc()) {

                                                                                $nomDeptoT2 = $rowDeptoT2['nomDepartamento'];

                                                                                $idDeptoT2 = $rowDeptoT2['idDepartamento'];

                                                                                if ($nomDeptoT2 == 'Informática' || $nomDeptoT2 == 'Ingeniería Operacional') {
                                                                                } else {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $nomDeptoT2; ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPI', $mesEspañol2Dash2); ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRI', $mesEspañol2Dash2); ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPE', $mesEspañol2Dash2); ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRE', $mesEspañol2Dash2); ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'OM', $mesEspañol2Dash2); ?></td>
                                                                                        <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'IS', $mesEspañol2Dash2); ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>

                                                                            </tbody>
                                                                            <tfoot>
                                                                            <tr>
                                                                                <td class="text-center">Totales</td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', $mesEspañol2Dash2); ?> </td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', $mesEspañol2Dash2); ?> </td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', $mesEspañol2Dash2); ?> </td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', $mesEspañol2Dash2); ?> </td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('OM', $mesEspañol2Dash2); ?> </td>
                                                                                <td class="text-center"><?php echo obtieneTotalHTTipo('IS', $mesEspañol2Dash2); ?> </td>
                                                                            </tr>
                                                                            </tfoot>

                                                                        </table>
                                                                    </div>
                                                                    <!-- /.card-body -->
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <br>

                                                        <br>

                                                        <?php

                                                        $numCardHT = 1;

                                                        $obtenerCantidadDepartamentos = obtenerCantidadDepartamentos();

                                                        $colorTipoDepartamento = '';

                                                        ?>
                                                        <div class="row">
                                                            <?php


                                                            $sqlDeptoT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN ('Control Centralizado') AND nomDepartamento NOT LIKE 'Ingeniería Operacional'  AND nomDepartamento NOT LIKE 'Informática' ORDER BY nomDepartamento ASC";

                                                            $resDeptoT3 = mysqli_query($conexion, $sqlDeptoT3);

                                                            while ($rowDeptoT3 = $resDeptoT3->fetch_assoc()) {

                                                                $idDeptoT3 = $rowDeptoT3['idDepartamento'];
                                                                $nomDeptoT3 = $rowDeptoT3['nomDepartamento'];

                                                                switch ($numCardHT) {
                                                                    case 1:
                                                                        $colorTipoDepartamento = 'card-success';
                                                                        break;
                                                                    case 2:
                                                                        $colorTipoDepartamento = 'card-purple';
                                                                        break;
                                                                    case 3:
                                                                        $colorTipoDepartamento = 'card-info';
                                                                        break;
                                                                    case 4:
                                                                        $colorTipoDepartamento = 'card-danger';
                                                                        break;
                                                                    case 5:
                                                                        $colorTipoDepartamento = 'card-orange';
                                                                        break;
                                                                    case 6:
                                                                        $colorTipoDepartamento = 'card-yellow';
                                                                        break;
                                                                    case 7:
                                                                        $colorTipoDepartamento = 'card-success';
                                                                        break;
                                                                    case 8:
                                                                        $colorTipoDepartamento = 'card-purple';
                                                                        break;
                                                                    case 9:
                                                                        $colorTipoDepartamento = 'card-info';
                                                                        break;
                                                                    case 10:
                                                                        $colorTipoDepartamento = 'card-danger';
                                                                        break;
                                                                    case 11:
                                                                        $colorTipoDepartamento = 'card-orange';
                                                                        break;
                                                                    case 11:
                                                                        $colorTipoDepartamento = 'card-yellow';
                                                                        break;
                                                                    default:
                                                                        $colorTipoDepartamento = '';
                                                                }

                                                                ?>


                                                                <div class="col-12 col-md-6 h-100">
                                                                    <div class="card <?php echo $colorTipoDepartamento; ?> collapsed-card">
                                                                        <div class="card-header">
                                                                            <h3 class="card-title">&nbsp;&nbsp;<b>
                                                                                    <?php
                                                                                    echo $nomDeptoT3;

                                                                                    $numCardHT = $numCardHT + 1;
                                                                                    ?>
                                                                                </b></h3>

                                                                            <div class="card-tools">
                                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                    <i class="fas fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                            <!-- /.card-tools -->
                                                                        </div>
                                                                        <!-- /.card-header -->
                                                                        <div class="card-body">
                                                                            <table class="table table-hover">
                                                                                <thead style="font-size: 12px">
                                                                                <tr>
                                                                                    <th scope="col" class="text-center">PERSONAL</th>
                                                                                    <th scope="col" class="text-center">CANTIDAD DE DOCUMENTOS</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody style="font-size: 12px">
                                                                                <?php


                                                                                $sql = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idPersonal ORDER BY p.nomPersonal ASC;";
                                                                                $res = mysqli_query($conexion, $sql);
                                                                                $totalDeptoFor = 0;

                                                                                while ($row = $res->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                    $datos['cargoPersonal'] = $row['cargoPersonal'];
                                                                                    $datos['idPersonal'] = $row['idPersonal'];
                                                                                    $datos['total'] = $row['total'];
                                                                                    $nomPersonal = $datos['nomPersonal'];
                                                                                    $cargoPersonal = $datos['cargoPersonal'];
                                                                                    $idPersonal = $datos['idPersonal'];
                                                                                    $total = $datos['total'];

                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $nomPersonal . " (" . $cargoPersonal . ")" ?></td>
                                                                                        <td class="text-center"><?php echo $total ?></td>
                                                                                    </tr>

                                                                                    <?php

                                                                                    $totalDeptoFor = $totalDeptoFor + $total;
                                                                                }

                                                                                $sqlJefeDeptoFor = "SELECT p.idJefe, p.nomJefe,p.cargoJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                                                                $resJefeDeptoFor = mysqli_query($conexion, $sqlJefeDeptoFor);

                                                                                while ($rowJefeDeptoFor = $resJefeDeptoFor->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['nomJefe'] = $rowJefeDeptoFor['nomJefe'];
                                                                                    $datos['cargoJefe'] = $rowJefeDeptoFor['cargoJefe'];
                                                                                    $datos['idJefe'] = $rowJefeDeptoFor['idJefe'];
                                                                                    $datos['total'] = $rowJefeDeptoFor['total'];
                                                                                    $nomJefe = $datos['nomJefe'];
                                                                                    $cargoJefe = $datos['cargoJefe'];
                                                                                    $idJefe = $datos['idJefe'];
                                                                                    $total = $datos['total'];
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $nomJefe . " (" . $cargoJefe . ")" ?></td>
                                                                                        <td class="text-center"><?php echo $total ?></td>
                                                                                    </tr>

                                                                                    <?php

                                                                                    $totalDeptoFor = $totalDeptoFor + $total;
                                                                                }

                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center"><?php echo $totalDeptoFor ?> </td>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- /.card-body -->
                                                                    </div>
                                                                </div>

                                                                <?php


                                                            }

                                                            ?>
                                                        </div>

                                                    </div><!-- fin pestaña -->
                                                    <?php
                                                }
                                                ?>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <?php
                                        $userUsuarioMinusc = mb_convert_case($userUsuario,MB_CASE_LOWER,'UTF-8');
                                        //                                                if ($userUsuarioMinusc == 'adminsdo') {
                                        //                                                    include_once '../sdo-includes/solicitudes_compra_dashboard.php';
                                        //                                                } else {
                                        //                                                    echo 'Deshabilitado';
                                        //                                                }
                                        include_once '../sdo-includes/solicitudes_compra_dashboard.php';

                                        ?>

                                    </div>
                                </div>
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
                }
            }
        });
    });
</script>
<script>
    const Anuladas = document.getElementById('Anuladas').textContent;
    const Rechazadas = document.getElementById('Rechazadas').textContent;
    const Aprobadas = document.getElementById('Aprobadas').textContent;
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Aprobadas', 'Rechazadas', 'Anuladas'],
            datasets: [{
                label: 'Cantidad',
                data: [Aprobadas, Rechazadas, Anuladas],
                borderWidth: 1,
                backgroundColor: ['green', 'red', 'orange'],
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>