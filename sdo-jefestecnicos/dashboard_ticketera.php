<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$sql = "SELECT COUNT(*) AS TOTAL FROM solicitudes_soportes WHERE situacionSolicitud = 'Solicitud Creada'";
$res = mysqli_query($conexion,$sql);
while($row = $res->fetch_assoc()){
    $total = $row['TOTAL'];
    if($total == 0){
        $total = 0;
    }
}


$sqlAsignado = "SELECT COUNT(*) AS TOTALASIGNADO FROM solicitudes_soportes WHERE situacionSolicitud = 'Solicitud con Personal Asignado'";
$resAsignado = mysqli_query($conexion,$sqlAsignado);
while($rowAsignado = $resAsignado->fetch_assoc()){
    $totalAsignado = $rowAsignado['TOTALASIGNADO'];
    if($totalAsignado == 0){
        $totalAsignado = 0;
    }
}

$sqlSolicitud = "SELECT COUNT(*) AS TOTALSolicitud FROM solicitudes_soportes WHERE situacionSolicitud = 'Se encuentra con Solicitud de Compra'";
$resSolicitud  = mysqli_query($conexion,$sqlSolicitud );
while($rowSolicitud  = $resSolicitud ->fetch_assoc()){
    $totalSolicitud  = $rowSolicitud ['TOTALSolicitud'];
    if($totalSolicitud == 0){
        $totalSolicitud = 0;
    }
}

$sqlObservaciones = "SELECT COUNT(*) AS TOTALObservaciones FROM solicitudes_soportes WHERE situacionSolicitud = 'Solicitud con Observaciones Nuevas'";
$resObservaciones  = mysqli_query($conexion,$sqlObservaciones);
while($rowObservaciones  = $resObservaciones ->fetch_assoc()){
    $totalObservaciones  = $rowObservaciones ['TOTALObservaciones'];
    if($totalObservaciones == 0){
        $totalObservaciones = 0;
    }
}
$sqlPROGRAMACION = "SELECT COUNT(*) AS TOTALPROGRAMACION FROM solicitudes_soportes WHERE situacionSolicitud = 'Ticket con Programación de Trabajos'";
$resPROGRAMACION  = mysqli_query($conexion,$sqlPROGRAMACION);
while($rowPROGRAMACION  = $resPROGRAMACION ->fetch_assoc()){
    $totalPROGRAMACION  = $rowPROGRAMACION ['TOTALPROGRAMACION'];
    if($totalPROGRAMACION == 0){
        $totalPROGRAMACION = 0;
    }
}
$sqlCANCELADO = "SELECT COUNT(*) AS TOTALCANCELADO FROM solicitudes_soportes WHERE canceladaSolicitud = 1";
$resCANCELADO  = mysqli_query($conexion,$sqlCANCELADO);
while($rowCANCELADO  = $resCANCELADO ->fetch_assoc()){
    $totalCANCELADO  = $rowCANCELADO ['TOTALCANCELADO'];
    if($totalCANCELADO == 0){
        $totalCANCELADO = 0;
    }
}

$sqlFINALIZADO = "SELECT COUNT(*) AS TOTALFINALIZADO FROM solicitudes_soportes WHERE situacionSolicitud = 'Solicitud Finalizada'";
$resFINALIZADO  = mysqli_query($conexion,$sqlFINALIZADO);
while($rowFINALIZADO  = $resFINALIZADO ->fetch_assoc()){
    $totalFINALIZADO  = $rowFINALIZADO ['TOTALFINALIZADO'];
    if($totalFINALIZADO == 0){
        $totalFINALIZADO = 0;
    }
}

$sqlProyecto = "SELECT COUNT(*) AS TOTALProyecto FROM solicitudes_soportes WHERE situacionSolicitud = 'Ticket de Proyecto'";
$resProyecto  = mysqli_query($conexion,$sqlProyecto);
while($rowProyecto  = $resProyecto ->fetch_assoc()){
    $totalProyecto  = $rowProyecto ['TOTALProyecto'];
    if($totalProyecto == 0){
        $totalProyecto = 0;
    }
}


/*Solicitud Finalizada*/
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Ticketera</title>

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
    <script src="js/dark-mode.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>
