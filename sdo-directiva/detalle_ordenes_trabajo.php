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
        $tipoTrabajo = $row['tipoTrabajo'];

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
            $rutaFinal = "../sdo-directiva/";
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
                $("#row8").show();
                $("#row4").show();
                $("#row5").show();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
            });
            $(".show-listar-detalle").click(function() {
                $("#row8").hide();
                $("#row4").hide();
                $("#row5").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
            });
        });
    </script>


</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/directiva-sidebar.php';
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?></h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>

                                <br>
                                <br>
                                <hr>
                                <div style="text-align:center;">
                                    <button class="btn bg-yellow show-listar" id="registrarAC" name="registrarAC"><i class="fas fa-eye"></i>&nbsp;Ver Linea de Vida de la OT </button>
                                    <button class="btn bg-yellow show-listar-detalle" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Detalle de la OT</button>
                                    <?php
                                    if ($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  ") {
                                        ?>

                                        <?php
                                    }else{
                                        ?>
                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/orden-trabajo.php" target="_blank" method="post">

                                            <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">

                                            <button type="submit" style="float: right;" id="inputMozi" class="btn btn-info"><i class="fa fa-eye"></i>&nbsp; Ver OT</button>
                                            <br>
                                        </form>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                    <?php
                                    if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                        ?>

                                        <?php
                                    }else{
                                        ?>
                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="post">

                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">

                                            <button type="submit" style="float: right;" id="inputMozi" class="btn btn-success"><i class="fa fa-eye"></i>&nbsp; Ver HT</button>
                                            <br>
                                        </form>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <br>
                                <fieldset class="border p-3" id="row4" name="row4" style="display: none">
                                    <legend class="w-auto">Avances de la OT</legend>
                                    <table id="observacionTotal" class="table table-bordered table-sm">
                                        <thead style="background-color: indianred; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Estado</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Obs</th>
                                            <th style="font-size: 15px">Ver HT</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND detalleObs NOT LIKE ('%Se ha ingresado la orden de trabajo %')";
                                        $res = mysqli_query($conexion,$sql);
                                        while($row = $res->fetch_assoc()){
                                            $nomCreador = $row['nomCreador'];
                                            $detalleObs = $row['detalleObs'];
                                            $estadoAvance = $row['estadoAvance'];
                                            $fechaSubida = $row['fechaSubida'];
                                            $observacion = $row['observacion'];
                                            $folioHT = $row['folioHT'];
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
                                                <td class="text-center" >
                                                    <?php
                                                    echo $detalleObs;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if($observacion == '' || empty($observacion)){
                                                        echo 'NO APLICA';
                                                    }else{
                                                        echo  $observacion;
                                                    }
                                                    ?>
                                                </td>
                                                <td style="text-align: center">
                                                    <?php
                                                    if($folioHT == '' || empty($folioHT)){
                                                        echo 'NO APLICA';
                                                    }else{
                                                        $sqlOrden = "SELECT * FROM ordenes_trabajos_general WHERE folioOrden = '$folioHT'";
                                                        $resOrden = mysqli_query($conexion,$sqlOrden);
                                                        while ($rowOrden = $resOrden->fetch_assoc()) {
                                                            $idOrden = $rowOrden['idOrden'];
                                                        }
                                                        ?>
                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                            <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                        </form>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: indianred; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Estado</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Obs</th>
                                            <th style="font-size: 15px">Ver HT</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </fieldset>
                                <fieldset class="border p-3" id="row5" name="row5" style="display: none">
                                    <legend class="w-auto">Observaciones Generales</legend>
                                    <table id="obsercacion2" class="table table-bordered table-sm">
                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObs = "SELECT * FROM observacion_ot WHERE idTG = $idTG";
                                        $resObs = mysqli_query($conexion,$sqlObs);
                                        while($rowObs = $resObs->fetch_assoc()){
                                            $detalle = $rowObs['detalleObs'];
                                            $nomCreador1 = $rowObs['nomCreador'];
                                            $fechaObs = $rowObs['fecha'];
                                            ?>
                                            <tr class="text-center">
                                                <td><?php echo $fechaObs?></td>
                                                <td><?php echo $nomCreador1?></td>
                                                <td><?php echo $detalle?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
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
                                        $resLinea = mysqli_query($conexion,$sqlLinea);
                                        while($rowLinea = $resLinea->fetch_assoc()){
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
                                                <td><?php echo $fechaObsLinea?></td>
                                                <td><?php echo $nomCreador1Linea?></td>
                                                <td><?php echo nl2br($detalleLinea)?></td>
                                                <td>
                                                    <?php
                                                    if($respuestaPanol == 1){
                                                        echo 'ENTREGA EXITOSA';
                                                    }else if($rechazadoPanol == 1){
                                                        echo 'RECHAZADO POR PAÑOL';
                                                    }else if($isPanol == 1){
                                                        echo 'SI';
                                                    }else{
                                                        echo 'NO';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($isMaterialSoliCompra == 1){
                                                        echo 'SI';
                                                    }else{
                                                        echo 'NO';
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle; font-size: 12px" align="center">
                                                    <?php
                                                    if($respuestaPanol == 1){
                                                        ?>
                                                        <div class="row justify-content-center">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalEyecheck<?php echo $idLineaVida?>"><i class="fas fa-eye"></i></button>
                                                                <div class="modal fade" id="exampleModalEyecheck<?php echo $idLineaVida?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                $resAceptado = mysqli_query($conexion,$sqlAceptado);
                                                                                while($rowAceptado = $resAceptado->fetch_assoc()){
                                                                                    $detalleAceptado = $rowAceptado['detalle'];
                                                                                    ?>
                                                                                    <textarea readonly class="form-control" rows="12"><?php echo $detalleAceptado?></textarea>
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
                                                    }elseif($rechazadoPanol == 1){
                                                        ?>

                                                        <div class="row justify-content-center">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEye<?php echo $idLineaVida?>"><i class="fas fa-eye"></i></button>
                                                                <div class="modal fade" id="exampleModalEye<?php echo $idLineaVida?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                $resVida = mysqli_query($conexion,$sqlVida);
                                                                                while($rowVida = $resVida->fetch_assoc()){
                                                                                    $detalleVida = $rowVida['detalle'];
                                                                                    ?>
                                                                                    <textarea readonly class="form-control" rows="12" ><?php echo $detalleVida?></textarea>

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
                                                    }else{
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

                                <fieldset id="row1" name="row1" class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n de la Orden de Trabajo</legend>
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
                                    <?php
                                    if($ticket == 0 || empty($ticket)){
                                    }else{
                                        ?>
                                        <h5>
                                            <b>Ticket Asociado:</b>
                                            <?php echo $ticket?>
                                        </h5>
                                        <?php
                                    }
                                    ?>
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

                                </fieldset>
                                <br>
                                <div  name="row2" id="row2" class="row">
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
                                <fieldset name="row3" id="row3"  class="border p-3">
                                    <legend class="w-auto">&nbsp;Acciones a Realizar</legend>
                                    <div class="row justify-content-md-center">
                                        <?php
                                        if($estadoTG == 'Trabajo Terminado' || $tecnico1 != $nomUsuario){
                                        }else{
                                            ?>
                                            <?php
                                            if($idDepartamento == 1){
                                                ?>
                                                <div class="col-md-2">
                                                    <form method="POST" action="ingresar_avance_orden_control.php">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                        <button type="submit" id="inputMozi"  class="btn btn-block bg-primary"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Avance del Trabajo</button>
                                                    </form>
                                                </div>
                                                <?php
                                            }else{
                                                ?>
                                                <div class="col-md-2">
                                                    <form method="POST" action="ingresar_avance_orden.php">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                        <button type="submit" id="inputMozi"  class="btn btn-block bg-primary"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Avance del Trabajo</button>
                                                    </form>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-2">
                                            <form action="detalle_ordenes_trabajo.php" method="POST" style="display: inline">
                                                <?php
                                                $sql = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                $res = mysqli_query($conexion,$sql);
                                                while($row = $res->fetch_assoc()){
                                                    $datos = [''];
                                                    $datos['rutaArchivo'] = $row['rutaArchivo'];
                                                    $rutaArchivo = $datos['rutaArchivo'];
                                                    $idArchivo = $row['idArchivo'];

                                                }
                                                if (empty($idArchivo) || $idArchivo == 0) {
                                                    ?>
                                                    <b>No hay Archivo Adjunto Subido por el Profesional</b>
                                                    <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                    <?php
                                                }else{
                                                    ?>
                                                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                    <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                    <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG?>">
                                                    <button type="submit" class="btn  btn-block" title="Descargar el Archivo Adjunto " style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>

                                                    <?php
                                                }
                                                ?>

                                            </form>
                                        </div>
                                        <?php
                                            if($solicitanteTG != $nomUsuario){
                                            }else{
                                                ?>
                                                <div class="col-md-2">
                                                    <button class="btn bg-warning btn-block" data-toggle="modal" data-target="#derivar" type="button"><i class="fas fa-arrow-right"></i> Cambiar Persona Asignada</button>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                        if($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  "){
                                            ?>
                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                            <?php
                                        }else{
                                            ?>
                                            <?php
                                              if($solicitanteTG != $nomUsuario){
                                                  ?>
                                                  <?php
                                              }else{
                                                  ?>
                                                  <div class="col-md-2">
                                                      <form style ="margin: 0; text-align: center;" class="" action="trabajos_general_modificar.php" method="post">
                                                          <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">
                                                          <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-pen"></i> Modificar Orden de Trabajo</button>
                                                      </form>
                                                  </div>
                                                  <?php
                                              }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-block bg-danger" data-toggle="modal" data-target="#exampleModallinadevida"><i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida</button>
                                        </div>
                                        <?php
                                        if($nomUsuario == $tecnico1){
                                            ?>
                                            <div class="col-md-2">
                                                <button id="finavance" style="color: white" name="<?php echo $idTG ?>"
                                                        type="button" class="btn float:right bg-gradient-info terminartodo">Finalizar Orden
                                                    de Trabajo</button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </fieldset>
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