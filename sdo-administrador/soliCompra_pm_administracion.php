<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');
if (isset($_POST['descargaCotizacion'], $_POST['folioSoliCompraDescarga'])) {
    $descargaCotizacion = $_POST['descargaCotizacion'];
    $folioSoliCompraDescarga = trim($_POST['folioSoliCompraDescarga']);

    if ($descargaCotizacion > 0) {

        if (empty($folioSoliCompraDescarga) || $folioSoliCompraDescarga == '') {
        } else {
            $descargaCotizacion = str_pad($descargaCotizacion, 4, "0", STR_PAD_LEFT);


            $folioSoliCompra2 = "SC-PM-" . $descargaCotizacion;

            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir = '../sdo-solicCompra/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Cotizaciones " . $folioSoliCompraDescarga . ".zip";

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zipSC($dir2, $zip);
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


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra</title>

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
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarSC").show();
                $("#realizarSC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
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

</head>

<body class="hold-transition sidebar-mini">
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
                        <h4>Solicitudes de Compras para Puesta en Marcha</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

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

        if (isset($_GET['errorAsignarNumInterno'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Ingresar N&uacute; Interno a la Solicitud de Compra',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['existeAsignarNumInterno'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'El N&uacute;mero Ingresado ya existe en el Sistema. Reintente.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoAsignarNumInterno'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Actualizaci&oacute;n Realizada con &Eacute;xito. !',
                    'El N&uacute;mero Interno de esta Solicitud ha sido actualizado.',
                    'success'
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
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


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
                                <div class="table-responsive">

                                    <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Interno de
                                                    <br>de Puesta en Marcha
                                                </center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Adjuntos</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Asignar N° Interno<br>PM
                                                </center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Anular SC</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $query = "SELECT * FROM solicitudes_compra_pm ORDER BY fechaSoliCompra DESC";

                                        $res = mysqli_query($conexion, $query);

                                        $numSolicitud = 1;

                                        while ($row = $res->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                <center>
                                                    <?php if ($row['fechaSoliCompra'] == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $inicioFecha = date('d/M/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
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

                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-SoliCompra-PM.php" target="_blank" method="post">

                                                            <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-primary">
                                                                <i class="fa fa-eye"></i>
                                                            </button>

                                                        </form>

                                                        <?php

                                                    }
                                                    ?>


                                                </center>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <?php
                                                $contarCotizacionesSoliCompraPM = contarCotizacionesSoliCompraPM($idSoliCompra);
                                                ?>

                                                <div class="text-center">
                                                    <?php if ($contarCotizacionesSoliCompraPM == 0): ?>
                                                        NO HAY ARCHIVOS ADJUNTOS
                                                    <?php else: ?>
                                                        <form class="d-inline-block" action="soliCompra_pm_administracion.php" method="post">
                                                            <input type="hidden" name="descargaCotizacion" value="<?php echo htmlspecialchars($idSoliCompra); ?>">
                                                            <input type="hidden" name="folioSoliCompraDescarga" value="<?php echo htmlspecialchars($folioSoliCompra); ?>">
                                                            <button type="submit" class="btn btn-warning" title="Descargar cotización">
                                                                <i class="fa fa-download"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>

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

                                                            echo "DESHABILITADO";
                                                        } else {
                                                            ?>


                                                            <form method="post" action="../sdo-funciones/soliCompra_agregarNumero_pm.php">
                                                                <button type="button" data-toggle="modal" data-target="#modalAddNumInterno<?php echo $idSoliCompra ?>" id="inputMozi" class="btn btn-block btn-info">
                                                                    <i class="fa fa-pen"></i>
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modalAddNumInterno<?php echo $idSoliCompra ?>" name="modalAddNumInterno<?php echo $idSoliCompra ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 style="text-align:left" class="modal-title" id="exampleModalLabel">Agregar N&uacute;mero Interno de PM a
                                                                                    <br> <?php echo $row['folioSoliCompra'] ?>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <div style="text-align:left;" class="form-group">
                                                                                    <label for="exampleInputBorder">N&uacute;mero Interno de Puesta en Marcha:
                                                                                        <code>*</code></label>
                                                                                    <input type="number" min="1" class="form-control form-control-border" id="addNumInterno" name="addNumInterno" placeholder="N&uacute;mero Interno de Puesta en Marcha" value="" required>
                                                                                </div>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="idSoliCompra" id="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
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

                                                            <!--Antes del cambio era soliCompra_visualizarAnularSC_pm.php en la carpeta sdo-administrador-->
                                                            <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/soliCompra_visualizarAnularSC_pm.php" method="post">

                                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-warning">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>

                                                            </form>

                                                            <?php
                                                        } else {
                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" action="soliCompra_anularSC_pm.php" method="post">

                                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-danger">
                                                                    <i class="fa fa-times"></i>
                                                                </button>

                                                            </form>

                                                            <?php
                                                        }
                                                    }
                                                    ?>


                                                </center>
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
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Interno de
                                                    <br>de Puesta en Marcha
                                                </center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Adjuntos</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Asignar N° Interno<br>PM
                                                </center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Anular SC</center>
                                            </th>
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
                                <h5>2.- Solicitar Compra de Materiales</h5>
                                <hr>
                                <form action="../sdo-funciones/soliCompra_solicitarCompraPM.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code> (Maximo 407 Caracteres)</label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" onkeydown="bloquearSaltoDeLinea1(event)" oninput="limitarCaracteres1(this)" maxlength="407" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud"></textarea>
                                        <script>
                                            function limitarCaracteres1(element) {
                                                var maxLength = parseInt(element.getAttribute("maxlength"));
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }

                                            }

                                            function bloquearSaltoDeLinea1(event) {
                                                if (event.keyCode === 13) {
                                                    event.preventDefault();
                                                    // También puedes mostrar un mensaje de advertencia al usuario si lo deseas
                                                    console.log("No se permite el salto de línea.");
                                                }
                                            }
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Describa el bien y/o servicio a adquirir claramente:<code>*</code> (Maximo 407 Caracteres)</label>
                                        <textarea id="materiales" style="resize:none;" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="407" required name="materiales" class="form-control" rows="10" placeholder="Describa el bien y/o servicio a adquirir claramente"></textarea>
                                        <script>
                                            function limitarCaracteres(element) {
                                                var maxLength = parseInt(element.getAttribute("maxlength"));
                                                if (element.value.length > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                }

                                            }

                                            function bloquearSaltoDeLinea(event) {
                                                if (event.keyCode === 13) {
                                                    event.preventDefault();
                                                    // También puedes mostrar un mensaje de advertencia al usuario si lo deseas
                                                    console.log("No se permite el salto de línea.");
                                                }
                                            }
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosSC[]" name="adjuntosSC[]" accept=".pdf, .doc, .docx, .xls, .xlsx">
                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>

                                    <div class="form-group">
                                        <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                                        <input type="hidden" name="cargoSC" id="cargoSC" value="<?php echo $cargoUsuario; ?>">
                                        <input type="hidden" name="deptoSC" id="deptoSC" value="Administrador">
                                        <input type="hidden" name="idSolicitudSoporte" id="idSolicitudSoporte" value="0">
                                        <input type="hidden" name="emailSC" id="emailSC" value="saladecontrolhbqp@gmail.com">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>


                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        //group add limit
                                        var maxGroup = "100";

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
        $('#example').DataTable({
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
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>