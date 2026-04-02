<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idSoliCompra1'])){
    $idSoliCompra1 = $_POST['idSoliCompra1'];
    if($idSoliCompra1 > 0){
        ini_set("memory_limit", "5256M");
        $zip = new ZipArchive();
        $dir = "../sdo-archivo-orden-de-compra-panol/id-$idSoliCompra1".'/';
        $archivoZip = "Orden de Compra-" . $idSoliCompra1 . ".zip";
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

if (isset($_POST['rutaArchivoRE'])) {
    $file = $_POST['rutaArchivoRE'];
    $nomArchivo = $_POST['nomArchivoRE'];
    if (is_file($file)) {
        $filename = $nomArchivo; // el nombre con el que se descargará, puede ser diferente al original
        /*header("Content-Type: application/octet-stream");*/
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($file);
    } else {
        die("Error: no se encontró el archivo '$file'");
    }
}




date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {
    $idSoliCompra = trim($_POST['idSoliCompra']);

    $querySeguimiento = "SELECT * FROM seguimiento_sc_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
    $resSeguimiento = mysqli_query($conexion, $querySeguimiento);

    while ($rowSeguimiento = $resSeguimiento->fetch_assoc()) {
        $datos = [];
        $datos['tipoActualizacion'] = $rowSeguimiento['tipoActualizacion'];
        $tipoActualizacion = $datos['tipoActualizacion'];

        $ruta = '../sdo-funciones/' . $tipoActualizacion;
    }

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
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

        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);


    $querySeguimiento = "SELECT * FROM seguimiento_sc_sdo WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
    $resSeguimiento = mysqli_query($conexion, $querySeguimiento);

    while ($rowSeguimiento = $resSeguimiento->fetch_assoc()) {
        $datos = [];
        $datos['observacion'] = $rowSeguimiento['observacion'];
        $observacion = $datos['observacion'];

        $ruta = '../sdo-funciones/' . $observacion;
    }

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
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

        $fechaCreacion = strtotime($fechaSoliCompra);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
}
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
    <!-- Copiar dentro del tag HEAD -->
    <script LANGUAGE="JavaScript">
        var cuenta = 0;

        function enviado() {
            if (cuenta == 0) {
                cuenta++;
                return true;
            } else {
                alert("El check-list está siendo enviado, espere porfavor! .");
                return false;
            }
        }
        // 
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/bodega-panol-sidebar.php';
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

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Mensajes -->
            <?php

            //ERROR

            if (isset($_GET['noIguales'])) {
            ?>
                <script>
                    Swal.fire(
                        'Oh no, problemas!',
                        'Debe completar ambos campos, seleccionar el checbox e ingresar su cantidad correspondiente',
                        'error'
                    )
                </script>

            <?php
            }

            //EXITO

            if (isset($_GET['exitoOrdena'])) {
            ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se guardo el checkList y se envio el correo correctamente',
                        'success'
                    )
                </script>

            <?php
            }

            if (isset($_GET['noOrdena'])) {
            ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'Debe seleccionar al menos un producto, antes de guardar',
                        'error'
                    )
                </script>

            <?php
            }


            if (isset($_GET['exitoRecibido'])) {
            ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se cambio el estado a recibido por pañol correctamente',
                        'success'
                    )
                </script>

            <?php
            }

            if (isset($_GET['exitoOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se Ingreso el detalle de orden de compra correctamente',
                        'success'
                    )
                </script>

            <?php
            }

            if (isset($_GET['errorOrdenCompra'])) {
            ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'Problemas al ingresar el detalle de orden de compra',
                        'error'
                    )
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

                                            <h5 style="font-size:150%"><b>Visualizando Solicitud:
                                                </b><?php echo $folioSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Funcionario Solicitante:
                                                </b><?php echo $personalSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Unidad Solicitante:
                                                </b><?php echo $unidadSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Correo Solicitante:
                                                </b><?php echo $emailSoliCompra; ?></h5>
                                            <h5 style="font-size:150%"><b>Estado Solicitud:
                                                </b><?php echo $estadoSoliCompra; ?></h5>

                                            <br>
                                        </div>
                                        <div style="font-size:85%;" class="col-md-3">
                                            <div class="col-md-12">
                                                <?php
                                                if ($idSoliCompra > 0) {

                                                    ?>

                                                    <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-SoliCompra-SDO.php" method="post">

                                                        <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Solicitud de Compra</button>

                                                    </form>

                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                                <?php
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
                                                ?>
                                                <?php
                                                if ($estadoSoliCompra == 'Solicitada a Secretaria SDO' || $estadoSoliCompra == 'Revisada por Secretaría SDO') {
                                                    ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregaSDA" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn disabled bg-primary btn-block sda" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                    <?php
                                                }
                                                ?>
                                                <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                    <div class="modal fade" id="entregaSDA" name="entregaSDA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Entregado a la SDA</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>


                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Fecha
                                                                            Entrega: <code>*</code></label>
                                                                        <input type="datetime-local" class="form-control" name="fechaEntrega" required id="fechaEntrega">
                                                                    </div>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                    <input type="hidden" value="<?php echo "entregadoSDA" ?>" name="opcion">
                                                                    <button type="submit" class="btn btn-primary">Guardar</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="col-md-12">

                                                <?php

                                                if ($estadoSoliCompra == 'Recibido por pañol' || $estadoSoliCompra == 'Solicitud Completada') {
                                                ?>
                                                    <button class="btn bg-primary   btn-block sda" disabled style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Recibido por Pañol</b></button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregapanol" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Recibido por Pañol</b></button>
                                                <?php
                                                }
                                                ?>


                                                <!-- Modal -->
                                                <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol.php" method="POST">
                                                    <div class="modal fade " id="entregapanol" name="entregapanol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Recibido por Bodega Pañol</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>


                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Fecha
                                                                            Recibido: <code>*</code></label>
                                                                        <input type="datetime-local" class="form-control" name="fechaEntrega" required id="fechaEntrega">
                                                                    </div>


                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                    <input type="hidden" value="<?php echo "recibidoPanol" ?>" name="opcion">
                                                                    <button type="submit" class="btn btn-primary">Guardar</button>

                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div><!-- Entregado a la SDA -->
                                            <br>
                                            <br>
                                            <br>
                                            <div class="col-md-12">

                                                <?php
                                                if ($estadoSoliCompra == 'Recibido por pañol') {
                                                ?>
                                                    <button class="btn bg-danger btn-block sda" data-toggle="modal" data-target="#ordenCompra" style="float:right;"><i class="fas fa-snowplow"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Orden de compra</b></button>
                                                    <button class="btn bg-success btn-block completada" id="<?php echo $idSoliCompra ?>" style="float:right;"><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Completar Solicitud</b></button>
                                                    <br>
                                                <?php
                                                }

                                                ?>
                                                <button class="btn bg-info btn-block " data-toggle="modal" data-target="#valor" style="float:right; margin-top: 10px"><i class="far fa-money-bill-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Valor orden de compra</b></button>
                                                <br>
                                                <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                    <div class="modal fade" id="valor" tabindex="-1" aria-labelledby="valor" aria-hidden="true">
                                                        <div class="modal-dialog  modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-info">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Orden compra, fecha y valor</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Orden de Compra:</label>
                                                                        <input type="text" required class="form-control form-control-sm" id="valorIdOC" name="ordenCompra" aria-describedby="emailHelp">
                                                                        <small id="emailHelp" class="form-text text-muted">Escriba la orden de compra respectiva.</small>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Fecha Envio:</label>
                                                                        <input type="date" required class="form-control form-control-sm" id="fechaEnvio" name="fechaEnvio" aria-describedby="emailHelp">
                                                                        <small id="emailHelp" class="form-text text-muted">Fecha envio de la orden de compra al proveedor.</small>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">Valor:</label>
                                                                        <input type="number" required class="form-control form-control-sm" min="0" id="valorIdOC" name="valorOrdenCompra" aria-describedby="emailHelp">
                                                                        <small id="emailHelp" class="form-text text-muted">Escriba el valor de la orden de compra respectiva.</small>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Subir Orden de Compra</label>
                                                                        <input type="file" class="form-control form-control-sm" id="archivoAdjunto" name="archivoAdjunto[]" multiple aria-describedby="emailHelp">
                                                                        <small id="emailHelp" class="form-text text-muted">Suba el archivo de la orden de compra</small>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                                                    <hr>
                                                                    <br>
                                                                    <br>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Orden de Compra</th>
                                                                                <th scope="col">Fecha Env. Proveedor</th>
                                                                                <th scope="col">Costo Total</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <?php

                                                                            $sqlOrdenCompra = "SELECT * FROM detalle_ordencompra WHERE idSoliCompra = $idSoliCompra";
                                                                            $resOrdenCompra = mysqli_query($conexion, $sqlOrdenCompra);


                                                                            while ($rowOrdenCompra = $resOrdenCompra->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['codOrdenCompra'] = $rowOrdenCompra['codOrdenCompra'];
                                                                                $datos['fechaEnvioProv'] = $rowOrdenCompra['fechaEnvioProv'];
                                                                                $datos['valorTotal'] = $rowOrdenCompra['valorTotal'];

                                                                                $codOrdenCompra = $datos['codOrdenCompra'];
                                                                                $fechaEnvioProv = $datos['fechaEnvioProv'];
                                                                                $valorTotal = $datos['valorTotal'];

                                                                                $valorTotal = moneda_chilena($valorTotal);
                                                                                $fechaEnvioProv = date('d-m-Y', strtotime($rowOrdenCompra['fechaEnvioProv']));

                                                                            ?>

                                                                                <tr>
                                                                                    <td style="font-weight:bold;color: green;font-size: 15px"><?php echo strtoupper($codOrdenCompra) ?></td>
                                                                                    <td style="font-weight:bold;color: green;font-size: 15px"><?php echo strtoupper($fechaEnvioProv) ?></td>
                                                                                    <td style="font-weight:bold;color: green;font-size: 15px"><?php echo $valorTotal ?></td>
                                                                                </tr>

                                                                            <?php
                                                                            }

                                                                            ?>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                                    <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                    <input type="hidden" value="<?php echo "valorOrdenCompra" ?>" name="opcion">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                                <br>
                                                <br>
                                                <br>
                                                <?php
                                                   $buscararchivocoti = buscararchivocoti($idSoliCompra);
                                                   if($buscararchivocoti > 0){
                                                       ?>
                                                       <form action="soliCompra_sdo_seguimiento.php" method="POST">
                                                           <input type="hidden" id="idSoliCompra1" name="idSoliCompra1" value="<?php echo $idSoliCompra?>">
                                                           <button type="submit" class="btn btn-block bg-success"><i class="fas fa-download"></i> Descargar Orden de Compra</button>
                                                       </form>
                                                       <?php
                                                   }
                                                ?>


                                                <form action="soliCompra_sdo_seguimiento.php" method="POST">

                                                    <?php

                                                    $sqlSelect = "SELECT * FROM archivos_solicompra_material_resumen WHERE idSoliCompra = '$idSoliCompra'";
                                                    $resSelect = mysqli_query($conexion, $sqlSelect);

                                                    while ($rowSelect = $resSelect->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['nomArchivoRE'] = $rowSelect['nomArchivoRE'];
                                                        $datos['rutaArchivoRE'] = $rowSelect['rutaArchivoRE'];
                                                        $datos['extArchivo'] = $rowSelect['extArchivo'];

                                                        $nomArchivoRE = $datos['nomArchivoRE'];
                                                        $rutaArchivoRE = $datos['rutaArchivoRE'];
                                                        $extArchivo = $datos['extArchivo'];

                                                    ?>
                                                        <input type="hidden" name="rutaArchivoRE" value="<?php echo $rutaArchivoRE ?>">
                                                        <input type="hidden" name="nomArchivoRE" value="<?php echo $rutaArchivoRE ?>">
                                                        <button class="btn btn-warning btn-block" style="float:right; margin-top: 10px"><i class="fas fa-download"></i> <b><?php echo $nomArchivoRE ?></b> </button>
                                                    <?php
                                                    }
                                                    ?>
                                                </form>
                                                <!-- Modal -->
                                                <div class="modal fade" id="ordenCompra" name="ordenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Orden de compras</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol.php" onsubmit="return enviado()" id="form" method="POST" enctype="multipart/form-data">

                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Orden
                                                                            de Compra: <code>*</code></label>
                                                                        <input type="text" class="form-control" style="text-transform: uppercase;" name="ordenCompra" required id="ordenCompra">
                                                                    </div>

                                                                    <div class="table-responsive">
                                                                        <table id="tabla_subEquipo" class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col">Articulo</th>
                                                                                    <th scope="col">Cantidad Solicitada/Faltante</th>
                                                                                    <th scope="col">Medida</th>
                                                                                    <th scope="col">Seleccionar</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                <?php

                                                                                $sqlEquipo = "SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = $idSoliCompra ";
                                                                                $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                                                                while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                                    $datos = [];
                                                                                    $datos['idArticuloSC'] = $rowEquipo['idArticuloSC'];
                                                                                    $datos['nomArticuloSC'] = $rowEquipo['nomArticuloSC'];
                                                                                    $datos['cantidadArticuloSC'] = $rowEquipo['cantidadArticuloSC'];
                                                                                    $datos['medidaSC'] = $rowEquipo['medidaSC'];
                                                                                    $datos['cantidadLlego'] = $rowEquipo['cantidadLlego'];
                                                                                    $datos['cantidadFaltante'] = $rowEquipo['cantidadFaltante'];

                                                                                    $idArticuloSC  = $datos['idArticuloSC'];
                                                                                    $nomArticuloSC = $datos['nomArticuloSC'];
                                                                                    $cantidadArticuloSC = $datos['cantidadArticuloSC'];
                                                                                    $medidaSC = $datos['medidaSC'];
                                                                                    $cantidadLlego = $datos['cantidadLlego'];
                                                                                    $cantidadFaltante = $datos['cantidadFaltante'];
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $nomArticuloSC ?> </td>
                                                                                        <td>
                                                                                            <?php

                                                                                            if ($cantidadFaltante > 0) {
                                                                                                $cantidadFalta = $cantidadFaltante;
                                                                                                echo  $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                                            } else {
                                                                                                $cantidadFalta = $cantidadArticuloSC;
                                                                                                echo $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                                            }

                                                                                            ?>
                                                                                        </td>
                                                                                        <td><?php echo mb_convert_case($medidaSC, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                                        <td>
                                                                                            <?php
                                                                                            if ($cantidadLlego == $cantidadArticuloSC) {
                                                                                            ?>
                                                                                                <span class="badge badge-success" style="font-size:15px">Todo Entregado <i class="fas fa-check"></i></span>
                                                                                            <?php
                                                                                            } else {
                                                                                            ?>
                                                                                                <div class="form-group" id="padreForm">
                                                                                                    <input type="hidden" name="opcion" value="ordenesCompra">
                                                                                                    <input type="hidden" name="id" value="<?php echo $idSoliCompra ?>">
                                                                                                    <label for="cantidadLlego" style="display: block" class="label-form">Cantidad:</label>
                                                                                                    <input type="checkbox" class="checkboxs" style="display: inline;" id="checks_'. $idArticuloSC. '" name="checks[]" value="<?php echo $idArticuloSC ?>">
                                                                                                    <br>
                                                                                                    <input type="hidden" class="cantidad" min='1' max="<?php echo $cantidadFalta ?>" id="cantidadLlego_'.$idArticuloSC.'" style="display: inline; margin-right: 10px;" name="cantidadLlego[]">
                                                                                                </div>
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
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" form="form" id="guardar" disabled class="btn btn-primary">Guardar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A' || $estadoSoliCompra == 'Derivado a Abastecimiento') {
                                                ?>
                                                <div class="col-md-12">

                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {

                                                        if (!empty($numInternoSDA)) {
                                                            ?>
                                                            <button type="button" data-toggle="modal" data-target="#editarAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" data-toggle="modal" data-target="#modalAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                            <?php
                                                        }
                                                    } else {


                                                        if (!empty($numInternoSDA)) {
                                                            ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalAsignarNumero" name="modalAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Asignar Número Interno SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">N°
                                                                                Interno: <code>*</code></label>
                                                                            <input type="text" class="form-control " id="numInterno" name="numInterno" placeholder="Escriba Numero Interno" required>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "asignarNumeroInternoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="editarAsignarNumero" name="editarAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-info">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Editar Número Interno SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">N°
                                                                                Interno: <code>*</code></label>
                                                                            <input type="text" class="form-control " id="numInterno" name="numInterno" value="<?php echo $numInternoSDA ?>" placeholder="Escriba Numero Interno" required>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar Modificación</button>
                                                                        <input type="hidden" value="<?php echo "editarAsignarNumeroInternoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>



                                                </div><!-- Número interno SDA -->
                                                <br>
                                                <br>
                                                <br>
                                                <div class="col-md-12">

                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalCambiaEstado" id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" disabled id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                        <?php
                                                    }

                                                    ?>


                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalCambiaEstado" name="modalCambiaEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Cambiar Estado SDA</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>


                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Estado
                                                                                SDA: <code>*</code></label>
                                                                            <select name="estadoSDA" id="estadoSDA" class="form-control" required>
                                                                                <option value="" selected disabled>-- Seleccione --</option>
                                                                                <option value="autorizada">Autorizada</option>
                                                                                <option value="denegada">Denegada</option>
                                                                            </select>
                                                                        </div>
                                                                        <div style="text-align:left;display: none;" class="form-group" id="motivo">
                                                                            <label for="exampleInputBorder">Motivo/
                                                                                Razón: <code>*</code></label>
                                                                            <br>
                                                                            <textarea name="motivo" id="motivoText" class="form-control" required></textarea>
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "cambiaEstadoSDA" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- Estado SDA -->
                                                <br>
                                                <br>
                                                <br>
                                                <div class="col-md-12">
                                                    <?php
                                                    if ($estadoSoliCompra == 'Autorizado por la S.D.A') {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalAbastecimiento" id="inputMozi" class="btn bg-danger btn-block" style="float:right;"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a Abastecimiento</b> </button>
                                                        <?php
                                                    }
                                                    ?>
                                                    <!-- Modal -->
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado.php" method="POST">
                                                        <div class="modal fade" id="modalAbastecimiento" name="modalAbastecimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <b>Entregado a Abastecimiento</b>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="exampleInputBorder">Fecha recibido
                                                                                Abastecimiento: <code>*</code></label>
                                                                            <input type="datetime-local" class="form-control" name="fechaEntrega" id="fechaEntrega">

                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                                        <input type="hidden" value="<?php echo "entregaAbastecimiento" ?>" name="opcion">

                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- Estado SDA -->
                                                <?php
                                            }
                                            ?>
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
                                } else if ($estadoSoliCompra == 'Recibido por pañol') {
                                ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-panol.png" alt="Linea Pañol">
                                    </div>
                                <?php
                                } else if ($estadoSoliCompra == 'Solicitud Completada') {
                                ?>
                                    <div class="row justify-content-center">
                                        <img src="img/linea-completada.png" alt="Linea Completada">
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



                                                    $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaActualizacion']))));
                                                    $inicioHora = date('H:i', strtotime($row['fechaActualizacion']));

                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap">
                                                    <?php
                                                    $tipoActualizacion = mb_strtoupper($row['tipoActualizacion']);
                                                    echo $tipoActualizacion;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap">
                                                    <?php
                                                    $observacion = mb_strtoupper($row['observacion']);
                                                    $ruta = '../sdo-funciones/' . $observacion;
                                                    echo $observacion;




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
        $(function() {
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


                            header: function(row) {
                                var data = row.data();
                                return 'Informaci&oacute;n de Solicitud de Compra: ';
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
        $(document).ready(function() {
            //group add limit
            var maxGroup = "10";

            //add more fields group
            $(".addMore").click(function() {
                if ($('body').find('.fieldGroup').length < maxGroup) {
                    var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                    $('body').find('.fieldGroup:last').after(fieldHTML);

                } else {
                    alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
                }
            });

            //remove fields group
            $("body").on("click", ".remove", function() {
                $(this).parents(".fieldGroup").remove();
            });
        });
    </script>

    <script src="js/dark-mode.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('[name="checks[]"]').click(function() {

                var arr = $('[name="checks[]"]:checked').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');

                console.log(str);

                $('#arrayProductos').val(str);

            });

            $('[name="cantidadLlego[]"]').change(function() {

                var arr = $('[name="cantidadLlego[]"]').map(function() {
                    return this.value;
                }).get();

                var str = arr.join(',');

                console.log(str);

                $('#arrayCantidad').val(str);

            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var checkboxs = document.querySelectorAll('.checkboxs');
            var guardar = document.getElementById('guardar');

            checkboxs.forEach(function(checkbox) {

                checkbox.addEventListener('change', function() {

                    var input = this.parentNode.querySelector('.cantidad');

                    if (this.checked) {
                        input.type = 'number';
                        input.required = true;
                        guardar.disabled = false;

                    } else {
                        input.type = 'hidden';
                        input.required = false;
                    }


                })
            })
        });
    </script>
        <script>
            $(document).ready(
                $('.completada').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                    var dataString = 'id=' + id;
                    Swal.fire({
                        icon: 'info',
                        title: '¿Seguro que quieres marcar como completada esta solicitud?',
                        showConfirmButton: true,
                        confirmButtonText: 'SI',
                        confirmButtonColor: '#3085d6',
                        showCancelButton: true,
                        cancelButtonText: 'NO',
                        cancelButtonColor: '#d33',
                        buttonsStyling: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../sdo-funciones/soliCompra_sdo_completarPanol.php',
                                type: 'POST',
                                data: dataString,
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Solicitud completada con exito" ',
                                        showConfirmButton: false,
                                        timer: 2000,
                                    })
                                    // setTimeout(function() {
                                    //     location.reload();
                                    // }, 2100)
                                }
                            })
                        }
                    })
                })
            )
        </script>
    <!-- <script>
        $(function() {
            $('#ordencompra').DataTable({
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
                }
            });
        });
    </script> -->
    <script>
        estadoSDA = document.getElementById('estadoSDA');
        motivo = document.getElementById('motivo');
        motivoText = document.getElementById('motivoText');

        estadoSDA.addEventListener('change', function(event) {

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