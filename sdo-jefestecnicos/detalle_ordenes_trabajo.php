<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];


if (isset($_POST['idTG']) || isset($_SESSION['idTG'])) {
    if (isset($_POST['idTG'])) {

        $idTG = $_POST['idTG'];

        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    } else if (isset($_SESSION['idTG'])) {
        $idTG = $_SESSION['idTG'];
        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $ticket = $row['ticketAsociado'];
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $servicio = $row['servicio'];

    }
} else {
    echo 'No hago nada';
    exit();
}


if (isset($_POST['folioDocumento'], $_POST['idArchivoDocu'])) {
    $folioDocumento = $_POST['folioDocumento'];
    $idArchivoDocu = trim($_POST['idArchivoDocu']);

    if ($idArchivoDocu > 0) {

        if (empty($folioDocumento) || $folioDocumento == '') {
        } else {

            $folioDocumento2 = "folio-" . $folioDocumento;

            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir = '../sdo-administrador/archivos-recepcion-documentos/' . $folioDocumento2 . "/";
            $archivoZip = "Documentos-" . $folioDocumento . ".zip";

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

if (isset($_POST['folioTGDescargar'])) {
    $folioDescargar = $_POST['folioTGDescargar'];
    $dir = "../sdo-archivo-ot-asignado/$folioDescargar" . '/';
    ini_set("memory_limit", "5256M");
    $zip = new ZipArchive();
    $dir2 = $dir . "/";
    $rutaFinal = "../sdo-jefestecnicos/";
    $archivoZip = "Archivos-Funcionario-Asignado-" . $folioDescargar . ".zip";
    if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
        agregar_zipSC($dir2, $zip);
        $zip->close();
        // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
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
        } else {
            $descargaConfirma .= "N";
        }
    }
}


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {
            if ($idTG1 > 116) {

                $dir = "../sdo-archivosOT/" . $folioTG . "/";
            } else {

                $dir = "../sdo-administrador/archivos-trabajo-general/folio-" . $folioTG . "/carpeta-" . $idTG1 . "/";
            }

            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Documentos-" . $folioTG . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zipSC($dir2, $zip);
                $zip->close();
                // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
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
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} else {
}


$sqlTrabajo = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
$resTrabajo = mysqli_query($conexion, $sqlTrabajo);
while ($rowTrabajo = $resTrabajo->fetch_assoc()) {
    $idOrden = $rowTrabajo['idOrden'];
    $folio = $rowTrabajo['folioOrden'];

}

$contarRondas = contarRonda($idTG);

/*CONTAR LOS DETALLES A HACER EN LA OT*/
$contarDetalleBMS = contarDetalleBMS($idTG);
$contarDetalleCamara = contarDetalleCamara($idTG);
$contarDetalleLLE = contarDetalleLLE($idTG);
$contarDetalleCA = contarDetalleCA($idTG);
$contarDetalleOTROS = contarDetalleOTROS($idTG);
$contarchecklistpantallasgraficas = contarchecklistpantallasgraficas($idTG);
$contarchecklistlogicaprogram = contarchecklistlogicaprogram($idTG);


$contarDetalles = $contarDetalleBMS + $contarDetalleCamara + $contarDetalleLLE + $contarDetalleCA + $contarDetalleOTROS;
if ($detalleTurnoRonda == '' || empty($detalleTurnoRonda)) {
} else {
    $contarDetalles = $contarDetalles + 2;
}


/*CONTAR LAS RESPUESTAS DADAS POR EL FUNCIONARIO ASIGNADO*/
$contarRespuestaBMS = contarRespuestaBMS($idTG);
$contarRespuestaCCTV = contarRespuestaCCTV($idTG);
$contarRespuestaLLE = contarRespuestaLLE($idTG);
$contarRespuestaCA = contarRespuestaCA($idTG);
$contarRespuestaOtros = contarRespuestaOtros($idTG);

if ($detalleTurnoRonda == '' || empty($detalleTurnoRonda)) {
    $contarRespuesta = $contarRespuestaBMS + $contarRespuestaCCTV + $contarRespuestaLLE + $contarRespuestaCA + $contarRespuestaOtros;
} else {
    $contarRespuesta = $contarRespuestaBMS + $contarRespuestaCCTV + $contarRespuestaLLE + $contarRespuestaCA + $contarRespuestaOtros + $contarRondas;
}


/* DETALLES DE NO CONTROL*/
$contarDetallesNoControl = contarDetallesnoControl($idTG);
/* RESPUESTAS DE NO CONTROL */
$contarNoControl = contarRespuestaNocontrol($idTG);


$contarPermisosRevisados = contarPermisosRevisadosJIJI($idTG);

/*BUSCAR EL NOMBRE DEL SERVICIO EN LAS BASE DE DATOS*/
$buscarNombreServicio = buscarNombreServicio($servicio);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detalle Orden de Trabajo</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script>
        $(document).ready(function () {
            $(".show-listar").click(function () {
                $("#rowAvancesObs").show();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#divRegistrarAC").hide();
                $("#divListarAC").show();
                $("#rowParametrosPab").hide();
                $("#divParamPab").show();
                $("#listarParametros").show();
                $("#rowCheckList").hide();
            });
            $(".show-listar-detalle").click(function () {
                $("#rowAvancesObs").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#divRegistrarAC").show();
                $("#divListarAC").hide();
                $("#rowParametrosPab").hide();
                $("#divParamPab").show();
                $("#listarParametros").show();
                $("#rowCheckList").hide();
            });
            $(".show-parametros").click(function () {
                $("#rowAvancesObs").hide();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#divRegistrarAC").hide();
                $("#divListarAC").show();
                $("#rowParametrosPab").show();
                $("#divParamPab").hide();
                $("#listarParametros").hide();
                $("#rowCheckList").hide();
            });
            $(".mostrar-check-list").click(function () {
                $("#rowCheckList").show();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#listardetalles").show();
                $("#divchecklist").hide();

            });
            $(".listar-check-list").click(function () {
                $("#rowCheckList").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklist").show();
                $("#listardetalles").hide();
            });
            $(".mostrar-check-list-logica").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistlogica").hide();
                $("#listardetalleslogica").show();
                $("#rowCheckListLogica").show();

            });
            $(".listar-check-list-logica").click(function () {
                $("#divchecklistlogica").show();
                $("#listardetalleslogica").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListLogica").hide();
            });

        });
    </script>
    <script>
        function handleRadioChange(radio) {
            var panolDiv = document.getElementById('panol');
            var solicompraDiv = document.getElementById('solicompra');

            if (radio.id === 'radioOpcion1') {
                panolDiv.style.display = 'block';
                solicompraDiv.style.display = 'none';
            } else if (radio.id === 'radioOpcion2') {
                panolDiv.style.display = 'none';
                solicompraDiv.style.display = 'block';
            }
        }
    </script>
    <style>
        fieldset {
            border: 1px solid #ccc;
            padding: 10px;
        }

        legend {
            font-weight: bold;
        }

        /* Eliminar el espacio interno por defecto en los elementos li */

        /* Aplicar espacio interno a los elementos li según sea necesario */
        ul li, ol li {
            padding-bottom: 10px; /* Ajusta el valor según tu preferencia */
        }
        .select2-container {
            z-index: 9999 !important;
        }

        .select2-container--open {
            z-index: 9999 !important;
            position: absolute !important;
        }
    </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <?php
                        if (isset($_GET['exitoObservacion'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Exito a ingresar la observacion'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorObservacion'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al ingresar la observacion'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoSinArchivo'])) {
                            ?>
                            <script>
                                Swal.fire(
                                    'Exito!',
                                    'Orden de Trabajo ingresada correctamente sin archivo adjunto!',
                                    'success'
                                )
                            </script>
                            <?php
                        }

                        if (isset($_GET['exitoConArchivo'])) {
                            ?>
                            <script>
                                Swal.fire(
                                    'Exito!',
                                    'Orden de Trabajo ingresada correctamente junto con archivo adjunto!',
                                    'success'
                                )
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorEnvioArchivo'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Algo Salio Mal',
                                    text: 'El Archivo Adjunto No Se Subio De Manera Correcta!',
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorArchivo'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Algo Salio Mal',
                                    text: 'Algo fallo con la subida del Archivo Adjunto.Reintente!',
                                })
                            </script>
                            <?php

                        }

                        if (isset($_GET['archivoNoHabilitado'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'El archivo no se encuentra permitido.!'
                                })
                            </script>
                            <?php

                        }

                        if (isset($_GET['error'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'A ocurrido un error inesperado.Porfavor reintente'
                                })
                            </script>
                            <?php

                        }

                        if (isset($_GET['errorTecnicoTG'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '¡No se puede colocar el técnico dos veces.!'
                                })
                            </script>
                            <?php

                        }

                        if (isset($_GET['error4tg'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Algunos datos no se han enviado correctamente.Reintente'
                                })
                            </script>
                            <?php

                        }

                        if (isset($_GET['errorEnlosDatos'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Algunos Datos No Se Encuentran Llenados.Reintente'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['exito'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'sucess',
                                    title: 'Exito',
                                    text: 'Se ha ingresado la respuesta con exito'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['exitoGenerar'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha ingresado el avance con exito'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarBMS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha la HT de BMS con exito'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarRONDAS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha la HT de Rondas con exito'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorFolioBMS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error en el folio de HT del bms'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorGenerarBMS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de BMS'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorOrdenRONDAS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al generar la HT de las rondas.'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorFolioRONDAS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error en el folio de HT de las rondas'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorGenerarCA'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de Control de Acceso'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarCA'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha creado la HT de Control de Acceso'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarCCTV'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha creado la HT de CCTV'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorGenerarCCTV'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de CCTV'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorFolioCCTV'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de CCTV'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarLLE'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se ha generado la HT de LLE con exito'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorFolioLLE'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de LLE'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorGenerarLLE'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de LLE'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['exitoGenerarOTROS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Exito al crear la HT de Otros'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorFolioOTROS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de otros'
                                })
                            </script>
                            <?php
                        }
                        if (isset($_GET['errorGenerarOTROS'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al crear la HT de otros'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['exitoSubirArchivos'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Los archivos se han subido correctamente'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorSubirArchivos'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al subir los archivos'
                                })
                            </script>
                            <?php
                        }

                        if (isset($_GET['errorSubirArchivosVacio'])) {
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al subir los archivos. Archivos Vacios'
                                })
                            </script>
                            <?php
                        }
                        ?>
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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-3 col-12" id="divRegistrarAC" name="divRegistrarAC">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-yellow btn-md show-listar" id="registrarAC" name="registrarAC">
                                            <i class="fas fa-eye"></i>&nbsp;Ver Linea de Vida de la OT
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-12" id="divListarAC" name="divListarAC" style="display:none">
                                        <!-- small box -->
                                        <button class="btn bg-yellow show-listar-detalle" id="listarAC" style="display:none;" name="listarAC">
                                            <i class="fas fa-list"></i>&nbsp;Ver Detalle de la OT
                                        </button>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-12">

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/orden-trabajo.php" method="POST" target="_blank">
                                            <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                            <button type="submit" class="btn btn-block bg-gradient-primary btn-md text-white">
                                                <i class="fas fa-clipboard"></i>&nbsp;Pre-visualizar OT
                                            </button>
                                        </form>

                                    </div>
                                    <?php
                                    if ($contarchecklistpantallasgraficas > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklist">
                                            <button type="button" style="background-color: indianred;color: white" id="checklist" name="checklist" class="btn btn-block btn-md text-white mostrar-check-list">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetalles" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistar" name="checklistar" class="btn btn-block btn-md text-white listar-check-list">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($contarchecklistlogicaprogram > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistlogica">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlogica" name="checklistlogica" class="btn btn-block btn-md text-white mostrar-check-list-logica">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List Logica
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetalleslogica" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistarlogica" name="checklistarlogica" class="btn btn-block btn-md text-white listar-check-list-logica">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($idDepartamento == 1) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divParamPab" name="divParamPab">
                                            <!-- small box -->
                                            <?php
                                            if ($contarchecklistpantallasgraficas > 0) {
                                                ?>
                                                <br>
                                                <?php
                                            }
                                            ?>
                                            <button class="btn btn-block bg-gradient-success btn-md show-parametros text-white" id="listarParametros" name="listarParametros">
                                                <i class="fas fa-pen"></i>&nbsp;Listado de Parametros de Pabellon
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>
                                <br>
                                <div class="row" id="rowCheckList" style="display: none">
                                    <div class="col-md-12">
                                        <div class="card" style="text-align:center;">
                                            <div class="card-body">
                                                <fieldset>
                                                    <legend>Mostrar Check List - Pantallas Graficas</legend>
                                                    <table id="checklistpantallas" class="table table-bordered table-sm">
                                                        <thead class="text-center bg-gradient-secondary">
                                                        <tr>
                                                            <th style="font-size: 15px">N°</th>
                                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                                            <th style="font-size: 15px">Nombre Pantalla</th>
                                                            <th style="font-size: 15px">Especialidad</th>
                                                            <th style="font-size: 15px">Visualizar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlCheckListPantallas = "SELECT * FROM datos_checklist WHERE idTG = $idTG";
                                                        $resCheckListPantallas = mysqli_query($conexion, $sqlCheckListPantallas);
                                                        $count = 1;
                                                        while ($rowCheckListPantallas = $resCheckListPantallas->fetch_assoc()) {
                                                            $idCheck = $rowCheckListPantallas['id'];
                                                            $fechaCheckPantallas = $rowCheckListPantallas['fecha'];
                                                            $pantallas = $rowCheckListPantallas['pantalla'];
                                                            $especialidad = $rowCheckListPantallas['especialidad'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $count ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo date('d/m/Y', strtotime($fechaCheckPantallas)); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $pantallas ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    echo $especialidad;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <form action="../sdo-pdf/SDO-CheckList-PantallasGraficas.php" target="_blank" method="POST">
                                                                        <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idCheck ?>">
                                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                                        <button type="submit" class="btn bg-gradient-primary">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot class="text-center bg-gradient-secondary">
                                                        <tr>
                                                            <th style="font-size: 15px">N°</th>
                                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                                            <th style="font-size: 15px">Nombre Pantalla</th>
                                                            <th style="font-size: 15px">Especialidad</th>
                                                            <th style="font-size: 15px">Visualizar</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="rowAvancesObs" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body" style="text-align:center;">

                                                <fieldset>
                                                    <legend>Avance de OT</legend>

                                                    <table id="observacionTotal" class="table table-bordered table-sm">
                                                        <thead style="background-color: indianred; color: white"
                                                                class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Estado</th>
                                                            <th style="font-size: 15px">Avance</th>
                                                            <th style="font-size: 15px">Obs</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND detalleObs NOT LIKE ('%Se ha ingresado la orden de trabajo%')";
                                                        $res = mysqli_query($conexion, $sql);
                                                        while ($row = $res->fetch_assoc()) {
                                                            $nomCreador = $row['nomCreador'];
                                                            $detalleObs = $row['detalleObs'];
                                                            $estadoAvance = $row['estadoAvance'];
                                                            $fechaSubida = $row['fechaSubida'];
                                                            $observacion = $row['observacion'];
                                                            $folioHT = $row['folioHT'];
                                                            $noavance = $row['noavance'];
                                                            ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <?php
                                                                    echo $fechaSubida;
                                                                    ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    echo $nomCreador;
                                                                    ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    echo $estadoAvance;
                                                                    ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    echo $detalleObs;
                                                                    ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    if ($observacion == '' || empty($observacion)) {
                                                                        echo 'NO APLICA';
                                                                    } else {
                                                                        echo $observacion;
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot style="background-color: indianred; color: white"
                                                                class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Estado</th>
                                                            <th style="font-size: 15px">Avance</th>
                                                            <th style="font-size: 15px">Obs</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                    <hr>

                                                    <legend>Observaciones Generales</legend>

                                                    <table id="obsercacion2" class="table table-bordered table-sm">
                                                        <thead style="background-color: lightslategray; color: white"
                                                                class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Obs</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlObs = "SELECT * FROM observacion_ot WHERE idTG = $idTG";
                                                        $resObs = mysqli_query($conexion, $sqlObs);
                                                        while ($rowObs = $resObs->fetch_assoc()) {
                                                            $detalle = $rowObs['detalleObs'];
                                                            $nomCreador1 = $rowObs['nomCreador'];
                                                            $fechaObs = $rowObs['fecha'];
                                                            ?>
                                                            <tr class="text-center">
                                                                <td><?php echo $fechaObs ?></td>
                                                                <td><?php echo $nomCreador1 ?></td>
                                                                <td><?php echo $detalle ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot style="background-color: lightslategray; color: white"
                                                                class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Obs</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                    <hr>

                                                    <legend>Linea de Vida de la OT</legend>
                                                    <br>
                                                    &nbsp;
                                                    <table class="table table-bordered table-sm">
                                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Detalle</th>
                                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                                            <th style="font-size: 15px">Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlLinea = "SELECT * FROM linea_vida_ot WHERE idTG = $idTG";
                                                        $resLinea = mysqli_query($conexion, $sqlLinea);
                                                        while ($rowLinea = $resLinea->fetch_assoc()) {
                                                            $detalleLinea = $rowLinea['detalle'];
                                                            $nomCreador1Linea = $rowLinea['nomCreador'];
                                                            $fechaObsLinea = $rowLinea['fechaSubida'];
                                                            $idLineaVida = $rowLinea['idLineaVida'];
                                                            $respuestaPanol = $rowLinea['respuestaPanol'];
                                                            $isPanol = $rowLinea['isPanol'];
                                                            $isMaterialSoliCompra = $rowLinea['isMaterialSoliCompra'];
                                                            $rechazadoPanol = $rowLinea['rechazadoPanol'];
                                                            ?>
                                                            <tr class="text-center">
                                                                <td><?php echo $fechaObsLinea ?></td>
                                                                <td><?php echo $nomCreador1Linea ?></td>
                                                                <td><?php echo nl2br($detalleLinea) ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($respuestaPanol == 1) {
                                                                        echo 'ENTREGA EXITOSA';
                                                                    } else if ($rechazadoPanol == 1) {
                                                                        echo 'RECHAZADO POR PAÑOL';
                                                                    } else if ($isPanol == 1) {
                                                                        echo 'SI';
                                                                    } else {
                                                                        echo 'NO';
                                                                    }

                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($isMaterialSoliCompra == 1) {
                                                                        echo 'SI';
                                                                    } else {
                                                                        echo 'NO';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle; font-size: 12px" align="center">
                                                                    <?php
                                                                    if ($respuestaPanol == 1) {
                                                                        ?>
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-md-6">
                                                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalEyecheck<?php echo $idLineaVida ?>">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </button>
                                                                                <div class="modal fade" id="exampleModalEyecheck<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-gradient-success">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Entrega de Materiales y/o Herramientas</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <?php
                                                                                                $sqlAceptado = "SELECT * FROM aceptados_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                                                                $resAceptado = mysqli_query($conexion, $sqlAceptado);
                                                                                                while ($rowAceptado = $resAceptado->fetch_assoc()) {
                                                                                                    $detalleAceptado = $rowAceptado['detalle'];
                                                                                                    ?>
                                                                                                    <textarea readonly class="form-control" rows="12"><?php echo $detalleAceptado ?></textarea>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    } elseif ($rechazadoPanol == 1) {
                                                                        ?>

                                                                        <div class="row justify-content-center">
                                                                            <div class="col-md-6">
                                                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEye<?php echo $idLineaVida ?>">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </button>
                                                                                <div class="modal fade" id="exampleModalEye<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-gradient-primary">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Motivos del Rechazo Pañol</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <?php
                                                                                                $sqlVida = "SELECT * FROM rechazo_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                                                                $resVida = mysqli_query($conexion, $sqlVida);
                                                                                                while ($rowVida = $resVida->fetch_assoc()) {
                                                                                                    $detalleVida = $rowVida['detalle'];
                                                                                                    ?>
                                                                                                    <textarea readonly class="form-control" rows="12"><?php echo $detalleVida ?></textarea>

                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <?php
                                                                    } else {
                                                                        echo 'NO APLICA';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot style="background-color: lightslategray; color: white" class="text-center">
                                                        <tr>
                                                            <th style="font-size: 15px">Fecha</th>
                                                            <th style="font-size: 15px">Creador</th>
                                                            <th style="font-size: 15px">Detalle</th>
                                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                                            <th style="font-size: 15px">Acciones</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </fieldset>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="row" id="rowCheckListLogica" style="display: none">
                                    <div class="col-md-12">
                                        <div class="card" style="text-align:center;">
                                            <div class="card-body">
                                                <fieldset>
                                                    <legend>Mostrar Check List - Lógicas de Progrmación</legend>
                                                    <table id="checklistlogica" class="table table-bordered table-sm">
                                                        <thead class="text-center bg-gradient-secondary">
                                                        <tr>
                                                            <th style="font-size: 15px">N°</th>
                                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                                            <th style="font-size: 15px">EC-BOS</th>
                                                            <th style="font-size: 15px">Controlador</th>
                                                            <th style="font-size: 15px">Visualizar</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlCheckListLogicas = "SELECT * FROM `datos_checklist_logica` WHERE idTG = $idTG";
                                                        $resCheckListLogicas = mysqli_query($conexion, $sqlCheckListLogicas);
                                                        $count = 1;
                                                        while ($rowCheckListLogicas = $resCheckListLogicas->fetch_assoc()) {
                                                            $idCheckLogica = $rowCheckListLogicas['id'];
                                                            $ecbos = $rowCheckListLogicas['ecbos'];
                                                            $controlador = $rowCheckListLogicas['controlador'];
                                                            $observacionesLogica = $rowCheckListLogicas['obs'];
                                                            $hojasprogram = $rowCheckListLogicas['hojasprogram'];
                                                            $titulossub = $rowCheckListLogicas['titulossub'];
                                                            $ordenbloques = $rowCheckListLogicas['ordenbloques'];
                                                            $recursosinutilizados = $rowCheckListLogicas['recursosinutilizados'];
                                                            $listaerrores = $rowCheckListLogicas['listaerrores'];
                                                            $logicacontrol = $rowCheckListLogicas['logicacontrol'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $count ?></td>
                                                                <td><?php echo $rowCheckListLogicas['fecha'] ?></td>
                                                                <td><?php echo $rowCheckListLogicas['ecbos'] ?></td>
                                                                <td><?php echo $rowCheckListLogicas['controlador'] ?></td>
                                                                <td>
                                                                    <form action="../sdo-pdf/SDO-CheckList-Logica-Programacion.php" target="_blank" method="POST">
                                                                        <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idCheckLogica ?>">
                                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                                        <button type="submit" class="btn bg-gradient-primary">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>

                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot class="text-center bg-gradient-secondary">
                                                        <tr>
                                                            <th style="font-size: 15px">N°</th>
                                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                                            <th style="font-size: 15px">EC-BOS</th>
                                                            <th style="font-size: 15px">Controlador</th>
                                                            <th style="font-size: 15px">Visualizar</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="border p-3" id="row10" name="row10" style="display: none">
                                    <legend class="w-auto">Tabla de HT</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Ticket Asociado</center>
                                            </th>
                                            <th>
                                                <center>Folio
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Fecha
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Ver HT
                                                    <center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $query = "SELECT * FROM ordenes_trabajos_general WHERE idDepartamento = '$idDepartamento' AND noOrdenTrabajo = '$folioTG' ORDER BY fechaOrden DESC";

                                        $res = mysqli_query($conexion, $query);

                                        $numOrden = 1;

                                        while ($row = $res->fetch_assoc()) {

                                            $nombreOrden = $row['nomOrden'];
                                            ?>

                                            <tr>
                                                <td style="vertical-align:middle;">
                                                    <center><?php echo $numOrden; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center><?php

                                                        $ticketAsociado = mb_strtoupper($row['ticketAsociado']);

                                                        if (!empty($ticketAsociado)) {
                                                            echo $ticketAsociado;
                                                        } else {
                                                            echo "SIN INFORMACIÓN";
                                                        }

                                                        ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <?php
                                                    $folioOrden = mb_strtoupper($row['folioOrden']);
                                                    echo $folioOrden;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <center>
                                                        <?php

                                                        $nomTecnicoRecepcionador = mb_strtoupper($row['nomTecnicoRecepcionador']);
                                                        echo $nomTecnicoRecepcionador;

                                                        ?>
                                                    </center>

                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <center>
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['nomSolicitante']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center><?php if ($row['fechaOrden'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {
                                                            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                        } ?></center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $idOrden = $row['idOrden'];
                                                        $idOrden = trim($idOrden);
                                                        if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">

                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Hoja Técnica">

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>
                                                    </center>
                                                </td>


                                            </tr>
                                            <?php

                                            $numOrden = $numOrden + 1;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Ticket Asociado</center>
                                            </th>
                                            <th>
                                                <center>Folio
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Fecha
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Ver HT
                                                    <center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row9" name="row9" style="display: none">
                                    <legend class="w-auto">Tabla de OT</legend>
                                    <table class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de la Orden de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario Asig.</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver OT</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE departamento = $idDepartamento AND folioTG = '$folioTG' ORDER BY fechaTG DESC";

                                        $resTG = mysqli_query($conexion, $queryTG);

                                        $numTG = 1;

                                        $colorTG = "";

                                        while ($rowTG = $resTG->fetch_assoc()) {
                                            $tecnico = $rowTG['tecnico1'];
                                            $fechaTG = $rowTG['fechaTG'];

                                            $turnoTG = $rowTG['turnoTG'];
                                            $turnoTG = trim($turnoTG);

                                            $solicitanteTG = $rowTG['solicitanteTG'];
                                            $solicitanteTG = trim($solicitanteTG);

                                            $idTG = $rowTG['idTG'];
                                            $idTG = trim($idTG);

                                            $estadoTG = trim($rowTG['estadoTG']);
                                            $folioTG = $rowTG['folioTG'];

                                            if ($estadoTG == 'Trabajo terminado con exito') {
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            } else {
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR ?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaTG == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaTG = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTG)));
                                                        $registroHoraTG = date('H:i', strtotime($fechaTG));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaTG . ' a las ' . $registroHoraTG . ' hrs';
                                                    } ?>

                                                </td>
                                                <td nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    <?php
                                                    echo $folioTG;
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($turnoTG)) {
                                                        echo "No registra Turno";
                                                    } else {

                                                        echo $turnoTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($solicitanteTG)) {
                                                        echo "No registra Solicitante";
                                                    } else {

                                                        echo $solicitanteTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    if (empty($tecnico)) {
                                                        echo "No registra funconario";
                                                    } else {
                                                        echo $tecnico;
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($estadoTG)) {
                                                        echo "No registra Estado";
                                                    } else {
                                                        if ($estadoTG == 'trabajoTerminado') {
                                                            $estado = "Trabajo Terminado";
                                                            echo $estado;
                                                        } else {
                                                            echo $estadoTG;
                                                        }

                                                    } ?>

                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/orden-trabajo.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                        <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Orden de Trabajo">
                                                    </form>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de la Orden de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario Asig.</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver OT</center>
                                            </th>

                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <fieldset class="border p-3" id="row8" name="row8" style="display: none">
                                    <legend class="w-auto">Linea de Vida de la OT</legend>
                                    <table class="table table-bordered table-sm">
                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                            <th style="font-size: 15px">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlLinea = "SELECT * FROM linea_vida_ot WHERE idTG = $idTG";
                                        $resLinea = mysqli_query($conexion, $sqlLinea);
                                        while ($rowLinea = $resLinea->fetch_assoc()) {
                                            $detalleLinea = $rowLinea['detalle'];
                                            $nomCreador1Linea = $rowLinea['nomCreador'];
                                            $fechaObsLinea = $rowLinea['fechaSubida'];
                                            $idLineaVida = $rowLinea['idLineaVida'];
                                            $respuestaPanol = $rowLinea['respuestaPanol'];
                                            $isPanol = $rowLinea['isPanol'];
                                            $isMaterialSoliCompra = $rowLinea['isMaterialSoliCompra'];
                                            $rechazadoPanol = $rowLinea['rechazadoPanol'];
                                            ?>
                                            <tr class="text-center">
                                                <td><?php echo $fechaObsLinea ?></td>
                                                <td><?php echo $nomCreador1Linea ?></td>
                                                <td><?php echo nl2br($detalleLinea) ?></td>
                                                <td>
                                                    <?php
                                                    if ($respuestaPanol == 1) {
                                                        echo 'ENTREGA EXITOSA';
                                                    } else if ($rechazadoPanol == 1) {
                                                        echo 'RECHAZADO POR PAÑOL';
                                                    } else if ($isPanol == 1) {
                                                        echo 'SI';
                                                    } else {
                                                        echo 'NO';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($isMaterialSoliCompra == 1) {
                                                        echo 'SI';
                                                    } else {
                                                        echo 'NO';
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle; font-size: 12px" align="center">
                                                    <?php
                                                    if ($respuestaPanol == 1) {
                                                        ?>
                                                        <div class="row justify-content-center">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalEyecheck<?php echo $idLineaVida ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <div class="modal fade" id="exampleModalEyecheck<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-success">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Entrega de Materiales y/o Herramientas</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <?php
                                                                                $sqlAceptado = "SELECT * FROM aceptados_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                                                $resAceptado = mysqli_query($conexion, $sqlAceptado);
                                                                                while ($rowAceptado = $resAceptado->fetch_assoc()) {
                                                                                    $detalleAceptado = $rowAceptado['detalle'];
                                                                                    ?>
                                                                                    <textarea readonly class="form-control" rows="12"><?php echo $detalleAceptado ?></textarea>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } elseif ($rechazadoPanol == 1) {
                                                        ?>

                                                        <div class="row justify-content-center">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEye<?php echo $idLineaVida ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <div class="modal fade" id="exampleModalEye<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Motivos del Rechazo Pañol</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <?php
                                                                                $sqlVida = "SELECT * FROM rechazo_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                                                $resVida = mysqli_query($conexion, $sqlVida);
                                                                                while ($rowVida = $resVida->fetch_assoc()) {
                                                                                    $detalleVida = $rowVida['detalle'];
                                                                                    ?>
                                                                                    <textarea readonly class="form-control" rows="12"><?php echo $detalleVida ?></textarea>

                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    } else {
                                                        echo 'NO APLICA';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                            <th style="font-size: 15px">Acciones</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <fieldset id="rowInformacion" name="rowInformacion" class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n de la Orden de Trabajo</legend>
                                    <br>
                                    &nbsp;
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8 col-12 text-justify">
                                            <h5>
                                                <b>Folio Trabajo: </b>
                                                <?php
                                                echo $folioTG;
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Fecha Trabajo:</b>
                                                <?php
                                                echo $fechaTG3
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Fecha de programacion del trabajo:</b>
                                                <?php
                                                echo $fechaProgram2;
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Turno Trabajo:</b>
                                                <?php
                                                echo $turnoTG;
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Personal Solicitante:</b>
                                                <?php
                                                echo $solicitanteTG;
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Estado :</b>
                                                <?php echo $estadoTG; ?>
                                            </h5>
                                            <h5>
                                                <b>Persona Asignada:</b>
                                                <?php echo $tecnico1 ?>
                                            </h5>
                                            <?php
                                            if ($ticket == 0 || empty($ticket)) {
                                            } else {
                                                ?>
                                                <h5>
                                                    <b>Ticket Asociado:</b>
                                                    <?php echo $ticket ?>
                                                </h5>
                                                <?php
                                            }
                                            ?>
                                            <h5>
                                                <b>Tipo de Trabajo:</b>
                                                <?php
                                                if ($tipoTrabajo !== '' || !empty($tipoTrabajo)) {
                                                    echo $tipoTrabajo;
                                                } else {
                                                    echo 'No presenta tipo de trabajo';
                                                }
                                                ?>
                                            </h5>
                                            <?php
                                            if ($departamentos == 1) {
                                                if ($detalleTurnoRonda !== '' || !empty($detalleTurnoRonda)) {
                                                    ?>
                                                    <h5>
                                                        <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                            <?php echo $contarRondas ?></b>
                                                    </h5>
                                                    <?php
                                                }
                                                if ($formularioRondas > 0) {
                                                    ?>
                                                    <h5>
                                                        <b>Formulario de rondas
                                                            <?php
                                                            if ($formularioRondas == 1) {
                                                                echo 'con Consola de Alarmas';
                                                            } else if ($formularioRondas == 2) {
                                                                echo 'con parametros de unidades manejadoras de aire BMS';
                                                            }

                                                            ?></b>
                                                    </h5>
                                                    <?php
                                                }
                                                if ($servicio > 0) {
                                                    ?>
                                                    <h5>
                                                        <b>Unidad o Servicio: <?php echo $buscarNombreServicio ?></b>
                                                    </h5>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-4 col-12 text-center">
                                            <?php
                                            $contarArchivos = contarArchivosDocumento($idTG);
                                            if ($contarArchivos > 0) {
                                                ?>
                                                <button type="button" class="btn btn-block" style="background-color: deepskyblue" data-toggle="modal" data-target="#mostrarDescargar">
                                                    <i class="fas fa-file"></i>&nbsp;Descargar Documentos Adjuntos
                                                </button>
                                                <?php
                                            }
                                            ?>
                                            <div class="modal fade" id="mostrarDescargar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color: deepskyblue">
                                                            <h5 class="modal-title" id="exampleModalLabel">Descargar Documentos Adjuntos</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-sm table-hover  table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" class="text-center">Unidad del Emisor</th>
                                                                    <th scope="col" class="text-center">Nombre del Emisor</th>
                                                                    <th scope="col" class="text-center">Persona Asignada</th>
                                                                    <th scope="col" class="text-center">Mostrar Documento</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlDocu = "SELECT * FROM archivos_recepcion_documento WHERE idTG = $idTG";
                                                                $resDocu = mysqli_query($conexion, $sqlDocu);
                                                                while ($rowDocu = $resDocu->fetch_assoc()) {
                                                                    $folioDocumento = $rowDocu['folioDocumento'];
                                                                    $idArchivoDocu = $rowDocu['idArchivosDocu'];
                                                                    $idRecepcion = $rowDocu['idRecepcion'];

                                                                    $sql1 = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
                                                                    $res1 = mysqli_query($conexion, $sql1);
                                                                    while ($row1 = $res1->fetch_assoc()) {
                                                                        $idRecepcion1 = $row1['idRecepcion'];
                                                                        $unidadEmisor = $row1['unidadEmisor'];
                                                                        $nombreEmisor = $row1['nomEmisor'];
                                                                        $asignadoRecepcion = $row1['asignadoRecepcion'];
                                                                        $folioRecep = $row1['folioRecep'];
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $unidadEmisor ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $nombreEmisor ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $asignadoRecepcion ?>
                                                                            </td>
                                                                            <td>
                                                                                <form action="detalle_ordenes_trabajo.php" method="POST" style="display: inline">
                                                                                    <?php
                                                                                    if (empty($folioDocumento) || $folioDocumento == '') {
                                                                                    } else {
                                                                                        ?>
                                                                                        <input type="hidden" name="folioDocumento" id="folioDocumento" value="<?php echo $folioDocumento ?>">
                                                                                        <input type="hidden" name="idArchivoDocu" id="idArchivoDocu" value="<?php echo $idArchivoDocu ?>">
                                                                                        <button type="submit" class="btn btn-block" style="background-color: deepskyblue; color: white">
                                                                                            <i class="fas fa-download"></i>&nbsp;Descargar Documento
                                                                                        </button>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>
                                                                    <th scope="col" class="text-center">Unidad del Emisor</th>
                                                                    <th scope="col" class="text-center">Nombre del Emisor</th>
                                                                    <th scope="col" class="text-center">Persona Asignada</th>
                                                                    <th scope="col" class="text-center">Mostrar Documento</th>

                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <?php
                                                $sql = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                $res = mysqli_query($conexion, $sql);
                                                while ($row = $res->fetch_assoc()) {
                                                    $datos = [''];
                                                    $datos['rutaArchivo'] = $row['rutaArchivo'];
                                                    $rutaArchivo = $datos['rutaArchivo'];
                                                    $idArchivo = $row['idArchivo'];

                                                }
                                                if (empty($idArchivo) || $idArchivo == 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-block" disabled title="No hay Archivo Adjunto Subido por el Profesional" style="background-color: indigo; color: white">No hay Archivo Adjunto Subido por el Profesional</button>

                                                    <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                    <?php
                                                } else {
                                                    ?>
                                                    <form action="detalle_ordenes_trabajo.php" method="POST" style="display: inline">
                                                        <button type="submit" class="btn btn-block" title="Descargar Archivo Subido por el Profesional" style="background-color: indigo; color: white">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG ?>">

                                                    </form>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($tecnico1 == $nomUsuario) {
                                                ?>
                                                <br>
                                                <button class="btn bg-warning btn-block" data-toggle="modal" data-target="#derivar" type="button">
                                                    <i class="fas fa-arrow-right"></i> Cambiar Persona Asignada
                                                </button>
                                                <br>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if ($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  ") {
                                                ?>
                                                <br>
                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                <?php
                                            } else {
                                                ?>
                                                <?php
                                                if ($solicitanteTG != $nomUsuario) {
                                                } else {
                                                    ?>
                                                    <br>
                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-jefestecnicos/trabajos_general_modificar.php" method="post">
                                                        <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-success">
                                                            <i class="fa fa-pen"></i> Modificar Orden de Trabajo
                                                        </button>
                                                    </form>
                                                    <?php
                                                }
                                            }

                                            ?>
                                            <br>
                                            <button type="button" class="btn btn-block bg-info" data-toggle="modal" data-target="#exampleModallinadevida">
                                                <i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida
                                            </button>
                                            <br>
                                            <?php
                                            if ($tecnico1 == $nomUsuario) {
                                                ?>
                                                <!--
                                                <br>
                                                <button type="button" style="color: whitesmoke" class="btn btn-block bg-gradient-primary" data-toggle="modal" data-target="#exampleModalmateriales"><i class="fas fa-eye"></i>&nbsp;Solicitar Materiales</button>
                                                -->
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            $contarSoliCompra = contarMaterial($idTG);
                                            //if($solicitanteTG == $nomUsuario){
                                            if ($contarSoliCompra > 0) {
                                                ?>
                                                <br>
                                                <button type="button" style="color: white;" class="btn btn-block bg-gradient-info" data-toggle="modal" data-target="#exampleModalSoliCompra">
                                                    <i class="fas fa-eye"></i> Agregar Solicitud de Compra
                                                </button>
                                                <?php
                                            }
                                            // }
                                            ?>
                                            <?php
                                            if ($departamentos == 1) {
                                                if ($contarDetalles == $contarRespuesta) {
                                                    if ($tecnico1 == $nomUsuario) {
                                                        ?>
                                                        <br>
                                                        <form action="ordenes-con-firma.php" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                            <button type="submit" class="btn btn-block bg-gradient-success" style="color: white">
                                                                <i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica
                                                            </button>
                                                        </form>
                                                        <?php
                                                    }
                                                }
                                            } else {
                                                if ($contarDetallesNoControl == $contarNoControl) {
                                                    if ($idDepartamento == 3 || $idDepartamento == 2 || $idDepartamento == 4 || $idDepartamento == 5 || $idDepartamento == 6 || $idDepartamento == 8 || $idDepartamento == 10 || $idDepartamento == 12) {
                                                        ?>
                                                        <br>
                                                        <br>
                                                        <button class="btn btn-danger btn-block terminartodo" name="<?php echo $idTG ?>">Finalizar OT</button>
                                                        <?php
                                                    } else {
                                                        if ($tecnico1 == $nomUsuario) {
                                                            ?>
                                                            <br>
                                                            <form action="ordenes-con-firma.php" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block bg-gradient-success" style="color: white">
                                                                    <i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica
                                                                </button>
                                                            </form>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }

                                            $buscarArchivostecnico = buscararchivostecnicos($idTG);
                                            if ($buscarArchivostecnico > 0) {
                                                ?>
                                                <br>
                                                <br>
                                                <form action="detalle_ordenes_trabajo.php" method="POST">
                                                    <input type="hidden" id="folioTGDescargar" name="folioTGDescargar" value="<?php echo $folioTG ?>">
                                                    <button type="submit" class="btn btn-block bg-gradient-success" style="color: white">
                                                        <i class="fas fa-download"></i> Descargar Archivos Subidos por el Funcionario Asignado
                                                    </button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                </fieldset>
                            </div>

                            <br>
                            <div name="rowTareas" id="rowTareas" class="row">
                                <?php
                                if ($idDepartamento == 1) {
                                    ?>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en BMS</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 == $nomUsuario) {
                                                if ($detalleTG == '' || empty($detalleTG) || $detalleTG = '') {
                                                } else {
                                                    $sqlAvances1 = "SELECT COUNT(*) AS TOTAL FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'BMS'";
                                                    $resAvances1 = mysqli_query($conexion, $sqlAvances1);
                                                    while ($rowAvances1 = $resAvances1->fetch_assoc()) {
                                                        $TOTAL = $rowAvances1['TOTAL'];
                                                    }
                                                    if ($TOTAL > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVERBMS" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODIBMS" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Modificar
                                                                Trabajos de BMS
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#exampleModalBMS"
                                                                class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar Trabajos de
                                                            BMS
                                                        </button>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>

                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en CCTV</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCamara; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleTurnoCamara == '' || empty($detalleTurnoCamara) || $detalleTurnoCamara = '') {
                                                } else {
                                                    $sqlAvances2 = "SELECT COUNT(*) AS TOTAL1 FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CCTV'";
                                                    $resAvances2 = mysqli_query($conexion, $sqlAvances2);
                                                    while ($rowAvances2 = $resAvances2->fetch_assoc()) {
                                                        $TOTAL1 = $rowAvances2['TOTAL1'];
                                                    }
                                                    if ($TOTAL1 > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVERCCTV" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODICCTV" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar Trabajos
                                                                de CCTV
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal"
                                                                data-target="#exampleModalCCTV" class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar Trabajos de
                                                            CCTV
                                                        </button>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en LLE</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoLLE; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleTurnoLLE == '' || empty($detalleTurnoLLE) || $detalleTurnoLLE = '') {
                                                } else {
                                                    $sqlAvances3 = "SELECT COUNT(*) AS TOTAL2 FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'LLE'";
                                                    $resAvances3 = mysqli_query($conexion, $sqlAvances3);
                                                    while ($rowAvances3 = $resAvances3->fetch_assoc()) {
                                                        $TOTAL2 = $rowAvances3['TOTAL2'];
                                                    }
                                                    if ($TOTAL2 > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVERLLE" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODILLE" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar Trabajos
                                                                de LLE
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#exampleModalLLE"
                                                                class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar Trabajos de
                                                            LLE
                                                        </button>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Acceso</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCA; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleTurnoCA == '' || empty($detalleTurnoCA) || $detalleTurnoCA = '') {
                                                } else {
                                                    $sqlAvances4 = "SELECT COUNT(*) AS TOTAL3 FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CA'";
                                                    $resAvances4 = mysqli_query($conexion, $sqlAvances4);
                                                    while ($rowAvances4 = $resAvances4->fetch_assoc()) {
                                                        $TOTAL3 = $rowAvances4['TOTAL3'];
                                                    }
                                                    if ($TOTAL3 > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVERCA" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODICA" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar Trabajos
                                                                de CA
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#exampleModalCA"
                                                                class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar Trabajos de CA
                                                        </button>
                                                        <?php
                                                    }
                                                }
                                                    if($contarPermisosRevisados > 0){

                                                    }else{
                                                        ?>
                                                        <br>
                                                        <br>
                                                        <button type="button" data-toggle="modal" data-target="#formModalRevision"
                                                                class="btn btn-block"
                                                                style="background-color: blue;color:white;"> Permisos
                                                        </button>

                                                        <!-- Modal -->
                                                        <!-- MODAL AGREGAR PERMISOS -->
                                                        <!-- MODAL: REVISIÓN GENERAL -->
                                                        <div class="modal fade" id="formModalRevision" tabindex="-1" role="dialog" aria-labelledby="formModalRevisionLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <form id="revisionForm" action="../sdo-funciones/guardar_memos_revisados.php" method="POST">
                                                                        <input type="hidden" name="nombreUsuario" value="<?= $nomUsuario ?>">
                                                                        <input type="hidden" name="idOrigen" id="idOrigenRevision" value=""> <!-- Set por JS dinámicamente -->

                                                                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">

                                                                        <div class="modal-header bg-info text-white">
                                                                            <h5 class="modal-title" id="formModalRevisionLabel">Revisión de Permisos / Formularios</h5>
                                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="form-group">
                                                                                <label for="selectMemosRevision">Seleccionar Memos</label>
                                                                                <select class="form-control select2" name="listadoUsuarios[]" id="selectMemosRevision" multiple required>
                                                                                    <?php
                                                                                    $sqlMemos = "SELECT * FROM memos_control_acceso ORDER BY id DESC";
                                                                                    $resMemos = mysqli_query($conexion, $sqlMemos);
                                                                                    while ($row = $resMemos->fetch_assoc()):
                                                                                        $idMemo = $row['id'];
                                                                                        echo "<option value='$idMemo'>N° PLATAFORMA: $idMemo</option>";
                                                                                    endwhile;
                                                                                    ?>
                                                                                </select>
                                                                            </div>

                                                                            <div class="form-group mt-3">
                                                                                <label for="selectFormatosRevision">Seleccionar Formularios</label>
                                                                                <select class="form-control select2" name="listadoFormatos[]" id="selectFormatosRevision" multiple required>
                                                                                    <?php
                                                                                    $sqlForm = "SELECT idFormRegAcceso, folioFormRegAcceso FROM formularios_registro ORDER BY idFormRegAcceso DESC";
                                                                                    $resForm = mysqli_query($conexion, $sqlForm);
                                                                                    while ($row = $resForm->fetch_assoc()):
                                                                                        $folio = $row['folioFormRegAcceso'];
                                                                                        echo "<option value='$folio'>$folio</option>";
                                                                                    endwhile;
                                                                                    ?>
                                                                                </select>
                                                                            </div>


                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-success">Guardar Revisión</button>
                                                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Rondas</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoRonda; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleTurnoRonda == '' || $detalleTurnoRonda = '' || empty($detalleTurnoRonda)) {
                                                } else {
                                                    if ($contarRondas >= 2) {
                                                    } else {
                                                        if ($formularioRondas == 1) {
                                                            ?>
                                                            <form action="formulario-rondas-alarmas.php" method="POST">
                                                                <input type="hidden" name="idTG" id="idTG"
                                                                        value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block"
                                                                        style="background-color: blue;color:white;">Agregar nueva
                                                                    ronda
                                                                </button>
                                                            </form>
                                                            <!--
                                                            <form action="#" method="POST">
                                                                <input type="hidden" name="idTG" id="idTG"
                                                                        value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar
                                                                    ronda Anterior</button>
                                                            </form>
                                                            -->
                                                            <?php
                                                        } else if ($formularioRondas == 2) {
                                                            ?>
                                                            <form action="formulario-parametros-bms.php" method="POST">
                                                                <input type="hidden" name="idTG" id="idTG"
                                                                        value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block"
                                                                        style="background-color: blue;color:white;">Agregar nueva
                                                                    ronda
                                                                </button>
                                                            </form>
                                                            <!--
                                                            <form action="#" method="POST">
                                                                <input type="hidden" name="idTG" id="idTG"
                                                                        value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar
                                                                    ronda Anterior</button>
                                                            </form>
                                                            -->
                                                            <?php
                                                        }

                                                    }
                                                }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle de Otros Trabajos</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleOtros" class="form-control" rows="15"><?php echo $detalleOtros; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleOtros == '' || $detalleOtros = '' || empty($detalleOtros)) {
                                                } else {
                                                    $sqlAvances5 = "SELECT COUNT(*) AS TOTAL5 FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
                                                    $resAvances5 = mysqli_query($conexion, $sqlAvances5);
                                                    while ($rowAvances5 = $resAvances5->fetch_assoc()) {
                                                        $TOTAL5 = $rowAvances5['TOTAL5'];
                                                    }
                                                    if ($TOTAL5 > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVEROTROS" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODIOTROS" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar Trabajos
                                                                de OTROS
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal"
                                                                data-target="#exampleModalOTROS" class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar Trabajos de
                                                            OTROS
                                                        </button>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-md-12">
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar</legend>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                            <br>
                                            <?php
                                            if ($tecnico1 !== $nomUsuario) {
                                            } else {
                                                if ($detalleTG == '' || $detalleTG = '' || empty($detalleTG)) {
                                                } else {
                                                    $sqlNOcontrol = "SELECT COUNT(*) AS TOTAL6 FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'NO-CONTROL'";
                                                    $resNOcontrol = mysqli_query($conexion, $sqlNOcontrol);
                                                    while ($rowNocontrol = $resNOcontrol->fetch_assoc()) {
                                                        $TOTAL6 = $rowNocontrol['TOTAL6'];
                                                    }
                                                    if ($TOTAL6 > 0) {
                                                        ?>
                                                        <div class="row justify-content-md-center">
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalVERNOCONTROL" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">&nbsp;Revisar
                                                                Avance
                                                            </button>
                                                            <button type="button" data-toggle="modal"
                                                                    data-target="#exampleModalMODINOCONTROL" class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Modificar Trabajos
                                                                Ingresados
                                                            </button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" data-toggle="modal"
                                                                data-target="#exampleModalNOControl" class="btn btn-block"
                                                                style="background-color: blue;color:white;">Completar los Trabajos Asignados
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                    <br>
                                                    <br>
                                                    <button class="btn btn-lg bg-success" style="float: right" data-toggle="modal" data-target="#exampleModalSubirArchivoAdjunto">
                                                        <i class="fas fa-upload"></i> Subir archivo adjunto
                                                    </button>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </fieldset>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <br>
                            <div class="modal fade" id="exampleModalSubirArchivoAdjunto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title" id="exampleModalLabel">Subir archivos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/subirArchivos-fu.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body row text-center">
                                                <label>Escoja el/los archivo/s a subir</label>
                                                <br>
                                                <input type="file" name="archivosFuncResponsable[]" id="archivosFuncResponsable" multiple class="file-input">
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="folioTGSubir" id="folioTGSubir" value="<?php echo $folioTG ?>">
                                                <input type="hidden" name="idTGSubir" id="idTGSubir" value="<?php echo $idTG ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success">Subir</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalmateriales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">Solicitar Materiales y/o Herramientas</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-linea-vida-solic.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                <label class="w-auto">Nombre Creador:</label>
                                                <input type="text" readonly class="form-control" id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>">
                                                <br>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="miRadioGroup" onchange="handleRadioChange(this)" id="radioOpcion1">
                                                    <label class="form-check-label" for="radioOpcion1">
                                                        Solicitar Materiales y/o Herramienta a Pañol
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="miRadioGroup" onchange="handleRadioChange(this)" id="radioOpcion2">
                                                    <label class="form-check-label" for="radioOpcion2">
                                                        Solicitar Materiales y/o Herramienta por Solicitud de Compra
                                                    </label>
                                                </div>
                                                <br>
                                                <div id="panol" style="display: none">
                                                    <label class="w-auto">Ingresar Materiales y/o Herramientas a Pañol:</label>
                                                    <textarea class="form-control" name="detalleMateriales" rows="12" id="detalleMateriales"></textarea>
                                                </div>
                                                <div id="solicompra" style="display: none">
                                                    <label class="w-auto">Ingresar Materiales y/o Herramientas:</label>
                                                    <textarea class="form-control" name="detalleSolic" rows="12" id="detalleSolic"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="derivar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title" id="exampleModalLabel">Cambiar Persona Asignada</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/cambioasigando-ot.php" method="POST">
                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador:
                                                        <code>*</code></label>
                                                    <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                    <br>
                                                    <label for="exampleInputBorder" style="font-size: 15px">Turno:
                                                        <code>*</code></label>
                                                    <select id="selectturno" name="selectturno" class="form-control form-control-border">
                                                        <option value="Personal Diurno">Personal Diurno</option>
                                                        <option value="Personal Nocturno">Personal Nocturno</option>
                                                    </select>
                                                    <br>
                                                    <label for="exampleInputBorder" style="font-size: 15px">Nueva Persona a Asignar:
                                                        <code>*</code></label>
                                                    <select id="selectPersona" required name="selectPersona" class="form-control form-control-border">
                                                        <?php
                                                        $sqlPerso = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento AND nomPersonal NOT LIKE ('%Técnico Pruebas%')";
                                                        $resPerso = mysqli_query($conexion, $sqlPerso);
                                                        ?>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        <?php
                                                        while ($rowPerso = $resPerso->fetch_assoc()) {
                                                            $nomPersonal = $rowPerso['nomPersonal'];
                                                            ?>
                                                            <option value="<?php echo $nomPersonal ?>"><?php echo $nomPersonal ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label for="exampleInputBorder" style="font-size: 15px">Justifique el cambio.
                                                        <code>*</code></label>
                                                    <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" required></textarea>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title" id="exampleModalLabel">Ingresar Observacion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-observacion-ot.php" method="POST">
                                            <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                            <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario ?>">
                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly" class="form-control " id="nomCreador" name="nomCreador" placeholder="Nombre de Personal" value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Describa la observacion:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalleObs" rows="10" name="detalleObs"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modificarAvance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-success">
                                            <h5 class="modal-title" id="exampleModalLabel">Modificar Avances</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificarAvance.php" method="POST">
                                            <div class="modal-body">
                                                <?php
                                                $sqlAvances = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND nomCreador = '$nomUsuario' AND noavance is null";
                                                $resAvances = mysqli_query($conexion, $sqlAvances);
                                                ?>
                                                <code>Saldran todos los avances y las observaciones que Ud. realizo.</code>
                                                <br><br>
                                                <code>Si un campo se encuentra vacío,puede llenarlo con información o dejarlo como esta.</code>
                                                <input type="hidden" id="nomCreador" name="nomCreador" value="<?php echo $nomCreador ?>">
                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                <br>
                                                <br>
                                                <?php
                                                while ($rowAvances = $resAvances->fetch_assoc()) {
                                                    $countA = 1;
                                                    $avances = $rowAvances['detalleObs'];
                                                    $obs = $rowAvances['observacion'];
                                                    $ida = $rowAvances['idAvance'];
                                                    ?>
                                                    <input type="hidden" name="ida[]" id="ida[]" value="<?php echo $ida ?>">
                                                    <label class="w-auto">Avance: </label>
                                                    <textarea class="form-control form-control-border" id="avances[]" name="avances[]"><?php echo $avances ?></textarea>
                                                    <br>
                                                    <label class="w-auto">Observacion:</label>
                                                    <textarea class="form-control form-control-border" id="obs[]" name="obs[]"><?php echo $obs ?></textarea>
                                                    <br>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModallinadevida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-info">
                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Obs a la Linea de Vida</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-linea-vida.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                <label class="w-auto">Nombre Creador:</label>
                                                <input type="text" readonly class="form-control" id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>">
                                                <br>
                                                <label class="w-auto">Ingresar Observacion:</label>
                                                <textarea class="form-control" name="detalleObs" rows="12" id="detalleObs"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVERBMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Revisar BMS</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="text-align:left;" class="form-group">
                                                <label for="exampleInputBorder">Detalles:
                                                    <code>*</code></label>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'BMS'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <textarea class="form-control" id="detalle" readonly
                                                        rows="10"
                                                        name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVERCCTV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Revisar CCTV</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="text-align:left;" class="form-group">
                                                <label for="exampleInputBorder">Detalles:
                                                    <code>*</code></label>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CCTV'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <textarea class="form-control" id="detalle" readonly
                                                        rows="10"
                                                        name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVERLLE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Revisar LLE</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="text-align:left;" class="form-group">
                                                <label for="exampleInputBorder">Detalles:
                                                    <code>*</code></label>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'LLE'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <textarea class="form-control" id="detalle" readonly
                                                        rows="10"
                                                        name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVERCA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Revisar CA</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="text-align:left;" class="form-group">
                                                <label for="exampleInputBorder">Detalles:
                                                    <code>*</code></label>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CA'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <textarea class="form-control" id="detalle" readonly
                                                        rows="10"
                                                        name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVEROTROS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Revisar OTROS</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="text-align:left;" class="form-group">
                                                <label for="exampleInputBorder">Detalles:
                                                    <code>*</code></label>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <textarea class="form-control" id="detalle" readonly
                                                        rows="10"
                                                        name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title" id="exampleModalLabel">Ingresar
                                                Observacion En la Orden de Trabajo</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-observacion-ot.php"
                                                method="POST">
                                            <input type="hidden" name="idTG" id="idTG"
                                                    value="<?php echo $idTG ?>">
                                            <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                    value="<?php echo $rolUsuario ?>">
                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador"
                                                            placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Describa la
                                                        observacion:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalleObs"
                                                            rows="10" name="detalleObs"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modificarAvance" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-success">
                                            <h5 class="modal-title" id="exampleModalLabel">Modificar
                                                Avances</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificarAvance.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <?php
                                                $sqlAvances = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND nomCreador = '$nomUsuario' AND noavance is null";
                                                $resAvances = mysqli_query($conexion, $sqlAvances);
                                                ?>
                                                <code>Saldran todos los avances y las observaciones que Ud. realizo.</code>
                                                <br><br>
                                                <code>Si un campo se encuentra vacío,puede llenarlo con información o dejarlo como esta.</code>
                                                <input type="hidden" id="nomCreador" name="nomCreador"
                                                        value="<?php echo $nomCreador ?>">
                                                <input type="hidden" id="idTG" name="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <br>
                                                <br>
                                                <?php
                                                while ($rowAvances = $resAvances->fetch_assoc()) {
                                                    $countA = 1;
                                                    $avances = $rowAvances['detalleObs'];
                                                    $obs = $rowAvances['observacion'];
                                                    $ida = $rowAvances['idAvance'];
                                                    ?>
                                                    <input type="hidden" name="ida[]" id="ida[]"
                                                            value="<?php echo $ida ?>">
                                                    <label class="w-auto">Avance: </label>
                                                    <textarea class="form-control form-control-border"
                                                            id="avances[]"
                                                            name="avances[]"><?php echo $avances ?></textarea>
                                                    <br>
                                                    <label class="w-auto">Observacion:</label>
                                                    <textarea class="form-control form-control-border"
                                                            id="obs[]" name="obs[]"><?php echo $obs ?></textarea>
                                                    <br>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cerrar
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalBMS" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">COMPLETAR
                                                TRABAJO DE BMS</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-trabajo-bms.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador"
                                                            placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle"
                                                            rows="10" name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODIBMS" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR
                                                TRABAJO DE BMS</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-bms.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador"
                                                            placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'BMS'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle"
                                                            rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCCTV" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">COMPLETAR TRABAJO
                                                DE CCTV</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-trabajo-cctv.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODICCTV" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO
                                                DE CCTV</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-cctv.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CCTV'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalLLE" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">COMPLETAR TRABAJO
                                                DE LLE</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-trabajo-lle.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODILLE" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO
                                                DE CCTV</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-lle.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'LLE'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCA" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">COMPLETAR TRABAJO
                                                DE CA</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-trabajo-ca.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODICA" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO
                                                DE CA</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-ca.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'CA'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalOTROS" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">COMPLETAR TRABAJO
                                                DE OTROS</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-trabajo-otros.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODIOTROS" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO
                                                DE OTROS</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-otros.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'OTROS'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalNOControl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">Completar Trabajos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-avance-no-control.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario ?>">
                                                <label for="exampleInputBorder">Nombre de Usuario:
                                                    <code>*</code></label>
                                                <input type="text" readonly="readonly" class="form-control " id="nomCreador" name="nomCreador" placeholder="Nombre de Personal" value="<?php echo $nomUsuario ?>" required>
                                                <br>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle"
                                                            rows="10" name="detalle"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalVERNOCONTROL" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">AVANCES</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-otros.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <?php
                                                    $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'NO-CONTROL'";
                                                    $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                    while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                        $detalleOBS1 = $rowMODIA['detalleObs'];
                                                    }
                                                    ?>
                                                    <textarea class="form-control" id="detalle" readonly
                                                            rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalMODINOCONTROL" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO
                                                DE OTROS</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/modificar-trabajo-no-control.php"
                                                method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="idTG" id="idTG"
                                                        value="<?php echo $idTG ?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario"
                                                        value="<?php echo $rolUsuario ?>">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre de Usuario:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly"
                                                            class="form-control " id="nomCreador"
                                                            name="nomCreador" placeholder="Nombre de Personal"
                                                            value="<?php echo $nomUsuario ?>" required>
                                                </div>
                                                <?php
                                                $sqlMODIA = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND tipoTrabajo = 'NO-CONTROL'";
                                                $resMODIA = mysqli_query($conexion, $sqlMODIA);
                                                while ($rowMODIA = $resMODIA->fetch_assoc()) {
                                                    $detalleOBS1 = $rowMODIA['detalleObs'];
                                                }
                                                ?>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Detalles:
                                                        <code>*</code></label>
                                                    <textarea class="form-control" id="detalle" rows="10"
                                                            name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalSoliCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-info">
                                            <h5 class="modal-title" id="exampleModalLabel">Solicitud de Compra</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-soli-compra.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                <label class="w-auto">Ingrese el Folio de la Solicitud de Compra </label>
                                                <input type="text" id="numSoliCompra" name="numSoliCompra" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="rowParametrosPab" style="display: none;">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body" style="text-align:center;">


                                            <fieldset>
                                                <legend>Registros de Parametros de Pabellones</legend>


                                                <div class="row text-center" style="display: flex; justify-content:center;">


                                                    <div class="col-lg-3 col-12" id="divAgregarParametros" name="divAgregarParametros">
                                                        <!-- small box -->
                                                        <!--<button class="btn btn-block bg-gradient-success btn-md text-white" id="agregarParametros" name="agregarParametros" data-toggle="modal" data-target="#modalAgregar">
                                                            <i class="fas fa-plus"></i>&nbsp;Agregar Nueva Medición
                                                        </button>-->

                                                        <!-- Modal -->
                                                    </div>

                                                </div>
                                                <hr>

                                                <div class="table-responsive">

                                                    <table style="font-size:80%" id="example2" class="table table-bordered table-hover">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>Fecha</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Funcionario</center>
                                                            </th>
                                                            <?php

                                                            for ($i = 2; $i <= 10; $i++) {
                                                                ?>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Pabellon <?php echo $i; ?></center>
                                                                </th>
                                                                <?php
                                                            }

                                                            ?>
                                                            <th>
                                                                Enviar
                                                            </th>
                                                            <th>
                                                                Modificar
                                                            </th>
                                                            <th>
                                                                Eliminar
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>Fecha</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Funcionario</center>
                                                            </th>
                                                            <?php

                                                            for ($i = 2; $i <= 10; $i++) {
                                                                ?>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Pabellon <?php echo $i; ?></center>
                                                                </th>
                                                                <?php
                                                            }

                                                            ?>

                                                            <th>
                                                                Enviar
                                                            </th>
                                                            <th>
                                                                Modificar
                                                            </th>
                                                            <th>
                                                                Eliminar
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>


                                                <script>
                                                    $(document).ready(function () {
                                                        var columns = [
                                                            {"data": "fechaRegistroPab"},
                                                            {"data": "nomRegistroPab"},

                                                            // Otras columnas existentes

                                                            // Agregar columnas dinámicamente
                                                        ];

                                                        for (var i = 2; i <= 10; i++) {
                                                            columns.push({"data": "valorTotal" + i});
                                                            // Agrega más columnas según sea necesario
                                                        }
                                                        columns.push(
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    var buttonHTML;

                                                                    if (row.correoEnviado === 0) {
                                                                        buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '"><i class="fas fa-envelope"></i></button>';
                                                                    } else {
                                                                        buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-envelope"></i></button>';
                                                                    }

                                                                    return buttonHTML;
                                                                },
                                                                "createdRow": function (row, data, dataIndex) {
                                                                    $(row).on('click', '.btn-confirm', function () {
                                                                        var button = $(this);
                                                                        var icon = button.find('i');

                                                                        // Cambiar el texto del botón a "Cargando"
                                                                        button.text('Cargando...');

                                                                        // Aquí puedes realizar cualquier acción adicional, como enviar una solicitud AJAX

                                                                        // Simular una operación asíncrona (puedes reemplazar esto con tu lógica real)
                                                                        setTimeout(function () {
                                                                            // Restaurar el botón a su estado original (habilitado y con el texto original)
                                                                            button.prop('disabled', false);
                                                                            icon.removeClass('fa-spinner fa-spin').addClass('fa-envelope');
                                                                            button.text('Enviar Correo');
                                                                        }, 2000); // Cambia 2000 por la cantidad de tiempo que tomará tu operación real
                                                                    });
                                                                }
                                                            },

                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    var buttonHTML;

                                                                    if (row.correoEnviado === 0) {
                                                                        buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '"><i class="fas fa-pen"></i></button>';
                                                                    } else {
                                                                        buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-pen"></i></button>';
                                                                    }

                                                                    return buttonHTML;
                                                                }
                                                            },
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    return '<button class="btn btn-danger btn-eliminar" data-id="' + row.idRegistroPab + '"><i class="fas fa-trash"></i></button>';
                                                                }
                                                            }
                                                        )

                                                        var example2 = $('#example2').DataTable({
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
                                                                "url": "ajax-pabCorreo-listado.php", // Archivo PHP que contiene la consulta SQL
                                                                "type": "POST",
                                                                "data": function (d) {

                                                                },
                                                                "dataSrc": ""
                                                            },
                                                            "columns": columns,
                                                            "createdRow": function (row, data, dataIndex) {
                                                                $('td', row).css({
                                                                    'text-align': 'center', // Centrar texto horizontalmente
                                                                    'vertical-align': 'middle', // Centrar texto verticalmente
                                                                    'border': '1px solid #dddddd' // Añadir borde
                                                                    // Añade más estilos según tus necesidades
                                                                });
                                                            }
                                                        });
                                                        // Manejar clic en el botón de confirmación
                                                        $('#example2').on('click', '.btn-confirm', function () {
                                                            var id = $(this).data('id');

                                                            // Mostrar swal.alert de confirmación
                                                            Swal.fire({
                                                                title: '¿Estás seguro de enviar Parametros a Pabellon?',
                                                                text: 'Esta acción no se puede revertir.',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Sí, confirmar'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Crear un formulario dinámico
                                                                    var form = $('<form>', {
                                                                        'action': '../sdo-funciones/pabellones_enviarCorreo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                        'method': 'POST'
                                                                    });

                                                                    // Agregar un campo oculto para el ID
                                                                    form.append($('<input>', {
                                                                        'type': 'hidden',
                                                                        'name': 'idRegPab',
                                                                        'value': id
                                                                    }));

                                                                    // Agregar el formulario al documento y enviarlo
                                                                    form.appendTo('body').submit();


                                                                    // Realizar acciones adicionales si el usuario confirma
                                                                    // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
                                                                    // confirmarAccion(id);
                                                                }
                                                            });
                                                        });

                                                        $('#example2').on('click', '.btn-editar', function () {
                                                            var idRegistro = $(this).data('id');

                                                            // Hacer una petición al servidor para obtener los datos del registro usando AJAX
                                                            $.ajax({
                                                                url: 'sdo-includes/parametros_pabellon_modificar.php',
                                                                type: 'POST',
                                                                data: {id: idRegistro},
                                                                dataType: 'json',
                                                                success: function (data) {
                                                                    console.log('Respuesta del servidor:', data);

                                                                    // Llenar el modal con los datos obtenidos
                                                                    for (var key in data) {
                                                                        var value = data[key];
                                                                        $('#' + key).val(value);
                                                                        console.log('Asignando valor a ' + key + ': ' + value);
                                                                    }

                                                                    $('#modalEditar').modal('show'); // Abre el modal
                                                                },
                                                                error: function (error) {
                                                                    console.log(error);
                                                                    // Manejar errores si es necesario
                                                                }
                                                            });

                                                        });

                                                        $('#example2').on('click', '.btn-eliminar', function () {
                                                            var id = $(this).data('id');

                                                            // Mostrar swal.alert de confirmación
                                                            Swal.fire({
                                                                title: '¿Estás seguro de eliminar estos Parametros?',
                                                                text: 'Esta acción no se puede revertir.',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Sí, confirmar'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Crear un formulario dinámico
                                                                    var form = $('<form>', {
                                                                        'action': '../sdo-funciones/pabellones_eliminarParametros.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                        'method': 'POST'
                                                                    });

                                                                    // Agregar un campo oculto para el ID
                                                                    form.append($('<input>', {
                                                                        'type': 'hidden',
                                                                        'name': 'idRegPab',
                                                                        'value': id
                                                                    }));

                                                                    // Agregar el formulario al documento y enviarlo
                                                                    form.appendTo('body').submit();


                                                                    // Realizar acciones adicionales si el usuario confirma
                                                                    // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
                                                                    // confirmarAccion(id);
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>

                                            </fieldset>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <form class="" method="post" action="../sdo-funciones/pabellones_agregarUltMedicion.php">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="modalAgregarLabel">Agregar Nueva Medición</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Nombre del Tecnico:
                                                        <code>*</code></label>
                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                </div>
                                                <div style="text-align:left;" class="form-group">
                                                    <label for="exampleInputBorder">Fecha de Registro:
                                                        <code>*</code></label>
                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                </div>
                                                <?php
                                                for ($k = 2; $k < 11; $k++) {
                                                    $valorTemp = 'addTempPabellon' . $k;
                                                    $valorHumedad = 'addHumedadPabellon' . $k;

                                                    $nomValorTemp = 'Temperatura Pabellon ' . $k;
                                                    $nomValorHumedad = 'Humedad Pabellon ' . $k;
                                                    ?>
                                                    <div class="form-row">
                                                        <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                            <label for="<?php echo $valorTemp; ?>"><?php echo $nomValorTemp; ?>:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-temperature-high"></i></span>
                                                                </div>
                                                                <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorTemp; ?>" name="<?php echo $valorTemp; ?>" placeholder="<?php echo $nomValorTemp; ?>">
                                                            </div>
                                                        </div>
                                                        <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                            <label for="<?php echo $valorHumedad; ?>"><?php echo $nomValorHumedad; ?>:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                                                                </div>
                                                                <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorHumedad; ?>" name="<?php echo $valorHumedad; ?>" placeholder="<?php echo $nomValorHumedad; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="nomRegistroPab" id="nomRegistroPab" value="<?php echo mb_convert_case($nomUsuario, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo mb_convert_case($folioTG, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i>&nbsp;&nbsp;Registrar Medicion
                                                </button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
    $(document).ready(function () {
        $('#selectMemosRevision, #selectFormatosRevision').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Seleccione una o más opciones'
        });

        // Confirmación con SweetAlert al guardar
        $('#revisionForm').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Confirmar revisión?',
                text: 'Se guardarán los elementos seleccionados.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

</script>


<script>
    function visible(valor) {
        if (valor == 1) {
            document.getElementById('materiales').style.display = 'block';
            document.getElementById('obs').style.display = 'none';
            document.getElementById('obs1').style.display = 'none';
        } else if (valor == 2) {
            document.getElementById('materiales').style.display = 'none';
            document.getElementById('obs').style.display = 'block';
            document.getElementById('obs1').style.display = 'none';
        }
    }
</script>
<script>
    $(function () {
        $('#observacionTotal').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    $(function () {
        $('#obsercacion2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    $(document).ready(
        $('.terminartodo').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('name');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea finalizar esta Orden de Trabajo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , finalizar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/finalizar_OT.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('finalizando.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'OT finalizada con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.href = "trabajos_general_listado_finalizados.php";
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>

</body>
</html>

