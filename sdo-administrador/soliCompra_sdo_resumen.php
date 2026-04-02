<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

$anioCurso = date("Y");

//CALCULA VALOR DE INSTRUMENTACIÓN
$sqlSC = "SELECT SUM(valor) as totalInst FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'INSTRUMENTACIÓN' AND YEAR(fechaSoliCompra) = $anioCurso";
$res = mysqli_query($conexion, $sqlSC);

while ($rowTotalInst = $res->fetch_assoc()) {
    $datos = [];
    $datos['totalInst'] = $rowTotalInst['totalInst'];
    $totalInst = $datos['totalInst'];
}

//CALCULA VALOR DE ELECTRICO
$sqlElectrico = "SELECT SUM(valor) as totalElect FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Electricidad' AND YEAR(fechaSoliCompra) = $anioCurso ";
$resElectrico = mysqli_query($conexion, $sqlElectrico);

while ($rowElectrico = $resElectrico->fetch_assoc()) {
    $datos = [];
    $datos['totalElect'] = $rowElectrico['totalElect'];
    $totalElect = $datos['totalElect'];
}

//CALCULA VALOR DE INDUSTRIAL
$sqlIndustrial = "SELECT SUM(valor) as totalIndustrial FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Equipos Industriales'  AND YEAR(fechaSoliCompra) = $anioCurso";
$resIndustrial = mysqli_query($conexion, $sqlIndustrial);

while ($rowIndustrial = $resIndustrial->fetch_assoc()) {
    $datos = [];
    $datos['totalIndustrial'] = $rowIndustrial['totalIndustrial'];
    $totalIndustrial = $datos['totalIndustrial'];
}

//CALCULA VALOR DE CLIMA
$sqlClima = "SELECT SUM(valor) as totalClima FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Inst. Térmicas, Climatización y Refrigeración'  AND YEAR(fechaSoliCompra) = $anioCurso";
$resClima = mysqli_query($conexion, $sqlClima);

while ($rowClima = $resClima->fetch_assoc()) {
    $datos = [];
    $datos['totalClima'] = $rowClima['totalClima'];
    $totalClima = $datos['totalClima'];
}

//CALCULA VALOR DE LOGISTICA
$sqlLogistica = "SELECT SUM(valor) as totalLogistica FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Logística'  AND YEAR(fechaSoliCompra) = $anioCurso";
$resLogistica = mysqli_query($conexion, $sqlLogistica);

while ($rowLogistica = $resLogistica->fetch_assoc()) {
    $datos = [];
    $datos['totalLogistica'] = $rowLogistica['totalLogistica'];
    $totalLogistica = $datos['totalLogistica'];
}


//CALCULA VALOR DE INFRAESTRUCTURA
$sqlInfraestructura = "SELECT SUM(valor) as totalInfraestructura FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Infraestructura' AND YEAR(fechaSoliCompra) = $anioCurso ";
$resInfraestructura = mysqli_query($conexion, $sqlInfraestructura);

while ($rowInfraestructura = $resInfraestructura->fetch_assoc()) {
    $datos = [];
    $datos['totalInfraestructura'] = $rowInfraestructura['totalInfraestructura'];
    $totalInfraestructura = $datos['totalInfraestructura'];
}

//CALCULA VALOR DE MOVILIZACIÓN
$sqlMovilizacion = "SELECT SUM(valor) as totalMovilizacion FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Movilización' AND YEAR(fechaSoliCompra) = $anioCurso ";
$resMovilizacion = mysqli_query($conexion, $sqlMovilizacion);

while ($rowMovilizacion = $resMovilizacion->fetch_assoc()) {
    $datos = [];
    $datos['totalMovilizacion'] = $rowMovilizacion['totalMovilizacion'];
    $totalMovilizacion = $datos['totalMovilizacion'];
}

