<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');

if(isset($_POST['idRecepcion']) || isset($_SESSION['idRecepcion'])){

    if(isset($_POST['idRecepcion'])){

        $idRecepcion = $_POST['idRecepcion'];

        if($idRecepcion > 0){
            $_SESSION['idRecepcion'] = $idRecepcion;
        }else{
            header('Location: listar_recepcion_documento.php?errorDetalle=true');
        }

    }else if(isset($_SESSION['idRecepcion'])){
        $idRecepcion = $_SESSION['idRecepcion'];

        if($idRecepcion > 0){
            $_SESSION['idRecepcion'] = $idRecepcion;
        }else{
            header('Location: listar_recepcion_documento.php?errorDetalle=true');
        }
    }else{
        header('Location: listar_recepcion_documento.php?errorDetalle=true');
    }

    $query = "SELECT * FROM recepcion_documento WHERE idRecepcion = $idRecepcion";
    $res = mysqli_query($conexion, $query);
    $row = $res->fetch_assoc();
    if($row){
        $folioReceo = $row['folioRecep'];
        $nomRecepcion = $row['nomRecepcion'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $unidadEmisor = $row['unidadEmisor'];
        $nomEmisor = $row['nomEmisor'];
        $fechaEmisor = $row['fechaEmisor'];
        $metodoEmisor = $row['metodoEmisor'];
        $correo = $row['correo'];
        $asignadoRecepcion = $row['asignadoRecepcion'];
        $estado = $row['estado'];
    }

}else{
    header('Location: listar_recepcion_documento.php?errorDetalle=true');
}
/*
if(isset($_POST['ruta'])){
    // Ruta del archivo en el servidor
    $rutaArchivo = $_POST['ruta'];
    // Verificar si el archivo existe
    if(file_exists($rutaArchivo)){
        // Establecer las cabeceras para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($rutaArchivo));
        readfile($rutaArchivo);
        exit;
    }else{
        echo "El archivo no existe.";
    }
}
*/
if (isset($_POST['folio'])) {
    $folioTG = $_POST['folio'];
    $idTG1 = $_POST['idTG1'];
    //$directorio = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folio2 ."/numDocumento-" . $numDocumento . "/";
    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        }else{
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folioTG ."/";
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
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detalle del Documento</title>

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
    <script>
        function handleRadioChange(radio) {
            var bms = document.getElementById('bms');
            var cctv = document.getElementById('cctv');
            var ca = document.getElementById('ca');
            var lle = document.getElementById('lle');
            var otros = document.getElementById('otros');

            if (radio.id === 'BMS') {
                bms.style.display = 'block';
                cctv.style.display = 'none';
                ca.style.display = 'none';
                lle.style.display = 'none';
                otros.style.display = 'none';

            }else if(radio.id === 'CCTV'){
                cctv.style.display = 'block';
                bms.style.display = 'none';
                ca.style.display = 'none';
                lle.style.display = 'none';
                otros.style.display = 'none';

            }else if(radio.id === 'CA'){
                cctv.style.display = 'none';
                bms.style.display = 'none';
                ca.style.display = 'block';
                lle.style.display = 'none';
                otros.style.display = 'none';

            }else if(radio.id === 'LLE'){
                cctv.style.display = 'none';
                bms.style.display = 'none';
                ca.style.display = 'none';
                lle.style.display = 'block';
                otros.style.display = 'none';
            }else if(radio.id === 'OTROS'){
                cctv.style.display = 'none';
                bms.style.display = 'none';
                ca.style.display = 'none';
                lle.style.display = 'none';
                otros.style.display = 'block';
            }
        }
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
                        <h1>Detalle del Documento  <?php echo $folioReceo;?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="listar_recepcion_documento.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <br>
                            <div class="card-body">
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n del Detalle : <?php echo $folioReceo;?>&nbsp;</legend>
                                    <h5>
                                        <b>Nombre Recepcion: </b>
                                        <?php
                                        echo $nomRecepcion;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha de Recepcion: </b>
                                        <?php
                                        $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRecepcion)));

                                        echo $registrofechaAC;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha de Emision del Documento: </b>
                                        <?php
                                        $registrofechaAC1 = date('d/m/Y', strtotime(str_replace('/', '-', $fechaEmisor)));

                                        echo $registrofechaAC1;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Unidad del Emisor: </b>
                                        <?php

                                        echo mb_convert_case($unidadEmisor,MB_CASE_UPPER,'UTF-8');;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Nombre del Emisor: </b>
                                        <?php
                                        echo mb_convert_case($nomEmisor,MB_CASE_UPPER,'UTF-8');;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Metodo de Entrega del Documento: </b>
                                        <?php
                                        echo mb_convert_case($metodoEmisor,MB_CASE_UPPER,'UTF-8');;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Email del Emisor: </b>
                                        <?php
                                        echo $correo;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Persona Asignada: </b>
                                        <?php
                                        echo $asignadoRecepcion;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Estado: </b>
                                        <?php
                                        echo $estado;
                                        ?>
                                    </h5>

                                </fieldset>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Historial de las Observaciones : <?php echo $folioReceo;?>&nbsp;</legend>
                                    <table class="table table-bordered" id="observacionTotal">
                                        <thead class="text-center bg-secondary">
                                        <tr>
                                            <th>Fecha de la Observacion</th>
                                            <th>Nombre del Creador</th>
                                            <th>Estado</th>
                                            <th>Detalle Observacion</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                        <?php
                                        $sql = "SELECT * FROM recepcion_observacion WHERE idRecepcion = $idRecepcion";
                                        $res = mysqli_query($conexion,$sql);
                                        while($row = $res->fetch_assoc()){
                                            $detalleObs = $row['detalleObservacion'];
                                            $fechaObs = $row['fechaObs'];
                                            $nomCreador = $row['nomCreador'];
                                            $estado1 = $row['estado'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $registrofechaAC2 = date('d/m/Y', strtotime(str_replace('/', '-', $fechaObs)));
                                                    echo $registrofechaAC2;
                                                    ?>

                                                </td>
                                                <td><?php echo $nomCreador?></td>
                                                <td><?php echo $estado1?></td>
                                                <td><?php echo $detalleObs?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Acciones del Documento : <?php echo $folioReceo;?>&nbsp;</legend>
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-2">
                                            <?php
                                            if($nomUsuario !== $asignadoRecepcion){
                                                ?>
                                                <button title="Solo el usuario asignado puede terminar el trabajo" disabled="disabled" type="submit" id="inputMozi" class="btn btn-block bg-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Trabajo Terminado</button>
                                                <!--<button type="button" id="inputMozi"  data-toggle="modal" data-target="#ingresarObservacionTotal"  class="btn btn-block bg-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Trabajo Terminado</button>-->
                                                <?php
                                            }else{
                                                ?>
                                                <button type="button" id="inputMozi"  data-toggle="modal" data-target="#ingresarObservacionTotal"  class="btn btn-block bg-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Trabajo Terminado</button>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                            if($nomUsuario == 'Alejandro Cantillano' || $nomUsuario == 'Isaac Perez' || $nomUsuario == 'Yorgo Casimis Subiabre' || $nomUsuario == 'Barbara Gallo'){
                                                ?>
                                                <form action="editar-documento.php" method="POST">
                                                    <input type="hidden" name="idRecepcion" id="idRecepcion" value="<?php echo $idRecepcion?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block bg-success"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Documento</button>
                                                </form>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                        <div class="col-md-2">
                                           <?php
                                                $sqlArchivo = "SELECT * FROM archivos_recepcion_documento WHERE idRecepcion = $idRecepcion";
                                                $resArchivo = mysqli_query($conexion,$sqlArchivo);
                                                while($rowArchivo = $resArchivo->fetch_assoc()){
                                                    $ruta = $rowArchivo['rutaDocumento'];
                                                    $folio = $rowArchivo['folioDocumento'];
                                                    $idTG = $rowArchivo['idArchivosDocu'];
                                                }
                                           ?>
                                            <?php
                                                if(empty($ruta) || $ruta == ''){
                                                    ?>
                                                    <form action="detalle_recepcion_documentos.php" method="POST" style="display: inline">
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folio?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                        <button type="submit" title="Descargar documento adjunto" disabled class="btn btn-primary btn-block"><i class="fas fa-file-download"></i></button>
                                                    </form>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <form action="detalle_recepcion_documentos.php" method="POST" style="display: inline">
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $ruta ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folio?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                        <button type="submit" title="Descargar documento adjunto" class="btn btn-primary btn-block"><i class="fas fa-file-download"></i></button>
                                                    </form>
                                                    <?php
                                                }
                                            ?>

                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                                if($idRecepcion == 0 || empty($idRecepcion) || $idRecepcion == '' || $estado == 'Finalizado'){
                                                    ?>
                                                    <button disabled="disabled" type="submit" id="inputMozi" class="btn btn-block bg-success"><i class="fas fa-arrow-right"></i>&nbsp;&nbsp;Cambiar Persona Asignada</button>
                                                    <?php
                                                }else if($asignadoRecepcion == $nomUsuario){
                                                    ?>
                                                    <button type="button" id="inputMozi" data-toggle="modal" data-target="#derivarPersona" class="btn btn-block bg-success"><i class="fas fa-arrow-right"></i>&nbsp;&nbsp;Cambiar Persona Asignada</button>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <button disabled type="button" id="inputMozi" data-toggle="modal" data-target="#derivarPersona" class="btn btn-block bg-success"><i class="fas fa-arrow-right"></i>&nbsp;&nbsp;Cambiar Persona Asignada</button>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <?php
                                            $contarRecepcion = contarRecepcionDocu($idRecepcion);
                                            if($contarRecepcion > 0){
                                            }else{
                                                ?>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn bg-indigo" data-toggle="modal" data-target="#mandarOt"><i class="fas fa-file"></i>&nbsp;Ingresar en una Orden de Trabajo</button>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="modal fade" id="mandarOt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-indigo">
                                            <h5 class="modal-title" id="exampleModalLabel">Ingresar en una orden de trabajo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/ingresar-ot-con-docu.php" method="POST">
                                            <input type="hidden" id="idArchivoDocumento" name="idArchivoDocumento" value="<?php echo $idTG?>">
                                            <input type="hidden" id="idRecepcion" name="idRecepcion" value="<?php echo $idRecepcion?>">
                                            <div class="modal-body">
                                                <label class="w-auto">Ingrese la Fecha de Programación del Trabajo<code>*</code></label>
                                                <input type="date" required class="form-control-border form-control" id="fechaProgramada" name="fechaProgramada">
                                                <br>
                                                <label class="w-auto">Turno<code>*</code></label>
                                                <select class="form-control form-control-border" required id="selectTurno" name="selectTurno">
                                                    <option value="" selected disabled>--Seleccione--</option>
                                                    <option value="Personal Diurno">Personal Diurno</option>
                                                    <option value="Personal Nocturno">Personal Nocturno</option>
                                                </select>
                                                <br>
                                                <label class="w-auto">Personal<code>*</code></label>
                                                <select class="form-control form-control-border" required id="selectPersonal" name="selectPersonal">
                                                    <option value="" selected disabled>--Seleccione--</option>
                                                    <?php
                                                    $queryPersonal = "SELECT jt.idJefe, jt.nomJefe AS nomEmpleado, jt.cargoJefe FROM jefes_tecnicos jt WHERE idDepartamento = '1' UNION SELECT p.idPersonal AS idJefe, p.nomPersonal AS nomEmpleado, p.cargoPersonal AS cargoJefe FROM personal p WHERE departamentoPersonal = '1'";
                                                    $resPersonal = mysqli_query($conexion,$queryPersonal);
                                                    while($rowPersonal = $resPersonal->fetch_assoc()){
                                                        ?>
                                                        <option id="idOptionTecnicoAdmin" value="<?php echo $rowPersonal['nomEmpleado'] ?>"><?php echo $rowPersonal['nomEmpleado']  ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <br>
                                                <label class="w-auto">Selecciona una opción:</label>
                                                <br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="opcion" onchange="handleRadioChange(this)" id="BMS" value="1">
                                                    <label class="form-check-label" for="opcion1">BMS</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="opcion" onchange="handleRadioChange(this)" id="CCTV" value="2">
                                                    <label class="form-check-label" for="opcion2">CCTV</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="opcion" onchange="handleRadioChange(this)" id="LLE" value="3">
                                                    <label class="form-check-label" for="opcion3">LLE</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="opcion" onchange="handleRadioChange(this)" id="CA" value="4">
                                                    <label class="form-check-label" for="opcion4">CA</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="opcion" onchange="handleRadioChange(this)" id="OTROS"value="5">
                                                    <label class="form-check-label" for="opcion5">OTROS</label>
                                                </div>
                                                <br>
                                                <br>
                                                <div id="bms" style="display: none">
                                                    <label class="w-auto">Detalle el Trabajo a Realizar en BMS</label>
                                                    <textarea rows="12" class="form-control" id="textareabms" name="textareabms"><?php echo $detalleObs?></textarea>
                                                </div>
                                                <div id="cctv" style="display: none">
                                                    <label class="w-auto">Detalle el Trabajo a Realizar en CCTV</label>
                                                    <textarea rows="12" class="form-control" id="textareacctv" name="textareacctv"><?php echo $detalleObs?></textarea>
                                                </div>
                                                <div id="lle" style="display: none">
                                                    <label class="w-auto">Detalle el Trabajo a Realizar en LLE</label>
                                                    <textarea rows="12" class="form-control" id="textarealle" name="textarealle"><?php echo $detalleObs?></textarea>
                                                </div>
                                                <div id="ca" style="display: none">
                                                    <label class="w-auto">Detalle el Trabajo a Realizar en CA</label>
                                                    <textarea rows="12" class="form-control" id="textareaca" name="textareaca"><?php echo $detalleObs?></textarea>
                                                </div>
                                                <div id="otros" style="display: none">
                                                    <label class="w-auto">Detalle el Trabajo a Realizar en OTROS</label>
                                                    <textarea rows="12" class="form-control" id="textareaotros" name="textareaotros"><?php echo $detalleObs?></textarea>
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

                            <div class="modal fade" id="ingresarObservacionTotal" name="ingresarObservacionTotal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <form method="POST" action="../sdo-funciones/ingresar-observacion-documento.php">
                                    <input type="hidden" name="idRecepcion" id="idRecepcion" value="<?php echo $idRecepcion?>">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Trabajo Terminado</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div style="text-align:left;" class="form-group">

                                                    <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                    <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                    <br>
                                                    <label for="exampleInputBorder" style="font-size: 15px">Estado: <code>*</code></label>
                                                    <select id="selectEstado" required name="selectEstado" class="form-control form-control-border">
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        <option value="Finalizado">Finalizado</option>
                                                        <option value="No Finalizado">No Finalizado</option>
                                                    </select>
                                                    <br>

                                                    <label for="exampleInputBorder" style="font-size: 15px">Detalle de la Observaci&oacute;n: <code>*</code></label>
                                                    <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" placeholder="Observaciones..." required></textarea>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal fade" id="derivarPersona" name="derivarPersona" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <form method="POST" action="../sdo-funciones/cambiar-persona-asignada.php">
                                      <input type="hidden" name="idRecepcion" id="idRecepcion" value="<?php echo $idRecepcion?>">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header bg-success">
                                                  <h5 class="modal-title" id="exampleModalLabel">Cambiar a la Persona Asignada</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <div class="modal-body">
                                                  <div style="text-align:left;" class="form-group">
                                                      <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                      <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                      <br>
                                                      <label for="exampleInputBorder" style="font-size: 15px">Nueva Persona a Asignar: <code>*</code></label>
                                                      <select id="selectPersona" required name="selectPersona" class="form-control form-control-border">
                                                          <?php
                                                          $tecnicos = array(
                                                              "Abel Torres Arancibia",
                                                              "Felipe Retamal Fuentes",
                                                              "Gonzalo Cáceres Carreño",
                                                              "Jaime Estay Jorquera",
                                                              "Jesús Silva Pellet",
                                                              "Joaquín Jorquera Gonzalez",
                                                              "Luis Pérez Bernal",
                                                              "Manuel Torres Sagredo",
                                                              "Pablo Sanchez Molina",
                                                              "Rene Condell Vargas",
                                                              "Yorgo Casimis Subiabre",
                                                              "Jorge Cantillano",
                                                              "Isaac Perez",
                                                              "Barbara Gallo",
                                                          );


                                                          sort($tecnicos);



                                                          echo "<option value='Seleccione'>Seleccione...</option>";
                                                          foreach ($tecnicos as $tecnico) {
                                                              echo "<option value=\"$tecnico\">$tecnico</option>";
                                                          }
                                                          ?>
                                                      </select>
                                                      <br>
                                                      <label for="exampleInputBorder" style="font-size: 15px">Detalle de la Observaci&oacute;n: <code>*</code></label>
                                                      <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" placeholder="Observaciones..." required></textarea>
                                                  </div>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="submit" id="guardarRecepDoc" name="guardarRecepDoc" disabled class="btn btn-primary">Agregar</button>
                                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                              </div>

                                          </div>
                                      </div>
                                  </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script src="js/dark-mode.js"></script>
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
    $(document).ready(function() {
        $('#selectPersona').change(function() {
            var selectedValue = $(this).val();
            $('#guardarRecepDoc').prop('disabled', selectedValue === 'Seleccione');
        });
    });
</script>
</body>
</html>
