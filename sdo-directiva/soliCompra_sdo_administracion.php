<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";


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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra - SDO</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                $("#listarSC2").show();
                $("#realizarSC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarSC").hide();
                $("#realizarSC").show();
                $("#listarSC2").hide();
                $("#realizarSC2").show();
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
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Solicitudes de Compras - SDO <?php echo $folioSoliCompra2; ?></h4>


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
                    title: 'Error al Ingresar Solicitud de Compra.',
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


                                <a href="index.php" style="float: right;" class="btn btn-danger"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>
                            <div class="card-body">

                                <div class="row text-center" style="display: flex; justify-content:center;">



                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="realizarSC2" name="realizarSC2">
                                        <!-- small box -->

<!--                                        --><?php
//
//                                        $ipClienteActual = obtenerIPCliente();
//
//                                        if($ipClienteActual == "10.69.250.151"){
//                                            ?>
<!---->
<!--                                            <button id="realizarSC" name="realizarSC"-->
<!--                                                    class="btn btn-block bg-gradient-yellow btn-md text-black text-center show-realizar">-->
<!--                                                <i class="nav-icon fas fa-pen"></i><br>Realizar-->
<!--                                                Solicitud de Compra-->
<!--                                            </button>-->
<!---->
<!--                                            --><?php
//                                        }else{
//                                            echo '<b>"No es posible crear solicitudes de compra en este momento"</b>';
//
//                                        }
//
//                                        ?>
                                        <button id="realizarSC" name="realizarSC"
                                                class="btn btn-block bg-gradient-yellow btn-md text-black text-center show-realizar">
                                            <i class="nav-icon fas fa-pen"></i><br>Realizar
                                            Solicitud de Compra
                                        </button>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarSC2" name="listarSC2" style="display: none;">
                                        <!-- small box -->
                                        <button id="listarSC" name="listarSC"
                                                class="btn btn-block bg-gradient-yellow btn-md text-black text-center show-listar"
                                                style="display: none;">
                                            <i class="nav-icon fas fa-list"></i><br>Listar Solicitudes de Compra
                                        </button>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="descargarInfo2"
                                            name="descargarInfo2">
                                        <!-- small box -->

                                        <button type="button"
                                                class="btn btn-block bg-gradient-primary btn-md text-white text-center"
                                                data-toggle="modal" data-target="#modalDescMaterial">
                                            <i class="nav-icon fas fa-download"></i><br>Descarga Materiales
                                            Solicitados
                                        </button>
                                        <form action="../sdo-jefestecnicos/haroll.php" method="post">

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDescMaterial"
                                                    name="modalDescMaterial" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Descargar
                                                                Materiales SC</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label>Solicitud de Compra Inicial:</label>
                                                                <input type="number" class="form-control"
                                                                        name="noinicioSC" id="noinicioSC" min="0"
                                                                        max="<?php echo getUltimoID(); ?>">
                                                            </div>
                                                            <br>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label>Solicitud de Compra Final:</label>
                                                                <input type="number" class="form-control"
                                                                        name="noterminoSC" id="noterminoSC" min="0"
                                                                        max="<?php echo getUltimoID(); ?>">

                                                            </div>
                                                            <br>
                                                            <p class="text-justify">
                                                                <b>Importante: </b>Materiales pertenecientes a
                                                                solicitudes anuladas, no ser&aacute;n registrados.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Descargar
                                                            </button>
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.modal -->
                                        </form>

                                    </div>
                                </div>

                            </div>
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
                                        <th style="vertical-align:middle; text-align: center;">
                                            Fecha de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Personal Solicitante
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Folio de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            N° Interno
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Estado de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Ticket
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Descripci&oacute;n
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $query = "SELECT * FROM solicitudes_compra_sdo ORDER BY idSoliCompra DESC";

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

                                                    $inicioFecha = date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
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
                                                <button type="submit" class="btn btn-xs btn-warning">
                                                    <b><i class="fa fa-edit"></i>&nbsp;&nbsp;Detalles</b>
                                                </button>
                                            </form>
                                        </td>
                                        <td style="vertical-align:middle;" align="center">
                                            <?php
                                            $idSolicitudSoporte = $row['idSolicitudSoporte'];
                                            if ($idSolicitudSoporte == 0 || $idSolicitudSoporte == "" || $idSolicitudSoporte == " " || $idSolicitudSoporte == "  ") {
                                                echo 'Sin Ticket Asociado';
                                                ?>
                                                <?php
                                            } else {
                                                ?>
                                                <form style="margin: 0; text-align: center;" class="" action="solicitudes_detalle.php" method="post">
                                                    <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitudSoporte; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-xs bg-pink">
                                                        <b>
                                                            <i class="fas fa-eye"></i></b>
                                                    </button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;" align="center">
                                            <?php
                                            $descripcionSoliCompra = $row['descripcionSoliCompra'];
                                            if (empty($descripcionSoliCompra)) {
                                                echo 'Sin Descripción';
                                            } else {
                                                echo mb_convert_case($descripcionSoliCompra, MB_CASE_UPPER, 'UTF-8');
                                            }
                                            ?>
                                        </td>


                                        <?php

                                        $numSolicitud = $numSolicitud + 1;
                                        }
                                        ?>

                                    </tr>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Fecha de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Personal Solicitante
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Folio de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            N° Interno
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Estado de Solicitud
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Ticket
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            Descripci&oacute;n
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Solicitar Compra de Materiales</h5>
                                <hr>
                                <form action="../sdo-funciones/soliCompra_solicitarCompraSDOUsers.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Defina Descripci&oacute;n de Solicitud (Ejemplo: Solicitud de Herramientas, Solicitud de Petroleo):<code>*</code></label>
                                        <input type="text" class="form-control" maxlength="60" id="descripcionSoliCompra" name="descripcionSoliCompra" required placeholder="Descripción Solicitud">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code> (Maximo 1271 Caracteres)</label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="1271" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud (Maximo 1271 Caracteres)"></textarea>

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


                                    <div class="form-row fieldGroup">
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                </div>
                                                <input type="number" min="1" class="form-control" id="cantidadSC[]"
                                                        name="cantidadSC[]" placeholder="Cantidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputRecinto">Articulo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="articuloSC[]"
                                                        name="articuloSC[]" placeholder="Articulo">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Medida:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control" id="medidaSC[]"
                                                        name="medidaSC[]" placeholder="Medida">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">Valor Total Referencial:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="fas fa-building"></i></span>
                                                </div>
                                                <input type="number" min="0" class="form-control" id="valorSC[]"
                                                        name="valorSC[]" placeholder="Valor Total Referencial">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)"
                                                        class="btn btn-block btn-success addMore"><span
                                                            class="glyphicon glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span>Agregar Otro Material</a>
                                            </div>
                                        </div>
                                    </div>


                                    <br>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc ,
                                            .docx , .xls , .xlsx, .eml</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="40" class="custom-file-input"
                                                    onchange="mostrarRadio(this)" multiple="" id="adjuntosSC[]"
                                                    name="adjuntosSC[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                            <label class="custom-file-label" for="customFile">Seleccionar
                                                Archivo...</label>
                                        </div>
                                    </div>

                                    <fieldset class="border p-3" id="radios" style="display: none;">
                                        <h6>¿Es una cotización el archivo adjunto?</h6>
                                        <div class="form-group">

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion"
                                                        id="isCotizacion1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">Si</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion"
                                                        id="isCotizacion2" value="0">
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>

                                        </div>
                                    </fieldset>
                                    <script type="text/javascript">
                                        $('.custom-file input').change(function (e) {
                                            var files = [];
                                            for (var i = 0; i < $(this)[0].files.length; i++) {
                                                files.push($(this)[0].files[i].name);
                                            }
                                            $(this).next('.custom-file-label').html(files.join(', '));
                                        });
                                    </script>


                                    <br>
                                    <hr>
                                    <br>

                                    <?php

                                    $deptoSCDirectivo = "Subdirección de Operaciones";

                                    if ($nomUsuario == 'Rafael Mendoza Saavedra') {
                                        $deptoSCDirectivo = "Ingeniería Operacional";
                                    }
                                    ?>

                                    <div class="form-group">
                                        <input type="hidden" name="personalSC" id="personalSC"
                                                value="<?php echo $nomUsuario; ?>">
                                        <input type="hidden" name="cargoSC" id="cargoSC"
                                                value="<?php echo $cargoUsuario; ?>">
                                        <input type="hidden" name="deptoSC" id="deptoSC"
                                                value="<?php echo $deptoSCDirectivo; ?>">
                                        <input type="hidden" name="emailSC" id="emailSC"
                                                value="<?php echo $correoUsuario; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>

                                <div class="form-row fieldGroupCopy" style="display: none;">

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                            </div>
                                            <input type="number" min="1" class="form-control" id="cantidadSC[]"
                                                    name="cantidadSC[]" placeholder="Cantidad">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="articuloSC[]"
                                                    name="articuloSC[]" placeholder="Articulo">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                            </div>
                                            <input type="text" min="1" class="form-control" id="medidaSC[]"
                                                    name="medidaSC[]" placeholder="Medida">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="number" min="0" class="form-control" id="valorSC[]"
                                                    name="valorSC[]" placeholder="Valor Total Referencial">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span
                                                        class="glyphicon glyphicon glyphicon-remove"
                                                        aria-hidden="true"></span> Eliminar</a>
                                        </div>
                                    </div>
                                </div>


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
<script src="js/dark-mode.js"></script>
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
    function mostrarRadio(inputFile) {
        radios = document.getElementById("radios");
        inputRadio1 = document.getElementById("isCotizacion1");
        inputRadio2 = document.getElementById("isCotizacion2");
        if (inputFile.files.length > 0) {
            radios.style.display = "block";
            inputRadio1.required = true;
            inputRadio2.required = true;
        } else {
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