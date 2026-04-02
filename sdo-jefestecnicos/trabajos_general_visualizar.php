<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];





if (isset($_POST['idTG'])) {

    $idTG = trim($_POST['idTG']);

    if ($idTG > 0) {

        $_SESSION['idTG'] = $idTG;

        $dataTG = array();
        $dataTG = getTG($idTG);

        $idTG2 = $dataTG['idTG'];

        $folioTG = $dataTG['folioTG'];

        $solicitanteTG = $dataTG['solicitanteTG'];

        $detalleTG = $dataTG['detalleTG'];

        $fechaTG = $dataTG['fechaTG'];

        $fechaTG2 = strtotime($fechaTG);

        $fechaTG3 = fechaEspañol2($fechaTG2);

        $estadoTG = $dataTG['estadoTG'];

        $turnoTG = $dataTG['turnoTG'];

        $tecnico1 = $dataTG['tecnico1'];

        $tecnico2 = $dataTG['tecnico2'];

        $tecnicoReemplazo1 = $dataTG['tecnicoReemplazo1'];

        $tecnicoReemplazo2 = $dataTG['tecnicoReemplazo2'];

        $observaciones = $dataTG['observaciones'];

        $observacionTGT = $dataTG['observacionTGT'];

        $tipoTecnico = $dataTG['tipoTecnico'];

        $fechaSubidaTecnico = $dataTG['fechaSubidaTecnico'];

        $fechaSubidaTecnico2 = strtotime($fechaSubidaTecnico);

        $fechaSubidaTecnico3 = fechaEspañol2($fechaSubidaTecnico2);

        $avanceTGT = $dataTG['avanceTGT'];

        $observacionTGT = $dataTG['observacionTGT'];

        /*
         * $fechaTG2 = strtotime($fechaTG);

        $fechaTG3 = fechaEspañol2($fechaTG2);*/




        /*$datos['tipoTecnico'] = $row['tipoTecnico'];
        $datos['fechaSubidaTecnico'] = $row['fechaSubidaTecnico'];
        $datos['avanceTGT'] = $row['avanceTGT'];*/


        /*USUARIOS TRABAJOS GENERALES*/
        $dataUTG = array();

        $dataUTG = getUsuariosTG($idTG);

        $idUTG = $dataUTG['idUTG'];
        $nomUTG = $dataUTG['nomUTG'];
        $cargoUTF = $dataUTG['cargoUTF'];


    }else{

        header('Location: trabajos_general_listado.php?errorVisualizar=true');
    }

} else if (isset($_SESSION['idTG'])) {

    $idTG = trim($_SESSION['idTG']);

    if ($idTG > 0) {

        $dataTG = array();
        $dataTG = getTG($idTG);

        $idTG2 = $dataTG['idTG'];

        $folioTG = $dataTG['folioTG'];

        $solicitanteTG = $dataTG['solicitanteTG'];

        $detalleTG = $dataTG['detalleTG'];

        $fechaTG = $dataTG['fechaTG'];

        $fechaTG2 = strtotime($fechaTG);

        $fechaTG3 = fechaEspañol2($fechaTG2);

        $estadoTG = $dataTG['estadoTG'];

        $turnoTG = $dataTG['turnoTG'];

        $tecnico1 = $dataTG['tecnico1'];

        $tecnico2 = $dataTG['tecnico2'];

        $tecnicoReemplazo1 = $dataTG['tecnicoReemplazo1'];

        $tecnicoReemplazo2 = $dataTG['tecnicoReemplazo2'];

        $observaciones = $dataTG['observaciones'];

        $observacionTGT = $dataTG['observacionTGT'];

        $tipoTecnico = $dataTG['tipoTecnico'];

        $fechaSubidaTecnico = $dataTG['fechaSubidaTecnico'];

        $fechaSubidaTecnico2 = strtotime($fechaSubidaTecnico);

        $fechaSubidaTecnico3 = fechaEspañol2($fechaSubidaTecnico2);

        $avanceTGT = $dataTG['avanceTGT'];

        $observacionTGT = $dataTG['observacionTGT'];


        /*USUARIOS TRABAJOS GENERALES*/
        $dataUTG = array();

        $dataUTG = getUsuariosTG($idTG);

        $idUTG = $dataUTG['idUTG'];
        $nomUTG = $dataUTG['nomUTG'];
        $cargoUTF = $dataUTG['cargoUTF'];
    } else {

        header('Location: trabajos_general_listado.php?errorVisualizar=true');
    }
} else {

    header('Location: trabajos_general_listado.php?errorVisualizar=true');
}

