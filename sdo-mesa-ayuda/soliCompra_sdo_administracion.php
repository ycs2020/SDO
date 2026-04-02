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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">



    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>



    <script type="text/javascript">
        $(document).ready(function() {
            recargarListTecnicos();
            recargarListTecnicosPersonal();

            $('#departamentoSolicitante').change(function() {
                recargarListTecnicos();

            });

            $('#depaPersonal').change(function() {
                recargarListTecnicosPersonal();

            });



            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicos1.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlSelect').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };

            function recargarListTecnicosPersonal() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosPersonal.php",
                    data: "idDepartamento=" + $('#depaPersonal').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlPersonal').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };


        });
    </script>
    <style>
        #personal,
        #jefes,
        #directivos {
            display: none;
        }
    </style>


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

    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
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
                    title: 'Error al Descargar Cotización de Solicitud de Compra.',
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

        if (isset($_GET['exitoAsignarNumInterno'])) {
            ?>
            <script>
                Swal.fire({
                    title: 'N&uacute;mero Interno agregado a Solicitud de Compra - SDO.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
            <?php
        }

        if (isset($_GET['errorAsignarNumInterno'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Error al Ingresar N&uacute;mero Interno a la Solicitud de Compra',
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

        if (isset($_GET['idNoValido'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'El ID no es valido.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['exitoObs'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Exito !',
                    'Exito al añadir observaciones.',
                    'success'
                )
            </script>
            <?php
        }

        if (isset($_GET['errorObs'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'No se a podido añadir la observación.',
                    'error'
                )
            </script>
            <?php
        }

        if (isset($_GET['noObs'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Ha ocurrido un Problema !',
                    'Sin Observacion escrita.',
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
                                <button class="btn bg-yellow show-realizar" id="realizarSC" name="realizarSC">Realizar Solicitud de Compra</button>
                                <button class="btn bg-yellow show-listar" id="listarSC" style="display:none;" name="listarSC">Listar Solicitudes de Compra</button>
                                <button class="btn bg-info ml-3" data-toggle="modal" data-target="#modalDescMaterial"> Descargar Materiales</button>

                                <!-- <button type="button" class="hide-btn">Hide</button>
                                <button type="button" class="show-btn">Show</button>
                                <button type="button" class="toggle-btn">Toggle</button> -->

                                <form action="../sdo-jefestecnicos/haroll.php" method="post">

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
                                                    <p class="text-justify"><b>Importante: </b>Materiales pertenecientes a
                                                        solicitudes anuladas, no ser&aacute;n registrados.</p>
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
                                </form>



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

                                    <table style="font-size:9pt; width:100%;" id="tableSCSDO" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;font-size:12px">
                                                Obs
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                N° Interno<br>SDO
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Fecha de Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Personal Solicitante
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Folio de Solicitud
                                            </th>
                                            <th align="center" style="vertical-align:middle;">
                                                Descripci&oacute;n<br>Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Estado de Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Visualizar<br>Solicitud de Compra
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Descargar<br>Cotizaciones
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Anular<br>Solicitud de Compra
                                            </th>


                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;font-size:12px">
                                                Obs
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                N° Interno<br>SDO
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Fecha de Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Personal Solicitante
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Folio de Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Descripci&oacute;n<br>Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Estado de Solicitud
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Visualizar<br>Solicitud de Compra
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Descargar<br>Cotizaciones
                                            </th>
                                            <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                Anular<br>Solicitud de Compra
                                            </th>


                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <script>
                                    $(document).ready(function () {
                                        var columns = [
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    let buttonHTML = '';
                                                    if (row.notificacion === 0) {
                                                        buttonHTML = '<button type="submit" data-toggle="modal" data-target="#modalAbastecimiento' + row.idSoliCompra + '" id="inputMozi" class="btn bg-info btn-xs"><i class="fas fa-comments"></i></button>';
                                                    } else {
                                                        buttonHTML = '<button type="submit" data-toggle="modal" data-target="#modalAbastecimiento' + row.idSoliCompra + '" id="inputMozi" class="btn bg-info btn-xs"><i class="fas fa-comments"></i><span class="badge badge-danger">' + row.notificacion + '</span></button>';
                                                    }

                                                    let modalHTML = '<div class="modal fade" id="modalAbastecimiento' + row.idSoliCompra + '" name="modalAbastecimiento' + row.idSoliCompra + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
                                                        '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">' +
                                                        '<div class="modal-content">' +
                                                        '<div class="modal-header bg-info">' +
                                                        '<h5 class="modal-title" id="exampleModalLabel">' +
                                                        '<b>Añadir Observacion</b>' +
                                                        '</h5>' +
                                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                        '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                        '</div>' +
                                                        '<div class="modal-body">' +
                                                        '<form action="../sdo-funciones/soliCompra_sdo_agregarObservacion.php" method="POST">' +
                                                        '<div style="text-align:left;" class="form-group">' +
                                                        '<label for="exampleInputBorder">Detalle Observacion: <code>*</code></label>' +
                                                        '<textarea class="form-control" name="detalleObservacion" required id="detalleObservacion" rows="5"></textarea>' +
                                                        '<input type="hidden" value="' + row.idSoliCompra + '" name="idSoliCompra">' +
                                                        '</div>' +
                                                        '<div align="left" style="text-align:left">' +
                                                        '<button type="submit" class="btn btn-success"><b><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir Observación</b></button>' +
                                                        '</div>' +
                                                        '</form>' +
                                                        '<br><br>' +
                                                        '<div class="table-responsive">' +
                                                        '<table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">' +
                                                        '<thead class="bg-secondary w-100">' +
                                                        '<tr>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Fecha</th>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Creado Por</th>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Detalles</th>' +
                                                        '</tr>' +
                                                        '</thead>' +
                                                        '<tbody id="modalBody' + row.idSoliCompra + '">';

                                                    // Aquí puedes agregar el código para cargar los datos del modal

                                                    modalHTML += '</tbody>' +
                                                        '<tfoot class="bg-secondary">' +
                                                        '<tr>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Fecha</th>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Creado Por</th>' +
                                                        '<th nowrap="nowrap" align="center" style="vertical-align:middle;">Detalles</th>' +
                                                        '</tr>' +
                                                        '</tfoot>' +
                                                        '</table>' +
                                                        '</div>' +
                                                        '</div>' +
                                                        '<div class="modal-footer">' +
                                                        '<button type="button" class="btn btn-info" data-dismiss="modal"><b>Revisado!</b></button>' +
                                                        '</div>' +
                                                        '</div>' +
                                                        '</div>' +
                                                        '</div>';
                                                    $.ajax({
                                                        url: 'ajax-mostrar-obsSC.php?idSoliCompra=' + row.idSoliCompra,
                                                        method: 'GET',
                                                        success: function(response) {
                                                            //console.log(response);
                                                            $('#modalBody' + row.idSoliCompra).html(response);
                                                        }
                                                    });
                                                    return buttonHTML + modalHTML;
                                                }
                                            },
                                            {"data": "numSoliCompra"},
                                            {"data": "fechaSoliCompra"},
                                            {"data": "personalSoliCompra"},
                                            {"data": "folioSoliCompra"},
                                            {"data": "descripcionSoliCompra"},
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    let estadoSoliCompra = row.estadoSoliCompra.toUpperCase();

                                                    let formHTML = '<form action="soliCompra_sdo_seguimiento.php" method="POST" style="display:inline;" enctype="multipart/form">' +
                                                        '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                        '<button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Actualizar Estado</b></button>' +
                                                        '</form>';

                                                    return estadoSoliCompra + '<br><br>' + formHTML;
                                                }
                                            }
                                            ,
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    if (row.idSoliCompra > 0) {
                                                        return '<form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-SoliCompra-SDO.php" method="post">' +
                                                            '<input type="hidden" name="idSoliCompra" id="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                            '<input type="hidden" name="opcion" id="opcion" value="' + row.opcion2 + '">' +
                                                            '<button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>' +
                                                            '</form>';
                                                    } else {
                                                        return '<center>Error al Cargar Datos.</center>';
                                                    }
                                                }
                                            },
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    if (row.contarCotizacion > 0) {
                                                        return '<form style="margin: 0; text-align: center;" class="" action="" method="post">' +
                                                            '<input type="hidden" name="descargaCotizacion" id="descargaCotizacion" value="' + row.idSoliCompra + '">' +
                                                            '<input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="' + row.folioSoliCompra + '">' +
                                                            '<button type="submit" id="inputMozi" class="btn btn-block bg-green"><i class="fa fa-download"></i></button>' +
                                                            '</form>';
                                                    } else {
                                                        return '<center>No existen<br>Cotizaciones Adjuntas.</center>';
                                                    }
                                                }
                                            },
                                            {
                                                "data": null,
                                                "render": function(data, type, row) {
                                                    if (row.idSoliCompra === 0 || row.idSoliCompra === "" || row.idSoliCompra === " " || row.idSoliCompra === "  ") {
                                                        return "Error al Cargar Datos.";
                                                    } else {
                                                        if (row.estadoSoliCompra === "SOLICITUD ANULADA") {
                                                            return '<form style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarAnularSC_sdo.php" method="post">' +
                                                                '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                                '<button type="submit" id="inputMozi" class="btn btn-block btn-warning"><b><i class="fa fa-eye"></i></b></button>' +
                                                                '</form>';
                                                        } else if (row.estadoSoliCompra === "RECHAZADO POR LA S.D.A") {
                                                            return '<form style="margin: 0; text-align: center;" class="" action="soliCompra_sdo_seguimiento.php" method="post">' +
                                                                '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                                '<button type="submit" id="inputMozi" class="btn btn-block btn-warning"><b><i class="fa fa-eye"></i></b></button>' +
                                                                '</form>';
                                                        } else {
                                                            return '<form style="margin: 0; text-align: center;" class="" action="soliCompra_anularSC_sdo.php" method="post">' +
                                                                '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                                                                '<button type="submit" id="inputMozi" class="btn btn-block btn-danger"><b><i class="fa fa-times"></i></b></button>' +
                                                                '</form>';
                                                        }
                                                    }
                                                }
                                            },
                                            // Otras columnas existentes

                                            // Agregar columnas dinámicamente
                                        ];




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
                                                    console.log(d);
                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": columns,
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

                                            }

                                        });
                                        $('#tableSCSDO').on('draw.dt', function() {
                                            // Obtener todos los botones que abren el modal y establecerles un evento click
                                            $('[data-toggle="modal"]').on('click', function() {
                                                // Obtener el ID de la solicitud de compra
                                                var idSoliCompra = $(this).data('target').split('modalAbastecimiento')[1];
                                                // Realizar la llamada AJAX para cargar los datos del modal
                                                $.ajax({
                                                    url: 'ajax-mostrar-obsSC.php?idSoliCompra=' + idSoliCompra,
                                                    method: 'GET',
                                                    success: function(response) {
                                                        console.log(response);
                                                        $('#modalBody' + idSoliCompra).html(response);
                                                    }
                                                });
                                            });
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Solicitar Compra de Materiales</h5>
                                <hr>
                                <form action="../sdo-funciones/soliCompra_solicitarCompraSDOMesaAyuda.php" method="post" enctype="multipart/form-data">


                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Datos Solicitante</legend>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio2" value="jefes">
                                            <label class="form-check-label" for="inlineRadio2">Encargados y Profesionales</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio3" value="directivos">
                                            <label class="form-check-label" for="inlineRadio3">Directivos</label>
                                        </div>
                                        <br>
                                        <br>

                                        <div class="form-row fieldGroup" id="jefes">


                                            <div class="form-group col-md-3">

                                                <label for="departamentoSolicitante">Departamento: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <select name="departamentoSolicitante" id="departamentoSolicitante" class="form-control">
                                                        <option value="" disabled selected> -- Seleccione --</option>
                                                        <?php

                                                        $queryPersonal = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional' ORDER BY nomDepartamento ASC";
                                                        $res = mysqli_query($conexion, $queryPersonal);

                                                        while ($row = $res->fetch_assoc()) {
                                                            if ($row['idDepartamento'] == $idDepartamento) {
                                                                ?>



                                                                <option value="<?php echo   $row['idDepartamento']; ?>">
                                                                    <?php echo $row['nomDepartamento']; ?>

                                                                </option>


                                                                <?php
                                                            } else {

                                                                ?>
                                                                <option value="<?php echo $row['idDepartamento'] ?>">
                                                                    <?php echo $row['nomDepartamento'] ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }


                                                        ?>
                                                    </select>
                                                    <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->

                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Personal:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <!-- <span class="input-group-text"><i class="fas fa-user"></i></span> -->
                                                    </div>
                                                    <div style="width: 100%;" id="htmlSelect" name="htmlSelect"></div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row fieldGroup" id="directivos">


                                            <div class="form-group col-md-6">

                                                <label for="departamentoSolicitante">Directivo Solicitante : <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <select name="directivo" id="directivo" class="form-control">
                                                        <option value="" disabled selected> -- Seleccione --</option>
                                                        <?php

                                                        $queryDirectivos = "SELECT * FROM directivos";
                                                        $resDirectivos = mysqli_query($conexion, $queryDirectivos);

                                                        while ($rowDirectivos = $resDirectivos->fetch_assoc()) {

                                                            $datos = [];

                                                            $datos['idDirectivo'] = $rowDirectivos['idDirectivo'];
                                                            $datos['nomDirectivo'] = $rowDirectivos['nomDirectivo'];

                                                            $idDirectivo = $datos['idDirectivo'];
                                                            $nomDirectivo = $datos['nomDirectivo'];

                                                            ?>

                                                            <option value="<?php echo $idDirectivo ?>">
                                                                <?php echo $nomDirectivo ?>
                                                            </option>

                                                            <?php
                                                        }


                                                        ?>
                                                    </select>
                                                    <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->

                                                </div>
                                            </div>

                                        </div>

                                        <input type="hidden" name="cargaSolicitante" id="cargaSolicitante" value="">

                                    </fieldset>

                                    <br>

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
                                                <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputRecinto">Articulo:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="articuloSC[]" name="articuloSC[]" placeholder="Articulo">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputCoordenadas">Medida:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control" id="medidaSC[]" name="medidaSC[]" placeholder="Medida">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">Valor Total Referencial:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                </div>
                                                <input type="number" min="0" class="form-control" id="valorSC[]" name="valorSC[]" placeholder="Valor Total Referencial">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                            <div class="input-group mb-3">
                                                <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Material</a>
                                            </div>
                                        </div>
                                    </div>


                                    <br>

                                    <div class="form-group">
                                        <!-- <label for="customFile">Custom File</label> -->
                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                        <div class="custom-file">
                                            <input type="file" max-uploads="40" class="custom-file-input" onchange="mostrarRadio(this)" multiple="" id="adjuntosSC[]" name="adjuntosSC[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                        </div>
                                    </div>

                                    <fieldset class="border p-3" id="radios" style="display: none;">
                                        <h6>¿Es una cotización el archivo adjunto?</h6>
                                        <div class="form-group">

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">Si</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion2" value="0">
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>

                                        </div>
                                    </fieldset>

                                    <script type="text/javascript">
                                        $('.custom-file input').change(function(e) {
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

                                    <div class="form-group">

                                        <input type="hidden" name="idParaCargaProfesional" id="idParaCargaProfesional" value="">



                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                    </div>
                                </form>

                                <div class="form-row fieldGroupCopy" style="display: none;">

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                            </div>
                                            <input type="number" min="1" class="form-control" id="cantidadSC[]" name="cantidadSC[]" placeholder="Cantidad">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="articuloSC[]" name="articuloSC[]" placeholder="Articulo">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                            </div>
                                            <input type="text" min="1" class="form-control" id="medidaSC[]" name="medidaSC[]" placeholder="Medida">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input type="number" min="0" class="form-control" id="valorSC[]" name="valorSC[]" placeholder="Valor Total Referencial">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group mb-3">
                                            <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                                        </div>
                                    </div>
                                </div>


                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        //group add limit
                                        var maxGroup = "100";

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
<!-- Page specific script -->

<script type="text/javascript">
    $("input[name='opciones']").change(function() {
        if ($(this).val() == 'personal') {
            $('#personal').show();
            $('#jefes').hide();
            $('#directivos').hide();

            document.getElementById('cargaSolicitante').value = 'Personal';

        } else if ($(this).val() == 'jefes') {
            $('#personal').hide();
            $('#jefes').show();
            $('#directivos').hide();

            document.getElementById('cargaSolicitante').value = 'Jefe';

        } else {
            $('#personal').hide();
            $('#jefes').hide();
            $('#directivos').show();

            document.getElementById('cargaSolicitante').value = 'Directivo';
        }
    })
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
</body>

</html>