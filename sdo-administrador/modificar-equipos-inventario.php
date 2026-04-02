<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['selectInventario']) || isset($_SESSION['selectInventario'])){

    if($_POST['selectInventario']){
        $selectInventario = $_POST['selectInventario'];

        if($selectInventario > 0) {
            $_SESSION['selectInventario'] = $selectInventario;
        }else{
            header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
        }
    }else if($_SESSION['selectInventario']){
        $selectInventario = $_SESSION['selectInventario'];

        if($selectInventario > 0) {
            $_SESSION['selectInventario'] = $selectInventario;
        }else{
            header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
        }
    }else{
        header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
    }


}else if(isset($_POST['idCategoria']) || isset($_SESSION['idCategoria'])){
    if($_POST['idCategoria']){
        $idCategoria = $_POST['idCategoria'];

        if($idCategoria > 0) {
            $_SESSION['idCategoria'] = $idCategoria;
        }else{
            header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
        }
    }else if($_SESSION['idCategoria']){
        $idCategoria = $_SESSION['idCategoria'];

        if($idCategoria > 0) {
            $_SESSION['idCategoria'] = $idCategoria;
        }else{
            header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
        }
    }else{
        header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
    }
}else{
    header('Location: ../sdo-administrador/inventario-hbqp.php?errorModificarInventario=true');
}

if(isset($_POST['idEquipoModificar']) || isset($_SESSION['idEquipoModificar'])){

    if($_POST['idEquipoModificar']){
        $idEquipoModificar = $_POST['idEquipoModificar'];

        if($idEquipoModificar > 0) {
            $_SESSION['idEquipoModificar'] = $idEquipoModificar;
        }else{
            if($idCategoria == 0 || empty($idCategoria)){
                header('Location: ../sdo-administrador/agregar-equipo-inventario-general.php?errorAgregarEquipo1=true');
            }else{
                header('Location: ../sdo-administrador/equipamiento_hbqp.php?errorIngresoInventario=true');
            }
        }
    }else if($_SESSION['idEquipoModificar']){
        $idEquipoModificar = $_SESSION['idEquipoModificar'];

        if($idEquipoModificar > 0) {
            $_SESSION['idEquipoModificar'] = $idInventario;
        }else{
            if($idCategoria == 0 || empty($idCategoria)){
                header('Location: ../sdo-administrador/agregar-equipo-inventario-general.php?errorAgregarEquipo2=true');
            }else{
                header('Location: ../sdo-administrador/equipamiento_hbqp.php?errorIngresoInventario=true');
            }
        }
    }else{
        if($idCategoria == 0 || empty($idCategoria)){
            header('Location: ../sdo-administrador/agregar-equipo-inventario-general.php?errorAgregarEquipo3=true');
        }else{
            header('Location: ../sdo-administrador/equipamiento_hbqp.php?errorIngresoInventario=true');
        }
    }

}else{
    if($idCategoria == 0 || empty($idCategoria)){
        header('Location: ../sdo-administrador/agregar-equipo-inventario-general.php?errorAgregarEquipo4=true');
    }else{
        header('Location: ../sdo-administrador/equipamiento_hbqp.php?errorIngresoInventario=true');
    }
}

$getDatosInventario = array();
$getDatosInventario = getDatosInventario($idEquipoModificar);


