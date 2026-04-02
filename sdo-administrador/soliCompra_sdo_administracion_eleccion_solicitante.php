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
            $descargaCotizacion = str_pad($descargaCotizacion, 4, "0", STR_PAD_LEFT);


            $folioSoliCompra2 = "SC-SDO-" . $descargaCotizacion;

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
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            recargarListTecnicos();
            recargarListTecnicosPersonal();

            $('#departamentoSolicitante').change(function () {
                recargarListTecnicos();

            });

            $('#depaPersonal').change(function () {
                recargarListTecnicosPersonal();

            });


            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicos1.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function (r) {

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
                    success: function (r) {

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
                                <a href="../sdo-pdf/SDO-EXCEL-SOLIC_COMPRA.php" style="float: left" class="btn btn-sm bg-success"><i class="fas fa-file-excel"></i> Exportar en Excel</a>
                                <br>
                                <table style="font-size:10pt; width:100%;" id="example2" class="table table-bordered table-hover text-center">
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
                                            <form action="soliCompra_sdo_seguimiento.php" target="_blank" method="POST" style="display:inline;" enctype="multipart/form">
                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra ?>">
                                                <button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Detalles</b></button>
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
                                                    <button type="submit" id="inputMozi" class="btn btn-xs bg-pink"><b> <i class="fas fa-eye"></i></b></button>
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
                                <form id="formGuardarSC" action="../sdo-funciones/soliCompra_generarSCSDO.php" method="post" enctype="multipart/form-data">


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

                                                        $queryPersonal = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE 'Control Centralizado' AND nomDepartamento NOT LIKE 'Informática' ORDER BY nomDepartamento ASC";
                                                        $res = mysqli_query($conexion, $queryPersonal);

                                                        while ($row = $res->fetch_assoc()) {
                                                            if ($row['idDepartamento'] == $idDepartamento) {
                                                                ?>
                                                                <option value="<?php echo $row['idDepartamento']; ?>">
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

                                    <div class="form-row">
                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                            <label for="exampleInputArgumentos">Descripci&oacute;n de Solicitud (Ejemplo: Solicitud de Herramientas):<code>*</code></label>
                                            <input type="text" class="form-control" maxlength="60" id="descripcionSoliCompra" name="descripcionSoliCompra" required placeholder="Descripción Solicitud">
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                            <label for="exampleInputArgumentos">Ingrese N&uacute;mero de Ticket SIGH:</label>
                                            <input type="text" class="form-control" maxlength="20" id="ticketSIGH" name="ticketSIGH" placeholder="Ticket SIGH">
                                        </div>
                                    </div>

                                    <br>


<!--                                    <div class="form-row">-->
<!--                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">-->
<!--                                            <label for="selectSubAsignacion">Sub-Asignaci&oacute;n:</label>-->
<!--                                            <select class="form-control" name="selectSubAsignacion" id="selectSubAsignacion">-->
<!--                                                <option value="Seleccione" selected disabled>Seleccione...</option>-->
<!--                                                --><?php
//                                                $sqlSubAsignacion = "SELECT * FROM subasignaciones_presupuesto ORDER BY nomSubAsigna ASC";
//
//
//                                                $resSubAsignacion = mysqli_query($conexion, $sqlSubAsignacion);
//
//
//                                                if(mysqli_num_rows($resSubAsignacion) > 0) {
//                                                    while ($rowSubAsignacion = $resSubAsignacion->fetch_assoc()) {
//                                                        $nomSubAsigna = $rowSubAsignacion['nomSubAsigna'];
//
//                                                        if(!empty($nomSubAsigna)){
//                                                            $nomSubAsigna2 = mb_convert_case($nomSubAsigna,MB_CASE_UPPER,'UTF-8');
//                                                            echo "<option value='".$rowSubAsignacion['idSubAsigna']."'>".$nomSubAsigna2."</option>";
//                                                        }
//
//                                                    }
//                                                }
//
//                                                ?>
<!---->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <br>-->
                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code> <p id="contadorCaracteres">(Caracteres restantes: 1271)</p></label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" oninput="limitarCaracteres(this)" maxlength="1271" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud (Máximo 1271 Caracteres)"></textarea>


                                        <script>
                                            function limitarCaracteres(element) {
                                                var maxLength = parseInt(element.getAttribute("maxlength"));
                                                var currentLength = element.value.length;
                                                var remaining = maxLength - currentLength;
                                                document.getElementById("contadorCaracteres").innerText = "Caracteres restantes: " + remaining;
                                                if (currentLength > maxLength) {
                                                    element.value = element.value.substring(0, maxLength);
                                                    document.getElementById("contadorCaracteres").innerText = "Caracteres restantes: 0";
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
                                                    <input class="form-control" list="datalistOptions" id="exampleDataList" name="articuloSC1" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" placeholder="Articulo...">
                                                    <datalist id="datalistOptions">
                                                        <?php echo listadoInventarioDataList(); ?>

                                                    </datalist>
                                                </td>
                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                    <input type="number" min="1" class="form-control" id="cantidadSC1" onchange="calcularSuma(this)" oninput="calcularSuma(this)" name="cantidadSC1" placeholder="Cantidad">
                                                    <input type="hidden" class="form-control" name="valorUnit1" id="valorUnit1" value="0">
                                                </td>

                                                <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                    <input type="text" min="1" class="form-control" id="medidaSC1" name="medidaSC1" placeholder="Medida">
                                                </td>
                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                    <input type="number" min="0" class="form-control" id="valorSC1" name="valorSC1" placeholder="Valor Total Referencial">
                                                </td>
                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                    No se puede eliminar
                                                </td>
                                            </tr>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="font-weight: bold; font-size: 16px; text-align: right;" colspan="3">TOTAL : (Valor no se incluye en Solicitud de Compra)</th>
                                                <th style="font-weight: bold; font-size: 16px;" id="thValorTotal"></th>
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
                                        <input type="hidden" name="idParaCargaProfesional" id="idParaCargaProfesional" value="">
                                        <input type="submit" id="btn-guardarSC" name="btn-guardarSC" class="btn btn-block btn-success btn-guardarSC" value="Guardar"/>
                                    </div>
                                    <script>
                                        // Selecciona el botón Guardar
                                        const btnGuardar = document.getElementById('btn-guardarSC');

                                        // Agrega un event listener para el clic en el botón Guardar
                                        btnGuardar.addEventListener('click', function(event) {
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

                                <script>
                                    const tabla = document.getElementById('miTabla');
                                    const btnAgregarFila = document.getElementById('agregarFila');
                                    let correlativo = 2;
                                    var tbody = document.getElementById('miTbody');

                                    btnAgregarFila.addEventListener('click', () => {
                                        contenido = '';
                                        const nuevaFila = document.createElement('tr');
                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">' +
                                            '<input type="text" class="form-control" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" name="articuloSC' + correlativo + '" list="opcionesArticulo' + correlativo + '" placeholder="Articulo">'+
                                            '<datalist id="opcionesArticulo' + correlativo + '"><?php echo listadoInventarioDataList(); ?></datalist>'+
                                            '</td>';

                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="1" onchange="calcularSuma(this)" oninput="calcularSuma(this)"  class="form-control" id="cantidadSC' + correlativo + '" name="cantidadSC' + correlativo + '" placeholder="Cantidad"><input type="hidden" class="form-control" name="valorUnit' + correlativo + '" id="valorUnit' + correlativo + '" value="0"></td>';

                                        contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" min="1" class="form-control" id="medidaSC' + correlativo + '" name="medidaSC' + correlativo + '" placeholder="Medida"></td>';

                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="0" class="form-control" id="valorSC' + correlativo + '" name="valorSC' + correlativo + '" placeholder="Valor Total Referencial"></td>';

                                        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';

                                        nuevaFila.innerHTML = contenido;

                                        tabla.querySelector('tbody').appendChild(nuevaFila);
                                        correlativo++; // Incrementar el contador

                                        var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
                                        document.getElementById('cantidadFilas').value = cantidadDeFilas2; // Actualizar la cantidad de filas en un campo oculto
                                    });
                                    function limpiarMedida(input) {
                                        const selectedOption = input.value;
                                        const dataList = input.list;
                                        const options = dataList.options;

                                        // Obtener el número de correlativo del id del input
                                        const correlativo = input.name.replace('articuloSC', '');

                                        const medidaInput = document.getElementById('medidaSC' + correlativo);
                                        const valorUnitInput = document.getElementById('valorUnit' + correlativo);

                                        // Verificar si el valor ingresado coincide con algún valor del datalist
                                        let coincide = false;
                                        for (let i = 0; i < options.length; i++) {
                                            if (options[i].text === selectedOption) {
                                                coincide = true;
                                                break;
                                            }
                                        }

                                        // Si no coincide, limpiar el valor del input medidaSC
                                        if (!coincide) {
                                            medidaInput.value = '';
                                            valorUnitInput.value = '';
                                        }
                                    }

                                    function cambiarUnidad(input) {
                                        const selectedOption = input.value;
                                        const dataList = input.list;
                                        const options = dataList.options;

                                        // Obtener el número de correlativo del id del input
                                        const correlativo = input.name.replace('articuloSC', '');

                                        const medidaInput = document.getElementById('medidaSC' + correlativo);

                                        const valorUnitInput = document.getElementById('valorUnit' + correlativo);

                                        // Buscar la opción seleccionada y obtener su unidad
                                        for (let i = 0; i < options.length; i++) {
                                            if (options[i].text === selectedOption) {
                                                const unidad = options[i].getAttribute('data-unidad');
                                                const valorSin = options[i].getAttribute('data-valorSin');
                                                medidaInput.value = unidad; // Asignar la unidad al input de medida correspondiente
                                                valorUnitInput.value = valorSin;
                                                break;
                                            }
                                        }


                                        // Si no coincide, limpiar el valor del input medidaSC
                                        if (!coincide) {
                                            medidaInput.value = '';
                                            valorUnitInput.value = '';
                                        }
                                    }

                                    function calcularSuma(input) {
                                        const selectedOption = input.value;

                                        // Obtener el número de correlativo del id del input
                                        const correlativo = input.name.replace('cantidadSC', '');

                                        const valorSC = document.getElementById('valorSC' + correlativo);

                                        const valorUnitInput = document.getElementById('valorUnit' + correlativo).value;

                                        const valorTotal = selectedOption * valorUnitInput;

                                        var valorFinal = Math.floor(valorTotal * 1.19);

                                        valorSC.value = valorFinal;
                                        actualizarValorTotal();
                                    }

                                    function actualizarValorTotal() {
                                        let valorTotal = 0;

                                        // Obtener todas las filas de la tabla
                                        const filas = document.querySelectorAll('#miTabla tbody tr');

                                        // Iterar sobre cada fila para calcular el valor total
                                        filas.forEach((fila, index) => {
                                            const cantidadInput = fila.querySelector('input[name^="cantidadSC"]');
                                            const valorUnitarioInput = fila.querySelector('input[name^="valorUnit"]');
                                            const cantidad = parseInt(cantidadInput.value);
                                            const valorUnitario = parseFloat(valorUnitarioInput.value);

                                            if (cantidad > 0) {

                                                valorTotal += cantidad * valorUnitario;
                                            } else {

                                                valorTotal += 0;
                                            }

                                        });

                                        // Calcular el valor total final con impuestos
                                        const valorTotalConImpuestos = Math.floor(valorTotal * 1.19);

                                        const valorTotalConImpuestos2 = valorTotalConImpuestos.toLocaleString('es-CL', {
                                            style: 'currency',
                                            currency: 'CLP'
                                        });

                                        // Actualizar el contenido del th con el valor total
                                        document.getElementById('thValorTotal').textContent = valorTotalConImpuestos2;
                                    }

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
                                    }

                                    function actualizarCorrelativos() {
                                        const filas = tabla.querySelectorAll('tbody tr');
                                        filas.forEach((fila, index) => {
                                            const correlativoCell = fila.querySelector('td:first-child');
                                            correlativoCell.textContent = index + 1;
                                        });
                                    }


                                </script>

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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function () {
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
    $("input[name='opciones']").change(function () {
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
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {
            'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
            'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('#reservationdatetime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function (event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function () {
            myDropzone.enqueueFile(file)
        }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End

</script>
</body>

</html>