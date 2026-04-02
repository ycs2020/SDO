<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];

$rolUsuario = $_SESSION['rolUsuario'];


if ($rolUsuario == 'Administrador') {

    $idDepartamento = 1;
}

$descargaConfirma = "";
if (isset($_POST['idHojaDesc'])) {
    $idHojaDesc = $_POST['idHojaDesc'];
    $folioDesc = trim($_POST['folioDesc']);
    $idUnico = $_POST['idUnico'];

    if ($idHojaDesc > 0) {

        if (empty($folioDesc) || $folioDesc == '') {
        } else {

            $folioSoliCompra2 = "EQUIPO-" . $idUnico;

            echo $folioSoliCompra2;
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = '../sdo-archivosHojasVida/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Archivos-" . $folioSoliCompra2 . ".zip";

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                if (file_exists($rutaFinal . "/" . $archivoZip)) {
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
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} {
}

if (!empty($_POST['idHoja']) || !empty($_GET['idHoja'])) {

    if ($_POST['idHoja']) {
        $idHoja = $_POST['idHoja'];
    } else {
        $idHoja = $_GET['idHoja'];
    }

    //TRAEMOS LOS DATOS DE LA HOJA
    $sqlHoja = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $resHoja = mysqli_query($conexion, $sqlHoja);

    while ($rowHoja = $resHoja->fetch_assoc()) {

        $datos = [];

        $datos['idHoja'] = $rowHoja['idHoja'];

        $datos['estadoActual'] = $rowHoja['estadoActual'];

        $datos['idUnico'] = $rowHoja['idUnico'];
        $datos['fechaCreacion'] = $rowHoja['fechaCreacion'];
        $datos['folio'] = $rowHoja['folio'];
        $datos['numInventario'] = $rowHoja['numInventario'];
        $datos['fechaRecepcion'] = $rowHoja['fechaRecepcion'];
        $datos['nomEquipo'] = $rowHoja['nomEquipo'];
        $datos['valorConIva'] = $rowHoja['valorConIva'];
        $datos['marca'] = $rowHoja['marca'];
        $datos['idLicitacion'] = $rowHoja['idLicitacion'];
        $datos['modelo'] = $rowHoja['modelo'];
        $datos['ordenCompra'] = $rowHoja['ordenCompra'];
        $datos['numSerie'] = $rowHoja['numSerie'];
        $datos['fechaCompra'] = $rowHoja['fechaCompra'];
        $datos['fechaMarcha'] = $rowHoja['fechaMarcha'];
        $datos['idServicio'] = $rowHoja['idServicio'];
        $datos['recinto'] = $rowHoja['recinto'];
        $datos['textoAccesorios'] = $rowHoja['textoAccesorios'];
        $datos['textoInsumos'] = $rowHoja['textoInsumos'];
        $datos['nomProveedor'] = $rowHoja['nomProveedor'];
        $datos['direccionProveedor'] = $rowHoja['direccionProveedor'];
        $datos['telProveedor'] = $rowHoja['telProveedor'];
        $datos['emailProveedor'] = $rowHoja['emailProveedor'];
        $datos['nomContacto'] = $rowHoja['nomContacto'];
        $datos['observaciones'] = $rowHoja['observaciones'];
        $datos['idCreadoPor'] = $rowHoja['idCreadoPor'];
        $datos['idDepaCreador'] = $rowHoja['idDepaCreador'];
        $datos['tipoUsuario'] = $rowHoja['tipoUsuario'];
        $datos['textoPautas'] = $rowHoja['textoPautas'];
        $datos['textoCertificados'] = $rowHoja['textoCertificados'];
        $datos['textoManuales'] = $rowHoja['textoManuales'];
        $datos['textoOtros'] = $rowHoja['textoOtros'];
        $datos['claseEquipo'] = $rowHoja['claseEquipo'];
        $datos['subClaseEquipo'] = $rowHoja['subClaseEquipo'];
        $datos['compleEquipo'] = $rowHoja['compleEquipo'];
        $datos['nivelRiesgo'] = $rowHoja['nivelRiesgo'];
        $datos['ahnoFabricacion'] = $rowHoja['ahnoFabricacion'];
        $datos['vidaUtil'] = $rowHoja['vidaUtil'];
        $datos['versionSoftware'] = $rowHoja['versionSoftware'];
        $datos['paisOrigen'] = $rowHoja['paisOrigen'];
        $datos['terminoGarantia'] = $rowHoja['terminoGarantia'];
        $datos['costoAnualMantenimiento'] = $rowHoja['costoAnualMantenimiento'];
        $datos['frecuenciaMantenimiento'] = $rowHoja['frecuenciaMantenimiento'];
        $datos['estadoActual'] = $rowHoja['estadoActual'];
        $datos['idEquipoPadre'] = $rowHoja['idEquipoPadre'];

        $estadoActual = $datos['estadoActual'];

        $idHoja = $datos['idHoja'];
        $idUnico = $datos['idUnico'];
        $fechaCreacion = $datos['fechaCreacion'];
        $folio = $datos['folio'];
        $numInventario = $datos['numInventario'];
        $fechaRecepcion = $datos['fechaRecepcion'];
        $nomEquipo = $datos['nomEquipo'];
        $valorConIva = $datos['valorConIva'];
        $marca = $datos['marca'];
        $idLicitacion = $datos['idLicitacion'];
        $modelo = $datos['modelo'];
        $ordenCompra = $datos['ordenCompra'];
        $numSerie = $datos['numSerie'];
        $fechaCompra = $datos['fechaCompra'];
        $fechaMarcha = $datos['fechaMarcha'];
        $idServicio = $datos['idServicio'];
        $recinto = $datos['recinto'];
        $textoAccesorios = $datos['textoAccesorios'];
        $textoInsumos = $datos['textoInsumos'];
        $nomProveedor = $datos['nomProveedor'];
        $direccionProveedor = $datos['direccionProveedor'];
        $telProveedor = $datos['telProveedor'];
        $emailProveedor = $datos['emailProveedor'];
        $nomContacto = $datos['nomContacto'];
        $observaciones = $datos['observaciones'];
        $idCreadoPor = $datos['idCreadoPor'];
        $idDepaCreador = $datos['idDepaCreador'];
        $tipoUsuario = $datos['tipoUsuario'];
        $textoPautas = $datos['textoPautas'];
        $textoCertificados = $datos['textoCertificados'];
        $textoManuales = $datos['textoManuales'];
        $textoOtros = $datos['textoOtros'];
        $claseEquipo = $datos['claseEquipo'];
        $subClaseEquipo = $datos['subClaseEquipo'];
        $compleEquipo = $datos['compleEquipo'];
        $nivelRiesgo = $datos['nivelRiesgo'];
        $ahnoFabricacion = $datos['ahnoFabricacion'];
        $vidaUtil = $datos['vidaUtil'];
        $versionSoftware = $datos['versionSoftware'];
        $paisOrigen = $datos['paisOrigen'];
        $costoAnualMantenimiento = $datos['costoAnualMantenimiento'];
        $frecuenciaMantenimiento = $datos['frecuenciaMantenimiento'];
        $estadoActual = $datos['estadoActual'];
        $idEquipoPadre = $datos['idEquipoPadre'];
    }

    //OBTIENE NOMBRE DEL CREADOR

    if ($tipoUsuario == "personal") {

        $sql = "SELECT * FROM personal WHERE idPersonal  = $idCreadoPor";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomPersonal'] = $row['nomPersonal'];
            $creadoPor = $datos['nomPersonal'];
        }
    } else if ($tipoUsuario == "administrativo") {
        $creadorPor = 'SIN INFORMACIÓN';
    } else if ($tipoUsuario == "bodega pañol") {
        $tipoUsuario = 'bodega pañol';
        $ruta = "sdo-bodega-panol";
    } else if ($tipoUsuario == "jefestecnicos") {

        $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe  = $idCreadoPor";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomJefe'] = $row['nomJefe'];
            $creadoPor = $datos['nomJefe'];
        }
    } else if ($tipoUsuario == "administrador") {

        $sql = "SELECT * FROM administracion WHERE idAdministrador = $idCreadoPor";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomAdministrador'] = $row['nomAdministrador'];
            $creadoPor = $datos['nomAdministrador'];
        }
    } else if ($tipoUsuario == "directiva") {
        $creadorPor = 'SIN INFORMACIÓN';
    } else {
        $creadorPor = 'SIN INFORMACIÓN';
    }
    $fechaFormateada = date("d/m/Y", strtotime($fechaCreacion));

    //Nombre del servicio 
    $sqlServicio = "SELECT * FROM servicios WHERE idServicio = $idServicio";
    $resServicio = mysqli_query($conexion, $sqlServicio);

    while ($rowServicio = $resServicio->fetch_assoc()) {
        $datos = [];
        $datos['nomServicio'] = $rowServicio['nomServicio'];
        $nomServicio = $datos['nomServicio'];
    }

    /* =========CODIGO PHP PARA EL DASHBOARD============= */
    $sqlOrdenes = "SELECT COUNT(*) as cantidad FROM ordenes_trabajos_general WHERE idEquipo = $idHoja";
    $resOrdenes = mysqli_query($conexion, $sqlOrdenes);

    while ($rowOrdenes = $resOrdenes->fetch_assoc()) {
        $datos = [];
        $datos['cantidad'] = $rowOrdenes['cantidad'];
        $cantidad = $datos['cantidad'];
    }

    $sqlHojasTec = "SELECT COUNT(*) as cantidadHojaTec FROM ordenes_trabajos_general WHERE idEquipo = $idHoja";
    $resHojasTec = mysqli_query($conexion, $sqlHojasTec);

    while ($rowHojasTec = $resHojasTec->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojaTec'] = $rowHojasTec['cantidadHojaTec'];
        $cantidadHojaTec = $datos['cantidadHojaTec'];
    }
} else {
    header('Location: administrar-equipos.php?noId');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Equipo</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();
            recargarListTecnicos();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
                recargarListTecnicos();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
            });


            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);


                    }
                });

            };

            function recargarNomDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);


                    }
                });

            };


            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicos.php",
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
        });
    </script>
    <style>
        .ocultar-manual {
            display: none;
        }

        .ocultar-accesorio {
            display: none;
        }

        .ocultar-pauta {
            display: none;
        }

        .ocultar-certificados {
            display: none;
        }

        .ocultar-otros {
            display: none;
        }
    </style>
    <style>
        #tooltip {
            background: #8000ff;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            font-size: 13px;
            border-radius: 4px;
            display: none;
        }

        #tooltip[data-show] {
            display: block;
        }

        #arrow,
        #arrow::before {
            position: absolute;
            width: 8px;
            height: 8px;
            background: inherit;
        }

        #arrow {
            visibility: hidden;
        }

        #arrow::before {
            visibility: visible;
            content: '';
            transform: rotate(45deg);
        }

        #tooltip[data-popper-placement^='top']>#arrow {
            bottom: -4px;
        }

        #tooltip[data-popper-placement^='bottom']>#arrow {
            top: -4px;
        }

        #tooltip[data-popper-placement^='left']>#arrow {
            right: -4px;
        }

        #tooltip[data-popper-placement^='right']>#arrow {
            left: -4px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h1 class="card-title" style="font-size:15px"> <b>Información General del equipo</b></h1>
                        </div>

                        <!-- AVISOS -->
                        <?php

                        if (isset($_GET['exito'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha modificado los datos del equipo exitosamente',
                                })
                            </script>

                        <?php
                        }

                        if (isset($_GET['noIdHoja'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'ID del hoja de vida no válido',
                                })
                            </script>

                        <?php
                        }

                        if (isset($_GET['noCambio'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No fue posible cambiar el estado del equipo',
                                })
                            </script>

                        <?php
                        }


                        if (isset($_GET['exitoCambioEstado'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'El estado del equipo fue actualizado exitosamente',
                                })
                            </script>

                        <?php
                        }


                        if (isset($_GET['errorCambioEstado'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No fue posible hacer el cambio de estado',
                                })
                            </script>

                        <?php
                        }


                        if (isset($_GET['noHijos'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No fue posible añadir los equipos hijos',
                                })
                            </script>

                        <?php
                        }

                        if (isset($_GET['errorHijos'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No fue posible actualizar los equipos hijos',
                                })
                            </script>

                        <?php
                        }

                        if (isset($_GET['noGasto'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al ver los gastos',
                                })
                            </script>

                        <?php
                        }


                        if (isset($_GET['exitoHijos'])) {
                        ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se agregaron los equipos hijos correctamente',
                                })
                            </script>

                        <?php
                        }

                        ?>

                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="administrar-equipos.php" style="float: right;" class="btn btn-danger "><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <span class="badge badge-success ">
                                        <h3 style="font-size:15px;font-weight:bold;" class="text-white">Equipo Registrado por: <?php echo $creadoPor ?></h3>
                                    </span>
                                    <br>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header bg-info" id="headingInfo">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link   btn-block text-left text-white" style="font-size:12px" type="button" data-toggle="collapse" data-target="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo">
                                                                <b>Información Básica del Equipo</b>
                                                            </button>
                                                        </h2>
                                                    </div>

                                                    <?php

                                                    if ($idUsuario == $idCreadoPor || $idDepaCreador == $idDepartamento) {
                                                    ?>
                                                        <input type="hidden" value="SI" id="permiso">
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <input type="hidden" value="NO" id="permiso">
                                                    <?php
                                                    }
                                                    ?>
                                                    <div id="collapseInfo" class="collapse show active" aria-labelledby="headingInfo" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Nombre Equipo: <span class="text-success"> <?php echo mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8'); ?> </span></h5>
                                                            <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">ID Equipo: <span class="text-success"> <?php echo mb_convert_case($idUnico, MB_CASE_UPPER, 'UTF-8'); ?> </span></h5>

                                                            <?php
                                                            if ($numInventario == '') {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Numero Inventario: <span class="text-danger"> SIN INFORMACIÓN</span> </h5>

                                                            <?php
                                                            } else {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Numero Inventario: <span class="text-success"> <?php echo strtoupper($numInventario) ?> </span> </h5>

                                                            <?php
                                                            }

                                                            ?>
                                                            <?php
                                                            if ($valorConIva == 0) {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Valor con IVA: <span class="text-danger">SIN INFORMACIÓN </span> </h5>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Valor con IVA: <span class="text-success"><?php echo $valorConIva ?> </span> </h5>
                                                            <?php
                                                            }

                                                            ?>
                                                            <?php
                                                            if ($numSerie == '') {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Numero Serie: <span class="text-danger">SIN INFORMACIÓN</span> </h5>

                                                            <?php
                                                            } else {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Numero Serie: <span class="text-success"><?php echo strtoupper($numSerie) ?></span> </h5>

                                                            <?php
                                                            }

                                                            ?>
                                                            <?php
                                                            if ($nomServicio == '') {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Servicio: <span class="text-danger">SIN INFORMACIÓN </span> </h5>


                                                            <?php
                                                            } else {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Servicio: <span class="text-success"><?php echo strtoupper($nomServicio) ?> </span> </h5>


                                                            <?php
                                                            }

                                                            ?>
                                                            <?php
                                                            if ($recinto == '') {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Recinto: <span class="text-danger"> SIN INFORMACIÓN</span></h5>

                                                            <?php
                                                            } else {
                                                            ?>
                                                                <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;" class="text-info">Recinto: <span class="text-success"> <?php echo strtoupper($recinto) ?></span></h5>
                                                            <?php
                                                            }

                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6" style="text-align:center;">

                                            <div style="margin: 0 auto">

                                                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#exampleModal">
                                                    <i class="far fa-edit"> <b>Cambiar Estado</b> </i>
                                                </button>
                                                <br>
                                                <br>

                                                <?php
                                                if ($estadoActual == 'Operativo') {
                                                ?>
                                                    <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;"> <span style="color:white;background-color:green;padding: 1rem;" class="rounded-circle">Estado Actual: <?php echo $estadoActual ?> </span></h5>
                                                <?php
                                                } else if ($estadoActual == 'Mantenimiento') {
                                                ?>
                                                    <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;"> <span style="color:white;background-color:yellow;padding: 1rem;" class="rounded-circle text-dark"> Estado Actual: <?php echo $estadoActual ?> </span></h5>
                                                <?php
                                                } else if ($estadoActual == 'Fuera de Servicio') {
                                                ?>
                                                    <h5 style="font-weight:bold; font-size: 12px;font-family: 'Poppins', sans-serif;"> <span style="color:white;background-color:red;padding: 1rem;" class="rounded-circle">Estado Actual: <?php echo $estadoActual ?> </span></h5>
                                                <?php
                                                }

                                                ?>
                                                <br>
                                                <!-- Button trigger modal -->


                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title" id="exampleModalLabel"> <b>Cambiar el estado del equipo</b> </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="formularioCambiaEstado" method="POST" action="../sdo-funciones/cambiaEstadoEquipo.php">

                                                                    <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">

                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="codigoBarra">Nuevo Estado:<code>*</code></label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-info"></i></span>
                                                                                </div>
                                                                                <select name="opcionEstado" id="opcionEstado" required class="form-control">
                                                                                    <option value="Operativo">Operativo</option>
                                                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                                                    <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="codigoBarra">Observaciones:<code>*</code></label>
                                                                            <div class="input-group mb-3">
                                                                                <textarea name="observaciones" id="observaciones" rows="3" class="form-control" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar Estado</button>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <br>
                                                <?php

                                                $imagen = "SELECT * FROM imagenes_equipo WHERE idEquipo = $idHoja LIMIT 1 ";
                                                $resImagen = mysqli_query($conexion, $imagen);

                                                if ($resImagen->num_rows) {
                                                    while ($rowImagen = $resImagen->fetch_assoc()) {
                                                        $datos['rutaImagen'] = $rowImagen['rutaImagen'];
                                                        $rutaImagen = $datos['rutaImagen'];

                                                        echo '<img  width="200px" src="' . $rutaImagen . '" alt="hola">';
                                                    }
                                                } else {
                                                ?>
                                                    <img src="../sdo-administrador/img/Imagen-no-disponible.png" alt="Imagen Equipo" width="200px">
                                                <?php
                                                }
                                                ?>

                                                <?php
                                                ?>
                                            </div>
                                        </div>
                                    </div><!-- .row -->
                                    <div class="row">

                                        <div class="col-md-12">
                                            <hr>
                                            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                                <li class="nav-item" style="font-size: 12px;">
                                                    <a class="nav-link active" id="pills-dashboard-tab" data-toggle="pill" href="#pills-dashboard" role="tab" aria-controls="pills-home" aria-selected="false"><b>DashBoard</b> </a>
                                                </li>
                                                <li class="nav-item" style="font-size: 12px;">
                                                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><b>Datos del Equipo</b> </a>
                                                </li>
                                                <li class="nav-item" style="font-size: 12px;">
                                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><b>Sub-Equipos</b> </a>
                                                </li>
                                                <li class="nav-item" style="font-size: 12px;">
                                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"><b>Programación</b> </a>
                                                </li>
                                                <li class="nav-item" style="font-size: 12px;">
                                                    <a class="nav-link" id="pills-galeria-tab" data-toggle="pill" href="#pills-galeria" role="tab" aria-controls="pills-galeria" aria-selected="false"><b>Galería de Imagenes</b> </a>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-dashboard" role="tabpanel" aria-labelledby="pills-dashboard-tab">
                                                    <div class="row">

                                                        <div class="col-12 col-sm-6 col-md-4">
                                                            <h3 style="font-size: 15px;font-weight:bold" class="text-success">Última Mantención Realizada:</h3>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-4">
                                                            <h3 style="font-size: 15px;font-weight:bold" class="text-warning">Próxima Mantención:</h3>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-4">
                                                            <h3 style="font-size: 15px;font-weight:bold" class="text-danger">Mantención no realizada:</h3>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <a href="hojastecnicas.php?idHoja=<?php echo $idHoja ?>" style="text-decoration: none;" class="text-info">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-info elevation-1">
                                                                        <i class="fas fa-file-alt"></i>
                                                                    </span>
                                                                    <div class="info-box-content">
                                                                        <span class="info-box-text"> <b>Hojas Técnicas</b> </span>
                                                                        <span class="info-box-number"><?php echo $cantidadHojaTec ?></span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="info-box">
                                                                <span class="info-box-icon bg-success elevation-1">
                                                                    <i class="fas fa-cart-arrow-down"></i>
                                                                </span>

                                                                <div class="info-box-content">
                                                                    <span class="info-box-text"> <b>Solicitudes de Compra</b> </span>
                                                                    <span class="info-box-number"><?php echo $cantidad ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <a href="archivosAdjuntos.php?idHoja=<?php echo $idHoja ?>"  style="text-decoration: none;" class="text-danger">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-danger elevation-1">
                                                                        <i class="fas fa-file-upload"></i>
                                                                    </span>
                                                                    <div class="info-box-content">

                                                                        <?php 
                                                                        
                                                                        
                                                                        
                                                                        ?>


                                                                        <span class="info-box-text"> <b>Archivos Adjuntos</b> </span>
                                                                        <span class="info-box-number"><?php echo $cantidad ?></span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <a href="gastoEquipo.php?idHoja=<?php echo $idHoja ?>" style="text-decoration: none;" class="text-primary">
                                                                <div class="info-box">
                                                                    <span class="info-box-icon bg-primary elevation-1">
                                                                        <i class="fas fa-money-check-alt"></i>
                                                                    </span>
                                                                    <div class="info-box-content">

                                                                        <?php

                                                                        $sqlTotal = "SELECT SUM(costoTotal) as total FROM gastoEquipos WHERE idEquipo = $idHoja";
                                                                        $resTotal = mysqli_query($conexion, $sqlTotal);


                                                                        while ($rowTotal = $resTotal->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['total'] = $rowTotal['total'];
                                                                            $total = $datos['total'];
                                                                        }

                                                                        ?>

                                                                        <span class="info-box-text"> <b>Dinero Gastado</b> </span>
                                                                        <span class="info-box-number"><b><?php
                                                                                                            $total = moneda_chilena($total);


                                                                                                            if ($total == '$ ') {
                                                                                                                $totalTexto = '$ 0';
                                                                                                                echo $totalTexto;
                                                                                                            } else {
                                                                                                                echo $total;
                                                                                                            }
                                                                                                            ?></b> </span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                                    <button type="button" id="editar" class="btn btn-warning" style="float:right;"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;<b>Habilitar Edición</b></button>

                                                    <form action="../sdo-pdf/SDO-Hoja-Vida.php" method="POST" target="_blank">
                                                        <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                        <button type="submit" id="inputMozi" class="btn btn-info" style="float:right;margin-right:10px"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<b>Descargar Hoja de Vida</b></button>
                                                    </form>

                                                    <?php

                                                    $sqlArchivosHoja = "SELECT * FROM archivos_hojas_vida WHERE idHoja = $idHoja ";
                                                    $resArchivosHoja = mysqli_query($conexion, $sqlArchivosHoja);

                                                    if ($resArchivosHoja->num_rows > 0) {
                                                    ?>
                                                        <form action="visualizarEquipo.php" method="POST">
                                                            <input type="hidden" name="idHojaDesc" value="<?php echo $idHoja; ?>">
                                                            <input type="hidden" name="folioDesc" id="folioSoliCompraDescarga" value="<?php echo $folio; ?>">
                                                            <input type="hidden" name="idUnico" id="idUnico" value="<?php echo $idUnico; ?>">
                                                            <button type="submit" id="archivos" class="btn bg-purple" style="float:right;margin-right:10px"><i class="fas fa-file-download"></i>&nbsp;&nbsp;<b>Descargar Archivos</b></button>
                                                        </form>
                                                    <?php
                                                    }

                                                    ?>



                                                    <p style="color:red;font-weight:bold;font-size:12px">*Campos Obligatorios</p>
                                                    <form action="../sdo-funciones/modificar-equipo-hojadevida.php" id="formulario" method="POST" enctype="multipart/form-data">
                                                        <fieldset class="fieldset" disabled>
                                                            <div class="form-row ">

                                                                <div class="form-group col-md-6">
                                                                    <label for="codigoBarra" style="font-size:12px">Nombre del equipo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="nombreEquipo" name="nombreEquipo" value="<?php echo $nomEquipo ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="codigoBarra" style="font-size:12px">N° de Inventario:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pallet"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control  form-control-sm" id="numeroInventario" name="numeroInventario" value="<?php echo $numInventario ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="codigoBarra" style="font-size:12px">Fecha de recepción:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <input name="fechaRecepcion" id="fechaRecepcion" class="form-control form-control-sm" value="<?php echo $fechaRecepcion ?>">
                                                                    </div>
                                                                    <small id="emailHelp" class="form-text text-muted">Formato fecha: dd/mm/yyyy </small>
                                                                </div>
                                                            </div>
                                                        </fieldset>

                                                        <div id="accordion">
                                                            <div class="card">
                                                                <div class="card-header bg-purple" id="headingOne" style="padding: 6px 12px;">
                                                                    <h5 class="mb-0">
                                                                        <button type="button" class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                            <b> Datos Técnicos del equipo <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                                        </button>
                                                                    </h5>
                                                                </div>

                                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                                    <div class="card-body">

                                                                        <fieldset class="fieldset1" disabled>
                                                                            <div class="form-row">

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="codInventario" style="font-size:12px">Valor con IVA:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-money-bill-wave-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="number" class="form-control form-control-sm" id="valorIva" name="valorIva" value="<?php echo $valorIva ?>">
                                                                                    </div>
                                                                                    <label for="" id="label" style="color: green; font-weight:bold"></label>
                                                                                    <script>
                                                                                        const input = document.querySelector("#valorIva");
                                                                                        const label = document.querySelector("#label");
                                                                                        const formatter = new Intl.NumberFormat("es-CL", {
                                                                                            style: "currency",
                                                                                            currency: "CLP"
                                                                                        });

                                                                                        input.addEventListener("input", e => {
                                                                                            label.textContent = formatter.format(input.value)
                                                                                        })
                                                                                    </script>

                                                                                </div>

                                                                                <div class="form-group col-md-3">

                                                                                    <label for="codInventario" style="font-size:12px">Marca:<code>*</code></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" value="<?php echo $marca ?>">
                                                                                    </div>

                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">ID - Licitación:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="idLicitacion" name="idLicitacion" value="<?php echo $idLicitacion ?>">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="departamento" style="font-size:12px">Modelo:<code>*</code></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="modeloEquipo" id="modeloEquipo" class="form-control form-control-sm" value="<?php echo $modelo ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">N° de Serie:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="numeroSerie" name="numeroSerie" value="<?php echo $numSerie ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha de Compra:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input name="fechaCompra" id="fechaCompra" class="form-control form-control-sm" value="<?php echo $fechaCompra ?>">
                                                                                    </div>
                                                                                    <small id="emailHelp" class="form-text text-muted">Formato fecha: dd/mm/yyyy </small>
                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">Fecha Puesta en Marcha:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input name="fechaMarcha" id="fechaMarcha" class="form-control form-control-sm" value="<?php echo $fechaMarcha ?>">

                                                                                    </div>
                                                                                    <small id="emailHelp" class="form-text text-muted">Formato fecha: dd/mm/yyyy </small>

                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">Recinto:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="nombreRecinto" name="nombreRecinto" value="<?php echo $recinto ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputTurno" style="font-size:12px">Servicio:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                                                        </div>
                                                                                        <select id="servicioOrden" class="form-control form-control-sm" name="servicioOrden">
                                                                                            <?php

                                                                                            $queryServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                                                            $resServicios = mysqli_query($conexion, $queryServicios);


                                                                                            while ($rowServicios = $resServicios->fetch_assoc()) {

                                                                                                if ($rowServicios['idServicio'] == $idServicio) {
                                                                                            ?>
                                                                                                    <option value="<?php echo $rowServicios['idServicio'] ?>" selected="selected"><?php echo $rowServicios['nomServicio'] ?></option>
                                                                                                <?php
                                                                                                } else {
                                                                                                ?>
                                                                                                    <option value="<?php echo $rowServicios['idServicio'] ?>"><?php echo $rowServicios['nomServicio'] ?></option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            <option value="Otro">
                                                                                                Otro
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-group col-md-3">

                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="departamento" style="font-size:12px">Orden de compra: (1 Orden de compra por linea)</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                                                                                        </div>
                                                                                        <textarea cols="30" rows="3" name="ordenCompra" id="ordenCompra" class="form-control form-control-sm"><?php echo $ordenCompra ?></textarea>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </fieldset>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-header bg-success" id="headingTwo" style="padding: 6px 12px;">
                                                                    <h5 class="mb-0">
                                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                            <b>Información Extra <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                                        </button>
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                                                    <div class="card-body">

                                                                        <fieldset class="fieldset2" disabled>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="codigoBarra" style="font-size:12px">Clase:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="claseEquipo" name="claseEquipo" value="<?php echo $claseEquipo; ?>">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="codInventario" style="font-size:12px">Sub-clase:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="subClaseEquipo" name="subClaseEquipo" value="<?php echo $subClaseEquipo; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="codInventario" style="font-size:12px">Complejidad Tecnológica:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="compleEquipo" name="compleEquipo" value="<?php echo $compleEquipo; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">Nivel de riesgo:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="nivelRiesgo" name="nivelRiesgo" value="<?php echo $nivelRiesgo; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="departamento" style="font-size:12px">Año Fabricación:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="ahnoFabricacion" id="ahnoFabricacion" class="form-control form-control-sm" value="<?php echo $ahnoFabricacion; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="departamento" style="font-size:12px">Vida Util:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="vidaUtil" id="vidaUtil" class="form-control form-control-sm" value="<?php echo $vidaUtil; ?>"></input>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Versión del software:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="versionSoftware" name="versionSoftware" value="<?php echo $versionSoftware; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Pais Origen:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-globe-americas"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="paisOrigen" name="paisOrigen" value="<?php echo $paisOrigen; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">Termino de garantía:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-dizzy"></i></span>
                                                                                        </div>
                                                                                        <input name="fechaGarantia" id="fechaGarantia" class="form-control form-control-sm" value="<?php echo $fechaGarantia ?>">
                                                                                    </div>
                                                                                    <small id="emailHelp" class="form-text text-muted">Formato fecha: dd/mm/yyyy </small>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Mantenimiento Anual:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                                        </div>
                                                                                        <input type="number" class="form-control form-control-sm" name="costoAnualMantenimiento" id="costoAnualMantenimiento" value="<?php echo $costoAnualMantenimiento; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputNomTarjeta" style="font-size:12px">Frecuencia Del Mantenimiento:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-wave-square"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="frecuenciaMantenimiento" name="frecuenciaMantenimiento" value="<?php echo $frecuenciaMantenimiento; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-header bg-danger" id="headingThree" style="padding: 6px 12px;">
                                                                    <h5 class="mb-0">
                                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                                            <b>Información Técnica y Mantenimiento Existente <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                                        </button>
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                                                    <div class="card-body">

                                                                        <fieldset class="fieldset3" disabled>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <div class="form-group">
                                                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                            <input type="checkbox" class="custom-control-input" id="pauta" onclick="togglePauta()">
                                                                                            <label class="custom-control-label" for="pauta">Adjuntar Pautas</label>
                                                                                            <input type="hidden" name="pauta" value="NO" id="tienePauta">
                                                                                            <script type="text/javascript">
                                                                                                document.getElementById('pauta').onchange = function() {
                                                                                                    var tienePauta = document.getElementById('tienePauta').value;
                                                                                                    var textoPautas = document.getElementById('textoPautas');
                                                                                                    var adjuntosPauta = document.getElementById('adjuntosPauta[]');

                                                                                                    if (tienePauta == 'SI') {
                                                                                                        document.getElementById('tienePauta').value = 'NO';
                                                                                                        textoPautas.removeAttribute("required");
                                                                                                        adjuntosPauta.removeAttribute("required");

                                                                                                    } else if (tienePauta == 'NO') {
                                                                                                        document.getElementById('tienePauta').value = 'SI';
                                                                                                        textoPautas.setAttribute("required", "");
                                                                                                        adjuntosPauta.setAttribute("required", "");

                                                                                                    } else {
                                                                                                        document.getElementById('tienePauta').value = 'NO';
                                                                                                        textoPautas.removeAttribute("required");
                                                                                                        adjuntosPauta.removeAttribute("required");
                                                                                                    }
                                                                                                };
                                                                                            </script>
                                                                                        </div>
                                                                                        <script>
                                                                                            function togglePauta() {
                                                                                                // var element = document.getElementById("navBarPrincipal")
                                                                                                // element.classList.toggle("dark-mode")
                                                                                                var pautaInput = document.getElementById("pautaInput");
                                                                                                pautaInput.classList.toggle("ocultar-pauta");
                                                                                            }
                                                                                        </script>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="form-group ocultar-pauta" id="pautaInput">
                                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Subir Archivos Adjuntos: </label><br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                                        <div class="custom-file">
                                                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosPauta[]" name="adjuntosPauta[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                        </div>
                                                                                        <br>
                                                                                        <br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Nombre de los archivos subidos :</label><br>
                                                                                        <textarea name="textoPautas" id="textoPautas" cols="30" rows="3" class="form-control form-control-sm"><?php echo $textoPautas; ?></textarea>
                                                                                        <script type="text/javascript">
                                                                                            $('.custom-file input').change(function(e) {
                                                                                                var files = [];
                                                                                                for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                                    files.push($(this)[0].files[i].name);
                                                                                                }
                                                                                                $(this).next('.custom-file-label').html(files.join(', '));
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <div class="form-group">
                                                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                            <input type="checkbox" class="custom-control-input" id="certificado" onclick="toggleCertificados()">
                                                                                            <label class="custom-control-label" for="certificado">Adjuntar Certificados</label>
                                                                                            <input type="hidden" name="certificado" value="NO" id="tieneCertificado">
                                                                                            <script type="text/javascript">
                                                                                                document.getElementById('certificado').onchange = function() {
                                                                                                    var tieneCertificado = document.getElementById('tieneCertificado').value;
                                                                                                    var textoCertificados = document.getElementById('textoCertificados');
                                                                                                    var adjuntosCertificados = document.getElementById('adjuntosCertificados[]');

                                                                                                    if (tieneCertificado == 'SI') {
                                                                                                        document.getElementById('tieneCertificado').value = 'NO';
                                                                                                        textoCertificados.removeAttribute("required");
                                                                                                        adjuntosCertificados.removeAttribute("required");

                                                                                                    } else if (tieneCertificado == 'NO') {
                                                                                                        document.getElementById('tieneCertificado').value = 'SI';
                                                                                                        textoCertificados.setAttribute("required", "");
                                                                                                        adjuntosCertificados.setAttribute("required", "");

                                                                                                    } else {
                                                                                                        document.getElementById('tieneCertificado').value = 'NO';
                                                                                                        textoCertificados.removeAttribute("required");
                                                                                                        adjuntosCertificados.removeAttribute("required");
                                                                                                    }
                                                                                                };
                                                                                            </script>
                                                                                        </div>
                                                                                        <script>
                                                                                            function toggleCertificados() {
                                                                                                // var element = document.getElementById("navBarPrincipal")
                                                                                                // element.classList.toggle("dark-mode")
                                                                                                var certificadosInput = document.getElementById("certificadosInput");
                                                                                                certificadosInput.classList.toggle("ocultar-certificados");
                                                                                            }
                                                                                        </script>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="form-group ocultar-certificados" id="certificadosInput">
                                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Subir Archivos Adjuntos: </label><br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                                        <div class="custom-file">
                                                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosCertificados[]" name="adjuntosCertificados[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                        </div>
                                                                                        <br>
                                                                                        <br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Nombre de los archivos subidos :</label><br>
                                                                                        <textarea name="textoCertificados" id="textoCertificados" cols="30" rows="3" class="form-control form-control-sm "><?php echo $textoCertificados; ?></textarea>
                                                                                        <script type="text/javascript">
                                                                                            $('.custom-file input').change(function(e) {
                                                                                                var files = [];
                                                                                                for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                                    files.push($(this)[0].files[i].name);
                                                                                                }
                                                                                                $(this).next('.custom-file-label').html(files.join(', '));
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div><!-- .pauta-->

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <div class="form-group">
                                                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                            <input type="checkbox" class="custom-control-input" id="manual" onclick="toggleManual()">
                                                                                            <label class="custom-control-label" for="manual">Adjuntar Manuales</label>
                                                                                            <input type="hidden" name="manual" value="NO" id="tieneManual">
                                                                                            <script type="text/javascript">
                                                                                                document.getElementById('manual').onchange = function() {
                                                                                                    var tieneManual = document.getElementById('tieneManual').value;
                                                                                                    var textoManuales = document.getElementById('textoManuales');
                                                                                                    var adjuntosManuales = document.getElementById('adjuntosManuales[]');

                                                                                                    if (tieneManual == 'SI') {
                                                                                                        document.getElementById('tieneManual').value = 'NO';
                                                                                                        textoManuales.removeAttribute("required");
                                                                                                        adjuntosManuales.removeAttribute("required");

                                                                                                    } else if (tieneManual == 'NO') {
                                                                                                        document.getElementById('tieneManual').value = 'SI';
                                                                                                        textoManuales.setAttribute("required", "");
                                                                                                        adjuntosManuales.setAttribute("required", "");

                                                                                                    } else {
                                                                                                        document.getElementById('tieneManual').value = 'NO';
                                                                                                        textoManuales.removeAttribute("required");
                                                                                                        adjuntosManuales.removeAttribute("required");
                                                                                                    }
                                                                                                };
                                                                                            </script>
                                                                                        </div>
                                                                                        <script>
                                                                                            function toggleManual() {
                                                                                                // var element = document.getElementById("navBarPrincipal")
                                                                                                // element.classList.toggle("dark-mode")
                                                                                                var manualInput = document.getElementById("manualInput");
                                                                                                manualInput.classList.toggle("ocultar-manual");
                                                                                            }
                                                                                        </script>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="form-group ocultar-manual" id="manualInput">
                                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Subir Archivos Adjuntos: </label><br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                                        <div class="custom-file">
                                                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosManuales[]" name="adjuntosManuales[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                        </div>
                                                                                        <br>
                                                                                        <br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Nombre de los archivos subidos :</label><br>
                                                                                        <textarea name="textoManuales" id="textoManuales" cols="30" rows="3" class="form-control form-control-sm"><?php echo $textoManuales; ?></textarea>
                                                                                        <script type="text/javascript">
                                                                                            $('.custom-file input').change(function(e) {
                                                                                                var files = [];
                                                                                                for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                                    files.push($(this)[0].files[i].name);
                                                                                                }
                                                                                                $(this).next('.custom-file-label').html(files.join(', '));
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <div class="form-group">
                                                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                            <input type="checkbox" class="custom-control-input" id="otros" onclick="toggleOtros()">
                                                                                            <label class="custom-control-label" for="otros">Adjuntar Otros Tipos de Archivos</label>
                                                                                            <input type="hidden" name="otros" value="NO" id="tieneOtros">
                                                                                            <script type="text/javascript">
                                                                                                document.getElementById('otros').onchange = function() {
                                                                                                    var tieneOtros = document.getElementById('tieneOtros').value;
                                                                                                    var textoOtros = document.getElementById('textoOtros');
                                                                                                    var adjuntosOtros = document.getElementById('adjuntosOtros[]');

                                                                                                    if (tieneOtros == 'SI') {
                                                                                                        document.getElementById('tieneOtros').value = 'NO';
                                                                                                        textoOtros.removeAttribute("required");
                                                                                                        adjuntosOtros.removeAttribute("required");

                                                                                                    } else if (tieneOtros == 'NO') {
                                                                                                        document.getElementById('tieneOtros').value = 'SI';
                                                                                                        textoOtros.setAttribute("required", "");
                                                                                                        adjuntosOtros.setAttribute("required", "");

                                                                                                    } else {
                                                                                                        document.getElementById('tieneOtros').value = 'NO';
                                                                                                        textoOtros.removeAttribute("required");
                                                                                                        adjuntosOtros.removeAttribute("required");
                                                                                                    }
                                                                                                };
                                                                                            </script>
                                                                                        </div>
                                                                                        <script>
                                                                                            function toggleOtros() {
                                                                                                // var element = document.getElementById("navBarPrincipal")
                                                                                                // element.classList.toggle("dark-mode")
                                                                                                var otrosInput = document.getElementById("otrosInput");
                                                                                                otrosInput.classList.toggle("ocultar-otros");
                                                                                            }
                                                                                        </script>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="form-group ocultar-otros" id="otrosInput">
                                                                                        <!-- <label for="customFile">Custom File</label> -->
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Subir Archivos Adjuntos: (Imagenes y cualquier otro archivo que no cabe en las anteriores categorias)</label><br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg</label>
                                                                                        <div class="custom-file">
                                                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosOtros[]" name="adjuntosOtros[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg">
                                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                        </div>
                                                                                        <br>
                                                                                        <br>
                                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px">Nombre de los archivos subidos : </label><br>
                                                                                        <textarea name="textoOtros" id="textoOtros" cols="30" rows="3" class="form-control form-control-sm"><?php echo $textoOtros; ?></textarea>
                                                                                        <script type="text/javascript">
                                                                                            $('.custom-file input').change(function(e) {
                                                                                                var files = [];
                                                                                                for (var i = 0; i < $(this)[0].files.length; i++) {
                                                                                                    files.push($(this)[0].files[i].name);
                                                                                                }
                                                                                                $(this).next('.custom-file-label').html(files.join(', '));
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>
                                                                            </div><!-- .manuales-->

                                                                            <div class="form-row">
                                                                                <div class="from-group col-md-6">
                                                                                    <label for="accesorios" class="form-label" style="font-size:12px">Accesorios:</label>
                                                                                    <textarea name="textoAccesorios" id="textoAccesorios" rows="3" class="form-control form-control-sm" placeholder="ESCRIBA LOS ACCESORIOS DEL EQUIPO"><?php echo $textoAccesorios; ?></textarea>
                                                                                </div>
                                                                                <div class="from-group col-md-6">
                                                                                    <label for="accesorios" class="form-label" style="font-size:12px">Insumos:</label>
                                                                                    <textarea name="textoInsumos" id="textoInsumos" rows="3" class="form-control form-control-sm" placeholder="ESCRIBA LOS INSUMOS DEL EQUIPO"><?php echo $textoInsumos; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card">
                                                                <div class="card-header bg-primary" id="headingFour" style="padding: 6px 12px;">
                                                                    <h5 class="mb-0">
                                                                        <button type="button" class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                                            <b>Datos contacto Servicio Técnico <i class="fas fa-arrow-alt-circle-down"></i></b>
                                                                        </button>
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                                                    <div class="card-body">

                                                                        <fieldset class="fieldset4" disabled>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Proveedor:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-store"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="nombreProveedor" name="nombreProveedor" value="<?php echo $nomProveedor ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Dirección:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="direccionProveedor" name="direccionProveedor" value="<?php echo $direccionProveedor ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Teléfono/Fax:</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="tel" class="form-control form-control-sm" id="telefonoProveedor" name="telefonoProveedor value=" <?php echo $telProveedor ?>"">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">E-mail</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="emailProveedor" name="emailProveedor" value="<?php echo $emailProveedor ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-row">

                                                                                <div class="form-group col-md-6">
                                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre del contacto</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control form-control-sm" id="nombreContactoProveedor" name="nombreContactoProveedor" value="<?php echo $nomContacto ?>">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group col-md-6">
                                                                                    <label for="" style="font-size:12px">Observaciones o sugerencias:</label>
                                                                                    <textarea name="observacionesContactoProveedor" id="observacionesContactoProveedor" rows="5" class="form-control form-control-sm"><?php echo $observaciones ?></textarea>
                                                                                </div>
                                                                            </div>

                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja" id="idHoja">
                                                            <button type="submit" id="guardar" onclick="numerar();" disabled class="btn btn-info btn-block"> <b>Guardar Modificación</b> </button>
                                                        </div>
                                                    </form>

                                                </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                                                    <button class="btn bg-purple" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Equipo</b> </button>
                                                    <div id="tooltip" role="tooltip">
                                                        <b>Agregar un nuevo subEquipo</b>
                                                        <div id="arrow" data-popper-arrow></div>
                                                    </div>
                                                    <br>
                                                    <br>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-purple">
                                                                    <h5 class="modal-title" id="staticBackdropLabel">Escoja el equipo para añadirlo como un sub-equipo</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="tabla_subEquipo" class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">N°</th>
                                                                                <th scope="col">Folio</th>
                                                                                <th scope="col">Nombre Equipo</th>
                                                                                <th scope="col">Estado</th>
                                                                                <th scope="col">Seleccionar</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <?php

                                                                            $sqlEquipo = "SELECT * FROM hoja_de_vida WHERE idEquipoPadre is null AND idHoja != $idHoja";
                                                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                            $num = 1;

                                                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['idHoja'] = $rowEquipo['idHoja'];
                                                                                $datos['folio'] = $rowEquipo['folio'];
                                                                                $datos['nomEquipo'] = $rowEquipo['nomEquipo'];
                                                                                $datos['idServicio'] = $rowEquipo['idServicio'];
                                                                                $datos['marca'] = $rowEquipo['marca'];
                                                                                $datos['modelo'] = $rowEquipo['modelo'];
                                                                                $datos['estadoActual'] = $rowEquipo['estadoActual'];

                                                                                $idHojaHijo = $datos['idHoja'];
                                                                                $folio = $datos['folio'];
                                                                                $nomEquipo = $datos['nomEquipo'];
                                                                                $idServicio = $datos['idServicio'];
                                                                                $marca = $datos['marca'];
                                                                                $modelo = $datos['modelo'];
                                                                                $estadoActual = $datos['estadoActual'];

                                                                            ?>

                                                                                <tr>
                                                                                    <td><?php echo $num ?> </td>
                                                                                    <td><?php echo strtoupper($folio)  ?></td>
                                                                                    <td><?php echo mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>

                                                                                    <?php

                                                                                    if ($estadoActual == 'Operativo') {
                                                                                    ?>
                                                                                        <td class="font-weight-bold  text-success" style="display: inline-block"><?php echo '<i class="fas fa-check"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                                    <?php
                                                                                    } else if ($estadoActual == 'Mantenimiento') {
                                                                                    ?>
                                                                                        <td class="font-weight-bold text-warning " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <td class="font-weight-bold text-danger " style="display: inline-block"><?php echo '<i class="fas fa-skull-crossbones"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                                    <?php
                                                                                    }

                                                                                    ?>


                                                                                    <td>

                                                                                        <form action="../sdo-funciones/agregarEquipoHijo.php" method="POST">
                                                                                            <input type="hidden" name="idHojaPadre" value="<?php echo $idHoja ?>">
                                                                                            <input type="hidden" name="idHojaHijo" value="<?php echo $idHojaHijo ?>">
                                                                                            <input type="submit" value="Añadir Esta Hoja" class="btn bg-purple">
                                                                                        </form>

                                                                                    </td>


                                                                                </tr>

                                                                            <?php
                                                                                $num++;
                                                                            }
                                                                            ?>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    <button type="submit" onclick="enviarForm()" form="agregarEquipoHijo" name="enviar" value="enviar" class="btn btn-primary ">Añadir!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <table id="table_equipos" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Folio</th>
                                                                <th scope="col">Nombre Equipo</th>
                                                                <th scope="col">Servicio</th>
                                                                <th scope="col">Marca</th>
                                                                <th scope="col">Modelo</th>
                                                                <th scope="col">Estado</th>
                                                                <th scope="col">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $sqlEquipo = "SELECT * FROM hoja_de_vida WHERE idEquipoPadre = $idHoja";
                                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                            $num = 1;

                                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['idHoja'] = $rowEquipo['idHoja'];
                                                                $datos['folio'] = $rowEquipo['folio'];
                                                                $datos['nomEquipo'] = $rowEquipo['nomEquipo'];
                                                                $datos['idServicio'] = $rowEquipo['idServicio'];
                                                                $datos['marca'] = $rowEquipo['marca'];
                                                                $datos['modelo'] = $rowEquipo['modelo'];
                                                                $datos['estadoActual'] = $rowEquipo['estadoActual'];

                                                                $idHoja = $datos['idHoja'];
                                                                $folio = $datos['folio'];
                                                                $nomEquipo = $datos['nomEquipo'];
                                                                $idServicio = $datos['idServicio'];
                                                                $marca = $datos['marca'];
                                                                $modelo = $datos['modelo'];
                                                                $estadoActual = $datos['estadoActual'];

                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $num ?> </td>
                                                                    <td><?php echo strtoupper($folio)  ?></td>
                                                                    <td><?php echo mb_convert_case($nomEquipo, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                                    <td><?php

                                                                        $sqlServicio = "SELECT * FROM servicios WHERE idServicio = '$idServicio'";
                                                                        $resServicio = mysqli_query($conexion, $sqlServicio);

                                                                        while ($rowServicio = $resServicio->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['nomServicio'] = $rowServicio['nomServicio'];
                                                                            $nomServicio = $datos['nomServicio'];
                                                                        }

                                                                        echo $nomServicio;
                                                                        ?></td>
                                                                    <td><?php echo strtoupper($marca)  ?></td>
                                                                    <td><?php echo strtoupper($modelo)  ?></td>
                                                                    <?php

                                                                    if ($estadoActual == 'Operativo') {
                                                                    ?>
                                                                        <td class="font-weight-bold text-success  " style="display: inline-block"><?php echo '<i class="fas fa-check"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                    <?php
                                                                    } else if ($estadoActual == 'Mantenimiento') {
                                                                    ?>
                                                                        <td class="font-weight-bold   text-warning " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <td class="font-weight-bold   text-danger " style="display: inline-block"><?php echo '<i class="fas fa-skull-crossbones"></i> ' .  strtoupper($estadoActual) ?></td>
                                                                    <?php
                                                                    }

                                                                    ?>
                                                                    <td>
                                                                        <form action="visualizarEquipo.php" method="POST" style="display:inline;">
                                                                            <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                                            <button type="submit" class="btn btn-info btn-xs"> <i class="fas fa-eye"></i></button>
                                                                        </form>
                                                                        <form action="../sdo-pdf/SDO-Hoja-Vida.php" target="_blank" method="POST" style="display:inline;">
                                                                            <input type="hidden" name="idHoja" id="idHoja" value="<?php echo $idHoja ?>">
                                                                            <button type="submit" class="btn btn-success btn-xs"><i class="fas fa-file-pdf"></i></button>
                                                                        </form>

                                                                        <a href="#" id="<?php echo $idHoja ?>" class="btn btn-danger quitar"><i class="fas fa-trash-alt"></i></a>
                                                                    </td>

                                                                </tr>
                                                            <?php
                                                                $num++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                    programación
                                                </div>
                                                <div class="tab-pane fade" id="pills-galeria" role="tabpanel" aria-labelledby="pills-galeria-tab">

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .row -->

                                </div><!-- .tabla-responsive -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col  -->
        </div>
        <!-- /.row -->
        </section>
        <!-- /.content -->
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

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_equipos').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado subEquipos, añada uno.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar:",
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
        $(document).ready(function() {
            $('#tabla_subEquipo').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado sub-Equipos, añada uno.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script>
        const button = document.querySelector('#agregar');
        const tooltip = document.querySelector('#tooltip');

        const popperInstance = Popper.createPopper(button, tooltip, {
            placement: 'right',
            modifiers: [{
                name: 'offset',
                options: {
                    offset: [0, 8],
                },
            }, ],
        });

        function show() {
            // Make the tooltip visible
            tooltip.setAttribute('data-show', '');
            // Enable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: true
                    },
                ],
            }));
            // Update its position
            popperInstance.update();
        }

        function hide() {
            // Hide the tooltip
            tooltip.removeAttribute('data-show');
            // Disable the event listeners
            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: 'eventListeners',
                        enabled: false
                    },
                ],
            }));
        }

        const showEvents = ['mouseenter', 'focus'];
        const hideEvents = ['mouseleave', 'blur'];

        showEvents.forEach((event) => {
            button.addEventListener(event, show);
        });

        hideEvents.forEach((event) => {
            button.addEventListener(event, hide);
        });
    </script>
    <script>
        const btnEditar = document.getElementById("editar");

        btnEditar.addEventListener("click", function() {
            permiso = document.getElementById('permiso');

            if (permiso.value == "SI") {
                // const disableForm = document.getElementById("disable");
                const fieldset = document.querySelector(".fieldset");
                const fieldset1 = document.querySelector(".fieldset1");
                const fieldset2 = document.querySelector(".fieldset2");
                const fieldset3 = document.querySelector(".fieldset3");
                const fieldset4 = document.querySelector(".fieldset4");
                const guardar = document.querySelector("#guardar");

                btnEditar.addEventListener("click", () => (fieldset.disabled = false));
                btnEditar.addEventListener("click", () => (fieldset1.disabled = false));
                btnEditar.addEventListener("click", () => (fieldset2.disabled = false));
                btnEditar.addEventListener("click", () => (fieldset3.disabled = false));
                btnEditar.addEventListener("click", () => (fieldset4.disabled = false));
                // disableForm.addEventListener("click", () => (fieldset.disabled = true));
                btnEditar.addEventListener("click", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Modo Edición',
                        text: 'Modo Edición Habilitado',
                    })
                });
                btnEditar.addEventListener("click", () => (guardar.disabled = false));
            } else if (permiso.value == "NO") {
                Swal.fire({
                    icon: 'error',
                    title: 'No Autorizado',
                    text: 'Solo la persona o el departamento que creo el registro puede modificar los datos del equipo',
                })
            }
        });
    </script>
    <script>
        $(document).ready(
            $('.quitar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Desea quitar este equipo?',
                    showConfirmButton: true,
                    confirmButtonText: 'Si , dejar huerfano',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, dejar ser feliz',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/eliminar-hoja-hijo.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Equipo hijo eliminado',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }
                        })
                    }
                })
            })
        )
    </script>
    <script type="text/javascript">
        function enviarForm() {

            const agregarEquipoHijo = document.getElementById('agregarEquipoHijo');
            agregarEquipoHijo.submit();

        }
    </script>
</body>

</html>