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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


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

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
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
                            <h4>Solicitudes de Compras - SDO</h4>

                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" style="color:black;">Inicio</a></li>
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

                                                        let formHTML = '<form action="soliCompra_sdo_seguimiento.php" method="POST" style="display:inline;" enctype="multipart/form">' +
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>2.- Solicitar Compra de Materiales</h5>
                                    <hr>
                                    <form id="formGuardarSC" action="../sdo-funciones/soliCompra_generarSCSDO.php" method="post" enctype="multipart/form-data">

                                        <div class="form-row">
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">
                                                <label for="exampleInputArgumentos">Descripci&oacute;n de Solicitud (Ejemplo: Solicitud de Herramientas):<code>*</code></label>
                                                <input type="text" class="form-control" maxlength="60" id="descripcionSoliCompra" name="descripcionSoliCompra" required placeholder="Descripción Solicitud">
                                            </div>
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">
                                                <label for="exampleInputArgumentos">Ingrese N&uacute;mero de Ticket SIGH: <code>Si desea agregar mas de 1 ticket, coloque un guion (-) entre los tickets</code></label>
                                                <input type="text" class="form-control" maxlength="20" id="ticketSIGH" name="ticketSIGH" placeholder="Ticket SIGH">
                                            </div>
                                        </div>
                                        <br>


                                        <!-- AQUI PABLO CARACTERES -->
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


                                        <br><br>

                                        <div class="form-group text-right">

                                            <button type="button" class="bg-primary btn" id="agregarFila"><i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Material</button>
                                        </div>

                                        <br><br>


                                        <div class="table-responsive">
                                            <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE MATERIAL:</td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MEDIDA:</td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">VALOR TOTAL REFERENCIAL:</td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                                </tr>
                                                </thead>
                                                <tbody id="miTbody">
                                                <tr>
                                                    <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        <input type="text" class="form-control" name="articuloSC1" placeholder="Articulo">
                                                    </td>
                                                    <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        <input type="number" min="1" class="form-control" id="cantidadSC1" name="cantidadSC1" placeholder="Cantidad">
                                                    </td>
                                                    <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        <input type="text" min="1" class="form-control" id="medidaSC1" name="medidaSC1" placeholder="Medida">
                                                    </td>
                                                    <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        <input type="number" min="0" class="form-control" id="valorSC1" oninput="sumarValores()" onchange="sumarValores()" name="valorSC1" placeholder="Valor Total Referencial">
                                                    </td>
                                                    <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        No se puede eliminar
                                                    </td>
                                                </tr>

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th style="font-weight: bold; font-size: 16px; text-align: right;" colspan="3">TOTAL : (Valor no se incluye en Solicitud de Compra)</th>
                                                    <th style="font-weight: bold; font-size: 16px;" id="thValorTotal">TOTAL</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
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

                                        <div class="form-group">
                                            <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                                            <input type="hidden" name="cargoSC" id="cargoSC" value="Encargado de Bodega Pañol">
                                            <input type="hidden" name="deptoSC" id="deptoSC" value="Subdirección de Operaciones">
                                            <input type="hidden" name="emailSC" id="emailSC" value="bodegapanol@gmail.com">
                                            <input type="hidden" name="idSolicitudSoporte" id="idSolicitudSoporte" value="0">
                                            <input type="hidden" name="cargaSolicitante" id="cargaSolicitante" value="PerfilEncargado">
                                            <input type="submit" id="btn-guardarSC" name="btn-guardarSC" class="btn btn-block btn-success btn-guardarSC" value="Guardar"/>

                                        </div>
                                        <script>
                                            // Selecciona el botón Guardar
                                            const btnGuardar = document.getElementById('btn-guardarSC');

                                            // Agrega un event listener para el clic en el botón Guardar
                                            btnGuardar.addEventListener('click', function (event) {
                                                // Previene el comportamiento por defecto del botón
                                                event.preventDefault();

                                                // Muestra el mensaje de confirmación
                                                Swal.fire({
                                                    title: '¿Estás seguro?',
                                                    text: '¿Deseas generar la Solicitud de Compra?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Guardar',
                                                    cancelButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    // Si el usuario confirma, envía el formulario
                                                    if (result.isConfirmed) {
                                                        btnGuardar.disabled = true;

                                                        document.getElementById('formGuardarSC').submit(); // Reemplaza 'formGuardarSC' con el ID de tu formulario
                                                    }
                                                });
                                            });
                                        </script>
                                    </form>

                                </div>

                            </div>

                        </div>


                        <script>
                            const tabla = document.getElementById('miTabla');
                            const btnAgregarFila = document.getElementById('agregarFila');
                            let correlativo = 2;
                            var tbody = document.getElementById('miTbody');

                            let valorTotalAcumulado = 0;


                            btnAgregarFila.addEventListener('click', () => {
                                contenido = '';
                                const nuevaFila = document.createElement('tr');
                                contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">' +
                                    '<input type="text" class="form-control" name="articuloSC' + correlativo + '" placeholder="Articulo">' +
                                    '</td>';

                                contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="1" class="form-control" id="cantidadSC' + correlativo + '" name="cantidadSC' + correlativo + '" placeholder="Cantidad"><input type="hidden" class="form-control" name="valorUnit' + correlativo + '" id="valorUnit' + correlativo + '" value="0"></td>';

                                contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" min="1" class="form-control" id="medidaSC' + correlativo + '" name="medidaSC' + correlativo + '" placeholder="Medida"></td>';

                                contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="0" oninput="sumarValores()" onchange="sumarValores()" class="form-control" id="valorSC' + correlativo + '" name="valorSC' + correlativo + '" placeholder="Valor Total Referencial"></td>';

                                contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';

                                nuevaFila.innerHTML = contenido;

                                tabla.querySelector('tbody').appendChild(nuevaFila);
                                correlativo++; // Incrementar el contador

                                var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
                                document.getElementById('cantidadFilas').value = cantidadDeFilas2; // Actualizar la cantidad de filas en un campo oculto
                            });


                            function eliminarFila(boton) {
                                const fila = boton.closest('tr');
                                fila.remove();
                                // Actualiza los correlativos después de eliminar una fila
                                correlativo--;
                                // Obtén una referencia al elemento <tbody> por su id

                                // Cuenta la cantidad de elementos <tr> dentro del <tbody>
                                var cantidadDeFilas = tbody.getElementsByTagName('tr').length;

                                if (cantidadDeFilas < 1) {
                                    document.getElementById("enviarFormulario").disabled = true;
                                }
                                var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

                                document.getElementById('cantidadFilas').value = cantidadDeFilas2;
                                sumarValores();
                            }

                            function actualizarCorrelativos() {
                                const filas = tabla.querySelectorAll('tbody tr');
                                filas.forEach((fila, index) => {
                                    const correlativoCell = fila.querySelector('td:first-child');
                                    correlativoCell.textContent = index + 1;
                                });
                            }
                            function sumarValores() {
                                // Obtener todas las filas de la tabla
                                const filas = document.querySelectorAll("#miTbody tr");

                                let total = 0;

                                // Iterar sobre cada fila y sumar el valorSC de cada una
                                filas.forEach((fila) => {
                                    const valorSC = fila.querySelector("input[name^='valorSC']").value;
                                    // Convertir el valorSC a número y sumarlo al total
                                    total += parseFloat(valorSC) || 0;

                                });

                                const total2 = total.toLocaleString('es-CL', {
                                    style: 'currency',
                                    currency: 'CLP'
                                });

                                // Actualizar el total en el thValorTotal
                                document.getElementById('thValorTotal').textContent = total2;
                            }

                            // Llamar a la función sumarValores al cargar la página para mostrar el total inicial
                            sumarValores();

                        </script>


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
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
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
</body>

</html>