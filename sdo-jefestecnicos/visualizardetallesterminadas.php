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
        $departamento = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $idTareaProgramada = $row['idTareaProgramada'];


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

if(isset($_POST['folioTGDescargar'])){
    $folioDescargar = $_POST['folioTGDescargar'];
    $dir = "../sdo-archivo-ot-asignado/$folioDescargar".'/';
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

$sqlTrabajo = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
$resTrabajo = mysqli_query($conexion,$sqlTrabajo);
while($rowTrabajo = $resTrabajo->fetch_assoc()){
    $idOrden = $rowTrabajo['idOrden'];
    $folio = $rowTrabajo['folioOrden'];


}
/*
$contarHT = contarHT1($folioTG);
$contarBMS = contarBMS1($folioTG);
$contarCA = contarCA1($folioTG);
$contarCCTV = contarCCTV1($folioTG);
$contarRondas = contarRondas1($folioTG);
$contarLLE = contarLLE1($folioTG);
$contarOtros = contarOtros1($folioTG);
*/
$contarchecklistpantallasgraficas = contarchecklistpantallasgraficas($idTG);
$contarchecklistlogicaprogram = contarchecklistlogicaprogram($idTG);
$contarchecklistucas = contarchecklistucas($idTG);
$contarchecklistcontroladores = contarchecklistcontroladores($idTG);
$contarchecklisttccuma = contarchecklisttccuma($idTG);
$contarchecklistvav = contarchecklistvav($idTG);
$contarchecklistvf = contarchecklistvf($idTG);
$contarchecklistuma = contarchecklistuma($idTG);


$contarRondas = contarRonda($idTG);
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
                $("#row6").hide();
                $("#row5").show();
                $("#row4").show();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#row7").hide();
                $("#row8").hide();
                $("#row9").show();
                $("#checklist").hide();

            });
            $(".show-listar-detalle").click(function() {
                $("#row6").show();
                $("#row5").hide();
                $("#row4").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#row7").hide();
                $("#row8").hide();
                $("#row9").hide();
                $("#checklist").hide();

            });

            $(".show-visar").click(function(){
                $("#checklistLogica").show();
                $("#checklist").show();
                $("#row8").show();
                $("#row7").show();
                $("#row6").hide();
                $("#row5").hide();
                $("#row4").hide();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").show();
                $("#visar").hide();
                $("#desvisar").show();
                $("#row9").hide();
                $("#checklistucas").show();
                $("#checklistcontroladores").show();
                $("#checklisttccuma").show();
                $("#checklistvav").show();
                $("#checklistvf").show();
                $("#checklistuma").show();

            });
            $(".show-detalles").click(function(){
                $("#checklist").hide();
                $("#checklistLogica").hide();
                $("#row8").hide();
                $("#row6").show();
                $("#row5").hide();
                $("#row4").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#row7").hide();
                $("#visar").show();
                $("#desvisar").hide();
                $("#row9").hide();
                $("#checklistucas").hide();
                $("#checklistcontroladores").hide();
                $("#checklisttccuma").hide();
                $("#checklistvav").hide();
                $("#checklistvf").hide();
                $("#checklistuma").hide();
            });
        });
    </script>


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
                        if(isset($_GET['exitoVisar'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Exito  al visar la ht'
                                })
                            </script>
                            <?php
                        }
                        if($_GET['exitoVisarCheckList']){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Exito  al visar el check list'
                                })
                            </script>
                            <?php
                        }
                        if(isset($_GET['errorVisarJefes'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al visar la ht'
                                })
                            </script>
                            <?php
                        }
                        if(isset($_GET[' exitoVisarOT'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Exito  al visar la ot'
                                })
                            </script>
                            <?php
                        }
                        if(isset($_GET['errorVisarJefeOT'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al visar la ot'
                                })
                            </script>
                            <?php
                        }
                        if(isset($_GET['errorVisar'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al visar la ht.Por favor reintente'
                                })
                            </script>
                            <?php
                        }
                        if(isset($_GET['errorVisarOT'])){
                            ?>
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al visar la ot.Por favor reintente'
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
                                    <a href="trabajos_general_listado_terminados.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>

                                <br>
                                <br>
                                <hr>
                                <div style="text-align:center;">
                                    <button class="btn bg-yellow show-listar" id="registrarAC" name="registrarAC"><i class="fas fa-eye"></i>&nbsp;Ver Linea de Vida de la OT </button>
                                    <button class="btn bg-yellow show-listar-detalle" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Detalle de la OT</button>
                                    <button type="button" class="btn bg-info" data-toggle="modal" data-target="#exampleModallinadevida"><i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida</button>
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
                                                    <input type="hidden" name="terminada" id="terminada" value="1">
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
                                    <?php
                                    if(/*$solicitanteTG == $nomUsuario*/ $idDepartamento == 1){
                                        ?>
                                        <button class="btn btn-success show-visar" style="float: right;" id="visar"><i class="fas fa-check"></i>&nbsp;Visar HT,OT y CheckList</button>
                                        <button class="btn btn-success show-detalles" style="float: right;display:none;" id="desvisar"><i class="fas fa-check"></i>&nbsp;Ver detalles</button>
                                        <?php
                                    }else{
                                        if($idDepartamento == 1){
                                            ?>
                                            <form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">
                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                <input type="submit" style="float:right;" id="inputMozi" class="btn  btn-info" value="Ver Hoja Técnica">
                                            </form>
                                            <br>
                                            <br>
                                            <?php
                                        }
                                        ?>
                                        <form action="../sdo-pdf/orden-trabajo.php" target="_blank" method="POST">
                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                            <input type="submit" id="inputMozi" style="float: right;" class="btn  btn-info" value="Ver Orden de Trabajo">
                                        </form>
                                        <br>
                                            <br>
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
                                    <?php
                                    if($tipoTrabajo == '' || empty($tipoTrabajo)){
                                    }else{
                                        ?>
                                        <h5>
                                            <b>Tipo de Trabajo:</b>
                                            <?php echo $tipoTrabajo?>
                                        </h5>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                        if($idDepartamento == 1){
                                            ?>
                                            <h5>
                                                <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                    <?php echo $contarRondas?></b>
                                            </h5>
                                            <?php
                                        }
                                    ?>

                                    <?php
                                    if($departamento == 1){
                                        ?>
                                        <?php
                                    }else{
                                        ?>
                                        <h5><b>Cantidad de Hojas Técnicas que lleva esta Orden de Trabajo es: <?php echo $contarHT?></b></h5>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                        if($departamento == 1){
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
                                    ?>

                                </fieldset>
                                <fieldset class="border p-3" id="row7" name="row7" style="display: none;">
                                    <legend class="w-auto">Tabla de HT</legend>
                                    <table  class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Ticket Asociado</center>
                                            </th>
                                            <th>
                                                <center>Folio<center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador<center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante<center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>

                                            <th>
                                                <center>Ver HT<center>
                                            </th>
                                            <th>
                                                <center>Visar HT<center>
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
                                                <td>
                                                    <center>
                                                        <?php
                                                            $sqlVisado = "SELECT isVisado FROM ordenes_trabajos_general WHERE idOrden = $idOrden";
                                                            $resVisado = mysqli_query($conexion,$sqlVisado);
                                                            while($rowVisado = $resVisado->fetch_assoc()){
                                                                $isVisado = $rowVisado['isVisado'];
                                                                if($isVisado == 1){
                                                                    ?>
                                                                    <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisar<?php echo $idOrden?>">
                                                                        Visar Esta HT
                                                                    </button>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisar<?php echo $idOrden?>">
                                                                        Visar Esta HT
                                                                    </button>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                        <div class="modal fade" id="exampleModalVisar<?php echo $idOrden?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-gradient-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Visar la HT</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/visar-ht.php" method="POST">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="idOrden" id="idOrden" value="<?php echo $idOrden?>">
                                                                            <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                            <input type="password" class="form-control" id="contra" name="contra">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                <center>Folio<center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador<center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante<center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>
                                            <th>
                                                <center>Ver HT<center>
                                            </th>
                                            <th>
                                                <center>Visar HT<center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row8" name="row8" style="display: none;">
                                    <legend class="w-auto">Tabla de OT</legend>
                                    <table id="tablaot" class="table table-bordered table-hover">
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
                                            <th style="vertical-align:middle;">
                                                <center>Visar OT</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado' AND departamento = $idDepartamento AND folioTG = '$folioTG' ORDER BY fechaTG DESC";

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

                                            if($estadoTG == 'Trabajo terminado con exito'){
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            }else{
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR?>">
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
                                                    if(empty($tecnico)){
                                                        echo "No registra funconario";
                                                    }else{
                                                        echo $tecnico;
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($estadoTG)) {
                                                        echo "No registra Estado";
                                                    } else {
                                                        if($estadoTG == 'trabajoTerminado'){
                                                            $estado = "Trabajo Terminado";
                                                            echo $estado;
                                                        }else{
                                                            echo $estadoTG;
                                                        }

                                                    } ?>

                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/orden-trabajo.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                        <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Orden de Trabajo">
                                                    </form>
                                                </td>

                                                    <?php
                                                        $sqlVisadoOT = "SELECT isVisado FROM trabajos_general WHERE idTG = $idTG";
                                                        $resVisadoOT = mysqli_query($conexion,$sqlVisadoOT);
                                                        while($rowVisadoOT = $resVisadoOT->fetch_assoc()){
                                                            $isVisadoOT = $rowVisadoOT['isVisado'];
                                                            if($isVisadoOT == 1){
                                                                ?>
                                                                <td>
                                                                <center>
                                                                    <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarOT">
                                                                        Visar Esta OT
                                                                    </button>
                                                                </center>
                                                                </td>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <td>
                                                                <center>
                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarOT">
                                                                        Visar Esta OT
                                                                    </button>
                                                                </center>
                                                                </td>
                                                                <?php
                                                            }
                                                        }
                                                    ?>



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
                                            <th style="vertical-align:middle;">
                                                <center>Visar OT</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <?php
                                    if($contarchecklistpantallasgraficas > 0){
                                        ?>
                                        <br>
                                        <fieldset class="border p-3" id="checklist" name="checklist" style="display: none;">
                                            <legend class="w-auto">Tabla de Check List - Pantalla Gráfica</legend>
                                            <table id="checklistpantallas" class="table table-bordered table-sm text-center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Nombre Pantalla</th>
                                                    <th style="font-size: 15px">Especialidad</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlCheckListPantallas = "SELECT * FROM datos_checklist WHERE idTG = $idTG";
                                                $resCheckListPantallas = mysqli_query($conexion,$sqlCheckListPantallas);
                                                $count = 1;
                                                while($rowCheckListPantallas = $resCheckListPantallas->fetch_assoc()){
                                                    $idCheck = $rowCheckListPantallas['id'];
                                                    $fechaCheckPantallas = $rowCheckListPantallas['fecha'];
                                                    $pantallas = $rowCheckListPantallas['pantalla'];
                                                    $especialidad = $rowCheckListPantallas['especialidad'];
                                                    $isVisadoCheck = $rowCheckListPantallas['isVisado'];
                                                    $folioCheck = $rowCheckListPantallas['folio'];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $count?>
                                                        </td>
                                                        <td>
                                                            <?php echo date('d/m/Y', strtotime($fechaCheckPantallas)); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $folioCheck?>
                                                        </td>
                                                        <td>
                                                            <?php echo $pantallas?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $especialidad;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-PantallasGraficas.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idCheck?>">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>
                                                            <?php
                                                            if($isVisadoCheck == 1){
                                                            ?>
                                                                <td>
                                                                    <center>
                                                                        <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListPantallas">
                                                                            Visar Esta Check List
                                                                        </button>
                                                                    </center>
                                                                </td>
                                                        <?php
                                                        }else{
                                                            ?>
                                                                <td>
                                                                    <center>
                                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListPantallas<?php echo $idCheck?>">
                                                                            Visar Esta Check List
                                                                        </button>
                                                                        <div class="modal fade" id="exampleModalVisarCheckListPantallas<?php echo $idCheck?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-gradient-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Visar el Check List</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <form action="../sdo-funciones/visar-check-list-pantallas.php" method="POST">
                                                                                        <div class="modal-body">
                                                                                            <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idCheck?>">
                                                                                            <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                            <input type="password" class="form-control" id="contra" name="contra">
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </center>
                                                                </td>
                                                            <?php
                                                        }
                                                            ?>
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
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Nombre Pantalla</th>
                                                    <th style="font-size: 15px">Especialidad</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($contarchecklistucas > 0){
                                        ?>
                                        <br>
                                        <fieldset class="border p-3" id="checklistucas" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - Ucas</legend>
                                            <table id="checklistucas" class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">UCA</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $countUCAS = 1;
                                                $sqlUcas = "SELECT * FROM datos_checklist_ucas WHERE idTG = $idTG ORDER BY uca ASC";
                                                $resUcas = mysqli_query($conexion,$sqlUcas);
                                                while($rowUcas = $resUcas->fetch_assoc()){
                                                    $fechaUca = $rowUcas['fecha'];
                                                    $uca = $rowUcas['uca'];
                                                    $idCheckUCA = $rowUcas['id'];
                                                    $isVisadoUCA = $rowUcas['isVisado'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $countUCAS?></td>
                                                        <td><?php echo $fechaUca?></td>
                                                        <td><?php echo $uca?></td>
                                                        <td>
                                                            <form target="_blank" action="../sdo-pdf/SDO-CheckList-Ucas.php" method="POST">
                                                                <input type="hidden" name="idCheck" value="<?php echo $idCheckUCA?>">
                                                                <input type="hidden" name="idTG" value="<?php echo $idTG?>">
                                                                <button type="submit" class="btn btn-sm bg-success"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>
                                                        <?php
                                                            if($isVisadoUCA == 1){
                                                        ?>
                                                            <td>
                                                                <center>
                                                                    <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListPantallas">
                                                                        Visar Esta Check List
                                                                    </button>
                                                                </center>
                                                            </td>
                                                        <?php
                                                            }else{
                                                            ?>
                                                            <td>
                                                                <center>
                                                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListUCAS<?php echo $idCheckUCA?>">
                                                                       Visar Esta Check List
                                                                   </button>
                                                                    <div class="modal fade" id="exampleModalVisarCheckListUCAS<?php echo $idCheckUCA?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-gradient-primary">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Visar el Check List </h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <form action="../sdo-funciones/visar-check-list-ucas.php" method="POST">
                                                                                    <div class="modal-body">
                                                                                        <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaProgramada?>">
                                                                                        <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idCheckUCA?>">
                                                                                        <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                        <input type="password" class="form-control" id="contra" name="contra">
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>
                                                            <?php
                                                            }
                                                        ?>
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
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($contarchecklistlogicaprogram > 0){
                                        ?>
                                        <br>
                                        <fieldset class="border p-3" id="checklistLogica" name="checklistLogica" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - Logica de Programacion</legend>
                                            <table id="checklistlogica" class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">EC-BOS</th>
                                                    <th style="font-size: 15px">Controlador</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlCheckListLogicas = "SELECT * FROM `datos_checklist_logica` WHERE idTG = $idTG";
                                                $resCheckListLogicas = mysqli_query($conexion,$sqlCheckListLogicas);
                                                $count = 1;
                                                while($rowCheckListLogicas = $resCheckListLogicas->fetch_assoc()){
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
                                                    $isVisadoCheckLogica = $rowCheckListLogicas['isVisado'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count?></td>
                                                        <td><?php echo $rowCheckListLogicas['fecha']?></td>
                                                        <td><?php echo $rowCheckListLogicas['ecbos']?></td>
                                                        <td><?php echo $rowCheckListLogicas['controlador']?></td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-Logica-Programacion.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idCheckLogica?>">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>
                                                        <?php
                                                            if($isVisadoCheckLogica > 0){
                                                                ?>
                                                                <td>
                                                                    <center>
                                                                        <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListPantallas">
                                                                            Visar Esta Check List
                                                                        </button>
                                                                    </center>
                                                                </td>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <td>
                                                                    <center>
                                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListLogica<?php echo $idCheckLogica?>">
                                                                            Visar Esta Check List
                                                                        </button>
                                                                        <div class="modal fade" id="exampleModalVisarCheckListLogica<?php echo $idCheckLogica?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-gradient-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - Logica Programacion</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <form action="../sdo-funciones/visar-check-list-logica.php" method="POST">
                                                                                        <div class="modal-body">
                                                                                            <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idCheckLogica?>">
                                                                                            <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                            <input type="password" class="form-control" id="contra" name="contra">
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </center>
                                                                </td>
                                                                <?php
                                                            }
                                                        ?>
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
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($contarchecklistcontroladores > 0){
                                        ?>
                                        <br>
                                        <fieldset id="checklistcontroladores" name="checklistcontroladores" class="border p-3" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - Controlador Modelo ECB</legend>
                                            <table class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">EC-BOS</th>
                                                    <th style="font-size: 15px">Controlador</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sqlControlador = "SELECT * FROM `datos_checklist_controlador_ecb` WHERE idTG = $idTG";
                                                        $resControlador = mysqli_query($conexion,$sqlControlador);
                                                        $count = 1;
                                                        while($rowControlador = $resControlador->fetch_assoc()){
                                                            $idControlador = $rowControlador['id'];
                                                            $fechaControlador = $rowControlador['fecha'];
                                                            $fecha_formato = date('d-m-Y H:i',strtotime($fechaControlador));
                                                            $ecbosControlador = $rowControlador['ecbos'];
                                                            $controladorECB = $rowControlador['controlador'];
                                                            $isVisadoControlador = $rowControlador['isVisado'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $count?></td>
                                                                <td><?php echo $fecha_formato?></td>
                                                                <td><?php echo $ecbosControlador?></td>
                                                                <td><?php echo $controladorECB?></td>
                                                                <td>
                                                                    <form action="../sdo-pdf/SDO-CheckList-Cotrolador-ECB.php" target="_blank" method="POST">
                                                                        <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idControlador?>">
                                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                        <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                                    </form>
                                                                </td>
                                                                <?php
                                                                    if($isVisadoControlador > 0){
                                                                        ?>
                                                                        <td>
                                                                            <center>
                                                                                <button type="button" disabled class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListPantallas">
                                                                                    Visar Esta Check List
                                                                                </button>
                                                                            </center>
                                                                        </td>
                                                                        <?php
                                                                    }else{
                                                                        ?>
                                                                        <td>
                                                                            <center>
                                                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListLogica<?php echo $idControlador?>">
                                                                                    Visar Esta Check List
                                                                                </button>
                                                                                <div class="modal fade" id="exampleModalVisarCheckListLogica<?php echo $idControlador?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-gradient-primary">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - Controlador Modelo ECB</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <form action="../sdo-funciones/visar-check-controlador-ecb.php" method="POST">
                                                                                                <div class="modal-body">
                                                                                                    <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idControlador?>">
                                                                                                    <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                                    <input type="password" class="form-control" id="contra" name="contra">
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </center>
                                                                        </td>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                        $count++;
                                                    ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">EC-BOS</th>
                                                    <th style="font-size: 15px">Controlador</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($contarchecklisttccuma > 0){
                                        ?>
                                        <br>
                                        <fieldset id="checklisttccuma" name="checklisttccuma" class="border p-3" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - TCC De UMA</legend>
                                            <table class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sqlTCCUMA = "SELECT * FROM datos_tcc_uma WHERE idTG = $idTG";
                                                        $resTCCUMA = mysqli_query($conexion,$sqlTCCUMA);
                                                        $contarTccUma = 1;
                                                        while($rowTCCUMA = $resTCCUMA->fetch_assoc()){
                                                            $idTCCUMA = $rowTCCUMA['id'];
                                                            $fechaIngresoTCCUMA = $rowTCCUMA['fecha'];
                                                            $folioTCCUMA = $rowTCCUMA['folio'];
                                                            $isVisadoTCCUMA = $rowTCCUMA['isVisado'];
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $contarTccUma?></td>
                                                                    <td><?php echo $fechaIngresoTCCUMA?></td>
                                                                    <td><?php echo $folioTCCUMA?></td>
                                                                    <td>
                                                                        <form action="../sdo-pdf/SDO-CheckList-TCC-UMA.php" target="_blank" method="POST">
                                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                            <input type="hidden" id="idTCCUMA" name="idTCCUMA" value="<?php echo $idTCCUMA?>">
                                                                            <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                                        </form>
                                                                    </td>
                                                                    <td>
                                                                        <center>
                                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListTCCUMA<?php echo $idTCCUMA?>">
                                                                                Visar Esta Check List
                                                                            </button>
                                                                            <div class="modal fade" id="exampleModalVisarCheckListTCCUMA<?php echo $idTCCUMA?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-gradient-primary">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - TCC de UMA - <?php echo $folioTCCUMA?></h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <form action="../sdo-funciones/visar-check-tcc-uma.php" method="POST">
                                                                                            <div class="modal-body">
                                                                                                <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idTCCUMA?>">
                                                                                                <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                                <input type="password" class="form-control" id="contra" name="contra">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </center>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            $contarTccUma++;
                                                        }
                                                    ?>
                                                </tbody>
                                                <tfoot class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($contarchecklistvav > 0){
                                        ?>
                                        <br>
                                        <fieldset id="checklistvav" name="checklistvav" class="border p-3" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - VAV</legend>
                                            <table class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $sqlCheckListVav = "SELECT * FROM datos_vav WHERE idTG = $idTG";
                                                        $resCheckListVav = mysqli_query($conexion,$sqlCheckListVav);
                                                        $contarVav = 1;
                                                        while($rowCheckListVav = $resCheckListVav->fetch_assoc()){
                                                            $fechaVav = $rowCheckListVav['fecha'];
                                                            $folioVav = $rowCheckListVav['folio'];
                                                            $idVav = $rowCheckListVav['idVav'];
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $contarVav?></td>
                                                                <td><?php echo $fechaVav?></td>
                                                                <td><?php echo $folioVav?></td>
                                                                <td>
                                                                    <form action="../sdo-pdf/SDO-CheckList-VAV.php" target="_blank" method="POST">
                                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                        <input type="hidden" id="idVAV" name="idVAV" value="<?php echo $idVav?>">
                                                                        <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <center>
                                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListVav<?php echo $idVav?>">
                                                                            Visar Esta Check List
                                                                        </button>
                                                                        <div class="modal fade" id="exampleModalVisarCheckListVav<?php echo $idVav?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-gradient-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - VAV - <?php echo $folioVav?></h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <form action="../sdo-funciones/visar-check-vav.php" method="POST">
                                                                                        <div class="modal-body">
                                                                                            <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaProgramada?>">
                                                                                            <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idVav?>">
                                                                                            <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                            <input type="password" class="form-control" id="contra" name="contra">
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </center>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $contarVav++;
                                                        }
                                                    ?>
                                                </tbody>
                                                <tfoot class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>

                                <?php
                                if($contarchecklistvf > 0){
                                    ?>
                                    <br>
                                    <fieldset id="checklistvf" name="checklistvf" class="border p-3" style="display: none">
                                        <legend class="w-auto">Tabla de Check List - VF</legend>
                                        <table class="table table-bordered table-sm" style="text-align: center">
                                            <thead class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">Folio</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                                <th style="font-size: 15px">Visar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlCheckListVF = "SELECT * FROM datos_vf WHERE idTG = $idTG";
                                            $resCheckListVF = mysqli_query($conexion,$sqlCheckListVF);
                                            $contarVF = 1;
                                            while($rowCheckListVF = $resCheckListVF->fetch_assoc()){
                                                $fechaVF = $rowCheckListVF['fecha'];
                                                $folioVF = $rowCheckListVF['folio'];
                                                $idVF = $rowCheckListVF['id'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $contarVF?></td>
                                                    <td><?php echo $fechaVF?></td>
                                                    <td><?php echo $folioVF?></td>
                                                    <td>
                                                        <form action="../sdo-pdf/SDO-CheckList-VF.php" target="_blank" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                            <input type="hidden" id="idVF" name="idVF" value="<?php echo $idVF?>">
                                                            <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListVF<?php echo $idVF?>">
                                                                Visar Esta Check List
                                                            </button>
                                                            <div class="modal fade" id="exampleModalVisarCheckListVF<?php echo $idVF?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-gradient-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - VF - <?php echo $folioVF?></h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/visar-check-vf.php" method="POST">
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaProgramada?>">
                                                                                <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idVF?>">
                                                                                <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                <input type="password" class="form-control" id="contra" name="contra">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    </td>
                                                </tr>
                                                <?php
                                                $contarVF++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">Folio</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                                <th style="font-size: 15px">Visar</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>
                                    <?php
                                }
                                ?>

                                <?php
                                    if($contarchecklistuma > 0){
                                        ?>
                                        <br>
                                        <fieldset id="checklistuma" name="checklistuma" class="border p-3" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - UMA</legend>
                                            <table class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                    <th style="font-size: 15px">Visar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlCheckListUMA = "SELECT * FROM datos_uma WHERE idTG = $idTG";
                                                $resCheckListUMA = mysqli_query($conexion,$sqlCheckListUMA);
                                                $contarUMA = 1;
                                                while($rowCheckListUMA = $resCheckListUMA->fetch_assoc()){
                                                    $fechaUMA = $rowCheckListUMA['fecha'];
                                                    $folioUMA = $rowCheckListUMA['folio'];
                                                    $idUMA = $rowCheckListUMA['idUma'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $contarUMA?></td>
                                                        <td><?php echo $fechaUMA?></td>
                                                        <td><?php echo $folioUMA?></td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-UMA.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                <input type="hidden" id="idUMA" name="idUMA" value="<?php echo $idUMA?>">
                                                                <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalVisarCheckListUMA<?php echo $idVF?>">
                                                                    Visar Esta Check List
                                                                </button>
                                                                <div class="modal fade" id="exampleModalVisarCheckListUMA<?php echo $idUMA?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-gradient-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Visar el Check List - UMA - <?php echo $folioUMA?></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form action="../sdo-funciones/visar-check-uma.php" method="POST">
                                                                                <div class="modal-body">
                                                                                    <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaProgramada?>">
                                                                                    <input type="hidden" name="idCheck" id="idCheck" value="<?php echo $idUMA?>">
                                                                                    <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                                                    <input type="password" class="form-control" id="contra" name="contra">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $contarUMA++;
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>

                                <br>
                                <fieldset class="border p-3" id="row9" name="row9" style="display: none">
                                    <legend class="w-auto">Linea de Vida de la OT</legend>
                                    <table class="table table-bordered table-sm">
                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Detalle</th>
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
                                            ?>
                                            <tr class="text-center">
                                                <td><?php echo $fechaObsLinea?></td>
                                                <td><?php echo $nomCreador1Linea?></td>
                                                <td><?php echo $detalleLinea?></td>
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
                                <?php
                                if($departamento == 1){
                                    ?>
                                    <div  name="row2" id="row2" class="row">
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
                                    </div>
                                    <br>
                                    <?php
                                }else{
                                    ?>
                                    <div id="row6">
                                        <div class="col-md-12">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>

                                <fieldset name="row3" id="row3"  class="border p-3">
                                    <legend class="w-auto">&nbsp;Acciones a Realizar</legend>
                                    <div class="row justify-content-md-center">
                                        <?php
                                        if($estadoTG == 'Trabajo Terminado' || $tecnico1 != $nomUsuario){
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
                                        <div class="col-md-2">
                                            <form action="visualizardetallesterminadas.php" method="POST" style="display: inline">
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
                                        $buscarArchivostecnico = buscararchivostecnicos($idTG);
                                        if($buscarArchivostecnico > 0){
                                            ?>
                                            <br>
                                            <div class="col-md-4">
                                                <form action="visualizardetallesterminadas.php" method="POST">
                                                    <input type="hidden" id="folioTGDescargar" name="folioTGDescargar" value="<?php echo $folioTG?>">
                                                    <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-download"></i> Descargar Archivos Subidos por el Funcionario Asignado</button>
                                                </form>
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
                                                            $sqlPerso = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento";
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
                                <!--<div class="modal fade" id="exampleModalVisar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-gradient-primary">
                                                <h5 class="modal-title" id="exampleModalLabel">Visar la HT</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/visar-ht.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="text" name="idOrden" id="idOrden" value="<?php echo $idOrden?>">
                                                    <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                    <input type="password" class="form-control" id="contra" name="contra">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="modal fade" id="exampleModalVisarOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-gradient-primary">
                                                <h5 class="modal-title" id="exampleModalLabel">Visar la OT</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/visar-ot.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                                    <label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>
                                                    <input type="password" class="form-control" id="contra" name="contra">
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
</body>
</html>