//CALCULA VALOR DE MOVILIZACIÓN
$sqlOperacional = "SELECT SUM(valor) as totalOperacional FROM solicitudes_sc_sdo_resumen WHERE unidadSoliCompra LIKE 'Subdirección de Operaciones' AND YEAR(fechaSoliCompra) = $anioCurso ";
$resOperacional = mysqli_query($conexion, $sqlOperacional);

while ($rowOperacional = $resOperacional->fetch_assoc()) {
    $datos = [];
    $datos['totalOperacional'] = $rowOperacional['totalOperacional'];
    $totalOperacional = $datos['totalOperacional'];
}

$totalGastos = $totalInst + $totalIndustrial + $totalClima + $totalInfraestructura + $totalMovilizacion + $totalOperacional + $totalElect + $totalLogistica;

$totalInst = moneda_chilena($totalInst);
$totalIndustrial = moneda_chilena($totalIndustrial);
$totalClima = moneda_chilena($totalClima);
$totalInfraestructura = moneda_chilena($totalInfraestructura);
$totalMovilizacion = moneda_chilena($totalMovilizacion);

if($totalOperacional == 0){
    $totalOperacional = '$ 0';
}else{
    $totalOperacional = moneda_chilena($totalOperacional);
}

if($totalElect == 0){
    $totalElect = '$ 0';
}else{
    $totalElect = moneda_chilena($totalElect);
}

