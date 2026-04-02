<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idOrden'])) {
    $idOrden = trim($_POST['idOrden']);

    if ($idOrden == 0 || empty($idOrden)) {
        header('Location: ordenes-subir-firmas.php?errorCargaTicket=true');
    } else {
        $datosTicket = array();
        $datosTicket = getOrdenTrabajoGeneral($idOrden);

        $idOrden2 = $datosTicket['idOrden'];

        $folioOrden = $datosTicket['folioOrden'];
        $nomOrden = $datosTicket['nomOrden'];
        $fechaOrden = $datosTicket['fechaOrden'];
        $nomSolicitante = $datosTicket['nomSolicitante'];
        $nomTecnicoRecepcionador = $datosTicket['nomTecnicoRecepcionador'];
        $idDepartamento = $datosTicket['idDepartamento'];
        $nomDepartamento = $datosTicket['nomDepartamento'];
        $idSubDepartamento = $datosTicket['idSubDepartamento'];
        $nomSubDepartamento = $datosTicket['nomSubDepartamento'];
        $servicioOrden = $datosTicket['servicioOrden'];
        $recintoOrden = $datosTicket['recintoOrden'];
        $pmaOrden = $datosTicket['pmaOrden'];
        $coordenadasOrden = $datosTicket['coordenadasOrden'];
        $tipoMantencion = $datosTicket['tipoMantencion'];
        $trabajoRealizado = $datosTicket['trabajoRealizado'];
        $problemaPresenta = $datosTicket['problemaPresenta'];
        $accionesRealiza = $datosTicket['accionesRealiza'];
        $comentariosTrabajos = $datosTicket['comentariosTrabajos'];
        $recepcionistaOrden = $datosTicket['recepcionistaOrden'];
        $ejecutorOrden = $datosTicket['ejecutorOrden'];
        $autorizadorOrden = $datosTicket['autorizadorOrden'];

        $nomSolicitante = mb_convert_case($nomSolicitante,MB_CASE_TITLE,'UTF-8');

        $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador,MB_CASE_TITLE,'UTF-8');

        $nomServicioOrden = getNomServicio($servicioOrden);

        $nomServicioOrden = mb_convert_case($nomServicioOrden,MB_CASE_TITLE,'UTF-8');

        $fechaCreacion = strtotime($fechaOrden);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaOrden));

        $_SESSION['idOrden'] = $idOrden;
    }
} else if (isset($_SESSION['idOrden'])) {
    $idOrden = trim($_SESSION['idOrden']);

    if ($idOrden == 0 || empty($idOrden)) {
        header('Location: ordenes-subir-firmas.php?errorCargaTicket=true');
    } else {
        $datosTicket = array();
        $datosTicket = getOrdenTrabajoGeneral($idOrden);

        $idOrden2 = $datosTicket['idOrden'];

        $folioOrden = $datosTicket['folioOrden'];
        $nomOrden = $datosTicket['nomOrden'];
        $fechaOrden = $datosTicket['fechaOrden'];
        $nomSolicitante = $datosTicket['nomSolicitante'];
        $nomTecnicoRecepcionador = $datosTicket['nomTecnicoRecepcionador'];
        $idDepartamento = $datosTicket['idDepartamento'];
        $nomDepartamento = $datosTicket['nomDepartamento'];
        $idSubDepartamento = $datosTicket['idSubDepartamento'];
        $nomSubDepartamento = $datosTicket['nomSubDepartamento'];
        $servicioOrden = $datosTicket['servicioOrden'];
        $recintoOrden = $datosTicket['recintoOrden'];
        $pmaOrden = $datosTicket['pmaOrden'];
        $coordenadasOrden = $datosTicket['coordenadasOrden'];
        $tipoMantencion = $datosTicket['tipoMantencion'];
        $trabajoRealizado = $datosTicket['trabajoRealizado'];
        $problemaPresenta = $datosTicket['problemaPresenta'];
        $accionesRealiza = $datosTicket['accionesRealiza'];
        $comentariosTrabajos = $datosTicket['comentariosTrabajos'];
        $recepcionistaOrden = $datosTicket['recepcionistaOrden'];
        $ejecutorOrden = $datosTicket['ejecutorOrden'];
        $autorizadorOrden = $datosTicket['autorizadorOrden'];

        $nomSolicitante = mb_convert_case($nomSolicitante,MB_CASE_TITLE,'UTF-8');

        $nomTecnicoRecepcionador = mb_convert_case($nomTecnicoRecepcionador,MB_CASE_TITLE,'UTF-8');

        $nomServicioOrden = getNomServicio($servicioOrden);

        $nomServicioOrden = mb_convert_case($nomServicioOrden,MB_CASE_TITLE,'UTF-8');


        $fechaCreacion = strtotime($fechaOrden);
        $fechaCreacion = fechaEspañol2($fechaCreacion);

        $horaCreacion = date('H:i', strtotime($fechaOrden));

        $_SESSION['idOrden'] = $idOrden;
    }
} else {
    header('Location: ordenes-subir-firmas.php?errorCargaTicket=true');
}