$equipoInventario = $getDatosInventario['equipoInventario'];
$marcaInventario = $getDatosInventario['marcaInventario'];
$modeloInventario = $getDatosInventario['modeloInventario'];
$cantidadInventario = $getDatosInventario['cantidadInventario'];
$categoriaInventario = $getDatosInventario['categoriaInventario'];
$fechaInventario = $getDatosInventario['fechaInventario'];
$fechaActualizacion = $getDatosInventario['fechaActualizacion'];
$nomProveedor = $getDatosInventario['nomProveedor'];
$rutProveedor = $getDatosInventario['rutProveedor'];
$tipoDocumento = $getDatosInventario['tipoDocumento'];
$solicompra = $getDatosInventario['solicompra'];
$observacion = $getDatosInventario['observacion'];
$valorUnitario = $getDatosInventario['valorUnitario'];
$unidad = $getDatosInventario['unidad'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Equipo a Inventario</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Inventario de Equipo </h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>

                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <a href="inventario-hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>

                        </div>

                    </div>

                </div>
                <?php
                if (isset($_GET['errorAgregarEquipo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            '¡ Ha ocurrido un Problema !',
                            'Error al Agregar Equipo. Reintente.',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorEquipoExiste'])) {
                    ?>
                    <script>
                        Swal.fire(
                            '¡ Ha ocurrido un Problema !',
                            'Equipo Existe en Base de Datos.',
                            'error'
                        )
                    </script>
                    <?php
                }
                ?>
                <div class="row" style="text-align:center;" id="rowCrearPresupuesto">
                    <div class="col-12" style="text-align:center;">
                        <div class="card" style="text-align:center;">
                            <div class="card-body" style="text-align:center;">

                                <form action="../sdo-funciones/modificar-inventario-hbqp.php" method="POST" enctype="multipart/form-data">

                                    <input type="hidden" id="idEquipoModificar" name="idEquipoModificar" value="<?php echo $idEquipoModificar?>">
                                    <fieldset class="border p-3">
                                        <legend class="w-auto" style="text-align: left;">&nbsp;Informaci&oacute;n del Proveedor:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                <label for="noOrdenTrabajo">Escriba Nombre de Proveedor:<br><code>Para agregar otro proveedor, elija "Seleccione..."</code></label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control select2" id="proveedorSelect" style="width: 100%">
                                                        <option value="Seleccione...">Seleccione...</option>
                                                        <?php
                                                        $sqlProveedor = "SELECT * FROM proveedor_equipos ORDER BY nomProveedor";
                                                        $resProveedor = mysqli_query($conexion, $sqlProveedor);
                                                        if ($resProveedor->num_rows > 0) {
                                                            while ($rowProveedor = $resProveedor->fetch_assoc()) {
                                                                if($rowProveedor['rutProveedor'] == $rutProveedor){
                                                                    ?>
                                                                    <option value="<?php echo $rowProveedor['idProveedor'] ?>" selected data-nombre="<?php echo $rowProveedor['nomProveedor']; ?>" data-rut="<?php echo $rowProveedor['rutProveedor']; ?>"><?php echo $rowProveedor['nomProveedor'] ?></option>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <option value="<?php echo $rowProveedor['idProveedor'] ?>" data-nombre="<?php echo $rowProveedor['nomProveedor']; ?>" data-rut="<?php echo $rowProveedor['rutProveedor']; ?>"><?php echo $rowProveedor['nomProveedor'] ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                                                <label for="noOrdenTrabajo">&nbsp;</label>
                                                <br>&nbsp;<br>
                                                <button type="button" class="btn btn-block bg-gradient-success" style="text-transform:capitalize" id="aumentar" name="aumentar" onclick="seleccionarProveedor()"><i class="fas fa-plus"></i> Seleccionar Proveedor</button>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del Proveedor: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <?php
                                                    ?>
                                                    <input type="text" class="form-control" name="nombreProveedor" id="nombreProveedor" placeholder="Nombre del Proveedor" value="<?php echo $nomProveedor;?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">RUT del Proveedor: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control rut" name="rutProveedor" id="rutProveedor" placeholder="RUT del Proveedor" value="<?php echo $rutProveedor;?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- COPIAS -->
                                    </fieldset>

                                    <br>

                                    <fieldset class="border p-3">
                                        <legend class="w-auto" style="text-align: left;">&nbsp;Informaci&oacute;n del Equipo:&nbsp;&nbsp;</legend>

                                        <div class="form-row">
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Fecha de Ingreso: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" readonly="readonly" id="fechaEquipo" name="fechaEquipo" class="form-control" value="<?php echo date('Y-m-d H:i:s',strtotime($fechaInventario)); ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Seleccione Inventario: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                                    </div>
                                                    <select class="form-control" id="selectInventario" name="selectInventario">
                                                        <option value="" disabled >--Seleccione--</option>
                                                        <?php
                                                        $sqlCategoria = "SELECT * FROM categorias_equipos ORDER BY nomCategoria ASC";
                                                        $resCategoria = mysqli_query($conexion, $sqlCategoria);
                                                        while ($rowCategoria = $resCategoria->fetch_assoc()) {
                                                            $nomCategoria = $rowCategoria['nomCategoria'];
                                                            $idCategoria = $rowCategoria['idCategoria'];

                                                            if($idCategoria == $categoriaInventario){
                                                                ?>
                                                                <option value="<?php echo $idCategoria ?>" selected><?php echo $nomCategoria ?></option>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <option value="<?php echo $idCategoria ?>"><?php echo $nomCategoria ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <input type="hidden" name="idEquipoModificarAgregar" id="idInventarioAgregar">
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Nombre del Equipo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cog"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="nombreEquipo" id="nombreEquipo" value="<?php echo $equipoInventario ?>" placeholder="Nombre del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Marca del Equipo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cog"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="marcaEquipo" id="marcaEquipo" value="<?php echo $marcaInventario ?>" placeholder="Marca del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Modelo del Equipo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cog"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="modeloEquipo" id="modeloEquipo" value="<?php echo $modeloInventario ?>" placeholder="Modelo del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Cantidad de Equipo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                    </div>
                                                    <input type="number" min="0" class="form-control" name="cantidadEquipo" id="cantidadEquipo" value="<?php echo $cantidadInventario ?>" placeholder="Cantidad de Equipo">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row">
<!--                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">-->
<!--                                                <label for="exampleInputTurno">Tipo de Documento: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-truck-loading"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <select id="tipoDocumento" name="tipoDocumento" onchange="selectTipoDocumento(event)" class="form-control">-->
<!--                                                        <option value="" selected disabled>--Seleccione--</option>-->
<!--                                                        <option value="Solicitud de Compra - SDO">Solicitud de Compra - SDO</option>-->
<!--                                                        <option value="Solicitud de Compra - PM">Solicitud de Compra - PM</option>-->
<!--                                                        <option value="Cotizacion Empresa">Cotizacion Empresa</option>-->
<!--                                                        <option value="Cotizacion Internet">Cotizacion Internet</option>-->
<!--                                                        <option value="Orden de Compra">Orden de Compra</option>-->
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <script>-->
<!--                                                function selectTipoDocumento() {-->
<!--                                                    var select = document.getElementById("tipoDocumento").value;-->
<!--                                                    var scsdo = document.getElementById("sc-sdo");-->
<!--                                                    var scpm = document.getElementById("sc-pm");-->
<!--                                                    var ce = document.getElementById("ce");-->
<!--                                                    var ci = document.getElementById("ci");-->
<!--                                                    var oc = document.getElementById("oc");-->
<!---->
<!--                                                    if (select === 'Solicitud de Compra - SDO') {-->
<!--                                                        scsdo.style.display = "block";-->
<!--                                                        scpm.style.display = "none";-->
<!--                                                        ce.style.display = "none";-->
<!--                                                        ci.style.display = "none";-->
<!--                                                        oc.style.display = "none";-->
<!--                                                    } else if (select === 'Solicitud de Compra - PM') {-->
<!--                                                        scpm.style.display = "block";-->
<!--                                                        scsdo.style.display = "none";-->
<!--                                                        ce.style.display = "none";-->
<!--                                                        ci.style.display = "none";-->
<!--                                                        oc.style.display = "none";-->
<!--                                                    } else if (select === 'Cotizacion Empresa') {-->
<!--                                                        ce.style.display = "block";-->
<!--                                                        scsdo.style.display = "none";-->
<!--                                                        scpm.style.display = "none";-->
<!--                                                        ci.style.display = "none";-->
<!--                                                        oc.style.display = "none";-->
<!--                                                    } else if (select === 'Cotizacion Internet') {-->
<!--                                                        ce.style.display = "none";-->
<!--                                                        scsdo.style.display = "none";-->
<!--                                                        scpm.style.display = "none";-->
<!--                                                        ci.style.display = "block";-->
<!--                                                        oc.style.display = "none";-->
<!--                                                    } else if (select === 'Orden de Compra') {-->
<!--                                                        ce.style.display = "none";-->
<!--                                                        scsdo.style.display = "none";-->
<!--                                                        scpm.style.display = "none";-->
<!--                                                        ci.style.display = "none";-->
<!--                                                        oc.style.display = "block";-->
<!--                                                    }-->
<!--                                                }-->
<!--                                            </script>-->
                                            <!-- AQUI VA LOS ADICIONALES -->

<!--                                            <div id="sc-sdo" class="form-group col-md-3" style="display: none">-->
<!--                                                <label for="exampleInputTurno">Solicitud de Compra - SDO: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <input list="sc-sdo1" class="form-control" name="sc-sdo1">-->
<!--                                                    <datalist id="sc-sdo1">-->
<!--                                                        --><?php
//                                                        $sqlscsdo = "SELECT * FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Completada'";
//                                                        $resscsdo = mysqli_query($conexion, $sqlscsdo);
//                                                        while ($rowscsdo = $resscsdo->fetch_assoc()){
//                                                        $folioSoliCompra = $rowscsdo['folioSoliCompra'];
//                                                        ?>
<!--                                                        <option value="--><?php //echo $folioSoliCompra ?><!--">-->
<!--                                                            --><?php
//                                                            }
//                                                            ?>
<!--                                                    </datalist>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div id="ce" class="form-group col-md-3" style="display: none">-->
<!--                                                <label for="exampleInputTurno">Cotizacion Empresa: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <input type="file" class="form-control" name="cotizacionempresa1" id="cotizacionempresa1">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div id="ci" class="form-group col-md-3" style="display: none">-->
<!--                                                <label for="exampleInputTurno">Cotizacion Internet: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <input type="file" class="form-control" name="cotizacioninternet1" id="cotizacioninternet1">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div id="oc" class="form-group col-md-3" style="display: none">-->
<!--                                                <label for="exampleInputTurno">Orden de Compra: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <input class="form-control" name="oc1" id="oc1" value="N°: 1704-">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div id="sc-pm" class="form-group col-md-3" style="display: none">-->
<!--                                                <label for="exampleInputTurno">Solicitud de Compra - PM: <code>*</code></label>-->
<!--                                                <div class="input-group mb-3">-->
<!--                                                    <div class="input-group-prepend">-->
<!--                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <input list="sc-pm1" class="form-control" name="sc-pm1">-->
<!--                                                    <datalist id="sc-pm1">-->
<!--                                                        --><?php
//                                                        $sqlscpm = "SELECT * FROM solicitudes_compra_pm";
//                                                        $resscpm = mysqli_query($conexion, $sqlscpm);
//                                                        while ($rowscpm = $resscpm->fetch_assoc()){
//                                                        $folioSoliCompraPm = $rowscpm['folioSoliCompra'];
//                                                        ?>
<!--                                                        <option value="--><?php //echo $folioSoliCompraPm ?><!--">-->
<!--                                                            --><?php
//                                                            }
//                                                            ?>
<!--                                                    </datalist>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                            <!-- FIN ADICIONALES -->
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Precio Unitario (S/IVA): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cash-register"></i></span>
                                                    </div>
                                                    <input class="form-control" type="number" min="0" value="<?php echo $valorUnitario ?>" name="precioUnitario" id="precioUnitario">
                                                </div>
                                            </div>
                                            <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                                <label for="exampleInputTurno">Unidad: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                                    </div>
                                                    <input class="form-control" type="text" name="unidadEquipo" value="<?php echo $unidad ?>" id="unidadEquipo">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <br>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto" style="text-align: left">&nbsp;Observaciones:&nbsp;&nbsp;</legend>
                                        <textarea class="form-control" style="resize: none" id="obs" name="obs" rows="7"><?php echo $observacion ?></textarea>
                                    </fieldset>
                                    <br>
                                    <div class="form-group">
                                        <br>
                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria1; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>
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

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
<!-- AdminLTE for demo purposes -->
<!-- Page specific script -->


<script>
    $(document).ready(function () {

        // Inicializar el select2
        $('.select2').select2();

        seleccionarProveedor();
    });


    function seleccionarProveedor() {
        var select = document.getElementById("proveedorSelect");
        var option = select.options[select.selectedIndex];

        if (option.value !== "Seleccione...") {
            document.getElementById("nombreProveedor").value = option.dataset.nombre;
            document.getElementById("nombreProveedor").readOnly = true;
            document.getElementById("rutProveedor").value = option.dataset.rut;
            document.getElementById("rutProveedor").readOnly = true;
        } else {
            document.getElementById("nombreProveedor").value = '';
            document.getElementById("nombreProveedor").readOnly = false;
            document.getElementById("rutProveedor").value = '';
            document.getElementById("rutProveedor").readOnly = false;
        }
    }

</script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {
            'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
            'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('#reservationdatetime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function (event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function () {
            myDropzone.enqueueFile(file)
        }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End
</script>


<script>
    function formatearRut(rut) {
        rut = rut.replace(/[^\dkK]/g, '');

        if (rut.length > 1) {
            let formateado = rut.slice(-4, -1) + '-' + rut.substr(rut.length - 1);
            for (let i = 4; i < rut.length; i += 3) {
                formateado = rut.slice(-i - 3, -i) + '.' + formateado;
            }
            return formateado;
        } else {
            return rut;
        }
    }

    // Agrega un evento delegado al document
    document.addEventListener('input', function (event) {
        // Verifica si el elemento que desencadenó el evento pertenece a la clase RUT
        if (event.target.classList.contains('rut')) {
            event.target.value = formatearRut(event.target.value).toUpperCase();
        }
    });

    document.addEventListener('paste', function (event) {
        if (event.target.classList.contains('rut')) {
            setTimeout(function () {
                event.target.value = formatearRut(event.target.value).toUpperCase();
            }, 0);
        }
    });
</script>
<script src="js/dark-mode.js"></script>
</body>
</html>
3