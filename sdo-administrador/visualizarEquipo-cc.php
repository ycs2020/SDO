+
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

// echo '';
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
            $rutaFinal = "../sdo-administrador/";
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

if (!empty($_POST['id']) || !empty($_GET['id'])) {

    if ($_POST['id']) {
        $idEquipo = $_POST['id'];
    } else {
        $idEquipo = $_GET['id'];
    }

    //TRAEMOS LOS DATOS DE LA HOJA
    $sqlHoja = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $resHoja = mysqli_query($conexion, $sqlHoja);

    while ($rowHoja = $resHoja->fetch_assoc()) {

        $datos = [];

        $datos['id'] = $rowHoja['id'];
        $datos['fechaRegistro'] = $rowHoja['fechaRegistro'];

        $datos['identificadorUnico'] = $rowHoja['identificadorUnico'];
        $datos['numeroInventario'] = $rowHoja['numeroInventario'];
        $datos['fechaRecepcion'] = $rowHoja['fechaRecepcion'];
        $datos['nombreEquipo'] = $rowHoja['nombreEquipo'];
        $datos['marca'] = $rowHoja['marca'];
        $datos['modelo'] = $rowHoja['modelo'];
        $datos['idServicio'] = $rowHoja['idServicio'];
        $datos['recinto'] = $rowHoja['recinto'];
        $datos['pma'] = $rowHoja['pma'];
        $datos['coordenadas'] = $rowHoja['coordenadas'];
        $datos['fechaCompra'] = $rowHoja['fechaCompra'];
        $datos['valorConIva'] = $rowHoja['valorConIva'];
        $datos['idLicitacion'] = $rowHoja['idLicitacion'];
        $datos['ordenCompra'] = $rowHoja['ordenCompra'];
        $datos['fechaMarcha'] = $rowHoja['fechaMarcha'];
        $datos['clase'] = $rowHoja['clase'];
        $datos['subclase'] = $rowHoja['subclase'];
        $datos['complejidadTec'] = $rowHoja['complejidadTec'];
        $datos['nivelRiesgo'] = $rowHoja['nivelRiesgo'];
        $datos['AhnoFrabricacion'] = $rowHoja['AhnoFrabricacion'];
        $datos['vidaUtil'] = $rowHoja['vidaUtil'];
        $datos['versionSoftware'] = $rowHoja['versionSoftware'];
        $datos['paisOrigen'] = $rowHoja['paisOrigen'];
        $datos['terminoGarantia'] = $rowHoja['terminoGarantia'];
        $datos['costoMantAnual'] = $rowHoja['costoMantAnual'];
        $datos['numeroSerie'] = $rowHoja['numeroSerie'];
        $datos['carpeta'] = $rowHoja['carpeta'];

        $id = $datos['id'];
        $fechaRegistro = $datos['fechaRegistro'];

        $valorConIva = $datos['valorConIva'];
        $identificadorUnico = $datos['identificadorUnico'];
        $numeroInventario = $datos['numeroInventario'];
        $fechaRecepcion = $datos['fechaRecepcion'];
        $nombreEquipo = $datos['nombreEquipo'];
        $marca = $datos['marca'];
        $modelo = $datos['modelo'];
        $idServicioDB = $datos['idServicio'];
        $recinto = $datos['recinto'];
        $pma = $datos['pma'];
        $coordenadas = $datos['coordenadas'];
        $fechaCompra = $datos['fechaCompra'];
        $clase = $datos['clase'];
        $subclase = $datos['subclase'];
        $complejidadTec = $datos['complejidadTec'];
        $nivelRiesgo = $datos['nivelRiesgo'];
        $AhnoFrabricacion = $datos['AhnoFrabricacion'];
        $vidaUtil = $datos['vidaUtil'];
        $versionSoftware = $datos['versionSoftware'];
        $paisOrigen = $datos['vidaUpaisOrigenil'];
        $terminoGarantia = $datos['terminoGarantia'];
        $costoMantAnual = $datos['costoMantAnual'];
        $numeroSerie = $datos['numeroSerie'];
        $carpeta = $datos['carpeta'];
        $idLicitacion = $datos['idLicitacion'];
        $fechaMarcha = $datos['fechaMarcha'];
    }

    $fechaFormateada = date("d/m/Y", strtotime($fechaRegistro));

    //Nombre del servicio 
    $sqlServicio = "SELECT * FROM servicios WHERE idServicio = $idServicioDB";
    $resServicio = mysqli_query($conexion, $sqlServicio);

    while ($rowServicio = $resServicio->fetch_assoc()) {
        $datos = [];
        $datos['nomServicio'] = $rowServicio['nomServicio'];
        $nomServicio = $datos['nomServicio'];
    }

    /* =========CODIGO PHP PARA EL DASHBOARD============= */
    $sqlOrdenes = "SELECT COUNT(*) as cantidad_hojas FROM ordenes_trabajos_general WHERE idEquipo = $id";
    $resOrdenes = mysqli_query($conexion, $sqlOrdenes);

    while ($rowOrdenes = $resOrdenes->fetch_assoc()) {
        $datos = [];
        $datos['cantidad_hojas'] = $rowOrdenes['cantidad_hojas'];
        $cantidad_hojas = $datos['cantidad_hojas'];
    }

    /* =========CODIGO PHP PARA EL DASHBOARD============= */
    $sqlGastos = "SELECT SUM(costoTotal) as cantidad_gastos FROM gastoEquipos2 WHERE idEquipo = $id";
    $resOrdenes = mysqli_query($conexion, $sqlGastos);

    while ($rowOrdenes = $resOrdenes->fetch_assoc()) {
        $datos = [];
        $datos['cantidad_gastos'] = $rowOrdenes['cantidad_gastos'];
        $cantidad_gastos = $datos['cantidad_gastos'];
    }

    // $sqlHojasTec = "SELECT COUNT(*) as cantidadHojaTec FROM ordenes_trabajos_general WHERE idEquipo = $idHoja";
    // $resHojasTec = mysqli_query($conexion, $sqlHojasTec);

    // while ($rowHojasTec = $resHojasTec->fetch_assoc()) {
    //     $datos = [];
    //     $datos['cantidadHojaTec'] = $rowHojasTec['cantidadHojaTec'];
    //     $cantidadHojaTec = $datos['cantidadHojaTec'];
    // }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
    <script src="../plugins/moment/moment-with-locales.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';

        if (isset($_GET['exitoEditar'])) {
        ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se guardaron los cambios correctamente!',
                    'success'
                )
            </script>
        <?php
        }

        if (isset($_GET['errorEditar'])) {
        ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Problemas para guardar los cambios!',
                    'error'
                )
            </script>
        <?php
        }
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
                            <h1 class="card-title" style="font-size:15px"> <b>Esta visualizando el equipo - <?php echo $nombreEquipo ?></b></h1>
                        </div>

                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="administrar-equipos-seleccion.php?carpeta=<?php echo $carpeta ?> " style="float: right;" class="btn btn-danger "><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>
                                    <hr>
                                    <br>

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header bg-danger " id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn text-center text-white btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="fas fa-arrow-down"></i> <b>Desplegar Información del equipo</b> <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">


                                                    <div class="col-md-12 col-12">

                                                        <form action="../sdo-funciones/editar-equipo-cc.php" method="POST">

                                                            <fieldset class="fielset">
                                                                <legend>Información Técnica</legend>

                                                                <input type="hidden" name="carpeta" value="<?php echo $carpeta ?>">

                                                                <div class="form-row">
                                                                    <div class="form-group  col-12  col-md-3">
                                                                        <label for="inputIDUnico" style="font-size:12px">ID Único:<code>*</code></label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm " required id="inputIDUnico" name="inputIDUnico" value="<?php echo $identificadorUnico ?>" placeholder="Escriba el ID Único">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group  col-12 col-md-3">
                                                                        <label for="inputInventario" style="font-size:12px">Numero Inventario:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm " id="inputInventario" name="inputInventario" value="<?php echo $numeroInventario ?>" placeholder="Escriba el número de inventario">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="IDLicitacion" style="font-size:12px">ID Licitación:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="IDLicitacion" value="<?php echo $idLicitacion ?>" name="IDLicitacion" placeholder="Escriba el ID de licitación">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="IDLicitacion" style="font-size:12px">Orden de compra:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="ordenCompra" value="<?php echo $ordenCompra ?>" name="ordenCompra" placeholder="Escriba la orden de compra">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="IDLicitacion" style="font-size:12px">F. de mantenimiento:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="inputMantenimiento" value="<?php echo $mante ?>" name="inputMantenimiento" placeholder="Escriba la orden de compra">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="fechaRecepcion" style="font-size:12px">Fecha de recepción:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="date" class="form-control form-control-sm" id="fechaRecepcion" value="<?php echo $fechaRecepcion ?>" name="fechaRecepcion">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="fechaCompra" style="font-size:12px">Fecha de compra:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="date" class="form-control form-control-sm" id="fechaCompra" value="<?php echo $fechaCompra ?>" name="fechaCompra">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="fechaMarcha" style="font-size:12px">Fecha en marcha:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="date" class="form-control form-control-sm" id="fechaMarcha" value="<?php echo $fechaMarcha ?>" name="fechaMarcha">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="clase" style="font-size:12px">Clase:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="clase" value="<?php echo $clase ?>" name="clase">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="subClase" style="font-size:12px">Sub-clase:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="subClase" value="<?php echo $subclase ?>" name="subClase">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="complejidad" style="font-size:12px">Complejidad Tecnológica:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="complejidad" value="<?php echo $complejidadTec ?>" name="complejidad">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="riesgo" style="font-size:12px">Nivel de riesgo:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="riesgo" value="<?php echo $nivelRiesgo ?>" name="riesgo">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="riesgo" style="font-size:12px">Costo Mantención Anual: $</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="costoMantencion" value="<?php echo $costoMantAnual ?>" name="costoMantencion">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <legend>Información Básica</legend>
                                                                <div class="row">
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="nombreEquipo" style="font-size:12px">Nombre del equipo:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="nombreEquipo" value="<?php echo $nombreEquipo ?>" name="nombreEquipo">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="nombreEquipo" style="font-size:12px">Valor con Iva:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="valorConIva" value="<?php echo $valorConIva ?>" name="valorConIva">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="marcaEquipo" style="font-size:12px">Marca:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="marcaEquipo" value="<?php echo $marca ?>" name="marcaEquipo">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="modeloEquipo" style="font-size:12px">Modelo:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="modeloEquipo" value="<?php echo $modelo ?>" name="modeloEquipo">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="fechaFabricación" style="font-size:12px">Año de fabricación:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="fechaFabricación" value="<?php echo $AhnoFrabricacion ?>" name="fechaFabricación">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="vidaUtil" style="font-size:12px">Vida Útil:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="vidaUtil" value="<?php echo $vidaUtil ?>" name="valorConIva">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="versionSoftware" style="font-size:12px">Versión de Software:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="versionSoftware" value="<?php echo $versionSoftware ?>" name="versionSoftware">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="paisOrigen" style="font-size:12px">Pais de origen:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="paisOrigen" value="<?php echo $paisOrigen ?>" name="paisOrigen">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="terminoGarantia" style="font-size:12px">Termino de garantía:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="terminoGarantia" value="<?php echo $terminoGarantia ?>" name="terminoGarantia">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-12 col-md-3">
                                                                        <label for="numSerie" style="font-size:12px">Termino de garantía:</label>
                                                                        <div class="input-group mb-3 ">
                                                                            <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $numeroSerie ?>" name="numSerie">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <legend>Ubicacion</legend>
                                                                <div class="row">
                                                                    <div class="form-group col-md-3 col-12">
                                                                        <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                                        <select class="form-control form-control-sm" id="servicio" required name="servicio">
                                                                            <option value="" disabled selected>-- Seleccione --</option>

                                                                            <?php

                                                                            $sqlServicios = "SELECT * FROM servicios";
                                                                            $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                            while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                                $datos = [];

                                                                                $datos['idServicio'] = $rowServicios['idServicio'];
                                                                                $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                                $idServicio  = $datos['idServicio'];
                                                                                $nomServicio  = $datos['nomServicio'];

                                                                                if ($idServicio == $idServicioDB) {
                                                                            ?>
                                                                                    <option selected value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                                <?php
                                                                                }

                                                                                ?>


                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-12">
                                                                        <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                                        <input type="text" class="form-control form-control-sm" id="recinto" name="recinto" value="<?php echo $recinto ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-12">
                                                                        <label for="pma" style="font-size: 12px;">PMA:</label>
                                                                        <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $pma ?>">
                                                                    </div>
                                                                    <div class="form-group col-md-3 col-12">
                                                                        <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                        <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas" value="<?php echo $coordenadas ?>">
                                                                    </div>

                                                                </div>
                                                    </div>

                                                    <hr>
                                                    <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $id ?>">
                                                    <button type="submit" class="btn  btn-block  justify-content-center btn-success"><b>Guardar Cambios</b> </button>
                                                    </fieldset>
                                                    </form>

                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                    <hr>
                                    <ul class="nav nav-pills mb-3 justify-content-center text-center" id="pills-tab" role="tablist">
                                        <li class="nav-item" style="font-size: 12px" role="presentation">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">DashBoard</a>
                                        </li>

                                        <li class="nav-item" style="font-size: 12px" role="presentation">
                                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Sub-Equipos</a>
                                        </li>
                                    </ul>
                                    <hr>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <a href="hojastecnicas-cc.php?idEquipo=<?php echo $id ?>" style="text-decoration: none;" class="text-info">
                                                        <div class="info-box bg-info">
                                                            <span class="info-box-icon bg-secondary elevation-1">
                                                                <i class="fas fa-file-alt"></i>
                                                            </span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text text-center" style="font-size: 18px;"> <b>Hojas Técnicas</b> </span>
                                                                <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><b>
                                                                        <?php
                                                                        if ($cantidad_hojas == 0) {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $cantidad_hojas;
                                                                        }
                                                                        ?>

                                                                    </b> </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <a href="solicitudesCompra-cc.php?idEquipo=<?php echo $id ?>" style="text-decoration: none;" >
                                                        <div class="info-box bg-success">
                                                            <span class="info-box-icon bg-secondary elevation-1">
                                                                <i class="fas fa-cart-arrow-down"></i>
                                                            </span>

                                                            <div class="info-box-content">
                                                                <span class="info-box-text text-center" style="font-size: 18px;"> <b>Solicitudes de Compra</b> </span>
                                                                <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><b><?php

                                                                                                                                                if ($cantidad_gastos == 0) {
                                                                                                                                                    echo 0;
                                                                                                                                                } else {
                                                                                                                                                    echo 0;
                                                                                                                                                }
                                                                                                                                                ?>

                                                                    </b> </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <a href="archivosAdjuntosEquipos-cc.php?idEquipo=<?php echo $id ?>" style="text-decoration: none;" class="text-danger">
                                                        <div class="info-box bg-danger">
                                                            <span class="info-box-icon bg-secondary elevation-1">
                                                                <i class="fas fa-file-upload"></i>
                                                            </span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text text-center" style="font-size: 18px;"> <b>Archivos Adjuntos</b> </span>
                                                                <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><b><?php

                                                                                                                                                if ($cantidad_gastos == 0) {
                                                                                                                                                    echo 0;
                                                                                                                                                } else {
                                                                                                                                                    echo 0;
                                                                                                                                                }
                                                                                                                                                ?>

                                                                    </b> </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <a href="gastoEquipo-cc.php?id=<?php echo $id ?>" style="text-decoration: none;" class="text-primary">
                                                        <div class="info-box bg-primary">
                                                            <span class="info-box-icon bg-secondary elevation-1">
                                                                <i class="fas fa-money-check-alt"></i>
                                                            </span>
                                                            <div class="info-box-content">

                                                                <span class="info-box-text text-center" style="font-size: 18px;"> <b>Dinero Gastado</b> </span>
                                                                <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><b><?php

                                                                                                                                                if ($cantidad_gastos == 0) {
                                                                                                                                                    echo 0;
                                                                                                                                                } else {
                                                                                                                                                    $cantidad_gastos = moneda_chilena($cantidad_gastos);
                                                                                                                                                    echo $cantidad_gastos;
                                                                                                                                                }
                                                                                                                                                ?>

                                                                    </b> </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">SubEquipo</div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>




                </div>
                <!-- /.card-body -->
        </div>
    </div>
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
        $(customSwitch1).click(function() {
            customSwitch1 = document.querySelector('#customSwitch1');
            fielset = document.querySelector(".fielset");
            if (customSwitch1.checked) {
                fielset.disabled = false;
            } else {
                fielset.disabled = true;
            }
        })
    </script>
</body>

</html>