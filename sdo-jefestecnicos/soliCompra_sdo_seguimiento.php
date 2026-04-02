<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {
    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];
        $idSolicitudSoporte = $datosSolicitud['idSolicitudSoporte'];
        $descripcionSoliCompra = $datosSolicitud['descripcionSoliCompra'];


        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];

        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];
        $idSolicitudSoporte = $datosSolicitud['idSolicitudSoporte'];
        $descripcionSoliCompra = $datosSolicitud['descripcionSoliCompra'];


        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
}


date_default_timezone_set('America/Santiago');
if (isset($_POST['descargaCotizacion'], $_POST['folioSoliCompraDescarga'])) {
    $descargaCotizacion = $_POST['descargaCotizacion'];
    $folioSoliCompraDescarga = trim($_POST['folioSoliCompraDescarga']);

    if ($descargaCotizacion > 0) {

        if (empty($folioSoliCompraDescarga) || $folioSoliCompraDescarga == '') {
        } else {
            if ($descargaCotizacion < 10) {
                $descargaCotizacion = "000" . $descargaCotizacion;
            } else if ($descargaCotizacion > 9 && $descargaCotizacion < 100) {
                $descargaCotizacion = "00" . $descargaCotizacion;
            } else if ($descargaCotizacion > 99 && $descargaCotizacion < 1000) {
                $descargaCotizacion = "0" . $descargaCotizacion;
            } else if ($descargaCotizacion > 999) {
                $descargaCotizacion = $descargaCotizacion;
            } else {
                $descargaCotizacion = $descargaCotizacion;
            }

            $folioSoliCompra2 = "SC-SDO-" . $descargaCotizacion;

            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir = '../sdo-solicCompra/' . $folioSoliCompra2 . "/";
            $archivoZip = "Cotizaciones_" . $folioSoliCompraDescarga . ".zip";

            if ($zip->open($archivoZip, ZipArchive::CREATE) === true) {
                agregar_zipSC($dir, $zip);
                $zip->close();

                if (file_exists($archivoZip)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;

                    $descargaConfirma .= "Y";
                } else {

                    $descargaConfirma .= "N";
                }
            }
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seguimiento de la solicitud</title>

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

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Seguimiento de la solicitud</h4>
                    </div>
                    <?php
                        if(isset($_GET['exitoIngresoSolicitudCompra'])){
                            ?>
                            <script>
                                Swal.fire({
                                    title: "Exito!",
                                    text: "La solicitud de compra fue ingresada con exito!",
                                    icon: "success"
                                });
                            </script>
                            <?php
                        }
                        if(isset($_GET['errorIngresoSolicitudCompra'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Algo ha salido mal. Por favor reintente!",
                                });
                            </script>
                            <?php
                        }
                    ?>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- MENSAJES DE ACCIONES -->
        <?php
        //TRUE
        if (isset($_GET['errorNumSDA'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al Obtener Numero SDA',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['exitoNumInterno'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Número Interno Asignado Correctamente',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['errorNumInterno'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error Al Asignar Número Interno SDA',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['exitoEntrega'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Exito al cambiar estado de la solicitud "Entregado a la SDA" ',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['errorEntrega'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cambiar estado de la solicitud "Entregado a la SDA',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['exitoEditEstado'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Exito cambio de estado de la SDA" ',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['errorEditEstado'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en el cambio de estado de la SDA',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['exitoEntregaAbastecimiento'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Exito al cambiar el estado de solicitud a "Derivado a Abastecimiento" ',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['errorEntregaAbastecimiento'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cambiar el estado de solicitud a "Derivado a Abastecimiento"',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['exitoOrdenCompra'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Exito al añadir ordenes de compra" ',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['errorOrdenCompra'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al añadir ordenes de compra"',
                })
            </script>
            <?php
        }
        //TRUE
        if (isset($_GET['ExitoEditOrdenCompra'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: 'Exito al editar ordenes de compra" ',
                })
            </script>
            <?php
        }
        //FALSE
        if (isset($_GET['ErrorEditOrdenCompra'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al editar ordenes de compra"',
                })
            </script>
            <?php
        }
        ?>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="soliCompra_sdo_administracion.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>


                            </div>
                            <div style="font-size:85%" class="card-body">
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-9">

                                        <h5 style="font-size:150%"><b>Visualizando Solicitud: </b><?php echo $folioSoliCompra; ?></h5>
                                        <h5 style="font-size:150%"><b>Funcionario Solicitante: </b><?php echo $personalSoliCompra; ?></h5>
                                        <h5 style="font-size:150%"><b>Unidad Solicitante: </b><?php echo $unidadSoliCompra; ?></h5>
                                        <h5 style="font-size:150%"><b>Correo Solicitante: </b><?php echo $emailSoliCompra; ?></h5>
                                        <h5 style="font-size:150%"><b>Estado Solicitud: </b><?php echo $estadoSoliCompra; ?></h5>
                                        <h5 style="font-size:150%"><b>Descripci&oacute;n Solicitud: </b><?php

                                            if (!empty($descripcionSoliCompra)) {
                                                echo $descripcionSoliCompra;
                                            } else {
                                                echo 'Sin Descripción.';
                                            }

                                            ?>
                                        </h5>
                                        <h5 style="font-size:150%"><b>Ticket Asociado: </b>
                                            <?php

                                            if ($idSolicitudSoporte > 0) {
                                                echo $idSolicitudSoporte;
                                            } else {
                                                echo 'No existe Ticket Asociado.';
                                            }

                                            ?>
                                        </h5>

                                        <br>
                                    </div>
                                    <div style="font-size:85%" class="col-md-3">
                                        <?php
                                        if ($idSoliCompra > 0 ) {


                                            $ipPermitida = '10.69.250.151';

                                            $ipCliente = $_SERVER['REMOTE_ADDR'];

                                            if($ipCliente == $ipPermitida){
                                                ?>

                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-SoliCompra-SDO-MPDF.php" method="post">

                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Solicitud de Compra</button>

                                                </form>

                                                <br>
                                                <?php
                                            }else{
                                                ?>

                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-SoliCompra-SDO.php" method="post">

                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Solicitud de Compra</button>

                                                </form>

                                                <br>
                                                <?php
                                            }
                                        }

                                        if ($idSoliCompra > 0) {
                                            $contarCotizacionesSoliCompraSDO = contarCotizacionesSoliCompraSDO($idSoliCompra);

                                            if ($contarCotizacionesSoliCompraSDO > 0) {

//                                                $userUsuarioMinus = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');
//
//                                                if ($userUsuarioMinus == 'ycasimis') {
                                                ?>

                                                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modalListDesc">
                                                    <i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos
                                                </button>


                                                <div class="modal fade" id="modalListDesc" style="font-size: 12px;">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h4 class="modal-title">
                                                                    Listado de Archivos Adjuntos
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <form action="soliCompra_sdo_seguimiento.php" style="margin: 0; text-align: center;" class="" method="post">

                                                                    <input type="hidden" name="descargaCotizacion" value="<?php echo $idSoliCompra; ?>">
                                                                    <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="<?php echo $folioSoliCompra; ?>">
                                                                    <button type="submit" style="float: right;" id="inputMozi" class="btn btn-success"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Todo</button>

                                                                </form>

                                                                <br>
                                                                &nbsp;
                                                                <br>
                                                                &nbsp;

                                                                <div class="table table-responsive">
                                                                    <table class="table table-bordered text-center">

                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                N°
                                                                            </th>
                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                Nombre Archivo
                                                                            </th>
                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                Descarga
                                                                            </th>
                                                                        </tr>
                                                                        </thead>

                                                                        <tbody>


                                                                        <?php
                                                                        $queryListDesc = "SELECT * FROM cotizacion_solicompra_sdo WHERE idSoliCompra = " . $idSoliCompra . " ORDER BY nomCotizacionSC ASC";
                                                                        $resListDesc = mysqli_query($conexion, $queryListDesc);

                                                                        $numListDesc = 1;

                                                                        while ($rowListDesc = $resListDesc->fetch_assoc()) {
                                                                            ?>

                                                                            <tr>

                                                                                <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                    <?php

                                                                                    echo $numListDesc;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" nowrap="nowrap">

                                                                                    <?php

                                                                                    $nomCotizacionSC = $rowListDesc['nomCotizacionSC'];
                                                                                    $nomCotizacionSC = mb_convert_case($nomCotizacionSC, MB_CASE_UPPER, 'UTF-8');

                                                                                    echo $nomCotizacionSC;
                                                                                    ?>

                                                                                </td>
                                                                                <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                    <?php

                                                                                    $rutaCotizacionSC = $rowListDesc['rutaCotizacionSC'];
                                                                                    $rutaCotizacionSC = trim($rutaCotizacionSC);

                                                                                    if (empty($rutaCotizacionSC) || $rutaCotizacionSC == '') {
                                                                                        echo 'Error al Obtener Datos';
                                                                                    } else {
                                                                                        ?>
                                                                                        <a href="<?php echo $rutaCotizacionSC; ?>" class="btn btn-success" download><i class="fas fa-download"></i>&nbsp;&nbsp;Descargar Archivo</a>
                                                                                        <?php
                                                                                    }


                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            $numListDesc++;
                                                                        }
                                                                        ?>

                                                                        </tbody>

                                                                        <tfoot class="bg-secondary">
                                                                        <tr>

                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                N°
                                                                            </th>
                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                Nombre Archivo
                                                                            </th>
                                                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                Descarga
                                                                            </th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-right">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <br>
                                                <?php
//                                                } else {
//                                                    ?>
                                                <!---->
                                                <!--                                                    <form action="soliCompra_sdo_seguimiento.php" style="margin: 0; text-align: center;" class="" method="post">-->
                                                <!---->
                                                <!--                                                        <input type="hidden" name="descargaCotizacion" value="--><?php //echo $idSoliCompra; ?><!--">-->
                                                <!--                                                        <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="--><?php //echo $folioSoliCompra; ?><!--">-->
                                                <!--                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos</button>-->
                                                <!---->
                                                <!--                                                    </form>-->
                                                <!---->
                                                <!--                                                    <br>-->
                                                <!--                                                    --><?php
//                                                }

                                            } else {
                                                ?>
                                                <button type="submit" id="inputMozi" disabled class="btn btn-block btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos</button>
                                                <br>
                                                <?php
                                            }
                                        }

                                        if ($idSoliCompra > 0) {

                                            $estadoSoliCompra2 = mb_convert_case($estadoSoliCompra, MB_CASE_UPPER, 'UTF-8');
                                            if ($estadoSoliCompra2 == "SOLICITUD ANULADA") {
                                                ?>
                                                <form style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarAnularSC_sdo.php" target="_blank" method="post">
                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><b> <i class="fa fa-eye"></i>&nbsp;&nbsp;Ver Razones</b></button>
                                                </form>
                                                <br>
                                                <?php
                                            } else {
                                                ?>
                                                <form style="margin: 0; text-align: center;" class="" action="soliCompra_anularSC_sdo.php" method="post">
                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-danger"><b> <i class="fa fa-times"></i>&nbsp;&nbsp;Anular Solicitud</b></button>
                                                </form>
                                                <br>
                                                <?php
                                            }
                                        }

                                        if ($idSoliCompra > 0) {
                                            ?>

                                            <button type="button" class="btn btn-block bg-orange" data-toggle="modal" data-target="#modal-xl">
                                                <i class="fas fa-comments"></i>&nbsp;&nbsp;Ver Observaciones
                                            </button>


                                            <div class="modal fade" id="modal-xl">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h4 class="modal-title">
                                                                Observaciones de la solicitud
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table table-responsive">
                                                                <table class="table table-bordered text-center">

                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Fecha
                                                                        </th>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Creado Por
                                                                        </th>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Detalles
                                                                        </th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody>

                                                                    <?php
                                                                    $queryObs = "SELECT * FROM solicitudes_compra_sdo_observaciones WHERE idSoliCompra = " . $idSoliCompra;
                                                                    $resObs = mysqli_query($conexion, $queryObs);

                                                                    while ($rowObs = $resObs->fetch_assoc()) {
                                                                        ?>

                                                                        <tr>

                                                                            <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                <?php


                                                                                $inicioFecha = date('d/m/Y', (strtotime(str_replace('/', '-', $rowObs['fechaObservacion']))));
                                                                                $inicioHora = date('H:i', strtotime($rowObs['fechaObservacion']));

                                                                                echo $inicioFecha . '<br>' . $inicioHora;


                                                                                ?>
                                                                            </td>
                                                                            <td style="vertical-align:middle;" nowrap="nowrap">

                                                                                <?php

                                                                                echo $rowObs['creadorObservacion'];

                                                                                ?>

                                                                            </td>
                                                                            <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                <?php

                                                                                echo $rowObs['detalleObservacion'];


                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    </tbody>

                                                                    <tfoot class="bg-secondary">
                                                                    <tr>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Fecha
                                                                        </th>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Creado Por
                                                                        </th>
                                                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                            Detalles
                                                                        </th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-right">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->

                                            <br>
                                            <?php
                                        }
                                        ?>

                                            <button type="button" class="btn btn-block bg-info" data-toggle="modal" data-target="#modal-orden">
                                                <i class="fas fa-file"></i>&nbsp;&nbsp;Agregar Solicitud a Orden de Trabajo
                                            </button>
                                            <div class="modal fade" id="modal-orden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">Asignar Orden de Trabajo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/ingresar-soli-compra-1.php" method="POST">
                                                            <div class="modal-body">
                                                                <label style="font-size: 15px" class="w-auto">Elija la orden de trabajo</label>

                                                                <!-- Folio de la solicitud de compra -->
                                                                <input type="hidden" id="folioSoli" name="folioSoli" value="<?php echo $folioSoliCompra ?>">

                                                                <!-- Campo visible para seleccionar la orden -->
                                                                <label for="selectOrden"></label>
                                                                <input list="ordenes" class="form-control" id="selectOrden" name="selectOrden" placeholder="--Seleccione--">

                                                                <!-- Campo oculto para enviar el idTG real -->
                                                                <input type="hidden" id="idTG" name="idTG">

                                                                <!-- Lista de opciones -->
                                                                <datalist id="ordenes">
                                                                    <option value="" selected disabled>--Seleccione--</option>
                                                                    <?php
                                                                    $sqlOrden = "SELECT * FROM trabajos_general WHERE departamento = $idDepartamento AND estadoTG != 'Trabajo Terminado' ORDER BY idTG DESC";
                                                                    $resOrden = mysqli_query($conexion, $sqlOrden);

                                                                    while ($rowOrden = $resOrden->fetch_assoc()) {
                                                                        $idOrden = $rowOrden['idTG'];
                                                                        $folioTG = $rowOrden['folioTG'];
                                                                        $tecnico1 = $rowOrden['tecnico1'];
                                                                        $turnoTG = $rowOrden['turnoTG'];
                                                                        $descripcion = "$folioTG / $tecnico1 / $turnoTG";
                                                                        ?>
                                                                        <option data-id="<?php echo $idOrden ?>" value="<?php echo $descripcion ?>"></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </datalist>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                            </div>
                                                        </form>

                                                        <!-- JavaScript para capturar el data-id de la opción seleccionada -->
                                                        <script>
                                                            document.getElementById('selectOrden').addEventListener('input', function () {
                                                                const input = this;
                                                                const datalist = document.getElementById('ordenes');
                                                                const options = datalist.options;
                                                                let found = false;

                                                                for (let i = 0; i < options.length; i++) {
                                                                    if (options[i].value === input.value) {
                                                                        const dataId = options[i].getAttribute('data-id');
                                                                        document.getElementById('idTG').value = dataId ?? '';
                                                                        found = true;
                                                                        break;
                                                                    }
                                                                }

                                                                if (!found) {
                                                                    document.getElementById('idTG').value = '';
                                                                }
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <br>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <h4>¿Como va mi solicitud?</h4>
                                <br>
                                <br>

                                <?php

                                if ($estadoSoliCompra == 'Solicitada a Secretaria SDO' || $estadoSoliCompra == 'Revisada por Secretaría SDO') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-sdo.png" alt="Linea mesa de ayuda">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-sda.png" alt="Linea SDA">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'Derivado a Abastecimiento') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-abastecimiento.png" alt="Linea Abastecimiento">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'En Pañol') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-panol.png" alt="Linea Pañol">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'Completada') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-completada.png" alt="Linea Pañol">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'Solicitud Anulada') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-sdo-anulada.png" alt="Linea Solicitud Anulada">
                                    </div>
                                    <?php
                                } else if ($estadoSoliCompra == 'Rechazado por la S.D.A') {
                                    ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-sda-rechazada.png" alt="Linea Rechazado sda">
                                    </div>
                                    <?php
                                }

                                ?>


                                <br>

                                <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Fecha Actualización
                                        </th>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Tipo de Actualización
                                        </th>

                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Observación
                                        </th>


                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $query = "SELECT * FROM seguimiento_sc_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
                                    $res = mysqli_query($conexion, $query);


                                    while ($row = $res->fetch_assoc()) {
                                        ?>

                                        <tr>
                                            <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                <?php


                                                $inicioFecha = date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaActualizacion']))));
                                                $inicioHora = date('H:i', strtotime($row['fechaActualizacion']));

                                                echo $inicioFecha . '<br>' . $inicioHora;
                                                ?>
                                            </td>
                                            <td style="vertical-align:middle;" nowrap="nowrap">
                                                <?php
                                                $cantidadArticuloSC = mb_strtoupper($row['tipoActualizacion']);
                                                echo $cantidadArticuloSC;

                                                ?>
                                            </td>
                                            <td style="vertical-align:middle;" nowrap="nowrap">
                                                <?php
                                                $nomArticuloSC = mb_strtoupper($row['observacion']);
                                                echo nl2br($nomArticuloSC);

                                                ?>
                                            </td>
                                        </tr>

                                        <?php

                                    }

                                    ?>
                                    </tbody>

                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Fecha Actualización
                                        </th>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Tipo de Actualización
                                        </th>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            Observación
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

<script src="js/dark-mode.js"></script>
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
            "pageLength": 10,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({


                        header: function (row) {
                            var data = row.data();
                            return 'Informaci&oacute;n de Solicitud Telefonica: ';
                        },
                        autoWidth: true,
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table col-12 text-justify align-items-center'
                    })
                }
            },
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
<script type="text/javascript">
    $(document).ready(function () {
        //group add limit
        var maxGroup = "10";

        //add more fields group
        $(".addMore").click(function () {
            if ($('body').find('.fieldGroup').length < maxGroup) {
                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);

            } else {
                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
            }
        });

        //remove fields group
        $("body").on("click", ".remove", function () {
            $(this).parents(".fieldGroup").remove();
        });
    });
</script>
<script>
    estadoSDA = document.getElementById('estadoSDA');
    motivo = document.getElementById('motivo');
    motivoText = document.getElementById('motivoText');

    estadoSDA.addEventListener('change', function (event) {

        valorEstado = estadoSDA.value;

        if (valorEstado == 'denegada') {
            motivo.style.display = 'block';
        } else if (valorEstado == '' || valorEstado == 'autorizada') {
            motivo.style.display = 'none';
            motivoText.removeAttribute("required");
        }


    })
</script>
</body>

</html>

