<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');

if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '12' &&  $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
    

}

// echo 'hola';

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




$sqlContador = "SELECT COUNT(*) AS totalSolicitudes FROM  solicitudes_sc_sdo_resumen";
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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 animate__animated animate__fadeInDown">
                        <div class="col-sm-6">
                            <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " .  $nomUsuario . " " . $icono  ?> </h3>
                        </div>
                        <div class="col-sm-6">
                            <p id="tiempo-actual"></p>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInLeft">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="far fa-file-alt"></i></span>

                                <a href="ordenes-administrar-general.php">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-info" style="font-family: 'Roboto', sans-serif;font-weight:900">Hojas Tecnicas</span>
                                        <span class="info-box-number text-info" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                            <?php
                                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM ordenes_trabajos_general";
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
                        <div class="col-12 col-sm-6 col-md-3 animate__animated animate__fadeInLeft">
                            <div class="info-box">
                                <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-cart"></i></span>

                                <a href="soliCompra_sdo_administracion.php">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-orange" style="font-family: 'Roboto', sans-serif;font-weight:900">Solicitudes de compra</span>
                                        <span class="info-box-number text-orange" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                            <?php
                                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM solicitudes_compra_sdo";
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
                                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-boxes"></i></span>

                                <a href="administrar-equipos.php">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-purple" style="font-family: 'Roboto', sans-serif;font-weight:900">Equipamiento</span>
                                        <span class="info-box-number text-purple" style="font-family: 'Roboto', sans-serif;font-weight:900">
                                            <?php
                                            $cantHojasTec = "SELECT COUNT(*) as TOTAL FROM hoja_de_vida";
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
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>

                                <a href="reportes-administrar.php">
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
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-12">

                            <div class="row">
                                <div class="col-12">
                                    <!-- Custom Tabs -->
                                    <div class="card">
                                        <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                            <h3 class="card-title p-3"> <b>Accesos Rápidos</b> </h3>
                                            <ul class="nav nav-pills ml-auto p-2">
                                                <li class="nav-item"><a class="nav-link active" href="#tab_4" data-toggle="tab"> <b>Menu</b> </a></li>
                                                <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab"> <b>Hojas Técnicas</b> </a></li>
                                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab"><b>Solicitudes de Compra</b></a></li>
                                                <!-- <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab"><b>Equipos</b></a></li> -->
                                            </ul>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_4">

                                                    <h5><b>Equipamiento Control Centralizado </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="administrar-equipos-cc.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="fas fa-clipboard-list"></i><br>Listado de Equipos</a>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <h5><b>Equipamiento HBQP </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="administrar-equipos.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="fas fa-clipboard-list"></i><br>Listado de Equipos</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Hojas Técnicas </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="ordenes-administrar-general.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Listar</a>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <h5><b>Rutina Diaria </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="bitacora-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Visualizar Bitacora</a>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <h5><b>Reportes Generales de Eventos Relevantes </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="reportes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Crear</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="reportes-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Historial</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Recepciones de Trabajos </b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="ordenes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-file"></i><br>Generar</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="ordenes-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Historial</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Observaciones de Sistema BMS</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="sistemas-listado.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Listado de sistemas</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="forzados-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Listado de Equipos Forzados</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="sistemas-observaciones.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-book"></i><br>Observaciones de Equipos</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Levantamiento de Sistema BMS</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="controladores-maestros-listado.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-list"></i><br>Sistemas Maestros</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="controladores-esclavos-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Controladores</a>
                                                        </div>
                                                        &nbsp;
