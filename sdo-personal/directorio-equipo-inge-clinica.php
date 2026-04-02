<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
date_default_timezone_set('America/Santiago');

if($_POST['idEquipo']){

    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){


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
        $ahnoAdquisicion = $row['AhnoAdquisicion'];
        $propiedades = $row['propiedades'];
        $evaluaciones = $row['evaluaciones'];
        $nombreProovedor = $row['nombreProovedor'];
        $mantenimientos = $row['mantenimientos'];
        $enGarantia = $row['enGarantia'];
        $vidaUtilResidual = $row['vidaUtilResidual'];
        $bajoPlanMantenimiento = $row['bajoPlanMantenimiento'];
        $idConvenioMantenimiento = $row['idConvenioMantenimiento'];
        $costoAnualMantenimiento = $row['costoAnualMantenimiento'];
        $frecuenciaAnualMantenimiento = $row['frecuenciaAnualMantenimiento'];
    }

    $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $nomCategoria = $datos['nomCategoria'];
    }

    $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
        $cantidadHojasTec = $datos['cantidadHojasTec'];
    }

    $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
    $resSolicitud = mysqli_query($conexion,$sqlSolicitud);

    while($rowSolicitud = $resSolicitud->fetch_assoc()){
        $datosSolicitud = [];
        $datosSolicitud['total'] = $rowSolicitud['total'];
        $total = $datosSolicitud['total'];
    }

    $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
        $cantidadSubEquipos = $datos['cantidadSubEquipos'];
    }

    $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadArchivos'] = $row['cantidadArchivos'];
        $cantidadArchivos = $datos['cantidadArchivos'];
    }


    $_SESSION['idEquipo'] = $idEquipo;
}else if($_SESSION['idEquipo']){
    $idEquipo = $_SESSION['idEquipo'];
    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sql);
    while($row = $res->fetch_assoc()){


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
        $ahnoAdquisicion = $row['AhnoAdquisicion'];
        $propiedades = $row['propiedades'];
        $evaluaciones = $row['evaluaciones'];
        $nombreProovedor = $row['nombreProovedor'];
        $mantenimientos = $row['mantenimientos'];
        $enGarantia = $row['enGarantia'];
        $vidaUtilResidual = $row['vidaUtilResidual'];
        $bajoPlanMantenimiento = $row['bajoPlanMantenimiento'];
        $idConvenioMantenimiento = $row['idConvenioMantenimiento'];
        $costoAnualMantenimiento = $row['costoAnualMantenimiento'];
        $frecuenciaAnualMantenimiento = $row['frecuenciaAnualMantenimiento'];
    }

    $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $nomCategoria = $datos['nomCategoria'];
    }

    $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
        $cantidadHojasTec = $datos['cantidadHojasTec'];
    }

    $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
        $cantidadSubEquipos = $datos['cantidadSubEquipos'];
    }

    $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
    $res = mysqli_query($conexion, $sql);

    while($row = $res->fetch_assoc()){
        $datos = [];
        $datos['cantidadArchivos'] = $row['cantidadArchivos'];
        $cantidadArchivos = $datos['cantidadArchivos'];
    }
    $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
    $resSolicitud = mysqli_query($conexion,$sqlSolicitud);

    while($rowSolicitud = $resSolicitud->fetch_assoc()){
        $datosSolicitud = [];
        $datosSolicitud['total'] = $rowSolicitud['total'];
        $total = $datosSolicitud['total'];
    }

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Directorio</title>
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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

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
            $(".agregarAccesorios").click(function () {
                $("#tabladelosaccesorios").hide();
                $("#listarAccesoriosbutton").show();
                $("#ingresodeaccesorios").show();
                $("#agregarAccesorios1").hide();
            });
            $(".listadoAccesorios").click(function () {
                $("#tabladelosaccesorios").show();
                $("#listarAccesoriosbutton").hide();
                $("#ingresodeaccesorios").hide();
                $("#agregarAccesorios1").show();
            });
            $(".agregarInsumos").click(function(){
                $("#agregarInsumos1").hide();
                $("#listarInsumosbutton").show();
                $("#ingresodeinsumos").show();
                $("#tabladelosinsumos").hide();
            });
            $(".listadoInsumos").click(function () {
                $("#tabladelosinsumos").show();
                $("#listarInsumosbutton").hide();
                $("#ingresodeinsumos").hide();
                $("#agregarInsumos1").show();
            });
        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';

    if(isset($_GET['exitoEditarEquipo'])){
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

    if(isset($_GET['errorEditarEquipo'])){
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

    if(isset($_GET['exitoHijos'])){
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

    if(isset($_GET['errorHijos'])){
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

    if(isset($_GET['exitoCambioEstado'])){
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

    if(isset($_GET['errorCambioEstado'])){
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
                            <div class="card-header bg-info">
                                <a href="carpeta-equipo-inge-clinica.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <h1 class="card-title">Visualizando Directorio - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?> </h1>
                                <?php

                                $nomDetalleEquipo = $nombreEquipo;

                                $nomDetalleCategoria = $nomCategoria;

                                $nomDetalleCompleto = $nomDetalleEquipo . ' - ' . $nomDetalleCategoria;

                                ?>
                            </div>
                            <div class="card-body">
                                <h4 class="text-center text-bold text-muted">Directorio - <?php echo $nombreEquipo ?></h4>
                                <br>
                                <div class="row text-center justify-content-center col-lg-12">
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header <?php  echo $color ?>">
                                                    <h5 class="modal-title" id="exampleModalLabel"> <b>Cambiar el estado del equipo</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="formularioCambiaEstado" method="POST" action="../sdo-funciones/cambiaEstadoEquipo.php">
                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                        <input type="hidden" value="<?php echo $idCategoria ?>" name="idCategoria">
                                                        <input type="hidden" value="<?php echo $nombreEquipo?>" name="nombreEquipo">
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
                                                                        if($estadoEquipo == 'Fuera de Servicio'){
                                                                            ?>
                                                                            <option value="<?php echo $fuera?>" selected="selected" >Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                                            <?php
                                                                        } else if($estadoEquipo == 'Mantenimiento'){
                                                                            ?>
                                                                            <option value="<?php echo $man?>" selected="selected" >Mantenimiento</option>
                                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                            <option value="Operativo">Operativo</option>
                                                                            <?php
                                                                        }else{
                                                                            ?>
                                                                            <option value="<?php echo $ope?>" selected="selected" >Operativo</option>
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
                                                    <h5 class="modal-title" id="exampleModalLabel"> <b> Estas visualizando todas las observaciones de este directorio</b> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                                        <thead  class="bg-info">
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
                                                        while($rowObservaciones = $resObservaciones->fetch_assoc()){
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
                                                                if($tipoUsuario == 'administrativo') {
                                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                                    }

                                                                }else if($tipoUsuario == 'jefe tecnico'){
                                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                                        $nombre = $datos2['nomJefe'];
                                                                    }

                                                                }else if($tipoUsuario == 'personal'){
                                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                        $datos2 = [];
                                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                                        $nombre = $datos2['nomPersonal'];
                                                                    }
                                                                } else if($tipoUsuario == 'administrador'){
                                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion,$sql);
                                                                    while($row2 = $res->fetch_assoc()){
                                                                        $datos2 = [];
                                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                                        $nombre = $datos2['nomAdministrador'];

                                                                    }

                                                                } else if($tipoUsuario == 'mesa ayuda'){
                                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                                    $res = mysqli_query($conexion,$sql);
                                                                    while($row2 = $res->fetch_assoc()){
                                                                        $datos2 = [];
                                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                                        $nombre = $datos2['nomMesaAyuda'];

                                                                    }
                                                                } else if($tipoUsuario == 'directiva'){
                                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                                    $res = mysqli_query($conexion,$sql);
                                                                    while($row2 = $res->fetch_assoc()){
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
                                                                <td class="text-center"  style="font-size: 14px"><?php echo  $fechaFormateada ?></td>
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
                                <!--
                                <div class="row">
                                    <div class="row col-lg-12">
                                        <div class="col-lg-3 col-12" >
                                            <form action="equipo-hojas-tecnicas.php" method="post" style="display: inline">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-primary btn-lg"> <i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?><br>Hojas Técnicas </button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12" >
                                            <form action="equipo-gastos.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="fas fa-money-bill"></i><br>
                                                    <?php
                                                    if($gastoTotal == 0){
                                                        echo '$ 0';
                                                    }else{
                                                        echo moneda_chilena($gastoTotal);
                                                    }
                                                    ?>
                                                    <br>Gastos</button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <form action="equipo-solicitudes-compra.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-danger btn-lg"> <i class="fas fa-cart-plus"></i><br>
                                                    <?php
                                                    if($total == 0){
                                                        echo '0';
                                                    }else{
                                                        echo $total;

                                                    }
                                                    ?>
                                                    <br>Solicitudes de Compra</button>
                                            </form>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <form action="equipo-archivos.php" method="post" style="display: inline">
                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                                <button type="submit" class="btn btn-block bg-gradient-pink btn-lg"> <i class="fas fa-archive"></i><br><?php echo $cantidadArchivos  ?><br>Archivos Adjuntos</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                -->
                                <nav class="bg-info">
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Info de todos los equipos <!--<span class="badge badge-danger"><?php echo $cantidadSubEquipos ?></span> --></button>
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
                                    <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <form action="../sdo-funciones/guardar-edicion-equipo-inge-clinica.php" method="post">
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
                                                        <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra ?>" >
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                        <input type="text" class="form-control form-control-sm" name="inputMantenimiento" value="<?php echo $frecuenciaMantenimiento ?>"  id="inputMantenimiento">
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
                                                        <input type="text" class="form-control form-control-sm" id="costoMantencion"  value="<?php echo $costoMantAnual ?>" name="costoMantencion">
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
                                                        <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $numeroSerie  ?>" name="numSerie">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Año de adquisicion:</label>
                                                        <input type="text" class="form-control form-control-sm" id="ahnoAdquisicion" value="<?php echo $ahnoAdquisicion  ?>" name="ahnoAdquisicion">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Propiedades:</label>
                                                        <input type="text" class="form-control form-control-sm" id="propiedades" value="<?php echo $propiedades  ?>" name="propiedades">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Evaluaciones:</label>
                                                        <input type="text" class="form-control form-control-sm" id="evaluaciones" value="<?php echo $evaluaciones  ?>" name="evaluaciones">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Nombre Del Proovedor:</label>
                                                        <input type="text" class="form-control form-control-sm" id="nombreProovedor" value="<?php echo $nombreProovedor  ?>" name="nombreProovedor">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Mantenimientos:</label>
                                                        <input type="text" class="form-control form-control-sm" id="mantenimientos" value="<?php echo $mantenimientos  ?>" name="nombreProovedor">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">En Garantía:</label>
                                                        <input type="text" class="form-control form-control-sm" id="enGarantia" value="<?php echo $enGarantia  ?>" name="enGarantia">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Vida Util Residual:</label>
                                                        <input type="text" class="form-control form-control-sm" id="vidaUtilResidual" value="<?php echo $vidaUtilResidual  ?>" name="vidaUtilResidual">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Bajo Plan de Mantenimiento:</label>
                                                        <input type="text" class="form-control form-control-sm" id="bajoPlanMantenimiento" value="<?php echo $bajoPlanMantenimiento  ?>" name="bajoPlanMantenimiento">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">ID Convenio Mantenimiento:</label>
                                                        <input type="text" class="form-control form-control-sm" id="idConvenioMantenimiento" value="<?php echo $idConvenioMantenimiento  ?>" name="idConvenioMantenimiento">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Costo Anual del Mantenimiento:</label>
                                                        <input type="text" class="form-control form-control-sm" id="costoAnualMantenimiento" value="<?php echo $costoAnualMantenimiento  ?>" name="costoAnualMantenimiento">
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                        <label for="numSerie" style="font-size: 12px;">Frecuencia Anual del Mantenimiento:</label>
                                                        <input type="text" class="form-control form-control-sm" id="frecuenciaAnualMantenimiento" value="<?php echo $frecuenciaAnualMantenimiento  ?>" name="frecuenciaAnualMantenimiento">
                                                    </div>
                                                    <?php
                                                    if($tipoControlador == 'esclavo'){
                                                        ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                            <input type="text" class="form-control form-control-sm" id="device" value="<?php echo $device  ?>" name="device">
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
                                                            <label for="numSerie" style="font-size: 12px;">Alimentación TCC:</label>
                                                            <input type="text" class="form-control form-control-sm" id="alimentaciontcc" value="<?php echo $alimentacionTCC ?>" name="alimentaciontcc">
                                                        </div>

                                                        <?php
                                                    }
                                                    if($tipoControlador == 'maestro'){
                                                        ?>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ejesECBOSS" value="<?php echo $ejesECBoss  ?>" name="ejesECBOSS">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Ejes Rack:</label>
                                                            <input type="text" class="form-control form-control-sm" id="ejesRack" value="<?php echo $ejesRack  ?>" name="ejesRack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Rack:</label>
                                                            <input type="text" class="form-control form-control-sm" id="rack" value="<?php echo $rack  ?>" name="rack">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Puerto:</label>
                                                            <input type="text" class="form-control form-control-sm" id="puerto" value="<?php echo $puerto ?>" name="puerto">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label for="numSerie" style="font-size: 12px;">Direccion IP:</label>
                                                            <input type="text" class="form-control form-control-sm" id="direccionIP" value="<?php echo $direccionIP  ?>" name="direccionIP">
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
                                                                $idServicio  = $datos['idServicio'];
                                                                $nomServicio  = $datos['nomServicio'];

                                                                if($idServicio == $servicioDB){
                                                                    ?>
                                                                    <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                                    <?php
                                                                }else{
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
                                                        <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $pma  ?>">
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
                                            <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $tipoControlador?>">
                                            <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <br>
                                        <br>
                                        <!--<button class="btn bg-info text-center" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Añadir un nuevo sub-equipo</b> </button>-->

                                        <!-- Modal -->
                                        <!--<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                                                <th scope="col"  class="text-center">ID Unico</th>
                                                                <th scope="col"  class="text-center">Nombre Equipo</th>
                                                                <th scope="col"  class="text-center">Estado</th>
                                                                <th scope="col"  class="text-center">Seleccionar</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                        $sqlhijo = "SELECT * FROM equipamiento_hbqp WHERE (idEquipoPadre is null OR idEquipoPadre = 0) AND idDepaPertenece = $idDepartamento";
                                        $resHijo = mysqli_query($conexion, $sqlhijo);
                                        $num = 1;
                                        while($rowHijo = $resHijo->fetch_assoc()){
                                            $datos = [];
                                            $datos['id'] = $rowHijo['id'];
                                            $datos['identificadorUnico'] = $rowHijo['identificadorUnico'];
                                            $datos['nombreEquipo'] = $rowHijo['nombreEquipo'];
                                            $datos['estadoEquipo'] = $rowHijo['estadoEquipo'];
                                            $id = $datos['id'];
                                            $identificadorUnico = $datos['identificadorUnico'];
                                            //$nombreEquipo = $datos['nombreEquipo'];
                                            $estadoEquipo = $datos['estadoEquipo'];
                                            ?>
                                                                <tr>
                                                                    <td  class="text-center"><?php echo $num?></td>
                                                                    <td  class="text-center"><?php echo $identificadorUnico?></td>
                                                                    <td  class="text-center"><?php echo $nombreEquipo?></td>
                                                                    <td  class="text-center"><?php echo $estadoEquipo?></td>
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
                                                                <th scope="col"  class="text-center">N°</th>
                                                                <th scope="col"  class="text-center">ID Unico</th>
                                                                <th scope="col"  class="text-center">Nombre Equipo</th>
                                                                <th scope="col"  class="text-center">Estado</th>
                                                                <th scope="col"  class="text-center">Seleccionar</th>
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
                                        -->
                                        <br>
                                        <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">ID</th>
                                                <th scope="col" class="text-center">Nombre Equipo</th>
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Recinto</th>
                                                <th scope="col" class="text-center">PMA</th>
                                                <th scope="col" class="text-center">Piso</th>
                                                <th scope="col" class="text-center">N° Inventario</th>
                                                <th scope="col" class="text-center">N° Serie</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo = '$nombreEquipo' ORDER BY piso ASC, pma ASC";

                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                            $num = 1;

                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowEquipo['id'];
                                                $datos['identificadorUnico'] = $rowEquipo['identificadorUnico'];
                                                $datos['nombreEquipo'] = $rowEquipo['nombreEquipo'];
                                                $datos['idServicio'] = $rowEquipo['idServicio'];
                                                $datos['recinto'] = $rowEquipo['recinto'];
                                                $datos['pma'] = $rowEquipo['pma'];
                                                $datos['piso'] = $rowEquipo['piso'];
                                                $datos['estadoEquipo'] = $rowEquipo['estadoEquipo'];

                                                $idEquipoGeneral = $datos['id'];
                                                $identificadorUnico = $datos['identificadorUnico'];
                                                $nombreEquipo = $datos['nombreEquipo'];
                                                $idServicio = $datos['idServicio'];
                                                $recinto = $datos['recinto'];
                                                $pma = $datos['pma'];
                                                $piso = $datos['piso'];
                                                $estadoEquipo = $datos['estadoEquipo'];
                                                $numeroInventarioHoja = $rowEquipo['numeroInventario'];
                                                $numeroSerieHoja = $rowEquipo['numeroSerie'];

                                                if ($recinto == '') {
                                                    $recinto = '-';
                                                }

                                                if ($pma == '') {
                                                    $pma = '-';
                                                }

                                                if ($piso == '') {
                                                    $piso = '-';
                                                }
                                                $servicio = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                $res = mysqli_query($conexion, $servicio);
                                                while ($rowServicio = $res->fetch_assoc()) {
                                                    $nombreServicio = $rowServicio['nomServicio'];
                                                }

                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $num; ?></td>
                                                    <!-- <td class="text-center"><?php echo $identificadorUnico ?></td> -->
                                                    <td class="text-center"><?php echo $nombreEquipo ?></td>
                                                    <td class="text-center"><?php echo $nombreServicio ?></td>
                                                    <td class="text-center"><?php echo $recinto ?></td>
                                                    <td class="text-center"><?php echo $pma ?></td>
                                                    <td class="text-center"><?php echo $piso ?></td>
                                                    <td class="text-center">
                                                        <?php echo $numeroInventarioHoja?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        echo $numeroSerieHoja;
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="equipo-inge-clinica.php" method="POST" style="display: inline">
                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipoGeneral ?>">
                                                            <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-door-open"></i></button>
                                                        </form>
                                                        <form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento-Inge-Clinica.php" target="_blank" method="POST" style="display: inline">
                                                            <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipoGeneral ?>">
                                                            <button type="submit" title="Hoja de vida del equipo por ingeniería clinica" class="btn btn-success btn-sm"><i class="fa-solid fa-file"></i></button>
                                                        </form>
                                                        <form action="../sdo-pdf/SDO-Registro-Actividad-Inge-Clinica.php" method="POST" target="_blank" style="display: inline">
                                                            <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipoGeneral ?>">
                                                            <button type="submit" title="Registros de actividades del equipo por ingeniería clinica" class="btn btn-primary btn-sm"><i class="fa-solid fa-file"></i></button>
                                                        </form>

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
                                                <th scope="col" class="text-center">Servicio</th>
                                                <th scope="col" class="text-center">Recinto</th>
                                                <th scope="col" class="text-center">PMA</th>
                                                <th scope="col" class="text-center">Piso</th>
                                                <th scope="col" class="text-center">N° Inventario</th>
                                                <th scope="col" class="text-center">N° Serie</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>

                                    <div class="tab-pane fade" id="nav-detalle" role="tabpanel" aria-labelledby="nav-detalle-tab">

                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto"><b>&nbsp;&nbsp;Detalle de <?php echo $nomDetalleEquipo; ?>&nbsp;&nbsp;</b></legend>

                                            <?php

                                            $porciones1 = explode("ECB", $nomDetalleEquipo);

                                            $variableParaTabla = 'ECB '.$porciones1[1];

                                            $porciones2 = explode("_", $variableParaTabla);

                                            $variableParaTablaFinal = $porciones2[0].'-'.$porciones2[1];
                                            ?>


                                            <div class="card-body">
                                                <table id="example2" class="table table-bordered table-hover">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Piso<center>
                                                        </th>
                                                        <th>
                                                            <center>TCC<center>
                                                        </th>
                                                        <th>
                                                            <center>Controlador<center>
                                                        </th>
                                                        <th>
                                                            <center>N&uacute;mero de Entrada<center>
                                                        </th>
                                                        <th>
                                                            <center>Digital Input (UI)<center>
                                                        </th>
                                                        <th>
                                                            <center>Analog Input (UI)<center>
                                                        </th>
                                                        <th>
                                                            <center>N&uacute;mero de Salida<center>
                                                        </th>
                                                        <th>
                                                            <center>Digital Output (UO)<center>
                                                        </th>
                                                        <th>
                                                            <center>Analog Output (UO)<center>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    $query = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "'.$variableParaTablaFinal.'" ORDER BY idControlBMS ASC';

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
                                                        <center>Piso<center>
                                                    </th>
                                                    <th>
                                                        <center>TCC<center>
                                                    </th>
                                                    <th>
                                                        <center>Controlador<center>
                                                    </th>
                                                    <th>
                                                        <center>N&uacute;mero de Entrada<center>
                                                    </th>
                                                    <th>
                                                        <center>Digital Input (UI)<center>
                                                    </th>
                                                    <th>
                                                        <center>Analog Input (UI)<center>
                                                    </th>
                                                    <th>
                                                        <center>N&uacute;mero de Salida<center>
                                                    </th>
                                                    <th>
                                                        <center>Digital Output (UO)<center>
                                                    </th>
                                                    <th>
                                                        <center>Analog Output (UO)<center>
                                                    </th>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="tab-pane fade" id="tabAccesorios" role="tabpanel" aria-labelledby="tabAccesorios1">
                                        <br>
                                        <button id="agregarAccesorios1" class="btn btn-sm bg-success agregarAccesorios"><i class="fas fa-plus"></i> Agregar Accesorios</button>
                                        <button id="listarAccesoriosbutton" style="display: none" class="btn btn-sm bg-success listadoAccesorios"><i class="fas fa-clipboard"></i> Listar Accesorios</button>
                                        <hr>
                                        <div id="tabladelosaccesorios">
                                            <table class="table table-hover text-center table-bordered">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="6">Detalle de Accesorios</th>
                                                </tr>
                                                <tr>
                                                    <th>Accesorios</th>
                                                    <th>Cantidad</th>
                                                    <th>Marca</th>
                                                    <th>Modelo</th>
                                                    <th>Serie</th>
                                                    <th>Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlAccesorios = "SELECT * FROM `detalle_accesorios_inge_clinica` WHERE idEquipo = $idEquipo";
                                                $resAccesorios = mysqli_query($conexion,$sqlAccesorios);
                                                while($rowAccesorios = $resAccesorios->fetch_assoc()){
                                                    ?>
                                                    <tr>
                                                        <td style="width: 25%">
                                                            <?php echo $rowAccesorios['accesorios']?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowAccesorios['cantidad']?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowAccesorios['marca']?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowAccesorios['modelo']?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowAccesorios['serie']?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowAccesorios['obs']?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="ingresodeaccesorios" style="display: none">
                                            <form action="../sdo-funciones/guardar-accesorios-inge-clinico.php" method="POST">
                                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                <div class="table-responsive">
                                                    <button type="button" style="float: right" id="btnAgregarFila" class="btn bg-primary"><i class="fas fa-plus"></i> Añadir otro accesorio</button>
                                                    <br>
                                                    <br>
                                                    <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCESORIOS:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MARCA:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MODELO:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">SERIE:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="miTbody">
                                                        <tr>
                                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <input type="text" class="form-control" name="articuloSC1"   placeholder="Articulo">
                                                            </td>
                                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <input type="number" min="1" class="form-control" id="cantidadSC1" name="cantidadSC1" placeholder="Cantidad">
                                                            </td>
                                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <input type="text" min="1" class="form-control" id="marcaSC1" name="marcaSC1" placeholder="Marca">
                                                            </td>
                                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <input type="text" min="1" class="form-control" id="modeloSC1" name="modeloSC1" placeholder="Modelo">
                                                            </td>
                                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <input type="text" min="1" class="form-control" id="serieSC1" name="serieSC1" placeholder="Serie">
                                                            </td>
                                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion1" name="observacion1"></textarea>
                                                            </td>
                                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                No se puede eliminar
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="cantidadFilasFuncionario" id="cantidadFilasFuncionario" value="1"/>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-block">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tabInsumos" role="tabpanel" aria-labelledby="tabInsumos1">
                                        <br>
                                        <button id="agregarInsumos1" class="btn btn-sm bg-primary agregarInsumos"><i class="fas fa-plus"></i> Agregar Insumos</button>
                                        <button id="listarInsumosbutton" style="display: none" class="btn btn-sm bg-primary listadoInsumos"><i class="fas fa-clipboard"></i> Listar Insumos</button>
                                        <hr>
                                        <div id="tabladelosinsumos">
                                            <table class="table table-hover text-center table-bordered">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="5">Detalle de los Insumos</th>
                                                </tr>
                                                <tr>
                                                    <th>Insumos</th>
                                                    <th>Marca</th>
                                                    <th>Tipo</th>
                                                    <th>Cantidad</th>
                                                    <th>Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlInsumos = "SELECT * FROM `detalle_insumos_inge_clinica` WHERE idEquipo = $idEquipo";
                                                $resInsumos = mysqli_query($conexion,$sqlInsumos);
                                                while($rowInsumos = $resInsumos ->fetch_assoc()){
                                                    ?>
                                                    <tr>
                                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                                            <?php echo $rowInsumos['insumos']?>
                                                        </td>
                                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                                            <?php echo $rowInsumos['marca']?>
                                                        </td>
                                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                                            <?php echo $rowInsumos['tipo']?>
                                                        </td>
                                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                                            <?php echo $rowInsumos['cantidad']?>
                                                        </td>
                                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                                            <?php echo $rowInsumos['obs']?>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="ingresodeinsumos" style="display: none">
                                            <form action="../sdo-funciones/guardar-insumos-inge-clinico.php" method="POST">
                                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                <button type="button" style="float: right" id="btnAgregarFilaInsumos" class="btn bg-danger"><i class="fas fa-plus"></i> Añadir otro insumo</button>
                                                <br>
                                                <br>
                                                <table id="miTablaInsumos" class="table table-bordered" style="font-size: 12px;">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INSUMOS:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">TIPO:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MARCA:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="miTbodyInsumos">
                                                    <tr>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" name="insumosSC1"   placeholder="Articulo">
                                                        </td>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" name="tiposSC1"   placeholder="Tipo">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" min="1" class="form-control" id="marcainsumosSC1" name="marcainsumosSC1" placeholder="Marca">
                                                        </td>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="number" min="1" class="form-control" id="cantidadinsumosSC1" name="cantidadinsumosSC1" placeholder="Cantidad">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion1" name="observacion1"></textarea>
                                                        </td>
                                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            No se puede eliminar
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="cantidadFilasInsumos" id="cantidadFilasInsumos" value="1"/>
                                                <button class="btn btn-primary btn-block">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
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
        });
    });
</script>
<script>
    const tablaInsumos = document.getElementById('miTablaInsumos');
    const btnAgregarFilaInsumos = document.getElementById('btnAgregarFilaInsumos');
    let correlativoInsumos = 2;
    var tbodyInsumos = document.getElementById('miTbodyInsumos');
    btnAgregarFilaInsumos.addEventListener('click', () => {
        const nuevaFilaInsumos = document.createElement('tr');
        let contenidoInsumos = `
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" class="form-control" name="insumosSC${correlativoInsumos}"   placeholder="Articulo">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" class="form-control" name="tiposSC${correlativoInsumos}"   placeholder="Tipo">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="marcainsumosSC${correlativoInsumos}" name="marcainsumosSC${correlativoInsumos}" placeholder="Marca">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="number" min="1" class="form-control" id="cantidadinsumosSC${correlativoInsumos}" name="cantidadinsumosSC${correlativoInsumos}" placeholder="Cantidad">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion${correlativoInsumos}" name="observacion${correlativoInsumos}"></textarea>
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
           <button type="button" onclick="eliminarFilaInsumos(this)" class="btn btn-block btn-danger">
                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
            </button>
        </td>
        `;
        nuevaFilaInsumos.innerHTML = contenidoInsumos;
        tablaInsumos.querySelector('tbody').appendChild(nuevaFilaInsumos);
        correlativoInsumos++;

        const cantidadDeFilas2Insumos = tbodyInsumos.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasInsumos').value = cantidadDeFilas2Insumos;
    });
    function eliminarFilaInsumos(boton) {
        const filaInsumos = boton.closest('tr');
        filaInsumos.remove();
        correlativoInsumos--;
        const cantidadDeFilas2Insumos = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasInsumos').value = cantidadDeFilas2Insumos;
    }
</script>
<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('btnAgregarFila');
    let correlativo = 2;
    var tbody = document.getElementById('miTbody');
    btnAgregarFila.addEventListener('click', () => {
        const nuevaFila = document.createElement('tr');
        let contenido = `
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
           <input type="text" class="form-control" name="articuloSC${correlativo}" placeholder="Articulo">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="number" min="1" class="form-control" id="cantidadSC${correlativo}" name="cantidadSC${correlativo}" placeholder="Cantidad">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="marcaSC${correlativo}" name="marcaSC${correlativo}" placeholder="Marca">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="modeloSC${correlativo}" name="modeloSC${correlativo}" placeholder="Modelo">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="serieSC${correlativo}" name="serieSC${correlativo}" placeholder="Serie">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion${correlativo}" name="observacion${correlativo}"></textarea>
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;">
                <button type="button" onclick="eliminarFila(this)" class="btn btn-block btn-danger">
                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
                </button>
        </td>
        `;
        nuevaFila.innerHTML = contenido;
        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++;

        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    });
    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        correlativo--;
        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    }
</script>

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
</html>
