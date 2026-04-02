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

            $folioSoliCompra2 = "SC-PM-" . $descargaCotizacion;


            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir = '../sdo-solicCompra/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-bodega-panol/";
            $archivoZip = "Cotizaciones " . $folioSoliCompra2 . ".zip";

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
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

    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/bodega-panol-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Solicitudes de Compras para Puesta en Marcha</h4>

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
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al crear la Solicitud de Compra',
                    'error'
                )
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
        <section class="content">
            <div class="container-fluid">
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
            </div>
            <div class="row" id="rowMenu1" name="rowMenu1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>1.- Listado de Solicitudes de Compra</h5>
                            <hr>
                            <div class="table-responsive">
                                <table style="width:100%; font-size:90%" id="solicitudesCompraPM" class="table table-bordered table-hover">
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
                                            <center>N° Interno de <br>de Puesta en Marcha</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Estado de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Ver Solicitud de Compra</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Ver Archivo<br>Adjunto</center>
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

                                                                $inicioFecha =  date('d/M/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
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
                                                    <td style="vertical-align:middle;" align="center">
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
                                                                $contarCotizacionesSoliCompraPM = contarCotizacionesSoliCompraPM($idSoliCompra);

                                                                if ($contarCotizacionesSoliCompraPM < 1) {
                                                                    echo "No existen Cotizaciones Adjuntas.";
                                                                } else {


                                                                    $porciones = explode("-", $folioSoliCompra);

                                                                    $tamano = count($porciones);

                                                                    $valorResta = $tamano - 1;

                                                                    $texto2 = '';

                                                                    for ($i = 0; $i < $tamano; $i++) {
                                                                        if ($valorResta == $i) {
                                                                        } else {
                                                                            if($i == 0){
                                                                                $texto2 = $porciones[$i];
                                                                            }else{
                                                                                $texto2 = $texto2.'-' . $porciones[$i];
                                                                            }
                                                                        }
                                                                    }

                                                                    ?>


                                                                    <form action="soliCompra_pm_administracion.php" style="margin: 0; text-align: center;" class="" method="post">

                                                                        <input type="hidden" name="descargaCotizacion" value="<?php echo $idSoliCompra; ?>">
                                                                        <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="<?php echo $texto2; ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-download"></i></button>

                                                                    </form>

                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </center>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
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
                                            <center>N° Interno de <br>de Puesta en Marcha</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Estado de Solicitud</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Ver Solicitud de Compra</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Ver Archivo<br>Adjunto</center>
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#solicitudesCompraPM').DataTable({
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
</body>
</html>

