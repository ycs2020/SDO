
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$idDepartamento = $_SESSION['idDepartamento'];

if ($_POST['idEquipo']) {


    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $idEquipoAnterior = $_POST['idEquipoAnterior'];
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {


        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
    }

    $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $nomCategoria = $datos['nomCategoria'];
    }

    $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
        $cantidadHojasTec = $datos['cantidadHojasTec'];
    }

    $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
    $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

    while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
        $datosSolicitud = [];
        $datosSolicitud['total'] = $rowSolicitud['total'];
        $total = $datosSolicitud['total'];
    }

    $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
        $cantidadSubEquipos = $datos['cantidadSubEquipos'];
    }

    $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadArchivos'] = $row['cantidadArchivos'];
        $cantidadArchivos = $datos['cantidadArchivos'];
    }


    $_SESSION['idEquipo'] = $idEquipo;
} else if ($_SESSION['idEquipo']) {
    $idEquipo = $_SESSION['idEquipo'];
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {


        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
    }

    $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $nomCategoria = $datos['nomCategoria'];
    }

    $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
        $cantidadHojasTec = $datos['cantidadHojasTec'];
    }

    $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
        $cantidadSubEquipos = $datos['cantidadSubEquipos'];
    }

    $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadArchivos'] = $row['cantidadArchivos'];
        $cantidadArchivos = $datos['cantidadArchivos'];
    }
    $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
    $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

    while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
        $datosSolicitud = [];
        $datosSolicitud['total'] = $rowSolicitud['total'];
        $total = $datosSolicitud['total'];
    }

}


// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipo</title>
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
    <style>
        .zoom-card {
            transition: transform 0.3s;
        }

        .zoom-card:hover {
            transform: scale(1.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $(".show-agregar").click(function () {
                $("#agregarnuevodiv").show();
                $("#tablaequipos").hide();
                $("#divshowlistar").show();
                $("#divshowagregar").hide();
            });
            $(".show-listar").click(function (){
                $("#agregarnuevodiv").hide();
                $("#tablaequipos").show();
                $("#divshowlistar").hide();
                $("#divshowagregar").show();
            });
        });

    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';

    if (isset($_GET['exitoEditarEquipo'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se guardo los cambios correctamente ',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorEditarEquipo'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Se produjo un error al guardar los cambios ',
                'error'
            )
        </script>
        <?php
    }

    if (isset($_GET['exitoHijos'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se agrego el sub-equipo exitosamente',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorHijos'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'No fue posible agregar el sub equipo seleccionado ',
                'error'
            )
        </script>
        <?php
    }

    if (isset($_GET['exitoCambioEstado'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'El cambio de estado del equipo resulto exitoso',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorCambioEstado'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Se genero un problema al cambiar el estado del equipo',
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
                            <div class="card-header bg-info">
                                <?php
                                if ($idEquipoAnterior > 0) {
                                    ?>
                                    <a href="equipo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                } else {
                                    ?>
                                    <a href="carpeta-equipo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <?php
                                }
                                ?>
                                <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?> </h1>
                                <?php

                                $nomDetalleEquipo = $nombreEquipo;

                                $nomDetalleCategoria = $nomCategoria;

                                $nomDetalleCompleto = $nomDetalleEquipo . ' - ' . $nomDetalleCategoria;

                                ?>
                            </div>
                            <div class="card-body">
                                <h4 class="text-center text-bold text-muted">Equipo - <?php echo $nombreEquipo ?></h4>
                                <br>
                                <div class="row text-center justify-content-center col-lg-12">
                                    <div class="col-lg-3 col-12">
                                        <?php
                                        if ($estadoEquipo == 'Operativo' || $estadoEquipo == 'operativo' || $estadoEquipo == 'OPERATIVO') {
                                            $color = 'bg-gradient-success';
                                        } else if ($estadoEquipo == 'Mantenimiento') {
                                            $color = 'bg-gradient-warning';
                                        } else {
                                            $color = 'bg-gradient-danger';
                                        }
                                        ?>
                                        <!-- small box -->
                                        <div class="row">
                                            <div class="row col-lg-12">
                                                <div class="col-lg-6 col-12">
                                                    <button type="button" class="btn btn-block <?php echo $color ?> btn-lg" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="fas fa-edit"></i> <br> Estado Actual: <b><?php echo $estadoEquipo ?></b> <br> </i>
                                                    </button>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <button type="button" class="btn btn-block btn-lg btn-primary" data-toggle="modal" data-target="#exampleModal1">
                                                        <i class="fas fa-list"></i> <br> Listado de Observaciones del Equipo<br> </i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <!-- Button trigger modal -->

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header <?php echo $color ?>">
                                                    <h5 class="modal-title" id="exampleModalLabel"><b>Cambiar el estado del equipo</b></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formularioCambiaEstado" method="POST" action="../sdo-funciones/cambiaEstadoEquipo.php">
                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                        <input type="hidden" value="<?php echo $idCategoria ?>" name="idCategoria">
                                                        <input type="hidden" value="<?php echo $nombreEquipo ?>" name="nombreEquipo">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="codigoBarra">Nuevo Estado:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-info"></i></span>
                                                                    </div>
                                                                    <select name="opcionEstado" id="opcionEstado" required class="form-control">
                                                                        <?php
                                                                        $fuera = 'Fuera de Servicio';
                                                                        $ope = 'Operativo';
                                                                        $man = 'Mantenimiento';
                                                                        if ($estadoEquipo == 'Fuera de Servicio') {
                                                                            ?>
                                                                            <option value="<?php echo $fuera ?>" selected="selected">Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <?php
                                                                        } else if ($estadoEquipo == 'Mantenimiento') {
                                                                            ?>
                                                                            <option value="<?php echo $man ?>" selected="selected">Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <option value="<?php echo $ope ?>" selected="selected">Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="codigoBarra">Observaciones:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Estado</button>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando todas las observaciones de este equipo</b></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                                        <thead class="bg-info">
                                                        <tr>
                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                            <th scope="col" class="text-center">Fecha</th>
                                                            <th scope="col" class="text-center">Tipo</th>
                                                            <th scope="col" class="text-center">Detalle</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos_respaldo WHERE idEquipo = $idEquipo";
                                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                                            $idCambio = $datos['idCambio'];
                                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                                            $fecha = $datos['fecha'];
                                                            $datos['estado'] = $rowObservaciones['estado'];
                                                            $estado = $datos['estado'];
                                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                                            $observaciones = $datos['observaciones'];
                                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                                            $idEquipo = $datos['idEquipo'];
                                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                                            $idUsuarioCambia = $datos['idUsuarioCambia'];
                                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                                            $tipoUsuario = $datos['tipoUsuario'];
                                                            $fecha = strtotime($fecha);
                                                            $fechaFormateada = date('d/m/Y', $fecha);
                                                            ?>
                                                            <tr>
                                                                <?php
                                                                if ($tipoUsuario == 'administrativo') {
                                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                                    }

                                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                                        $nombre = $datos2['nomJefe'];
                                                                    }

                                                                } else if ($tipoUsuario == 'personal') {
                                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                                        $nombre = $datos2['nomPersonal'];
                                                                    }
                                                                } else if ($tipoUsuario == 'administrador') {
                                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                                        $nombre = $datos2['nomAdministrador'];

                                                                    }

                                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                                        $nombre = $datos2['nomMesaAyuda'];

                                                                    }
                                                                } else if ($tipoUsuario == 'directiva') {
                                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                                        $nombre = $datos2['nomDirectivo'];
                                                                    }
                                                                }
                                                                ?>
                                                                <td class="text-center" style="font-size: 14px">
                                                                    <?php
                                                                    echo $nombre;
                                                                    ?>

                                                                </td>
                                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot class="bg-info">
                                                        <th scope="col" class="text-center">Usuario Observador</th>
                                                        <th scope="col" class="text-center">Fecha</th>
                                                        <th scope="col" class="text-center">Tipo</th>
                                                        <th scope="col" class="text-center">Detalle</th>
                                                        </tfoot>
                                                    </table>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="row col-lg-12">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <form action="equipo-hojas-tecnicas.php" method="post" style="display: inline">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?><br>Hojas Técnicas</button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <form action="equipo-gastos.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-money-bill"></i><br>
                                                    <?php
                                                    if ($gastoTotal == 0) {
                                                        echo '$ 0';
                                                    } else {
                                                        echo moneda_chilena($gastoTotal);
                                                    }
                                                    ?>
                                                    <br>Gastos
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <form action="equipo-solicitudes-compra.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-danger btn-lg"><i class="fas fa-cart-plus"></i><br>
                                                    <?php
                                                    if ($total == 0) {
                                                        echo '0';
                                                    } else {
                                                        echo $total;

                                                    }
                                                    ?>
                                                    <br>Solicitudes de Compra
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <form action="equipo-archivos.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-pink btn-lg"><i class="fas fa-archive"></i><br><?php echo $cantidadArchivos ?><br>Archivos Adjuntos</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <nav class="bg-info">
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Información Equipo</button>
                                        <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Sub-Equipos <span class="badge badge-danger"><?php echo $cantidadSubEquipos ?></span></button>
                                        <?php
                                        if($idDepartamento == 1){
                                            ?>
                                            <button class="nav-link" id="nav-pdf-tab" data-toggle="tab" data-target="#nav-pdf" type="button" role="tab" aria-controls="nav-pdf" aria-selected="false">Manual</button>
                                            <button class="nav-link" id="nav-pautas-tab" data-toggle="tab" data-target="#nav-pauta" type="button" role="tab" aria-controls="nav-pauta" aria-selected="false">Pauta de Mantencion</button>
                                            <?php
                                        }
                                        ?>

                                        <?php

                                        $modeloBuscar1 = 'ECB_300';
                                        $modeloBuscar2 = 'ECB_400';
                                        $modeloBuscar3 = 'ECB_600';

                                        $pos1 = strpos($modeloCadena, $modeloBuscar1);
                                        $pos2 = strpos($modeloCadena, $modeloBuscar2);
                                        $pos3 = strpos($modeloCadena, $modeloBuscar3);
                                        if ($pos1 === false) {
                                            if ($pos2 === false) {
                                                if ($pos3 === false) {
                                                } else {
                                                    ?>
                                                    <button class="nav-link" id="nav-detalle-tab" data-toggle="tab" data-target="#nav-detalle" type="button" role="tab" aria-controls="nav-detalle" aria-selected="false">Detalle Controlador</button>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <button class="nav-link" id="nav-detalle-tab" data-toggle="tab" data-target="#nav-detalle" type="button" role="tab" aria-controls="nav-detalle" aria-selected="false">Detalle Controlador</button>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <button class="nav-link" id="nav-detalle-tab" data-toggle="tab" data-target="#nav-detalle" type="button" role="tab" aria-controls="nav-detalle" aria-selected="false">Detalle Controlador</button>
                                            <?php
                                        }

                                        ?>

                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <form action="../sdo-funciones/guardar-edicion-equipo.php" method="post">
                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                            <br>
                                            <fieldset>
                                                <legend>Información Técnica</legend>
                                                <div class="form-row">
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $identificadorUnico ?>" placeholder="Escriba el Identificador unico del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                        <input type="text" class="form-control form-control-sm" id="inputInventario" name="inputInventario" value="<?php echo $numeroInventario ?>" placeholder="Escriba el numero de inventario del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                        <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacion" value="<?php echo $idLicitacion ?>" placeholder="Escriba el id de litación del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                        <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra ?>">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                        <input type="text" class="form-control form-control-sm" name="inputMantenimiento" value="<?php echo $frecuenciaMantenimiento ?>" id="inputMantenimiento">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                        <input type="date" class="form-control form-control-sm" name="fechaRecepcion" value="<?php echo $fechaRecepcion ?>" id="fechaRecepcion">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                        <input type="date" class="form-control form-control-sm" name="fechaCompra" value="<?php echo $fechaCompra ?>" id="fechaCompra" name="fechaCompra">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                        <input type="date" class="form-control form-control-sm" name="fechaMarcha" value="<?php echo $fechaMarcha ?>" id="fechaMarcha" name="fechaMarcha">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="clase" style="font-size: 12px;">Clase:</label>
                                                        <input type="text" class="form-control form-control-sm" id="clase" value="<?php echo $clase ?>" name="clase">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                        <input type="text" class="form-control form-control-sm" id="subClase" value="<?php echo $subclase ?>" name="subClase">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                        <input type="text" class="form-control form-control-sm" id="complejidad" value="<?php echo $complejidadTec ?>" name="complejidad">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                        <input type="text" class="form-control form-control-sm" id="riesgo" value="<?php echo $nivelRiesgo ?>" name="riesgo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                        <input type="text" class="form-control form-control-sm" id="costoMantencion" value="<?php echo $costoMantAnual ?>" name="costoMantencion">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <fieldset>
                                                <legend>Información Básica</legend>

                                                <div class="form-row">
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipo" value="<?php echo $nombreEquipo ?>" placeholder="Escriba el nombre del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                        <input type="number" class="form-control form-control-sm" id="valorConIva" value="<?php echo $valorConIva ?>" name="valorConIva">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                        <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" value="<?php echo $marca ?>" placeholder="Escriba la marca del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                        <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" value="<?php echo $modelo ?>" placeholder="Escriba el modelo del equipo">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                        <input type="text" class="form-control form-control-sm" id="fechaFabricación" value="<?php echo $AhnoFrabricacion ?>" name="fechaFabricación">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                        <input type="text" class="form-control form-control-sm" id="vidaUtil" value="<?php echo $vidaUtil ?>" name="vidaUtil">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                        <input type="text" class="form-control form-control-sm" id="versionSoftware" value="<?php echo $versionSoftware ?>" name="versionSoftware">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                        <input type="text" class="form-control form-control-sm" id="paisOrigen" value="<?php echo $paisOrigen ?>" name="paisOrigen">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                        <input type="text" class="form-control form-control-sm" id="terminoGarantia" value="<?php echo $terminoGarantia ?>" name="terminoGarantia">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                        <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $numeroSerie ?>" name="numSerie">
                                                    </div>
                                                    <?php
                                                    if ($tipoControlador == 'esclavo') {
                                                        ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                            <input type="text" class="form-control form-control-sm" id="device" value="<?php echo $device ?>" name="device">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Network:</label>
                                                            <input type="text" class="form-control form-control-sm" id="network" value="<?php echo $network ?>" name="network">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Mac Address:</label>
                                                            <input type="text" class="form-control form-control-sm" id="mac" value="<?php echo $mac ?>" name="mac">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">TCC:</label>
                                                            <input type="text" class="form-control form-control-sm" id="tcc" value="<?php echo $tcc ?>" name="tcc">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes TCC:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ejestcc" value="<?php echo $ejesTCC ?>" name="ejestcc">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Alimentacion TCC:</label>
                                                            <input type="text" class="form-control form-control-sm" id="alimentaciontcc" value="<?php echo $alimentacionTCC ?>" name="alimentaciontcc">
                                                        </div>

                                                        <?php
                                                    }
                                                    if ($tipoControlador == 'maestro') {
                                                        ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ejesECBOSS" value="<?php echo $ejesECBoss ?>" name="ejesECBOSS">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes Rack:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ejesRack" value="<?php echo $ejesRack ?>" name="ejesRack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Rack:</label>
                                                            <input type="text" class="form-control form-control-sm" id="rack" value="<?php echo $rack ?>" name="rack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Puerto:</label>
                                                            <input type="text" class="form-control form-control-sm" id="puerto" value="<?php echo $puerto ?>" name="puerto">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Direccion IP:</label>
                                                            <input type="text" class="form-control form-control-sm" id="direccionIP" value="<?php echo $direccionIP ?>" name="direccionIP">
                                                        </div>
                                                        <?php
                                                    }

                                                    ?>

                                                </div>
                                            </fieldset>
                                            <hr>
                                            <fieldset>
                                                <legend>Ubicación</legend>
                                                <div class="form-row">
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                        <select class="form-control form-control-sm" id="servicio" required name="servicio">
                                                            <option value="" disabled selected>-- Seleccione --</option>

                                                            <?php

                                                            $sqlServicios = "SELECT * FROM servicios";
                                                            $resServicios = mysqli_query($conexion, $sqlServicios);

                                                            while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                $datos = [];

                                                                $datos['idServicio'] = $rowServicios['idServicio'];
                                                                $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                $idServicio = $datos['idServicio'];
                                                                $nomServicio = $datos['nomServicio'];

                                                                if ($idServicio == $servicioDB) {
                                                                    ?>
                                                                    <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                        <input type="text" class="form-control form-control-sm" id="recinto" name="recinto" value="<?php echo $recinto ?>">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="pma" style="font-size: 12px;">PMA:</label>
                                                        <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $pma ?>">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                        <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas" value="<?php echo $coordenadas ?>">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="coordenadas" style="font-size: 12px;">Piso:</label>
                                                        <input type="text" class="form-control form-control-sm" id="piso" name="piso" value="<?php echo $piso ?>">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <hr>
                                            <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $tipoControlador ?>">
                                            <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <br>
                                        <div class="row">
                                            <button class="btn bg-info text-center" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Asociar un nuevo sub-equipo</b></button>&nbsp;
                                            <div id="divshowagregar">
                                                <button class="btn bg-gradient-success text-center show-agregar" id="agregarnuevo"><i class="fas fa-plus"></i><b> Añadir un nuevo equipo y asociarlo como sub-equipo</b></button>
                                            </div>
                                            <div id="divshowlistar" style="display: none">
                                                <button class="btn bg-gradient-success text-center show-listar"  id="listarsub-equipos"><i class="fas fa-clipboard-list"></i><b> Listar sub-equipos</b></button>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Seleccione el equipo a añadir</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-sm table-bordered table-hover" id="tablaSubequipos">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">ID Unico</th>
                                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                                <th scope="col" class="text-center">Estado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $sqlhijo = "SELECT * FROM equipamiento_hbqp WHERE (idEquipoPadre is null OR idEquipoPadre = 0) AND idDepaPertenece = $idDepartamento";
                                                            $resHijo = mysqli_query($conexion, $sqlhijo);
                                                            $num = 1;
                                                            while ($rowHijo = $resHijo->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['id'] = $rowHijo['id'];
                                                                $datos['identificadorUnico'] = $rowHijo['identificadorUnico'];
                                                                $datos['nombreEquipo'] = $rowHijo['nombreEquipo'];
                                                                $datos['estadoEquipo'] = $rowHijo['estadoEquipo'];
                                                                $id = $datos['id'];
                                                                $identificadorUnico = $datos['identificadorUnico'];
                                                                $nombreEquipo = $datos['nombreEquipo'];
                                                                $estadoEquipo = $datos['estadoEquipo'];
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $num ?></td>
                                                                    <td class="text-center"><?php echo $identificadorUnico ?></td>
                                                                    <td class="text-center"><?php echo $nombreEquipo ?></td>
                                                                    <td class="text-center"><?php echo $estadoEquipo ?></td>
                                                                    <td>
                                                                        <form action="../sdo-funciones/agregarEquipoHijo.php" method="POST">
                                                                            <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo ?>">
                                                                            <input type="hidden" name="idEquipoHijo" value="<?php echo $id ?>">
                                                                            <input type="submit" value="Añadir Equipo" class="btn bg-danger">
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                <?php

                                                                $num++;
                                                            }
                                                            ?>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">ID Unico</th>
                                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                                <th scope="col" class="text-center">Estado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="button" class="btn btn-primary">Guardar Equipo</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="agregarnuevodiv" style="display:none;">
                                            <p class="text-bold text-danger">(*) Campos Obligatorios</p>
                                            <form action="../sdo-funciones/guardar-equipo.php" method="post">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo?>">
                                                <fieldset>
                                                    <legend>Información Técnica</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2 col-12">
                                                            <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                            <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $idGenerado ?>" placeholder="Escriba el Identificador unico del equipo">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                                <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID Único es generado aleatoriamente y es la identificación del equipo dentro del sistema, guarde este código." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                            <input type="text" class="form-control form-control-sm" id="inputInventarioInsert" name="inputInventarioInsert" placeholder="Escriba el numero de inventario del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                            <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacionInsert" placeholder="Escriba el id de litación del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompraInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                            <input type="text" class="form-control form-control-sm" name="inputMantenimiento" id="inputMantenimientoInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                            <input type="date" class="form-control form-control-sm" name="fechaRecepcion" id="fechaRecepcionInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                            <input type="date" class="form-control form-control-sm"  id="fechaCompra" name="fechaCompraInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                            <input type="date" class="form-control form-control-sm"  id="fechaMarcha" name="fechaMarchaInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="clase" style="font-size: 12px;">Clase:</label>
                                                            <input type="text" class="form-control form-control-sm" id="clase" name="claseInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                            <input type="text" class="form-control form-control-sm" id="subClase" name="subClaseInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                            <input type="text" class="form-control form-control-sm" id="complejidad" name="complejidadInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                            <input type="text" class="form-control form-control-sm" id="riesgo" name="riesgoInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                            <input type="text" class="form-control form-control-sm" id="costoMantencion" name="costoMantencionInsert">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <fieldset>
                                                    <legend>Información Básica</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                            <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipoInsert" placeholder="Escriba el nombre del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                            <input type="number" class="form-control form-control-sm" id="valorConIva" name="valorConIvaInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                            <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipoInsert" placeholder="Escriba la marca del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                            <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipoInsert" placeholder="Escriba el modelo del equipo">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                            <input type="text" class="form-control form-control-sm" id="fechaFabricación" name="fechaFabricaciónInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                            <input type="text" class="form-control form-control-sm" id="vidaUtil" name="vidaUtilInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                            <input type="text" class="form-control form-control-sm" id="versionSoftware" name="versionSoftwareInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                            <input type="text" class="form-control form-control-sm" id="paisOrigen" name="paisOrigenInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                            <input type="text" class="form-control form-control-sm" id="terminoGarantia" name="terminoGarantiaInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                            <input type="text" class="form-control form-control-sm" id="numSerie" name="numSerieInsert">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <fieldset>
                                                    <legend>Ubicación</legend>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                            <select class="form-control form-control-sm" id="servicio" required name="servicioInsert">
                                                                <option value="" disabled selected>-- Seleccione --</option>

                                                                <?php

                                                                $sqlServicios = "SELECT * FROM servicios";
                                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                    $datos = [];

                                                                    $datos['idServicio'] = $rowServicios['idServicio'];
                                                                    $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                                    $idServicio  = $datos['idServicio'];
                                                                    $nomServicio  = $datos['nomServicio'];

                                                                    ?>

                                                                    <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>

                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                            <input type="text" class="form-control form-control-sm" id="recinto" name="recintoInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="pma" style="font-size: 12px;">PMA:</label>
                                                            <input type="text" class="form-control form-control-sm" id="pma" name="pmaInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                            <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadasInsert">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="coordenadas" style="font-size: 12px;">Piso: <code>*</code> <code>Coloque un 0 si no conoce el piso</code></label>
                                                            <input type="text"  required class="form-control form-control-sm" id="piso" name="pisoInsert">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <hr>
                                                <button type="submit" class="btn btn-success btn-block text-center"><b> Guardar Equipo</b></button>
                                            </form>
                                        </div>
                                        <br>
                                        <div id="tablaequipos">
                                            <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">ID</th>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                    <th scope="col" class="text-center">Recinto</th>
                                                    <th scope="col" class="text-center">PMA</th>
                                                    <th scope="col" class="text-center">Piso</th>
                                                    <th scope="col" class="text-center">Estado</th>
                                                    <th scope="col" class="text-center">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                if ($modelo == 'ASC1208C-S') {
                                                    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = " . $idEquipo . " ORDER BY nombreEquipo ASC";
                                                } else {
                                                    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo ORDER BY piso ASC, pma ASC";
                                                }

                                                $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                $num = 1;

                                                while ($rowEquipo = $resEquipo->fetch_assoc()) {

                                                    $idSubEquipo = $rowEquipo['id'];
                                                    $idUnicoSubEquipo = $rowEquipo['identificadorUnico'];
                                                    $nomSubEquipo = $rowEquipo['nombreEquipo'];
                                                    $servicioSubEquipo = $rowEquipo['idServicio'];
                                                    $recintoSubEquipo = $rowEquipo['recinto'];
                                                    $pmaSubEquipo = $rowEquipo['pma'];
                                                    $pisoSubEquipo = $rowEquipo['piso'];
                                                    $estadoSubEquipo = $rowEquipo['estadoEquipo'];

                                                    if ($recintoSubEquipo == '') {
                                                        $recintoSubEquipo = '-';
                                                    }

                                                    if ($pmaSubEquipo == '') {
                                                        $pmaSubEquipo = '-';
                                                    }

                                                    if ($pisoSubEquipo == '') {
                                                        $pisoSubEquipo = '-';
                                                    }


                                                    $estadoSubEquipo = mb_convert_case($estadoSubEquipo, MB_CASE_UPPER, 'UTF-8');
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $num; ?></td>
                                                        <?php
                                                        if ($modelo == 'ASC1208C-S') {
                                                            ?>
                                                            <td class="text-center bg-danger"><?php echo $nomSubEquipo ?></td>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <td class="text-center"><?php echo $nomSubEquipo ?></td>
                                                            <?php
                                                        }

                                                        ?>
                                                        <td class="text-center"><?php echo $recintoSubEquipo ?></td>
                                                        <td class="text-center"><?php echo $pmaSubEquipo ?></td>
                                                        <td class="text-center"><?php echo $pisoSubEquipo ?></td>

                                                        <?php


                                                        if ($estadoSubEquipo == 'OPERATIVO') {
                                                            ?>
                                                            <td class="font-weight-bold text-success  text-center " style="display: inline-block"><?php echo '<i class="fas fa-check"></i> ' . strtoupper($estadoSubEquipo) ?></td>
                                                            <?php
                                                        } else if ($estadoSubEquipo == 'MANTENIMIENTO') {
                                                            ?>
                                                            <td class="font-weight-bold   text-warning text-center " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' . strtoupper($estadoSubEquipo) ?></td>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <td class="font-weight-bold   text-danger text-center " style="display: inline-block"><?php echo '<i class="fas fa-skull-crossbones"></i> ' . strtoupper($estadoSubEquipo) ?></td>
                                                            <?php
                                                        }

                                                        ?>
                                                        <td class="text-center">
                                                            <form action="../sdo-pdf/sdo-hoja-vida-equipamiento.php" target="_blank" method="post" style="display: inline;">
                                                                <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idSubEquipo ?>">
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="far fa-file"></i></button>
                                                            </form>
                                                            <form action="equipo.php" method="post" style="display: inline">
                                                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idSubEquipo ?>">
                                                                <input type="hidden" id="idEquipoAnterior" name="idEquipoAnterior" value="<?php echo $idSubEquipo ?>">
                                                                <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-door-open"></i></button>
                                                            </form>

                                                            <button class="btn btn-danger btn-sm eliminar" id="<?php echo $idSubEquipo ?>"><i class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php

                                                    $num++;
                                                }

                                                ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">ID</th>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <!-- <th scope="col" class="text-center">Servicio</th> -->
                                                    <th scope="col" class="text-center">Recinto</th>
                                                    <th scope="col" class="text-center">PMA</th>
                                                    <th scope="col" class="text-center">Piso</th>
                                                    <th scope="col" class="text-center">Estado</th>
                                                    <th scope="col" class="text-center">Acciones</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="nav-detalle" role="tabpanel" aria-labelledby="nav-detalle-tab">

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto"><b>&nbsp;&nbsp;Detalle de <?php echo $nomDetalleEquipo; ?>&nbsp;&nbsp;</b></legend>

                                            <?php

                                            $porciones1 = explode("ECB", $nomDetalleEquipo);

                                            $variableParaTabla = 'ECB ' . $porciones1[1];

                                            $porciones2 = explode("_", $variableParaTabla);

                                            $variableParaTablaFinal = $porciones2[0] . '-' . $porciones2[1];
                                            ?>


                                            <div class="card-body">
                                                <table id="example2" class="table table-bordered table-hover">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Piso
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>TCC
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>Controlador
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>N&uacute;mero de Entrada
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>Digital Input (UI)
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>Analog Input (UI)
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>N&uacute;mero de Salida
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>Digital Output (UO)
                                                                <center>
                                                        </th>
                                                        <th>
                                                            <center>Analog Output (UO)
                                                                <center>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $query = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "' . $variableParaTablaFinal . '" ORDER BY idControlBMS ASC';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numContBMS = 1;

                                                    while ($row = $res->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center><?php echo $numContBMS; ?></center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                                <?php
                                                                $piso_ControlBMS = mb_strtoupper($row['piso_ControlBMS']);
                                                                echo $piso_ControlBMS;
                                                                ?>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $tcc_ControlBMS = mb_strtoupper($row['tcc_ControlBMS']);
                                                                echo $tcc_ControlBMS;
                                                                ?>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $controlador_ControlBMS = mb_strtoupper($row['controlador_ControlBMS']);
                                                                echo $controlador_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $numEntrada_ControlBMS = $row['numEntrada_ControlBMS'];
                                                                echo $numEntrada_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $digitalInput_ControlBMS = $row['digitalInput_ControlBMS'];
                                                                echo $digitalInput_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $analogInput_ControlBMS = $row['analogInput_ControlBMS'];
                                                                echo $analogInput_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $numSalida_ControlBMS = $row['numSalida_ControlBMS'];
                                                                echo $numSalida_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $digitalOutput_ControlBMS = $row['digitalOutput_ControlBMS'];
                                                                echo $digitalOutput_ControlBMS;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $analogOutput_ControlBMS = $row['analogOutput_ControlBMS'];
                                                                echo $analogOutput_ControlBMS;

                                                                ?>
                                                            </td>

                                                        </tr>
                                                        <?php

                                                        $numContBMS = $numContBMS + 1;
                                                    }
                                                    ?>


                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Piso
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>TCC
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>Controlador
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>N&uacute;mero de Entrada
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>Digital Input (UI)
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>Analog Input (UI)
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>N&uacute;mero de Salida
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>Digital Output (UO)
                                                            <center>
                                                    </th>
                                                    <th>
                                                        <center>Analog Output (UO)
                                                            <center>
                                                    </th>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="tab-pane fade" id="nav-pdf" role="tabpanel" aria-labelledby="nav-pdf-tab">
                                        <!--<embed src="../sdo-administrador/archivos-equipamiento/archivos-adjuntos/carpeta-llamadodeenfermería/1367-SDO.pdf" type="application/pdf" width="100%" height="600px"/>-->
                                        <div class="row">
                                            <fieldset class="border p-3 text-center" style="width: 20%">
                                                <p><b>Listado de Manuales:</b></p>
                                                <hr>
                                                <button type="button" title="Agregar un manual" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarmanual"><i class="fas fa-plus"></i></button>
                                                <hr>
                                                <?php
                                                $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo AND tipoArchivo = 'Manuales'";
                                                $resArchivos = mysqli_query($conexion,$sqlArchivos);
                                                $count = 1;
                                                while($rowArchivos = $resArchivos->fetch_assoc()){
                                                    $nomArchivo = $rowArchivos['nomArchivo'];
                                                    $rutaArchivo = $rowArchivos['rutaArchivo'];
                                                    $idArchivo = $rowArchivos['id'];
                                                    ?>
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')"><i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo?></button>
                                                            <!--<button type="button" title="Modificar el manual" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modificarmanual<?php echo $idArchivo?>"><i class="fas fa-pen"></i></button>-->
                                                            <div class="modal fade" id="modificarmanual<?php echo $idArchivo?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-gradient-warning">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Modificar Nombre del Manual</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/modificar-nomManueales.php" method="POST" enctype="multipart/form-data">
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-12 col-12">
                                                                                        <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                            </div>
                                                                                            <input type="text" class="form-control form-control-sm" required id="nomArchivoModi" name="nomArchivoModi" value="<?php echo $nomArchivo?>">
                                                                                            <input type="hidden" name="id" id="id" value="<?php echo $idArchivo?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-success">Modificar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-danger btn-sm eliminarManual" id="<?php echo $idArchivo ?>"><i class="fas fa-trash-alt"></i></button>
                                                            <hr>
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </fieldset>
                                            <fieldset style="width: 80%" class="border p-3 text-center">
                                                <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px"/>
                                            </fieldset>
                                        </div>
                                        <script>
                                            function cambiarPDF(ruta) {
                                                document.getElementById('pdfViewer').setAttribute('src', ruta);
                                            }
                                        </script>
                                        <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                            <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nombreEquipo ?>">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
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
                                                                            $sql = "SELECT * FROM tiposarchivos WHERE nomTipo = 'Manuales'";
                                                                            $res = mysqli_query($conexion, $sql);
                                                                            while($row = $res->fetch_assoc()){
                                                                                $datos = [];
                                                                                $datos['id '] = $row['id'];
                                                                                $datos['nomTipo'] = $row['nomTipo'];
                                                                                $id  = $datos['id'];
                                                                                $nomTipo = $datos['nomTipo'];
                                                                                ?>
                                                                                <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case( $nomTipo, MB_CASE_UPPER, 'UTF-8')  ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                                </div>
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <input type="file" class="form-control form-control-sm" required id="archivoAdjunto" name="archivoAdjunto">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-pauta" role="tabpanel" aria-labelledby="nav-pautas-tab">
                                        <fieldset class="border p-3">
                                            <h2>Pauta de Mantenimiento </h2>
                                            <p>A continuación se detallan las acciones de mantenimiento a realizar:</p>
                                            <ul>
                                                <li><strong>Inspección visual:</strong> Realizar una inspección visual del equipo para detectar signos de desgaste o daño.</li>
                                                <li><strong>Lubricación:</strong> Aplicar lubricante a los puntos de lubricación según las especificaciones del fabricante.</li>
                                                <li><strong>Verificación de niveles:</strong> Verificar y ajustar los niveles de fluidos, como aceite y refrigerante.</li>
                                                <li><strong>Pruebas de funcionamiento:</strong> Realizar pruebas de funcionamiento para asegurar que el equipo opera correctamente.</li>
                                                <li><strong>Calibración:</strong> Calibrar los instrumentos de medición según las especificaciones del fabricante.</li>
                                                <li><strong>Reemplazo de filtros:</strong> Reemplazar los filtros de aire, aceite u otros según el programa de mantenimiento.</li>
                                                <li><strong>Revisión de conexiones:</strong> Verificar y apretar las conexiones eléctricas y mecánicas.</li>
                                                <li><strong>Registro de mantenimiento:</strong> Mantener un registro detallado de todas las actividades de mantenimiento realizadas.</li>
                                            </ul>
                                        </fieldset>

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
<script>
    $(document).ready(function () {
        $('#tablaSubequipos').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#tabla-equipos').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function () {
                this.api().columns([1]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([4]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Piso</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            }
        });
    });
</script>
<script>
    $(document).ready(
        $('.eliminar').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este equipo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-hoja-hijo.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del sub-equipo eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script>
    $(document).ready(function () {
        $('#tabla-equipo-observacion').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>

</body>

</html>