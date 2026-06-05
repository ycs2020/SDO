<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuarioSwitch = trim($_SESSION['rolUsuario']);
$rolUsuarioSwitch = mb_convert_case($rolUsuarioSwitch, MB_CASE_LOWER, 'UTF-8');

$rutaDireccion = '';

switch ($rolUsuarioSwitch) {
    case 'personal':
        $rutaDireccion = 'sdo-personal';
        break;
    case 'administrativo':
        $rutaDireccion = 'sdo-admtec';
        break;
    case 'bodega pañol':
        $rutaDireccion = 'sdo-bodega-panol';
        break;
    case 'jefe tecnico':
        $rutaDireccion = 'sdo-jefestecnicos';
        break;
    case 'administrador':
        $rutaDireccion = 'sdo-administrador';
        break;
    case 'directiva':
        $rutaDireccion = 'sdo-directiva';
        break;
    case 'oficina tecnica':
        $rutaDireccion = 'sdo-oficinaTecnica';
        break;
    default:
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
}

$idGrupoCargar = null;

if (isset($_POST['idTG'])) {
    $idTG = trim($_POST['idTG']);
} elseif (isset($_SESSION['idTG'])) {
    $idTG = trim($_SESSION['idTG']);
}

if (!$idTG || !is_numeric($idTG) || $idTG <= 0) {
    header("Location: ../$rutaDireccion/trabajos_general_listado_terminado.php?error=true");
    exit();
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
    $departamento = $row['departamento'];
    $formularioRondas = $row['formularioRondas'];
    $tipoTrabajo = $row['tipoTrabajo'];
    $idTareaProgramada = $row['idTareaProgramada'];


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
            $rutaFinal = "../sdo-administrador/";
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
}

