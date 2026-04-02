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

    $sqlGasto = "SELECT * FROM gasto_equipo_carpeta_inge_clinica WHERE id = $idGasto ";
    $resGasto = mysqli_query($conexion, $sqlGasto);

    while ($rowGasto = $resGasto->fetch_assoc()) {
        $datos = [];
        $datos['fechaRegistro'] = $rowGasto['fechaRegistro'];
        $datos['fechaGasto'] = $rowGasto['fechaGasto'];
        $datos['mesGasto'] = $rowGasto['mesGasto'];
        $datos['nombreMantenimiento'] = $rowGasto['nombreMantenimiento'];
        $datos['idHojaTecnica'] = $rowGasto['idHojaTecnica'];
        $datos['tipoIntervencion'] = $rowGasto['tipoIntervencion'];
        $datos['solicitudCompra'] = $rowGasto['solicitudCompra'];
        $datos['codigoOrdenCompra'] = $rowGasto['codigoOrdenCompra'];
        $datos['costoTotal'] = $rowGasto['costoTotal'];
        $datos['encargadoMant'] = $rowGasto['encargadoMant'];

        $fechaRegistro = $datos['fechaRegistro'];
        $mesGasto = $datos['mesGasto'];
        $fechaGasto = $datos['fechaGasto'];
        $nomMantenimiento = $datos['nombreMantenimiento'];
        $idHojaTecnicaEdit = $datos['idHojaTecnica'];
        $tipoMantencion = $datos['tipoIntervencion'];
        $solicitudCompraEdit = $datos['solicitudCompra'];
        $codOrdenCompra = $datos['codigoOrdenCompra'];
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
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-dark">
                                <h1 class="card-title">Editando Gasto Del Equipo - <?php echo $nombreEquipo ?></h1>
                                <a href="carpeta-gastos-inge-clinica.php"  style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <form action="../sdo-funciones/guardar-edicion-gasto-carpeta-inge-clinica.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                    <p class="text-muted">*Nota: Si sube un nuevo archivo (solicitud de compra, orden de compra o hoja técnica), elimina el anterior.*</p>
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
                                                    <input type="file" class="custom-file-input" id="registrosAc" name="registrosAc">
                                                    <label class="custom-file-label" for="customFileInge" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>                                                                        </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo de Intervención:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                </div>
                                                <select class="form-control" id="tipoIntervencion1" name="tipoIntervencion1">
                                                    <?php
                                                    if($tipoMantencion == 'MPPC'){
                                                        ?>
                                                        <option value=""  disabled>Seleccion...</option>
                                                        <option value="MPPC" selected>MPPC</option>
                                                        <option value="MPPA">MPPA</option>
                                                        <option value="PREDEC">PREDEC.</option>
                                                        <option value="REPARAT">REPARAT.</option>
                                                        <?php
                                                    }else if($tipoMantencion == 'MPPA'){
                                                        ?>
                                                        <option value=""  disabled>Seleccion...</option>
                                                        <option value="MPPC" >MPPC</option>
                                                        <option value="MPPA" selected>MPPA</option>
                                                        <option value="PREDEC">PREDEC.</option>
                                                        <option value="REPARAT">REPARAT.</option>
                                                        <?php
                                                    }else if($tipoMantencion == 'PREDEC'){
                                                        ?>
                                                        <option value=""  disabled>Seleccion...</option>
                                                        <option value="MPPC" >MPPC</option>
                                                        <option value="MPPA" >MPPA</option>
                                                        <option value="PREDEC" selected>PREDEC.</option>
                                                        <option value="REPARAT">REPARAT.</option>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <option value=""  disabled>Seleccion...</option>
                                                        <option value="MPPC" >MPPC</option>
                                                        <option value="MPPA" >MPPA</option>
                                                        <option value="PREDEC" >PREDEC.</option>
                                                        <option value="REPARAT"selected>REPARAT.</option>
                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="solicitudCompraAc" style="font-size:12px">Subir Solicitud de Compra Asociada:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                </div>
                                                <input type="file" class="custom-file-input" id="solicitudCompraAc" name="solicitudCompraAc">
                                                <label class="custom-file-label" for="solicitudCompraAc" style="font-size:12px;"><b>Seleccionar Archivo...</b></label>
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
            </div>
        </section>
    </div>
</div>
</body>
</html>
