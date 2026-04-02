<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');

if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else if ($horaActual >= '12' && $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';

}


//resumen solicitudes de compra

//CALCULA VALOR DE INSTRUMENTACIÓN
$sqlSC = "SELECT SUM(valor) as totalInst FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'INSTRUMENTACIÓN' ";
$res = mysqli_query($conexion, $sqlSC);

while ($rowTotalInst = $res->fetch_assoc()) {
    $datos = [];
    $datos['totalInst'] = $rowTotalInst['totalInst'];
    $totalInst = $datos['totalInst'];
}


//CALCULA VALOR DE ELECTRICO
$sqlElectrico = "SELECT SUM(valor) as totalElect FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Electricidad' ";
$resElectrico = mysqli_query($conexion, $sqlElectrico);

while ($rowElectrico = $resElectrico->fetch_assoc()) {
    $datos = [];
    $datos['totalElect'] = $rowElectrico['totalElect'];
    $totalElect = $datos['totalElect'];
}

//CALCULA VALOR DE INDUSTRIAL
$sqlIndustrial = "SELECT SUM(valor) as totalIndustrial FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Equipos Industriales' ";
$resIndustrial = mysqli_query($conexion, $sqlIndustrial);

while ($rowIndustrial = $resIndustrial->fetch_assoc()) {
    $datos = [];
    $datos['totalIndustrial'] = $rowIndustrial['totalIndustrial'];
    $totalIndustrial = $datos['totalIndustrial'];
}

//CALCULA VALOR DE CLIMA
$sqlClima = "SELECT SUM(valor) as totalClima FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Inst. Térmicas, Climatización y Refrigeración' ";
$resClima = mysqli_query($conexion, $sqlClima);

while ($rowClima = $resClima->fetch_assoc()) {
    $datos = [];
    $datos['totalClima'] = $rowClima['totalClima'];
    $totalClima = $datos['totalClima'];
}

//CALCULA VALOR DE LOGISTICA
$sqlLogistica = "SELECT SUM(valor) as totalLogistica FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Logística' ";
$resLogistica = mysqli_query($conexion, $sqlLogistica);

while ($rowLogistica = $resLogistica->fetch_assoc()) {
    $datos = [];
    $datos['totalLogistica'] = $rowLogistica['totalLogistica'];
    $totalLogistica = $datos['totalLogistica'];
}


//CALCULA VALOR DE INFRAESTRUCTURA
$sqlInfraestructura = "SELECT SUM(valor) as totalInfraestructura FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Infraestructura' ";
$resInfraestructura = mysqli_query($conexion, $sqlInfraestructura);

while ($rowInfraestructura = $resInfraestructura->fetch_assoc()) {
    $datos = [];
    $datos['totalInfraestructura'] = $rowInfraestructura['totalInfraestructura'];
    $totalInfraestructura = $datos['totalInfraestructura'];
}

//CALCULA VALOR DE MOVILIZACIÓN
$sqlMovilizacion = "SELECT SUM(valor) as totalMovilizacion FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Movilización' ";
$resMovilizacion = mysqli_query($conexion, $sqlMovilizacion);

while ($rowMovilizacion = $resMovilizacion->fetch_assoc()) {
    $datos = [];
    $datos['totalMovilizacion'] = $rowMovilizacion['totalMovilizacion'];
    $totalMovilizacion = $datos['totalMovilizacion'];
}

//CALCULA VALOR DE MOVILIZACIÓN
$sqlOperacional = "SELECT SUM(valor) as totalOperacional FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra = 'Subdirección de Operaciones' ";
$resOperacional = mysqli_query($conexion, $sqlOperacional);

while ($rowOperacional = $resOperacional->fetch_assoc()) {
    $datos = [];
    $datos['totalOperacional'] = $rowOperacional['totalOperacional'];
    $totalOperacional = $datos['totalOperacional'];
}

$totalInst = moneda_chilena($totalInst);
$totalIndustrial = moneda_chilena($totalIndustrial);
$totalClima = moneda_chilena($totalClima);
$totalInfraestructura = moneda_chilena($totalInfraestructura);
$totalMovilizacion = moneda_chilena($totalMovilizacion);

if ($totalOperacional == 0) {
    $totalOperacional = '$ 0';
} else {
    $totalOperacional = moneda_chilena($totalOperacional);
}

