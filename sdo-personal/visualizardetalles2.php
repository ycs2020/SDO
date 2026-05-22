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


$contarRondas = contarRonda($idTG);

/*CONTAR LOS DETALLES A HACER EN LA OT*/
$contarDetalleBMS = contarDetalleBMS($idTG);
$contarDetalleCamara = contarDetalleCamara($idTG);
$contarDetalleLLE = contarDetalleLLE($idTG);
$contarDetalleCA = contarDetalleCA($idTG);
$contarDetalleOTROS = contarDetalleOTROS($idTG);

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
    <style>
        fieldset {
            border: 1px solid #ccc;
            padding: 10px;
        }

        legend {
            font-weight: bold;
            color: #333;
        }

        /* Eliminar el espacio interno por defecto en los elementos li */

        /* Aplicar espacio interno a los elementos li según sea necesario */
        ul li, ol li {
            padding-bottom: 10px; /* Ajusta el valor según tu preferencia */
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

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Orden-Trabajo-Visor.php" method="POST" target="_blank">
                                            <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                            <button type="submit" class="btn btn-block bg-gradient-primary btn-md text-white">
                                                <i class="fas fa-clipboard"></i>&nbsp;Pre-visualizar OT
                                            </button>
                                        </form>

                                    </div>

                                    <div class="col-lg-3 col-12" id="divParamPab" name="divParamPab">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-success btn-md show-parametros text-white" id="listarParametros" name="listarParametros">
                                            <i class="fas fa-pen"></i>&nbsp;Listado de Parametros de Pabellon
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
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
                                                ?>
                                                <h5>
                                                    <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                        <?php echo $contarRondas ?></b>
                                                </h5>
                                                <?php
                                            }

                                            if ($idDepartamento == 1) {
                                                ?>
                                                <?php
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
                                            <div class="col-md-12">
                                                <button type="button" style="color: whitesmoke" class="btn btn-block bg-gradient-primary" data-toggle="modal" data-target="#exampleModalmateriales"><i class="fas fa-eye"></i>&nbsp;Solicitar Materiales</button>
                                            </div>
                                            <br>
                                            <?php

                                            if ($departamentos == 1) {
                                                if ($contarDetalles == $contarRespuesta) {
                                                    ?>
                                                    <div class="col-md-12">
                                                        <form action="ordenes-con-firma.php" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                            <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica</button>
                                                        </form>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                if ($contarDetallesNoControl == $contarNoControl) {
                                                    if ($idDepartamento == 3) {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <button class="btn btn-danger btn-block terminartodo" name="<?php echo $idTG ?>">Finalizar OT</button>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <form action="ordenes-con-firma.php" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG ?>">
                                                                <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-clipboard-list"></i>&nbsp;Crear Hoja Técnica</button>
                                                            </form>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
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
                                                }
                                                ?>
                                            </fieldset>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control
                                                    de Rondas
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
                                                }
                                                ?>
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
                                                        return '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '"><i class="fas fa-pen"></i></button>';
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
                                <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo mb_convert_case($idTG, MB_CASE_UPPER, 'UTF-8'); ?>">
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

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
</body>

</html>