<body class="hold-transition sidebar-mini ">
    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Dashboard Ticketera</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header" style="background-color: indigo; color: white">
                                <center>
                                    <h5>Cantidad de Ticket Segun Estado</h5>
                                </center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                        <?php

                                        date_default_timezone_set('America/Santiago');
                                        $nowAnio = date('Y', time());
                                        $nowMes = date('m', time());


                                        $mesDespues = date("m", strtotime($now . "+ 1 month"));

                                        $nombreMes = '';

                                        ?>

                                        <div class="col-lg-3 col-auto">
                                            <div class="small-box" style="background-color: #28A745; color: whitesmoke">
                                                <div class="inner">
                                                    <h4>Solicitudes Ingresadas</h4>

                                                    <p style="font-size: 20px">Cantidad: <?php echo $total?></p>
                                                    <div class="icon">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-auto">
                                            <div class="small-box" style="background-color: #FFC107; color: black">
                                                <div class="inner">
                                                    <h4>Solicitud con Personal Asignado</h4>

                                                    <p style="font-size: 20px">Cantidad: <?php echo $totalAsignado?></p>
                                                    <div class="icon">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box" style="background-color: #563D7C; color: white">
                                            <div class="inner">
                                                <h4>Solicitudes de Compra por Ticket</h4>

                                                <p style="font-size: 20px">Cantidad: <?php echo $totalSolicitud?></p>
                                                <div class="icon">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box" style="background-color: #A04000; color: white">
                                            <div class="inner">
                                                <h4>Solicitudes con Observaciones</h4>

                                                <p style="font-size: 20px">Cantidad: <?php echo $totalObservaciones?></p>
                                                <div class="icon">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box" style="background-color: #868678; color: white">
                                            <div class="inner">
                                                <h4>Ticket con Programaci&oacute;n de Trabajo</h4>

                                                <p style="font-size: 20px">Cantidad: <?php echo $totalPROGRAMACION?></p>
                                                <div class="icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box" style="background-color: #FF6600; color: white">
                                            <div class="inner">
                                                <h4>Solicitudes con Proyecto</h4>

                                                <p style="font-size: 20px">Cantidad: <?php echo $totalProyecto?></p>
                                                <div class="icon">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box bg-danger">
                                                <div class="inner">
                                                    <a href="solicitudes_canceladas.php" style="color: white">
                                                        <h4>Solicitudes Canceladas</h4>

                                                        <p style="font-size: 20px">Cantidad: <?php echo $totalCANCELADO?></p>
                                                        <div class="icon">
                                                            <i class="fas fa-times"></i>
                                                        </div>
                                                    </a>

                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-auto">
                                        <div class="small-box" style="background-color: #00AAE4; color: white">
                                            <div class="inner">
                                                <a style="color: white" href="solicitudes_finalizadas.php">
                                                    <h4>Solicitudes Finalizadas</h4>

                                                    <p style="font-size: 20px">Cantidad: <?php echo $totalFINALIZADO?></p>
                                                    <div class="icon">
                                                        <i class="fas fa-file"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="row">
                       <div class="col-12 col-md-12">
                           <div class="card">
                               <div class="card-header" style="background-color: indianred;color: whitesmoke">
                                   <?php
                                   $nowAnio = date('Y');
                                   ?>
                                   <center>
                                       <h5>Cantidad de Tickets Ingresados en el año <?php echo $nowAnio?> por Departamento </h5>

                                   </center>
                               </div>
                               <div class="card-body">
                                   <table style="font-size:80%"  class="table table-hover">
                                       <thead>
                                       <tr>
                                           <th style="font-size: 15px" scope="col" class="text-center">Departamentos</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Enero</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Febrero</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Marzo</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Abril</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Mayo</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Junio</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Julio</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Agosto</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Septiembre</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Octubre</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Noviembre</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Diciembre</th>
                                           <th style="font-size: 15px" scope="col" class="text-center">Total Año <?php echo $nowAnio?></th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <?php
                                       $sqlSelectDepa = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";
                                       $resSelectDepa = mysqli_query($conexion,$sqlSelectDepa);


                                       $cantPorDepto = 0;

                                       $cantPorMesTotal = 0;

                                       $arrayCantTotal = array();

                                       $totalMes1ticket = 0;
                                       $totalMes2ticket = 0;
                                       $totalMes3ticket = 0;
                                       $totalMes4ticket = 0;
                                       $totalMes5ticket = 0;
                                       $totalMes6ticket = 0;
                                       $totalMes7ticket = 0;
                                       $totalMes8ticket = 0;
                                       $totalMes9ticket = 0;
                                       $totalMes10ticket = 0;
                                       $totalMes11ticket = 0;
                                       $totalMes12ticket = 0;

                                       while($rowSelectDepa = $resSelectDepa->fetch_assoc()){
                                           $cantTotalFinal = 0;

                                           $idDepartamento1 = $rowSelectDepa['idDepartamento'];
                                           $nomDepartamentos1 = $rowSelectDepa['nomDepartamento'];

                                           $contarHojasTecnicasAnio = contarTicketsAnio($nomDepartamentos1, $nowAnio);

                                           if($nomDepartamentos1 == 'Informática'){
                                           }else{
                                               $cantTotalDepto = 0;
                                               ?>
                                               <tr>
                                                   <th class="text-center" style="font-size: 15px">
                                                       <?php echo $nomDepartamentos1?>
                                                   </th>
                                                   <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        ?>
                                                   <td class="text-center" style="font-size: 15px">
                                                       <?php
                                                       $mesHT = '';
                                                       if ($i < 13) {
                                                           $mesHT = '0' . $i;
                                                       }
                                                       $fechaT = $nowAnio . '-' . $mesHT;
                                                       $contarTicketsMesAnio = contarTicketsMesAnio($nomDepartamentos1, $fechaT);
                                                       $cantTotalDepto = $cantTotalDepto + $contarTicketsMesAnio;
                                                       if ($contarTicketsMesAnio < 1) {
                                                           echo '-';
                                                       } else {
                                                           echo $contarTicketsMesAnio;
                                                       }
                                                       ?>
                                                   </td>
                                                        <?php

                                                        if ($i == 1) {
                                                            $totalMes1 = $totalMes1 + $contarTicketsMesAnio;
                                                        } else if ($i == 2) {
                                                            $totalMes2 = $totalMes2 + $contarTicketsMesAnio;
                                                        } else if ($i == 3) {
                                                            $totalMes3 = $totalMes3 + $contarTicketsMesAnio;
                                                        } else if ($i == 4) {
                                                            $totalMes4 = $totalMes4 + $contarTicketsMesAnio;
                                                        } else if ($i == 5) {
                                                            $totalMes5 = $totalMes5 + $contarTicketsMesAnio;
                                                        } else if ($i == 6) {
                                                            $totalMes6 = $totalMes6 + $contarTicketsMesAnio;
                                                        } else if ($i == 7) {
                                                            $totalMes7 = $totalMes7 + $contarTicketsMesAnio;
                                                        } else if ($i == 8) {
                                                            $totalMes8 = $totalMes8 + $contarTicketsMesAnio;
                                                        } else if ($i == 9) {
                                                            $totalMes9 = $totalMes9 + $contarTicketsMesAnio;
                                                        } else if ($i == 10) {
                                                            $totalMes10 = $totalMes10 + $contarTicketsMesAnio;
                                                        } else if ($i == 11) {
                                                            $totalMes11 = $totalMes11 + $contarTicketsMesAnio;
                                                        } else if ($i == 12) {
                                                            $totalMes12 = $totalMes12 + $contarTicketsMesAnio;
                                                        }


                                                        $cantTotalFinal = $cantTotalFinal + $cantTotalDepto;
                                                    }

                                                   ?>
                                                   <td style="font-size: 15px" class="text-center text-bold">
                                                       <?php echo $cantTotalDepto; ?>
                                                   </td>
                                               </tr>
                                               <?php
                                           }
                                           ?>

                                           <?php
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
                                           <th scope="row" style="font-size: 15px" class="text-center">Totales</th>

                                           <?php
                                           for ($i = 0; $i < 12; $i++) {
                                               ?>
                                               <td style="font-size: 15px" class="text-center  text-bold"><?php echo $arrayCantTotal[$i] ?></td>
                                               <?php
                                               $cantPorMesTotal = $cantPorMesTotal + $arrayCantTotal[$i];
                                           }
                                           ?>

                                           <td style="font-size: 15px" class="text-center text-bold"><?php echo $cantPorMesTotal ?></td>
                                       </tr>

                                       </tbody>
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="card collapsed-card">
                                <div class="card-header" style="background-color: indianred;color: whitesmoke">

                                        <h3 class="card-title"><i class="fas fa-ticket-alt"></i>&nbsp;&nbsp;<b>Cantidad de Tickets por Tipo de Estado y Departamento </b></h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                </div>
                                <div class="card-body" >

                                    <table style="font-size:80%"  class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="text-center">DEPARTAMENTOS</th>
                                            <th scope="col" class="text-center">SI</th>
                                            <th scope="col" class="text-center">SPA</th>
                                            <th scope="col" class="text-center">SCT</th>
                                            <th scope="col" class="text-center">SO</th>
                                            <th scope="col" class="text-center">SPT</th>
                                            <th scope="col" class="text-center">SP</th>
                                            <th scope="col" class="text-center">SC</th>
                                            <th scope="col" class="text-center">SF</th>
                                            <th scope="col" class="text-center">Total Año <?php echo $nowAnio?></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlSelect = "SELECT * FROM departamentos";
                                        $resSelect = mysqli_query($conexion,$sqlSelect);
                                        while($rowSelect = $resSelect->fetch_assoc()){
                                            $nomDepartamentos = $rowSelect['nomDepartamento'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <h6><?php echo $nomDepartamentos?></h6>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSI = "SELECT COUNT(*) AS TOTALSI FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Solicitud Creada'";
                                                    $resSI = mysqli_query($conexion,$sqlSI);
                                                    while($rowSI = $resSI->fetch_assoc()){
                                                        $totalSI = $rowSI['TOTALSI'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSI?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSPA = "SELECT COUNT(*) AS TOTALSPA FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Solicitud con Personal Asignado'";
                                                    $resSPA = mysqli_query($conexion,$sqlSPA);
                                                    while($rowSPA = $resSPA->fetch_assoc()){
                                                        $totalSPA = $rowSPA['TOTALSPA'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSPA?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSCT = "SELECT COUNT(*) AS TOTALSCT FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Se encuentra con Solicitud de Compra'";
                                                    $resSCT = mysqli_query($conexion,$sqlSCT);
                                                    while($rowSCT = $resSCT->fetch_assoc()){
                                                        $totalSCT = $rowSCT['TOTALSCT'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSCT?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSO = "SELECT COUNT(*) AS TOTALSO FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Solicitud con Observaciones Nuevas'";
                                                    $resSO = mysqli_query($conexion,$sqlSO);
                                                    while($rowSO = $resSO->fetch_assoc()){
                                                        $totalSO = $rowSO['TOTALSO'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSO?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSPT = "SELECT COUNT(*) AS TOTALSPT FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Ticket con Programación de Trabajos'";
                                                    $resSPT = mysqli_query($conexion,$sqlSPT);
                                                    while($rowSPT = $resSPT->fetch_assoc()){
                                                        $totalSPT = $rowSPT['TOTALSPT'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSPT?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSP = "SELECT COUNT(*) AS TOTALSP FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Ticket de Proyecto'";
                                                    $resSP = mysqli_query($conexion,$sqlSP);
                                                    while($rowSP = $resSP->fetch_assoc()){
                                                        $totalSP = $rowSP['TOTALSP'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSP?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSC = "SELECT COUNT(*) AS TOTALSC FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND canceladaSolicitud = 1";
                                                    $resSC = mysqli_query($conexion,$sqlSC);
                                                    while($rowSC = $resSC->fetch_assoc()){
                                                        $totalSC = $rowSC['TOTALSC'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSC?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlSF = "SELECT COUNT(*) AS TOTALSF FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos' AND situacionSolicitud = 'Solicitud Finalizada'";
                                                    $resSF = mysqli_query($conexion,$sqlSF);
                                                    while($rowSF = $resSF->fetch_assoc()){
                                                        $totalSF = $rowSF['TOTALSF'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalSF?></h6>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sqlTotal = "SELECT COUNT(*) AS TOTALTotal FROM solicitudes_soportes WHERE deptoResponsable = '$nomDepartamentos'";
                                                    $resTotal = mysqli_query($conexion,$sqlTotal);
                                                    while($rowTotal = $resTotal->fetch_assoc()){
                                                        $totalTotal = $rowTotal['TOTALTotal'];
                                                        ?>
                                                        <h6 class="text-center"><?php echo $totalTotal?></h6>
                                                        <?php
                                                    }
                                                    ?>
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
                    </div>
                </div>
                <br>
            </section>
        </div>
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>

    <!-- /.control-sidebar -->

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
</body>
</html>