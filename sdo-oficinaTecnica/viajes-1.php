<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];



date_default_timezone_set('America/Santiago');


// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Viajes</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <style>
        .zoom-card {
            transition: transform 0.3s;
        }

        .zoom-card:hover {
            transform: scale(1.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }
        .contador {
            display: inline-block;
            background-color: rgb(40,167,69);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }

    </style>

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>

    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule

            $(".show-verdash").click(function() {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#verDashh").hide();
                $("#ocultarDashAC").show();
                $("#ocultarDashh").show();
            });

            $(".show-verdash1").click(function() {
                $("#rowMenu2").show();
                $("#verDashAC1").hide();
                $("#verDashh1").hide();
                $("#ocultarDashAC1").show();
                $("#ocultarDashh1").show();

            });
            $(".show-ocultardash1").click(function() {
                $("#rowMenu2").hide();
                $("#verDashAC1").show();
                $("#verDashh1").show();
                $("#ocultarDashAC1").hide();
                $("#ocultarDashh1").hide();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu3").hide();
                $("#verDashAC").show();
                $("#verDashh").show();
                $("#ocultarDashAC").hide();
                $("#ocultarDashh").hide();
            });
        });
    </script>

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
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-body" style="text-align:center;">
                                </div>
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                            </div>
                            <div class="card-body text-center">
                                <div class="card card-success card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="electricidad-tab" data-toggle="tab" data-target="#electricidad" type="button" role="tab" aria-controls="electricidad" aria-selected="true">Viajes</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body text-center">
                                        <hr>
                                        <div class="text-center">
                                            <?php
                                            if (isset($_GET['exitoCrearCategoria'])) {
                                                ?>
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se creo la nueva categoria correctamente.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                            }
                                            if (isset($_GET['errorCrearCategoria'])) {
                                                ?>
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al crear la nueva categoría.&nbsp;&nbsp;&nbsp;&nbsp;<a href="soporte-sdo.php" style="text-decoration: none;" class="btn btn-warning"> <b>Reportar el error</b> </a>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                            }

                                            if (isset($_GET['exitoActualizarCategoria'])) {
                                                ?>
                                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Exito al modificar la categoria seleccionada.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                            }
                                            if (isset($_GET['errorActualizarCategoria'])) {
                                                ?>
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al modificar la  categoría seleccionada .&nbsp;&nbsp;&nbsp;&nbsp;<a href="soporte-sdo.php" style="text-decoration: none;" class="btn btn-danger"> <b>Reportar el error</b> </a>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                            }
                                            if(isset($_GET['exitoIngresoInventario'])){
                                                ?>
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se ingreso el equipo al inventario correctamente.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php

                                            }
                                            if(isset($_GET['errorIngresoInventario'])){
                                                ?>
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al ingresar un equipo a inventario.Reintente
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php

                                            }
                                            if(isset($_GET['exitoModificarInventario'])){
                                                ?>
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se modifico la cantidad del equipo correctamente.
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                            }

                                            if(isset($_GET['errorModificarInventario'])){
                                                ?>
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al modificar la cantidad del equipo.Reintente
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php

                                            }
                                            ?>
                                            <!-- Aqui va codigo de un modal para editar la informacion de las categorias ya existentes -->
                                        </div>
                                        <div class="tab-content text-center" id="myTabContent">
                                            <div class="tab-pane fade show active text-center" id="electricidad" role="tabpanel" aria-labelledby="electricidad-tab">
                                                <!-- <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button> -->
                                                <!-- <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button> -->
                                                <div class="row">
                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">
                                                            <h5 style="text-align: center" class="card-title text-bold">Viajes Contrato Antiguo<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Jose Daniel Reyes'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="listado-viajes.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="listado-viajes.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <!--<img src="TCPDF/examples/images/movilizacion.JPG" style="text-align: center; width:300px; height:200px; "> -->
                                                        <!--<img src="../sdo-pdf/TCPDF/examples/images/movilizacion.JPG"  alt="..." height="500px">-->
                                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">
                                                            <h5 style="text-align: center" class="card-title text-bold">Viajes Contrato Nuevo<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Jose Daniel Reyes'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="listado-viajes-contra-nuevo.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="listado-viajes-contra-nuevo.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12" id="rowMenu3" name="rowMenu3" style="display:none;">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <br>
                                                                <h5>Dashboard de Informaci&oacute;n</h5>
                                                                <hr>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <?php

                                                                        date_default_timezone_set('America/Santiago');
                                                                        $nowAnio = date('Y', time());
                                                                        $nowMes = date('m', time());


                                                                        $mesDespues = date("m", strtotime($now . "+ 1 month"));

                                                                        $nombreMes = '';

                                                                        ?>
                                                                        <div class="row">

                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $nowAnio?> HBQP</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>
                                                                                        </div>

                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border table-responsive">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {
                                                                                                        if ($i > $nowMes) {
                                                                                                        }else{
                                                                                                            $t1Mes = obtenerConsumoMesEnergia($nowAnio . '-' . $i);
                                                                                                            $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1Mes. ' kWh' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {

                                                                                                            $t1Mes = obtenerMontodelMes($nowAnio . '-' . $i);
                                                                                                            $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                                                                            ?>



                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mes. ' con IVA'.' <br>'; ?></b></p>

                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php

                                                                                                    $total = number_format($total, 0, ",", ".");

                                                                                                    ?>

                                                                                                    <p class="info-box-text text-center"><b><?php echo '' . $total . ' kWh.<br>'; ?></b></p>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    $totalMonto = number_format($totalMonto, 0, ",", ".");
                                                                                                    ?>
                                                                                                    <p class="info-box-text text-center"><b><?php echo '$'. $totalMonto . ' <br>'; ?></b></p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $nowAnio?> HSMQ</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>

                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            $t1Mes = obtenerSumaTotalHSMQ($nowAnio . '-' . $i);
                                                                                                            $t1Mes = number_format($t1Mes, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' kWh' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            $t2Mes = obtenerSumaTotalMontoHSMQ($nowAnio . '-' . $i);
                                                                                                            $t2Mes = number_format($t2Mes, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>

                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQL2 = "SELECT SUM(consumoEnergia) as TotalSumaConsumo1 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ'";
                                                                                                    $RES2 = mysqli_query($conexion,$SQL2);
                                                                                                    while($ROW2 = $RES2->fetch_assoc()){
                                                                                                        $TotalSumaConsumo1 = $ROW2['TotalSumaConsumo1'];
                                                                                                        if(empty($TotalSumaConsumo1)){
                                                                                                            $TotalSumaConsumo1 = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumo1 . 'kWh <br>'; ?></b></p>

                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    $SQL3 = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ'";
                                                                                                    $RES3 = mysqli_query($conexion,$SQL3);
                                                                                                    while($ROW3 = $RES3->fetch_assoc()){
                                                                                                        $TotalSumaConsumo2 = $ROW3['TotalSumaConsumo2'];
                                                                                                        if(empty($TotalSumaConsumo2)){
                                                                                                            $TotalSumaConsumo2 = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumo2 = number_format($TotalSumaConsumo2, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo '$'. $TotalSumaConsumo2 . ' <br>'; ?></b></p>

                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;TOTALES AÑO  <?php echo $nowAnio?> AMBOS HOSPITALES</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>
                                                                                        </div>

                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++){
                                                                                                        if ($i > $nowMes){
                                                                                                        }else{
                                                                                                            $consumoEnergia = obtenerSumaCosumodeTodo($nowAnio . '-' . $i);
                                                                                                            $consumoEnergia = number_format($consumoEnergia, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoEnergia.' kWh' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++){
                                                                                                        if ($i > $nowMes){
                                                                                                        }else{
                                                                                                            $montoTotalElectrico = obtenerSumaTotaldeTodo($nowAnio . '-' . $i);
                                                                                                            $montoTotalElectrico = number_format($montoTotalElectrico, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalElectrico ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQLCONSUMO = "SELECT SUM(consumoEnergia) as TotalSumaConsumoTotal FROM electricidad_administracion";
                                                                                                    $RESCONSUMO = mysqli_query($conexion,$SQLCONSUMO);
                                                                                                    while($ROWCONSUMO = $RESCONSUMO->fetch_assoc()){
                                                                                                        $TotalSumaConsumoTotal = $ROWCONSUMO['TotalSumaConsumoTotal'];
                                                                                                        if(empty($TotalSumaConsumoTotal)){
                                                                                                            $TotalSumaConsumoTotal = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumoTotal = number_format($TotalSumaConsumoTotal, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotal . 'kWh <br>'; ?></b></p>

                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQLMONTOTOTALENERGIA = "SELECT SUM(montoTotalMes) as TotalSumaMontoTotal FROM electricidad_administracion";
                                                                                                    $RESMONTOENERGIA = mysqli_query($conexion,$SQLMONTOTOTALENERGIA);
                                                                                                    while($ROWMONTO = $RESMONTOENERGIA->fetch_assoc()){
                                                                                                        $TotalSumaMontoTotal = $ROWMONTO['TotalSumaMontoTotal'];
                                                                                                        if(empty($TotalSumaMontoTotal)){
                                                                                                            $TotalSumaMontoTotal = 0;
                                                                                                        }
                                                                                                        $TotalSumaMontoTotal = number_format($TotalSumaMontoTotal, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotal; ?></b></p>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
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
                                                        </div>
                                                    </div> <!--HASTA AQUI-->
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="agua" role="tabpanel" aria-labelledby="agua-tab">
                                                <button class="btn bg-green show-verdash1" id="verDashAC1" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                                <button class="btn bg-red show-ocultardash1" id="ocultarDashAC1" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
                                                <hr>
                                                <div class="row">

                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">

                                                            <h5 style="text-align: center" class="card-title text-bold">Hospital Biprovincial Quillota Petorca<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Cynthia Latorre Ravanales'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="agua_administracion_hbqp.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="agua_administracion_hbqp.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <img src="../dist/img/HSMQ.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">
                                                            <h5 style="text-align: center" class="card-title text-bold">Hospital San Martin de Quillota<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Cynthia Latorre Ravanales'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="agua_administracion_hsmq.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="agua_administracion_hsmq.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12" id="rowMenu2" name="rowMenu2" style="display: none">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <br>
                                                                <h5>Dashboard de Informaci&oacute;n</h5>
                                                                <hr>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <?php

                                                                        date_default_timezone_set('America/Santiago');
                                                                        $nowAnio = date('Y', time());
                                                                        $nowMes = date('m', time());


                                                                        $mesDespues = date("m", strtotime($now . "+ 1 month"));

                                                                        $nombreMes = '';

                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $nowAnio?> HBQP</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>
                                                                                        </div>

                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {
                                                                                                        if ($i > $nowMes) {
                                                                                                        }else{
                                                                                                            $t1MesAGUA = obtenerConsumoMesAgua($nowAnio . '-' . $i);
                                                                                                            $t1MesAGUA = number_format($t1MesAGUA, 0, ",", ".");
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1MesAGUA. ' (Mt<sup>3</sup>)' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {

                                                                                                            $t1Mesagua1 = obtenerMontodelMesAgua($nowAnio . '-' . $i);
                                                                                                            $t1Mesagua1 = number_format($t1Mesagua1, 0, ",", ".");
                                                                                                            ?>



                                                                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mesagua1. ' con IVA'.' <br>'; ?></b></p>

                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php

                                                                                                    $totalAGUA = number_format($totalAGUA, 0, ",", ".");

                                                                                                    ?>

                                                                                                    <p class="info-box-text text-center"><b><?php echo '' . $totalAGUA . ' (Mt<sup>3</sup>).<br>'; ?></b></p>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    $totalMontoAGUA = number_format($totalMontoAGUA, 0, ",", ".");
                                                                                                    ?>
                                                                                                    <p class="info-box-text text-center"><b><?php echo '$'. $totalMontoAGUA . ' <br>'; ?></b></p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $nowAnio?> HSMQ</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>

                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            $t1MesConsumoAgua = obtenerSumaTotalAguaHSMQ($nowAnio . '-' . $i);
                                                                                                            $t1MesConsumoAgua = number_format($t1MesConsumoAgua, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1MesConsumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            $t2MesMontoTotalAgua = obtenerSumaTotalMontoAguaHSMQ($nowAnio . '-' . $i);
                                                                                                            $t2MesMontoTotalAgua = number_format($t2MesMontoTotalAgua, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2MesMontoTotalAgua ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>

                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQL2agua = "SELECT SUM(consumoAgua) as TotalSumaConsumo1agua FROM agua_administracion WHERE tipoHospital = 'HSMQ'";
                                                                                                    $RES2agua = mysqli_query($conexion,$SQL2agua);
                                                                                                    while($ROW2agua = $RES2agua->fetch_assoc()){
                                                                                                        $TotalSumaConsumo1agua = $ROW2agua['TotalSumaConsumo1agua'];
                                                                                                        if(empty($TotalSumaConsumo1agua)){
                                                                                                            $TotalSumaConsumo1agua = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumo1agua = number_format($TotalSumaConsumo1agua, 0, ",", ".");

                                                                                                    }
                                                                                                    ?>
                                                                                                    <p class="info-box-text text-center"><b><?php echo  $TotalSumaConsumo1agua . ' (Mt<sup>3</sup>) <br>'; ?></b></p>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    $SQL3agua = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2agua FROM agua_administracion WHERE tipoHospital = 'HSMQ'";
                                                                                                    $RES3agua = mysqli_query($conexion,$SQL3agua);
                                                                                                    while($ROW3agua = $RES3agua->fetch_assoc()){
                                                                                                        $TotalSumaConsumo2agua = $ROW3agua['TotalSumaConsumo2agua'];
                                                                                                        if(empty($TotalSumaConsumo2agua)){
                                                                                                            $TotalSumaConsumo2agua = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumo2agua = number_format($TotalSumaConsumo2agua, 0, ",", ".");

                                                                                                    }
                                                                                                    ?>
                                                                                                    <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaConsumo2agua ?></b></p>

                                                                                                </td>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-md-4 h-100">
                                                                                <div class="card card-primary">
                                                                                    <div class="card-header">
                                                                                        <center>
                                                                                            <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;TOTALES AÑO  <?php echo $nowAnio?> AMBOS HOSPITALES</b></h3>
                                                                                        </center>
                                                                                        <div class="card-tools">

                                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                                <i class="fas fa-minus"></i>
                                                                                            </button>

                                                                                        </div>

                                                                                        <!-- /.card-tools -->
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <table class="table table-bordered cell-border" id="example3">
                                                                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <th scope="col" class="text-center">MESES</th>
                                                                                                <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
                                                                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody style="font-size: 12px" class="table-bordered">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++) {

                                                                                                        if ($i > $nowMes) {
                                                                                                        } else {
                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++){
                                                                                                        if ($i > $nowMes){
                                                                                                        }else{
                                                                                                            $consumoAgua = obtenerSumaConsumodeTodoAgua($nowAnio . '-' . $i);
                                                                                                            $consumoAgua = number_format($consumoAgua, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php
                                                                                                    for ($i = 1; $i < 13; $i++){
                                                                                                        if ($i > $nowMes){
                                                                                                        }else{
                                                                                                            $montoTotalAgua = obtenerSumaTotaldeTodoAgua($nowAnio . '-' . $i);
                                                                                                            $montoTotalAgua = number_format($montoTotalAgua, 0, ",", ".");

                                                                                                            ?>
                                                                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalAgua ?></b></p>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                                                                            <tr>
                                                                                                <td class="info-box-text text-center">Total</td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQLCONSUMOAGUA = "SELECT SUM(consumoAgua) as TotalSumaConsumoTotalAgua FROM agua_administracion";
                                                                                                    $RESCONSUMOAGUA = mysqli_query($conexion,$SQLCONSUMOAGUA);
                                                                                                    while($ROWCONSUMOAGUA = $RESCONSUMOAGUA->fetch_assoc()){
                                                                                                        $TotalSumaConsumoTotalAgua = $ROWCONSUMOAGUA['TotalSumaConsumoTotalAgua'];
                                                                                                        if(empty($TotalSumaConsumoTotalAgua)){
                                                                                                            $TotalSumaConsumoTotalAgua = 0;
                                                                                                        }
                                                                                                        $TotalSumaConsumoTotalAgua = number_format($TotalSumaConsumoTotalAgua, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotalAgua . '(Mt<sup>3</sup>) <br>'; ?></b></p>

                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class="text-center">
                                                                                                    <?php
                                                                                                    $SQLMONTOTOTALAGUA = "SELECT SUM(montoTotalMes) as TotalSumaMontoTotalAGUA FROM agua_administracion";
                                                                                                    $RESMONTOAGUA = mysqli_query($conexion,$SQLMONTOTOTALAGUA);
                                                                                                    while($ROWMONTOAGUA = $RESMONTOAGUA->fetch_assoc()){
                                                                                                        $TotalSumaMontoTotalAGUA = $ROWMONTOAGUA['TotalSumaMontoTotalAGUA'];
                                                                                                        if(empty($TotalSumaMontoTotalAGUA)){
                                                                                                            $TotalSumaMontoTotalAGUA = 0;
                                                                                                        }
                                                                                                        $TotalSumaMontoTotalAGUA = number_format($TotalSumaMontoTotalAGUA, 0, ",", ".");
                                                                                                        ?>
                                                                                                        <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotalAGUA; ?></b></p>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
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
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="combustible" role="tabpanel" aria-labelledby="combustible-tab">

                                                <div class="row">
                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">
                                                            <h5 style="text-align: center" class="card-title text-bold">Hospital Biprovincial Quillota Petorca<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Matias Gutierrez'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="combustible_visor.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="combustible_visor.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

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
    // Obtener referencia al campo de entrada de archivo
    const inputImagen = document.getElementById('imagen');
    // Obtener referencia al div de vista previa
    const vistaPreviaDiv = document.getElementById('vista-previa');

    // Escuchar el evento change del campo de entrada de archivo
    inputImagen.addEventListener('change', function(e) {
        // Obtener la imagen seleccionada
        const imagenSeleccionada = e.target.files[0];

        // Crear un objeto URL para la imagen seleccionada
        const imagenURL = URL.createObjectURL(imagenSeleccionada);

        // Crear un elemento de imagen para mostrar la vista previa
        const imagenPrevia = document.createElement('img');
        imagenPrevia.src = imagenURL;
        imagenPrevia.classList.add('img-fluid');

        // Limpiar el div de vista previa antes de añadir la nueva imagen
        vistaPreviaDiv.innerHTML = '';

        // Agregar la imagen de vista previa al div
        vistaPreviaDiv.appendChild(imagenPrevia);
    });
</script>
<script>
    $('[data-id]').click(function() {
        var dataId = $(this).attr('data-id');
        $('#modalEditarCategoria').data('id', dataId).modal('show');
        var elementos = {};
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtiene_datos_categoria.php',
            data: {
                'id': dataId
            },
            success: function(response) {
                // console.log(response);
                var datos = JSON.parse(response);

                elementos.nomCategoria = datos.nomCategoria;
                elementos.idDepartamento = datos.idDepartamento;
                elementos.imagenCategoria = datos.imagenCategoria;

                $('#idCategoria').val(dataId);
                $('#nombreCategoriaEdita').val(elementos.nomCategoria);
                $('#departamentoCategoriaEdita').val(elementos.idDepartamento);
                $('#img').attr('src',  elementos.imagenCategoria );

            }
        })
    })
</script>

<script>
    $(document).ready(function() {
        $('#example3').DataTable({
            "paging": false,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": false,
            "ordering": false,
            "info": false,
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
    function obtenerid(idCategoria){
        console.log(idCategoria);

</script>
<script type="text/javascript">
    $("input[name='opciones']").change(function() {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>
<script src="js/fullScreen.js"></script>
</body>

</html>