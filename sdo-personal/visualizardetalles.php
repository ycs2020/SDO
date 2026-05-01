<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];


$sqlUsuario = "SELECT * FROM personal WHERE idPersonal = $idUsuario";
$resUsuario = mysqli_query($conexion, $sqlUsuario);
while ($rowUsuario = $resUsuario->fetch_assoc()) {
    $nomPersonal = $rowUsuario['nomPersonal'];


}


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
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        //$tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $ticketAsociado = $row['ticketAsociado'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
        $servicio = $row['servicio'];
        $idEquipamiento = $row['idEquipamiento'];
    }



}

if (isset($_POST['folioTGDescargar'])) {
    $folioDescargar = $_POST['folioTGDescargar'];
    $dir = "../sdo-archivo-ot-asignado/$folioDescargar" . '/';
    ini_set("memory_limit", "5256M");
    $zip = new ZipArchive();
    $dir2 = $dir . "/";
    $rutaFinal = "../sdo-personal/";
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
            $rutaFinal = "../sdo-personal/";
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


$buscarchecklist = buscarchecklist($idTG);

if ($departamentos == 1) {
    $cantidadCheckList = $buscarchecklist['cantidad'];
} else {
    $cantidadCheckList = contartipocheck($idTG);
}
$tipoChecklist = $buscarchecklist['tipo'];


$contarRondas = contarRonda($idTG);

/*CONTAR LOS DETALLES A HACER EN LA OT*/
$contarDetalleBMS = contarDetalleBMS($idTG);
$contarDetalleCamara = contarDetalleCamara($idTG);
$contarDetalleLLE = contarDetalleLLE($idTG);
$contarDetalleCA = contarDetalleCA($idTG);
$contarDetalleOTROS = contarDetalleOTROS($idTG);

$contarDetalles = $contarDetalleBMS + $contarDetalleCamara + $contarDetalleLLE + $contarDetalleCA + $contarDetalleOTROS;


$conteoMostrar = ' - ' . $contarDetalles;

if ($detalleTurnoRonda == '' || empty($detalleTurnoRonda)) {
} else {
    $contarDetalles = $contarDetalles + 2;

    $conteoMostrar .= ' - ' . $contarDetalles;
}
if ($cantidadCheckList == '' || empty($cantidadCheckList) || $cantidadCheckList == 0) {
} else {
    $contarDetalles = $contarDetalles + $cantidadCheckList;
    $conteoMostrar .= ' - ' . $cantidadCheckList;
}

//echo "<script>console.log('Detalles -> ".$contarDetalles."');</script>";
//echo "<script>console.log('Detalles1 -> ".$contarDetalles1."');</script>";
//echo "<script>console.log('Detalles2 -> ".$contarDetalles2."');</script>";


/*CONTAR LAS RESPUESTAS DADAS POR EL FUNCIONARIO ASIGNADO*/
$contarRespuestaBMS = contarRespuestaBMS($idTG);
$contarRespuestaCCTV = contarRespuestaCCTV($idTG);
$contarRespuestaLLE = contarRespuestaLLE($idTG);
$contarRespuestaCA = contarRespuestaCA($idTG);
$contarRespuestaOtros = contarRespuestaOtros($idTG);
$contarchecklistpantallasgraficas = contarchecklistpantallasgraficas($idTG);
$contarchecklistlogicaprogram = contarchecklistlogicaprogram($idTG);
$contarchecklistucas = contarchecklistucas($idTG);
$contarchecklistcontroladores = contarchecklistcontroladores($idTG);
$contarchecklisttccuma = contarchecklisttccuma($idTG);
$contarchecklistvav = contarchecklistvav($idTG);
$contarchecklistvf = contarchecklistvf($idTG);
$contarchecklistuma = contarchecklistuma($idTG);
$contarASTUCA = contarASTUCA($idTG);
$contarchecklistLLE = contarchecklistLLE($idTG);

if ($detalleTurnoRonda == '' || empty($detalleTurnoRonda)) {
    $contarRespuesta = $contarRespuestaBMS + $contarRespuestaCCTV + $contarRespuestaLLE + $contarRespuestaCA + $contarRespuestaOtros;
} else {
    $contarRespuesta = $contarRespuestaBMS + $contarRespuestaCCTV + $contarRespuestaLLE + $contarRespuestaCA + $contarRespuestaOtros + $contarRondas;
}

if ($contarchecklistpantallasgraficas == '' || empty($contarchecklistpantallasgraficas)) {
} else {
    $contarRespuesta = $contarRespuesta + $contarchecklistpantallasgraficas;
}

if ($contarchecklistlogicaprogram == '' || empty($contarchecklistlogicaprogram)) {
} else {
    $contarRespuesta = $contarRespuesta + $contarchecklistlogicaprogram;
}

if ($contarchecklistucas == '' || empty($contarchecklistucas)) {
} else {
    $contarRespuesta = $contarRespuesta + $contarchecklistucas;
}

if ($contarchecklistcontroladores == '' || empty($contarchecklistcontroladores)) {
} else {
    $contarRespuesta = $contarRespuesta + $contarchecklistcontroladores;
}

if ($contarchecklisttccuma == '' || empty($contarchecklisttccuma)) {
} else {
    $contarRespuesta = $contarRespuesta + $contarchecklisttccuma;
}
if($contarchecklistvav == '' || empty($contarchecklistvav)){
}else{
    $contarRespuesta = $contarRespuesta + $contarchecklistvav;
}

if($contarchecklistvf == '' || empty($contarchecklistvf)){
}else{
    $contarRespuesta = $contarRespuesta + $contarchecklistvf;
}

if($contarchecklistuma == '' || empty($contarchecklistuma)){
}else{
    $contarRespuesta = $contarRespuesta + $contarchecklistuma;
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
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                $("#divchecklistucas").show();
                $("#listardetallesucas").hide();
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
                $("#rowCheckListUCAS").hide();
                $("#divchecklistucas").hide();
            });
            $(".mostrar-check-list").click(function () {
                $("#rowCheckList").show();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklist").hide();
                $("#listardetalles").show();
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
            $(".mostrar-check-list-ucas").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistucas").hide();
                $("#listardetallesucas").show();
                $("#rowCheckListUCAS").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-ucas").click(function () {
                $("#divchecklistucas").show();
                $("#listardetallesucas").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListUCAS").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-controladores").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistcontroladores").hide();
                $("#listardetallescontroladores").show();
                $("#rowCheckListControladores").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-controladores").click(function () {
                $("#divchecklistcontroladores").show();
                $("#listardetallescontroladores").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListControladores").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-tccuma").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklisttccuma").hide();
                $("#listardetallestccuma").show();
                $("#rowCheckListtccuma").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-tccuma").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklisttccuma").show();
                $("#listardetallestccuma").hide();
                $("#rowCheckListtccuma").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-vav").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistvav").hide();
                $("#listardetallesvav").show();
                $("#rowCheckListvav").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-vav").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklistvav").show();
                $("#listardetallesvav").hide();
                $("#rowCheckListvav").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-vf").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistvf").hide();
                $("#listardetallesvf").show();
                $("#rowCheckListvf").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-vf").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklistvf").show();
                $("#listardetallesvf").hide();
                $("#rowCheckListvf").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-uma").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistuma").hide();
                $("#listardetallesuma").show();
                $("#rowCheckListuma").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-uma").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklistuma").show();
                $("#listardetallesuma").hide();
                $("#rowCheckListuma").hide();
                $("#rowParametrosPab").hide();
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
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <?php
                    function mostrarMensaje($tipo, $titulo, $texto)
                    {
                        echo
                        <<<EOL
                            <script>
                                Swal.fire({
                                    icon: '$tipo',
                                    title: '$titulo',
                                    text: '$texto'
                                });
                            </script>
EOL;
                    }

                    $mensajes = [
                        'exitoObservacion' => ['success', 'Exito', 'Exito al ingresar la observacion'],
                        'errorObservacion' => ['error', 'Error', 'Error al ingresar la observacion'],
                        'exitoSinArchivo' => ['success', 'Exito', 'Orden de Trabajo ingresada correctamente sin archivo adjunto'],
                        'exitoConArchivo' => ['success', 'Exito', 'Orden de Trabajo ingresada correctamente junto con archivo adjunto'],
                        'errorEnvioArchivo' => ['error', 'Error', 'El Archivo Adjunto No Se Subio De Manera Correcta'],
                        'errorArchivo' => ['error', 'Error', 'Algo fallo con la subida del Archivo Adjunto.Reintente'],
                        'archivoNoHabilitado' => ['error', 'Error', 'El archivo no se encuentra permitido'],
                        'error' => ['error', 'Error', 'A ocurrido un error inesperado.Porfavor reintente'],
                        'errorTecnicoTG' => ['error', 'Error', 'No se puede colocar el técnico dos veces'],
                        'error4tg' => ['error', 'Error', 'Algunos datos no se han enviado correctamente.Reintente'],
                        'errorEnlosDatos' => ['error', 'Error', 'Algunos Datos No Se Encuentran Llenados.Reintente'],
                        'exito' => ['sucess', 'Exito', 'Se ha ingresado la respuesta con exito'],
                        'exitoGenerar' => ['success', 'Exito', 'Se ha ingresado el avance con exito'],
                        'exitoGenerarBMS' => ['success', 'Exito', 'Se ha la HT de BMS con exito'],
                        'exitoGenerarRONDAS' => ['success', 'Exito', 'Se ha la HT de Rondas con exito'],
                        'errorFolioBMS' => ['error', 'Error', 'Error en el folio de HT del bms'],
                        'errorGenerarBMS' => ['error', 'Error', 'Error al crear la HT de BMS'],
                        'errorOrdenRONDAS' => ['error', 'Error', 'Error al generar la HT de las rondas'],
                        'errorFolioRONDAS' => ['error', 'Error', 'Error en el folio de HT de las rondas'],
                        'errorGenerarCA' => ['error', 'Error', 'Error al crear la HT de Control de Acceso'],
                        'exitoGenerarCA' => ['success', 'Exito', 'Se ha creado la HT de Control de Acceso'],
                        'exitoGenerarCCTV' => ['success', 'Exito', 'Se ha creado la HT de CCTV'],
                        'errorGenerarCCTV' => ['error', 'Error', 'Error al crear la HT de CCTV'],
                        'errorFolioCCTV' => ['error', 'Error', 'Error al crear la HT de CCTV'],
                        'exitoGenerarLLE' => ['success', 'Exito', 'Se ha generado la HT de LLE con exito'],
                        'errorFolioLLE' => ['error', 'Error', 'Error al crear la HT de LLE'],
                        'errorGenerarLLE' => ['error', 'Error', 'Error al crear la HT de LLE'],
                        'exitoGenerarOTROS' => ['success', 'Exito', 'Exito al crear la HT de Otros'],
                        'errorFolioOTROS' => ['error', 'Error', 'Error al crear la HT de otros'],
                        'errorGenerarOTROS' => ['error', 'Error', 'Error al crear la HT de otros'],
                        'exitoActualizar' => ['success', 'Exito', 'Se ha modificado correctamente'],
                        'errorActualizar' => ['error', 'Error', 'Ha ocurrido un error.Por favor reintente'],
                        'exitoBMS' => ['success', 'Exito', 'Respuesta Ingresada con exito'],
                        'exitoCA' => ['success', 'Exito', 'Respuesta Ingresada con exito'],
                        'exitoLLE' => ['success', 'Exito', 'Respuesta Ingresada con exito'],
                        'exitoOTRO' => ['success', 'Exito', 'Respuesta Ingresada con exito'],
                        'exitoCCTV' => ['success', 'Exito', 'Respuesta Ingresada con exito'],
                        'exitoModificarParam' => ['success', 'Exito', 'Parametros de Pabellones Modificados con Exito.'],
                        'errorModificarParam' => ['error', 'Error', 'Error al Modificar Parametros de Pabellones.'],
                        'noModData' => ['error', 'Error', 'Error al Cargar Parametros de Pabellones. Reintente.'],
                        'exitoRegistrarParam' => ['success', 'Exito', 'Parametros de Pabellones Registrados con Exito.'],
                        'errorRegistrarParam' => ['error', 'Error', 'Error al Registrar Parametros de Pabellones.'],
                        'noData' => ['error', 'Error', 'Error al Registrar Parametros de Pabellones. Reintente.'],
                        'exitoEliminarRegistro' => ['success', 'Exito', 'Parametros de Pabellones Eliminados con Exito.'],
                        'errorEliminarRegistro' => ['error', 'Error', 'Error al Eliminar Parametros de Pabellones.'],
                        'noIdRegPab' => ['error', 'Error', 'Error al Cargar Parametros de Pabellones. Reintente.'],
                        'exitoCorreoEnviado' => ['success', 'Exito', 'Parametros de Pabellones Enviados con Exito.'],
                        'exitoCorreoEnviadoSinBloqueo' => ['success', 'Exito', 'Parametros de Pabellones Enviados con Exito.'],
                        'errorCorreoEnviado' => ['error', 'Error', 'Error al Enviar Parametros de Pabellones. Reintente.'],
                        'errorIDCorreoEnviado' => ['error', 'Error', 'Error al Cargar Parametros de Pabellones a Enviar. Reintente.'],
                        'exitoCheckListUcas' => ['success', 'Exito', 'Check List Ucas Ingresado.'],
                        'errorActualizarCheckListUCAS' => ['error', 'Error', 'Error al actualizar checkList ucas. Por favor reintente'],
                        'exitoActualizarCheckListUCAS' => ['success', 'Exito', 'Check List Actualizado con Exito.'],
                        'exitotccuma' => ['success', 'Exito', 'Check List TCC de las UMA guardado con exito'],
                        'errorSubirImagenes' => ['error', 'Error', 'Error al subir las imagenes. Por favor reintente'],
                        'errorSubirImagenes1' => ['error', 'Error', 'La imagen ya se encuentra subida.'],
                        'errorIngresoUpdate' => ['error', 'Error', 'Error con el ingreso del Check List.'],
                        'errorInsert' => ['error', 'Error', 'Error con el ingreso del Check List.'],
                        'errorGeneral' => ['error', 'Error', 'Error con el ingreso del Check List.'],
                        'errorModificarIngresoVF' => ['error', 'Error', 'Error con el modificar del Check List.'],
                        'errorModificarGeneralVF' => ['error', 'Error', 'Error con el modificar del Check List.'],
                        'exitoModificarIngresoVF' => ['success','Exito', 'Exito en modificar el check list.'],
                        'exitoIngresoVF' => ['success', 'Exito', 'Check List de Vigas Frias guardado con exito'],
                        'logroPermisos' => ['success', 'Exito', 'Ingresado Con Exito'],
                        'errorPermisos' => ['error', 'Error', 'Error al ingresar, Contacte a Yorgo Casimis']
                    ];

                    foreach ($mensajes as $param => $mensaje) {
                        if (isset($_GET[$param])) {
                            mostrarMensaje(...$mensaje);
                        }
                    }
                    ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
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

                            <?php
                            if ($_GET['errorLogicaProgram']) {
                                ?>
                                <script>
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Ha ocurrido un error al modificar el check list.Por favor reintente",
                                        icon: "error"
                                    });
                                </script>
                                <?php
                            }
                            if ($_GET['exitoModificarLogicaProgramacion']) {
                                ?>
                                <script>
                                    Swal.fire({
                                        title: "Exito!",
                                        text: "Se ha modificado correctamente el check list de logica de programacion",
                                        icon: "success"
                                    });
                                </script>
                                <?php
                            }
                            if ($_GET['exitoLogicaProgram']) {
                                ?>
                                <script>
                                    Swal.fire({
                                        title: "Exito!",
                                        text: "Se ha ingresado correctamente el check list de logica de programacion",
                                        icon: "success"
                                    });
                                </script>
                                <?php
                            }
                            if ($_GET['exitoPantallasGraficas']) {
                                ?>
                                <script>
                                    Swal.fire({
                                        title: "Exito!",
                                        text: "Se ha ingresado correctamente el check list",
                                        icon: "success"
                                    });
                                </script>
                                <?php
                            }

                            if ($_GET['exitoModificarPantallasGraficas']) {
                                ?>
                                <script>
                                    Swal.fire({
                                        title: "Exito!",
                                        text: "Se ha modificado correctamente el check list",
                                        icon: "success"
                                    });
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

                            if (isset($_GET['exitoControladorECB'])) {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'El checklist fue ingresado correctamente'
                                    })
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorControladorECB'])) {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error al ingresar el checklist. Reintente'
                                    })
                                </script>
                                <?php
                            }

                            if (isset($_GET['exitoModiControladorECB'])) {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'El checklist fue modificado correctamente'
                                    })
                                </script>
                                <?php
                            }

                            if (isset($_GET['errorModiControladorECB'])) {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error al modificar el checklist. Reintente'
                                    })
                                </script>
                                <?php
                            }

                            ?>
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
                                        <button class="btn btn-block bg-gradient-yellow btn-md show-listar-detalle" id="listarAC" style="display:none;" name="listarAC">
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
                                    if ($contarchecklistucas > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistucas">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistucas" name="checklistucas" class="btn btn-block btn-md text-white mostrar-check-list-ucas">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List UCAS
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallesucas" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistarucas" name="checklistarucas" class="btn btn-block btn-md text-white listar-check-list-ucas">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($contarchecklistcontroladores > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistcontroladores">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistcontroladores" name="checklistcontroladores" class="btn btn-block btn-md text-white mostrar-check-list-controladores">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List Controladores ECB
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallescontroladores" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistarcontroladores" name="checklistarcontroladores" class="btn btn-block btn-md text-white listar-check-list-controladores">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($contarchecklisttccuma > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklisttccuma">
                                            <button type="button" style="background-color: indianred;color: white" id="checklisttccuma" name="checklisttccuma" class="btn btn-block btn-md text-white mostrar-check-list-tccuma">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List TCC de UMA
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallestccuma" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistartccuma" name="checklistartccuma" class="btn btn-block btn-md text-white listar-check-list-tccuma">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if ($contarchecklistvav > 0) {
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistvav">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistvav" name="checklistvav" class="btn btn-block btn-md text-white mostrar-check-list-vav">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List VAV
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallesvav" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistarvav" name="checklistarvav" class="btn btn-block btn-md text-white listar-check-list-vav">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if($contarchecklistvf > 0){
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistvf">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistvf" name="checklistvf" class="btn btn-block btn-md text-white mostrar-check-list-vf">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List VF
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallesvf" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistarvf" name="checklistarvf" class="btn btn-block btn-md text-white listar-check-list-vf">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Detalles
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if($contarchecklistuma > 0){
                                        ?>
                                        <div class="col-lg-3 col-12" id="divchecklistuma">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistuma" name="checklistuma" class="btn btn-block btn-md text-white mostrar-check-list-uma">
                                                <i class="fas fa-clipboard"></i>&nbsp;Mostrar Check List UMA
                                            </button>
                                        </div>
                                        <div class="col-lg-3 col-12" id="listardetallesuma" style="display: none">
                                            <button type="button" style="background-color: indianred;color: white" id="checklistlistaruma" name="checklistaruma" class="btn btn-block btn-md text-white listar-check-list-uma">
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

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

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
                                            <th style="font-size: 15px">Modificar</th>
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
                                            $observaciones = $rowCheckListPantallas['obs'];
                                            $enlaceBotones = $rowCheckListPantallas['enlaceBotones'];
                                            $enlaceImagenes = $rowCheckListPantallas['enlaceImagenes'];
                                            $enlaceParametros = $rowCheckListPantallas['enlaceParametros'];
                                            $enlaceGraficas = $rowCheckListPantallas['enlaceGraficas'];
                                            $enlaceTendencias = $rowCheckListPantallas['enlaceTendencias'];
                                            $enlaceTextos = $rowCheckListPantallas['enlaceTextos'];
                                            $revisionStatus = $rowCheckListPantallas['revisionStatus'];
                                            $temperaturaHumedad = $rowCheckListPantallas['temperaturaHumedad'];
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
                                                        <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalModiCheck<?php echo $idCheck ?>">
                                                        <i class="fas fa-pen"></i> Modificar
                                                    </button>
                                                    <div class="modal fade" id="exampleModalModiCheck<?php echo $idCheck ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gradient-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modificar Check List</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="../sdo-funciones/modificar-checklist-pantallas-graficas.php" method="POST">
                                                                    <input type="hidden" id="id" name="id" value="<?php echo $idCheck ?>">
                                                                    <div class="modal-body">
                                                                        <div class="form-row">
                                                                            <label>Pantalla:</label>
                                                                            <input type="text" class="form-control" id="pantalla" name="pantalla" value="<?php echo $pantallas ?>">
                                                                            <br>
                                                                            <br>
                                                                            <label>Especialidad:</label>
                                                                            <input type="text" class="form-control" id="especialidad" name="especialidad" value="<?php echo $especialidad ?>">
                                                                            <br>
                                                                            <br>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <table class="table table-bordered" width="100%">
                                                                                <thead class="text-center bg-gradient-secondary">
                                                                                <th>Item</th>
                                                                                <th>Revisión en pantalla gráfica</th>
                                                                                <th>Estado</th>
                                                                                </thead>
                                                                                <tbody class="text-center">
                                                                                <tr>
                                                                                    <td>1</td>
                                                                                    <td>Enlaces de botones</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceBotones == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="enlaceBotones" id="enlaceBotones">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlaceBotones" id="enlaceBotones">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>2</td>
                                                                                    <td>Enlaces de imágenes</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceImagenes == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="enlaceImagenes" id="enlaceImagenes">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlaceImagenes" id="enlaceImagenes">
                                                                                            <?php
                                                                                        }
                                                                                        ?>

                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>3</td>
                                                                                    <td>Enlaces de parámetros (temperatura, presión, etc.)</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceParametros == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlaceparametros" checked id="enlaceparametros">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlaceparametros" id="enlaceparametros">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>4</td>
                                                                                    <td>Enlaces de gráficas</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceGraficas == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" checked type="checkbox" value="1" name="enlacegraficas" id="enlacegraficas">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacegraficas" id="enlacegraficas">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>5</td>
                                                                                    <td>Enlaces de tendencias</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceTendencias == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="enlacetendencias" id="enlacetendencias">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacetendencias" id="enlacetendencias">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>6</td>
                                                                                    <td>Revisión de textos</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($enlaceTextos == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="enlacetextos" id="enlacetextos">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacetextos" id="enlacetextos">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>7</td>
                                                                                    <td>Revisión de Status effect</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($revisionStatus == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacestatus" checked id="enlacestatus">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacestatus" id="enlacestatus">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>8</td>
                                                                                    <td>Temperatura y humedad exterior</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($temperaturaHumedad == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="enlacehumedad" id="enlacehumedad">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="enlacehumedad" id="enlacehumedad">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <br>
                                                                        <div class="form-row">
                                                                            <label>Observaciones:</label>
                                                                            <textarea name="obs" style="resize: none" rows="5" class="form-control"><?php echo nl2br($observaciones) ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Modificar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <th style="font-size: 15px">Modificar</th>
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
                                    <legend>Mostrar Check List - Logica de Programacion</legend>
                                    <table id="checklistlogica" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
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
                                                        <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalModiCheckLogica<?php echo $idCheckLogica ?>">
                                                        <i class="fas fa-pen"></i> Modificar
                                                    </button>
                                                    <div class="modal fade" id="exampleModalModiCheckLogica<?php echo $idCheckLogica ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gradient-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modificar Check List - Logica de Programacion</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="../sdo-funciones/modificar-checklist-logicas-programacion.php" method="POST">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idCheckLogica ?>">
                                                                        <div class="form-row">
                                                                            <label>EC-BOS:</label>
                                                                            <input type="text" class="form-control" id="ecbos" name="ecbos" value="<?php echo $ecbos ?>">
                                                                            <br>
                                                                            <br>
                                                                            <label>Controlador:</label>
                                                                            <input type="text" class="form-control" id="controlador" name="controlador" value="<?php echo $controlador ?>">
                                                                            <br>
                                                                            <br>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <table class="table table-hover table-bordered">
                                                                                <thead class="text-center bg-gradient-secondary">
                                                                                <th>Item</th>
                                                                                <th>Revisión en lógica de programación</th>
                                                                                <th>Estado</th>
                                                                                </thead>
                                                                                <tbody class="text-center">
                                                                                <tr>
                                                                                    <td>1</td>
                                                                                    <td>Nombre de hojas de programación</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($hojasprogram == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="hojasprogram" id="hojasprogram">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="hojasprogram" id="hojasprogram">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>2</td>
                                                                                    <td>Presencia de títulos y/o subtítulos</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($titulossub == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="titulossub" id="titulossub">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="titulossub" id="titulossub">

                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>3</td>
                                                                                    <td>Orden de bloques</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($ordenbloques == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="ordenbloques" id="ordenbloques">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="ordenbloques" id="ordenbloques">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>4</td>
                                                                                    <td>Eliminar recursos inutilizados</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($recursosinutilizados == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="recursosinutilizados" id="recursosinutilizados">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="recursosinutilizados" id="recursosinutilizados">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>5</td>
                                                                                    <td>Lista de errores</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($listaerrores == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="listaerrores" id="listaerrores">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="listaerrores" id="listaerrores">
                                                                                            <?php
                                                                                        }

                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>6</td>
                                                                                    <td>Respado de lógica de control</td>
                                                                                    <td style="font-family:DejaVu Sans;text-align: center; vertical-align:middle;">
                                                                                        <?php
                                                                                        if ($logicacontrol == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="logicacontrol" id="logicacontrol">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="logicacontrol" id="logicacontrol">
                                                                                            <?php
                                                                                        }

                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <br>
                                                                        <br>
                                                                        <div class="form-row">
                                                                            <label>Observaciones:</label>
                                                                            <textarea name="obsLogica" style="resize: none" rows="5" class="form-control"><?php echo nl2br($observacionesLogica) ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-primary">Modificar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowCheckListControladores" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - Controladores Modelo ECB</legend>
                                    <table id="checklistucas" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlControladores = "SELECT * FROM datos_checklist_controlador_ecb WHERE idTG = $idTG";
                                        $resControladores = mysqli_query($conexion, $sqlControladores);
                                        $count = 1;
                                        while ($rowControladores = $resControladores->fetch_assoc()) {
                                            $idCheckControladores = $rowControladores['id'];
                                            $ecbosControlador = $rowControladores['ecbos'];
                                            $controladorECB = $rowControladores['controlador'];
                                            $fechaControlador = $rowControladores['fecha'];
                                            $fecha_formato = date('d-m-Y H:i', strtotime($fechaControlador));
                                            $macaddress = $rowControladores['macaddress'];
                                            $network = $rowControladores['network'];
                                            $limpiezadispositivo = $rowControladores['limpiezadispositivo'];
                                            $verficarEstadoDis = $rowControladores['verficarEstadoDis'];
                                            $limpiezaContactosElec = $rowControladores['limpiezaContactosElec'];
                                            $verificarVoltaje = $rowControladores['verificarVoltaje'];
                                            $chequeoComunicacion = $rowControladores['chequeoComunicacion'];
                                            $verificacionFuncionamiento = $rowControladores['verificacionFuncionamiento'];
                                            $verificacionLogicaControl = $rowControladores['verificacionLogicaControl'];
                                            $verificacionIntegracion = $rowControladores['verificacionIntegracion'];
                                            $chequeoRutina = $rowControladores['chequeoRutina'];
                                            $observacionControlador = $rowControladores['obs'];
                                            $idEquipamientoECB = $rowControladores['idEquipamiento'];
                                            $folioECB = $rowControladores['folio'];

                                            $sqlEquipamiento = "SELECT * FROM `equipamiento_hbqp` WHERE id = $idEquipamientoECB";
                                            $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
                                            while($rowEquipamiento = $resEquipamiento->fetch_assoc()){
                                                $estadoEquipo = $rowEquipamiento['estadoEquipo'];
                                                $idCategoriaECB = $rowEquipamiento['idCategoria'];
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $fecha_formato ?></td>
                                                <td><?php echo $ecbosControlador ?></td>
                                                <td><?php echo $controladorECB ?></td>
                                                <td>
                                                    <form target="_blank" action="../sdo-pdf/SDO-CheckList-Cotrolador-ECB.php" method="POST">
                                                        <input type="hidden" name="idCheck" value="<?php echo $idCheckControladores ?>">
                                                        <input type="hidden" name="folio" value="<?php echo $folioECB ?>">
                                                        <input type="hidden" name="idTG" value="<?php echo $idTG ?>">
                                                        <button type="submit" class="btn btn-sm bg-success"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalControlador<?php echo $idCheckControladores ?>">
                                                        <i class="fas fa-pen"></i>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModalControlador<?php echo $idCheckControladores ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Modificar Check List de Controlador Modelo ECB</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="../sdo-funciones/modificar-check-list-controlador.php" method="POST">

                                                                    <div class="modal-body">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idCheckControladores ?>">
                                                                        <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $idUsuario ?>">
                                                                        <div class="form-row">
                                                                            <label>EC-BOS:</label>
                                                                            <input type="text" class="form-control" id="ecbos" name="ecbos" value="<?php echo $ecbosControlador ?>">
                                                                            <br>
                                                                            <br>
                                                                            <label>Controlador:</label>
                                                                            <select name="ecbID" id="ecbID" class="form-control select2" style="width: 100%" required onchange="updateInput()">
                                                                                <option value="<?php echo $idEquipamientoECB?>" selected><?php echo $controladorECB?></option>
                                                                                <?php
                                                                                // Consultar la base de datos para obtener los equipos
                                                                                $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE '%ECB%' AND nombreEquipo NOT LIKE '%$controladorECB%' ORDER BY nombreEquipo ASC";
                                                                                $resServicio = mysqli_query($conexion, $sqlServicio);

                                                                                // Verificar si $idEquipamiento tiene un valor
                                                                                while ($rowServicio = $resServicio->fetch_assoc()) {
                                                                                    $idEquipo = $rowServicio['id'];
                                                                                    $idCategoria = $rowServicio['idCategoria'];
                                                                                    $nombreEquipo = $rowServicio['nombreEquipo'];

                                                                                    // Si el id del equipo es igual al $idEquipamiento, marcarlo como seleccionado
                                                                                    $selected = ($idEquipo == $idEquipamiento) ? 'selected' : '';

                                                                                    ?>
                                                                                    <option value="<?php echo $idEquipo; ?>" <?php echo $selected; ?> data-categoria="<?php echo $idCategoria; ?>"><?php echo $nombreEquipo; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>


                                                                            <!-- Campo de texto para el nombre del controlador -->
                                                                            <input type="hidden" name="controlador" id="controlador" value="<?php echo $controladorECB?>" readonly>
                                                                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria?>">

                                                                            <script>
                                                                                function updateInput() {
                                                                                    var selectElement = document.getElementById("ecbID");
                                                                                    var inputElement = document.getElementById("controlador");
                                                                                    var idCategoriaElement = document.getElementById("idCategoria");
                                                                                    var selectedOption = selectElement.options[selectElement.selectedIndex];

                                                                                    // Verifica si se ha seleccionado una opción válida
                                                                                    if (selectedOption.value !== "") {
                                                                                        inputElement.value = selectedOption.textContent; // Guarda el nombre del controlador en el input
                                                                                        idCategoriaElement.value = selectedOption.getAttribute('data-categoria'); // Actualiza el valor de idCategoria
                                                                                    } else {
                                                                                        // Si se selecciona la opción predeterminada, limpia los valores
                                                                                        inputElement.value = "";
                                                                                        idCategoriaElement.value = ""; // Limpiar el valor de idCategoria
                                                                                    }
                                                                                }

                                                                                // Si $idEquipamiento tiene un valor, seleccionar la opción correspondiente y actualizar el campo de texto
                                                                                window.onload = function() {
                                                                                    <?php if (!empty($idEquipamiento)): ?>
                                                                                    // Establecer el valor del controlador en el campo de texto cuando la página cargue
                                                                                    var selectElement = document.getElementById("vavID");
                                                                                    var inputElement = document.getElementById("controlador");
                                                                                    var idCategoriaElement = document.getElementById("idCategoria");

                                                                                    // Buscar la opción que coincide con $idEquipamiento
                                                                                    var selectedOption = selectElement.querySelector('option[value="<?php echo $idEquipamiento; ?>"]');

                                                                                    // Establecer el valor del input con el nombre del equipo
                                                                                    if (selectedOption) {
                                                                                        inputElement.value = selectedOption.textContent;
                                                                                        idCategoriaElement.value = selectedOption.getAttribute('data-categoria'); // Establecer el idCategoria
                                                                                    }
                                                                                    <?php endif; ?>
                                                                                }
                                                                            </script>
                                                                            <br>
                                                                            <br>
                                                                            <label>Mac Address:</label>
                                                                            <input type="text" class="form-control" id="macaddress" name="macaddress" value="<?php echo $macaddress ?>">
                                                                            <br>
                                                                            <label>Network:</label>
                                                                            <input type="text" class="form-control" id="network" name="network" value="<?php echo $network ?>">
                                                                            <br>
                                                                            <br>
                                                                            <div class="form-group col-12">
                                                                                <label style="float: left;">ESTADO DEL EQUIPO:</label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                                                    </div>
                                                                                    <?php
                                                                                    if($estadoEquipo == 'Operativo'){
                                                                                        ?>
                                                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                                            <option  disabled value="">Seleccione....</option>
                                                                                            <option value="Operativo" selected>Operativo</option>
                                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                                        </select>
                                                                                        <?php
                                                                                    }else if($estadoEquipo == 'Fuera de Servicio'){
                                                                                        ?>
                                                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                                            <option  disabled value="">Seleccione....</option>
                                                                                            <option value="Operativo" >Operativo</option>
                                                                                            <option value="Fuera de Servicio" selected>Fuera de Servicio</option>
                                                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                                        </select>
                                                                                        <?php
                                                                                    }else if($estadoEquipo == 'OperativoObs'){
                                                                                        ?>
                                                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                                            <option  disabled value="">Seleccione....</option>
                                                                                            <option value="Operativo" >Operativo</option>
                                                                                            <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                                            <option value="OperativoObs" selected>Operativo con Observaciones</option>
                                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                                        </select>
                                                                                        <?php
                                                                                    }else if($estadoEquipo == 'Mantenimiento'){
                                                                                        ?>
                                                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                                            <option  disabled value="">Seleccione....</option>
                                                                                            <option value="Operativo" >Operativo</option>
                                                                                            <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                                            <option value="OperativoObs" >Operativo con Observaciones</option>
                                                                                            <option value="Mantenimiento" selected>Mantenimiento</option>
                                                                                        </select>
                                                                                        <?php
                                                                                    }else{
                                                                                        ?>
                                                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                                            <option  disabled selected value="">Seleccione....</option>
                                                                                            <option value="Operativo" >Operativo</option>
                                                                                            <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                                            <option value="OperativoObs" >Operativo con Observaciones</option>
                                                                                            <option value="Mantenimiento" >Mantenimiento</option>
                                                                                        </select>
                                                                                        <?php
                                                                                    }
                                                                                    ?>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <table class="table table-hover table-bordered text-center">
                                                                                <thead class="text-center bg-gradient-secondary">
                                                                                <th>Item</th>
                                                                                <th>Revisión en controlador modelo ECB</th>
                                                                                <th>Estado</th>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>1</td>
                                                                                    <td>Limpieza general del dispositivo y su entorno</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($limpiezadispositivo == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="limpiezadispositivo" id="limpiezadispositivo">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="limpiezadispositivo" id="limpiezadispositivo">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>2</td>
                                                                                    <td>Verificar el estado del dispositivo</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($verficarEstadoDis == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="verficarEstadoDis" id="verficarEstadoDis">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="verficarEstadoDis" id="verficarEstadoDis">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>3</td>
                                                                                    <td>Limpieza de contactos electrónicos</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($limpiezaContactosElec == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="limpiezaContactosElec" id="limpiezaContactosElec">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="limpiezaContactosElec" id="limpiezaContactosElec">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>4</td>
                                                                                    <td>Verificación de voltaje (DC/AC) de los contactos de acuerdo a ficha técnica (24V AC)</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($verificarVoltaje == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="verificarVoltaje" id="verificarVoltaje">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="verificarVoltaje" id="verificarVoltaje">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>5</td>
                                                                                    <td>Chequeo de la red de comunicación Bacnet MS/TP (0,7 - 1,2V AC)</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($chequeoComunicacion == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="chequeoComunicacion" id="chequeoComunicacion">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="chequeoComunicacion" id="chequeoComunicacion">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>6</td>
                                                                                    <td>Verificación de funcionamiento (Entradas y salidas)</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($verificacionFuncionamiento == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="verificacionFuncionamiento" id="verificacionFuncionamiento">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="verificacionFuncionamiento" id="verificacionFuncionamiento">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>7</td>
                                                                                    <td>Verificación de lógica de control (Programación)</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($verificacionLogicaControl == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="verificacionLogicaControl" id="verificacionLogicaControl">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="verificacionLogicaControl" id="verificacionLogicaControl">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>8</td>
                                                                                    <td>Verificación de integración en software de BMS</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($verificacionIntegracion == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="verificacionIntegracion" id="verificacionIntegracion">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="verificacionIntegracion" id="verificacionIntegracion">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>9</td>
                                                                                    <td>Chequeo a la rutina de programación y alarmas</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($chequeoRutina == 1) {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" checked value="1" name="chequeoRutina" id="chequeoRutina">
                                                                                            <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <input class="form-check-input" type="checkbox" value="1" name="chequeoRutina" id="chequeoRutina">
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <br>
                                                                        <div class="form-row">
                                                                            <label>Observaciones:</label>
                                                                            <textarea name="obsControlador" style="resize: none" rows="5" class="form-control"><?php echo nl2br($observacionControlador) ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                        <button type="submit" class="btn btn-success">Modificar</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowCheckListUCAS" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - UCAS</legend>
                                    <table id="checklistucas" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">UCA</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $countUCAS = 1;
                                        $sqlUcas = "SELECT * FROM datos_checklist_ucas WHERE idTG = $idTG ORDER BY uca ASC";
                                        $resUcas = mysqli_query($conexion, $sqlUcas);
                                        while ($rowUcas = $resUcas->fetch_assoc()) {
                                            $fechaUca = $rowUcas['fecha'];
                                            $uca = $rowUcas['uca'];
                                            $idCheckUCA = $rowUcas['id'];
                                            ?>
                                            <tr>
                                                <td><?php echo $countUCAS ?></td>
                                                <td><?php echo $fechaUca ?></td>
                                                <td><?php echo $uca ?></td>
                                                <td>
                                                    <form target="_blank" action="../sdo-pdf/SDO-CheckList-Ucas.php" method="POST">
                                                        <input type="hidden" name="idCheck" value="<?php echo $idCheckUCA ?>">
                                                        <input type="hidden" name="idTG" value="<?php echo $idTG ?>">
                                                        <button type="submit" class="btn btn-sm bg-success"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="modificar_checklist_ucas.php" method="post">
                                                        <input type="hidden" name="idCheck" value="<?php echo $idCheckUCA ?>">
                                                        <input type="hidden" name="idTG" value="<?php echo $idTG ?>">
                                                        <button type="submit" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $countUCAS++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">UCA</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
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
                                            <!--
                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                            <th style="font-size: 15px">Acciones</th>
                                            -->
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
                                                <!--
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
                                                -->
                                                <!--
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
                                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalEyecheck<?php echo $idLineaVida ?>"><i class="fas fa-eye"></i></button>
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
                                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEye<?php echo $idLineaVida ?>"><i class="fas fa-eye"></i></button>
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
                                                -->
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
                                            <!--
                                            <th style="font-size: 15px">Pedido a Pañol</th>
                                            <th style="font-size: 15px">Pedido a Solicitante por Solicitud de Compra</th>
                                            <th style="font-size: 15px">Acciones</th>
                                            -->
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="row" id="rowCheckListtccuma" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - TCC DE UMA</legend>
                                    <table id="checklistucas" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlTCCUma = "SELECT * FROM datos_tcc_uma WHERE idTG = $idTG";
                                        $resTCCUma = mysqli_query($conexion, $sqlTCCUma);
                                        $contarTCCUMA = 1;
                                        while ($rowTCCUma = $resTCCUma->fetch_assoc()) {
                                            $fechaIngreso = $rowTCCUma['fecha'];
                                            $folio = $rowTCCUma['folio'];
                                            $idTCCUMA = $rowTCCUma['id'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $contarTCCUMA ?>
                                                </td>
                                                <td>
                                                    <?php echo $fechaIngreso ?>
                                                </td>
                                                <td>
                                                    <?php echo $folio ?>
                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/SDO-CheckList-TCC-UMA.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                        <input type="hidden" id="idTCCUMA" name="idTCCUMA" value="<?php echo $idTCCUMA ?>">
                                                        <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="modificar-check-list-tcc-uma.php" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                        <input type="hidden" id="idTCCUMA" name="idTCCUMA" value="<?php echo $idTCCUMA ?>">
                                                        <button type="submit" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $contarTCCUMA++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowCheckListvav" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - VAV</legend>
                                    <table id="checklistucas" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlVAV = "SELECT * FROM datos_vav WHERE idTG = $idTG";
                                        $resVAV = mysqli_query($conexion, $sqlVAV);
                                        $contarVAV = 1;
                                        while ($rowVAV = $resVAV->fetch_assoc()) {
                                            $fechaIngresoVAV = $rowVAV['fecha'];
                                            $folioVAV = $rowVAV['folio'];
                                            $idVAV = $rowVAV['idVav'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $contarVAV ?>
                                                </td>
                                                <td>
                                                    <?php echo $fechaIngresoVAV ?>
                                                </td>
                                                <td>
                                                    <?php echo $folioVAV ?>
                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/SDO-CheckList-VAV.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                        <input type="hidden" id="idVAV" name="idVAV" value="<?php echo $idVAV ?>">
                                                        <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="modificar-check-list-vav.php" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                        <input type="hidden" id="idVAV" name="idVAV" value="<?php echo $idVAV ?>">
                                                        <button type="submit" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $contarVAV++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowCheckListvf" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - VF</legend>
                                    <table id="checklistvf" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sqlVF = "SELECT * FROM datos_vf WHERE idTG = $idTG ";
                                                $resVF = mysqli_query($conexion, $sqlVF);
                                                $contadorVF = 1;
                                                while($rowVF = $resVF->fetch_assoc()){
                                                    $fechaIngresoVF = $rowVF['fecha'];
                                                    $folioVF = $rowVF['folio'];
                                                    $idVF = $rowVF['id'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $contadorVF?></td>
                                                        <td><?php echo $fechaIngresoVF?></td>
                                                        <td><?php echo $folioVF?></td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-VF.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG; ?>">
                                                                <input type="hidden" id="idVF" name="idVF" value="<?php echo $idVF; ?>">

                                                                <button type="submit" class="btn btn-secondary btn-md" title="Ver detalles">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <form action="modificar_checklist_vf.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG; ?>">
                                                                <input type="hidden" id="idVF" name="idVF" value="<?php echo $idVF; ?>">

                                                                <button type="submit" class="btn btn-warning btn-md" title="Modificar">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $contadorVF++;
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowCheckListuma" style="display: none">
                    <div class="col-md-12">
                        <div class="card" style="text-align:center;">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Mostrar Check List - UMA</legend>
                                    <table id="checklistuma" class="table table-bordered table-sm">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlUMA = "SELECT * FROM datos_uma WHERE idTG = $idTG ";
                                        $resUMA = mysqli_query($conexion, $sqlUMA);
                                        $contadorUMA = 1;
                                        while($rowUMA = $resUMA->fetch_assoc()){
                                            $fechaIngresoUMA = $rowUMA['fecha'];
                                            $folioUMA = $rowUMA['folio'];
                                            $idUMA = $rowUMA['idUma'];
                                            ?>
                                            <tr>
                                                <td><?php echo $contadorUMA?></td>
                                                <td><?php echo $fechaIngresoUMA?></td>
                                                <td><?php echo $folioUMA?></td>
                                                <td>
                                                    <form action="../sdo-pdf/SDO-CheckList-UMA.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG; ?>">
                                                        <input type="hidden" id="idUMA" name="idUMA" value="<?php echo $idUMA; ?>">

                                                        <button type="submit" class="btn btn-secondary btn-md" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="modificar_checklist_uma.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG; ?>">
                                                        <input type="hidden" id="idUMA" name="idUMA" value="<?php echo $idUMA; ?>">

                                                        <button type="submit" class="btn btn-warning btn-md" title="Modificar">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $contadorVF++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">Folio</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                            <th style="font-size: 15px">Modificar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row" id="rowInformacion">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <fieldset>
                                    <legend>Informaci&oacute;n de la Orden de Trabajo</legend>

                                    <br>
                                    &nbsp;
                                    <br>
                                    &nbsp;
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
                                                <?php echo $nomPersonal ?>
                                            </h5>
                                            <?php
                                            if ($ticketAsociado == 0 || empty($ticketAsociado)) {
                                            } else {
                                                ?>
                                                <h5>
                                                    <b>Ticket Asociado:</b>
                                                    <?php echo $ticketAsociado ?>
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
                                            if ($idDepartamento == 1) {
                                                if ($detalleTurnoRonda == '' || empty($detalleTurnoRonda)) {
                                                } else {
                                                    ?>
                                                    <h5>
                                                        <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                            <?php echo $contarRondas ?></b>
                                                    </h5>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if ($cantidadCheckList == 0 || $cantidadCheckList == '') {
                                                } else {
                                                    if ($contarchecklistpantallasgraficas > 0) {
                                                        ?>
                                                        <h5>
                                                            <b>Se han completado: <?php echo $contarchecklistpantallasgraficas . ' de ' . $cantidadCheckList . ' CheckList' ?></b>
                                                            <!--Se ha completado 0 de 1 lista de verificación.-->
                                                        </h5>
                                                        <?php
                                                    } else if ($contarchecklistlogicaprogram > 0) {
                                                        ?>
                                                        <h5>
                                                            <b>Se han completado: <?php echo $contarchecklistlogicaprogram . ' de ' . $cantidadCheckList . ' CheckList' ?></b>
                                                            <!--Se ha completado 0 de 1 lista de verificación.-->
                                                        </h5>
                                                        <?php
                                                    } else if ($contarchecklistucas > 0) {
                                                        ?>
                                                        <h5>
                                                            <b>Se han completado: <?php echo $contarchecklistucas . ' de ' . $cantidadCheckList . ' CheckList' ?></b>
                                                            <!--Se ha completado 0 de 1 lista de verificación.-->
                                                        </h5>
                                                        <?php
                                                    } else if ($contarchecklistcontroladores > 0) {
                                                        ?>
                                                        <h5>
                                                            <b>Se han completado: <?php echo $contarchecklistcontroladores . 'de' . $cantidadCheckList . 'CheckList' ?></b>
                                                        </h5>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <?php
                                                if ($servicio > 0) {
                                                    ?>
                                                    <h5>
                                                        <b>Unidad o Servicio: <?php echo $buscarNombreServicio ?></b>
                                                    </h5>
                                                    <?php
                                                }

                                                if ($formularioRondas == '' || empty($formularioRondas)) {
                                                } else {
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
                                            }

                                            if ($idEquipamiento > 0) {
                                                ?>
                                                    <br>
                                                <h5>
                                                    <b>EQUIPAMIENTO ASOCIADO: </b><?php echo getNomEquipamiento($idEquipamiento); ?>
                                                </h5>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                        <div class="col-md-4 col-12 text-center">
                                            <div class="col-md-12">
                                                <?php
                                                $contarArchivos = contarArchivosDocumento($idTG);
                                                if ($contarArchivos > 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-block" style="background-color: deepskyblue" data-toggle="modal" data-target="#mostrarDescargar"><i class="fas fa-file"></i>&nbsp;Descargar Documentos Adjuntos</button>
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
                                                                                    <form action="visualizardetalles.php" method="POST" style="display: inline">
                                                                                        <?php
                                                                                        if (empty($folioDocumento) || $folioDocumento == '') {
                                                                                        } else {
                                                                                            ?>
                                                                                            <input type="hidden" name="folioDocumento" id="folioDocumento" value="<?php echo $folioDocumento ?>">
                                                                                            <input type="hidden" name="idArchivoDocu" id="idArchivoDocu" value="<?php echo $idArchivoDocu ?>">
                                                                                            <button type="submit" class="btn btn-block" style="background-color: deepskyblue; color: white"><i class="fas fa-download"></i>&nbsp;Descargar Documento</button>
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
                                                    <form action="visualizardetalles.php" method="POST" style="display: inline">
                                                        <button type="submit" class="btn btn-block" title="Descargar Archivo Subido por el Profesional" style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG ?>">

                                                    </form>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-block bg-danger" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i> Agregar una Observacion a la OT</button>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-block bg-info" data-toggle="modal" data-target="#exampleModallinadevida"><i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida</button>
                                            </div>
                                            <br>
                                            <!--
                                            <div class="col-md-12">
                                                <button type="button" style="color: whitesmoke" class="btn btn-block bg-gradient-primary" data-toggle="modal" data-target="#exampleModalmateriales"><i class="fas fa-eye"></i>&nbsp;Solicitar Materiales</button>
                                            </div>
                                            -->
                                            <br>
                                            <?php
                                            if ($departamentos == 1 || $departamentos == 13) {
                                                if($nomUsuario === 'Pablo Sanchez Molina'){
                                                    ?>
                                                    <div class="col-md-12">
                                                        <form action="ordenes-con-firma.php" target="_blank" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                            <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica</button>
                                                        </form>
                                                    </div>
                                                    <?php
                                                }
                                                if ($contarDetalles <= $contarRespuesta) {
                                                    ?>
                                                    <div class="col-md-12">
                                                        <form action="ordenes-con-firma.php" target="_blank" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                            <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica</button>
                                                        </form>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                if ($contarDetallesNoControl == $contarNoControl) {
                                                    if ($idDepartamento == 3 || $idDepartamento == 2 || $idDepartamento == 4 || $idDepartamento == 5 || $idDepartamento == 6 || $idDepartamento == 8 || $idDepartamento == 10 || $idDepartamento == 12) {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <button class="btn btn-danger btn-block terminartodo" name="<?php echo $idTG ?>">Finalizar OT</button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <form action="ordenes-con-firma.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica</button>
                                                            </form>
                                                        </div>
                                                        <?php
                                                    }
                                                }

//                                                echo '<br><br>Detalles '.$contarDetallesNoControl.' - No Control '.$contarNoControl;
                                            }
                                            $buscarArchivostecnico = buscararchivostecnicos($idTG);
                                            if ($buscarArchivostecnico > 0) {
                                                ?>
                                                <br>
                                                <br>
                                                <form action="visualizardetalles.php" method="POST">
                                                    <input type="hidden" id="folioTGDescargar" name="folioTGDescargar" value="<?php echo $folioTG ?>">
                                                    <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-download"></i> Descargar Archivos Subidos por el Funcionario Asignado</button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="modal fade" id="exampleModalVERBMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModalVERCCTV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModalVERLLE" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModalVERCA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
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
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModalVEROTROS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
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
                                                            <textarea class="form-control" style="" id="detalle" readonly
                                                                      rows="10"
                                                                      name="detalle"><?php echo $detalleOBS1 ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                                                                    observacion: <code>*</code></label>
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
                                                                <br><br>
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
                                                            <label for="exampleInputBorder">Nombre de Usuario: <code>*</code></label>
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
                                                        <h5 class="modal-title" id="exampleModalLabel">MODIFICAR TRABAJO</h5>
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

                                    </div>
                                    <br>
                                    <br>


                                </fieldset>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="row" id="rowTareas">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">


                                <div class="row">

                                    <?php
                                    if ($idDepartamento == 1) {
                                        ?>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en BMS
                                                </legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                                <?php
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
                                                    if ($cantidadCheckList > 0) {
                                                        if ($tipoChecklist == 'Pantallas Graficas') {
                                                            if ($contarchecklistpantallasgraficas >= $cantidadCheckList) {
                                                            } else {
                                                                ?>
                                                                <form action="completar_checklist_pantallas_graficas.php" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Pantallas Graficas
                                                                    </button>
                                                                    <br>
                                                                </form>
                                                                <?php
                                                            }
                                                        } else if ($tipoChecklist == 'Logica de Programacion') {
                                                            if ($contarchecklistlogicaprogram >= $cantidadCheckList) {
                                                            } else {
                                                                ?>
                                                                <form action="completar_checklist_logica_program.php" method="post">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Lógica de Programación
                                                                    </button>
                                                                    <br>
                                                                </form>
                                                                <?php
                                                            }
                                                        } else if ($tipoChecklist == 'Controlador Modelo ECB') {
                                                            if ($contarchecklistcontroladores >= $cantidadCheckList) {
                                                            } else {
                                                                ?>
                                                                <form action="completar_checklist_controlador_ecb.php" method="post">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Controlador Modelo ECB
                                                                    </button>
                                                                    <br>
                                                                </form>
                                                                <?php
                                                            }

                                                        } else if ($tipoChecklist == 'TCC de UMA') {
                                                            if ($contarchecklisttccuma >= $cantidadCheckList) {
                                                            } else {
                                                                ?>
                                                                <form action="completar_checklist_tcc_uma.php" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de TCC de UMA
                                                                    </button>
                                                                </form>
                                                                <?php
                                                            }
                                                        } else if ($tipoChecklist == 'VAV') {
                                                            if ($contarchecklistvav >= $cantidadCheckList) {
                                                            } else {
                                                                ?>
                                                                <form action="completar_checklist_vav.php" target="_blank" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de VAV
                                                                    </button>
                                                                </form>
                                                                <?php
                                                            }
                                                        } else if($tipoChecklist == 'VF'){
                                                            if($contarchecklistvf >= $cantidadCheckList){
                                                            }else{
                                                                ?>
                                                                <form action="completar_checklist_vf.php" target="_blank" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de VF
                                                                    </button>
                                                                </form>
                                                                <?php
                                                            }
                                                            ?>
                                                            <br>
                                                            <a href="../sdo-personal/ASTCC/ASTCC.pdf"
                                                                    download
                                                                    title="Recuerde imprimir en carta"
                                                                    class="btn btn-block"
                                                                    style="background-color: purple; color: white;">
                                                                <i class="fas fa-download"></i> Descargar AST VF
                                                            </a>
                                                            <?php
                                                        } else if($tipoChecklist == 'UMA'){
                                                            if($contarchecklistuma >= $cantidadCheckList) {
                                                            }else{
                                                                ?>
                                                                <form action="completar_checklist_uma.php" target="_blank" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de UMA
                                                                    </button>
                                                                </form>
                                                                <?php
                                                            }
                                                        }
                                                    }

                                                }
                                                ?>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en CCTV
                                                </legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleTG" class="form-control"
                                                          rows="15"><?php echo $detalleTurnoCamara; ?></textarea>
                                                <br>
                                                <?php
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
                                                ?>
                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en LLE
                                                </legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleTG" class="form-control"
                                                          rows="15"><?php echo $detalleTurnoLLE; ?></textarea>
                                                <br>
                                                <?php
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

                                                if($tipoChecklist === 'LLamado de Enfermeria'){
                                                    if($contarchecklistLLE >= $cantidadCheckList) {

                                                    }else{
                                                        ?>
                                                        <form action="../sdo-personal/completar_checklist_lle.php" method="post">
                                                            <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                            <br>
                                                            <button type="submit"
                                                                    class="btn btn-block"
                                                                    style="background-color: blue;color:white;">Completar Check List de LLE
                                                            </button>
                                                        </form>
                                                        <?php
                                                    }
                                                }

                                                }

                                                ?>
                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control
                                                    de Acceso
                                                </legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleTG" class="form-control"
                                                          rows="15"><?php echo $detalleTurnoCA; ?></textarea>
                                                <br>
                                                <?php
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
                                                                style="background-color: blue;color:white;">Completar Trabajos de
                                                            CA
                                                        </button>
                                                        <?php
                                                    }
                                                    if($nomUsuario === 'Pablo Sanchez Molina'){
                                                        if($contarPermisosRevisados > 0){

                                                        }else{
                                                            ?>
                                                            <br>
                                                            <button type="button" data-toggle="modal" data-target="#exampleModalPERMISOS"
                                                                    class="btn btn-block"
                                                                    style="background-color: blue;color:white;"> Permisos
                                                            </button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="exampleModalPERMISOS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-secondary">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Permisos Revisados</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/guardar_memos_revisados.php" method="POST">
                                                                            <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                            <input type="hidden" name="nombreUsuario" id="nombreUsuario" value="<?php echo $nomUsuario ?>">
                                                                            <div class="modal-body">
                                                                                <label>Seleccionar Memos</label>
                                                                                <select class="form-control listadoUsuarios"  id="listadoUsuarios" name="listadoUsuarios[]" multiple="multiple">
                                                                                    <option value="Seleccione..." disabled>Seleccione...</option>
                                                                                    <?php
                                                                                    $sqlMemos = "SELECT * FROM `memos_control_acceso` ORDER BY id DESC";
                                                                                    $resMemos = mysqli_query($conexion, $sqlMemos);
                                                                                    while ($rowMemos = $resMemos->fetch_assoc()) {
                                                                                        $idMemos = $rowMemos['id'];
                                                                                        //$unidad = $rowMemos['unidad'];
                                                                                        $datos = "N° PLATAFORMA: ".$idMemos;
                                                                                        ?>
                                                                                        <option value="<?php echo $idMemos?>"><?php echo $datos?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <hr>
                                                                                <label>Selecciona Formatos</label>
                                                                                <select class="form-control listadoFormatos"  id="listadoFormatos" name="listadoFormatos[]" multiple="multiple">
                                                                                    <option value="Seleccione..." disabled>Seleccione...</option>
                                                                                    <?php
                                                                                    $sqlFormatos = "SELECT * FROM `formularios_registro` ORDER BY idFormRegAcceso DESC";
                                                                                    $resFormatos = mysqli_query($conexion, $sqlFormatos);
                                                                                    while ($rowFormatos = $resFormatos->fetch_assoc()) {
                                                                                        $idFormatos = $rowFormatos['idFormRegAcceso'];
                                                                                        $folioFormato = $rowFormatos['folioFormRegAcceso'];

                                                                                        ?>
                                                                                        <option value="<?php echo $folioFormato?>"><?php echo $folioFormato?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        
                                                        echo "<br>";
                                                        ?>
                                                        <br>
                                                        <form action="revisar_datos_funcionario.php" method="POST">
                                                            <button type="submit" class="form-control btn" style="background-color: blue;color:white;"> Revisar Datos del Funcionario</button>
                                                            <?php
                                                                $sqlBuscar = "SELECT * FROM `datos_credencial_funcionarios` WHERE idTG = $idTG";
                                                                $resBuscar = mysqli_query($conexion,$sqlBuscar);
                                                                while($rowBuscar = $resBuscar->fetch_assoc()){
                                                                    ?>
                                                                    <input type="hidden" name="idRegistroTarjeta" id="idRegistroTarjeta" value="<?php echo $rowBuscar['idRegistroTarjeta']?>">
                                                                    <?php
                                                                }
                                                            ?>
                                                        </form>
                                                        <?php
                                                        
                                                        
                                                    }
                                                }

                                                if ($cantidadCheckList > 0) {
                                                    if ($tipoChecklist == 'Unidades Control de Accesos') {
                                                        if ($contarchecklistucas >= $cantidadCheckList) {
                                                        } else {
                                                            if($contarASTUCA > 0){
                                                                ?>
                                                                <form action="completar_checklist_ucas.php" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <br>
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Unidades de Control de Accesos
                                                                    </button>
                                                                    <br>
                                                                </form>

                                                                <br>
                                                                <form action="../sdo-pdf/SDO-AST-UCAS.php" method="POST" target="_blank">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <button type="submit"                                                                             class="btn btn-block"
                                                                            style="background-color: blue;color:white;">
                                                                        Revisar AST
                                                                    </button>
                                                                </form>


                                                                <?php
                                                            }else{
                                                                if($nomUsuario == 'Pablo Sanchez Molina'){
                                                                    ?>
                                                                    <form action="completar_checklist_ucas.php" method="POST">
                                                                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                        <br>
                                                                        <button type="submit"
                                                                                class="btn btn-block"
                                                                                style="background-color: blue;color:white;">Completar Check List de Unidades de Control de Accesos
                                                                        </button>
                                                                        <br>
                                                                    </form>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <br>
                                                                <!-- Button trigger modal -->
                                                                <button type="button"                                                                             class="btn btn-block"
                                                                        style="background-color: blue;color:white;" data-toggle="modal" data-target="#exampleModalAST">
                                                                    Revisar AST
                                                                </button>
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="exampleModalAST" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary ">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Revisar AST</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form action="../sdo-funciones/guardar-personas-ast-ucas.php" method="POST">
                                                                                <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                                <div class="modal-body">
                                                                                    <label style="margin-left: 15px;">¿Quién realizó esta mantención?:</label>
                                                                                    <select class="form-control listadoEncInstru" required id="nomEncargadoTurno" name="nomEncargadoTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                                                        <option value="<?php echo $tecnico1 ?>" selected><?php echo $tecnico1 ?></option>
                                                                                        <?php
                                                                                        // Consulta SQL ajustada
                                                                                        $sqlUsuarios = "
                SELECT nomEmpleado FROM (
                    SELECT nomPersonal AS nomEmpleado 
                    FROM personal 
                    WHERE departamentoPersonal = $idDepartamento 
                    AND activoPersonal = 1 AND nomPersonal != '$tecnico1'
                    UNION
                    SELECT nomJefe AS nomEmpleado
                    FROM jefes_tecnicos
                    WHERE idDepartamento = $idDepartamento
                    AND userActivado = 1
                ) AS empleados
                ORDER BY nomEmpleado
            ";

                                                                                        $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                                        $grupoNombres = [
                                                                                            'Abel Torres Arancibia' => ['Abel Torres Arancibia', 'Manuel Torres Sagredo'],
                                                                                            'Manuel Torres Sagredo' => ['Abel Torres Arancibia', 'Manuel Torres Sagredo'],
                                                                                            'Felipe Retamal Fuentes' => ['Felipe Retamal Fuentes', 'Jesús Silva Pellet'],
                                                                                            'Jesús Silva Pellet' => ['Felipe Retamal Fuentes', 'Jesús Silva Pellet'],
                                                                                            'Gonzalo Cáceres Carreño' => ['Gonzalo Cáceres Carreño', 'Luis Pérez Bernal'],
                                                                                            'Luis Pérez Bernal' => ['Gonzalo Cáceres Carreño', 'Luis Pérez Bernal'],
                                                                                            'Joaquín Jorquera Gonzalez' => ['Joaquín Jorquera Gonzalez', 'Rene Condell Vargas'],
                                                                                            'Rene Condell Vargas' => ['Joaquín Jorquera Gonzalez', 'Rene Condell Vargas']
                                                                                        ];

                                                                                        function esSeleccionado($nomUsuario, $nomEmpleado, $grupoNombres) {
                                                                                            return in_array($nomUsuario, array_keys($grupoNombres)) && in_array($nomEmpleado, $grupoNombres[$nomUsuario]);
                                                                                        }

                                                                                        if ($resUsuarios->num_rows > 0) {
                                                                                            while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                                                $nomEmpleado = trim($rowUsuarios['nomEmpleado']);
                                                                                                $selected = esSeleccionado($nomUsuario, $nomEmpleado, $grupoNombres) ? 'selected' : '';
                                                                                                echo "<option value=\"$nomEmpleado\" $selected>$nomEmpleado</option>";
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <br>
                                                                                    <!-- Botón de Vista Previa -->
                                                                                    <label>Revisar Vista Previa de la AST</label><br>
                                                                                    <button type="button" class="btn bg-primary" id="btnVistaPrevia">
                                                                                        <i class="fas fa-file-pdf"></i> Vista Previa
                                                                                    </button>
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                    <button type="submit" class="btn btn-primary" id="btnGuardar" disabled>Guardar</button>
                                                                                </div>
                                                                            </form>

                                                                            <script>
                                                                                // Cuando se hace clic en "Vista Previa", se abre el PDF y se habilita el botón "Guardar"
                                                                                document.getElementById('btnVistaPrevia').addEventListener('click', function() {
                                                                                    window.open('../sdo-pdf/VistaPrevia_AST_UCA.php', '_blank');
                                                                                    setTimeout(function() {
                                                                                        document.getElementById('btnGuardar').disabled = false;
                                                                                    }, 10000);

                                                                                });
                                                                            </script>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--<form target="_blank" action="../sdo-pdf/SDO-AST-UCAS.php" method="POST">
                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Visualizar AST de UCA
                                                                    </button>
                                                                </form>-->
                                                                <?php
                                                            }

                                                        }
                                                    }
                                                }
                                                ?>
                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Rondas
                                                </legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleTG" class="form-control"
                                                          rows="15"><?php echo $detalleTurnoRonda; ?></textarea>
                                                <br>
                                                <?php
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
                                                            <br>
                                                            <?php
                                                            if ($idTG == 404) {
                                                                ?>
                                                                <button class="btn btn-block" style="background-color: blue;color:white;" data-toggle="modal" data-target="#modificarRondaAlarma">Listado de Rondas Hechas con Rondas de Alarmas</button>
                                                                <div class="modal fade" id="modificarRondaAlarma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Rondas hechas en la OT N°: <?php echo $idTG ?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <table class="table">
                                                                                    <thead class="bg-gradient-secondary">
                                                                                    <tr>
                                                                                        <th>N°</th>
                                                                                        <th>Fecha</th>
                                                                                        <th>Hora</th>
                                                                                        <th>Visualizar HT</th>
                                                                                        <th>Modificar</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php
                                                                                    $count = 1;
                                                                                    $sqlRondas = "SELECT * FROM parametros_ot_rondas WHERE idTG = 1042";
                                                                                    $resRondas = mysqli_query($conexion, $sqlRondas);
                                                                                    while ($rowRondas = $resRondas->fetch_assoc()) {
                                                                                        $fechaRonda = $rowRondas['fecha'];
                                                                                        $fechaSoloFecha = date('d-m-Y', strtotime($fechaRonda));
                                                                                        $horaRonda = $rowRondas['hora'];
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $count ?></td>
                                                                                            <td><?php echo $fechaSoloFecha ?></td>
                                                                                            <td><?php echo $horaRonda ?></td>
                                                                                            <td>
                                                                                                <?php
                                                                                                $buscarIdOrden = buscarIdOrden($fechaRonda);
                                                                                                ?>
                                                                                                <form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">
                                                                                                    <input type="hidden" name="idOrden" value="<?php echo $buscarIdOrden; ?>">
                                                                                                    <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Hoja Técnica">
                                                                                                </form>
                                                                                            </td>
                                                                                            <td>
                                                                                                <form action="modificar_ronda.php" method="POST">
                                                                                                    <input type="hidden" name="fecha" id="fecha" value="<?php echo $fechaRonda ?>">
                                                                                                    <input type="hidden" name="conteo" id="conteo" value="<?php echo $count ?>">
                                                                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                                                                    <input type="submit" id="inputMozi" class="btn btn-block btn-warning" value="Modificar Ronda">
                                                                                                </form>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                        $count++;
                                                                                    }
                                                                                    ?>
                                                                                    </tbody>
                                                                                    <tfoot class="bg-gradient-secondary">
                                                                                    <tr>
                                                                                        <th>N°</th>
                                                                                        <th>Fecha</th>
                                                                                        <th>Hora</th>
                                                                                        <th>Visualizar HT</th>
                                                                                        <th>Modificar</th>
                                                                                    </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
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
                                                            <br>
                                                            <?php
                                                            if ($idTG == 404) {
                                                                ?>
                                                                <button class="btn btn-primary btn-block">Listado de Rondas Hechas con Rondas de Parametros de Umas</button>
                                                                <?php
                                                            }
                                                            ?>
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
                                                ?>

                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle de Otros Trabajos</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleOtros" class="form-control"
                                                          rows="15"><?php echo $detalleOtros; ?></textarea>
                                                <br>
                                                <?php
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
                                                ?>
                                            </fieldset>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-md-12">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%"
                                                          readonly="readonly" name="detalleTG" class="form-control"
                                                          rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                                <?php
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

                                                    //AQUI VAN LOS CHECK LIST NO DE CONTROL
                                                    if ($cantidadCheckList > 0) {
                                                        $buscarCantidad = "SELECT * FROM tipos_check_list WHERE idTG = $idTG";
                                                        $resbuscarcantidad = mysqli_query($conexion, $buscarCantidad);
                                                        while ($rowbuscarcantidad = $resbuscarcantidad->fetch_assoc()) {
                                                            $tipoCheckListNoControl = $rowbuscarcantidad['tipo'];
                                                            $cantidadCheckListNoControl = $rowbuscarcantidad['cantidad'];
                                                            if ($tipoCheckListNoControl == 'aguaCaliente') {
                                                                ?>
                                                                <form action="check-list-agua-caliente.php" method="POST">
                                                                    <br>
                                                                    <input type="hidden" id="idTGagua" name="idTGagua" value="<?php echo $idTG ?>">
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Agua Caliente
                                                                    </button>
                                                                </form>

                                                                <?php
                                                            } else if ($tipoCheckListNoControl == 'salacomunicacion') {
                                                                ?>
                                                                <form action="#" method="POST">
                                                                    <br>
                                                                    <input type="hidden" id="idTGsala" name="idTGsala" value="<?php echo $idTG ?>">
                                                                    <button type="submit"
                                                                            class="btn btn-block"
                                                                            style="background-color: blue;color:white;">Completar Check List de Sala de Comunicacion
                                                                    </button>
                                                                </form>

                                                                <?php
                                                            }
                                                        }
                                                    }

                                                }
                                                ?>
                                                <br>
                                                <br>
                                                <button class="btn btn-lg bg-success" style="float: right" data-toggle="modal" data-target="#exampleModalSubirArchivoAdjunto"><i class="fas fa-upload"></i> Subir archivo adjunto</button>

                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>


                            </div>

                        </div>

                    </div>

                </div>
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


                <div class="row" id="rowParametrosPab" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">


                                <fieldset>
                                    <legend>Registros de Parametros de Pabellones</legend>


                                    <div class="row text-center" style="display: flex; justify-content:center;">


                                        <div class="col-lg-3 col-12" id="divAgregarParametros" name="divAgregarParametros">
                                            <!-- small box -->
                                            <button class="btn btn-block bg-gradient-success btn-md text-white" id="agregarParametros" name="agregarParametros" data-toggle="modal" data-target="#modalAgregar">
                                                <i class="fas fa-plus"></i>&nbsp;Agregar Nueva Medición
                                            </button>

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
                                                        // Mostrar el overlay de bloqueo de pantalla
                                                        $('.overlay').show();

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
                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                </div>
                                <div style="text-align:left;" class="form-group">
                                    <label for="exampleInputBorder">Fecha de Registro: <code>*</code></label>
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
                                                <input type="number" min="0" step=".1" class="form-control" max="26" required id="<?php echo $valorTemp; ?>" name="<?php echo $valorTemp; ?>" placeholder="<?php echo $nomValorTemp; ?>">
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
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;Registrar Medicion</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para editar -->
            <!-- Modal de Edición -->
            <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <form class="" method="post" action="../sdo-funciones/pabellones_actualizarMedicion.php">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title" id="modalEditarLabel">Editar Medición</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                for ($k = 2; $k < 11; $k++) {
                                    $valorTemp = 'tempPabellon' . $k;
                                    $valorHumedad = 'humedadPabellon' . $k;

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
                                <!-- Agrega los campos ocultos para enviar información necesaria al servidor -->
                                <input type="hidden" name="idRegistroPab" id="idRegistroPab">
                                <input type="hidden" name="nomModificaPab" id="nomModificaPab" value="<?php echo $nomUsuario; ?>">
                                <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo $folioTG; ?>">
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;Actualizar Medición</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </section>
    </div>


    <div class="overlay">
        <div class="spinner">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <!-- Spinner icon -->
        </div>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

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

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoEncInstru').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEspecialidades').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEncInstruAnterior').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno Anterior',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>

<script>
    function visible(valor) {
        if (valor == 1) {
            document.getElementById('materiales').style.display = 'block';
            document.getElementById('obs').style.display = 'none';
        } else {
            document.getElementById('materiales').style.display = 'none';
            document.getElementById('obs').style.display = 'block';
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
            console.log(id);
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
                                location.href =
                                    "trabajos_general_listado_finalizados.php";
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoUsuarios').select2({
            theme: 'bootstrap4'
        })
        $('#listadoUsuarios').select2({
            dropdownParent: $('#exampleModalPERMISOS')
        });

    });
</script>
<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoFormatos').select2({
            theme: 'bootstrap4'
        })
        $('#listadoFormatos').select2({
            dropdownParent: $('#exampleModalPERMISOS')
        });

    });
</script>
</body>

</html>