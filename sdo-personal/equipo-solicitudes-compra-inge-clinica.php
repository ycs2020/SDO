<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$idDepartamento = $_SESSION['idDepartamento'];

if (isset($_POST['idEquipo'])) {
    $idEquipo = $_POST['idEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }

    $_SESSION["idEquipo"] = $idEquipo;
    $_SESSION["idCategoria"] = $idCategoria;

} else if ($_SESSION["idEquipo"]) {
    $idEquipo = $_SESSION["idEquipo"];
    $idCategoria = $_SESSION["idCategoria"];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }
}

//echo $idCategoria;
//exit;
if (isset($_POST['idEquipo'])) {
    $idGasto = $_POST['idEquipo'];
    $nombreArchivo = $_POST['nombreArchivo'];
    if ($idGasto > 0) {
        if (empty($idGasto) || $idGasto == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../archivos-equipamiento-inge-clinica-solicompra/archivos-adjuntos/nomArchivo-" . $nombreArchivo . "/";
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-personal/";
            $archivoZip = "OrdenCompra-" . $nombreArchivo . "-Gasto-" . $idGasto . ".zip";
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
} {
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #mesInforme {
            display: none;
        }
        #rangoFecha {
            display: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <?php
                                if($idDepartamento == 6){
                                    ?>
                                    <a href="equipo-inge-clinica.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="equipo.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Equipo <?php echo $nombreEquipo ?> - Solicitud de Compra </h1>
                                        <hr>
                                        <br>
                                        <button class="btn btn-sm bg-indigo" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Añadir Solicitud de Compra</button>
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-indigo">
                                                        <h5 class="modal-title" id="exampleModalLabel">Formulario - Subir Solicitud de Compra</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/guardar-soliCompra-inge-clinica.php" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                        <input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $idCategoria?>">
                                                        <div class="modal-body">
                                                            <label for="nomArchivo">Nombre del Archivo:</label>
                                                            <input type="text" required name="nomArchivo" id="nomArchivo" class="form-control">
                                                            <br>
                                                            <label for="soliCompra">Seleccione un archivo:</label>
                                                            <input type="file" required id="soliCompra" name="soliCompra" class="form-control">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Nombre Creador</th>
                                                <th scope="col" class="text-center">Nombre Archivo</th>
                                                <th scope="col" class="text-center">Descargar Solicitud de Compra</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                $sqlSoli = "SELECT * FROM solicitudes_compra_inge_clinica WHERE idEquipo = $idEquipo";

                                                $resSoli = mysqli_query($conexion,$sqlSoli);
                                                    $num = 1;
                                                    while($rowSoli = $resSoli->fetch_assoc()){
                                                        $fecha = $rowSoli['fecha'];
                                                        $nomCreador = $rowSoli['nombreCreador'];
                                                        if($nomCreador == '' || empty($nomCreador)){
                                                            $nomCreador = 'Sin Información';
                                                        }
                                                        $nomArchivo = $rowSoli['nomArchivo'];
                                                        $nomMostrar = $rowSoli['nomMostrar'];

                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $num?>
                                                            </td>
                                                            <td>
                                                                <?php echo $fecha?>
                                                            </td>
                                                            <td>
                                                                <?php echo $nomCreador?>
                                                            </td>
                                                            <td>
                                                                <?php echo $nomMostrar?>
                                                            </td>
                                                            <td>
                                                                <form action="equipo-solicitudes-compra-inge-clinica.php" method="POST">
                                                                    <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                                    <input type="hidden" value="<?php echo $nomArchivo?>" id="nombreArchivo" name="nombreArchivo">
                                                                    <button type="submit" class="btn btn-sm bg-indigo"><i class="fas fa-download"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $num++;
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Nombre Creador</th>
                                                <th scope="col" class="text-center">Nombre Archivo</th>
                                                <th scope="col" class="text-center">Descargar Solicitud de Compra</th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>

<!-- ./wrapper -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
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
</body>
</html>