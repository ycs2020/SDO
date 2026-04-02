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
            $dir = '../sdo-solicCompra/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-admtec/";
            $archivoZip = "Cotizaciones " . $folioSoliCompraDescarga . ".zip";

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
    <link rel="stylesheet" href="../dist/css/modelo.css">

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
        }#jefecontrol {
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
    include '../sdo-templates/admtec-sidebar.php';
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

                                <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            N° Interno - SDO
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
                                            <?php if ($row['fechaSoliCompra'] == 0) {
                                                echo "No registra fecha";
                                            } else {

                                                $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
                                                $inicioHora = date('H:i', strtotime($row['fechaSoliCompra']));

                                                echo $inicioFecha . '&nbsp;<br>' . $inicioHora;
                                            } ?>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $personalSoliCompra = mb_strtoupper($row['personalSoliCompra']);

                                            $personalSoliCompra = explode(' (', $personalSoliCompra);

                                            $unidadSoliCompra = mb_strtoupper($row['unidadSoliCompra']);

                                            if (empty($personalSoliCompra[1])) {
                                                echo $personalSoliCompra[0] . '&nbsp;<br>(' . $unidadSoliCompra . ')';
                                            } else {
                                                echo $personalSoliCompra[0] . '&nbsp;<br>(' . $personalSoliCompra[1] . '&nbsp;<br>(' . $unidadSoliCompra . ')';
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
                                            $estadoSoliCompra = mb_strtoupper($row['estadoSoliCompra']);
                                            echo $estadoSoliCompra;
                                            ?>
                                            <br>
                                            <br>
                                            <!-- Large modal -->
                                            <form action="soliCompra_sdo_seguimiento.php" method="POST" style="display:inline;" enctype="multipart/form">
                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra ?>">
                                                <button type="submit" class="btn btn-xs btn-warning"><b><i class="fa fa-edit"></i>&nbsp;&nbsp;Actualizar Estado</b></button>
                                            </form>


                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">

                                            <?php
                                            $idSoliCompra = $row['idSoliCompra'];
                                            $idSoliCompra = trim($idSoliCompra);
                                            if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                ?>
                                                <?php echo "Error al Cargar Datos."; ?>
                                                <?php
                                            } else {

                                                ?>

                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="soliCompra_visualizarSC_sdo.php" method="post">

                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"> <b> <i class="fa fa-eye"></i></i></b></button>

                                                </form>

                                                <?php

                                            }
                                            ?>



                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">

                                            <?php
                                            $idSoliCompra = $row['idSoliCompra'];
                                            $idSoliCompra = trim($idSoliCompra);
                                            if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                ?>
                                                <?php echo "Error al Cargar Datos."; ?>
                                                <?php
                                            } else {
                                                $contarCotizacionesSoliCompraSDO = contarCotizacionesSoliCompraSDO($idSoliCompra);

                                                if ($contarCotizacionesSoliCompraSDO < 1) {
                                                    echo "No existen " . ' <br>  ' . "Cotizaciones Adjuntas.";
                                                } else {
                                                    ?>
                                                    <form action="soliCompra_sdo_administracion.php" style="margin: 0; text-align: center;" class="" method="post">

                                                        <input type="hidden" name="descargaCotizacion" value="<?php echo $idSoliCompra; ?>">
                                                        <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="<?php echo $folioSoliCompra; ?>">
                                                        <button type="submit" id="inputMozi" class="btn btn-block bg-green"> <b><i class="fa fa-download"></i></b> </button>

                                                    </form>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                            <?php
                                            $idSoliCompra = $row['idSoliCompra'];
                                            $idSoliCompra = trim($idSoliCompra);
                                            if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                ?>
                                                <?php echo "Error al Cargar Datos."; ?>
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
                                        </td>


                                        <?php

                                        $numSolicitud = $numSolicitud + 1;
                                        }
                                        ?>

                                    </tr>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                            N° Interno - SDO
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
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio4" value="jefecontrol">
                                            <label class="form-check-label" for="inlineRadio4">Jefe Control Centralizado</label>
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
                                        <div class="form-row fieldGroup" id="jefecontrol">
                                        </div>

                                        <input type="hidden" name="cargaSolicitante" id="cargaSolicitante" value="">

                                    </fieldset>

                                    <br>

                                    <div class="form-group">
                                        <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code></label>
                                        <textarea id="argumentosNecesidad" style="resize:none;" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud"></textarea>
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
                                                <input class="form-check-input " type="radio" name="isCotizacion"   id="isCotizacion1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">Si</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="radio" name="isCotizacion"  id="isCotizacion2" value="0">
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

<!-- jQuery -->
<script src="js/dark-mode"></script>
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
            "buttons": [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'SOLICITUDES DE COMPRA - SDO',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            }],
            "columnDefs": [{
                target: 5,
                visible: false,
            }, ],
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
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<script type="text/javascript">
    $("input[name='opciones']").change(function() {
        if ($(this).val() == 'personal') {
            $('#personal').show();
            $('#jefes').hide();
            $('#directivos').hide();
            $('#jefecontrol').hide();

            document.getElementById('cargaSolicitante').value = 'Personal';

        } else if ($(this).val() == 'jefes') {
            $('#personal').hide();
            $('#jefes').show();
            $('#directivos').hide();
            $('#jefecontrol').hide();

            document.getElementById('cargaSolicitante').value = 'Jefe';

        } else if ($(this).val() == 'jefecontrol') {
            $('#personal').hide();
            $('#jefes').hide();
            $('#directivos').hide();
            $('#jefecontrol').show();

            document.getElementById('cargaSolicitante').value = 'JefeCC';

        } else {
            $('#personal').hide();
            $('#jefes').hide();
            $('#directivos').show();
            $('#jefecontrol').hide();

            document.getElementById('cargaSolicitante').value = 'Directivo';
        }
    })
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
<script src="js/dark-mode.js"></script>
</body>

</html>