<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda"){
    $ruta = "sdo-mesa-ayuda";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set('America/Santiago');
// echo '';
$direccion = $_POST['direccion'];

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

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sqlGeneral = "SELECT * FROM  archivos_equipamiento_hbqp WHERE id  = $id";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomArchivo'] = $row['nomArchivo'];
        $datos['tipoArchivo'] = $row['tipoArchivo'];
        $datos['rutaArchivo'] = $row['rutaArchivo'];

        $nomArchivo = $datos['nomArchivo'];
        $tipoArchivo = $datos['tipoArchivo'];
        $rutaArchivo = $datos['rutaArchivo'];
    }

    $nomCarpeta = $_POST['nomCarpeta'];

    $_SESSION['id'] = $id;
} else if ($_SESSION['id']) {
    $id = $_SESSION['id'];
    $sqlGeneral = "SELECT * FROM  archivos_equipamiento_hbqp WHERE id  = $id";

    $nomCarpeta = $_POST['nomCarpeta'];
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomArchivo'] = $row['nomArchivo'];
        $datos['tipoArchivo'] = $row['tipoArchivo'];
        $datos['rutaArchivo'] = $row['rutaArchivo'];


        $nomArchivo = $datos['nomArchivo'];
        $tipoArchivo = $datos['tipoArchivo'];
        $rutaArchivo = $datos['rutaArchivo'];
    }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Archivos</title>
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

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarNomDepartamento();

            $('#tipoArchivo').change(function() {
                recargarNomDepartamento();
            });

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-archivos/habilitarIngresoArchivo.php",
                    data: "id=" + $('#tipoArchivo').val(),
                    success: function(r) {
                        $('#visualizarNomTipo').html(r);
                    }
                });
            };
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';

    if (isset($_GET['exitoEditar'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se creo subio correctamente el archivo adjunto!',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorSubirArchivos'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Ha ocurrido un problema al subir el archivo!',
                'error'
            )
        </script>
        <?php
    }



    ?>
    <!-- /.navbar -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <a href="carpeta-archivos.php" style="float: right;" class="btn btn-danger btn-sm"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Editando Registro Archivos Adjuntos </h1>
                                        <hr>
                                        <br>

                                        <form method="POST" action="../sdo-funciones/guardar-editar-archivo-carpeta.php" enctype="multipart/form-data">

                                            <input type="hidden" value="<?php echo $id ?>" name="id">
                                            <input type="hidden" value="<?php echo $direccion ?>" name="direccion">
                                            <input type="hidden" value="<?php echo $nomCarpeta ?>" name="nomCarpeta">
                                            <input type="hidden" value="<?php echo $rutaArchivo ?>" name="rutaArchivo">

                                            <div class="row">
                                                <div class="form-group col-md-12 col-12">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" value="<?php echo $nomArchivo ?>" required id="nomArchivo" name="nomArchivo">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12 col-12">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        </div>
                                                        <select  class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                            <?php
                                                            $sql = "SELECT * FROM  tiposArchivos";
                                                            $res = mysqli_query($conexion, $sql);
                                                            while($row = $res->fetch_assoc()){
                                                                $datos = [];
                                                                $datos['id '] = $row['id'];
                                                                $datos['nomTipo'] = $row['nomTipo'];
                                                                $id  = $datos['id'];
                                                                $nomTipo = $datos['nomTipo'];

                                                                if($nomTipo == $tipoArchivo){
                                                                    ?>
                                                                    <option selected value="<?php echo $nomTipo ?>"><?php echo mb_convert_case( $nomTipo, MB_CASE_UPPER, 'UTF-8')  ?></option>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case( $nomTipo, MB_CASE_UPPER, 'UTF-8')  ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <?php
                                                            }
                                                            ?>
                                                            <option value="Otro">Otro</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                </div>

                                                <div class="form-group col-md-12 col-12">
                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <input type="file" class="form-control form-control-sm"  id="archivoAdjunto" name="archivoAdjunto">
                                                    </div>
                                                </div>

                                                <p class="text-muted  text-bold">*Subir un nuevo archivo, reemplaza el anterior.</p>

                                                <button type="submit" class="btn btn-block btn-warning"> <b>Guardar Cambios</b> </button>
                                        </form>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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