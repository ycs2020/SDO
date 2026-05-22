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


if(isset($_POST['idTG']) || isset($_SESSION['idTG'])){
    if(isset($_POST['idTG'])){

        $idTG = $_POST['idTG'];

        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }else if(isset($_SESSION['idTG'])){
        $idTG = $_SESSION['idTG'];
        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()) {
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
}

if (isset($_POST['folioDocumento'])) {
    $folioDocumento = $_POST['folioDocumento'];
    $idArchivoDocu = $_POST['idArchivoDocu'];
    //$directorio = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folio2 ."/numDocumento-" . $numDocumento . "/";
    if ($idArchivoDocu > 0) {
        if (empty($idArchivoDocu) || $idArchivoDocu == '') {
        }else{
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folioDocumento ."/";
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Documentos-" . $folioDocumento . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
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


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {
            if($idTG1 > 116){

                $dir = "../sdo-archivosOT/". $folioTG ."/";
            }else{

                $dir = "../sdo-administrador/archivos-trabajo-general/folio-". $folioTG ."/carpeta-" . $idTG1 . "/";
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
}else {
}


$sqlTrabajo = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
$resTrabajo = mysqli_query($conexion,$sqlTrabajo);
while($rowTrabajo = $resTrabajo->fetch_assoc()){
    $idOrden = $rowTrabajo['idOrden'];
    $folio = $rowTrabajo['folioOrden'];

}
$contarHT = contarHT1($folioTG);
$contarBMS = contarBMS1($folioTG);
$contarCA = contarCA1($folioTG);
$contarCCTV = contarCCTV1($folioTG);
$contarRondas = contarRondas1($folioTG);
$contarLLE = contarLLE1($folioTG);
$contarOtros = contarOtros1($folioTG);
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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".show-listar").click(function() {
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
            $(".show-listar-detalle").click(function() {
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


</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                        <?php
                        if(isset($_GET['exitoObservacion'])){
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
                        if(isset($_GET['errorObservacion'])){
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
                        ?>

                    </div>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-3 col-12" id="divRegistrarAC" name="divRegistrarAC">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-yellow btn-md show-listar" id="registrarAC" name="registrarAC">
                                            <i class="fas fa-eye"></i>&nbsp;Ver Linea de Vida de la OT
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-12" id="divListarAC" name="divListarAC" style="display:none">
                                        <!-- small box -->
                                        <button class="btn bg-yellow show-listar-detalle" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Detalle de la OT</button>
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
                                <br>
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


                                <fieldset id="rowInformacion" name="rowInformacion" class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n de la Orden de Trabajo</legend>
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
                                                <?php echo $tecnico1?>
                                            </h5>
                                            <h5>
                                                <b>Ticket Asociado:</b>
                                                <?php
                                                if($ticket !== '' || !empty($ticket) || $ticket !== 0){
                                                    echo $ticket;
                                                }else{
                                                    echo 'No presenta ticket asociado';
                                                }
                                                ?>
                                            </h5>
                                            <h5>
                                                <b>Tipo de Trabajo:</b>
                                                <?php
                                                if($tipoTrabajo !== '' || !empty($tipoTrabajo)){
                                                    echo $tipoTrabajo;
                                                }else{
                                                    echo 'No presenta tipo de trabajo';
                                                }
                                                ?>
                                            </h5>
                                            <?php
                                            if($departamentos == 1){
                                                if($detalleTurnoRonda !== '' || !empty($detalleTurnoRonda)){
                                                    ?>
                                                    <h5>
                                                        <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                            <?php echo $contarRondas?></b>
                                                    </h5>
                                                    <?php
                                                }
                                                 if($formularioRondas == '' || empty($formularioRondas)){
                                                 }else{
                                                     ?>
                                                     <h5>
                                                         <b>Formulario de rondas
                                                             <?php
                                                             if($formularioRondas == 1){
                                                                 echo 'con Consola de Alarmas';
                                                             }else if($formularioRondas == 2){
                                                                 echo 'con parametros de unidades manejadoras de aire BMS';
                                                             }

                                                             ?></b>
                                                     </h5>
                                                     <?php
                                                 }
                                                if($servicio > 0){
                                                    ?>
                                                    <h5>
                                                        <b>Unidad o Servicio: <?php echo $buscarNombreServicio?></b>
                                                    </h5>
                                                    <?php
                                                }

                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-4 col-12 text-center">
                                            <?php
                                            $contarArchivos = contarArchivosDocumento($idTG);
                                            if($contarArchivos > 0){
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
                                                                $resDocu = mysqli_query($conexion,$sqlDocu);
                                                                while($rowDocu = $resDocu->fetch_assoc()){
                                                                    $folioDocumento = $rowDocu['folioDocumento'];
                                                                    $idArchivoDocu = $rowDocu['idArchivosDocu'];
                                                                    $idRecepcion = $rowDocu['idRecepcion'];

                                                                    $sql1 = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
                                                                    $res1 = mysqli_query($conexion,$sql1);
                                                                    while($row1 = $res1->fetch_assoc()){
                                                                        $idRecepcion1 = $row1['idRecepcion'];
                                                                        $unidadEmisor = $row1['unidadEmisor'];
                                                                        $nombreEmisor = $row1['nomEmisor'];
                                                                        $asignadoRecepcion = $row1['asignadoRecepcion'];
                                                                        $folioRecep = $row1['folioRecep'];
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $unidadEmisor?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $nombreEmisor?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $asignadoRecepcion?>
                                                                            </td>
                                                                            <td>
                                                                                <form action="detalle_ordenes_trabajo.php" method="POST" style="display: inline">
                                                                                    <?php
                                                                                    if(empty($folioDocumento) || $folioDocumento == ''){
                                                                                    }else{
                                                                                        ?>
                                                                                        <input type="hidden" name="folioDocumento" id="folioDocumento" value="<?php echo $folioDocumento?>">
                                                                                        <input type="hidden" name="idArchivoDocu" id="idArchivoDocu" value="<?php echo $idArchivoDocu?>">
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
                                                    <br>
                                                    <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                    <?php
                                                } else {
                                                    ?>

                                                    <form action="detalle_ordenes_trabajo.php" method="POST" style="display: inline">
                                                        <button type="submit" class="btn btn-block" title="Descargar Archivo Subido por el Profesional" style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG ?>">

                                                    </form>
                                                    <?php
                                                }
                                                ?>
                                                <br>
                                                <form style ="margin: 0; text-align: center;" class="" action="../sdo-administrador/trabajos_general_modificar.php" method="post">
                                                    <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-pen"></i> Modificar Orden de Trabajo</button>
                                                </form>
                                                <br>
                                                <button type="button" class="btn btn-block bg-info" data-toggle="modal" data-target="#exampleModallinadevida"><i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida</button>
                                                <br>
                                                <?php
                                                $contarSoliCompra = contarMaterial($idTG);
                                                //if($solicitanteTG == $nomUsuario){
                                                if($contarSoliCompra > 0){
                                                    ?>

                                                        <button type="button" style="color: white;" class="btn btn-block bg-gradient-info" data-toggle="modal" data-target="#exampleModalSoliCompra"><i class="fas fa-eye"></i> Agregar Solicitud de Compra</button>

                                                    <?php
                                                }
                                                // }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <br>
                                <div  name="rowTareas" id="rowTareas" class="row">
                                    <?php
                                    if($departamentos == 1){
                                        ?>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en BMS</legend>

                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>

                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en CCTV</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCamara; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en LLE</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoLLE; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Acceso</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCA; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Rondas</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoRonda; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle de Otros Trabajos</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleOtros" class="form-control" rows="15"><?php echo $detalleOtros; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div class="col-md-12">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                    ?>


                                </div>
                                <br>
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
                                                        <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                        <br>
                                                        <label for="exampleInputBorder" style="font-size: 15px">Nueva Persona a Asignar: <code>*</code></label>
                                                        <select id="selectPersona" required name="selectPersona" class="form-control form-control-border">
                                                            <?php
                                                            $sqlPerso = "SELECT * FROM personal WHERE departamentoPersonal = $departamentos";
                                                            $resPerso = mysqli_query($conexion,$sqlPerso);
                                                            ?>
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            <?php
                                                            while($rowPerso = $resPerso->fetch_assoc()){
                                                                $nomPersonal = $rowPerso['nomPersonal'];
                                                                ?>
                                                                <option value="<?php echo $nomPersonal?>"><?php echo $nomPersonal?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <br>
                                                        <label for="exampleInputBorder" style="font-size: 15px">Justifique el cambio. <code>*</code></label>
                                                        <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" required></textarea>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                                                    <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
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
                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                                    <label class="w-auto">Nombre Creador:</label>
                                                    <input type="text" readonly class="form-control" id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario?>">
                                                    <br>
                                                    <?php
                                                    if($tecnico1 == $nomUsuario){
                                                        ?>
                                                        <label class="w-auto">¿Se necesitan materiales por Solicitud de Compra?</label>
                                                        <select id="material" name="material" class="form-control" onchange="visible(this.value)">
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            <option value="1">Si</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <br>
                                                        <div id="obs" style="display: none">
                                                            <label class="w-auto">Ingresar Observacion:</label>
                                                            <textarea class="form-control" name="detalleObs" rows="12" id="detalleObs"></textarea>
                                                        </div>
                                                        <div id="materiales" style="display: none">
                                                            <label class="w-auto">Ingrese los materiales:</label>
                                                            <textarea class="form-control" name="detalleObs" rows="12" id="detalleObs"></textarea>
                                                        </div>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div id="obs1">
                                                            <label class="w-auto">Ingresar Observacion:</label>
                                                            <textarea class="form-control" name="detalleObs" rows="12" id="detalleObs"></textarea>
                                                        </div>
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
                                                <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                                <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario?>">
                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre de Usuario: <code>*</code></label>
                                                        <input type="text" readonly="readonly" class="form-control " id="nomCreador" name="nomCreador" placeholder="Nombre de Personal" value="<?php echo $nomUsuario?>" required>
                                                    </div>
                                                    <div style="text-align:left;" class="form-group" >
                                                        <label for="exampleInputBorder">Describa la observacion: <code>*</code></label>
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
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
        $('.terminartodo').click(function(e) {
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
                        success: function(response) {
                            console.log('finalizando.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'OT finalizada con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
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

