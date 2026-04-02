<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];



date_default_timezone_set('America/Santiago');


$sqlSuma = "SELECT SUM(valorTotalNeto) as total FROM `gases_clinicos`";
$resSuma = mysqli_query($conexion,$sqlSuma);
while($rowSuma = $resSuma->fetch_assoc()){
    $datos = [];
    $datos['total'] = $rowSuma['total'];
    $total = $datos['total'];
}

$sqlMonto = "SELECT SUM(valorTotalIva) as totalMonto FROM gases_clinicos";
$resMonto = mysqli_query($conexion,$sqlMonto);
while($rowMonto = $resMonto->fetch_assoc()){
    $datosMonto = [];
    $datosMonto['totalMonto'] = $rowMonto['totalMonto'];
    $totalMonto = $datosMonto['totalMonto'];

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Gases Clinicos HBQP</title>

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
                $("#registrarAC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#registrarAC").show();
            });

            $(".show-verdash").click(function() {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#verDashh").hide();
                $("#ocultarDashAC").show();
                $("#ocultarDashh").show();
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
    <!-- <script>
         function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
             if (d.checked == true) {
                 document.getElementById(dchecked).style.display = "block";
                 document.getElementById(dunchecked).style.display = "none";
             } else {
                 document.getElementById(dchecked).style.display = "none";
                 document.getElementById(dunchecked).style.display = "block";
             }
         }
     </script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
            });

            $('#selectnCliente').change(function() {
                agregarCliente();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };

            function agregarCliente() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-cliente/ingresarCliente.php",
                    data: "idCliente=" + $('#selectnCliente').val(),
                    success: function(r) {
                        $('#visualizarCliente').html(r);
                    }
                });

            };
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Administracion de Consumo de Gases Clinicos HBQP</h4>

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

                                <a href="consumos_basicos.php" style="float: right;" class="btn bg-gradient-danger btn-md text-white text-center"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <?php
                                if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera' || $nomUsuario == 'Pablo Sanchez Molina'){
                                    ?>
                                    <button class="btn bg-yellow show-realizar" id="registrarAC" name="registrarAC"><i class="fas fa-clipboard"></i>&nbsp;Registrar Consumo de Gases Clinicos</button>
                                    <button class="btn bg-yellow show-listar" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Consumo de Gases Clinicos</button>
                                    <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                    <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>
                                    <?php
                                } else{
                                    ?>
                                    <button class="btn bg-yellow show-listar" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Consumo de Gases Clinicos</button>
                                    <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;Ver Dashboard</button>
                                    <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>&nbsp;Ocultar Dashboard</button>

                                    <?php
                                }
                                ?>



                                <!--  <a class="btn bg-primary" href="../sdo-pdf/SDO-Registro-Combustible.php" target="_blank">Descargar Registro de Combustible</a> -->


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


                <?php

                if (isset($_GET['exitoModificar'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Modificacion del Consumo de Gases Clinicos Conseguido',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['exito'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Consumo de Gases Clinicos Agregado con &Eacute;xito.',
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
                if (isset($_GET['errorInsert'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Consumo de Gases Clinicos.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                if (isset($_GET['errorMofificacion'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Modificar Consumo de Gases Clinicos.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }

                if(isset($_GET['errorSumacilindros'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'El valor neto no es correcto. Por favor reintente',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',

                        });
                    </script>
                    <?php
                }
                ?>



                <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">
                    <?php include_once('../sdo-includes/gases_clinicos_dashboard.php')?>
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Consumo de Gases Clinicos</h5>
                                <hr>
                                <br>
                                <!--
                                <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                <ul>
                                    <li><i style="color: #06961e; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Pagado</li>
                                </ul>
                                --><?php
                                $valorUnitario10m3 = '1405720';
                                $valorUnitario07m3 = '1405000';
                                ?>
                                <div class="table-responsive">

                                    <table style="font-size:80%" id="example2" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cilindros Entregado 10M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cilindros Entregado 0,7M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Pacientes en el Mes</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor 10M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor 0,7M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TOTAL</center>
                                            </th>
                                            <?php
                                            if ($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera') {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Editar</center>
                                                </th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody class="table table-bordered">

                                        <?php
                                        $queryAC = "SELECT * FROM gases_clinicos ORDER BY fechaIngreso DESC";

                                        $resAC = mysqli_query($conexion, $queryAC);

                                        $numAC = 1;

                                        $classTR = "";

                                        while ($rowAC = $resAC->fetch_assoc()) {

                                            $idGases = $rowAC['idGases'];
                                            $fechaIngreso = $rowAC['fechaIngreso'];

                                            $cilindroEntre10m3 = $rowAC['cilindrosEntre10m3'];

                                            $cilindroEntre07m3 = trim($cilindroEntre10m3);

                                            $cilindroEntre07m3 = $rowAC['cilindrosEntre07m3'];
                                            $cilindroEntre07m3 = trim($cilindroEntre07m3);

                                            $numPacientes = $rowAC['nPacientes'];
                                            $valor10M3 = $rowAC['valor10M3'];
                                            $valor07M3 = $rowAC['valor07M3'];
                                            $valorTotalNeto = $rowAC['valorTotalNeto'];
                                            $valorTotal = $rowAC['valorTotalIva'];





                                            if ($estado == 'PAGADO') {
                                                $classTR = "background-color: #06961e; color: white";
                                            } else {
                                                $classTR = '';
                                            }
                                            ?>

                                            <tr style="<?php echo $classTR; ?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaIngreso== 0 || $fechaIngreso == '0000-00-00 00:00:00') {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaIngreso)));


                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaAC;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($cilindroEntre10m3) || $cilindroEntre10m3 == '-' || $cilindroEntre10m3 == 0) {
                                                        echo "No registra cilindros entregados";
                                                    } else {

                                                        echo $cilindroEntre10m3;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty( $cilindroEntre07m3) ||  $cilindroEntre07m3 == '-' ||  $cilindroEntre07m3 == 0) {
                                                        echo "No registra cilindros entregados";
                                                    } else {
                                                        echo $cilindroEntre07m3;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (!$numPacientes == '' || !empty($numPacientes) || !$numPacientes == 0) {
                                                        echo $numPacientes;
                                                    } else {
                                                        echo "-";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($valor10M3 == '-' || $valor10M3 == 0 || empty($valor10M3)) {
                                                        echo "No registra valor en el cilindro de 10M"?><sup>3</sup><?php ;
                                                    } else {
                                                        echo '$' . number_format($valor10M3, 0, ",", ".");
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($valor07M3 > -1 || !empty($valor07M3)) {
                                                        echo '$'.number_format($valor07M3, 0, ",", ".");
                                                    } else {
                                                        echo "No registra valor en el cilindro de 07M"?><sup>3</sup><?php ;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($valorTotalNeto > -1 || !empty($valorTotalNeto)) {
                                                        echo '$' . number_format($valorTotalNeto, 0, ",", ".");
                                                    } else {
                                                        echo "No registra valor total neto";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    echo '$' . number_format($valorTotal, 0, ",", ".");
                                                    ?>
                                                </td>
                                                <?php
                                                if ($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera') {
                                                    ?>
                                                    <td style="vertical-align: middle;" align="center">
                                                        <?php
                                                        if($idGases == 0 || $idGases == '' || $idGases == ' '){
                                                            echo 'NO HAY DATOS';
                                                        }else{
                                                            ?>
                                                            <form action="editar_consumo_gases.php" method="POST">
                                                                <input name="idGases" id="idGases" type="hidden" value="<?php echo $idGases?>">
                                                                <button type="submit" class="form-control btn-info"><i class="fas fa-pen"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php
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
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cilindros Entregado 10M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Cilindros Entregado 0,7M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Pacientes en el Mes</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor 10M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor 0,7M<sup>3</sup></center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TOTAL</center>
                                            </th>
                                            <?php
                                            if ($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera') {
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
                                <h5>2.- Registrar Consumo de Gases Clinicos </h5>
                                <br>

                                <legend class="w-auto reset"><b>&nbsp;Informaci&oacute;n del Procedimiento:&nbsp;&nbsp;</b></legend>
                                <br>
                                <form action="../sdo-funciones/gases_nuevoConsumo.php" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Valor Unitario 10M<sup>3</sup>: <code>*</code></label>
                                            <input class="form-control" required id="valorUnitario10M3" placeholder="Fecha de Carga" readonly type="text" name="valorUnitario10M3" value="<?php echo '$' . number_format( $valorUnitario10m3, 0, ",", ".");  ?>" />
                                            <input class="form-control" required id="valorUnitario10M31" placeholder="Fecha de Carga" readonly type="hidden" name="valorUnitario10M31" value="1405720" />

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Valor Unitario 0,7M<sup>3</sup> <code>*</code></label>
                                            <input class="form-control" required id="valorUnitario07M3" placeholder="Fecha de Carga" readonly type="text" name="valorUnitario07M3" value="<?php echo '$' . number_format( $valorUnitario07m3, 0, ",", "."); ?>"/>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Ingrese Fecha: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input class="form-control" required id="fechaDocumento" placeholder="Fecha de Carga" type="datetime-local" name="fechaDocumento" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Cantidad de Cilindros Entregados de 10M<sup>3</sup>: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control"  id="cantidadCilindros10M3" placeholder="Cantidad de Cilindros" type="number" name="cantidadCilindros10M3" /> <!--onchange="suma()"-->
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Cantidad de Cilindros Entregados de 0,7M<sup>3</sup>: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="cantidadCilindros07M3" placeholder="Cantidad de Cilindros" type="number" name="cantidadCilindros07M3" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>N° de Pacientes En El Mes: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nPacientes" placeholder="Número de Pacientes" type="number" name="nPacientes" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Valor del Cilindro de 10M<sup>3</sup> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorCilindor10m3" placeholder="Valor Cilindro de 10M3" type="number" name="valorCilindor10m3" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Valor del Cilindro de 0,7M<sup>3</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorCilindor07M3" placeholder="Valor Cilindro de 0,7M3" type="number" name="valorCilindor07M3" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Valor Total Neto: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorNeto" placeholder="Valor Neto" type="number" name="valorNeto" />
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <!--
                                    <div class="form-group">
                                        <label for="exampleInputObservaciones">Observaciones:</label>
                                        <textarea id='observacionAC' name='observacionAC' class="form-control"></textarea><br>

                                        <script type="text/javascript">
                                            $('#observacionAC').summernote({
                                                height: 200,
                                            });
                                            $('#observacionAC').summernote('foreColor', 'rgb(255, 255, 255)');
                                        </script>
                                    </div>-->
                                    <br>
                                    <div class="form-group">
                                        <input type="hidden" name="personalAC" id="personalAC" value="<?php echo $nomUsuario; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                    </div>
                                </form>


                                <!--HASTA AQUI-->

                                <hr>




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
            // "buttons": [{
            //     extend: 'excelHtml5',
            //     className: 'btn btn-success',
            //     text: 'Exportar a Excel',
            //     title: 'Resumen Gasolina',
            //     exportOptions: {
            //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            //         format: {
            //             body: function(data, column, row) {
            //                 if (typeof data === 'string' || data instanceof String) {
            //                     data = data.replace(/<br\s*\/?>/ig, "\r\n");
            //                 }
            //                 return data;
            //             }
            //         }
            //     }
            // }],
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

<script>
    function suma(){
        valorUnitario10M3 = document.getElementById("valorUnitario10M31").value;
        valorParse = parseInt(valorUnitario10M3);
        console.log(valorParse);
        cilindros10m3 = document.getElementById("cantidadCilindros10M3").value;
        cilindrosparse = parseInt(cilindros10m3);
        console.log(cilindrosparse);
        total = valorParse * cilindrosparse;
        console.log(total);
        document.getElementById('valorCilindor10m3').value = total;

    }
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