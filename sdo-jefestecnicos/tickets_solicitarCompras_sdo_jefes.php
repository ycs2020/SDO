<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];

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

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = '../sdo-solicCompra/' . $folioSoliCompra2 . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Cotizaciones " . $folioSoliCompraDescarga . ".zip";

            // echo $rutaFinal.$folioSoliCompra2;

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir, $zip);
                $zip->close();
                if (file_exists($rutaFinal . $archivoZip)) {

                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
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


if (isset($_POST['idSolicitud'])) {
    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));

        $_SESSION['idSolicitud'] = $idSolicitud;
    }
} else if (isset($_SESSION['idSolicitud'])) {
    $idSolicitud = trim($_SESSION['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));
    }
} else {
    header('Location: solicitudes_detalle.php?errorSolicitudCompra=true');
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


    <script>
        $(document).ready(function() {
            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarSC").show();
                $("#realizarSC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarSC").hide();
                $("#realizarSC").show();
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


    <script>
        function descargarArchivos($id) {
            alert($id);
        }
    </script>

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
                        <h4>Ticket S.D.O - Solicitudes de Compra</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

        if ($descargaConfirma == "Y" || $descargaConfirma == "y") {
            ?>
            <script>
                Swal.fire({
                    title: 'Descarga de Cotizaciones con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
        <?php
        } else if ($descargaConfirma == "N" || $descargaConfirma == "n") {
        ?>
            <script>
                Swal.fire({
                    title: 'Error al Descargar Cotizaci&oacute;n.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }

        if (isset($_GET['exitoCrearSC'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'Solicitud de Compra Ingresada con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                }).then((result) => {
                    <?php
                    if ($_GET['material'] == "Con") {
                    ?>
                    Swal.fire('Materiales solicitados con &eacute;xito.', '', 'success')
                    <?php
                    } else if ($_GET['material'] == "Sin") {
                    ?>
                    Swal.fire('Solicitud sin Materiales espec&iacute;ficos.', '', 'success')
                    <?php
                    } else {
                    ?>
                    Swal.fire('Error al Solicitar Materiales.', '', 'error')
                    <?php
                    }
                    ?>
                })
            </script>
            <?php
        }
        if (isset($_GET['errorCrearSC'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Ingresar Solicitud de Compra.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }

        if (isset($_GET['exitoAnularSolicitud'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'Anulaci&oacute;n de Solicitud de Compra con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
            <?php
        }

        if (isset($_GET['errorAnularSolicitud'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Anular Solicitud de Compra.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
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
                                <a href="solicitudes_detalle.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <h5>Solicitud de Soporte:<br><b>#<?php echo $folioSolicitud; ?></b></h5>
                                <br>
                                <br>

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
                                <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Realizar Solicitud de Compra</button>
                                <button class="btn bg-yellow show-listar" id="listarSC" style="display:none;" name="listarSC">Listar Solicitudes de Compra</button>

                                <!-- <button type="button" class="hide-btn">Hide</button>
                                <button type="button" class="show-btn">Show</button>
                                <button type="button" class="toggle-btn">Toggle</button> -->


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Solicitudes de Compra</h5>
                                <hr>

                                <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Personal Solicitante</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Folio de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>N° Interno</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Estado de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Descripci&oacute;n
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Visualizar<br>Solicitud de Compra</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Descargar<br>Cotizaciones</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Anular<br>Solicitud de Compra</center>
                                        </th>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;font-size:12px">
                                            Obs
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $query = "SELECT * FROM solicitudes_compra_sdo WHERE idSolicitudSoporte = $idSolicitud ORDER BY idSoliCompra DESC";

                                    $res = mysqli_query($conexion, $query);

                                    $numSolicitud = 1;

                                    while ($row = $res->fetch_assoc()) {
                                    $idSoliCompra = $row['idSoliCompra'];

                                    ?>
                                    <tr>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <center>
                                                <?php if ($row['fechaSoliCompra'] == 0) {
                                                    echo "No registra fecha";
                                                } else {

                                                    $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
                                                    $inicioHora = date('H:i', strtotime($row['fechaSoliCompra']));

                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                } ?>
                                            </center>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $personalSoliCompra = mb_strtoupper($row['personalSoliCompra']);

                                            $personalSoliCompra = explode(' (', $personalSoliCompra);

                                            $unidadSoliCompra = mb_strtoupper($row['unidadSoliCompra']);

                                            if (empty($personalSoliCompra[1])) {
                                                echo $personalSoliCompra[0] . '<br>(' . $unidadSoliCompra . ')';
                                            } else {
                                                echo $personalSoliCompra[0] . '<br>(' . $personalSoliCompra[1] . '<br>(' . $unidadSoliCompra . ')';
                                            }

                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $folioSoliCompra = mb_strtoupper($row['folioSoliCompra']);
                                            echo $folioSoliCompra;

                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $numSoliCompra = mb_strtoupper($row['numSoliCompra']);

                                            if ($numSoliCompra == 0) {
                                                echo "SIN N&Uacute;MERO ASIGNADO";
                                            } else {
                                                echo $numSoliCompra;
                                            }



                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $estadoSoliCompra = mb_strtoupper($row['estadoSoliCompra']);
                                            echo $estadoSoliCompra;

                                            ?>
                                            <br>
                                            <br>
                                            <!-- Large modal -->
                                            <form action="soliCompra_sdo_seguimiento.php" method="POST" style="display:inline;" enctype="multipart/form">
                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra ?>">
                                                <button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Detalles</b></button>
                                            </form>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php echo $row['descripcionSoliCompra']?>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <center>
                                                <?php
                                                $idSoliCompra = $row['idSoliCompra'];
                                                $idSoliCompra = trim($idSoliCompra);
                                                if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                    ?>
                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                    <?php
                                                } else {

                                                    ?>

                                                    <form target="_blank" style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarSC_sdo.php" method="post">

                                                        <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                    </form>

                                                    <?php

                                                }
                                                ?>


                                            </center>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <center>
                                                <?php
                                                $idSoliCompra = $row['idSoliCompra'];
                                                $idSoliCompra = trim($idSoliCompra);
                                                if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                    ?>
                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                    <?php
                                                } else {
                                                    $contarCotizacionesSoliCompraSDO = contarCotizacionesSoliCompraSDO($idSoliCompra);

                                                    if ($contarCotizacionesSoliCompraSDO < 1) {
                                                        echo "No existen Cotizaciones Adjuntas.";
                                                    } else {
                                                        ?>


                                                        <form action="soliCompra_sdo_administracion.php" style="margin: 0; text-align: center;" class="" method="post">

                                                            <input type="hidden" name="descargaCotizacion" value="<?php echo $idSoliCompra; ?>">
                                                            <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="<?php echo $folioSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-download"></i></button>

                                                        </form>

                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </center>
                                        </td>


                                        <td style="vertical-align:middle;">
                                            <center>
                                                <?php
                                                $idSoliCompra = $row['idSoliCompra'];
                                                $idSoliCompra = trim($idSoliCompra);
                                                if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                    ?>
                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                    <?php
                                                } else {
                                                    if ($estadoSoliCompra == "SOLICITUD ANULADA") {
                                                        ?>
                                                        <form style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarAnularSC_sdo.php" method="post">
                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-warning"> <b> <i class="fa fa-eye"></i>&nbsp;&nbsp;Ver Razones</b></button>
                                                        </form>
                                                        <?php
                                                    } else if ($estadoSoliCompra == "RECHAZADO POR LA S.D.A") {
                                                        ?>
                                                        <form style="margin: 0; text-align: center;" class="" action="soliCompra_sdo_seguimiento.php" method="post">
                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-warning"> <b> <i class="fa fa-eye"></i>&nbsp;&nbsp;Ver Detalles</b></button>
                                                        </form>
                                                        <?php

                                                    } else {
                                                        ?>
                                                        <form style="margin: 0; text-align: center;" class="" action="soliCompra_anularSC_sdo.php" method="post">
                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-danger"> <b> <i class="fa fa-times"></i>&nbsp;&nbsp;Anular Solicitud</b></button>
                                                        </form>
                                                        <?php
                                                    }
                                                }
                                                ?>


                                            </center>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">

                                            <?php

                                            $idSoliCompra = $row['idSoliCompra'];

                                            ?>

                                            <button type="button" data-toggle="modal" data-target="#modalAbastecimiento<?php echo $idSoliCompra ?>" id="inputMozi" class="btn bg-info btn-xs" style="float:right;"><i class="fas fa-comments"></i> </button>

                                            <div class="modal fade" id="modalAbastecimiento<?php echo $idSoliCompra ?>" name="modalAbastecimiento<?php echo $idSoliCompra ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                <b>Observaciones de la solicitud</b>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>


                                                        <div class="modal-body">
                                                            <!-- <form action="../sdo-funciones/soliCompra_sdo_agregarObservacion.php" method="POST">
                                                                            <div style="text-align:left;" class="form-group">
                                                                                <label for="exampleInputBorder">Detalle
                                                                                    Observacion: <code>*</code></label>
                                                                                <textarea class="form-control" name="detalleObservacion" required id="detalleObservacion" rows="5"></textarea>
                                                                                <input type="hidden" value="<?php echo $idSoliCompra ?>" name="idSoliCompra">

                                                                            </div>

                                                                            <div align="left" style="text-align:left">
                                                                                <button type="submit" class="btn btn-success" style="text-align:left"> <b><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir Observación</b> </button>
                                                                            </div>
                                                                        </form> -->

                                                            <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
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
                                                                $queryObs = "SELECT * FROM solicitudes_compra_sdo_observaciones WHERE idSoliCompra = '$idSoliCompra'";
                                                                $resObs = mysqli_query($conexion, $queryObs);


                                                                while ($rowObs = $resObs->fetch_assoc()) {

                                                                    ?>
                                                                    <tr>

                                                                        <td style="vertical-align:middle;" nowrap="nowrap">
                                                                            <?php


                                                                            $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowObs['fechaObservacion']))));
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
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-info" data-dismiss="modal"> <b>Revisado!</b> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>


                                        <?php

                                        $numSolicitud = $numSolicitud + 1;
                                        }
                                        ?>

                                    </tr>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Personal Solicitante</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Folio de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>N° Interno</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Estado de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Descripci&oacute;n
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Visualizar<br>Solicitud de Compra</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Descargar<br>Cotizaciones</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Anular<br>Solicitud de Compra</center>
                                        </th>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;font-size:12px">
                                            Obs
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

                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">

                    <?php
                    if($nomDepartamento == 'Instrumentación' || $nomDepartamento == 'Control Centralizado'){
                        include_once 'sdo-includes/sc-sdo-control.php';
                    }else{
                        include_once 'sdo-includes/sc-sdo-especialidades.php';
                    }
                    ?>

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
            "paging": true,
            "pageLength": 10,
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
<script>
    function mostrarRadio(inputFile){
        radios = document.getElementById("radios");
        inputRadio1 = document.getElementById("isCotizacion1");
        inputRadio2 = document.getElementById("isCotizacion2");
        if(inputFile.files.length > 0){
            radios.style.display = "block";
            inputRadio1.required = true;
            inputRadio2.required = true;
        }else{
            radios.style.display = "none";
            inputRadio1.required = false;
            inputRadio2.required = false;
        }
    }
</script>
<!-- <script>
    function myFunction(name) {
        alert(name)
    }
</script> -->
</body>

</html>