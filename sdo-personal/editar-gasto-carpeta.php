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

$idGasto = $_POST['idGasto'];

if ($idGasto == '') {
    header('Location: ../' . $ruta . '/carpeta-gastos.php?noIdGasto=true');
} else {

    $sqlGasto = "SELECT * FROM gasto_equipo_carpeta WHERE idGasto = $idGasto ";
    $resGasto = mysqli_query($conexion, $sqlGasto);

    while ($rowGasto = $resGasto->fetch_assoc()) {
        $datos = [];
        $datos['fechaRegistro'] = $rowGasto['fechaRegistro'];
        $datos['fechaGasto'] = $rowGasto['fechaGasto'];
        $datos['mesGasto'] = $rowGasto['mesGasto'];
        $datos['nomMantenimiento'] = $rowGasto['nomMantenimiento'];
        $datos['idHojaTecnica'] = $rowGasto['idHojaTecnica'];
        $datos['tipoMantencion'] = $rowGasto['tipoMantencion'];
        $datos['solicitudCompra'] = $rowGasto['solicitudCompra'];
        $datos['codOrdenCompra'] = $rowGasto['codOrdenCompra'];
        $datos['costoTotal'] = $rowGasto['costoTotal'];
        $datos['encargadoMant'] = $rowGasto['encargadoMant'];

        $fechaRegistro = $datos['fechaRegistro'];
        $mesGasto = $datos['mesGasto'];
        $fechaGasto = $datos['fechaGasto'];
        $nomMantenimiento = $datos['nomMantenimiento'];
        $idHojaTecnicaEdit = $datos['idHojaTecnica'];
        $tipoMantencion = $datos['tipoMantencion'];
        $solicitudCompraEdit = $datos['solicitudCompra'];
        $codOrdenCompra = $datos['codOrdenCompra'];
        $costoTotal = $datos['costoTotal'];
        $encargadoMant = $datos['encargadoMant'];
    }
}

if (isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }

    $_SESSION['idCarpeta'] = $idCarpeta;
} else if ($_SESSION['idCarpeta']) {
    $idCarpeta = $_SESSION['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Gastos</title>
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
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
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
                            <div class="card-header bg-secondary text-dark">
                                <h1 class="card-title">Editando Gasto De La Carpeta - <?php echo $nomCategoria ?></h1>
                                <a href="carpeta-gastos.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <form action="../sdo-funciones/guardar-edicion-gasto-carpeta.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Mantenimiento:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" required id="nomMantenimiento" name="nomMantenimiento" value="<?php echo $nomMantenimiento ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hoja Técnica Asociada:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                                                </div>
                                                <select name="idHojaTecnica" id="idHojaTecnica" class="form-control form-control-sm">
                                                    <option value="" selected disabled>-- Seleccione --</option>
                                                    <?php
                                                    $sql = "SELECT * FROM ordenes_trabajos_general ORDER BY idOrden  DESC";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($rowOrden = $res->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idOrden'] = $rowOrden['idOrden'];
                                                        $datos['nomOrden'] = $rowOrden['nomOrden'];
                                                        $idOrden  = $datos['idOrden'];
                                                        $nomOrden = $datos['nomOrden'];

                                                        if ($idHojaTecnicaEdit == $idOrden) {
                                                            ?>
                                                            <option value="<?php echo $idOrden; ?>" selected><?php echo  $nomOrden ?></option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo $idOrden; ?>"><?php echo  $nomOrden ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Mantención:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="tipoMantencion" class="form-control form-control-sm" required id="tipoMantencion">
                                                    <?php
                                                    if ($tipoMantencion == 'MPE') {
                                                        ?>
                                                        <option value="MPE" selected="selected">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>


                                                        <?php
                                                    }
                                                    if ($tipoMantencion == 'MRE') {
                                                        ?>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE" selected="selected">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>


                                                        <?php
                                                    }
                                                    if ($tipoMantencion == 'MPI') {
                                                        ?>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI" selected="selected">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>


                                                        <?php
                                                    }
                                                    if ($tipoMantencion == 'MRI') {
                                                        ?>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI" selected="selected">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>


                                                        <?php
                                                    }
                                                    if ($tipoMantencion == 'OM') {
                                                        ?>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM" selected="selected">OM - Obras Menores</option>
                                                        <option value="IS">IS - Inspección/Supervisión</option>


                                                        <?php
                                                    }
                                                    if ($tipoMantencion == 'IS') {
                                                        ?>
                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                        <option value="OM">OM - Obras Menores</option>
                                                        <option value="IS" selected="selected">IS - Inspección/Supervisión</option>

                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Solicitud de Compra Asociada:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                </div>
                                                <select name="ordenCompra" id="ordenCompra" class="form-control form-control-sm">
                                                    <option value="" selected disabled>-- Seleccione --</option>
                                                    <?php
                                                    $sql = "SELECT * FROM solicitudes_compra_sdo ORDER BY idSoliCompra  DESC";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($rowSoli = $res->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idSoliCompra'] = $rowSoli['idSoliCompra'];
                                                        $datos['personalSoliCompra'] = $rowSoli['personalSoliCompra'];

                                                        $idSoliCompra = $datos['idSoliCompra'];
                                                        $personalSoliCompra = $datos['personalSoliCompra'];

                                                        if ($solicitudCompraEdit == $idSoliCompra) {
                                                            ?>
                                                            <option value="<?php echo $idSoliCompra; ?>" selected><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' .  $personalSoliCompra ?></option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo $idSoliCompra; ?>"><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' .  $personalSoliCompra ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Código Orden Compra:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                </div>
                                                <input type="text" min="1" class="form-control form-control-sm" id="codOrdenCompra" name="codOrdenCompra" value="<?php echo $codOrdenCompra ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Orden de Compra:</label><br>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="archivoOC" name="archivoOC">
                                                <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                <p class="text-muted">*Subir un nuevo archivo, elimina el anterior.*</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Total:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                </div>
                                                <input type="number" required  class="form-control form-control-sm" id="costoTotal" name="costoTotal" value="<?php echo $costoTotal ?>">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm" value="<?php echo $fechaGasto ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Encargado Mantenimiento:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="encargadoMant" name="encargadoMant" value="<?php echo $encargadoMant ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-block bg-pink "> <b> Guardar Cambios</b></button>

                                </form>
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