if ($totalElect == 0) {
    $totalElect = '$ 0';
} else {
    $totalElect = moneda_chilena($totalElect);
}

if ($totalLogistica == 0) {
    $totalLogistica = '$ 0';
} else {
    $totalLogistica = moneda_chilena($totalLogistica);
}


$sqlContador = "SELECT COUNT(*) AS totalSolicitudes FROM  solicitudes_sc_sdo_resumen ";
$resContador = mysqli_query($conexion, $sqlContador);

while ($rowContador = $resContador->fetch_assoc()) {
    $datos = [];
    $datos['totalSolicitudes'] = $rowContador['totalSolicitudes'];
    $totalSolicitudes = $datos['totalSolicitudes'];
}


$sqlContador1 = "SELECT COUNT(*) AS totalAprobadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Autorizado por la S.D.A' || estadoSoliCompra =  'Derivado a Abastecimiento' ";
$resContador1 = mysqli_query($conexion, $sqlContador1);

while ($rowContador1 = $resContador1->fetch_assoc()) {
    $datos1 = [];
    $datos1['totalAprobadas'] = $rowContador1['totalAprobadas'];
    $totalAprobadas = $datos1['totalAprobadas'];
}


$sqlContador2 = "SELECT COUNT(*) AS totalRechazadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Rechazado por la S.D.A' ";
$resContador2 = mysqli_query($conexion, $sqlContador2);

while ($rowContador2 = $resContador2->fetch_assoc()) {
    $datos2 = [];
    $datos2['totalRechazadas'] = $rowContador2['totalRechazadas'];
    $totalRechazadas = $datos2['totalRechazadas'];
}


$sqlContador3 = "SELECT COUNT(*) AS totalAnuladas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Anulada' ";
$resContador3 = mysqli_query($conexion, $sqlContador3);

while ($rowContador3 = $resContador3->fetch_assoc()) {
    $datos3 = [];
    $datos3['totalAnuladas'] = $rowContador3['totalAnuladas'];
    $totalAnuladas = $datos3['totalAnuladas'];
}

$sqlContador4 = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'SOLICITADA A SECRETARIA SDO' ";
$resContador4 = mysqli_query($conexion, $sqlContador4);

while ($rowContador4 = $resContador4->fetch_assoc()) {
    $datos4 = [];
    $datos4['totalSecretaria'] = $rowContador4['totalSecretaria'];
    $totalSecretaria = $datos4['totalSecretaria'];
}

$sqlTotalRevisados = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Revisada por Secretaría SDO' ";
$resTotalRevisados = mysqli_query($conexion, $sqlTotalRevisados);

while ($rowRevisados = $resTotalRevisados->fetch_assoc()) {
    $datos4 = [];
    $datos4['totalSecretaria'] = $rowRevisados['totalSecretaria'];
    $totalRevisados = $datos4['totalSecretaria'];
}


$sqlContador5 = "SELECT COUNT(*) AS totalSDA FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'ENTREGADO A LA S.D.A' ||  estadoSoliCompra = 'Autorizado por la S.D.A' ";
$resContador5 = mysqli_query($conexion, $sqlContador5);

while ($rowContador5 = $resContador5->fetch_assoc()) {
    $datos5 = [];
    $datos5['totalSDA'] = $rowContador5['totalSDA'];
    $totalSDA = $datos5['totalSDA'];
}


$sqlContador6 = "SELECT COUNT(*) AS totalAbastecimiento FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'DERIVADO A ABASTECIMIENTO' ";
$resContador6 = mysqli_query($conexion, $sqlContador6);

while ($rowContador6 = $resContador6->fetch_assoc()) {
    $datos6 = [];
    $datos6['totalAbastecimiento'] = $rowContador6['totalAbastecimiento'];
    $totalAbastecimiento = $datos6['totalAbastecimiento'];
}


$sqlContador7 = "SELECT COUNT(*) AS totalPanol FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Recibido por pañol' ";
$resContador7 = mysqli_query($conexion, $sqlContador7);

while ($rowContador7 = $resContador7->fetch_assoc()) {
    $datos7 = [];
    $datos7['totalPanol'] = $rowContador7['totalPanol'];
    $totalPanol = $datos7['totalPanol'];
}


