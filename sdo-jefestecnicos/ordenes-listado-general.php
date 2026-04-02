<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$idDepartamento = $_SESSION['idDepartamento'];

if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];

    if ($idOrden > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folio . "/";
        $rutaFinal = "../sdo-jefestecnicos/";
        $archivoZip = "Archivos-" . $folio . ".zip";

        // echo $rutaFinal.$folioSoliCompra2;

        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zipSC($dir, $zip);
            $zip->close();
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

                $descargaConfirma .= "Y";
            } else {

                $descargaConfirma .= "N";
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
    <title>Listado de Recepciones de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

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
                        <h4><b>Listado de Hojas Técnicas</b></h4>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="text-align:right;">
                                <a href="index.php" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <?php
                                    if($idDepartamento == 6){
                                        $activo = "ordenes-formulario-general-inge-clinica.php";
                                    }else{
                                        $activo = "ordenes-formulario-general.php";
                                    }
                                ?>
                                <form method="post" action="<?php echo $activo?>" style="display: inline-block;">
                                    <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Agregar Hoja Técnica</b></button>
                                </form>

                                <button class="btn bg-info show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;&nbsp;Ver Dashboard</button>
                                <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fa fa-eye-slash"></i>&nbsp;&nbsp;Ocultar Dashboard</button>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">

                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <?php


                                $tipoUsuarioVer = 'unico';
                                include_once '../sdo-includes/hojastecnicas_dashboard.php';

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <?php

                                if (isset($_GET['noEditId'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;No fue posible recuperar el ID del registro para editar.</h5></div>';
                                }

                                if (isset($_GET['ExitoActualizar'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-check"></i>&nbsp;Exito al actualizar la hoja tecnica</h5></div>';
                                }

                                if (isset($_GET['exitoEliminarHoja'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al eliminar hoja tecnica</h5></div>';
                                }

                                if (isset($_GET['exitoGenerar'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-check"></i>&nbsp;Exito la Hoja Técnica</h5></div>';
                                }

                                if (isset($_GET['exitoUpload'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al subir hoja técnica firmada</h5></div>';
                                }

                                if (isset($_GET['errorUpload'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al subir hoja técnica firmada</h5></div>';
                                }


                                if (isset($_GET['errorSeleccionarOrden'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Seleccionar HT. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }

                                ?>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <?php
                                if ($idDepartamento == 1) {
                                    include_once '../sdo-includes/encargados/listado_ht_convisado.php';
                                }else if($idDepartamento == 6){
                                    include_once '../sdo-includes/encargados/listado_ht_sinvisado_inge_clinica.php';
                                }else {
                                    include_once '../sdo-includes/encargados/listado_ht_sinvisado.php';
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
            <!-- /.row -->
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
<!-- Page specific script -->

<script src="js/dark-mode.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        // Show div by removing inline display none style rule
        $(".show-verdash").click(function () {
            $("#rowMenu3").show();
            $("#verDashAC").hide();
            $("#ocultarDashAC").show();
        });

        $(".show-ocultardash").click(function () {
            $("#rowMenu3").hide();
            $("#verDashAC").show();
            $("#ocultarDashAC").hide();
        });
    });
</script>

</body>

</html>