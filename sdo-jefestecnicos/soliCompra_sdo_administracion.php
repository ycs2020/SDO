
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
                        <h4>Solicitudes de Compras - SDO</h4>

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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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


<!--                                --><?php
//
//                                $ipClienteActual = obtenerIPCliente();
//
//                                if($ipClienteActual == "10.69.250.151"){
//                                    ?>
<!--                                    <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Realizar Solicitud de Compra</button>-->
<!--                                --><?php
//                                }else{
//                                    echo '<b>"No es posible crear solicitudes de compra en este momento"</b>';
//
//                                }
//
//                                ?>
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

                                <input type="hidden" id="nomDeptoEnviar" name="nomDeptoEnviar" value="<?php echo $nomDepartamento; ?>">
                                <div class="table-responsive">
                                    <table style="font-size:90%" id="tableSCSDO" class="table table-bordered table-hover">
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

                                        <tbody></tbody>
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


                                <script>
                                    $(document).ready(function () {
                                        var columns = [
                                            {"data": "fechaSoliCompra"},
                                            {"data": "personalSoliCompra"},
                                            {"data": "folioSoliCompra"},
                                            {"data": "numSoliCompra"},
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    let estadoSoliCompra = row.estadoSoliCompra.toUpperCase();

                                                    let formHTML = '<form action="soliCompra_sdo_seguimiento.php" method="POST" target="_blank" style="display:inline;" enctype="multipart/form">' +
                                                        '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                        '<button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Detalles</b></button>' +
                                                        '</form>';

                                                    return estadoSoliCompra + '<br><br>' + formHTML;
                                                }
                                            },
                                            {"data": "textoTicket"},
                                            {"data": "descripcionSoliCompra"},

                                            // Agregar columnas dinámicamente
                                        ];

                                        var nomDeptoEnviar = $('#nomDeptoEnviar').val();

                                        $('#tableSCSDO').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
                                            "responsive": true,
                                            "ordering": false,
                                            "language": {
                                                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                "zeroRecords": "No se han encontrado Registros.",
                                                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                "infoEmpty": "No existen Registros.",
                                                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                "search": "Buscar:",
                                                "processing": "Procesando...",
                                                "paginate": {
                                                    "previous": "Anterior",
                                                    "next": "Siguiente"
                                                }
                                            },
                                            "ajax": {
                                                "url": "ajax-listado-sc-sdo.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                    d.nomDeptoEnviar = $('#nomDeptoEnviar').val(); // Pasa el valor seleccionado del select como parámetro
                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": columns,
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

                                            }
                                        });
                                    });
                                </script>
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