$sql3 = "SELECT * FROM personal WHERE idPersonal = $tecnico1";
$res3 = mysqli_query($conexion,$sql3);
while($row3 = $res3->fetch_assoc()){
    $tecnicoResponsable = $row3['nomPersonal'];

}

/*if(isset($_POST['ruta'])){
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
} */


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    echo $idTG1;
    $folio = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-administrador/archivos-trabajo-general/folio-". $folio ."/carpeta-" . $idTG1 . "/";
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Trabajo-General-" . $folio . "-Carpeta-" . $idTG1 . ".zip";
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
}else {
}

if(isset($_POST['idTG2'])){
    $idTG2 = $_POST['idTG2'];
    $folio1 = $_POST['folio1'];
    if ($idTG2 > 0) {
        if (empty($idTG2) || $idTG2 == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-administrador/archivos-trabajo-general-tecnicos/carpeta-". $idTG ."/tecnico-" . $tecnicoResponsable . "/";
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-jefestecnicos/";
            $archivoZip = "Trabajo-General-" . $folio1 . "-Carpeta-" . $idTG2 . ".zip";
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
}else{

}



date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizaci&oacute;n de Trabajos</title>

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

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }

            /* Cambiar el color de los enlaces de navegación */
        .nav-link {
            color: #ffffff; /* Cambia #FF0000 al color que desees */
            text-decoration: none; /* Esto elimina el subrayado predeterminado de los enlaces */
        }

        /* Cambiar el color del enlace cuando se pasa el mouse por encima */
        .nav-link:hover {
            color: #000000; /* Cambia #00FF00 al color que desees para el estado de "hover" */
        }
    </style>

    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
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


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Visualizaci&oacute;n de Trabajo</h4>

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


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header p-0 pt-1 bg-gradient-green">
                                    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                            <li class="nav-item " role="presentation">
                                                <button class="nav-link active"  id="control-tab" aria-current="page"  data-toggle="tab" data-target="#revision" type="button" role="tab" aria-controls="revision" aria-selected="true"><i class="fas fa-user-tie"></i>&nbsp;&nbsp;Datos subido por el profesional</button>
                                            </li>

                                            <li class="nav-item " >
                                                <button class="nav-link"  id="incendio-tab" data-toggle="tab" data-target="#avance" type="button" role="tab" aria-controls="avance" aria-selected="false"><i class="fas fa-user"></i>&nbsp;&nbsp;Revisar la Orden de Trabajo del Técnico</button>
                                            </li>
                                    </ul>
                                </div>
                                <br>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active"  id="revision" role="tabpanel" aria-labelledby="control-tab">
                                        <div class="row">
                                            <div class="col-12 col-lg-3 col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless text-justify">

                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Folio Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $folioTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Fecha Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $fechaTG3; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Turno Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $turnoTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Personal Solicitante:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $solicitanteTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Estado :</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
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
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>
                                            <h6 style="text-align: justify;">
                                                <form action="trabajos_general_visualizar.php" method="POST" style="display: inline">
                                                    <?php
                                                    $sql = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                    $res = mysqli_query($conexion,$sql);
                                                    while($row = $res->fetch_assoc()){
                                                        $datos = [''];
                                                        $datos['rutaArchivo'] = $row['rutaArchivo'];
                                                        $rutaArchivo = $datos['rutaArchivo'];
                                                        $nomArchivo = $datos['nomArchivo'];
                                                        $idArchivo = $row['idArchivo'];
                                                        $trabajoTerminado = $row['trabajoTerminado'];
                                                        $trabajoNoTerminado = $row['trabajoObservacion'];
                                                    }
                                                    if (empty($idArchivo) || $idArchivo == '' || $idArchivo = 0) {
                                                        ?>
                                                        <b>&nbsp&nbspNo hay Archivo Adjunto Subido por el Profesional</b>
                                                        <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                        <?php
                                                    }else{
                                                        ?>

                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG?>">
                                                        <b>&nbsp;&nbsp;Descargar Archivo Adjunto Subido por el Profesional:</b>
                                                        <button type="submit" class="btn btn-lg" style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>

                                                        <?php
                                                    }
                                                    ?>
                                                </form>
                                            </h6>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div style="font-size:85%" class="col-md-12">

                                                <h6 style="text-align:justify; vertical-align: middle">
                                                    <b>&nbsp;&nbsp;Detalle Solicitud: </b>
                                                    <br><br>
                                                    <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="12" placeholder="Detalle del Problema que Presenta..."><?php echo $detalleTG; ?></textarea>

                                                </h6>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="tab-pane fade"  id="avance" role="tabpanel" aria-labelledby="control-tab">
                                        <div class="row">
                                            <div class="col-12 col-lg-3 col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless text-justify">
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Folio Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $folioTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Fecha OT Técnico:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php
                                                               echo $fechaSubidaTecnico3;

                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Técnico:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php
                                                                    $sql1 = "SELECT * FROM personal WHERE idPersonal = $tecnico1";
                                                                    $res1 = mysqli_query($conexion,$sql1);
                                                                    while($row1 = $res1->fetch_assoc()){
                                                                        $nomPersonal = $row1['nomPersonal'];
                                                                    }

                                                                echo $nomPersonal;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Personal Solicitante:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $solicitanteTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Estado:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $estadoTG; ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </div>
                                            <h6 style="text-align: justify;">
                                                <form action="trabajos_general_visualizar.php" method="POST" style="display: inline">
                                                    <?php
                                                    $sql2 = "SELECT * FROM Archivos_Trabajo_General_Tecnico WHERE idTG = $idTG";
                                                  // echo $sql;
                                                    $res2 = mysqli_query($conexion,$sql2);
                                                    while($row2 = $res2->fetch_assoc()){
                                                        $datos2 = [''];
                                                        $datos2['rutaArchivo'] = $row2['rutaArchivo'];
                                                        $rutaArchivo = $datos2['rutaArchivo'];
                                                        $nomArchivo = $datos2['nomArchivo'];
                                                        $idArchivo1 = $row2['idArchivo'];
                                                        $trabajoTerminado1 = $row2['trabajoTerminado'];
                                                        $trabajoNoTerminado1 = $row2['trabajoObservacion'];
                                                        $idTGArchivo = $row2['idTG'];

                                                    }
                                                    if ($trabajoTerminado1 == 1 || $trabajoNoTerminado1 == 1) {
                                                        ?>
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG2" id="idTG2" value="<?php echo $idTG?>">
                                                        <input type="hidden" name="folio1" id="folio1" value="<?php echo $folioTG?>">

                                                        <b>&nbsp;&nbsp;Descargar Archivo Adjunto Subido por el Técnico:</b>
                                                        <button type="submit" class="btn btn-lg" style="background-color: #e08b0b; color: white"><i class="fas fa-download"></i></button>

                                                        <br>
                                                        <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <?php
                                                              //  echo $idTGArchivo;
                                                              //  echo $idTG;
                                                            ?>

                                                        <b>&nbsp&nbspNo hay Archivo Adjunto Subido por el Tecnico</b>
                                                        <?php
                                                    }
                                                    ?>
                                                </form>
                                            </h6>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div style="font-size:85%" class="col-md-12">
                                                <h6 style="text-align:justify; vertical-align: middle">
                                                    <b>&nbsp;&nbsp;Avance del Trabajo: </b>
                                                    <br><br>
                                                    <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="12" placeholder="No presenta avance..."><?php echo $avanceTGT; ?></textarea>

                                                </h6>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div style="font-size:85%" class="col-md-12">
                                                <h6 style="text-align:justify; vertical-align: middle">
                                                    <b>&nbsp;&nbsp;Observaciones: </b>
                                                    <br><br>
                                                    <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="12" placeholder="No presenta observacion..."><?php echo $observacionTGT; ?></textarea>

                                                </h6>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div style="font-size:85%" class="col-md-12">
                                        <h6 style="text-align: justify; vertical-align: middle">
                                            <b></b>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

            </div>
            <!-- /.timeline -->

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
<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>


<script type="text/javascript">
    $("input[name='opciones']").change(function () {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>
</body>

</html>