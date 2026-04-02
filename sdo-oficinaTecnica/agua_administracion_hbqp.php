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


$sqlSuma = "SELECT SUM(consumoAgua) as total FROM agua_administracion WHERE tipohospital = 'HBQP'";
$resSuma = mysqli_query($conexion, $sqlSuma);
while ($rowSuma = $resSuma->fetch_assoc()) {
    $total = $rowSuma['total'];
}

$sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM agua_administracion WHERE tipoHospital = 'HBQP'";
$resMonto = mysqli_query($conexion, $sqlMonto);
while ($rowMonto = $resMonto->fetch_assoc()) {
    $totalMonto = $rowMonto['totalMonto'];

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Consumo de Agua HBQP</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
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
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#registrarAC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#registrarAC").show();
            });

            $(".show-verdash").click(function () {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#verDashh").hide();
                $("#ocultarDashAC").show();
                $("#ocultarDashh").show();
            });

            $(".show-ocultardash").click(function () {
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
        $(document).ready(function () {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function () {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function () {
                recargarNomDepartamento();
            });

            $('#selectnCliente').change(function () {
                agregarCliente();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function (r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };

            function agregarCliente() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-cliente/ingresarCliente.php",
                    data: "idCliente=" + $('#selectnCliente').val(),
                    success: function (r) {
                        $('#visualizarCliente').html(r);
                    }
                });

            };
        });
    </script>

    <script>

    </script>
    <script>

    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                        <h4>Administracion de Consumo de Agua HBQP</h4>

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
                                if($nomUsuario == "Cynthia Latorre Ravanales" || $nomUsuario == 'Gustavo Loira'){
                                    ?>
                                    <button class="btn bg-yellow show-realizar" id="registrarAC" name="registrarAC"><i class="fas fa-clipboard"></i>&nbsp;Registrar Consumo de Agua</button>
                                    <button class="btn bg-yellow show-listar" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i> Ver Consumo de Agua</button>
                                    <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>Ver Dashboard</button>
                                    <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>Ocultar Dashboard</button>
                                    <?php
                                }else{
                                    ?>
                                    <button class="btn bg-yellow show-listar" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i> Ver Consumo de Agua</button>
                                    <button class="btn bg-green show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>Ver Dashboard</button>
                                    <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fas fa-eye-slash"></i>Ocultar Dashboard</button>
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

                if (isset($_GET['exitoModificarConsumo'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Modificacion del Consumo de Agua Logrado con Exito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['exitoNuevoConsumo'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Consumo de Agua Agregado con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorModificarConsumo'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Modificar Consumo de Agua',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                if (isset($_GET['errorNuevoConsumo'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Consumo de Agua.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }
                ?>

                <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">
                    <?php include_once('../sdo-includes/agua_dashboard_hbqp.php')?>
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Consumo de Agua</h5>
                                <hr>
                                <br>
                                <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                <ul>
                                    <li><i style="color: #06961e; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Pagado</li>
                                </ul>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° Orden de Compra</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tipo<br>de<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N°<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Proveedor</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>de<br>Lectura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N°<br>Lectura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Consumo de Agua (Mt<sup>3</sup>)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>IVA</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Monto Total del Mes y Documento</center>
                                            </th>
                                            <?php

                                            if ($nomUsuario == "Cynthia Latorre Ravanales" || $nomUsuario == 'Gustavo Loira') {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Visualizar</center>
                                                </th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryAC = "SELECT * FROM agua_administracion WHERE tipoHospital = 'HBQP' ORDER BY fechaDocumento DESC";

                                        $resAC = mysqli_query($conexion, $queryAC);

                                        $numAC = 1;

                                        while ($rowAC = $resAC->fetch_assoc()) {

                                            $idAA = $rowAC['idAA'];
                                            $fechaDocumento = $rowAC['fechaDocumento'];

                                            $tipoDocumento = $rowAC['tipoDocumento'];
                                            $tipoDocumento = trim($tipoDocumento);

                                            $nDocumento = $rowAC['nDocumento'];
                                            $nDocumento = trim($nDocumento);

                                            $proveedor = $rowAC['proveedor'];
                                            $proveedor = trim($proveedor);

                                            $fechaLectura = $rowAC['fechaLectura'];

                                            $consumoAgua = $rowAC['consumoAgua'];

                                            $neto = $rowAC['neto'];
                                            $neto = trim($neto);

                                            $iva = $rowAC['iva'];
                                            $iva = trim($iva);

                                            $nLectura = $rowAC['nLectura'];
                                            $nLectura = trim($nLectura);

                                            $montoTotalMes = $rowAC['montoTotalMes'];
                                            $montoTotalMes = trim($montoTotalMes);

                                            $tipoHospital = $rowAC['tipoHospital'];

                                            $nCliente = $rowAC['nCliente'];
                                            $nCliente = trim($nCliente);

                                            $medidor = $rowAC['medidor'];
                                            $medidor = trim($medidor);

                                            $estado = $rowAC['estado'];
                                            $estado = trim($estado);

                                            $numeroOC = $rowAC['numeroOC'];
                                            $numeroOC = trim($numeroOC);

                                            if ($estado == 'PAGADO') {
                                                $classTR = "background-color: #06961e; color: white";
                                            } else {
                                                $classTR = '';
                                            }
                                            ?>

                                            <tr style="<?php echo $classTR; ?>">
                                                <!--<td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                   <?php
                                                $query = "SELECT * FROM cliente_agua WHERE idCliente = $nCliente AND tipoHospital = 'HBQP'";
                                                $resQuery = mysqli_query($conexion, $query);
                                                while ($rowQuery = $resQuery->fetch_assoc()) {
                                                    $nCliente1 = $rowQuery['nCliente'];
                                                }
                                                ?>

                                                </td> -->
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    if($numeroOC == 0 || empty($numeroOC)){
                                                        echo 'No registra Orden de Compra';
                                                    }else{
                                                        echo $numeroOC;
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaDocumento == 0 || $fechaDocumento == '0000-00-00 00:00:00') {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaDocumento)));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaAC;
                                                    } ?>


                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($tipoDocumento) || $tipoDocumento == '-') {
                                                        echo "No registra tipo de documento";
                                                    } else {

                                                        echo $tipoDocumento;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($nDocumento) || $nDocumento == '-') {
                                                        echo "No registra N° de Documento";
                                                    } else {
                                                        echo $nDocumento;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($proveedor == '' || !empty($proveedor)) {
                                                        echo $proveedor;
                                                    } else {
                                                        echo "No registra Medida";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($fechaLectura == '0000-00-00 00:00:00') {
                                                        echo "No registra fecha de lectura";
                                                    } else {

                                                        $registrofechaAC1 = date('d/m/Y', strtotime(str_replace('/', '-', $fechaLectura)));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaAC1;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($nLectura) || $nLectura == '-') {
                                                        echo "No registra N° de Documento";
                                                    } else {
                                                        echo number_format($nLectura, 0, ",", ".");
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($consumoAgua > -1 || !empty($consumoAgua)) {
                                                        echo number_format($consumoAgua, 0, ",", ".") . ' (Mt<sup>3</sup>)';
                                                    } else {
                                                        echo "No registra consumo de energia";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if ($neto > -1 || !empty($neto)) {
                                                        echo '$' . number_format($neto, 0, ",", ".");
                                                    } else {
                                                        echo "No registra valor neto";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (!empty($iva)) {
                                                        echo '$' . number_format($iva, 0, ",", ".");
                                                    } else {
                                                        echo "No registra iva";
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (!empty($montoTotalMes)) {
                                                        echo '$' . number_format($montoTotalMes, 0, ",", ".");
                                                    } else {
                                                        echo "No registra monto total del mes";
                                                    } ?>

                                                </td>
                                                <?php
                                                if ($nomUsuario == "Cynthia Latorre Ravanales" || $nomUsuario == 'Gustavo Loira') {
                                                    ?>

                                                    <td style="vertical-align: middle;" align="center">
                                                        <?php
                                                        if ($idAA == 0 || $idAA == '' || $idAA == ' ') {
                                                            echo 'Deshabilitado.';
                                                        } else {
                                                            ?>
                                                            <form action="admin_consumo_agua.php" method="POST">
                                                                <input name="idAA" id="idAA" type="hidden" value="<?php echo $idAA ?>">
                                                                <button type="submit" class="form-control btn-info"><i class="fas fa-eye"></i></button>
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
                                                <center>N° Orden de Compra</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Tipo<br>de<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N°<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Proveedor</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>de<br>Lectura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N°<br>Lectura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Consumo de Agua (Mt<sup>3</sup>)</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>IVA</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Monto Total del Mes y Documento</center>
                                            </th>
                                            <?php

                                            if ($nomUsuario == "Cynthia Latorre Ravanales" || $nomUsuario == 'Gustavo Loira') {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Visualizar</center>
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
                                <h5>2.- Registrar Consumo de la Agua</h5>
                                <br>

                                <br>
                                <form action="../sdo-funciones/agua_nuevoConsumo.php" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N° Orden de Compra: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nCompra" placeholder="N° Orden de Compra" type="text" name="nCompra"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Ingrese Fecha del Documento: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input class="form-control" required id="fechaDocumento" placeholder="Fecha de Carga" type="date" name="fechaDocumento"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Tipo de Documento: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <select class="form-control" id="tipoDocumento" name="tipoDocumento">
                                                    <option value="Seleccione" disabled selected>Seleccione...</option>
                                                    <option value="Boleta">Boleta</option>
                                                    <option value="Factura Electrónica">Factura Electrónica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N° del Documento: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nDocumento" placeholder="Numero de Documento" type="number" name="nDocumento"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Proveedor: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <select class="form-control" id="proveedor" name="proveedor">
                                                    <option value="ESVAL S.A.">ESVAL S.A.</option>
                                                </select>

                                                <!--<input class="form-control" id="proveedor" placeholder="Proveedor" type="text" name="proveedor"/>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Fecha de Lectura: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="fechaLectura" placeholder="Numero de Documento" type="date" name="fechaLectura"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N&uacute;mero de Lectura: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nLectura" placeholder="Numero de Lectura" type="number" name="nLectura"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Consumo de Agua (Mt<sup>3</sup>): </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="consumoAgua" placeholder="Consumo de Agua" type="number" name="consumoAgua"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Valor Neto: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorNeto" min="0" onchange="sumar();" placeholder="Valor Neto" type="number" name="valorNeto"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">IVA: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorIVA" min="0" readonly placeholder="IVA" type="number" name="valorIVA"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Monto Total del Mes y Documento: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorTotal" min="0" readonly placeholder="Valor Total" type="number" name="valorTotal"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Estado: </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <select class="form-control" id="estadoPagado" name="estadoPagado">
                                                    <option value="Seleccione" disabled selected>Seleccione...</option>
                                                    <option value="Pagado">Pagado</option>
                                                    <option value="No Pagado">No Pagado</option>
                                                </select>
                                            </div>
                                        </div>

                                        <label for="exampleInputTerreno1">Notas: <code>Para dejar constancia de alguna observación, como lecturas estimadas o no tomadas, saldos anteriores, etc.</code></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-file"></i></span>
                                            </div>
                                            <textarea id="notas" name="notas" class="form-control"></textarea>
                                        </div>


                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <?php

                                        $getMedidorAguaHBQP = getMedidorAguaHBQP();

                                        ?>
                                        <input type="hidden" name="personalAC" id="personalAC" value="<?php echo $nomUsuario?>">
                                        <input type="hidden" name="tipoHospital" id="tipoHospital" value="HBQP">
                                        <input type="hidden" name="nCliente" id="nCliente" value="<?php echo $getMedidorAguaHBQP; ?>">
                                        <input type="hidden" name="medidor" id="medidor" value="<?php echo $getMedidorAguaHBQP; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>


                                <!--HASTA AQUI-->

                                <hr>


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
    $(function () {
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


<script type="text/javascript">
    $("input[name='opciones']").change(function () {
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
<script>
    function sumar() {

        var valorNeto = document.getElementById('valorNeto').value;

        valorNeto1 = parseInt(valorNeto);

        iva = valorNeto1 * 0.19;

        ivaint = parseInt(iva);

        console.log(ivaint);

        document.getElementById('valorIVA').value = ivaint;

        montoTotal = valorNeto1 + ivaint;

        document.getElementById('valorTotal').value = montoTotal;

        //total = document.getElementById('spTotal').innerHTML;

        // Aquí valido si hay un valor previo, si no hay datos, le pongo un cero "0".
    }
</script>
</body>

</html>