$sqlContador8 = "SELECT COUNT(*) AS totalCompletadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Completada' ";
$resContador8 = mysqli_query($conexion, $sqlContador8);

while ($rowContador8 = $resContador8->fetch_assoc()) {
    $datos8 = [];
    $datos8['totalCompletadas'] = $rowContador8['totalCompletadas'];
    $totalCompletadas = $datos8['totalCompletadas'];
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }

        .cantidad1 {
            background-color: blue;
            color: white;
        }

        .cantidad4 {
            background-color: green;
            color: white;
        }

        .cantidad5 {
            background-color: green;
            color: white;
        }

    </style>
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
                <div class="row mb-2 animate__animated animate__fadeInDown">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " . $nomUsuario . " " . $icono ?> </h3>
                    </div>
                    <div class="col-sm-6">
                        <p id="tiempo-actual"></p>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php
        $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
        $obtenerOTSegunNombre = obtenerOTSegunNombre($nomUsuario);
        $contarfaltavisado = contarfaltavisado($nomUsuario);

        if ($obtenerOTSegunNombre > 0) {
            ?>
            <script>
                Swal.fire(
                    'Existen nuevas Ordenes de Trabajo.',
                    '',
                    'info'
                )
            </script>
            <?php
        }
        if ($idDepartamento == 1) {
            if ($contarfaltavisado > 0) {
                ?>
                <script>
                    Swal.fire(
                        'Existen Ordenes de Trabajo sin visar, en total: <?php echo $contarfaltavisado?>.',
                        '',
                        'info'
                    )
                </script>
                <?php
            }
        }
        if ($idDepartamento == 1) {
            if (isset($_GET['loginExito'])) {
                ?>
                <script>
                    Swal.fire({
                        title: 'Ingreso Exitoso a la Plataforma.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    }).then(async (result) => {
                        <?php
                        if ($obtenerOTSegunNombre > 0) {
                        ?>
                        await Swal.fire(
                            'Existen nuevas Órdenes de Trabajo.',
                            '',
                            'info'
                        );
                        <?php
                        }
                        ?>
                        <?php
                        if ($contarfaltavisado > 0) {
                        ?>
                        await Swal.fire(
                            'Existen <?php echo $contarfaltavisado ?> Órdenes de Trabajo sin visar.',
                            '',
                            'info'
                        );
                        <?php
                        }
                        ?>
                    });
                </script>
                <?php
            }
        }


        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <?php

                    $mostrarOcultar = deshabilitarOpciones();

                    if ($mostrarOcultar == 'Mostrar') {
                        ?>
                        <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInLeft">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="far fa-file-alt"></i></span>
                                <a href="ordenes-listado-general.php">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-info" style="font-family: 'Roboto', sans-serif;font-weight:900">Hojas Tecnicas</span>
                                        <span class="info-box-number text-info" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                            <?php
                                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM ordenes_trabajos_general WHERE idDepartamento =" . $idDepartamento;
                                            $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                            while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                $datos = [];
                                                $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                $TOTAL = $datos['TOTAL'];
                                            }

                                            ?>
                                            <?php echo $TOTAL ?>
                                        </span>
                                    </div>
                                </a>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <?php
                    }


                    ?>
                    <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInLeft">
                        <div class="info-box">
                            <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-cart"></i></span>

                            <a href="soliCompra_sdo_administracion.php">
                                <div class="info-box-content">
                                    <span class="info-box-text text-orange" style="font-family: 'Roboto', sans-serif;font-weight:900">Solicitudes de compra</span>
                                    <span class="info-box-number text-orange" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                            <?php
                                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM solicitudes_compra_sdo where unidadSoliCompra = '" . $nomDepartamento . "'";
                                            $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                            while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                $datos = [];
                                                $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                $TOTAL = $datos['TOTAL'];
                                            }

                                            ?>
                                            <?php echo $TOTAL ?>
                                        </span>
                                </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->


                    </div>
                    <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInRight">
                        <div class="info-box">
                            <span class="info-box-icon bg-pink elevation-1"><i class="fas fa-boxes"></i></span>

                            <a href="equipamiento_hbqp.php">
                                <div class="info-box-content">
                                    <span class="info-box-text text-pink" style="font-family: 'Roboto', sans-serif;font-weight:900; ">Equipamiento</span>

                                    <span class="info-box-number text-pink" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                              <?php
                                              $sqlCategorias = "SELECT * FROM categorias_equipos WHERE idDepartamento = $idDepartamento";
                                              $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                              $cantidadEquipos = 0;

                                              while ($row = $resCategorias->fetch_assoc()) {
                                                  $datos = [];
                                                  $datos['idCategoria'] = $row['idCategoria'];
                                                  $idCategoria = $datos['idCategoria'];

                                                  $sqlEquipos = "SELECT COUNT(*) AS total FROM equipamiento_hbqp WHERE idCategoria = $idCategoria";
                                                  $resEquipos = mysqli_query($conexion, $sqlEquipos);

                                                  while ($rowEquipos = $resEquipos->fetch_assoc()) {
                                                      $datosEquipos = [];
                                                      $datosEquipos['total'] = $rowEquipos['total'];
                                                      $total = $datosEquipos['total'];
                                                  }

                                                  $cantidadEquipos = $cantidadEquipos + $total;

                                              }
                                              ?>
                                              <?php echo $cantidadEquipos ?>
                                        </span>
                                </div>
                            </a>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <?php

                    if ($nomDepartamento == 'Instrumentación') {
                        ?>
                        <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInRight">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>

                                <a href="reportes-listado.php">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-success" style="font-family: 'Roboto', sans-serif;font-weight:900">Reportes Turno</span>
                                        <span class="info-box-number text-success" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                                <?php
                                                $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM reportes_generales";
                                                $resCantHojasTec = mysqli_query($conexion, $cantHojasTec);
                                                while ($rowCantHojasTec = $resCantHojasTec->fetch_assoc()) {
                                                    $datos = [];
                                                    $datos['TOTAL'] = $rowCantHojasTec['TOTAL'];
                                                    $TOTAL = $datos['TOTAL'];
                                                }

                                                ?>
                                                <?php echo $TOTAL ?>
                                            </span>
                                    </div>
                                </a>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <?php
                    }

                    ?>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12">

                        <div class="row" style="font-size: 15px;">
                            <div class="col-12">
                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                        <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                                        <ul class="nav nav-pills ml-auto p-2">
                                            <li class="nav-item"><a class="nav-link active " href="#tab_4" data-toggle="tab"> <b>Menu</b> </a></li>
                                            <?php

                                            $mostrarOcultar = deshabilitarOpciones();

                                            if ($mostrarOcultar == 'Mostrar') {
                                                ?>
                                                <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab"> <b>Hojas Técnicas</b> </a></li>
                                                <?php
                                            }
                                            ?>

                                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab"><b>Solicitudes de Compra</b></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab"><b>Ordenes de Trabajo</b></a></li>
                                        </ul>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="tab_4">

                                                <?php

                                                $departamentoJefe = getNomDepartamento($idDepartamento);

                                                ?>

                                            </div>
                                            <div class="tab-pane" id="tab_1">

                                                <?php


                                                $tipoUsuarioVer = 'unico';
                                                include_once '../sdo-includes/hojastecnicas_dashboard.php';

                                                ?>

                                            </div>
                                            <!-- /.tab-pane -->
                                            <?php
                                            if ($nomUsuario == 'Yorgo Casimis Subiabre') {
                                                ?>
                                                <div class="tab-pane" id="tab_2">
                                                    <?php include_once('../sdo-includes/solicitudes_compra_dashboard.php') ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="tab-pane" id="tab_2">
                                                    <h4 class="text-center text-bold text-danger" style="font-size:20px">Información General</h4>
                                                    <br>
                                                    <div class="row ">
                                                        <div class="col-12  col-sm-6 col-md-3">
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-info elevation-1">
                                                                        <i class="fas fa-list-ol"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-info" style="font-size:15px"> <b>Total Solicitudes de Compra</b> </span>
                                                                        <span class="info-box-number text-info" style="font-size:15px"><?php echo $totalSolicitudes ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-success elevation-1">
                                                                        <i class="fas fa-cart-arrow-down"></i>
                                                                    </span>

                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-success"> <b>Solicitudes Aprobadas por la S.D.A</b> </span>
                                                                        <span class="info-box-number text-success" id="Aprobadas"><?php echo $totalAprobadas ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-danger elevation-1">
                                                                        <i class="fas fa-times-circle"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-danger"> <b>Solicitudes Rechazadas por la S.D.A</b> </span>
                                                                        <span class="info-box-number text-danger" id="Rechazadas"><?php echo $totalRechazadas ?? 0 ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-12">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-warning elevation-1">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text text-warning"> <b>Solicitudes Anuladas</b> </span>
                                                                        <span class="info-box-number text-warning" id="Anuladas"><?php echo $totalAnuladas ?? 0 ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 ">
                                                            <div class="col-12 col-sm-6 col-md-4  mx-auto">
                                                                <canvas id="myChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h4 class="text-center text-bold text-info" style="font-size:20px">Cantidad de solicitudes por fase</h4>
                                                    <br>
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-purple elevation-1">
                                                                    <i class="fas fa-user"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-purple" style="font-size:15px"> <b>Solicitadas a la secretaria S.D.O</b> </span>
                                                                    <span class="info-box-number text-purple" style="font-size:15px"><?php echo $totalSecretaria ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-pink elevation-1">
                                                                    <i class="fas fa-user"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-pink" style="font-size:15px"> <b>Revisadas por secretaria S.D.O</b> </span>
                                                                    <span class="info-box-number text-pink" style="font-size:15px"><?php echo $totalRevisados ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-orange elevation-1">
                                                                    <i class="fas fa-wallet"></i>
                                                                </span>

                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-orange"> <b>Recibidas por la S.D.A</b> </span>
                                                                    <span class="info-box-number text-orange"><?php echo $totalSDA ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-lightblue elevation-1">
                                                                    <i class="fas fa-truck-loading"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-lightblue"> <b>Derivados a Abastecimiento</b> </span>
                                                                    <span class="info-box-number text-lightblue"><?php echo $totalAbastecimiento ?? 0 ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-dark elevation-1">
                                                                    <i class="fas fa-warehouse"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-dark"> <b>Recibidos por pañol</b> </span>
                                                                    <span class="info-box-number text-dark"><?php echo $totalPanol ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-green elevation-1">
                                                                    <i class="fas fa-smile-wink"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-green"> <b>Completadas</b> </span>
                                                                    <span class="info-box-number text-green"><?php echo $totalCompletadas ?></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <hr>
                                                    <h4 class="text-center text-bold text-success" style="font-size:20px">$$ Dinero Gastado por departamento $$</h4>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-purple elevation-1">
                                                                    <i class="fas fa-cogs"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-purple"> <b>Instrumentación</b> </span>
                                                                    <span class="info-box-number text-purple"><?php echo $totalInst ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-dark elevation-1">
                                                                    <i class="fas fa-building"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-dark"> <b>Infraestructura</b> </span>
                                                                    <span class="info-box-number text-dark"><?php echo $totalInfraestructura
                                                                        ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-warning elevation-1">
                                                                    <i class="fas fa-industry"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-warning"> <b>Industrial</b> </span>
                                                                    <span class="info-box-number text-warning"><?php echo $totalIndustrial ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-success elevation-1">
                                                                    <i class="far fa-snowflake"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-success"> <b>Climatización</b> </span>
                                                                    <span class="info-box-number text-success"><?php echo $totalClima ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-danger elevation-1">
                                                                    <i class="fas fa-lightbulb"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-danger"> <b>Electrico</b> </span>
                                                                    <span class="info-box-number text-danger"><?php echo $totalElect ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-info elevation-1">
                                                                    <i class="fas fa-dolly-flatbed"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-info"> <b>Logistica</b> </span>
                                                                    <span class="info-box-number text-info"><?php echo $totalLogistica ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-orange elevation-1">
                                                                    <i class="fas fa-car-side"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-orange"> <b>Movilización</b> </span>
                                                                    <span class="info-box-number text-orange"><?php echo $totalMovilizacion ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-pink elevation-1">
                                                                    <i class="fas fa-users"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-pink"> <b>Ingenieria Operacional</b> </span>
                                                                    <span class="info-box-number text-pink"><?php echo $totalOperacional ?></span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_5">
                                                <?php
                                                $tipoUsuarioVer = 'unico';
                                                include_once('../sdo-includes/ordenes_trabajo_dashboard.php')
                                                ?>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- ./card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
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
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function () {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
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

</body>

</html>


