<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];



date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Niveles de Combustible</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

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
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
            });

            $(".show-verdash").click(function() {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#ocultarDashAC").show();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu3").hide();
                $("#verDashAC").show();
                $("#ocultarDashAC").hide();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Administraci&oacute;n de Combustible</h4>

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

                                <a href="consumos_basicos.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>

                            <div class="card-body">

                                <div class="row text-center" style="display: flex; justify-content:center;">

                                    <?php
                                    if($userUsuario == 'halvarez' || $userUsuario == 'mgutierrez'){
                                        ?>
                                        <div id="registrarAC2" name="registrarAC2" class="col-lg-3 col-md-6 col-12 text-center">
                                            <!-- small box -->
                                            <button id="registrarAC" name="registrarAC" class="btn btn-block bg-gradient-primary btn-lg show-realizar text-white text-center"><i class="nav-icon fas fa-gas-pump"></i><br>Registrar Medici&oacute;n</button>

                                        </div>
                                        <?php
                                    }
                                    ?>


                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">
                                        <!-- small box -->
                                        <button id="listarAC" style="display:none;" name="listarAC" class="btn btn-block bg-gradient-purple btn-lg text-white text-center show-listar"><i class="nav-icon fas fa-gas-pump"></i><br>Informe de Combustible</button>

                                    </div>
                                    &nbsp;
                                    <?php
                                    if($userUsuario == 'halvarez' || $userUsuario == 'mgutierrez'){
                                        ?>
                                        <div class="col-lg-3 col-md-6 col-12 text-center">
                                            <!-- small box -->
                                            <a href="../sdo-pdf/SDO-Registro-Combustible.php" target="_blank" class="btn btn-block bg-gradient-secondary btn-lg text-white text-center"><i class="nav-icon fas fa-gas-pump"></i><br>Historial de Combustible</a>

                                        </div>
                                        <?php
                                    }
                                    ?>



                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                        <button class="btn btn-block bg-green btn-lg text-white text-center show-verdash" id="verDashAC" name="verDashAC"><i class="nav-icon fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                        <button class="btn btn-block bg-red btn-lg text-white text-center show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="nav-icon fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
                                    </div>



                                </div>

                                <hr>


                                <div class="row" style="display: flex; justify-content:center;">
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 text-center">

                                        <div class="info-box bg-info">
                                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gas-pump"></i></span>

                                            <div class="info-box-content">
                                                <?php

                                                $fechaHoy = strtotime("now");

                                                ?>
                                                <span class="info-box-text"><b><?php echo mesEspañol($fechaHoy); ?></b></span>
                                                <span class="info-box-number">
                                                        <?php echo cargasMes() . ' Cargas'; ?>
                                                    </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 text-center">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-gas-pump"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text"><b>&Uacute;ltima Carga</b></span>
                                                <span class="info-box-number">
                                                        <?php

                                                        $fechaCarga = date("d-m-Y", strtotime(ultimaCargaCombustible()));
                                                        echo $fechaCarga;

                                                        ?>
                                                    </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>
                                    <!-- /.col -->


                                </div>
                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <?php

                if (isset($_GET['exitoAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }

                if(isset($_GET['exitoModificarAC'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Modificacion del Combustible conseguida',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        })
                    </script>
                    <?php
                }

                if(isset($_GET['errorModificarAC'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error en la Modificacion de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                if(isset($_GET['error1'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Un dato ingresado se encuentra vacio, Por favor reintente.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                ?>

                <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">
                    <?php include_once('../sdo-includes/combustible_dashboard.php')?>
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Niveles de Combustible</h5>
                                <hr>
                                <br>
                                <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                <ul>
                                    <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Cargas de Combustible</li>
                                </ul>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Medici&oacute;n</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Orden de Compra</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Factura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Boleta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Gu&iacute;a de Despacho</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 1 (10.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 2 (10.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 3 (40.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 4 (40.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Precio Unitario</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Observaci&oacute;n</center>
                                            </th>
                                            <?php
                                            if($nomUsuario == 'Matias Gutierrez'){
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Editar</center>
                                                </th>
                                                <?php
                                            }
                                            ?>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryAC = "SELECT * FROM combustible_administracion ORDER BY fechaAC DESC";

                                        $resAC = mysqli_query($conexion, $queryAC);

                                        $numAC = 1;

                                        while ($rowAC = $resAC->fetch_assoc()) {

                                            $fechaAC = $rowAC['fechaAC'];

                                            $idAC = $rowAC['idAC'];

                                            $ordenCompraAC = $rowAC['ordenCompraAC'];
                                            $ordenCompraAC = trim($ordenCompraAC);

                                            $facturaAC = $rowAC['facturaAC'];
                                            $facturaAC = trim($facturaAC);

                                            $boletaAC = $rowAC['boletaAC'];
                                            $boletaAC = trim($boletaAC);

                                            $guiaDespachoAC = $rowAC['guiaDespachoAC'];
                                            $guiaDespachoAC = trim($guiaDespachoAC);

                                            $tanque1AC = $rowAC['tanque1AC'];
                                            $tanque1AC = trim($tanque1AC);

                                            $tanque2AC = $rowAC['tanque2AC'];
                                            $tanque2AC = trim($tanque2AC);

                                            $tanque3AC = $rowAC['tanque3AC'];
                                            $tanque3AC = trim($tanque3AC);

                                            $tanque4AC = $rowAC['tanque4AC'];
                                            $tanque4AC = trim($tanque4AC);

                                            $precioAC = $rowAC['precioAC'];
                                            $precioAC = trim($precioAC);

                                            $usuarioAC = $rowAC['usuarioAC'];
                                            $usuarioAC = trim($usuarioAC);

                                            $observacionAC = $rowAC['observacionAC'];
                                            $observacionAC = trim($observacionAC);

                                            $tipoAC = $rowAC['tipoAC'];
                                            $tipoAC = trim($tipoAC);

                                            if ($tipoAC == 'Carga de Combustible') {
                                                $classTR = 'bg-yellow';
                                            } else {
                                                $classTR = '';
                                            }
                                            ?>

                                            <tr class="<?php echo $classTR; ?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaAC == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaAC)));
                                                        $registroHoraAC = date('H:i', strtotime($fechaAC));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaAC . ' a las ' . $registroHoraAC . ' hrs';
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($ordenCompraAC) || $ordenCompraAC == '-') {
                                                        echo "No registra Orden de Compra";
                                                    } else {

                                                        echo $ordenCompraAC;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($facturaAC) || $facturaAC == '-') {
                                                        echo "No registra Factura";
                                                    } else {

                                                        echo $facturaAC;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($boletaAC) || $boletaAC == '-') {
                                                        echo "No registra Boleta";
                                                    } else {

                                                        echo $boletaAC;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($guiaDespachoAC) || $guiaDespachoAC == '-') {
                                                        echo "No registra Guia de Despacho";
                                                    } else {
                                                        echo $guiaDespachoAC;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($tanque1AC > -1 || !empty($tanque1AC)) {
                                                        echo number_format($tanque1AC, 0, ",", ".");
                                                    } else {
                                                        echo "No registra Medida";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($tanque2AC > -1 || !empty($tanque2AC)) {
                                                        echo number_format($tanque2AC, 0, ",", ".");
                                                    } else {
                                                        echo "No registra Medida";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($tanque3AC > -1 || !empty($tanque3AC)) {
                                                        echo number_format($tanque3AC, 0, ",", ".");
                                                    } else {
                                                        echo "No registra Medida";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($tanque3AC > -1 || !empty($tanque3AC)) {
                                                        echo number_format($tanque4AC, 0, ",", ".");
                                                    } else {
                                                        echo "No registra Medida";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (!empty($precioAC)) {
                                                        echo '$' . number_format($precioAC, 0, ",", ".");
                                                    } else {
                                                        echo "No registra Precio";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (!empty($observacionAC)) {
                                                        echo $observacionAC;
                                                    } else {
                                                        echo "No registra Observaci&oacute;n";
                                                    } ?>

                                                </td>
                                                <?php
                                                if($nomUsuario == 'Matias Gutierrez'){
                                                    ?>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <form action="modificar_combustible.php" method="POST">
                                                            <input type="hidden" id="idAC" name="idAC" value="<?php echo $idAC?>">
                                                            <button type="submit" class="btn btn-info btn-xs">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                        </form>

                                                    </td>

                                                    <?php
                                                }else{

                                                }
                                                ?>

                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Medici&oacute;n</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Orden de Compra</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Factura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Boleta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Gu&iacute;a de Despacho</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 1 (10.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 2 (10.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 3 (40.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tanque N° 4 (40.000 Lts.)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Precio Unitario</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Observaci&oacute;n</center>
                                            </th>
                                            <?php
                                            if($nomUsuario == 'Matias Gutierrez'){
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Editar</center>
                                                </th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar Combustible</h5>
                                <br>



                                <fieldset class="border p-3 reset">
                                    <legend class="w-auto reset"><b>&nbsp;Informaci&oacute;n del Procedimiento:&nbsp;&nbsp;</b></legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTurno">Procedimiento Realizado: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="opciones" id="exampleRadios1" value="medicionN">
                                                    <label class="form-check-label" for="exampleRadios1">
                                                        Medici&oacute;n Normal
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="opciones" id="exampleRadios2" value="cargaC">
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        Carga de Combustible
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <hr>

                                <fieldset class="border p-3 reset" id="cargaCombustible" name="cargaCombustible">
                                    <legend class="w-auto reset"><b>&nbsp;Procedimiento: Carga de Combustible&nbsp;&nbsp;</b></legend>

                                    <form action="../sdo-funciones/combustible_nuevaCarga.php" method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Fecha de Carga: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="fechaCargaAC" placeholder="Fecha de Carga" type="datetime-local" name="fechaCargaAC" />
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Orden de Compra: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="ordenCompraAC" placeholder="N° de Orden de Compra" type="text" name="ordenCompraAC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Factura:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="facturaAC" placeholder="N° de Factura" type="text" name="facturaAC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Boleta:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="boletaAC" placeholder="N° de Boleta" type="text" name="boletaAC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Guia de Despacho: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="guiaDespachoAC" placeholder="N° de Guia de Despacho" type="text" name="guiaDespachoAC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Carga: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="noCargaAC" placeholder="N° de Carga" type="text" name="noCargaAC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 1: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque1AC" placeholder="Medida Tanque N° 1" type="number" min="0" name="tanque1AC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 2: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque2AC" placeholder="Medida Tanque N° 2" type="number" min="0" name="tanque2AC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 3: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque3AC" placeholder="Medida Tanque N° 3" type="number" min="0" name="tanque3AC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 4: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque4AC" placeholder="Medida Tanque N° 4" type="number" min="0" name="tanque4AC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Precio Unitario: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="precioAC" placeholder="Precio Unitario" type="number" min="0" name="precioAC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="exampleInputObservaciones">Observaciones:</label>
                                            <textarea id='observacionAC' name='observacionAC' class="form-control"></textarea><br>
                                            <!-- Script -->
                                            <script type="text/javascript">
                                                $('#observacionAC').summernote({
                                                    height: 200,
                                                });
                                                $('#observacionAC').summernote('foreColor', 'rgb(255, 255, 255)');
                                            </script>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <input type="hidden" name="personalAC" id="personalAC" value="<?php echo $nomUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                    </form>
                                </fieldset>


                                <fieldset class="border p-3 reset" id="medicionNormal" name="medicionNormal">
                                    <legend class="w-auto reset"><b>&nbsp;Medici&oacute;n Normal&nbsp;&nbsp;</b></legend>
                                    <br>
                                    <p><b>¡ Importante !: </b>Destacar que los campos son autocompletados con la &uacute;ltima recarga de combustible. En caso de existir alg&uacute;n error, puede modificar los campos.</p>
                                    <br>

                                    <form action="../sdo-funciones/combustible_nuevaMedicionEnc.php" method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Fecha de Carga: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="fechaCargaAC" placeholder="Fecha de Carga" type="datetime-local" name="fechaCargaAC" />
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Orden de Compra: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>

                                                    <input class="form-control" required id="ordenCompraAC" placeholder="N° de Orden de Compra" type="text" name="ordenCompraAC" value="<?php echo ultimaOCCompraAC(); ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Factura:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="facturaAC" placeholder="N° de Factura" type="text" name="facturaAC" value="<?php echo ultimaFacturaAC(); ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Boleta: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="boletaAC" placeholder="N° de Boleta" type="text" name="boletaAC" value="<?php echo ultimaBoletaAC(); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Guia de Despacho: </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" id="guiaDespachoAC" placeholder="N° de Guia de Despacho" type="text" name="guiaDespachoAC" value="<?php echo ultimaGuiaDespAC(); ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese N° de Carga: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="noCargaAC" placeholder="N° de Carga" type="text" name="noCargaAC" value="<?php echo ultimaNoCargaAC(); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 1: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque1AC" placeholder="Medida Tanque N° 1" type="number" min="0" name="tanque1AC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 2: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque2AC" placeholder="Medida Tanque N° 2" type="number" min="0" name="tanque2AC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 3: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque3AC" placeholder="Medida Tanque N° 3" type="number" min="0" name="tanque3AC" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Medida Tanque N° 4: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="tanque4AC" placeholder="Medida Tanque N° 4" type="number" min="0" name="tanque4AC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputTerreno1">Ingrese Precio Unitario: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="precioAC" placeholder="Precio Unitario" type="number" min="0" name="precioAC" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="exampleInputObservaciones">Observaciones:</label>
                                            <textarea id='observacionMedAC' name='observacionMedAC' class="form-control"></textarea><br>
                                            <!-- Script -->
                                            <script type="text/javascript">
                                                $('#observacionMedAC').summernote({
                                                    height: 200,
                                                });
                                                $('#observacionMedAC').summernote('foreColor', 'rgb(255, 255, 255)');
                                            </script>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <input type="hidden" name="personalAC" id="personalAC" value="<?php echo $nomUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                    </form>
                                </fieldset>



                            </div>

                        </div>

                    </div>

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
<script src="js/dark-mode.js"></script>
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
            "buttons": [{
                extend: 'excelHtml5',
                className: 'btn btn-block bg-gradient-green btn-lg text-white text-center',
                text: 'Exportar a Excel',
                title: 'Resumen Gasolina',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    format: {
                        body: function(data, column, row) {
                            if (typeof data === 'string' || data instanceof String) {
                                data = data.replace(/<br\s*\/?>/ig, "\r\n");
                            }
                            return data;
                        },
                        body: function(data, row, column, node) {
                            return data.replace('.', ',');
                        }
                    }
                }
            }],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
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

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
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
</body>

</html>