if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];

    if ($idOrden > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folio;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-administrador/";
        $archivoZip = "Archivos-" . $folio . ".zip";

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zipSC($dir2, $zip);
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

$sqlTrabajo = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
$resTrabajo = mysqli_query($conexion, $sqlTrabajo);
while ($rowTrabajo = $resTrabajo->fetch_assoc()) {
    $idOrden = $rowTrabajo['idOrden'];
    $folio = $rowTrabajo['folioOrden'];


}
$contarHT = contarHT1($folioTG);
/*
$contarBMS = contarBMS1($folioTG);
$contarCA = contarCA1($folioTG);
$contarCCTV = contarCCTV1($folioTG);
$contarRondas = contarRondas1($folioTG);
$contarLLE = contarLLE1($folioTG);
$contarOtros = contarOtros1($folioTG);
*/
$contarchecklistpantallasgraficas = contarchecklistpantallasgraficas($idTG);
$contarchecklistcontroladores = contarchecklistcontroladores($idTG);
$contarchecklistvav = contarchecklistvav($idTG);
$contarchecklistucas = contarchecklistucas($idTG);
$contarchecklistvf = contarchecklistvf($idTG);
$contarchecklistuma = contarchecklistuma($idTG);
$contarASTUCA = contarASTUCA($idTG);


$contarRondas = contarRonda($idTG);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Orden de Trabajo</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Detalle Orden de Trabajo: <?php echo $folioTG ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="trabajos_general_listado_terminado.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="idCarga" id="idCarga" value="<?php echo $idTG; ?>">
                    <input type="hidden" name="folioCarga" id="folioCarga" value="<?php echo $folioTG; ?>">
                    <input type="hidden" name="usuarioCarga" id="usuarioCarga" value="<?php echo $nomUsuario; ?>">


                    <div class="col-md-8 col-12 d-flex">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline w-100 h-100">
                            <div class="card-body box-profile">
                                <div class="table-responsive">
                                    <table style="font-size: 15px;" class="table table-bordered table-sm">
                                        <tbody>
                                        <?php
                                        // Convertir las variables necesarias a mayúsculas o títulos
                                        $folioTG2 = mb_convert_case($folioTG, MB_CASE_UPPER, "UTF-8");
                                        // Fecha formateada
                                        $fechaTG2 = strtotime($fechaTG);
                                        $fechaTG3 = fechaEspañol2($fechaTG2);
                                        $fechaTG4 = mb_convert_case($fechaTG3, MB_CASE_UPPER, "UTF-8");
                                        // Fecha formateada
                                        $fechaProgramacion2 = strtotime($fechaProgramacion);
                                        $fechaProgramacion3 = fechaEspañol2($fechaProgramacion2);
                                        $fechaProgramacion4 = mb_convert_case($fechaProgramacion3, MB_CASE_UPPER, "UTF-8");

                                        $turnoTG2 = mb_convert_case($turnoTG, MB_CASE_UPPER, "UTF-8");

                                        $solicitanteTG2 = mb_convert_case($solicitanteTG, MB_CASE_UPPER, "UTF-8");

                                        $estadoTG2 = mb_convert_case($estadoTG, MB_CASE_UPPER, "UTF-8");

                                        $tecnico2 = mb_convert_case($tecnico1, MB_CASE_UPPER, "UTF-8");

                                        if ($ticket == 0 || empty($ticket)) {
                                            $textoTicket = 'Sin Ticket Asociado';
                                        } else {
                                            $textoTicket = $ticket;
                                        }

                                        $textoTicket2 = mb_convert_case($textoTicket, MB_CASE_UPPER, "UTF-8");

                                        if ($tipoTrabajo == '' || empty($tipoTrabajo)) {
                                            $tipoTrabajo2 = 'Sin Tipo de Trabajo';
                                        } else {
                                            $tipoTrabajo2 = $tipoTrabajo;
                                        }

                                        $tipoTrabajo2 = mb_convert_case($tipoTrabajo2, MB_CASE_UPPER, "UTF-8");

                                        // Tabla con los datos
                                        $filas = [
                                            ['Folio OT', $folioTG2],
                                            ['Fecha Creación OT', $fechaTG4],
                                            ['Fecha Programación OT', $fechaProgramacion4],
                                            ['Turno', $turnoTG2],
                                            ['Solicitante', $solicitanteTG2],
                                            ['Estado', $estadoTG2],
                                            ['Persona Asignada', $tecnico2],
                                            ['Ticket Asociado', $textoTicket2],
                                            ['Tipo de Trabajo', $tipoTrabajo2],
                                        ];

                                        if ($idDepartamento == 1 || $idDepartamento == 13) {
                                            $filas[] = ['Cantidad de Rondas', $contarRondas];
                                            if ($formularioRondas == 1) {
                                                $filas[] = ['Formulario de Rondas', 'Consola de Alarmas'];
                                            } else if ($formularioRondas == 2) {
                                                $filas[] = ['Formulario de Rondas', 'Parametros de UMAS'];
                                            }
                                        }

                                        if ($idDepartamento !== 1 && $idDepartamento !== 13) {
                                            $filas[] = ['Cantidad de HT Realizadas', $contarHT];
                                        }


                                        // Generar las filas de la tabla
                                        foreach ($filas as $fila) {
                                            // Para el estado de credencial aplicamos los estilos condicionales
                                            echo "<tr><td style='width: 25%'><b>{$fila[0]}:</b></td><td style='width: 75%'>{$fila[1]}</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-12 d-flex">
                        <div class="card card-primary card-outline w-100 h-100">
                            <div class="card-body box-profile d-flex align-items-center justify-content-center">
                                <div class="row justify-content-center w-100">
                                    <?php if ($contarASTUCA > 0): ?>
                                        <div class="col-12 mb-3">
                                            <button type="button" class="btn bg-primary w-100" id="btnVistaAST" data-idtg="<?php echo htmlspecialchars($idTG, ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="fas fa-eye"></i> Revisar AST
                                            </button>
                                        </div>
                                        <script>
                                            document.getElementById('btnVistaAST').addEventListener('click', function () {
                                                const idTG = this.dataset.idtg; // ← accede al atributo data-idtg
                                                const form = document.createElement('form');
                                                form.method = 'POST';
                                                form.action = '../sdo-pdf/SDO-AST-UCAS.php';
                                                form.target = '_blank';

                                                const input = document.createElement('input');
                                                input.type = 'hidden';
                                                input.name = 'idTG';
                                                input.value = idTG;
                                                form.appendChild(input);

                                                document.body.appendChild(form);
                                                form.submit();
                                                document.body.removeChild(form);
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <?php

                                    ?>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 my-2">
                        <hr>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabTrabajosEncom" data-toggle="tab">Trabajos Encomendados</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabDocumentacion" data-toggle="tab">HT, OT y CheckList</a>
                                    </li>
                                </ul>
                            </div>


                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabTrabajosEncom">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Trabajos Encomendados</h5>

                                            <br>

                                            <?php

                                            if ($idDepartamento !== 1 && $idDepartamento !== 13) {
                                                $filas2 = [
                                                    ['1. Detalle del Trabajo a Realizar en BMS', $detalleTG],
                                                    ['2. Detalle del Trabajo a Realizar en CCTV', $detalleTurnoCamara],
                                                    ['3. Detalle del Trabajo a Realizar en LLE', $detalleTurnoCamara],
                                                    ['4. Detalle del Trabajo a Realizar en Control de Accesos', $detalleTurnoCA],
                                                    ['5. Detalle del Trabajo a Realizar en Rondas', $detalleTurnoRonda],
                                                    ['6. Detalle de Otros Trabajos', $detalleOtros],
                                                ];
                                            } else {
                                                $filas2 = [
                                                    ['Detalle del Trabajo a Realizar', $detalleTG],
                                                ];
                                            }

                                            ?>

                                            <div class="table-responsive">
                                                <table style="font-size: 15px;" class="table table-bordered table-sm">
                                                    <tbody>
                                                    <?php


                                                    // Generar las filas de la tabla
                                                    foreach ($filas2 as $fila2) {
                                                        if (!empty(trim($fila2[1]))) {
                                                            // Aplicamos htmlspecialchars para seguridad y nl2br para respetar los saltos de línea
                                                            $contenidoFormateado = nl2br(htmlspecialchars($fila2[1], ENT_QUOTES, 'UTF-8'));
                                                            echo "<tr><td style='width: 25%'><b>{$fila2[0]}:</b></td><td style='width: 75%'>{$contenidoFormateado}</td></tr>";
                                                        }else{
                                                            echo "<tr><td style='width: 25%'><b>{$fila2[0]}:</b></td><td style='width: 75%'>Sin Trabajos Programados.</td></tr>";
                                                        }
                                                    }
                                                    ?>

                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>

                                    <!-- TAB padre: Documentación -->
                                    <!-- TAB padre: Documentación -->
                                    <div class="tab-pane fade" id="tabDocumentacion" role="tabpanel">

                                        <?php
                                        $verificarVisadosTG = verificarVisadosTG($idTG);

                                        if ($verificarVisadosTG == 1) {
                                            ?>

                                            <div class="row text-center" style="display: flex; justify-content:center;">
                                                <div class="col-lg-3 col-md-4 col-12">

                                                    <button type="submit" id="btnVisarTodoTG" class="btn btn-success btn-block">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Visar Documentación
                                                    </button>
                                                    <input type="hidden" id="idTGVisador" value="<?php echo $idTG; ?>">
                                                    <input type="hidden" id="nomUsuarioVisador" value="<?php echo $nomUsuario; ?>">
                                                </div>
                                            </div>

                                            <hr>
                                            <?php
                                        }

                                        ?>

                                        <hr>

                                        <!-- Subtabs internas: HT / OT -->
                                        <ul class="nav nav-tabs mt-3" id="tabsInternosDocumentacion" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tab-ht-tab" data-toggle="tab" href="#tabHT" role="tab" aria-controls="tabHT" aria-selected="true">
                                                    Hojas Técnicas
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-ot-tab" data-toggle="tab" href="#tabOT" role="tab" aria-controls="tabOT" aria-selected="false">
                                                    Ordenes de Trabajo
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tab-check-tab" data-toggle="tab" href="#tabCheck" role="tab" aria-controls="tabCheck" aria-selected="false">
                                                    Check-List
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Contenido de subtabs -->
                                        <div class="tab-content pt-3" id="tabsInternosDocumentacionContent">

                                            <!-- 🟢 TAB HT -->
                                            <div class="tab-pane fade show active" id="tabHT" role="tabpanel" aria-labelledby="tab-ht-tab">
                                                <div class="table-responsive">
                                                    <table id="tableHTVisar" class="table table-bordered table-sm text-center">
                                                        <thead>
                                                        <tr class="bg-primary text-white">
                                                            <th colspan="8">Hojas Técnicas</th>
                                                        </tr>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Ticket Asociado</th>
                                                            <th>Folio HT</th>
                                                            <th>Personal HT</th>
                                                            <th>Solicitante HT</th>
                                                            <th>Fecha HT</th>
                                                            <th>Ver HT</th>
                                                            <th>Visar HT</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Ticket Asociado</th>
                                                            <th>Folio HT</th>
                                                            <th>Personal HT</th>
                                                            <th>Solicitante HT</th>
                                                            <th>Fecha HT</th>
                                                            <th>Ver HT</th>
                                                            <th>Visar HT</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- 🔵 TAB OT -->
                                            <div class="tab-pane fade" id="tabOT" role="tabpanel" aria-labelledby="tab-ot-tab">
                                                <div class="table-responsive">
                                                    <table id="tableOTVisar" class="table table-bordered table-sm text-center">
                                                        <thead>
                                                        <tr class="bg-primary text-white">
                                                            <th colspan="9">Ordenes de Trabajo</th>
                                                        </tr>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Folio OT</th>
                                                            <th>Personal OT</th>
                                                            <th>Solicitante OT</th>
                                                            <th>Fecha OT</th>
                                                            <th>Turno OT</th>
                                                            <th>Estado OT</th>
                                                            <th>Ver OT</th>
                                                            <th>Visar OT</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Folio OT</th>
                                                            <th>Personal OT</th>
                                                            <th>Solicitante OT</th>
                                                            <th>Fecha OT</th>
                                                            <th>Turno OT</th>
                                                            <th>Estado OT</th>
                                                            <th>Ver OT</th>
                                                            <th>Visar OT</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- 🔵 TAB CHECK -->
                                            <div class="tab-pane fade" id="tabCheck" role="tabpanel" aria-labelledby="tab-check-tab">
                                                <div class="table-responsive">
                                                    <table id="tableCheckVisar" class="table table-bordered table-sm text-center">
                                                        <thead>
                                                        <tr class="bg-primary text-white">
                                                            <th colspan="9">CheckList</th>
                                                        </tr>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Folio CheckList</th>
                                                            <th>Tipo CheckList</th>
                                                            <th>Equipo CheckList</th>
                                                            <th>Fecha CheckList</th>
                                                            <th>Ver CheckList</th>
                                                            <th>Visar CheckList</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                        <tr class="bg-secondary text-white">
                                                            <th>N°</th>
                                                            <th>Folio CheckList</th>
                                                            <th>Tipo CheckList</th>
                                                            <th>Equipo CheckList</th>
                                                            <th>Fecha CheckList</th>
                                                            <th>Ver CheckList</th>
                                                            <th>Visar CheckList</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
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
<script src="js/dark-mode.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });

    $(document).ready(function () {

        //FUNCIONES HOJAS TECNICAS
        $('#tableHTVisar').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "/sdo-includes/ot_terminadas_ht_visar.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                    d.folioCarga = $('#folioCarga').val();
                    d.usuarioCarga = $('#usuarioCarga').val();
                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "numOrden"},
                {"data": "ticketAsociado"},
                {"data": "folioOrden"},
                {"data": "nomTecnicoRecepcionador"},
                {"data": "nomSolicitante2"},
                {"data": "fechaCompleta"},
                {"data": "buttonDescarga"},
                {"data": "buttonVisado"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });

        $('#tableHTVisar').on('click', '.btn-visualizar-ht', function () {
            var idordenht = $(this).data('idordenht');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': '../sdo-pdf/SDO-OrdenTrabajoGeneral.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idOrden',
                'value': idordenht
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });

        $('#tableHTVisar').on('click', '.btn-visarht', function () {
            var idordenht = $(this).data('idordenht');
            var solicitante = $(this).data('solicitante');
            var folioCarga = $(this).data('folio');
            var usuarioCarga = $('#usuarioCarga').val();
            // var usuarioCarga = '1';

            // Convertir ambos a minúsculas antes de comparar
            if (solicitante.toLowerCase() === usuarioCarga.toLowerCase()) {
                // Son iguales ignorando mayúsculas
                Swal.fire({
                    title: "¿Confirmar Visado?",
                    text: "Esta opción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, Visar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/sdo-funciones/ot_terminadas_ht_visar.php",
                            type: "POST",
                            data: {
                                idorden: idordenht,
                                usuario: solicitante
                            },
                            dataType: "json",
                            success: function (resp) {
                                if (resp.status === "success") {
                                    Swal.fire("Completado", resp.msg, "success");

                                    // ✅ Actualizar DataTable sin recargar página
                                    $('#tableHTVisar').DataTable().ajax.reload(null, false);

                                } else if (resp.status === "warning") {
                                    Swal.fire("Atención", resp.msg, "warning");
                                } else {
                                    Swal.fire("Error", resp.msg, "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire("Error", "No se pudo procesar la solicitud.", "error");
                            }
                        });

                    }
                });


            } else {
                Swal.fire({
                    title: "Error",
                    text: "Visador No Coincide con Solicitante.",
                    icon: "error"
                });
            }
        });
        //FIN FUNCIONES HOJAS TECNICAS

        //FUNCIONES ORDENES DE TRABAJO
        $('#tableOTVisar').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "/sdo-includes/ot_terminadas_ot_visar.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                    d.idCarga = $('#idCarga').val();
                    d.usuarioCarga = $('#usuarioCarga').val();
                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "numOrden"},
                {"data": "folioTG2"},
                {"data": "tecnico2"},
                {"data": "solicitanteTG2"},
                {"data": "fechaCompleta"},
                {"data": "turnoTG2"},
                {"data": "estadoTG2"},
                {"data": "buttonDescarga"},
                {"data": "buttonVisado"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });

        $('#tableOTVisar').on('click', '.btn-visualizar-ot', function () {
            var idot = $(this).data('idot');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': '../sdo-pdf/SDO-Orden-Trabajo-Visor.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idTG',
                'value': idot
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });

        $('#tableOTVisar').on('click', '.btn-visarot', function () {
            var idot = $(this).data('idot');
            var solicitante = $(this).data('solicitante');
            var usuarioCarga = $('#usuarioCarga').val();
            // var usuarioCarga = '1';

            // Convertir ambos a minúsculas antes de comparar
            if (solicitante.toLowerCase() === usuarioCarga.toLowerCase()) {
                // Son iguales ignorando mayúsculas
                Swal.fire({
                    title: "¿Confirmar Visado?",
                    text: "Esta opción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, Visar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/sdo-funciones/ot_terminadas_ot_visar.php",
                            type: "POST",
                            data: {
                                idot: idot,
                                usuario: solicitante
                            },
                            dataType: "json",
                            success: function (resp) {
                                if (resp.status === "success") {
                                    Swal.fire("Completado", resp.msg, "success");

                                    // ✅ Actualizar DataTable sin recargar página
                                    $('#tableOTVisar').DataTable().ajax.reload(null, false);

                                } else if (resp.status === "warning") {
                                    Swal.fire("Atención", resp.msg, "warning");
                                } else {
                                    Swal.fire("Error", resp.msg, "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire("Error", "No se pudo procesar la solicitud.", "error");
                            }
                        });

                    }
                });


            } else {
                Swal.fire({
                    title: "Error",
                    text: "Visador No Coincide con Solicitante.",
                    icon: "error"
                });
            }
        });
        //FIN FUNCIONES ORDENES DE TRABAJO

        //FUNCIONES CHECKLIST
        $('#tableCheckVisar').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "/sdo-includes/ot_terminadas_checklist_visar.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                    d.idCarga = $('#idCarga').val();
                    d.usuarioCarga = $('#usuarioCarga').val();
                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "numOrden"},
                {"data": "folioCheck2"},
                {"data": "especialidad2"},
                {"data": "equipoCheck2"},
                {"data": "fechaCompleta"},
                {"data": "buttonDescarga"},
                {"data": "buttonVisado"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });

        $('#tableCheckVisar').on('click', '.btn-visarcheck', function () {
            var idot = $(this).data('idot');
            var idcheck = $(this).data('idcheck');
            var tipocheck = $(this).data('tipocheck');
            var visador = $(this).data('visador');
            var usuarioCarga = $('#usuarioCarga').val();
            // var usuarioCarga = '1';

            // Convertir ambos a minúsculas antes de comparar
            if (visador.toLowerCase() === usuarioCarga.toLowerCase()) {
                // Son iguales ignorando mayúsculas
                Swal.fire({
                    title: "¿Confirmar Visado?",
                    text: "Esta opción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, Visar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/sdo-funciones/ot_terminadas_checklist_visar.php",
                            type: "POST",
                            data: {
                                idot: idot,
                                idcheck: idcheck,
                                tipocheck: tipocheck,
                                usuario: visador
                            },
                            dataType: "json",
                            success: function (resp) {
                                if (resp.status === "success") {
                                    Swal.fire("Completado", resp.msg, "success");

                                    // ✅ Actualizar DataTable sin recargar página
                                    $('#tableCheckVisar').DataTable().ajax.reload(null, false);

                                } else if (resp.status === "warning") {
                                    Swal.fire("Atención", resp.msg, "warning");
                                } else {
                                    Swal.fire("Error", resp.msg, "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire("Error", "No se pudo procesar la solicitud.", "error");
                            }
                        });

                    }
                });


            } else {
                Swal.fire({
                    title: "Error",
                    text: "Visador No Coincide con Solicitante.",
                    icon: "error"
                });
            }
        });
        // FIN FUNCIONES CHECKLIST


        $("#btnVisarTodoTG").click(function(e){
            e.preventDefault();

            Swal.fire({
                title: "¿Confirmar Visado?",
                text: "Se visarán HT, OT y Checklist asociados.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, Visar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if(result.isConfirmed){

                    $.ajax({
                        url: "/sdo-funciones/tg_visar_documentacion.php",
                        type: "POST",
                        data: {
                            idTG: $("#idTGVisador").val(),
                            usuario: $("#nomUsuarioVisador").val()
                        },
                        dataType: "json",
                        success: function(resp){
                            if(resp.status === "ok"){
                                Swal.fire("Completado", resp.msg, "success");
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                Swal.fire("Error", resp.msg, "error");
                            }
                        }
                    });

                }
            });
        });
    });
</script>

</body>

</html>