if($totalLogistica == 0){
    $totalLogistica = '$ 0';
}else{
    $totalLogistica = moneda_chilena($totalLogistica);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra - SDO</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <!-- Site wrapper -->
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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Solicitudes de Compras - SDO - Resumen</h4>

                        </div>
                        <div class="col-sm-6">
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

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu1" name="rowMenu1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <?php

                                    $sqlContador = "SELECT COUNT(*) AS total FROM  solicitudes_sc_sdo_resumen WHERE YEAR(fechaSoliCompra) = $anioCurso";
                                    $resContador = mysqli_query($conexion, $sqlContador);

                                    while ($rowContador = $resContador->fetch_assoc()) {
                                        $datos = [];
                                        $datos['total'] = $rowContador['total'];
                                        $total = $datos['total'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador1 = "SELECT COUNT(*) AS totalAprobadas FROM  solicitudes_compra_sdo WHERE (estadoSoliCompra LIKE 'Autorizado por la S.D.A' OR estadoSoliCompra LIKE  'Derivado a Abastecimiento') AND YEAR(fechaSoliCompra) = $anioCurso ";
                                    $resContador1 = mysqli_query($conexion, $sqlContador1);

                                    while ($rowContador1 = $resContador1->fetch_assoc()) {
                                        $datos1 = [];
                                        $datos1['totalAprobadas'] = $rowContador1['totalAprobadas'];
                                        $totalAprobadas = $datos1['totalAprobadas'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador2 = "SELECT COUNT(*) AS totalRechazadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Rechazado por la S.D.A' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador2 = mysqli_query($conexion, $sqlContador2);

                                    while ($rowContador2 = $resContador2->fetch_assoc()) {
                                        $datos2 = [];
                                        $datos2['totalRechazadas'] = $rowContador2['totalRechazadas'];
                                        $totalRechazadas = $datos2['totalRechazadas'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador3 = "SELECT COUNT(*) AS totalAnuladas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Anulada' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador3 = mysqli_query($conexion, $sqlContador3);

                                    while ($rowContador3 = $resContador3->fetch_assoc()) {
                                        $datos3 = [];
                                        $datos3['totalAnuladas'] = $rowContador3['totalAnuladas'];
                                        $totalAnuladas = $datos3['totalAnuladas'];
                                    }
                                    ?>
                                    <!-- CANTIDADES DIVIDAS POR FASE -->
                                    <?php

                                    $sqlContador4 = "SELECT COUNT(*) AS totalSecretaria FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'SOLICITADA A SECRETARIA SDO' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador4 = mysqli_query($conexion, $sqlContador4);

                                    while ($rowContador4 = $resContador4->fetch_assoc()) {
                                        $datos4 = [];
                                        $datos4['totalSecretaria'] = $rowContador4['totalSecretaria'];
                                        $totalSecretaria = $datos4['totalSecretaria'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador5 = "SELECT COUNT(*) AS totalSDA FROM  solicitudes_compra_sdo WHERE (estadoSoliCompra LIKE 'ENTREGADO A LA S.D.A' OR estadoSoliCompra LIKE 'Autorizado por la S.D.A') AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador5 = mysqli_query($conexion, $sqlContador5);

                                    while ($rowContador5 = $resContador5->fetch_assoc()) {
                                        $datos5 = [];
                                        $datos5['totalSDA'] = $rowContador5['totalSDA'];
                                        $totalSDA = $datos5['totalSDA'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador6 = "SELECT COUNT(*) AS totalAbastecimiento FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'DERIVADO A ABASTECIMIENTO' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador6 = mysqli_query($conexion, $sqlContador6);

                                    while ($rowContador6 = $resContador6->fetch_assoc()) {
                                        $datos6 = [];
                                        $datos6['totalAbastecimiento'] = $rowContador6['totalAbastecimiento'];
                                        $totalAbastecimiento = $datos6['totalAbastecimiento'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador7 = "SELECT COUNT(*) AS totalPanol FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Recibido por pañol' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador7 = mysqli_query($conexion, $sqlContador7);

                                    while ($rowContador7 = $resContador7->fetch_assoc()) {
                                        $datos7 = [];
                                        $datos7['totalPanol'] = $rowContador7['totalPanol'];
                                        $totalPanol = $datos7['totalPanol'];
                                    }
                                    ?>

                                    <?php

                                    $sqlContador8 = "SELECT COUNT(*) AS totalCompletadas FROM  solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Completada' AND YEAR(fechaSoliCompra) = $anioCurso  ";
                                    $resContador8 = mysqli_query($conexion, $sqlContador8);

                                    while ($rowContador8 = $resContador8->fetch_assoc()) {
                                        $datos8 = [];
                                        $datos8['totalCompletadas'] = $rowContador8['totalCompletadas'];
                                        $totalCompletadas = $datos8['totalCompletadas'];
                                    }
                                    ?>


                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header bg-info" id="headingOne" style="padding: 6px 12px;">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-center text-bold text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="fas fa-arrow-alt-circle-down"></i> <i class="fas fa-arrow-alt-circle-down"></i> Dashboard <i class="fas fa-arrow-alt-circle-down"></i> <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <h4 class="text-center text-bold text-danger" style="font-size:20px">Información General</h4>
                                                    <br>
                                                    
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
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-gradient-teal elevation-1">
                                                                <i class="fas fa-money-bill-wave"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text text-teal"> <b>Gasto Total </b> </span>
                                                                    <span class="info-box-number text-teal"><?php echo  moneda_chilena($totalGastos)  ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">

                                                        </div>
                                                        <div class="col-12 col-md-6">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Unidad
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Solicitante
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Numero Interior SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Area de derivación
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Ordenes de compra
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Envio OC Proveedor
                                                </th>

                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado General Solicitud
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            //CONSULTA SC SDO
                                            $sqlSC = "SELECT * FROM solicitudes_sc_sdo_resumen ORDER BY idSoliCompra DESC";
                                            $res = mysqli_query($conexion, $sqlSC);
                                            while ($rowSC = $res->fetch_assoc()) {
                                                $datos = [];
                                                $datos['fechaSDA'] = $rowSC['fechaSDA'];
                                                $fechaSDA = $datos['fechaSDA'];
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $idSoliCompra  = mb_strtoupper($rowSC['idSoliCompra']);
                                                        echo $idSoliCompra;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $descripcion = $rowSC['descripcion'];
                                                        echo substr($descripcion, 0, 30) . '...';
                                                        // echo $argumentosSoliCompra;
                                                        ?>
                                                        <button class="btn  btn-xs btn-success" id='#idHerramienta<?php echo $idSoliCompra ?>' data-toggle='modal' data-target='#idHerramienta<?php echo $idSoliCompra ?>'> <b>Ver mas</b> </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="idHerramienta<?php echo $idSoliCompra ?>" name="idHerramienta<?php echo $idSoliCompra ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="EjemploModalLabel">Descripcion Solicitud</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <?php
                                                                        $sqlDescript = "SELECT descripcion FROM solicitudes_sc_sdo_resumen WHERE idSoliCompra = ${idSoliCompra}";
                                                                        $resDescript = mysqli_query($conexion, $sqlDescript);
                                                                        while ($rowDescript = $resDescript->fetch_assoc()) {
                                                                            $descript = $rowDescript['descripcion'];
                                                                        }
                                                                        ?>
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <textarea type="text" readonly class="form-control" rows="10"><?php echo $descript ?></textarea>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $descripcion = $rowSC['descripcion'];
                                                        echo nl2br($descripcion);
                                                        // echo $argumentosSoliCompra;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowSC['fechaSoliCompra']))));
                                                        echo $inicioFecha;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $unidadSoliCompra = mb_strtoupper($rowSC['unidadSoliCompra']);
                                                        echo $unidadSoliCompra;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $solicitante = mb_strtoupper($rowSC['solicitante']);
                                                        $solicitante = explode('(', $solicitante);
                                                        echo $solicitante[0];
                                                        // $solicitante = explode(' (', $solicitante);
                                                        // $unidadSoliCompra = mb_strtoupper($rowSC['unidadSoliCompra']);
                                                        // if (empty($solicitante[1])) {
                                                        //     echo $solicitante[0] . '<br>(' . $unidadSoliCompra . ')';
                                                        // } else {
                                                        //     echo $solicitante[0] . '<br>(' . $solicitante[1] . '<br>(' . $unidadSoliCompra . ')';
                                                        // }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        if ($rowSC['numInterior'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['numInterior']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['estadoSDA'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['estadoSDA']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($fechaSDA == '') {
                                                            echo 'SIN INFORMACIÓN';
                                                        } else {

                                                            $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowSC['fechaSDA']))));

                                                            echo $inicioFecha;
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['areaDerivacion'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['areaDerivacion']);
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['ordenCompra'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['ordenCompra']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['fechaEnvioOC'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['fechaEnvioOC']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        if ($rowSC['valor'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['valor']);
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['estadoSolicitud'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['estadoSolicitud']);
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Unidad
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Solicitante
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Numero Interior SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Area de derivación
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Ordenes de compra
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Envio OC Proveedor
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado General Solicitud
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
            $('#example2').DataTable({
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

                'columnDefs': [
                    //hide the second & fourth column
                    {
                        'visible': false,
                        'targets': [2]
                    }
                ],
                "paging": true,
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
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
                },
                initComplete: function() {
                    this.api().columns([4]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Unidad...</option></select>')
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
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Solicitante...</option></select>')
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
                }
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        $(function() {
            $('#tabla_anuladas').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
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
                },
            })
        });
    </script>
    <script type="text/javascript">
        function verMas(id) {

            vermas = document.getElementById('vermas');

            vermas.addEventListener('click', function(e) {
                e.preventDefault();
            })

            event.preventDefault();
            console.log('click en ver mas: ', id);
        }
    </script>
    <script src="js/dark-mode.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        const Anuladas = document.getElementById('Anuladas').textContent;
        const Rechazadas = document.getElementById('Rechazadas').textContent;
        const Aprobadas = document.getElementById('Aprobadas').textContent;

        cantAnuladas = parseInt(Anuladas);
        cantRechazadas = parseInt(Rechazadas);
        cantAprobadas = parseInt(Aprobadas);

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Solicitudes', 'Estados'],
                ['Aprobadas',cantAprobadas],
                ['Rechazadas',cantRechazadas],
                ['Anuladas',cantAnuladas],
            ]);

            var options = {
                title: '% Solicitudes',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

</body>

</html>