<!--                                                        <div class="col-lg-3 col-12">-->
<!--                                                            <a href="detalle_controladores_bms.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-book"></i><br>Detalle Controladores</a>-->
<!--                                                        </div>-->
                                                    </div>
                                                    <hr>
                                                    <h5><b>Administraci&oacute;n de Jefes y Tecnicos</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="jefes-administrar.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-users"></i><br>Encargados</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="personal-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Técnicos</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Solicitudes de Soporte</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Tickets Recibidos</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Tickets Finalizados</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-book"></i><br>Tickets Cancelados</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Control de Acceso</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="control-acceso.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-users"></i><br>Listado</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="actas-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Administrar</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="parking-acceso.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-parking"></i><br>Parking</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-2 col-12">
                                                            <!-- small box -->
                                                            <a href="llaves_entregadas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-key"></i><br>Llaves Entregadas</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Solicitudes de Compra</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>Administrar - Puesta en marcha</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Administrar - SDO</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="soliCompra_sdo_resumen.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-list"></i><br>Visualizar Resumen</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Bodega Pañol</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="panel-arriendo-retiro.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-store"></i><br>Entrada y Salida de productos</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="bodega-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-tools"></i><br>Ingresar Herramienta</a>
                                                        </div>
                                                        &nbsp;
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="materiales-administrar.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-box"></i><br>Ingresar Materiales</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Viajes Transporte - Movilización</b></h5>

                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="listado-viajes.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-car"></i><br>Ingresar Viaje</a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5><b>Combustible</b></h5>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <!-- small box -->
                                                            <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-car"></i><br>Registrar Medición</a>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                </div>
                                                <div class="tab-pane" id="tab_1">
                                                    <div class="col-12">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-9">

                                                                <div class="row">
                                                                    <?php
                                                                    $titulo1 = "Cantidad de Hojas Técnicas Realizadas por Departamento";

                                                                    $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                                    ?>
                                                                    <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover">
                                                                            <thead class="bg-success">
                                                                                <tr>
                                                                                    <th scope="col">Departamento</th>
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
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th scope="row">Apoyo Clinico</th>

                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $depaApoyoClic = 10;
                                                                                        $mes = 'Marzo';

                                                                                        $cantidadMarzoAC = contarHojasTecnicas($depaApoyoClic, $mes);
                                                                                        echo $cantidadMarzoAC;
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Abril';

                                                                                        $cantidadAbrilAC = contarHojasTecnicas($depaApoyoClic, $mes);
                                                                                        echo $cantidadAbrilAC;
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Mayo';

                                                                                        $cantidadMayoAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadMayoAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMayoAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Junio';

                                                                                        $cantidadJunioAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadJunioAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJunioAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Julio';

                                                                                        $cantidadJulioAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadJulioAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJulioAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Agosto';

                                                                                        $cantidadAgostoAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadAgostoAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAgostoAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Septiembre';

                                                                                        $cantidadSeptiembreAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadSeptiembreAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadSeptiembreAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Octubre';

                                                                                        $cantidadOctubreAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadOctubreAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadOctubreAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Noviembre';

                                                                                        $cantidadNoviembreAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadNoviembreAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadNoviembreAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Diciembre';

                                                                                        $cantidadDiciembreAC = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadDiciembreAC == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadDiciembreAC;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Climatización</th>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $depaApoyoClic = 3;
                                                                                        $mes = 'Marzo';

                                                                                        $cantidadMarzioClic = contarHojasTecnicas($depaApoyoClic, $mes);
                                                                                        echo $cantidadMarzioClic;
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Abril';

                                                                                        $cantidadAbrilClic = contarHojasTecnicas($depaApoyoClic, $mes);
                                                                                        echo $cantidadAbrilClic;
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Mayo';

                                                                                        $cantidadMayoClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadMayoClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMayoClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Junio';

                                                                                        $cantidadJunioClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadJunioClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJunioClic;
                                                                                        }


                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Julio';

                                                                                        $cantidadJulioClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadJulioClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJulioClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Agosto';

                                                                                        $cantidadAgostoClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadAgostoClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAgostoClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Septiembre';

                                                                                        $cantidadSeptiembreClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadSeptiembreClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadSeptiembreClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">


                                                                                        <?php

                                                                                        $mes = 'Octubre';

                                                                                        $cantidadOctubreClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadOctubreClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadOctubreClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">


                                                                                        <?php

                                                                                        $mes = 'Noviembre';

                                                                                        $cantidadNoviembreClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadNoviembreClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadNoviembreClic;
                                                                                        }


                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">


                                                                                        <?php

                                                                                        $mes = 'Diciembre';

                                                                                        $cantidadDiciembreClic = contarHojasTecnicas($depaApoyoClic, $mes);

                                                                                        if ($cantidadDiciembreClic == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadDiciembreClic;
                                                                                        }


                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Electricidad</th>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $depaElec = 4;
                                                                                        $mes = 'Marzo';

                                                                                        $cantidadMarzoElect = contarHojasTecnicas($depaElec, $mes);
                                                                                        echo $cantidadMarzoElect;
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Abril';

                                                                                        $cantidadAbrilElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadAbrilElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAbrilElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Mayo';

                                                                                        $cantidadMayoElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadMayoElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMayoElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Junio';

                                                                                        $cantidadJunioElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadJunioElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJunioElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Julio';

                                                                                        $cantidadJulioElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadJulioElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJulioElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Agosto';

                                                                                        $cantidadAgostoElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadAgostoElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAgostoElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Septiembre';

                                                                                        $cantidadSeptiembreElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadSeptiembreElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadSeptiembreElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Octubre';

                                                                                        $cantidadOctubreElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadOctubreElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadOctubreElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Noviembre';

                                                                                        $cantidadNoviembreElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadNoviembreElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadNoviembreElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Diciembre';

                                                                                        $cantidadDiciembreElect = contarHojasTecnicas($depaElec, $mes);

                                                                                        if ($cantidadDiciembreElect == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadDiciembreElect;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Industriales</th>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Marzo';

                                                                                        $cantidadMarzoIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadMarzoIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMarzoIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Abril';

                                                                                        $cantidadAbrilIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadAbrilIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAbrilIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Mayo';

                                                                                        $cantidadMayoIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadMayoIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMayoIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Junio';

                                                                                        $cantidadJunioIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadJunioIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJunioIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Julio';

                                                                                        $cantidadJulioIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadJulioIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJulioIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Agosto';

                                                                                        $cantidadAgostoIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadAgostoIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAgostoIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Septiembre';

                                                                                        $cantidadSeptiembreIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadSeptiembreIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadSeptiembreIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Octubre';

                                                                                        $cantidadOctubreIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadOctubreIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadOctubreIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Noviembre';

                                                                                        $cantidadNoviembreIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadNoviembreIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadNoviembreIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $depaIndus = 2;
                                                                                        $mes = 'Diciembre';

                                                                                        $cantidadDiciembreIndus = contarHojasTecnicas($depaIndus, $mes);

                                                                                        if ($cantidadDiciembreIndus == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadDiciembreIndus;
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th scope="row">Instrumentación</th>
                                                                                    <td class="text-center">


                                                                                        <?php

                                                                                        $depaInst = 1;
                                                                                        $mes = 'Marzo';

                                                                                        $cantidadMarzoInst = contarHojasTecnicas($depaInst, $mes);
                                                                                        echo $cantidadMarzoInst;
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Abril';

                                                                                        $cantidadAbrilInst = contarHojasTecnicas($depaInst, $mes);
                                                                                        echo $cantidadAbrilInst;
                                                                                        ?>

                                                                                    </td>
                                                                                    <td class="text-center">

                                                                                        <?php

                                                                                        $mes = 'Mayo';

                                                                                        $cantidadMayoInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadMayoInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadMayoInst;
                                                                                        }
                                                                                        ?>


                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Junio';

                                                                                        $cantidadJunioInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadJunioInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJunioInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Julio';

                                                                                        $cantidadJulioInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadJulioInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadJulioInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Agosto';

                                                                                        $cantidadAgostoInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadAgostoInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadAgostoInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Septiembre';

                                                                                        $cantidadSeptiembreInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadSeptiembreInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadSeptiembreInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Octubre';

                                                                                        $cantidadOctubreInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadOctubreInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadOctubreInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Noviembre';

                                                                                        $cantidadNoviembreInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadNoviembreInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadNoviembreInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        $mes = 'Diciembre';

                                                                                        $cantidadDiciembreInst = contarHojasTecnicas($depaInst, $mes);

                                                                                        if ($cantidadDiciembreInst == 0) {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            echo $cantidadDiciembreInst;
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr class="bg-info">
                                                                                    <th scope="row">Totales</th>
                                                                                    <td class="text-center text-bold"> <?php echo $cantidadMarzoInst + $cantidadMarzoIndus + $cantidadMarzoElect + $cantidadMarzoAC + $cantidadMarzioClic; ?></td>
                                                                                    <td class="text-center text-bold"><?php echo $cantidadAbrilInst + $cantidadAbrilIndus + $cantidadAbrilElect + $cantidadAbrilAC + $cantidadAbrilClic; ?></td>
                                                                                    <td class="text-center text-bold"><?php echo $cantidadMayoInst + $cantidadMayoIndus + $cantidadMayoElect + $cantidadMayoAC + $cantidadMayoClic; ?></td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                    <td class="text-center text-bold">0</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-3 col-12">

                                                                <?php

                                                                //TOTALES
                                                                $totalMarzo = $cantidadMarzoInst + $cantidadMarzoIndus + $cantidadMarzoElect + $cantidadMarzoAC + $cantidadMarzioClic;
                                                                $totalAbril = $cantidadAbrilInst + $cantidadAbrilIndus + $cantidadAbrilElect + $cantidadAbrilAC + $cantidadAbrilClic;
                                                                $totalMayo = $cantidadMayoInst + $cantidadMayoIndus + $cantidadMayoElect + $cantidadMayoAC + $cantidadMayoClic;

                                                                $totalAhno = $totalMarzo + $totalAbril + $totalMayo;

                                                                ?>

                                                                <div class="card bg-danger" style="width: 18rem;">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title"> <b>Total Año 2023</b> </h5>
                                                                    </div>
                                                                    <div class="card-body text-center">
                                                                        <p class="card-text" style="font-size: 25px;"><i class="far fa-file-alt fa-lg"></i> <?php echo $totalAhno ?> </p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"> <b>Marzo</b> </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"> <b>Abril</b> </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"> <b>Mayo</b> </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-success collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-gamepad"></i>&nbsp;&nbsp;<b>Control Centralizado</b></h3>

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


                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

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
                                                                                        <tr>
                                                                                            <td class="text-center">Control Centralizado</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Apoyo Clinico</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Electricidad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Climatización</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Industriales</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Incendio y Seguridad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Totales</td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', 'Marzo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', 'Marzo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', 'Marzo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', 'Marzo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('OM', 'Marzo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('IS', 'Marzo'); ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>

                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-info collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-clinic-medical"></i>&nbsp;&nbsp;<b>Apoyo Clinico</b></h3>

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

                                                                                        $sqlJefesAC = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total   FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 10 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC;";
                                                                                        $resSqlJefeAC = mysqli_query($conexion, $sqlJefesAC);
                                                                                        $totalACMarzo = 0;

                                                                                        while ($rowSqlJefeAC = $resSqlJefeAC->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['idJefe'] = $rowSqlJefeAC['idJefe'];
                                                                                            $datos['nomJefe'] = $rowSqlJefeAC['nomJefe'];
                                                                                            $datos['total'] = $rowSqlJefeAC['total'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalACMarzo = $totalACMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalACMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-danger collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-plug"></i>&nbsp;&nbsp;<b>Electricidad</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-orange collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-cloud-sun-rain"></i>&nbsp;&nbsp;<b>Climatización</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-warning collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-robot"></i>&nbsp;&nbsp;<b>Industriales</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-01-01 00:00:00' AND '2023-03-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div><!-- fin pestaña -->

                                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-success collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-gamepad"></i>&nbsp;&nbsp;<b>Control Centralizado</b></h3>

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


                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

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
                                                                                        <tr>
                                                                                            <td class="text-center">Control Centralizado</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'OM', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'IS', 'Abril'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Apoyo Clinico</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'OM', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'IS', 'Abril'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Electricidad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'OM', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'IS', 'Abril'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Climatización</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'OM', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'IS', 'Abril'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Industriales</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRI', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRE', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'OM', 'Abril'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'IS', 'Abril'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Incendio y Seguridad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Totales</td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', 'Abril'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', 'Abril'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', 'Abril'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', 'Abril'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('OM', 'Abril'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('IS', 'Abril'); ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>

                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-info collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-clinic-medical"></i>&nbsp;&nbsp;<b>Apoyo Clinico</b></h3>

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

                                                                                        $sqlJefesAC = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total   FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 10 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idJefe ORDER BY total DESC;";
                                                                                        $resSqlJefeAC = mysqli_query($conexion, $sqlJefesAC);
                                                                                        $totalACMarzo = 0;

                                                                                        while ($rowSqlJefeAC = $resSqlJefeAC->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['idJefe'] = $rowSqlJefeAC['idJefe'];
                                                                                            $datos['nomJefe'] = $rowSqlJefeAC['nomJefe'];
                                                                                            $datos['total'] = $rowSqlJefeAC['total'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalACMarzo = $totalACMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalACMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-danger collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-plug"></i>&nbsp;&nbsp;<b>Electricidad</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-orange collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-cloud-sun-rain"></i>&nbsp;&nbsp;<b>Climatización</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-warning collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-robot"></i>&nbsp;&nbsp;<b>Industriales</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-04-01 00:00:00' AND '2023-04-31 23:59:59'  GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-success collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-gamepad"></i>&nbsp;&nbsp;<b>Control Centralizado</b></h3>

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


                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 1 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>


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
                                                                                        <tr>
                                                                                            <td class="text-center">Control Centralizado</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MPE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'MRE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'OM', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(1, 'IS', 'Mayo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Apoyo Clinico</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MPE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'MRE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'OM', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(10, 'IS', 'Mayo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Electricidad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MPE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'MRE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'OM', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(4, 'IS', 'Mayo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Climatización</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MPE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'MRE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'OM', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(3, 'IS', 'Mayo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Industriales</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRI', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MPE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'MRE', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'OM', 'Mayo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(2, 'IS', 'Mayo'); ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="text-center">Incendio y Seguridad</td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRI', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MPE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'MRE', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'OM', 'Marzo'); ?></td>
                                                                                            <td class="text-center"><?php echo obtieneHTTipo(12, 'IS', 'Marzo'); ?></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Totales</td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', 'Mayo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', 'Mayo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', 'Mayo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', 'Mayo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('OM', 'Mayo'); ?> </td>
                                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('IS', 'Mayo'); ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>

                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-info collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-clinic-medical"></i>&nbsp;&nbsp;<b>Apoyo Clinico</b></h3>

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

                                                                                        $sqlJefesAC = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total   FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 10 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC;";
                                                                                        $resSqlJefeAC = mysqli_query($conexion, $sqlJefesAC);
                                                                                        $totalACMarzo = 0;

                                                                                        while ($rowSqlJefeAC = $resSqlJefeAC->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['idJefe'] = $rowSqlJefeAC['idJefe'];
                                                                                            $datos['nomJefe'] = $rowSqlJefeAC['nomJefe'];
                                                                                            $datos['total'] = $rowSqlJefeAC['total'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalACMarzo = $totalACMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalACMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-danger collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-plug"></i>&nbsp;&nbsp;<b>Electricidad</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 4 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-orange collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-cloud-sun-rain"></i>&nbsp;&nbsp;<b>Climatización</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 3 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }


                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 h-100">
                                                                        <div class="card card-warning collapsed-card">
                                                                            <div class="card-header">
                                                                                <h3 class="card-title"><i class="fas fa-robot"></i>&nbsp;&nbsp;<b>Industriales</b></h3>

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

                                                                                        $sql = "SELECT p.idPersonal, p.nomPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idPersonal ORDER BY total DESC;";
                                                                                        $res = mysqli_query($conexion, $sql);
                                                                                        $totalInstruMarzo = 0;

                                                                                        while ($row = $res->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                            $datos['idPersonal'] = $row['idPersonal'];
                                                                                            $datos['total'] = $row['total'];
                                                                                            $nomPersonal = $datos['nomPersonal'];
                                                                                            $idPersonal = $datos['idPersonal'];
                                                                                            $total = $datos['total'];

                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomPersonal ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        $sqlJefeInstruMarzo = "SELECT p.idJefe, p.nomJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = 2 AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '2023-05-01 00:00:00' AND '2023-05-31 23:59:59' GROUP BY p.idJefe ORDER BY total DESC";
                                                                                        $resJefeInstruMarzo = mysqli_query($conexion, $sqlJefeInstruMarzo);

                                                                                        while ($rowJefeInstruMarzo = $resJefeInstruMarzo->fetch_assoc()) {
                                                                                            $datos = [];
                                                                                            $datos['nomJefe'] = $rowJefeInstruMarzo['nomJefe'];
                                                                                            $datos['idJefe'] = $rowJefeInstruMarzo['idJefe'];
                                                                                            $datos['total'] = $rowJefeInstruMarzo['total'];
                                                                                            $nomJefe = $datos['nomJefe'];
                                                                                            $idJefe = $datos['idJefe'];
                                                                                            $total = $datos['total'];
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td class="text-center"><?php echo $nomJefe ?></td>
                                                                                                <td class="text-center"><?php echo $total ?></td>
                                                                                            </tr>

                                                                                        <?php

                                                                                            $totalInstruMarzo = $totalInstruMarzo + $total;
                                                                                        }

                                                                                        ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td class="text-center">Total</td>
                                                                                            <td class="text-center"><?php echo $totalInstruMarzo ?> </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>

                                                </div>
                                                <!-- /.tab-pane -->
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
                                                                    <span class="info-box-number text-danger"><?php echo $totalElect
                                                                                                                ?></span>
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
                                                <!-- /.tab-pane -->
                                                <div class="tab-pane" id="tab_3">
                                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has
                                                    survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                                                    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
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


        toggle.addEventListener('click', function() {
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