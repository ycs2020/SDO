<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

if (isset($_POST['idHojaTec'])) {
    $idHojaTec = $_POST['idHojaTec'];
    $folioHojaTec = trim($_POST['folioHojaTec']);


    if ($idHojaTec > 0) {

        if (empty($folioHojaTec) || $folioHojaTec == '') {
        } else {

            $folioSoliCompra2 = "HOJA-" . $idHojaTec;

            echo $folioSoliCompra2;
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = '../sdo-archivosHojasTecnicas/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Archivos " . $folioHojaTec . ".zip";

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

if (isset($_SESSION['idHoja'])) {
    $idHoja = $_SESSION['idHoja'];
}

if (isset($_SESSION['idUnico'])) {
    $idEquipo = $_SESSION['idUnico'];
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar Equipo</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            // Show div by removing inline display none style rule
            $(".show-visualizar").click(function() {
                $("#rowMenu1").show();
                $("#rowMenu2").hide();
                $("#VisualizarHV").hide();
                $("#ListarHT").show();
            });
            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu1").hide();
                $("#rowMenu2").show();
                $("#VisualizarHV").show();
                $("#ListarHT").hide();
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
</head>

<body class="hold-transition sidebar-mini dark-mode" id="body">
    <!-- <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> -->

    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><b>Consultar Equipo</b></h1>
                        </div>
                        <div class="col-sm-6">

                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3"><b>Modo Claro</b></label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")
                                    document.body.classList.toggle("dark-mode");
                                }
                            </script>
                            <br>
                            <br>
                        </div>


                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php

            if (isset($_GET['noExiste'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No existe',
                        text: 'El registro del equipo no existe',
                    })
                </script>
            <?php
            }


            if (isset($_GET['existe'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Datos cargados del equipo',
                    })
                </script>
            <?php
            }

            if (isset($_GET['faltanCampos'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: 'Faltan datos por ingresar',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noId'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No existe',
                        text: 'ID del registro no valido',
                    })
                </script>
            <?php
            }


            if (isset($_GET['exito'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Registro del equipo modificado exitosamente',
                    })
                </script>
            <?php
            }

            if (isset($_GET['errorModificar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Modificar',
                        text: 'Error al modificar el registro del equipo',
                    })
                </script>
            <?php
            }

            if (isset($_GET['exitoTecnica'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al crear hoja técnica del equipo',
                    })
                </script>
            <?php
            }

            if (isset($_GET['errorTecnica'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al crear hoja técnica del equipo',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noidTec'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error ID',
                        text: 'ID Hoja Técnica no valida',
                    })
                </script>
            <?php
            }

            if (isset($_GET['exitoModTec'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Modificaciones Guardadas',
                    })
                </script>
            <?php
            }

            if (isset($_GET['noModTec'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Modificar',
                        text: 'Ocurrio un error al guardar las modificaciones',
                    })
                </script>
            <?php
            }

            if (isset($_GET['exitoEliminar'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Exito al elimianr la hoja técnica',
                    })
                </script>
            <?php
            }

            if (isset($_GET['filtrosVacios'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No valido',
                        text: 'Por favor, seleccione un filtro de busqueda',
                    })
                </script>
            <?php
            }

            if (isset($_GET['errorModTec'])) {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'SQL',
                        text: 'No fue posible actualizar el registro',
                    })
                </script>
            <?php
            }




            ?>
            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Buscar Registro</b>
                            </h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">

                                    <a href="index.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b></a>
                                    <br>
                                    <br>

                                    <form action="../sdo-funciones/validaEquipo.php" method="POST">

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <fieldset class="border p-3 ">
                                                    <legend class="w-auto"><b>Buscar por:</b></legend>

                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <select name="opcion" id="opcion" class="form-control">
                                                            <option value="" selected disabled>--Seleccione--</option>
                                                            <option value="folio">Folio</option>
                                                            <option value="inventario">Número Inventario</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-group mb-3">

                                                        <input type="text" style="text-transform:uppercase;" disabled class="form-control" id="busqueda" name="busqueda">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto"><b>Buscar por ID:</b></legend>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <select name="opcionId" id="opcionId" class="form-control">
                                                            <option value="" selected disabled>--Seleccione--</option>
                                                            <?php

                                                            $sqlSelectId = "SELECT * FROM hoja_de_vida";
                                                            $resSqlSelectId = mysqli_query($conexion, $sqlSelectId);

                                                            while ($rowSelectId = $resSqlSelectId->fetch_assoc()) {

                                                                $datos = [];
                                                                $datos['idHoja'] = $rowSelectId['idHoja'];
                                                                $datos['idUnico'] = $rowSelectId['idUnico'];
                                                                $idHojaSelect = $datos['idHoja'];
                                                                $idUnico = $datos['idUnico'];

                                                            ?>
                                                                <option value="<?php echo $idHojaSelect ?>"><?php echo $idUnico ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <label for="idFiltra">Filtrar por id</label>
                                                    <input list="ids" name="idFiltra" class="form-control" placeholder="Escriba el id para filtrar" />
                                                    <datalist id="ids">

                                                        <?php

                                                        $sqlIdUnico = "SELECT * FROM hoja_de_vida";
                                                        $res = mysqli_query($conexion, $sqlIdUnico);

                                                        while ($row = $res->fetch_assoc()) {
                                                            $datos[''];
                                                            $datos['idUnico'] = $row['idUnico'];
                                                            $idUnico = $datos['idUnico'];
                                                        ?>
                                                            <option><?php echo $idUnico; ?></option>
                                                        <?php
                                                        }

                                                        ?>


                                                    </datalist>
                                                </fieldset>
                                            </div>

                                            <button class="btn btn-secondary btn-block" type="submit"><b><i class="fas fa-search"></i> Buscar</b></button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><b>Consultar Información del equipo</b></h3>
                        </div>
                        <br>
                        <br>

                        <div style="text-align:center;">
                            <button class="btn bg-yellow show-listar" id="ListarHT" name="ListarHT"><b>Listar Hoja Técnica </b></button>
                            <button class="btn bg-yellow show-visualizar" id="VisualizarHV" style="display:none;" name="VisualizarHV"><b>Visualizar Hoja de vida</b></button>

                        </div>


                        <div class="row" id="rowMenu1" name="rowMenu1">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <?php

                                    if (isset($_SESSION['idHoja']) == '') {

                                    ?>
                                        <button type="button" disabled id="editar" class="btn btn-success" onclick="editar()" style="float:right;"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;Habilitar Edición</button>
                                        <button type="button" disabled id="inputMozi" class="btn btn-info" style="float:right;margin-right:10px"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;Descargar Hoja de Vida</button>


                                    <?php


                                    } else {

                                    ?>
                                        <button type="button" id="editar" class="btn btn-success" onclick="editar()" style="float:right;"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;<b>Habilitar Edición</b></button>
                                        <input type="hidden" value="NO" id="editando">

                                        <?php

                                        $contarArchivosHojasdeVida =  contarArchivosHojasdeVida($idHoja);

                                        $sqlParaDescarga = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
                                        $resParaDescarga = mysqli_query($conexion, $sqlParaDescarga);

                                        while ($rowParaDescarga = $resParaDescarga->fetch_assoc()) {
                                            $datos = [];
                                            $datos['folio'] = $rowParaDescarga['folio'];
                                            $datos['idUnico'] = $rowParaDescarga['idUnico'];
                                            $folio = $datos['folio'];
                                            $idUnico = $datos['idUnico'];
                                        }

                                        if ($contarArchivosHojasdeVida > 0) {
                                        ?>
                                            <form action="consultar-equipo.php" method="POST">

                                                <input type="hidden" name="idHojaDesc" value="<?php echo $idHoja; ?>">
                                                <input type="hidden" name="folioDesc" id="folioSoliCompraDescarga" value="<?php echo $folio; ?>">
                                                <input type="hidden" name="idUnico" id="idUnico" value="<?php echo $idUnico; ?>">

                                                <button type="submit" id="archivos" class="btn btn-warning" style="float:right;margin-right:10px"><i class="fas fa-file-download"></i>&nbsp;&nbsp;<b>Descargar Archivos</b></button>
                                            </form>
                                        <?php
                                        } else {
                                        ?>
                                            <button type="button" title="No Adjunta Archivos" disabled id="archivos" class="btn btn-warning" style="float:right;margin-right:10px"><i class="fas fa-file-download"></i>&nbsp;&nbsp;<b>Descargar Archivos</b></button>
                                        <?php
                                        }

                                        ?>
                                        <form target="_blank" action="../sdo-pdf/SDO-Hoja-Vida.php" method="POST">

                                            <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                            <button type="submit" id="inputMozi" class="btn btn-info" style="float:right;margin-right:10px"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<b>Descargar Hoja de Vida</b></button>

                                        </form>
                                    <?php
                                    }

                                    ?>
                                    <br>
                                    <br>
                                    <br>

                                    <form action="../sdo-funciones/modificar-equipo-hojadevida.php" method="POST" enctype="multipart/form-data">

                                        <?php

                                        $idHoja = $_SESSION['idHoja'] ?? 0;

                                        $sqlEquipo = "SELECT * FROM hoja_de_vida WHERE idHoja = ${idHoja}";
                                        $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {

                                            $datos = [];

                                            $datos['folio'] = $rowEquipo['folio'];
                                            $datos['idUnico'] = $rowEquipo['idUnico'];
                                            $datos['numInventario'] = $rowEquipo['numInventario'];
                                            $datos['fechaRecepcion'] = $rowEquipo['fechaRecepcion'];
                                            $datos['nomEquipo'] = $rowEquipo['nomEquipo'];
                                            $datos['valorConIva'] = $rowEquipo['valorConIva'];
                                            $datos['marca'] = $rowEquipo['marca'];
                                            $datos['idLicitacion'] = $rowEquipo['idLicitacion'];
                                            $datos['modelo'] = $rowEquipo['modelo'];
                                            $datos['ordenCompra'] = $rowEquipo['ordenCompra'];
                                            $datos['numSerie'] = $rowEquipo['numSerie'];
                                            $datos['fechaCompra'] = $rowEquipo['fechaCompra'];
                                            $datos['fechaMarcha'] = $rowEquipo['fechaMarcha'];
                                            $datos['idServicio'] = $rowEquipo['idServicio'];
                                            $datos['recinto'] = $rowEquipo['recinto'];
                                            $datos['textoAccesorios'] = $rowEquipo['textoAccesorios'];
                                            $datos['textoInsumos'] = $rowEquipo['textoInsumos'];
                                            $datos['nomProveedor'] = $rowEquipo['nomProveedor'];
                                            $datos['direccionProveedor'] = $rowEquipo['direccionProveedor'];
                                            $datos['telProveedor'] = $rowEquipo['telProveedor'];
                                            $datos['emailProveedor'] = $rowEquipo['emailProveedor'];
                                            $datos['nomContacto'] = $rowEquipo['nomContacto'];
                                            $datos['observaciones'] = $rowEquipo['observaciones'];
                                            $datos['textoPautas'] = $rowEquipo['textoPautas'];
                                            $datos['textoCertificados'] = $rowEquipo['textoCertificados'];
                                            $datos['textoManuales'] = $rowEquipo['textoManuales'];
                                            $datos['textoOtros'] = $rowEquipo['textoOtros'];
                                            $datos['claseEquipo'] = $rowEquipo['claseEquipo'];
                                            $datos['subClaseEquipo'] = $rowEquipo['subClaseEquipo'];
                                            $datos['compleEquipo'] = $rowEquipo['compleEquipo'];
                                            $datos['nivelRiesgo'] = $rowEquipo['nivelRiesgo'];
                                            $datos['ahnoFabricacion'] = $rowEquipo['ahnoFabricacion'];
                                            $datos['vidaUtil'] = $rowEquipo['vidaUtil'];
                                            $datos['versionSoftware'] = $rowEquipo['versionSoftware'];
                                            $datos['paisOrigen'] = $rowEquipo['paisOrigen'];
                                            $datos['terminoGarantia'] = $rowEquipo['terminoGarantia'];
                                            $datos['costoAnualMantenimiento'] = $rowEquipo['costoAnualMantenimiento'];
                                            $datos['frecuenciaMantenimiento'] = $rowEquipo['frecuenciaMantenimiento'];


                                            $folio = $datos['folio'];
                                            $idUnico = $datos['idUnico'];
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
                                            $terminoGarantia = $datos['terminoGarantia'];
                                            $costoAnualMantenimiento = $datos['costoAnualMantenimiento'];
                                            $frecuenciaMantenimiento = $datos['frecuenciaMantenimiento'];
                                        }
                                        ?>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra">ID:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pallet"></i></span>
                                                    </div>
                                                    <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" name="idUnicoMod" id="idUnicoMod" class="form-control" value="<?php echo isset($idUnico) ?  $idUnico : '';  ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra">Folio:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pallet"></i></span>
                                                    </div>
                                                    <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" value="<?php echo isset($folio) ?  $folio : '';  ?>">
                                                </div>
                                            </div>



                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra">N° de Inventario:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-pallet"></i></span>
                                                    </div>
                                                    <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="numeroInventario" name="numeroInventario" value="<?php echo isset($numInventario) ?  $numInventario : '';  ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="codigoBarra">Fecha de recepción:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input disabled style="text-transform:uppercase;cursor: not-allowed" type="date" required class="form-control" id="fechaRecepcion" name="fechaRecepcion" value="<?php echo isset($fechaRecepcion) ?  $fechaRecepcion : '';  ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Datos Técnicos del equipo:</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="codigoBarra">Nombre del equipo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="nombreEquipo" name="nombreEquipo" value="<?php echo isset($nomEquipo) ?  $nomEquipo : '';  ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="codInventario">Valor con IVA:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-money-bill-wave-alt"></i></span>
                                                        </div>
                                                        <input type="number" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($valorConIva) ?  $valorConIva : '';  ?>" class="form-control" id="valorIva" name="valorIva">
                                                    </div>
                                                    <label for="" id="label" style="color: greenyellow; font-weight:bold"></label>
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

                                                <div class="form-group col-md-6">

                                                    <label for="codInventario">Marca:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($marca) ?  $marca : '';  ?>" class="form-control" id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                                    </div>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">ID - Licitación:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($idLicitacion) ?  $idLicitacion : '';  ?>" class="form-control" id="idLicitacion" name="idLicitacion" placeholder="ID de Licitación">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="departamento">Modelo:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($modelo) ?  $modelo : '';  ?>" name="modeloEquipo" id="modeloEquipo" class="form-control" placeholder="Escriba el modelo del equipo">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="departamento">Orden de compra: (1 Orden de compra por linea)<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                                                        </div>
                                                        <textarea cols="30" rows="4" disabled style="text-transform:uppercase;cursor: not-allowed" name="ordenCompra" id="ordenCompra" class="form-control"><?php echo isset($ordenCompra) ?  $ordenCompra : '';  ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="exampleInputCodigoTarjeta">N° de Serie:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($numSerie) ?  $numSerie : '';  ?>" id="numeroSerie" name="numeroSerie" placeholder="Escriba el número de serie">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Fecha de Compra:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="date" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($fechaCompra) ?  $fechaCompra : '';  ?>" class="form-control" id="fechaCompra" name="fechaCompra">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Fecha Puesta en Marcha:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>

                                                        <input type="date" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($fechaMarcha) ?  $fechaMarcha : '';  ?>" class="form-control" id="fechaEnMarcha" name="fechaEnMarcha">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Servicio/Unidad:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                        </div>
                                                        <select name="idServicio" class="form-control" disabled style="cursor: not-allowed" required id="idServicio">
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <?php

                                                            $queryServicio = "SELECT * FROM servicios";
                                                            $resServicio = mysqli_query($conexion, $queryServicio);

                                                            while ($rowServicios = $resServicio->fetch_assoc()) {

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
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Recinto:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        </div>

                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($recinto) ?  $recinto : '';  ?>" id="nombreRecinto" name="nombreRecinto" placeholder="¿DÓNDE SE ENCUENTRA EL EQUIPO?">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Información Extra:</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="codigoBarra">Clase:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="claseEquipo" name="claseEquipo" value="<?php echo isset($claseEquipo) ?  $claseEquipo : '';  ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="codInventario">Sub-clase:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="subClaseEquipo" name="subClaseEquipo" value="<?php echo isset($subClaseEquipo) ?  $subClaseEquipo : '';  ?>" placeholder="Tipo de sub-clase del equipo">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">

                                                    <label for="codInventario">Complejidad Tecnológica:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="compleEquipo" name="compleEquipo" value="<?php echo isset($compleEquipo) ?  $compleEquipo : '';  ?>" placeholder="Escriba la complejidad técnologica del equipo">
                                                    </div>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Nivel de riesgo:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="nivelRiesgo" name="nivelRiesgo" value="<?php echo isset($nivelRiesgo) ?  $nivelRiesgo : '';  ?>" placeholder="Escriba el nivel de riesgo del equipo">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="departamento">Año Fabricación:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" name="ahnoFabricacion" id="ahnoFabricacion" class="form-control" value="<?php echo isset($ahnoFabricacion) ?  $ahnoFabricacion : '';  ?>" placeholder="ESCRIBA EL AÑO DE FABRICACIÓN DEL EQUIPO">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="departamento">Vida Util:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" name="vidaUtil" id="vidaUtil" class="form-control" value="<?php echo isset($vidaUtil) ?  $vidaUtil : '';  ?>" placeholder="Escriba los años de vida util del equipo"></input>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Versión del software:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" id="versionSoftware" name="versionSoftware" value="<?php echo isset($versionSoftware) ?  $versionSoftware : '';  ?>" placeholder="Escriba la versión del software">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Pais Origen:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-globe-americas"></i></span>
                                                        </div>
                                                        <input type="text" disabled style="text-transform:uppercase;cursor: not-allowed" class="form-control" id="paisOrigen" name="paisOrigen" value="<?php echo isset($paisOrigen) ?  $paisOrigen : '';  ?>" placeholder="ESCRIBA EL PAIS DE ORIGEN DEL EQUIPO">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Termino de garantía:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-dizzy"></i></span>
                                                        </div>

                                                        <input type="date" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" id="terminoGarantia" name="terminoGarantia" value="<?php echo isset($terminoGarantia) ?  $terminoGarantia : '';  ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Costo Mantenimiento Anual:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                        </div>
                                                        <input type="number" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" name="costoAnualMantenimiento" id="costoAnualMantenimiento" value="<?php echo isset($costoAnualMantenimiento) ?  $costoAnualMantenimiento : '';  ?>" placeholder="COSTO ANUAL DEL MANTENIMIENTO DEL EQUIPO">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Frecuencia Del Mantenimiento:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-wave-square"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" id="frecuenciaMantenimiento" value="<?php echo isset($frecuenciaMantenimiento) ?  $frecuenciaMantenimiento : '';  ?>" name="frecuenciaMantenimiento" placeholder="ESCRIBA LA FRECUENCIA DEL MANTENIMIENTO">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Información de Técnica y de Mantenimiento Existente</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
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
                                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                        <div class="custom-file">
                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosPauta[]" name="adjuntosPauta[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <label for="exampleInputTareasControlCent">Nombre de los archivos subidos :</label>
                                                        <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                            <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="No elimine el texto actual, ya que aqui esta escrito los archivos subidos con anterioridad. Si desea subir un archivo nuevo, debe escribir su nombre junto al texto actual separado por comas." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                        </div>
                                                        <textarea name="textoPautas" id="textoPautas" cols="30" rows="5" class="form-control"><?php echo isset($textoPautas) ?  $textoPautas : '';  ?></textarea>
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
                                                <div class="form-group col-md-12">
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
                                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                        <div class="custom-file">
                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosCertificados[]" name="adjuntosCertificados[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <label for="exampleInputTareasControlCent">Nombre de los archivos subidos :</label>
                                                        <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                            <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="No elimine el texto actual, ya que aqui esta escrito los archivos subidos con anterioridad. Si desea subir un archivo nuevo, debe escribir su nombre junto al texto actual separado por comas." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                        </div>
                                                        <textarea name="textoCertificados" id="textoCertificados" cols="30" rows="5" class="form-control"><?php echo isset($textoCertificados) ?  $textoCertificados : '';  ?></textarea>
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
                                            </div><!-- .certificados-->
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
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
                                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                        <div class="custom-file">
                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosManuales[]" name="adjuntosManuales[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <label for="exampleInputTareasControlCent">Nombre de los archivos subidos :</label>
                                                        <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                            <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="No elimine el texto actual, ya que aqui esta escrito los archivos subidos con anterioridad. Si desea subir un archivo nuevo, debe escribir su nombre junto al texto actual separado por comas." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                        </div>
                                                        <textarea name="textoManuales" id="textoManuales" cols="30" rows="5" class="form-control"><?php echo isset($textoManuales) ?  $textoManuales : '';  ?></textarea>
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
                                                <div class="form-group col-md-12">
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
                                                        <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: (Imagenes y cualquier otro archivo que no cabe en las anteriores categorias)</label><br>
                                                        <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg</label>
                                                        <div class="custom-file">
                                                            <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosOtros[]" name="adjuntosOtros[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml, .png, .jpeg, .gif, .svg, .jpg">
                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                        </div>
                                                        <br>
                                                        <br>

                                                        <label for="exampleInputTareasControlCent">Nombre de los archivos subidos :</label>
                                                        <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                            <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="No elimine el texto actual, ya que aqui esta escrito los archivos subidos con anterioridad. Si desea subir un archivo nuevo, debe escribir su nombre junto al texto actual separado por comas." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                        </div>
                                                        <textarea name="textoOtros" id="textoOtros" cols="30" rows="5" class="form-control"><?php echo isset($textoOtros) ?  $textoOtros : '';  ?></textarea>

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
                                            </div><!-- .otros-->
                                            <div class="form-row">
                                                <div class="from-group col-md-6">
                                                    <label for="accesorios" class="form-label">Accesorios:</label>
                                                    <textarea name="textoAccesorios" id="textoAccesorios" rows="3" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" placeholder="ESCRIBA LOS ACCESORIOS DEL EQUIPO"><?php echo isset($textoAccesorios) ?  $textoAccesorios : '';  ?></textarea>
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="accesorios" class="form-label">Insumos:</label>
                                                    <textarea name="textoInsumos" id="textoInsumos" rows="3" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" placeholder="ESCRIBA LOS INSUMOS DEL EQUIPO"><?php echo isset($textoInsumos) ?  $textoInsumos : '';  ?></textarea>
                                                </div>
                                            </div>

                                        </fieldset>
                                        <br>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Datos de contacto servicio técnico</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Proveedor:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-store"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($nomProveedor) ?  $nomProveedor : '';  ?>" id="nombreProveedor" name="nombreProveedor" placeholder="ESCRIBA EL NOMBRE DEL PROVEEDOR">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Dirección:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($direccionProveedor) ?  $direccionProveedor : '';  ?>" id="direccionProveedor" name="direccionProveedor" placeholder="ESCRIBA LA DIRECCIÓN EXACTA DEL PROVEEDOR">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Teléfono/Fax:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                        </div>
                                                        <input type="tel" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($telProveedor) ?  $telProveedor : '';  ?>" id="telefonoProveedor" name="telefonoProveedor" placeholder="ESCRIBA EL NÚMERO TELÉFONICO DE CONTACTO CON CÓDIGO DE AREA ">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">E-mail</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($emailProveedor) ?  $emailProveedor : '';  ?>" id="emailProveedor" name="emailProveedor" placeholder="ESCRIBA EL EMAIL DE CONTACTO">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Nombre del contacto</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" disabled style="text-transform:uppercase;cursor: not-allowed" value="<?php echo isset($nomContacto) ?  $nomContacto : '';  ?>" id="nombreContactoProveedor" name="nombreContactoProveedor" placeholder="ESCRIBA EL NOMBRE DEL CONTACTO">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="">Observaciones o sugerencias:</label>
                                                <textarea name="observacionesContactoProveedor" id="observacionesContactoProveedor" disabled style="text-transform:uppercase;cursor: not-allowed" rows="10" class="form-control" placeholder="Escriba todas las observaciones necesarios aqui o sugerencias..."><?php echo isset($observaciones) ?  $observaciones : '';  ?></textarea>
                                            </div>
                                        </fieldset>
                                        <br>
                                        <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                        <button type="submit" disabled id="guardar" class="btn btn-primary btn-block">Guardar</button>
                                    </form>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="row" id="rowMenu2" name="rowMenu2" style="display: none">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <!-- Button trigger modal -->
                                    <?php

                                    if ($idHoja == 0) {
                                    ?>
                                        <button type="button" disabled id="inputMozi" data-toggle="modal" data-target="#exampleModalLong" class="btn btn-primary" style="float:right;margin-right:10px"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<b>Añadir Hoja Técnica<b></button>
                                        <button type="button" id="inputMozi" disabled class="btn btn-danger" style="float:right;margin-right:10px"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;<b>Exportar a Excel<b></button>
                                        
                                    <?php
                                    } else {
                                    ?>
                                        <button type="button" id="inputMozi" data-toggle="modal" data-target="#exampleModalLong" class="btn btn-primary" style="float:right;margin-right:10px"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<b>Añadir Hoja Técnica<b></button>
                                        <button type="button" disabled id="<?php echo $idHoja ?>" href="../sdo-funciones/generar-excel-hojatecnica.php" id="inputMozi"  class="btn btn-danger" style="float:right;margin-right:10px"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;<b>Ir a generar reporte</button>

                                    <?php
                                    }

                                    ?>

                                    <!-- Modal -->
                                    <div class="modal fade in" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Hoja Técnica</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <form action="../sdo-funciones/guardar-equipo-hojatecnica.php" method="POST" enctype="multipart/form-data">

                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta">Tipo Mantención:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                    </div>
                                                                    <select name="tipoMantencion" class="form-control" required id="tipoMantencion">
                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta">Ejecutado Por:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="ejecutador">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta">Trabajo Realizado(Especifique):<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                  
                                                                    <textarea name="trabajo" id="" maxlength="4" cols="30" rows="3" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta">Problema que presenta(Detalles):</label>
                                                                <div class="input-group mb-3">

                                                                    <textarea name="problema" id="" maxlength="255" cols="30" rows="3" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta">Acciones Realizadas(Detalles):</label>
                                                                <div class="input-group mb-3">

                                                                    <textarea name="acciones" id="" maxlength="255" cols="30" rows="3" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <fieldset class="border p-3">
                                                            <legend class="w-auto">Tipo Trabajo:</legend>
                                                            <div class="form-row">

                                                                <div class="form-group  text-center col-md-6" align="middle">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="garantia">
                                                                        <label class="form-check-label" for="inlineCheckbox1">Garantía</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline" align="middle">
                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="convenio">
                                                                        <label class="form-check-label" for="inlineCheckbox2">Convenio</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group  col-md-6">
                                                                    <label for="ordenCompra" class="form-label"> Orden Compra: </label>
                                                                    <input class="form-control" id="ordenCompra" type="text" name="ordenCompra">
                                                                </div>
                                                                <div class="form-group  col-md-6">
                                                                    <label for="ordenCompra" class="form-label"> Otro: </label>
                                                                    <input class="form-control" id="otroTipoTrabajo" type="text" name="otroTipoTrabajo" placeholder="Especifique">
                                                                </div>

                                                            </div>
                                                        </fieldset>
                                                        <br>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                                <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                <div class="custom-file">
                                                                    <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosTecnica[]" name="adjuntosTecnica[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputTareasControlCent">Observaciones: </label><br>
                                                                <div class="form-group">
                                                                    <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Escriba aqui las observaciones de la hoja técnica"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <label for="">Repuestos Utilizados:</label>
                                                        <div class="form-row fieldGroup">
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCoordenadas">Especie:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="especiesHT[]" placeholder="Nombre">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCoordenadas">Cantidad:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-arrow-circle-up"></i></span>
                                                                    </div>
                                                                    <input type="number" min="1" class="form-control" name="cantidad[]" placeholder="Cantidad">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCoordenadas">$:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                    </div>
                                                                    <input type="number" min="1" class="form-control" name="precio[]" placeholder="precio">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="exampleInputCoordenadas">&nbsp;</label>
                                                                <div class="input-group mb-3">
                                                                    <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Material</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover " style="font-weight: bold;">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Folio N°<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Tipo<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Ejecutado Por<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Trabajo Realizado<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Valor Total<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Acciones<center>
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php


                                                    $sqlSelectHT = "SELECT * FROM hoja_tecnica WHERE idHoja = ${idHoja} ORDER BY fechaCreado DESC ";
                                                    $resSelectHT = mysqli_query($conexion, $sqlSelectHT);

                                                    while ($row = $resSelectHT->fetch_assoc()) {

                                                        $datos = [];

                                                        $datos['idHojaTec'] = $row['idHojaTec'];
                                                        $datos['folioHojaTec'] = $row['folioHojaTec'];

                                                        $idHojaTec = $datos['idHojaTec'];

                                                        $folioHojaTec = $datos['folioHojaTec'];


                                                    ?>
                                                        <tr>

                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $date = $row['fechaCreado'];
                                                                    $fechaNueva = date("d/m/Y H:i", strtotime($date));
                                                                    echo $fechaNueva;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;text-transform: uppercase">
                                                                <center>
                                                                    <?php
                                                                    echo $row['folioHojaTec']
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;text-transform: uppercase">
                                                                <center>
                                                                    <?php
                                                                    echo $row['tipoMantencion']
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;text-transform: uppercase">
                                                                <center>
                                                                    <?php
                                                                    echo $row['ejecutadoPor']
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;text-transform: uppercase">
                                                                <center>
                                                                    <?php
                                                                    echo $row['trabajoRealizado']
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;text-transform: uppercase">
                                                                <center>
                                                                    <?php
                                                                    $total = 0;
                                                                    $sqlMateriales = "SELECT * FROM material_hojatecnica WHERE idHojaTec = ${idHojaTec}";
                                                                    $resMateriales = mysqli_query($conexion, $sqlMateriales);
                                                                    while ($rowMateriales = $resMateriales->fetch_assoc()) {
                                                                        $datos = [];
                                                                        $datos['precio'] = $rowMateriales['precio'];
                                                                        $datos['cantidad'] = $rowMateriales['cantidad'];
                                                                        $precio = $datos['precio'];
                                                                        $cantidad = $datos['cantidad'];

                                                                        $valor = $precio * $cantidad;
                                                                        $total +=  $valor;
                                                                    };
                                                                    $numeroFormateado = number_format($total, 0, '', '.');
                                                                    echo  "$" . $numeroFormateado;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; ">
                                                                <center>
                                                                    <?php

                                                                    $contarArchivosHojasTecnicas =  contarArchivosHojasTecnicas($idHojaTec);

                                                                    if ($contarArchivosHojasTecnicas > 0) {
                                                                    ?>
                                                                        <form action="consultar-equipo.php" method="POST" style="display: inline">

                                                                            <input type="hidden" name="folioHojaTec" value="<?php echo $folioHojaTec; ?>">
                                                                            <input type="hidden" name="idHojaTec" id="folioSoliCompraDescarga" value="<?php echo $idHojaTec; ?>">

                                                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-download"></i></button>

                                                                        </form>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <button type="submit" disabled class="btn btn-info btn-sm"><i class="fas fa-download"></i></button>

                                                                    <?php
                                                                    }
                                                                    ?>

                                                                    <form action="../sdo-pdf/SDO-Hoja-Tecnica.php" target="_blank" method="POST" style="display: inline">

                                                                        <input type="hidden" name="idHojaTec" value="<?php echo $idHojaTec ?>">
                                                                        <input type="hidden" name="nomEquipo" value="<?php echo $nomEquipo ?>">
                                                                        <input type="hidden" name="idServicio" value="<?php echo $idServicio ?>">
                                                                        <input type="hidden" name="marcaEquipo" value="<?php echo $marca ?>">
                                                                        <input type="hidden" name="serie" value="<?php echo $numSerie ?>">
                                                                        <input type="hidden" name="modelo" value="<?php echo $modelo ?>">
                                                                        <input type="hidden" name="inventario" value="<?php echo $numInventario ?>">
                                                                        <input type="hidden" name="recinto" value="<?php echo $recinto ?>">


                                                                        <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">

                                                                        <button class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i></button>
                                                                    </form>

                                                                    <!-- BOTON EDITAR-->

                                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar<?php echo $idHojaTec ?>" id="editar"><i class="fas fa-edit"></i></button>
                                                                    <!-- BOTON ELIMIANAR -->

                                                                    <a class="btn btn-danger btn-sm eliminar" id="<?php echo $idHojaTec ?>" href="#"><i class="fas fa-trash"></i></a>
                                                                </center>
                                                                <div class="modal fade in" id="modalEditar<?php echo $idHojaTec ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLongTitle">Editar Hoja Técnica</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <?php

                                                                                $sqlHojaTec = "SELECT * FROM hoja_tecnica WHERE idHojatec = $idHojaTec ";
                                                                                $resHojaTec = mysqli_query($conexion, $sqlHojaTec);

                                                                                while ($rowHojaTec = $resHojaTec->fetch_assoc()) {

                                                                                    $datos = [];
                                                                                    $datos['tipoMantencion'] = $rowHojaTec['tipoMantencion'];
                                                                                    $datos['ejecutadoPor'] = $rowHojaTec['ejecutadoPor'];
                                                                                    $datos['trabajoRealizado'] = $rowHojaTec['trabajoRealizado'];
                                                                                    $datos['problema'] = $rowHojaTec['problema'];
                                                                                    $datos['accionesRealizadas'] = $rowHojaTec['accionesRealizadas'];
                                                                                    $datos['tipoTrabajo'] = $rowHojaTec['tipoTrabajo'];
                                                                                    $datos['ordenCompra'] = $rowHojaTec['ordenCompra'];
                                                                                    $datos['otroTipoTrabajo'] = $rowHojaTec['otroTipoTrabajo'];


                                                                                    $tipoMantencion = $datos['tipoMantencion'];
                                                                                    $ejecutadoPor = $datos['ejecutadoPor'];
                                                                                    $trabajoRealizado = $datos['trabajoRealizado'];
                                                                                    $problema = $datos['problema'];
                                                                                    $accionesRealizadas = $datos['accionesRealizadas'];
                                                                                    $tipoTrabajo = $datos['tipoTrabajo'];
                                                                                    $ordenCompraTec = $datos['ordenCompra'];
                                                                                    $otroTipoTrabajo = $datos['otroTipoTrabajo'];
                                                                                }


                                                                                ?>


                                                                                <form action="../sdo-funciones/modificar-equipo-hojatecnica.php" id="formularioaenviar" method="POST" enctype="multipart/form-data">

                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputCodigoTarjeta">Tipo Mantención:<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                                                </div>
                                                                                                <select name="tipoMantencion" class="form-control" required id="tipoMantencion">

                                                                                                    <?php
                                                                                                    if ($tipoMantencion == 'MPE') {
                                                                                                    ?>
                                                                                                        <option value="MPE" selected="selected">MPE - Mantenimiento Preventivo Externo</option>
                                                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                                    <?php
                                                                                                    }
                                                                                                    if ($tipoMantencion == 'MRE') {
                                                                                                    ?>
                                                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                                                        <option value="MRE" selected="selected">MRE - Mantenimiento Reparativo Externo</option>
                                                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                                    <?php
                                                                                                    }
                                                                                                    if ($tipoMantencion == 'MPI') {
                                                                                                    ?>
                                                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                                        <option value="MPI" selected="selected">MPI - Mantenimiento Preventivo Interno</option>
                                                                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                                    <?php
                                                                                                    }
                                                                                                    if ($tipoMantencion == 'MRI') {
                                                                                                    ?>
                                                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                                        <option value="MRI" selected="selected">MRI - Mantenimiento Reparativo Interno</option>
                                                                                                    <?php
                                                                                                    }

                                                                                                    ?>
                                                                                                </select>


                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputCodigoTarjeta">Ejecutado Por:<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                                                </div>
                                                                                                <input type="text" class="form-control" name="ejecutador" value="<?php echo  $ejecutadoPor ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputCodigoTarjeta">Trabajo Realizado(Especifique):<code>*</code></label>
                                                                                            <div class="input-group mb-3">
                                                                                                <textarea name="trabajo" id="" maxlength="60" cols="30" rows="3" class="form-control"><?php echo  $trabajoRealizado ?></textarea>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputCodigoTarjeta">Problema que presenta(Detalles):</label>
                                                                                            <div class="input-group mb-3">

                                                                                                <textarea name="problema" id=""  maxlength="255"  cols="30" rows="3" class="form-control"><?php echo  $problema ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputCodigoTarjeta">Acciones Realizadas(Detalles):</label>
                                                                                            <div class="input-group mb-3">

                                                                                                <textarea name="acciones" id=""  maxlength="255" cols="30" rows="3" class="form-control"><?php echo  $accionesRealizadas ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <fieldset class="border p-3">
                                                                                        <legend class="w-auto">Tipo de Trabajo</legend>
                                                                                        <div class="form-row">

                                                                                            <div class="form-group text-center col-md-12">

                                                                                                <?php

                                                                                                if ($tipoTrabajo == 'garantia') {

                                                                                                ?>
                                                                                                    <div class="form-group  text-center col-md-6" align="middle">
                                                                                                        <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input" type="radio" name="radio" id="radio" value="garantia" checked="checked">
                                                                                                            <label class="form-check-label" for="inlineCheckbox1">Garantía</label>
                                                                                                        </div>
                                                                                                        <div class="form-check form-check-inline" align="middle">
                                                                                                            <input class="form-check-input" type="radio" name="radio" id="radio" value="convenio">
                                                                                                            <label class="form-check-label" for="inlineCheckbox2">Convenio</label>
                                                                                                        </div>

                                                                                                    </div>


                                                                                                <?php
                                                                                                }

                                                                                                if ($tipoTrabajo == 'convenio') {

                                                                                                ?>
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="garantia">
                                                                                                        <label class="form-check-label" for="inlineCheckbox1">Garantía</label>
                                                                                                    </div>
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="convenio" checked>
                                                                                                        <label class="form-check-label" for="inlineCheckbox2">Convenio</label>
                                                                                                    </div>

                                                                                                <?php
                                                                                                }

                                                                                                if ($tipoTrabajo == '' || $tipoTrabajo == '') {

                                                                                                ?>
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="garantia">
                                                                                                        <label class="form-check-label" for="inlineCheckbox1">Garantía</label>
                                                                                                    </div>
                                                                                                    <div class="form-check form-check-inline">
                                                                                                        <input class="form-check-input" type="radio" name="radio" id="radio" value="convenio">
                                                                                                        <label class="form-check-label" for="inlineCheckbox2">Convenio</label>
                                                                                                    </div>

                                                                                                <?php
                                                                                                }

                                                                                                ?>



                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="form-row">
                                                                                            <div class="form-group  col-md-6">
                                                                                                <label for="ordenCompra" class="form-label"> Orden Compra: </label>
                                                                                                <input class="form-control" id="ordenCompra" type="text" name="ordenCompra" value="<?php echo $ordenCompraTec ?>">
                                                                                            </div>
                                                                                            <div class="form-group  col-md-6">
                                                                                                <label for="ordenCompra" class="form-label"> Otros: </label>
                                                                                                <input class="form-control" id="otroTipoTrabajo" type="text" name="otroTipoTrabajo" value="<?php echo $otroTipoTrabajo ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </fieldset>
                                                                                    <br>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-12">
                                                                                            <label for="exampleInputTareasControlCent">Observaciones: </label><br>
                                                                                            <div class="form-group">
                                                                                                <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Escriba aqui las observaciones de la hoja técnica"> <?php echo $observaciones ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- <div class="form-row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                                                                                                <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                                                                                                <div class="custom-file">
                                                                                                    <input type="file" max-uploads="40" class="custom-file-input" multiple="" id="adjuntosTecnica[]" name="adjuntosTecnica[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                                                                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> -->
                                                                                    <br>
                                                                                    <!-- <label for="">Repuestos Utilizados:</label>
                                                                                    <div class="form-row fieldGroup">
                                                                                        <div class="form-group col-md-4">
                                                                                            <label for="exampleInputCoordenadas">Especie:</label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                                                </div>
                                                                                                <input type="text" class="form-control" name="especiesHT[]" placeholder="Nombre">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label for="exampleInputCoordenadas">Cantidad:</label>
                                                                                            <div class="input-group mb-3">
                                                                                                <div class="input-group-prepend">
                                                                                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                                                                                </div>
                                                                                                <input type="number" min="1" class="form-control" name="cantidad[]" placeholder="Cantidad">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label for="exampleInputCoordenadas">&nbsp;</label>
                                                                                            <div class="input-group mb-3">
                                                                                                <a href="javascript:void(0)" class="btn btn-block btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Otro Material</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div> -->
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <input type="hidden" value="<?php echo $idHojaTec ?>" name="idHojaTec">
                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Folio N°<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Tipo<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Ejecutado Por<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Trabajo Realizado<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Valor Total<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Acciones<center>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!--/.col  -->
                        </div>

                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <div class="form-row fieldGroupCopy" style="display: none;">
            <div class="form-group col-md-4">
                <label for="exampleInputCoordenadas">Especie:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                    </div>
                    <input type="text" class="form-control" name="especiesHT[]" placeholder="Nombre">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="exampleInputCoordenadas">Cantidad:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-arrow-circle-up"></i></span>
                    </div>
                    <input type="number" min="1" class="form-control" name="cantidad[]" placeholder="Cantidad">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="exampleInputCoordenadas">$:</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                    </div>
                    <input type="number" min="1" class="form-control" name="precio[]" placeholder="Precio">
                </div>
            </div>


            <div class="form-group col-md-4">
                <label for="exampleInputCoordenadas">&nbsp;</label>
                <div class="input-group mb-3">
                    <a href="javascript:void(0)" class="btn btn-block btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Eliminar</a>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                //group add limit
                var maxGroup = "10";

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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $('[data-toggle="popover"]').popover()
        })
    </script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $(document).ready(

            $('.eliminar').click(function(e) {

                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'warning',
                    title: '¿Quieres eliminar esta hoja técnica?',
                    showConfirmButton: true,
                    confirmButtonText: 'ELIMINAR',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'CANCELAR',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/eliminar-hoja-tecnica.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Hoja Tecnica Eliminada',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function(){
                                    location.reload();
                                }, 2100)
                            }
                        })
                    }
                })
            })


        )
    </script>
    <script>
        $(function() {
            $("#opcion").change(function() {
                if ($(this).val() === "1") {
                    $("#busqueda").prop("disabled", true);
                } else {
                    $("#busqueda").prop("disabled", false);
                }
            });
        });
    </script>
    <script>
        function editar() {


            let editar = document.getElementById("editar");
            let guardar = document.getElementById("guardar");
            let editando = document.getElementById("editando");

            if (editando.value == "NO") {
                editando.value = "SI";
            } else if (editando.value == "SI") {
                editando.value = "NO";
            }

            let numeroInventario = document.getElementById("numeroInventario");
            let idUnicoMod = document.getElementById("idUnicoMod");
            let fechaRecepcion = document.getElementById("fechaRecepcion");
            let nombreEquipo = document.getElementById("nombreEquipo");
            let valorIva = document.getElementById("valorIva");
            let marcaEquipo = document.getElementById("marcaEquipo");
            let idLicitacion = document.getElementById("idLicitacion");
            let modeloEquipo = document.getElementById("modeloEquipo");
            let ordenCompra = document.getElementById("ordenCompra");
            let numeroSerie = document.getElementById("numeroSerie");
            let fechaCompra = document.getElementById("fechaCompra");
            let fechaEnMarcha = document.getElementById("fechaEnMarcha");
            let idServicio = document.getElementById("idServicio");
            let nombreRecinto = document.getElementById("nombreRecinto");
            let nombreProveedor = document.getElementById("nombreProveedor");
            let direccionProveedor = document.getElementById("direccionProveedor");
            let telefonoProveedor = document.getElementById("telefonoProveedor");
            let emailProveedor = document.getElementById("emailProveedor");
            let nombreContactoProveedor = document.getElementById("nombreContactoProveedor");
            let observacionesContactoProveedor = document.getElementById("observacionesContactoProveedor");

            //INFORAMCIÓN EXTRA
            let claseEquipo = document.getElementById("claseEquipo");
            let subClaseEquipo = document.getElementById("subClaseEquipo");
            let compleEquipo = document.getElementById("compleEquipo");
            let nivelRiesgo = document.getElementById("nivelRiesgo");
            let ahnoFabricacion = document.getElementById("ahnoFabricacion");
            let vidaUtil = document.getElementById("vidaUtil");
            let versionSoftware = document.getElementById("versionSoftware");
            let paisOrigen = document.getElementById("paisOrigen");
            let terminoGarantia = document.getElementById("terminoGarantia");
            let costoAnualMantenimiento = document.getElementById("costoAnualMantenimiento");
            let frecuenciaMantenimiento = document.getElementById("frecuenciaMantenimiento");
            let textoAccesorios = document.getElementById("textoAccesorios");
            let textoInsumos = document.getElementById("textoInsumos");


            if (editando.value == "SI") {
                editar.innerHTML = "<i class='fa fa-edit' aria-hidden='true'></i><b> Deshabilitar Modo Edición</b>";
                editar.classList.remove("btn-warning");
                editar.classList.add("btn-danger");
                guardar.disabled = false;
                // editar.textContent = "Habilitar Edicion";

                numeroInventario.removeAttribute("disabled");
                numeroInventario.style.removeProperty("cursor");
                fechaRecepcion.removeAttribute("disabled");
                fechaRecepcion.style.removeProperty("cursor");
                nombreEquipo.removeAttribute("disabled");
                nombreEquipo.style.removeProperty("cursor");
                valorIva.removeAttribute("disabled");
                valorIva.style.removeProperty("cursor");
                marcaEquipo.removeAttribute("disabled");
                marcaEquipo.style.removeProperty("cursor");
                idLicitacion.removeAttribute("disabled");
                idLicitacion.style.removeProperty("cursor");
                modeloEquipo.removeAttribute("disabled");
                modeloEquipo.style.removeProperty("cursor");
                ordenCompra.removeAttribute("disabled");
                ordenCompra.style.removeProperty("cursor");
                numeroSerie.removeAttribute("disabled");
                numeroSerie.style.removeProperty("cursor");
                fechaCompra.removeAttribute("disabled");
                fechaCompra.style.removeProperty("cursor");
                fechaEnMarcha.removeAttribute("disabled");
                fechaEnMarcha.style.removeProperty("cursor");
                idServicio.removeAttribute("disabled");
                idServicio.style.removeProperty("cursor");
                nombreRecinto.removeAttribute("disabled");
                nombreRecinto.style.removeProperty("cursor");
                nombreProveedor.removeAttribute("disabled");
                nombreProveedor.style.removeProperty("cursor");
                direccionProveedor.removeAttribute("disabled");
                direccionProveedor.style.removeProperty("cursor");
                telefonoProveedor.removeAttribute("disabled");
                telefonoProveedor.style.removeProperty("cursor");
                emailProveedor.removeAttribute("disabled");
                emailProveedor.style.removeProperty("cursor")
                nombreContactoProveedor.removeAttribute("disabled");
                nombreContactoProveedor.style.removeProperty("cursor")
                observacionesContactoProveedor.removeAttribute("disabled");
                observacionesContactoProveedor.style.removeProperty("cursor");
                // idUnicoMod.removeAttribute("disabled");
                // idUnicoMod.style.removeProperty("cursor")

                //AÑADIR Y QUITAR PROPIEDADES INFO EXTRA
                claseEquipo.removeAttribute("disabled");
                claseEquipo.style.removeProperty("cursor")
                subClaseEquipo.removeAttribute("disabled");
                subClaseEquipo.style.removeProperty("cursor")
                compleEquipo.removeAttribute("disabled");
                compleEquipo.style.removeProperty("cursor")
                nivelRiesgo.removeAttribute("disabled");
                nivelRiesgo.style.removeProperty("cursor")
                ahnoFabricacion.removeAttribute("disabled");
                ahnoFabricacion.style.removeProperty("cursor")
                vidaUtil.removeAttribute("disabled");
                vidaUtil.style.removeProperty("cursor")
                versionSoftware.removeAttribute("disabled");
                versionSoftware.style.removeProperty("cursor")
                paisOrigen.removeAttribute("disabled");
                paisOrigen.style.removeProperty("cursor")
                terminoGarantia.removeAttribute("disabled");
                terminoGarantia.style.removeProperty("cursor")
                costoAnualMantenimiento.removeAttribute("disabled");
                costoAnualMantenimiento.style.removeProperty("cursor")
                frecuenciaMantenimiento.removeAttribute("disabled");
                frecuenciaMantenimiento.style.removeProperty("cursor")
                textoAccesorios.removeAttribute("disabled");
                textoAccesorios.style.removeProperty("cursor")
                textoInsumos.removeAttribute("disabled");
                textoInsumos.style.removeProperty("cursor")


                Swal.fire({
                    icon: 'success',
                    title: 'Modo Edición',
                    text: 'Modo Edición Habilitado',
                })
            } else if (editando.value == "NO") {
                editar.innerHTML = "<b><i class='fa fa-edit' aria-hidden='true'></i> Habilitar Edición</b>";
                editar.classList.remove("btn-danger");
                editar.classList.add("btn-success");
                guardar.disabled = true;
                // editar.textContent = "Habilitar Edicion";

                numeroInventario.setAttribute("disabled", "disabled");
                numeroInventario.style.setProperty("cursor", "not-allowed");
                fechaRecepcion.setAttribute("disabled", "disabled");
                fechaRecepcion.style.setProperty("cursor", "not-allowed");
                nombreEquipo.setAttribute("disabled", "disabled");
                nombreEquipo.style.setProperty("cursor", "not-allowed");
                valorIva.setAttribute("disabled", "disabled");
                valorIva.style.setProperty("cursor", "not-allowed");
                marcaEquipo.setAttribute("disabled", "disabled");
                marcaEquipo.style.setProperty("cursor", "not-allowed");
                idLicitacion.setAttribute("disabled", "disabled");
                idLicitacion.style.setProperty("cursor", "not-allowed");
                modeloEquipo.setAttribute("disabled", "disabled");
                modeloEquipo.style.setProperty("cursor", "not-allowed");
                ordenCompra.setAttribute("disabled", "disabled");
                ordenCompra.style.setProperty("cursor", "not-allowed");
                numeroSerie.setAttribute("disabled", "disabled");
                numeroSerie.style.setProperty("cursor", "not-allowed");
                fechaCompra.setAttribute("disabled", "disabled");
                fechaCompra.style.setProperty("cursor", "not-allowed");
                fechaEnMarcha.setAttribute("disabled", "disabled");
                fechaEnMarcha.style.setProperty("cursor", "not-allowed");
                idServicio.setAttribute("disabled", "disabled");
                idServicio.style.setProperty("cursor", "not-allowed");
                nombreRecinto.setAttribute("disabled", "disabled");
                nombreRecinto.style.setProperty("cursor", "not-allowed");
                nombreProveedor.setAttribute("disabled", "disabled");
                nombreProveedor.style.setProperty("cursor", "not-allowed");
                direccionProveedor.setAttribute("disabled", "disabled");
                direccionProveedor.style.setProperty("cursor", "not-allowed");
                telefonoProveedor.setAttribute("disabled", "disabled");
                telefonoProveedor.style.setProperty("cursor", "not-allowed");
                emailProveedor.setAttribute("disabled", "disabled");
                emailProveedor.style.setProperty("cursor", "not-allowed");
                nombreContactoProveedor.setAttribute("disabled", "disabled");
                nombreContactoProveedor.style.setProperty("cursor", "not-allowed");
                observacionesContactoProveedor.setAttribute("disabled", "disabled");
                observacionesContactoProveedor.style.setProperty("cursor", "not-allowed");
                // idUnicoMod.setAttribute("disabled", "disabled");
                // idUnicoMod.style.setProperty("cursor", "not-allowed");

                claseEquipo.setAttribute("disabled", "disabled");
                claseEquipo.style.setProperty("cursor", "not-allowed");
                subClaseEquipo.setAttribute("disabled", "disabled");
                subClaseEquipo.style.setProperty("cursor", "not-allowed");
                compleEquipo.setAttribute("disabled", "disabled");
                compleEquipo.style.setProperty("cursor", "not-allowed");
                nivelRiesgo.setAttribute("disabled", "disabled");
                nivelRiesgo.style.setProperty("cursor", "not-allowed");
                ahnoFabricacion.setAttribute("disabled", "disabled");
                ahnoFabricacion.style.setProperty("cursor", "not-allowed");
                vidaUtil.setAttribute("disabled", "disabled");
                vidaUtil.style.setProperty("cursor", "not-allowed");
                versionSoftware.setAttribute("disabled", "disabled");
                versionSoftware.style.setProperty("cursor", "not-allowed");
                paisOrigen.setAttribute("disabled", "disabled");
                paisOrigen.style.setProperty("cursor", "not-allowed");
                terminoGarantia.setAttribute("disabled", "disabled");
                terminoGarantia.style.setProperty("cursor", "not-allowed");
                costoAnualMantenimiento.setAttribute("disabled", "disabled");
                costoAnualMantenimiento.style.setProperty("cursor", "not-allowed");
                frecuenciaMantenimiento.setAttribute("disabled", "disabled");
                frecuenciaMantenimiento.style.setProperty("cursor", "not-allowed");
                textoAccesorios.setAttribute("disabled", "disabled");
                textoAccesorios.style.setProperty("cursor", "not-allowed");
                textoInsumos.setAttribute("disabled", "disabled");
                textoInsumos.style.setProperty("cursor", "not-allowed");

                Swal.fire({
                    icon: 'success',
                    title: 'Modo Edición',
                    text: 'Modo Edición Deshabilitado',
                })
            }


        }
    </script>
    <script>
        $(function() {
            $('[data-toggle="popover"]').popover()
        })
    </script>

</body>

</html>