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

function totalEquipos($idCategoria){
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function totalSumaEquipos($idCategoria){
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);
    $cantidadEquipos = 0;
    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    $totalEquipos1 = $cantidadEquipos + $totalEquipos;
    return $totalEquipos1;
}



function equiposOperativos($idCategoria){
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo = 'Operativo'";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function equiposConObservacion($idCategoria){
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM historial_cambios_equipos WHERE idCategoria = $idCategoria ";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function equiposFueraDeServicio($idCategoria){
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo = 'Fuera de Servicio'";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

$sqlSuma = "SELECT SUM(consumoEnergia) as total FROM `electricidad_administracion` WHERE tipohospital = 'HBQP'";
$resSuma = mysqli_query($conexion,$sqlSuma);
while($rowSuma = $resSuma->fetch_assoc()){
    $datos = [];
    $datos['total'] = $rowSuma['total'];
    $total = $datos['total'];
}

$sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM electricidad_administracion WHERE tipoHospital = 'HBQP'";
$resMonto = mysqli_query($conexion,$sqlMonto);
while($rowMonto = $resMonto->fetch_assoc()){
    $datosMonto = [];
    $datosMonto['totalMonto'] = $rowMonto['totalMonto'];
    $totalMonto = $datosMonto['totalMonto'];

}


$sqlSumaAGUA = "SELECT SUM(consumoAgua) as totalAGUA FROM agua_administracion WHERE tipohospital = 'HBQP'";
$resSumaAGUA = mysqli_query($conexion, $sqlSumaAGUA);
while ($rowSumaAGUA = $resSumaAGUA->fetch_assoc()) {
    $totalAGUA = $rowSumaAGUA['totalAGUA'];
}

$sqlMontoAGUA = "SELECT SUM(montoTotalMes) as totalMontoAGUA FROM agua_administracion WHERE tipoHospital = 'HBQP'";
$resMontoAGUA = mysqli_query($conexion, $sqlMontoAGUA);
while ($rowMontoAGUA = $resMontoAGUA->fetch_assoc()) {
    $totalMontoAGUA = $rowMontoAGUA['totalMontoAGUA'];

}

$sqlSumaGASES = "SELECT SUM(valorTotalNeto) as total FROM `gases_clinicos`";
$resSumaGASES = mysqli_query($conexion,$sqlSumaGASES);
while($rowSumaGASES = $resSumaGASES->fetch_assoc()){
    $datosGASES = [];
    $datosGASES['total'] = $rowSumaGASES['total'];
    $totalGASES = $datosGASES['total'];
}

$sqlMontoGASES = "SELECT SUM(valorTotalIva) as totalMonto FROM gases_clinicos";
$resMontoGASES = mysqli_query($conexion,$sqlMontoGASES);
while($rowMontoGASES = $resMontoGASES->fetch_assoc()){
    $datosMontoGASES = [];
    $datosMontoGASES['totalMonto'] = $rowMontoGASES['totalMonto'];
    $totalMontoGASES = $datosMontoGASES['totalMonto'];

}

date_default_timezone_set('America/Santiago');


// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consumos Basicos</title>
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

            $(".show-verdash2").click(function() {
                $("#rowMenu4").show();
                $("#verDashAC2").hide();
                $("#verDashh2").hide();
                $("#ocultarDashAC2").show();
                $("#ocultarDashh2").show();
            });

            $(".show-verdash3").click(function() {
                $("#rowMenu5").show();
                $("#verDashAC3").hide();
                $("#verDashh3").hide();
                $("#ocultarDashAC3").show();
                $("#ocultarDashh3").show();
            });

            $(".show-ocultardash1").click(function() {
                $("#rowMenu2").hide();
                $("#verDashAC1").show();
                $("#verDashh1").show();
                $("#ocultarDashAC1").hide();
                $("#ocultarDashh1").hide();
            });

            $(".show-ocultardash2").click(function() {
                $("#rowMenu4").hide();
                $("#verDashAC2").show();
                $("#verDashh2").show();
                $("#ocultarDashAC2").hide();
                $("#ocultarDashh2").hide();
            });

            $(".show-ocultardash3").click(function() {
                $("#rowMenu5").hide();
                $("#verDashAC3").show();
                $("#verDashh3").show();
                $("#ocultarDashAC3").hide();
                $("#ocultarDashh3").hide();
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
    include '../sdo-templates/user-sidebar.php';
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
                                                <button class="nav-link active" id="electricidad-tab" data-toggle="tab" data-target="#electricidad" type="button" role="tab" aria-controls="electricidad" aria-selected="true">Electricidad</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="agua-tab" data-toggle="tab" data-target="#agua" type="button" role="tab" aria-controls="agua" aria-selected="false">Agua</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="combustible-tab" data-toggle="tab" data-target="#combustible" type="button" role="tab" aria-controls="combustible" aria-selected="false">Combustible</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="gases-tab" data-toggle="tab" data-target="#gases" type="button" role="tab" aria-controls="gases" aria-selected="false">Gases</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="criogenico-tab" data-toggle="tab" data-target="#criogenico" type="button" role="tab" aria-controls="criogenico" aria-selected="false">Criogenico</button>
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
                                                <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                                <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
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
                                                            if($nomUsuario == 'Gustavo Loira' || $nomUsuario == 'Ricardo González Olivares'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="electricidad_administracion_hbqp.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="electricidad_administracion_hbqp.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>                                                            <hr>
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
                                                            if($nomUsuario == 'Gustavo Loira' || $nomUsuario == 'Ricardo González Olivares'){
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="electricidad_administracion_hsmq.php"><b>Administrar</b></a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a class="btn btn-block bg-info " href="electricidad_administracion_hsmq.php"><b>Visualizar</b></a>
                                                                <?php
                                                            }
                                                            ?>                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12" id="rowMenu3" name="rowMenu3" style="display:none;">
                                                        <?php include_once('../sdo-includes/cosumos_basicos_dashboard_electrico.php')?>
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
                                                            ?>                                                            <hr>
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
                                                            ?>                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12" id="rowMenu2" name="rowMenu2" style="display: none">
                                                        <?php include_once('../sdo-includes/consumos_basicos_dashboard_agua.php')?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="combustible" role="tabpanel" aria-labelledby="combustible-tab">
                                                <button class="btn bg-green show-verdash2" id="verDashAC2" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                                <button class="btn bg-red show-ocultardash2" id="ocultarDashAC2" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
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
                                                <div class="col-12 col-md-12" id="rowMenu4" name="rowMenu4" style="display: none">
                                                    <?php include_once '../sdo-includes/combustible_dashboard.php'; ?>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="gases" role="tabpanel" aria-labelledby="gases-tab">
                                                <button class="btn bg-green show-verdash3" id="verDashAC3" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                                <button class="btn bg-red show-ocultardash3" id="ocultarDashAC3" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
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
                                                            if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera'){
                                                                ?>
                                                                <a  class="btn btn-block bg-info " href="gases_clinicos.php"><b></b>Administrar</a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a  class="btn btn-block bg-info " href="gases_clinicos.php"><b></b>Visualizar</a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <hr>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="rowMenu5" name="rowMenu5" style="display: none">
                                                    <?php include_once('../sdo-includes/consumos_basicos_dashboard_gases.php')?>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="criogenico" role="tabpanel" aria-labelledby="criogenico-tab">
                                                <div class="row">
                                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                                        <br>
                                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                                        <div class="card-body text-center">
                                                            <h5 style="text-align: center" class="card-title text-bold">Hospital Biprovincial Quillota Petorca<br></h5>
                                                            <br>&nbsp;
                                                            <br>
                                                            <?php
                                                            if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera'){
                                                                ?>
                                                                <a  class="btn btn-block bg-info " href="criogenico_datos.php"><b></b>Administrar</a>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <a  class="btn btn-block bg-info " href="criogenico_datos.php"><b></b>Visualizar</a>
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