if (isset($_POST['idOrdenArchivos'])) {

    $idOrdenArchivos = $_POST['idOrdenArchivos'];
    $folioArchivos = $_POST['folioArchivos'];

    if ($idOrdenArchivos > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folioArchivos;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-admtec/";
        $archivoZip = "Archivos-" . $folioArchivos . ".zip";

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
            }
        }
    }
}



if (isset($_POST['rutaArchivoHT'],$_POST['folioFirmadaHT'])) {
    $file = $_POST['rutaArchivoHT'];
    $folioFirmadaHT = $_POST['folioFirmadaHT'];
    if (is_file($file)) {
        $filename = "Hoja Tecnica ".$folioFirmadaHT.".pdf"; // el nombre con el que se descargará, puede ser diferente al original
        /*header("Content-Type: application/octet-stream");*/
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($file);
    } else {
        die("Error: no se encontró el archivo '$file'");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando Hoja T&eacute;cnica</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">

</head>

<body class="hold-transition sidebar-mini ">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Visualizando Hoja T&eacute;cnica</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

        if(isset($_GET['exitoUpload'])){
            ?>
            <script>
                Swal.fire({
                    title: 'HT Firmada con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
            <?php
        }

        if(isset($_GET['errorUpload'])){
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Subir HT Firmada. Reintente.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }

        if(isset($_GET['exitoEliminarHoja'])){
            ?>
            <script>
                Swal.fire({
                    title: 'HT Eliminada con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
            <?php
        }

        if(isset($_GET['errorEliminarHoja'])){
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Eliminar HT. Reintente.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }

        if(isset($_GET['ExitoActualizar'])){
            ?>
            <script>
                Swal.fire({
                    title: 'HT Modificada con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
            <?php
        }

        if(isset($_GET['errorActualizar'])){
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Actualizar HT. Reintente.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }

        if(isset($_GET['noEditId'])){
            ?>
            <script>
                Swal.fire({
                    title: 'Error al Recuperar ID de Hoja T&eacute;cnica. Reintente.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="ordenes-subir-firmas.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>


                            </div>
                            <div style="font-size:85%" class="card-body">
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-9">

                                        <h5><b>Visualizando Hoja T&eacute;cnica:
                                            </b><?php echo $folioOrden; ?></h5>
                                        <h5><b>Fecha Hoja T&eacute;cnica:
                                            </b><?php echo $fechaCreacion; ?></h5>
                                        <h5><b>Solicitante Hoja T&eacute;cnica:
                                            </b><?php echo $nomSolicitante; ?></h5>
                                        <h5><b>Personal Recepcionador:
                                            </b><?php echo $nomTecnicoRecepcionador; ?></h5>
                                        <h5><b>Servicio Hoja T&eacute;cnica:
                                            </b><?php echo $nomServicioOrden; ?></h5>

                                        <br>
                                    </div>


                                </div>
                                <br>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <form style=" display: inline" target="_blank" class="" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">

                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                            <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-primary btn-lg"><i class="fa fa-eye"></i><br>Visualizar Hoja T&eacute;cnica</button>

                                        </form>

                                    </div>
                                    <h5>&nbsp;</h5>
                                    <div class="col-lg-2 col-12">
                                        <?php

                                        $sqlImagenHT = "SELECT * FROM archivos_hoja_firmada WHERE idHoja = $idOrden";
                                        $resImagenHT = mysqli_query($conexion, $sqlImagenHT);

                                        while ($rowImagenHT = $resImagenHT->fetch_assoc()) {
                                            $datos = [];
                                            $datos['rutaArchivoHT'] = $rowImagenHT['rutaArchivoHT'];
                                            $rutaArchivoHT = $datos['rutaArchivoHT'];
                                        }

                                        if ($resImagenHT->num_rows > 0) {
                                            ?>
                                            <form action="ordenes-menu-admin.php" method="POST" style="display:inline">
                                                <input type="hidden" value="<?php echo $rutaArchivoHT ?>" name="rutaArchivoHT">
                                                <input type="hidden" name="idHojaFirmadaHT" id="idHojaFirmadaHT" value="<?php echo $idOrden ?>">
                                                <input type="hidden" name="folioFirmadaHT" id="folioFirmadaHT" value="<?php echo $folioOrden ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-danger btn-lg"><i class="fas fa-download"></i><br>Descargar HT Firmada</button>
                                            </form>

                                            <?php
                                        } else {
                                            ?>
                                            <a id="<?php echo $idOrden; ?>" class="btn btn-block bg-gradient-green btn-lg abrirmodal" data-toggle="modal" data-target="#exampleModal" for="upload"><i class="fas fa-upload"></i><br>Subir HT Firmada</a>
                                            <?php
                                        }

                                        ?>


                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="exampleModalLabel">Subir Hoja Firmada</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Alerta de éxito -->
                                                        <div class="alert alert-success alert-dismissible fade" id="successAlert" role="alert">
                                                            <b>Se adjunto un archivo correctamente! Por favor haz click en el botón verde Subir.</b>
                                                        </div>

                                                        <p class="text-bold text-purple"><b> **Adjunte la hoja técnica firmada para respaldarla**</b></p>
                                                        <p class="text-bold text-danger"><b> **Formato PDF**</b></p>

                                                        <?php

                                                        $idHojaFirmada = $_GET['idHoja'];

                                                        ?>

                                                        <form action="../sdo-funciones/subirHojaFirmada.php" method="POST" enctype="multipart/form-data" style="display: inline;">
                                                            <input type="hidden" name="idHoja" id="idmodal" value="<?php echo $idHojaFirmada ?>">
                                                            <input type="file" onchange="showSuccessAlert()" required accept="application/pdf" name="imagenHoja" id="upload" class="ocultar ">
                                                            <label for="upload" class="btn btn-block btn-warning"> <i class="fas fa-file-contract"></i> Adjuntar Imagen <i class="fas fa-file-contract"></i></label>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Subir</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <style>
                                            .ocultar {
                                                display: none;
                                            }
                                        </style>
                                    </div>

                                    <h5>&nbsp;</h5>
                                    <div class="col-lg-2 col-12">

                                        <?php
                                        $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden ";
                                        $resArchivo = mysqli_query($conexion, $sqlArchivo);

                                        while ($rowArchivo = $resArchivo->fetch_assoc()) {
                                            $datos = [];
                                            $datos['cantidad'] = $rowArchivo['cantidad'];
                                            $cantidad = $datos['cantidad'];
                                        }

                                        if ($cantidad > 0) {
                                            ?>
                                            <form style="margin: 0; text-align: center;" class="" action="ordenes-menu-admin.php" method="post">
                                                <input type="hidden" name="idOrdenArchivos" value="<?php echo $idOrden; ?>">
                                                <input type="hidden" name="folioArchivos" value="<?php echo $folioOrden; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-info btn-lg"> <i class="fas fa-download"></i><br>Archivos Adjuntos</button>
                                            </form>
                                            <?php
                                        } else {
                                            ?>
                                            <button type="submit" id="inputMozi" disabled class="btn btn-block bg-gradient-info btn-lg"> <i class="fas fa-ban"></i><br>Sin Archivos Adjuntos</button>
                                            <?php
                                        }

                                        ?>


                                    </div>

                                    <h5>&nbsp;</h5>
                                    <div class="col-lg-2 col-12">
                                        <form style="margin: 0; text-align: center;" class="" action="ordenes-modificar-general.php" method="post">

                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                            <button type="submit" id="inputMozi" class="btn btn-block bg-gradient-yellow btn-lg"><i class="fa fa-pen"></i><br>Modificar HT</button>

                                        </form>

                                    </div>

                                    <h5>&nbsp;</h5>
                                    <div class="col-lg-2 col-12">
                                        <form action="../sdo-funciones/ordenes-eliminar-general.php" method="POST">
                                            <input type="hidden" value="<?php echo $idOrden ?>" name="idOrden">
                                            <button type="submit" class="btn btn-block bg-gradient-danger btn-lg "><i class="fa fa-trash"></i><br>Eliminar HT</button>
                                        </form>

                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:justify;">

                                <fieldset class="border p-3">
                                    <legend class="w-auto"><b>&nbsp;&nbsp;Problema que Presenta&nbsp;&nbsp;</b></legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <div class="input-group mb-3">
                                                <br>
                                                <h5><?php echo $problemaPresenta; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <hr>

                                <fieldset class="border p-3">
                                    <legend class="w-auto"><b>&nbsp;&nbsp;Trabajo Realizado&nbsp;&nbsp;</b></legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <div class="input-group mb-3">
                                                <br>
                                                <h5><?php echo $trabajoRealizado; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

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
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->

<script src="js/dark-mode.js"></script>
<script>
    $("a.abrirmodal").click(function() {
        //Capturamos el valor del id para enviarlo al modal
        let id = $(this).attr('id');
        //Pasamos el id al campo input hiddien del modal.
        $("input#idmodal").val(id);
    });
</script>
<script>
    // Función para mostrar la alerta de éxito
    function showSuccessAlert() {
        var fileInput = document.getElementById('upload');
        if (fileInput.files.length > 0) {
            document.getElementById('successAlert').classList.add('show');
        }
    }

    $("#exampleModal").on("hidden.bs.modal", function() {
        // Aquí va el código a disparar en el evento
        document.getElementById('successAlert').classList.remove('show');
        document.getElementById('upload').value = '';
    });

</script>

</body>

</html>