<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');
// echo '';
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

    //CUENTA LA CANTIDAD DE EQUIPOS DE LA CARPETA
    $cuentaEquiposRegistrados = "SELECT COUNT(*) AS cantidadEquiposRegistrados FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaEquiposRegistrados);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadEquiposRegistrados'] = $row['cantidadEquiposRegistrados'];
        $cantidadEquiposRegistrados = $datos['cantidadEquiposRegistrados'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS DE OPERATIVOS
    $cuentaEquiposOperativos = "SELECT COUNT(*) AS cuentaEquiposOperativos FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta && estadoEquipo = 'Operativo'";
    $res = mysqli_query($conexion, $cuentaEquiposOperativos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaEquiposOperativos'] = $row['cuentaEquiposOperativos'];
        $cuentaEquiposOperativos = $datos['cuentaEquiposOperativos'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS EN MANTENIMIENTO
    $cantEquiposMantenimiento = "SELECT COUNT(*) AS cantEquiposMantenimiento FROM historial_cambios_equipos_observacion WHERE idCategoria = $idCarpeta ";
    $res = mysqli_query($conexion, $cantEquiposMantenimiento);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposMantenimiento'] = $row['cantEquiposMantenimiento'];
        $cantEquiposMantenimiento = $datos['cantEquiposMantenimiento'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS CON FALLAS
    $cantEquiposFalla = "SELECT COUNT(*) AS cantEquiposFalla FROM historial_cambios_equipos_falla WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantEquiposFalla);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposFalla'] = $row['cantEquiposFalla'];
        $cantEquiposFalla = $datos['cantEquiposFalla'];
    }

    //CUENTA LA CANTIDAD DE HOJAS TÉCNICAS DE LA CARPETA
    $cuentaHojasTecnicas = "SELECT COUNT(*) AS cantidadHojasTecnicas FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta";


    $res = mysqli_query($conexion, $cuentaHojasTecnicas);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTecnicas'] = $row['cantidadHojasTecnicas'];
        $cantidadHojasTecnicas = $datos['cantidadHojasTecnicas'];
    }

    //SUMA COSTO TOTAL
    $sumaGastoTotal = "SELECT SUM(costoTotal) AS gastoTotal FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sumaGastoTotal);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];
    }

    //SUMA SOLICITUDES COMPRA
    $cuentaSoliCompra = "SELECT COUNT(*) AS cuentaSoliCompra FROM solicitudes_compra_sdo WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaSoliCompra);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaSoliCompra'] = $row['cuentaSoliCompra'];
        $cuentaSoliCompra = $datos['cuentaSoliCompra'];
    }

    //SUMA SOLICITUDES COMPRA
    $cantidadArchivos = "SELECT COUNT(*) AS cuentaArchivos FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantidadArchivos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaArchivos'] = $row['cuentaArchivos'];
        $cuentaArchivos = $datos['cuentaArchivos'];
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

    //CUENTA LA CANTIDAD DE EQUIPOS DE LA CARPETA
    $cuentaEquiposRegistrados = "SELECT COUNT(*) AS cantidadEquiposRegistrados FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaEquiposRegistrados);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadEquiposRegistrados'] = $row['cantidadEquiposRegistrados'];
        $cantidadEquiposRegistrados = $datos['cantidadEquiposRegistrados'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS DE OPERATIVOS
    $cuentaEquiposOperativos = "SELECT COUNT(*) AS cuentaEquiposOperativos FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta && estadoEquipo = 'Operativo'";
    $res = mysqli_query($conexion, $cuentaEquiposOperativos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaEquiposOperativos'] = $row['cuentaEquiposOperativos'];
        $cuentaEquiposOperativos = $datos['cuentaEquiposOperativos'];
    }

    //CUENTA LA CANTIDAD DE EQUIPOS EN MANTENIMIENTO
    $cantEquiposMantenimiento = "SELECT COUNT(*) AS cantEquiposMantenimiento FROM historial_cambios_equipos_observacion WHERE idCategoria = $idCarpeta ";
    $res = mysqli_query($conexion, $cantEquiposMantenimiento);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposMantenimiento'] = $row['cantEquiposMantenimiento'];
        $cantEquiposMantenimiento = $datos['cantEquiposMantenimiento'];
    }


    //CUENTA LA CANTIDAD DE EQUIPOS CON FALLAS
    $cantEquiposFalla = "SELECT COUNT(*) AS cantEquiposFalla FROM historial_cambios_equipos_falla WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantEquiposFalla);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantEquiposFalla'] = $row['cantEquiposFalla'];
        $cantEquiposFalla = $datos['cantEquiposFalla'];
    }


    //CUENTA LA CANTIDAD DE HOJAS TÉCNICAS DE LA CARPETA
    $cuentaHojasTecnicas = "SELECT COUNT(*) AS cantidadHojasTecnicas FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaHojasTecnicas);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cantidadHojasTecnicas'] = $row['cantidadHojasTecnicas'];
        $cantidadHojasTecnicas = $datos['cantidadHojasTecnicas'];
    }

    //SUMA COSTO TOTAL
    $sumaGastoTotal = "SELECT SUM(costoTotal) AS gastoTotal FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sumaGastoTotal);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['gastoTotal'] = $row['gastoTotal'];
        $gastoTotal = $datos['gastoTotal'];

    }

    //SUMA SOLICITUDES COMPRA
    $cuentaSoliCompra = "SELECT COUNT(*) AS cuentaSoliCompra FROM solicitudes_compra_sdo WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cuentaSoliCompra);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaSoliCompra'] = $row['cuentaSoliCompra'];
        $cuentaSoliCompra = $datos['cuentaSoliCompra'];
    }

    //SUMA SOLICITUDES COMPRA
    $cantidadArchivos = "SELECT COUNT(*) AS cuentaArchivos FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $cantidadArchivos);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['cuentaArchivos'] = $row['cuentaArchivos'];
        $cuentaArchivos = $datos['cuentaArchivos'];
    }


}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.1/codebase/dhtmlxgantt.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-gantt.min.js" type="text/javascript"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="preloader  flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
    <br>
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
            </div>
        </div>
        <?php
        if (isset($_GET['exitoGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Exito!',
                    'Se ha guardado el nuevo equipo correctamente!',
                    'success'
                )
            </script>
            <?php
        }
        if (isset($_GET['errorGuardarEquipo'])) {
            ?>
            <script>
                Swal.fire(
                    'Error!',
                    'Se ha producido un error al intentar guardar el equipo!',
                    'error'
                )
            </script>
            <?php
        }
        ?>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a href="equipamiento_hbqp.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <img style="max-width:100%; max-height: 100%;" class="border border-secondary" src="<?php echo $imagenCategoria ?>" alt="Imagen de la categoria">
                                        <br>
                                     
                                        <?php
                                            /*
                                            if($an){

                                            }
                                            */
                                        ?>
                                        
                                        <div id="chart_div" style="width:400; height:300;"></div>

                                        <div id="grafico_barras" style="width:400; height:300;"></div>
                                    </div>
                                    <div class="col-md-9 col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Carpeta - <?php echo $nomCategoria ?> </h1>
                                        <hr>
                                        <br>
                                        <br>
                                        <div class="row ">
                                            <div class="col-12">
                                                <h4 class=" text-bold text-muted" style="display: inline">Estado Actual de los equipos:</h4>
                                            </div>
                                            <br>
                                            <div class="col-12">
                                                <!--  <button class="btn btn-warning "  data-toggle="modal" data-target="#informeModal">
                                                     <b><i class="fas fa-file-pdf"></i> <b>Reporte de estados</b> </b>
                                                 </button> -->

                                                <!-- Modal -->
                                                <div class="modal fade" id="informeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title" id="exampleModalLabel"> <b>Seleccione Periodo</b> </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="../sdo-pdf/SDO-Informe-Estados2.php">
                                                                    <input type="hidden" value="<?php echo $idCarpeta ?>" name="$idCarpeta">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <select name="" id="" class="form-control-sm form-control">
                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                <option value="Enero">Enero</option>
                                                                                <option value="Febrero">Febrero</option>
                                                                                <option value="Marzo">Marzo</option>
                                                                                <option value="Abril">Abril</option>
                                                                                <option value="Mayo">Mayo</option>
                                                                                <option value="Junio">Junio</option>
                                                                                <option value="Julio">Julio</option>
                                                                                <option value="Agosto">Agosto</option>
                                                                                <option value="Septiembre">Septiembre</option>
                                                                                <option value="Octubre">Octubre</option>
                                                                                <option value="Noviembre">Noviembre</option>
                                                                                <option value="Diciembre">Diciembre</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="month" class="form-control form-control-sm" name="fechaSeleccionada" id="fechaSeleccionada">
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar Modal</button>
                                                                <button type="submit" class="btn btn-success">Generar Informe</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">
                                                    <div class="inner">
                                                        <h3><?php echo $cantidadEquiposRegistrados ?></h3>
                                                        <p> <b>Equipos Registrados</b> </p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-folder-plus"></i>
                                                    </div>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h3><?php echo $cuentaEquiposOperativos ?></h3>
                                                        <p> <b>Equipos Operativos</b> </p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-warning" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer">
                                                    <div class="inner">
                                                        <h3><?php
                                                            $cantEquiposObservacion = "SELECT COUNT(*) AS cantEquiposObservacion FROM historial_cambios_equipos WHERE idCategoria = $idCarpeta";
                                                            $res1 = mysqli_query($conexion, $cantEquiposObservacion);
                                                            while ($row1 = $res1->fetch_assoc()) {
                                                                $datos1 = [];
                                                                $datos1['cantEquiposObservacion'] = $row1['cantEquiposObservacion'];
                                                                $cantEquiposObservacion = $datos1['cantEquiposObservacion'];
                                                            }

                                                            echo $cantEquiposObservacion

                                                            ?></h3>
                                                        <p> <b>Equipos Operativos con observación</b> </p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b> Estas visualizando los equipos con observaciones </b> </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <table class="table table-bordered table-sm" id="tabla-equipo-falla">
                                                                <thead  class="bg-info">
                                                                <tr>
                                                                    <th scope="col" class="text-center">Usuario Observador</th>
                                                                    <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                    <th scope="col" class="text-center">Equipo</th>
                                                                    <th scope="col" class="text-center">Fecha</th>
                                                                    <th scope="col" class="text-center">Tipo</th>
                                                                    <th scope="col" class="text-center">Detalle</th>
                                                                    <th scope="col" class="text-center">Marcar</th>
                                                                    <th scope="col" class="text-center">Editar</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = $idCarpeta";
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
                                                                    $datos['nombreEquipo'] = $rowObservaciones['nombreEquipo'];
                                                                    $idEquipo = $datos['idEquipo'];
                                                                    $nomEquipo = $datos['nombreEquipo'];
                                                                    $fecha = strtotime($fecha);
                                                                    $fechaFormateada = date('d/m/Y', $fecha);
                                                                    $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                                                    $tipoUsuario = $datos['tipoUsuario'];
                                                                    $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                                                    $idUsuarioCambia = $datos['idUsuarioCambia'];
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
                                                                        <td class="text-center"  style="font-size: 14px">
                                                                            <?php echo $nomEquipo ?>
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            <form  method="POST" action="equipo.php">
                                                                                <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                                                <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                                            </form>
                                                                        </td>
                                                                        <td class="text-center"  style="font-size: 14px"><?php echo  $fechaFormateada ?></td>
                                                                        <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                        <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                                                        <td class="text-center" style="font-size: 14px">
                                                                            <form method="POST" action="quitar_observacion_de_lista.php">
                                                                                <input type="hidden" value="<?php echo $idCambio?>" name="idCambio" id="idCambio">
                                                                                <button type="submit" class="btn btn-info btn-xs">
                                                                                    <i class="fas fa-check"></i><b></b></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                        <td class="text-center" style="font-size: 14px">
                                                                            <form method="POST" action="editar_observacion.php">
                                                                                <input type="hidden" value="<?php echo $idCambio?>" name="idCambio" id="idCambio">
                                                                                <button type="submit" class="btn btn-warning btn-xs">
                                                                                    <i class="fas fa-pen"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>

                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </tbody>
                                                                <tfoot class="bg-info">
                                                                <th scope="col" class="text-center">Usuario Observador</th>
                                                                <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                <th scope="col" class="text-center">Equipo</th>
                                                                <th scope="col" class="text-center">Fecha</th>
                                                                <th scope="col" class="text-center">Tipo</th>
                                                                <th scope="col" class="text-center">Detalle</th>
                                                                <th scope="col" class="text-center">Marcar</th>
                                                                <th scope="col" class="text-center">Editar</th>
                                                                </tfoot>
                                                            </table>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">

                                                <div class="small-box bg-danger" data-toggle="modal" data-target="#modalFalla" style="cursor: pointer">
                                                    <div class="inner">
                                                        <h3><?php echo $cantEquiposFalla ?></h3>
                                                        <p> <b>Equipos Fuera de Servicio</b> </p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-skull-crossbones"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modalFalla" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b> Estas visualizando los equipos fuera de servicio </b> </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                                                <thead  class="bg-info">
                                                                <tr>
                                                                    <th scope="col" class="text-center">Usuario Observador</th>
                                                                    <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                    <th scope="col" class="text-center">Equipo</th>
                                                                    <th scope="col" class="text-center">Fecha</th>
                                                                    <th scope="col" class="text-center">Tipo</th>
                                                                    <th scope="col" class="text-center">Detalle</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                                <tfoot class="bg-info">
                                                                <th scope="col" class="text-center">Usuario Observador</th>
                                                                <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                <th scope="col" class="text-center">Equipo</th>
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
                                        <hr>
                                        <div class="row ">
                                            <?php
                                            //MPI
                                            $totalMPI = obtieneCantidadTiposMantencion($idCarpeta, 'MPI');
                                            $costoMPI = obtieneGastoTiposMantencion($idCarpeta, 'MPI');

                                            if($totalMPI >= 0){
                                                ?>
                                                <input type="hidden" id="costoMPI" value="<?php echo $costoMPI ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#" data-toggle="modal" data-target="#MPI" >
                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>MPI</h3>
                                                                <input  type="hidden" id="cantidadMPI" value="<?php echo $totalMPI ?>">
                                                                <h5 >Cantidad: <?php echo $totalMPI ?></h5>
                                                                <h5><?php
                                                                    if($costoMPI == 0){
                                                                        echo $costoMPI = '$ 0';
                                                                    }else{
                                                                        echo  $costoMPI = moneda_chilena($costoMPI);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->

                                                <div class="modal fade" id="MPI" tabindex="-1" aria-labelledby="MPILabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-info">
                                                                <h5 class="modal-title" id="MRILabel">Estas visualizando los gastos asociados a Mantenimientos Preventivos Internos (MPI)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <table style="width: 100%" id="tabla-hojas-mpi" class="table table-hover text-center  table-bordered table-sm">
                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                    <tfoot class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            //MRI
                                            $totalMRI = obtieneCantidadTiposMantencion($idCarpeta, 'MRI');
                                            $costoMRI = obtieneGastoTiposMantencion($idCarpeta, 'MRI');

                                            if($totalMRI >= 0){
                                                ?>
                                                <input type="hidden" id="costoMRI" value="<?php echo $costoMRI ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#"  data-toggle="modal" data-target="#MRI">
                                                        <div class="small-box bg-danger">
                                                            <div class="inner">
                                                                <h3>MRI</h3>
                                                                <input type="hidden" id="cantidadMRI" value="<?php echo $totalMRI ?>">
                                                                <h5>Cantidad: <?php echo $totalMRI ?></h5>
                                                                <h5><?php
                                                                    if($costoMRI == 0){
                                                                        echo $costoMRI = '$ 0';
                                                                    }else{
                                                                        echo $costoMRI = moneda_chilena($costoMRI);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->

                                                      <!-- Modal -->
                                                <div class="modal fade" id="MRI" tabindex="-1" aria-labelledby="MRILabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger">
                                                                <h5 class="modal-title" id="MRILabel">Estas visualizando los gastos asociados a Mantenimientos Reparativos Internos (MRI)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <table id="tabla-hojas-mri" class="table table-hover  table-bordered table-sm">
                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'MRI' ORDER BY fechaGasto DESC";
                                                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);



                                                                    $num = 1;

                                                                    if($resEquipo && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                            $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                            $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                            $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                            $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                            $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                            $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                            $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                            $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                            $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                            $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                            $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                            $idGasto = $datos['idGasto'];
                                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                                            $mesGasto = $datos['mesGasto'];
                                                                            $fechaGasto = $datos['fechaGasto'];
                                                                            $nomMantenimiento = $datos['nomMantenimiento'];
                                                                            $idHojaTecnica = $datos['idHojaTecnica'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $solicitudCompra = $datos['solicitudCompra'];
                                                                            $codOrdenCompra = $datos['codOrdenCompra'];
                                                                            $costoTotal = $datos['costoTotal'];
                                                                            $encargadoMant = $datos['encargadoMant'];
                                                                            $idEquipo = $datos['idEquipo'];

                                                                            $formatoFecha = date('d/m/Y', strtotime($fechaGasto));




                                                                            $costoTotal = moneda_chilena($costoTotal);

                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idHojaTecnica == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($encargadoMant == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $encargadoMant;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo == 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $idEquipo;
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }

                                                                    }

                                                                    $sqlHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'MRI' ORDER BY fechaOrden DESC";
                                                                    $resHT = mysqli_query($conexion, $sqlHT);

                                                                    if($resHT && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowHT = $resHT->fetch_assoc()) {

                                                                            $datos = [];

                                                                            $datos['idOrden'] = $rowHT['idOrden'];
                                                                            $datos['fechaOrden'] = $rowHT['fechaOrden'];
                                                                            $datos['tipoMantencion'] = $rowHT['tipoMantencion'];
                                                                            $datos['folioOrden'] = $rowHT['folioOrden'];
                                                                            $datos['ticketAsociado'] = $rowHT['ticketAsociado'];
                                                                            $datos['nomTecnicoRecepcionador'] = $rowHT['nomTecnicoRecepcionador'];
                                                                            $datos['trabajoRealizado'] = $rowHT['trabajoRealizado'];
                                                                            $datos['idEquipo'] = $rowHT['idEquipo'];

                                                                            $idOrden = $datos['idOrden'];
                                                                            $idEquipo = $datos['idEquipo'];
                                                                            $fechaOrden = $datos['fechaOrden'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $folioOrden = $datos['folioOrden'];
                                                                            $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                            $ticketAsociado = $datos['ticketAsociado'];
                                                                            $trabajoRealizado = $datos['trabajoRealizado'];

                                                                            $trabajoRealizado =  substr($trabajoRealizado, 0., 100);

                                                                            $fechaOrden = date('d/m/Y', strtotime($fechaOrden));

                                                                            if($tipoMantencion == 'MPI'){
                                                                                $nomMantencion = 'MANTENCIÓN PREVENTIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MRI'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MPE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'MRE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'OM'){
                                                                                $nomMantencion = 'OBRAS MENORES';
                                                                            }else if($tipoMantencion == 'IS'){
                                                                                $nomMantencion = 'INSPECCIÓN Y/O SUPERVISIÓN';
                                                                            }

                                                                            $solicitudCompra = '';
                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $fechaOrden ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantencion ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idOrden == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($nomTecnicoRecepcionador == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, "UTF-8");
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo == 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="equipo.php" method="post">
                                                                                            <input type="hidden" id="idCategoria" value="<?php echo $idCarpeta?>">
                                                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }

                                                                    }


                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                            }

                                            //MPE
                                            $totalMPE = obtieneCantidadTiposMantencion($idCarpeta, 'MPE');
                                            $costoMPE = obtieneGastoTiposMantencion($idCarpeta, 'MPE');

                                            if($totalMPE >= 0){
                                                ?>
                                                <input type="hidden" id="costoMPE" value="<?php echo $costoMPE ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#"  data-toggle="modal" data-target="#MPE">
                                                        <div class="small-box bg-secondary">
                                                            <div class="inner">
                                                                <h3>MPE</h3>
                                                                <input type="hidden" id="cantidadMPE" value="<?php echo $totalMPE ?>">
                                                                <h5 >Cantidad: <?php echo $totalMPE ?></h5>
                                                                <h5 id="costoMPE"><?php
                                                                    if($costoMPE == 0){
                                                                        echo $costoMPE = '$ 0';
                                                                    }else{
                                                                        echo $costoMPE = moneda_chilena($costoMPE);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->

                                                      <!-- Modal -->
                                                <div class="modal fade" id="MPE" tabindex="-1" aria-labelledby="MPELabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-secondary">
                                                                <h5 class="modal-title" id="ticketsLabel">Estas visualizando los gastos asociados a Mantenimientos Preventivos Externos (MPE)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <fieldset class="border p-3">
                                                                    <label class="w-auto">Tabla de Gastos</label>
                                                                    <br>
                                                                    <table id="tabla-gastos-mpe" class="table table-hover  table-bordered table-sm">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                            <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                            <th scope="col" class="text-center">Solicitud Compra</th>
                                                                            <th scope="col" class="text-center">Hoja Técnica</th>
                                                                            <th scope="col" class="text-center">Monto</th>
                                                                            <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'MPE' ORDER BY fechaGasto DESC";
                                                                        $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                        $num = 1;
                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                            $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                            $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                            $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                            $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                            $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                            $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                            $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                            $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                            $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                            $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                            $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                            $idGasto = $datos['idGasto'];
                                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                                            $mesGasto = $datos['mesGasto'];
                                                                            $fechaGasto = $datos['fechaGasto'];
                                                                            $nomMantenimiento = $datos['nomMantenimiento'];
                                                                            $idHojaTecnica = $datos['idHojaTecnica'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $solicitudCompra = $datos['solicitudCompra'];
                                                                            $codOrdenCompra = $datos['codOrdenCompra'];
                                                                            $costoTotal = $datos['costoTotal'];
                                                                            $encargadoMant = $datos['encargadoMant'];
                                                                            $idEquipo = $datos['idEquipo'];

                                                                            $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                                            $costoTotal = moneda_chilena($costoTotal);

                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idHojaTecnica == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo $costoTotal ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($encargadoMant == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $encargadoMant;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo == 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form method="POST" action="equipo.php">
                                                                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                                            <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                            <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                            <th scope="col" class="text-center">Solicitud Compra</th>
                                                                            <th scope="col" class="text-center">Hoja Técnica</th>
                                                                            <th scope="col" class="text-center">Monto</th>
                                                                            <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </fieldset>
                                                                <br>
                                                                <fieldset class="border p-3">
                                                                    <label class="w-auto">Tabla de Hojas Técnicas</label>
                                                                    <br>
                                                                    <table id="tabla_ht_mpe" class="table table-hover table-bordered table-sm">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Folio</th>
                                                                            <th scope="col" class="text-center">Personal que Realiza</th>
                                                                            <th scope="col" class="text-center">Solicitante</th>
                                                                            <th scope="col" class="text-center">Visualizar HT</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <?php
                                                                        $sqlMPEHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta AND tipoMantencion = 'MPE' ORDER BY fechaOrden DESC";
                                                                        $resMPEHT = mysqli_query($conexion,$sqlMPEHT);
                                                                        if($resMPEHT && mysqli_affected_rows($conexion) > 0) {
                                                                            $numMPE = 1;
                                                                            while($rowMPEHT = $resMPEHT->fetch_assoc()){
                                                                                $idOrdenMPE = $rowMPEHT['idOrden'];
                                                                                $fechaMPEHT = $rowMPEHT['fechaOrden'];
                                                                                $folioMPEHT = $rowMPEHT['folioOrden'];
                                                                                $personalAsignadoMPE = $rowMPEHT['nomTecnicoRecepcionador'];
                                                                                $nomSolicitanteMPE = $rowMPEHT['nomSolicitante'];
                                                                                $idEquipoMPE = $rowMPEHT['idEquipo'];
                                                                                $idCategoriaMPE = $rowMPEHT['idCategoria'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $numMPE?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $fechaMPEHT?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $folioMPEHT?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $personalAsignadoMPE?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo mb_convert_case($nomSolicitanteMPE, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        if ($idOrdenMPE == '') {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            ?>
                                                                                            <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrdenMPE?>">
                                                                                                <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                            </form>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                        if ($idEquipoMPE == '') {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            ?>
                                                                                            <form action="equipo.php" method="post" target="_blank">
                                                                                                <input type="hidden" id="idCategoria" value="<?php echo $idCategoriaMPE?>">
                                                                                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipoMPE?>">
                                                                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                            </form>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                                $numMPE++;
                                                                            }

                                                                        }
                                                                        ?>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Folio</th>
                                                                            <th scope="col" class="text-center">Personal que Realiza</th>
                                                                            <th scope="col" class="text-center">Solicitante</th>
                                                                            <th scope="col" class="text-center">Visualizar HT</th>
                                                                            <th scope="col" class="text-center">Equipo</th>

                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                            }

                                            //MRE
                                            $totalMRE = obtieneCantidadTiposMantencion($idCarpeta, 'MRE');
                                            $costoMRE = obtieneGastoTiposMantencion($idCarpeta, 'MRE');



                                            if($totalMRE >= 0){
                                                ?>
                                                <input type="hidden" id="costoMRE" value="<?php echo $costoMRE ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#"  data-toggle="modal" data-target="#MRE">
                                                        <div class="small-box bg-purple" >
                                                            <div class="inner">
                                                                <h3>MRE</h3>
                                                                <input type="hidden" id="cantidadMRE" value="<?php echo $totalMRE ?>">
                                                                <h5 >Cantidad: <?php echo $totalMRE ?></h5>
                                                                <h5><?php
                                                                    if($costoMRE == 0){
                                                                        echo $costoMRE = '$ 0';
                                                                    }else{
                                                                        echo $costoMRE = moneda_chilena($costoMRE);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->

                                                <div class="modal fade" id="MRE" tabindex="-1" aria-labelledby="MRELabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-purple">
                                                                <h5 class="modal-title" id="MRELabel">Estas visualizando los gastos asociados a Mantenimientos Reparativos Externos (MRE)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <fieldset class="border p-3">
                                                                    <label class="w-auto">Tabla de Gastos:</label>
                                                                    <table id="tabla-gastos-mre" class="table table-hover  table-bordered table-sm">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                            <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                            <th scope="col" class="text-center">Solicitud Compra</th>
                                                                            <th scope="col" class="text-center">Hoja Técnica</th>
                                                                            <th scope="col" class="text-center">Monto</th>
                                                                            <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'MRE' ORDER BY fechaGasto DESC";
                                                                        $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                        $num = 1;
                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                            $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                            $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                            $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                            $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                            $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                            $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                            $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                            $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                            $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                            $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                            $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                            $idGasto = $datos['idGasto'];
                                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                                            $mesGasto = $datos['mesGasto'];
                                                                            $fechaGasto = $datos['fechaGasto'];
                                                                            $nomMantenimiento = $datos['nomMantenimiento'];
                                                                            $idHojaTecnica = $datos['idHojaTecnica'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $solicitudCompra = $datos['solicitudCompra'];
                                                                            $codOrdenCompra = $datos['codOrdenCompra'];
                                                                            $costoTotal = $datos['costoTotal'];
                                                                            $encargadoMant = $datos['encargadoMant'];
                                                                            $idEquipo = $datos['idEquipo'];

                                                                            $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                                            $costoTotal = moneda_chilena($costoTotal);

                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idHojaTecnica == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo $costoTotal ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($encargadoMant == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $encargadoMant;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo == 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $idEquipo;
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                            <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                            <th scope="col" class="text-center">Solicitud Compra</th>
                                                                            <th scope="col" class="text-center">Hoja Técnica</th>
                                                                            <th scope="col" class="text-center">Monto</th>
                                                                            <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </fieldset>
                                                                <br>
                                                                <fieldset class="border p-3">
                                                                   <label class="w-auto">Tabla de Hojas Técnicas</label>
                                                                    <table id="tabla_ht_mre" class="table table-hover  table-bordered table-sm">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Folio</th>
                                                                            <th scope="col" class="text-center">Personal que Realiza</th>
                                                                            <th scope="col" class="text-center">Solicitante</th>
                                                                            <th scope="col" class="text-center">Visualizar HT</th>
                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $sqlMREHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta AND tipoMantencion = 'MRE' ORDER BY fechaOrden DESC";
                                                                        $resMREHT = mysqli_query($conexion,$sqlMREHT);
                                                                        if($resMREHT && mysqli_affected_rows($conexion) > 0) {
                                                                            $numMRE = 1;
                                                                            while($rowMREHT = $resMREHT->fetch_assoc()){
                                                                                $idOrdenMRE = $rowMREHT['idOrden'];
                                                                                $fechaMREHT = $rowMREHT['fechaOrden'];
                                                                                $folioMREHT = $rowMREHT['folioOrden'];
                                                                                $personalAsignadoMRE = $rowMREHT['nomTecnicoRecepcionador'];
                                                                                $nomSolicitanteMRE = $rowMREHT['nomSolicitante'];
                                                                                $idEquipoMRE = $rowMREHT['idEquipo'];
                                                                                $idCategoriaMRE = $rowMREHT['idCategoria'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $numMRE?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $fechaMREHT?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $folioMREHT?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo $personalAsignadoMRE?>
                                                                                    </td>
                                                                                    <td style="vertical-align: center" class="text-center">
                                                                                        <?php echo mb_convert_case($nomSolicitanteMRE, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php

                                                                                        if ($idOrdenMRE == '') {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            ?>
                                                                                            <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrdenMRE?>">
                                                                                                <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                            </form>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                        if ($idEquipoMRE == '') {
                                                                                            echo '-';
                                                                                        } else {
                                                                                            ?>
                                                                                            <form action="equipo.php" method="post" target="_blank">
                                                                                                <input type="hidden" id="idCategoria" value="<?php echo $idCategoriaMRE?>">
                                                                                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipoMRE?>">
                                                                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                            </form>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                                $numMRE++;
                                                                            }

                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">N°</th>
                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                            <th scope="col" class="text-center">Folio</th>
                                                                            <th scope="col" class="text-center">Personal que Realiza</th>
                                                                            <th scope="col" class="text-center">Solicitante</th>
                                                                            <th scope="col" class="text-center">Visualizar HT</th>
                                                                            <th scope="col" class="text-center">Equipo</th>

                                                                        </tr>
                                                                        </tfoot>

                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }

                                            //OM
                                            $totalOM = obtieneCantidadTiposMantencion($idCarpeta, 'OM');
                                            $costoOM = obtieneGastoTiposMantencion($idCarpeta, 'OM');

                                            if($totalOM >= 0){
                                                ?>
                                                <input type="hidden" id="costoOM" value="<?php echo $costoOM ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#" data-toggle="modal" data-target="#OM">
                                                        <div class="small-box bg-primary">
                                                            <div class="inner">
                                                                <h3>OM</h3>
                                                                <input type="hidden" id="cantidadOM" value="<?php echo $totalOM ?>">
                                                                <h5 >Cantidad: <?php echo $totalOM ?></h5>
                                                                <h5><?php
                                                                    if($costoOM == 0){
                                                                        echo $costoOM = '$ 0';
                                                                    }else{
                                                                        echo $costoOM = moneda_chilena($costoOM);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->
                                                <div class="modal fade" id="OM" tabindex="-1" aria-labelledby="OMLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="OMLabel">Estas visualizando los gastos asociados a Obras Menores (OM)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <table id="tabla-hojas-om" class="table table-hover  table-bordered table-sm">
                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'OM' ORDER BY fechaGasto DESC";
                                                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                    $num = 1;

                                                                    if($resEquipo && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                            $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                            $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                            $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                            $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                            $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                            $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                            $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                            $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                            $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                            $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                            $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                            $idGasto = $datos['idGasto'];
                                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                                            $mesGasto = $datos['mesGasto'];
                                                                            $fechaGasto = $datos['fechaGasto'];
                                                                            $nomMantenimiento = $datos['nomMantenimiento'];
                                                                            $idHojaTecnica = $datos['idHojaTecnica'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $solicitudCompra = $datos['solicitudCompra'];
                                                                            $codOrdenCompra = $datos['codOrdenCompra'];
                                                                            $costoTotal = $datos['costoTotal'];
                                                                            $encargadoMant = $datos['encargadoMant'];
                                                                            $idEquipo = $datos['idEquipo'];

                                                                            $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                                            $costoTotal = moneda_chilena($costoTotal);

                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idHojaTecnica == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($encargadoMant == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $encargadoMant;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo = 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $idEquipo;
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }
                                                                    }

                                                                    $sqlHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'OM' ORDER BY fechaOrden DESC";
                                                                    $resHT = mysqli_query($conexion, $sqlHT);

                                                                    if($resHT && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowHT = $resHT->fetch_assoc()) {

                                                                            $datos = [];

                                                                            $datos['idOrden'] = $rowHT['idOrden'];
                                                                            $datos['fechaOrden'] = $rowHT['fechaOrden'];
                                                                            $datos['tipoMantencion'] = $rowHT['tipoMantencion'];
                                                                            $datos['folioOrden'] = $rowHT['folioOrden'];
                                                                            $datos['ticketAsociado'] = $rowHT['ticketAsociado'];
                                                                            $datos['nomTecnicoRecepcionador'] = $rowHT['nomTecnicoRecepcionador'];
                                                                            $datos['trabajoRealizado'] = $rowHT['trabajoRealizado'];
                                                                            $datos['idEquipo'] = $rowHT['idEquipo'];

                                                                            $idOrden = $datos['idOrden'];
                                                                            $idEquipo = $datos['idEquipo'];
                                                                            $fechaOrden = $datos['fechaOrden'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $folioOrden = $datos['folioOrden'];
                                                                            $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                            $ticketAsociado = $datos['ticketAsociado'];
                                                                            $trabajoRealizado = $datos['trabajoRealizado'];

                                                                            if($tipoMantencion == 'MPI'){
                                                                                $nomMantencion = 'MANTENCIÓN PREVENTIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MRI'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MPE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'MRE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'OM'){
                                                                                $nomMantencion = 'OBRAS MENORES';
                                                                            }else if($tipoMantencion == 'IS'){
                                                                                $nomMantencion = 'INSPECCIÓN Y/O SUPERVISIÓN';
                                                                            }

                                                                            $fechaOrden = date('d/m/Y', strtotime($fechaOrden));

                                                                            $solicitudCompra == '';
                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $fechaOrden ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantencion ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idOrden == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($nomTecnicoRecepcionador == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, "UTF-8");
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="equipo.php" method="post">
                                                                                            <input type="hidden" id="idCategoria" value="<?php echo $idCarpeta?>">
                                                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }

                                                                    }


                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            //IS
                                            $totalIS = obtieneCantidadTiposMantencion($idCarpeta, 'IS');
                                            $costoIS = obtieneGastoTiposMantencion($idCarpeta, 'IS');

                                            if($totalIS >= 0){
                                                ?>
                                                <input type="hidden" id="costoIS" value="<?php echo $costoIS ?>">
                                                <div class="col-md-3 col-12">
                                                    <a href="#" data-toggle="modal" data-target="#IS">
                                                        <div class="small-box bg-orange">
                                                            <div class="inner">
                                                                <h3>IS</h3>
                                                                <input type="hidden" id="cantidadIS" value="<?php echo $totalIS ?>">
                                                                <h5 >Cantidad: <?php echo $totalIS ?></h5>
                                                                <h5><?php
                                                                    if($costoIS == 0){
                                                                        echo $costoIS = '$ 0';
                                                                    }else{
                                                                        echo $costoIS = moneda_chilena($costoIS);
                                                                    }
                                                                    ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div><!-- Hojas Técnicas -->

                                                <div class="modal fade" id="IS" tabindex="-1" aria-labelledby="ISLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-orange">
                                                                <h5 class="modal-title" id="ISLabel">Estas visualizando los gastos asociados a Inspección/Supervisión  (IS)</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <table id="tabla-hojas-is" class="table table-hover  table-bordered table-sm">
                                                                    <thead class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'IS' ORDER BY fechaGasto DESC";
                                                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                                                    $num = 1;

                                                                    if($resEquipo && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                            $datos = [];
                                                                            $datos['idGasto'] = $rowEquipo['idGasto'];
                                                                            $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                                            $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                                            $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                                            $datos['nomMantenimiento'] = $rowEquipo['nomMantenimiento'];
                                                                            $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                                            $datos['tipoMantencion'] = $rowEquipo['tipoMantencion'];
                                                                            $datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                                            $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                                            $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                                            $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                                            $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                                            $idGasto = $datos['idGasto'];
                                                                            $fechaRegistro = $datos['fechaRegistro'];
                                                                            $mesGasto = $datos['mesGasto'];
                                                                            $fechaGasto = $datos['fechaGasto'];
                                                                            $nomMantenimiento = $datos['nomMantenimiento'];
                                                                            $idHojaTecnica = $datos['idHojaTecnica'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $solicitudCompra = $datos['solicitudCompra'];
                                                                            $codOrdenCompra = $datos['codOrdenCompra'];
                                                                            $costoTotal = $datos['costoTotal'];
                                                                            $encargadoMant = $datos['encargadoMant'];
                                                                            $idEquipo = $datos['idEquipo'];

                                                                            $formatoFecha = date('d/m/Y', strtotime($fechaGasto));


                                                                            $costoTotal = moneda_chilena($costoTotal);

                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $formatoFecha ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idHojaTecnica == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idHojaTecnica  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($encargadoMant == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $encargadoMant;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $idEquipo;
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }

                                                                    }

                                                                    $sqlHT = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = $idCarpeta  AND tipoMantencion	 = 'IS' ORDER BY fechaOrden DESC";
                                                                    $resHT = mysqli_query($conexion, $sqlHT);

                                                                    if($resHT && mysqli_affected_rows($conexion) > 0){

                                                                        while ($rowHT = $resHT->fetch_assoc()) {

                                                                            $datos = [];

                                                                            $datos['idOrden'] = $rowHT['idOrden'];
                                                                            $datos['fechaOrden'] = $rowHT['fechaOrden'];
                                                                            $datos['tipoMantencion'] = $rowHT['tipoMantencion'];
                                                                            $datos['folioOrden'] = $rowHT['folioOrden'];
                                                                            $datos['ticketAsociado'] = $rowHT['ticketAsociado'];
                                                                            $datos['nomTecnicoRecepcionador'] = $rowHT['nomTecnicoRecepcionador'];
                                                                            $datos['trabajoRealizado'] = $rowHT['trabajoRealizado'];
                                                                            $datos['idEquipo'] = $rowHT['idEquipo'];

                                                                            $idOrden = $datos['idOrden'];
                                                                            $idEquipo = $datos['idEquipo'];
                                                                            $fechaOrden = $datos['fechaOrden'];
                                                                            $tipoMantencion = $datos['tipoMantencion'];
                                                                            $folioOrden = $datos['folioOrden'];
                                                                            $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                            $ticketAsociado = $datos['ticketAsociado'];
                                                                            $trabajoRealizado = $datos['trabajoRealizado'];

                                                                            if($tipoMantencion == 'MPI'){
                                                                                $nomMantencion = 'MANTENCIÓN PREVENTIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MRI'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO INTERNO';
                                                                            }else if($tipoMantencion == 'MPE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'MRE'){
                                                                                $nomMantencion = 'MANTENCIÓN REPARATIVO EXTERNO';
                                                                            }else if($tipoMantencion == 'OM'){
                                                                                $nomMantencion = 'OBRAS MENORES';
                                                                            }else if($tipoMantencion == 'IS'){
                                                                                $nomMantencion = 'INSPECCIÓN Y/O SUPERVISIÓN';
                                                                            }

                                                                            $fechaOrden = date('d/m/Y', strtotime($fechaOrden));

                                                                            $solicitudCompra == '';
                                                                            ?>
                                                                            <tr >
                                                                                <td class="text-center"><?php echo $num ?></td>
                                                                                <td class="text-center"><?php echo $fechaOrden ?></td>
                                                                                <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                                                <td class="text-center"><?php echo $nomMantencion ?></td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($solicitudCompra == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action='../<?php echo $ruta ?>/soliCompra_visualizarSC_sdo.php' target="_blank" method="POST">
                                                                                            <input type="hidden" name="idSoliCompra" value="<?php echo $solicitudCompra  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    if ($idOrden == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST">
                                                                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden  ?>">
                                                                                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center text-success text-bold"><?php echo '$ 0' ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($nomTecnicoRecepcionador == '') {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, "UTF-8");
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if ($idEquipo == '' || $idEquipo == 0) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        ?>
                                                                                        <form action="equipo.php" method="post">
                                                                                            <input type="hidden" id="idCategoria" value="<?php echo $idCarpeta?>">
                                                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                                                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                                                        </form>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                            <?php
                                                                            $num++;
                                                                        }

                                                                    }


                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot class="bg-secondary">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">N°</th>
                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                        <th scope="col" class="text-center">Trabajo Realizado</th>
                                                                        <th scope="col" class="text-center">Solicitud Compra</th>
                                                                        <th scope="col" class="text-center">Hoja Técnica</th>
                                                                        <th scope="col" class="text-center">Monto</th>
                                                                        <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            //TICKET
                                            $totalTicket = obtieneCantidadTicketCarpeta($idCarpeta);
                                            if($totalTicket >= 0){
                                                ?>
                                                <div class="col-md-3 col-12">
                                                    <a href="#"  data-toggle="modal" data-target="#tickets">

                                                        <div class="small-box bg-gradient-indigo">
                                                            <div class="inner">
                                                                <h3>Ticket </h3>
                                                                <h5>Cerrados</h5>
                                                                <h5>Cantidad: <?php echo $totalTicket ?></h5>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-ticket-alt"></i>
                                                            </div>
                                                        </div>
                                                    </a>

                                                </div><!-- Hojas Técnicas -->
                                                <?php
                                            }
                                            ?>

                                            <!-- Modal -->
                                            <div class="modal fade" id="tickets" tabindex="-1" aria-labelledby="ticketsLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-purple">
                                                            <h5 class="modal-title" id="ticketsLabel">Estas visualizando las hojas técnicas con ticket cerrados</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <table id="tabla-tickets" class="table table-sm table-bordered table-hover">
                                                                <thead class="bg-secondary">
                                                                <tr>
                                                                    <th>
                                                                        <center>N°</center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Nombre<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Tecnico Recepcionador<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Ticket Asociado<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Equipo<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Acciones<center>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <?php
                                                                $query = "SELECT * FROM ordenes_trabajos_general  WHERE idCategoria = $idCarpeta AND ticketAsociado != '' AND ticketAsociado != '0' ORDER BY idOrden DESC;";

                                                                $res = mysqli_query($conexion, $query);

                                                                $num = 1;

                                                                while ($row = $res->fetch_assoc()) {

                                                                    $datos['idOrden'] = $row['idOrden'];
                                                                    $datos['nomOrden'] = $row['nomOrden'];
                                                                    $datos['nomTecnicoRecepcionador'] = $row['nomTecnicoRecepcionador'];
                                                                    $datos['ticketAsociado'] = $row['ticketAsociado'];
                                                                    $datos['folioOrden'] = $row['folioOrden'];

                                                                    $datos['idEquipo'] = $row['idEquipo'];

                                                                    $idOrden  = $datos['idOrden'];
                                                                    $nomOrden = $datos['nomOrden'];
                                                                    $nomTecnicoRecepcionador = $datos['nomTecnicoRecepcionador'];
                                                                    $ticketAsociado = $datos['ticketAsociado'];
                                                                    $folioOrden = $datos['folioOrden'];

                                                                    $idEquipo = $datos['idEquipo'];

//                                                                        //SQL EQUIPO
//                                                                        $queryEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
//                                                                        $resEquipo = mysqli_query($conexion, $queryEquipo);
//
//                                                                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
//                                                                            $datos1 = [];
//                                                                            $datos1['id'] = $rowEquipo['id'];
//                                                                            $datos1['identificadorUnico'] = $rowEquipo['identificadorUnico'];
//                                                                            $datos1['nombreEquipo'] = $rowEquipo['nombreEquipo'];
//                                                                            $identificadorUnico = $datos1['identificadorUnico'];
//                                                                            $id = $datos1['id'];
//                                                                            $nombreEquipo = $datos1['nombreEquipo'];
//                                                                        }


                                                                    ?>
                                                                    <tr>
                                                                        <td style="vertical-align:middle; font-size: 12px">
                                                                            <center><?php echo $num; ?></center>
                                                                        </td>
                                                                        <td style="vertical-align:middle; font-size: 12px" align="justify">
                                                                            <?php echo mb_convert_case($nomOrden, MB_CASE_UPPER, 'UTF-8');; ?>
                                                                        </td>
                                                                        <td style="vertical-align:middle; font-size: 12px" align="center">
                                                                            <?php echo mb_convert_case($nomTecnicoRecepcionador, MB_CASE_UPPER, 'UTF-8'); ?>
                                                                        </td>
                                                                        <td style="vertical-align:middle; font-size: 12px" align="center">
                                                                            <?php
                                                                            if ($ticketAsociado == '') {
                                                                                echo "SIN INFORMACIÓN";
                                                                            } else {
                                                                                echo mb_convert_case($ticketAsociado, MB_CASE_UPPER, 'UTF-8');
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td style="vertical-align:middle; font-size: 12px" align="center">

                                                                            <form action="equipo.php" method="POST" target="_blank">
                                                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                                    <b> <i class="far fa-eye"></i></b>
                                                                                </button>
                                                                            </form>


                                                                        </td>
                                                                        <td class="text-center">
                                                                            <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" target="_blank" method="POST" style="display: inline;">
                                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden ?>">
                                                                                <button type="submit" class="btn btn-sm btn-info "><i class="far fa-eye"></i></button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    <?php

                                                                    $num = $num + 1;
                                                                }
                                                                ?>



                                                                </tbody>
                                                                <tfoot class="bg-secondary">
                                                                <tr>
                                                                    <th>
                                                                        <center>N°</center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Nombre<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Tecnico Recepcionador<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Ticket Asociado<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Equipo<center>
                                                                    </th>
                                                                    <th>
                                                                        <center>Acciones<center>
                                                                    </th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Revisado!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">
                                                    <div class="inner">
                                                        <h3><?php echo $cantidadHojasTecnicas ?></h3>
                                                        <p><b>Hojas Tecnicas</b></p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-file-signature"></i>
                                                    </div>
                                                    <form action="carpeta-hojas-tecnicas.php" method="POST">
                                                        <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                        <button class="btn btn-primary btn-sm btn-block small-box-footer">Mas Información&nbsp;&nbsp;<i class="fas fa-eye"></i></button>
                                                    </form>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">
                                                        <h3><?php
                                                            if($gastoTotal == 0){
                                                                echo '$ 0';
                                                            }else{
                                                                echo $gastoTotal = moneda_chilena($gastoTotal);
                                                            }
                                                            ?></h3>
                                                        <p><b>Gastos</b> </p>
                                                    </div>
                                                    <input value="<?php echo $gastoTotal ?>" id="gastoTotal" type="hidden">
                                                    <div class="icon">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <form action="carpeta-gastos.php" method="POST">
                                                        <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                        <button class="btn btn-secondary btn-sm btn-block small-box-footer">Mas Información&nbsp;&nbsp;<i class="fas fa-eye"></i></button>
                                                    </form>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-danger">
                                                    <div class="inner">
                                                        <h3><?php echo $cuentaSoliCompra ?></h3>
                                                        <p> <b>Solicitudes de Compra</b> </p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </div>
                                                    <form action="carpeta-solicitudes-compra.php" method="POST">
                                                        <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                        <button class="btn btn-danger btn-sm btn-block small-box-footer">Mas Información&nbsp;&nbsp;<i class="fas fa-eye"></i></button>
                                                    </form>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                            <div class="col-md-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-pink">
                                                    <div class="inner">
                                                        <h3><?php echo $cuentaArchivos ?></h3>
                                                        <p> <b> Archivos Adjuntos</b></p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-archive"></i>
                                                    </div>
                                                    <form action="carpeta-archivos.php" method="POST">
                                                        <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                        <button class="btn bg-pink  btn-sm btn-block small-box-footer">Mas Información&nbsp;&nbsp;<i class="fas fa-eye"></i></button>
                                                    </form>
                                                </div>
                                            </div><!-- Hojas Técnicas -->
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row ">
                                    <div class="col-12">
                                        <div class="accordion" id="accordionExample">
                                            <div class="card">
                                                <div class="card-header bg-success" id="headingOne">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-block text-center text-white" style="font-size: 18px;" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            <b>Equipos Registrados</b>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <form action="agregar-equipo.php" method="POST">
                                                                    <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                    <button type="submit" class="btn btn-success"><b><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Añadir un nuevo equipo</b></button>
                                                                </form>
                                                                <br>
                                                                <div class="table-responsive">
                                                                    <table id="tabla-equipos" class="table text-center table-hover  table-bordered table-sm">
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
                                                                        <tbody></tbody>
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--
                                                <div class="card">
                                                    <div class="card-header bg-info" id="headingTwo">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-block text-center text-white collapsed" style="font-size: 18px;" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                <b> Programación</b>
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div id="container" style="width: 100%; height: 400px;"></div>
                                                            <input type="hidden" id="id" value="<?php //echo $idCarpeta ?>">

                                                        </div>
                                                    </div>
                                                </div>
                                                -->
                                            <!--
                                            <div class="card">

                                                <div class="card-header bg-secondary" id="headingThree">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-block text-center text-white collapsed" style="font-size: 18px;" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            <b>Estadisticas</b>
                                                        </button>
                                                    </h2>
                                                </div>

                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
                                                    </div>
                                                </div>

                                            </div>
                                            -->
                                        </div>
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
<script>
    $(document).ready(function() {
        let idCarpeta = document.getElementById('idCategoria').value;
        //console.log('Hola ' + idCarpeta);
        $('#tabla-equipos').DataTable({
            //console.log($('#idCategoria').val());
            "ajax": {
                              "url": "../sdo-personal/listar_equipos_personal.php",
                              "type": "POST",
                              "data": function(d) {
                                  d.idCarpeta = idCarpeta;
                              },
                              "dataSrc": ""
                          },
            "columns": [
                { "data": "id" },
                { "data": "nombreEquipo" },
                { "data": "recinto" },
                { "data": "pma" },
                { "data": "piso" },
                // Estado con color
                {
                    "data": "estadoEquipo",
                    "render": function(data, type, row) {

                        if (data === 'Operativo') {
                            return '<span class="font-weight-bold text-success"><i class="fas fa-check"></i> ' + data.toUpperCase() + '</span>';
                        }

                        if (data === 'Mantenimiento') {
                            return '<span class="font-weight-bold text-warning"><i class="fas fa-exclamation-triangle"></i> ' + data.toUpperCase() + '</span>';
                        }

                        if (data === 'OperativoObs') {
                            return '<span class="font-weight-bold text-orange"><i class="fas fa-exclamation-triangle"></i> OPERATIVO CON OBSERVACIONES</span>';
                        }

                        if (data === 'Extraviado') {
                            return '<span class="font-weight-bold text-purple"><i class="fas fa-question"></i> ' + data.toUpperCase() + '</span>';
                        }

                        return '<span class="font-weight-bold text-danger"><i class="fas fa-skull-crossbones"></i> ' + data.toUpperCase() + '</span>';
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {

                        return `
                        <form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php" target="_blank" method="POST" style="display:inline;">
                            <input type="hidden" name="idCarpeta" value="${data}">
                            <button type="submit" class="btn btn-primary btn-sm" title="Hoja de Vida">
                                <i class="far fa-file"></i>
                            </button>
                        </form>

                        <form action="equipo.php" method="POST" target="_blank" style="display:inline;">
                            <input type="hidden" name="idEquipo" value="${data}">
                            <input type="hidden" name="idCategoria" value="${row.idCategoria ?? ''}">
                            <button type="submit" class="btn btn-info btn-sm" title="Información">
                                <i class="fa-solid fa-door-open"></i>
                            </button>
                        </form>

                        <button class="btn btn-danger btn-sm eliminar" data-id="${data}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
            `;
                    }
                }
            ],
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

            initComplete: function() {
                this.api().columns([1]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Nombre Equipo</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([4]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Piso</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });

            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        let idCarpeta = document.getElementById('idCategoria').value;

        $('#tabla-hojas-mpi').DataTable({

            ajax: {
                url: "../sdo-personal/listar_equipos_personal_mpi.php",
                type: "POST",
                data: function(d) {
                    d.idCarpeta = idCarpeta;
                },
                dataSrc: ""
            },

            columns: [

                {
                    data: null,
                    render: function(data, type, row, meta){
                        return meta.row + 1;
                    }
                },

                { data: 'fecha' },

                { data: 'tipoMantencion' },

                { data: 'descripcion' },

                {
                    data: 'solicitudCompra',
                    render: function(data){

                        if(!data) return '-';

                        return `
                    <form action="../ruta/soliCompra_visualizarSC_sdo.php"
                          target="_blank"
                          method="POST">

                        <input type="hidden"
                               name="idSoliCompra"
                               value="${data}">

                        <button class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>
                    </form>
                `;
                    }
                },

                {
                    data: 'idOrden',
                    render: function(data){

                        if(!data) return '-';

                        return `
                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php"
                          target="_blank"
                          method="POST">

                        <input type="hidden"
                               name="idOrden"
                               value="${data}">

                        <button class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </button>
                    </form>
                `;
                    }
                },

                {
                    data: null,
                    render: function(){
                        return '<span class="text-success font-weight-bold">$ 0</span>';
                    }
                },

                {
                    data: 'encargado',
                    render: function(data){
                        return data ? data : '-';
                    }
                },

                {
                    data: 'idEquipo',
                    render: function(data){

                        if(!data || data == 0){
                            return '-';
                        }

                        return `
                    <form action="equipo.php" method="POST">
                        <input type="hidden" name="idEquipo" value="${data}">
                        <button type="submit"
                                class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </button>
                    </form>
                `;
                    }
                }

            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        let idCarpeta = document.getElementById('idCategoria').value;
        $('#tabla-equipo-observacion').DataTable({
            ajax:{
                url:'../sdo-personal/listar_equipos_fallas_personal.php',
                type:'POST',
                data:function(d){
                    d.idCarpeta = idCarpeta;
                },
                dataSrc:''
            },

            columns:[
                {data:'usuario'},
                {data:'nomEquipo'},
                {
                    data:'idEquipo',
                    render:function(data){

                        return `
                    <form method="POST" action="equipo.php">
                        <input type="hidden" name="idEquipo" value="${data}">
                        <button type="submit" class="btn btn-primary btn-xs">
                            <i class="fas fa-eye"></i>
                        </button>
                    </form>
                `;
                    }
                },
                {data:'fecha'},
                {data:'estado'},
                {data:'observaciones'}
            ],
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
    $(document).ready(function() {
        $('#tabla-tickets').DataTable({
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
    $(document).ready(function() {
        $('#tabla-hojas-is').DataTable({
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
    $(document).ready(function() {
        $('#tabla-hojas-mri').DataTable({
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
    $(document).ready(function() {
        $('#tabla-equipo-falla').DataTable({
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
    $(document).ready(function() {
        $('#tabla-gastos-mre').DataTable({
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
    $(document).ready(function() {
        $('#tabla-gastos-mpe').DataTable({
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
    $(document).ready(function() {
        $('#tabla_ht_mpe').DataTable({
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
    $(document).ready(function() {
        $('#tabla-hojas-om').DataTable({
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
    $(document).ready(function() {
        $('#tabla_ht_mre').DataTable({
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
    $(document).ready(
        $('.eliminar').click(function(e) {
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
                        url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro del equipo eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
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

    let id = document.getElementById('id').value;

    fetch('../sdo-funciones/obtener_tarea_programacion.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            let jsonData = JSON.stringify(data);
            console.log(jsonData);
            anychart.onDocumentReady(function () {
                anychart.format.inputLocale("en");

                // create a data tree
                var treeData = anychart.data.tree(jsonData, "as-tree");
                // create a chart
                var chart = anychart.ganttProject();
                // set the data
                chart.data(treeData);
                // set the container id
                chart.container("container");
                // initiate drawing the chart
                chart.draw();
                // fit elements to the width of the timeline
                chart.fitAll();
            });

        })
        .catch(error => {
            console.log('Error en la consulta Fetch API: ', error)
        })
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let gastoTotal = document.getElementById('gastoTotal').value;

    let costoMRE = document.getElementById('costoMRE').value;
    let costoMPI = document.getElementById('costoMPI').value;
    let costoMRI = document.getElementById('costoMRI').value;
    let costoMPE = document.getElementById('costoMPE').value;
    let costoOM = document.getElementById('costoOM').value;
    let costoIS = document.getElementById('costoIS').value;

    if(costoMRE === ""){
        costoMRE = "0";
    }

    costoMRE = parseInt(costoMRE);
    costoMPI = parseInt(costoMPI);
    costoMRI = parseInt(costoMRI);
    costoMPE = parseInt(costoMPE);
    costoOM = parseInt(costoOM);
    costoIS = parseInt(costoIS);

    console.log( costoMRE)

    if(costoMRE == 0 ){
        console.log('entre')
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['Sin Información', 1],
            ]);

            // Set chart options
            var options = {'title':' Estas visualizando un gráfico estándar, que no contiene información.',
                'width':400,
                'height':300,
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    }else{
        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');

            data.addRows([
                ['MPI', costoMPI],
                ['MRI', costoMRI],
                ['MPE', costoMPE],
                ['MRE', costoMRE],
                ['OM', costoOM],
                ['IS', costoIS],
            ]);

            // Set chart options
            var options = {'title':' Total: ' + gastoTotal  ,
                'width':400,
                'height':300,
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    }


    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.


    let cantidadMRE = document.getElementById('cantidadMRE').value;
    let cantidadMPI = document.getElementById('cantidadMPI').value;
    let cantidadMRI = document.getElementById('cantidadMRI').value;
    let cantidadMPE = document.getElementById('cantidadMPE').value;
    let cantidadOM = document.getElementById('cantidadOM').value;
    let cantidadIS = document.getElementById('cantidadIS').value;

    cantidadMRE = parseInt(cantidadMRE);
    cantidadMPI = parseInt(cantidadMPI);
    cantidadMRI = parseInt(cantidadMRI);
    cantidadMPE = parseInt(cantidadMPE);
    cantidadOM = parseInt(cantidadOM);
    cantidadIS = parseInt(cantidadIS);

    if(cantidadMRE == 0 && cantidadMPI == 0 && cantidadMRI == 0 && cantidadMPE == 0 && cantidadOM == 0 && cantidadIS == 0){

        console.log("Hola");

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['Sin Información', 1],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {title:'Estas visualizando un gráfico estándar, que no contiene información.',
                width:400,
                height:300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#566573']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }


    }else{

        google.charts.setOnLoadCallback(drawChartHojas);

        function drawChartHojas() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tipos');
            data.addColumn('number', 'Cantidad');
            data.addRows([
                ['MPI', cantidadMPI],
                ['MRI', cantidadMRI],
                ['MPE', cantidadMPE],
                ['MRE', cantidadMRE],
                ['OM', cantidadOM],
                ['IS', cantidadIS],
            ]);

            // Instantiate and draw our chart, passing in some options.
            var barchart_options = {title:'Cantidad de tipos de mantenciones',
                width:400,
                height:300,
                legend: 'none',
                'backgroundColor': 'transparent',
                colors: ['#5bc0de', '#d9534f', '#7c8184', '#59287a', '#2972fa', '#fab633']
            };
            var barchart = new google.visualization.PieChart(document.getElementById('grafico_barras'));
            barchart.draw(data, barchart_options);
        }
    }
</script>


</body>

</html>