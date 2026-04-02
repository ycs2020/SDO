<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión de usuario
if (!isset($_SESSION['idUsuario'], $_SESSION['idDepartamento'], $_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$rolUsuario = $_SESSION['rolUsuario'];

// Asignar departamento a 1 si es Administrador
if ($rolUsuario == 'Administrador') {
    $idDepartamento = 1;
}

// Función para obtener datos del vehículo
function obtenerDatosMovil($conexion, $idMovil) {
    $datos = [];

    // Consulta SQL segura con prepared statements
    $sql = "SELECT * FROM moviles_movilizacion WHERE idMovil = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idMovil);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $datos = $res->fetch_assoc();  // Solo es necesario hacer un fetch si hay una fila
    }

    $stmt->close();
    return $datos;
}

// Verificar si se envió idMovil por POST o si está en la sesión
$idMovil = null;

if (isset($_POST['idMovil'])) {
    $idMovil = trim($_POST['idMovil']);
    if (empty($idMovil)) {
        header('Location: administrar-moviles.php?noId');
        exit();
    } else {
        $_SESSION['idMovil'] = $idMovil;  // Guardar en sesión
    }
} elseif (isset($_SESSION['idMovil'])) {
    $idMovil = trim($_SESSION['idMovil']);
    if (empty($idMovil)) {
        header('Location: administrar-moviles.php?noId');
        exit();
    }
}

if ($idMovil) {
    $datos = obtenerDatosMovil($conexion, $idMovil);
    if (empty($datos)) {
        header('Location: administrar-moviles.php?noData=true');
        exit();
    }

    // Asignar los valores de $datos para usarlos en el resto del código
    $idMovil = $datos['idMovil'];
    $idVehiculo = $datos['idUnico'];
    $nomVehiculo = $datos['nomVehiculo'];
    $patenteVehiculo = $datos['patenteVehiculo'];
    $marcaVehiculo = $datos['marcaVehiculo'];
    $modeloVehiculo = $datos['modeloVehiculo'];
    $servicioVehiculo = $datos['servicioVehiculo'];
    $valorConIva = $datos['valorConIva'];
    $estadoVehiculo = $datos['estadoVehiculo'];
    $numInventario = $datos['numInventario'];
    $numChasis = $datos['numChasis'];
    $numMotor = $datos['numMotor'];
    $vidaUtil = $datos['vidaUtil'];
    $fabricante = $datos['frabricante'];
    $fechaCompra = $datos['fechaCompra'];
    $ordenCompra = $datos['ordenCompra'];
    $estadoVehiculo = $datos['estadoVehiculo'];
} else {
    header('Location: administrar-moviles.php?noId');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Equipo</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
    <script src="../plugins/moment/moment-with-locales.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha1 {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';

    if(isset($_GET['exitoModificar'])){
        ?>
        <script>
            Swal.fire(
                'Bien!',
                'Se actualizo correctamente el registro!',
                'success'
            )
        </script>
        <?php
    }

    if(isset($_GET['errorModificar'])){
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Error al actualizar el registro!',
                'success'
            )
        </script>
        <?php
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <br>
        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <a href="administrar-moviles.php" style="float: right;" class="btn btn-danger "><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                <button class="btn bg-success show-verdash animate__animated animate__tada" id="verDashAC" name="verDashAC"> <b> <i class="fas fa-eye"></i>&nbsp;&nbsp;Ver Dashboard</b></button>
                                <button class="btn bg-red show-ocultardash animate__animated animate__tada" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"> <b><i class="fa fa-eye-slash"></i>&nbsp;&nbsp;Ocultar Dashboard</b> </button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">
                    <div class="col-12 col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Fechas Últimos Reportes:</legend>
                                    <?php
                                    function obtieneFechaEstado($estado)
                                    {
                                        include '../sdo-funciones/conexion.php';
                                        $sqlFechaEstados = "SELECT fechaReporte FROM historial_estados_moviles WHERE estadoMovil = '$estado' AND idMovil = '$idMovil' " ;
                                        $resFechaEstados = mysqli_query($conexion, $sqlFechaEstados);
                                        $dato = mysqli_fetch_assoc($resFechaEstados);
                                        $fechaReporte = $dato['fechaReporte'];
                                        $fechaReporte = strtotime($fechaReporte);
                                        $fechaReporte = date('d/m/Y', $fechaReporte);
                                        return  $fechaReporte;
                                    }
                                    ?>
                                    <div class="row justify-content-center text-center">
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <h3 style="font-size: 14px;font-weight:bold" class="text-success">OPERATIVA:
                                                <?php
                                                $fecha =  obtieneFechaEstado('OPERATIVA');
                                                if ($fecha == '31/12/1969') {
                                                    $fecha = '-';
                                                }

                                                echo $fecha;
                                                ?>
                                            </h3>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <h3 style="font-size: 14px;font-weight:bold" class="text-warning">MANTENCIÓN PREVENTIVA:
                                                <?php
                                                $fecha =  obtieneFechaEstado('MANTENCION PREVENTIVA');
                                                if ($fecha == '31/12/1969') {
                                                    $fecha = '-';
                                                }

                                                echo $fecha;
                                                ?>
                                            </h3>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <h3 style="font-size: 14px;font-weight:bold" class="text-danger">MANTENCIÓN CORRECTIVA:
                                                <?php
                                                $fecha =  obtieneFechaEstado('MANTENCION CORRECTIVA');
                                                if ($fecha == '31/12/1969') {
                                                    $fecha = '-';
                                                }

                                                echo $fecha;
                                                ?>

                                            </h3>
                                        </div>
                                    </div>
                                </fieldset>
                                <br>
                                <div class="justify-content-center text-center align-items-center">
                                    <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                                        <b>Registro Estados</b>
                                    </button>
                                    <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collpaseGasolina" aria-expanded="false" aria-controls="collpaseGasolina">
                                        <b>Registro Petróleo</b>
                                    </button>
                                    <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseKilometraje" aria-expanded="false" aria-controls="collapseKilometraje">
                                        <b>Registro Kilometraje</b>
                                    </button>
                                    <br>
                                    <button class="btn btn-info mt-3" data-toggle="modal" data-target=".generarInformeResumen"><b>Generar Informe</b></button>

                                    <form action="../sdo-pdf/SDO-Reporte-Moviles.php" method="POST" id="formulario">
                                        <div class="modal fade generarInformeResumen" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title"> <b>Generar Informe </b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Seleccione archivo a generar:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" required id="archivoAGenerar" name="archivoAGenerar">
                                                                    <option value="" selected disabled>-- Seleccione --</option>
                                                                    <option value="Registro kilometrico">Registro kilometrico</option>
                                                                    <option value="Registro del petroleo">Registro del petróleo</option>
                                                                    <option value="Registro de los estados">Registro de los estados</option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <input type="hidden" name="idMovil" id="idMovil" value="<?php echo $idMovil ?>">
                                                        <input type="hidden" name="idUnico" id="idUnico" value="<?php echo $idVehiculo ?>">

                                                        <div class="form-group">
                                                            <h6 class="tex-center">Escoja el periodo</h6>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios1" value="mensual">
                                                                <label class="form-check-label" for="exampleRadios1">
                                                                    Mensual
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios3" value="todos">
                                                                <label class="form-check-label" for="exampleRadios3">
                                                                    Todos
                                                                </label>
                                                            </div>
                                                        </div>


                                                        <div class="row" id="mesInforme">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Escoja el mes:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <select type="text" class="form-control form-control-sm" required id="mensual" name="mesInforme">
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
                                                            </div>
                                                        </div>

                                                        <div class="row" id="rangoFecha1">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="date" class="form-control form-control-sm" required id="fechaInicio1" name="fechaInicio1">

                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="date" class="form-control form-control-sm" onchange="ValidarFechas()" required id="fechaFin1" name="fechaFin1">

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" id="enviar" class="btn btn-primary"> <b>Generar Reporte</b> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <br>
                                <br>
                                <div class="collapse width" id="collapseWidthExample">
                                    <div class="card ">
                                        <div class="card-header">
                                            <h6><b> <i class="fas fa-hand-pointer"></i> - Registros de los estados - <i class="fas fa-chart-bar"></i></b></h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center justify-content-center">
                                                <button class="btn btn-success mb-3 " data-toggle="modal" data-target="#estadoModal"><b><i class="fas fa-plus"></i> Añadir Nuevo Estado</b></button>
                                            </div>
                                            <br>
                                            <div class=" col-12 justify-content-center ">
                                                <table class="table  table-sm  table-bordered " id="table_estado">
                                                    <thead class="text-center bg-danger">
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Motivo</th>
                                                    </thead>
                                                    <tbody class="text-center">
                                                    <?php
                                                    $sqlHistEstados = "SELECT * FROM historial_estados_moviles WHERE idMovil = $idMovil ORDER BY fechaReporte DESC";
                                                    $resHistEstados = mysqli_query($conexion, $sqlHistEstados);
                                                    while ($rowHistEstados = $resHistEstados->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['fechaReporte'] = $rowHistEstados['fechaReporte'];
                                                        $datos['estadoMovil'] = $rowHistEstados['estadoMovil'];
                                                        $datos['motivo'] = $rowHistEstados['motivo'];
                                                        $fechaReporte = $datos['fechaReporte'];
                                                        $estadoMovil = $datos['estadoMovil'];
                                                        $motivo = $datos['motivo'];
                                                        $fechaReporte = strtotime($fechaReporte);
                                                        // if ($estadoMovil == 'OPERATIVO' || $estadoMovil == 'operativo') {
                                                        //     $color = 'bg-success';
                                                        // } else if ($estadoMovil == 'MANTENIMIENTO') {
                                                        //     $color = 'bg-warning';
                                                        // } else {
                                                        //     $color = 'bg-danger';
                                                        // }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo date('d/m/Y H:i', $fechaReporte) ?></td>
                                                            <td><?php echo strtoupper($estadoMovil) ?></td>
                                                            <td><?php echo strtoupper($motivo) ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                    <tfoot class="text-center bg-danger">
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Motivo</th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse width" id="collpaseGasolina">
                                    <div class="card card-body">
                                        <div class=" col-12 justify-content-center ">
                                            <table class="table  table-sm  table-bordered" id="table_gasolina">
                                                <thead class="  text-center bg-warning">
                                                <tr>
                                                    <th scope="col" class="text-center">Fecha</th>
                                                    <th scope="col" class="text-center">N° Guía</th>
                                                    <th scope="col" class="text-center">Litros</th>
                                                    <th scope="col" class="text-center">Valor Unitario</th>
                                                    <th scope="col" class="text-center">Total Sin Iva</th>
                                                    <th scope="col" class="text-center">IVA</th>
                                                    <th scope="col" class="text-center">Total</th>
                                                    <th scope="col" class="text-center">Acciones</th>
                                                </tr>

                                                </thead>
                                                <tbody class="text-center">
                                                <?php
                                                $sqlHistKilometro = "SELECT * FROM historial_petroleo_moviles WHERE idMovil = $idMovil ORDER BY fechaRegistro DESC";
                                                $resHistKilometros = mysqli_query($conexion, $sqlHistKilometro);
                                                while ($rowHistKilometros = $resHistKilometros->fetch_assoc()) {
                                                    $datos = [];
                                                    $datos['idPetroleo'] = $rowHistKilometros['idPetroleo'];
                                                    $datos['fechaRegistro'] = $rowHistKilometros['fechaRegistro'];
                                                    $datos['numGuia'] = $rowHistKilometros['numGuia'];
                                                    $datos['cantidadLitros'] = $rowHistKilometros['cantidadLitros'];
                                                    $datos['valorUnitario'] = $rowHistKilometros['valorUnitario'];
                                                    $datos['totalSinIva'] = $rowHistKilometros['totalSinIva'];
                                                    $datos['IvaCalculado'] = $rowHistKilometros['IvaCalculado'];
                                                    $datos['totalConIva'] = $rowHistKilometros['totalConIva'];
                                                    $idPetroleo = $rowHistKilometros['idPetroleo'];
                                                    $fechaRegistro = $datos['fechaRegistro'];
                                                    $numGuia = $datos['numGuia'];
                                                    $cantidadLitros = $datos['cantidadLitros'];
                                                    $valorUnitario = $datos['valorUnitario'];
                                                    $totalSinIva = $datos['totalSinIva'];
                                                    $IvaCalculado = $datos['IvaCalculado'];
                                                    $totalConIva = $datos['totalConIva'];
                                                    $fechaRegistro = strtotime($fechaRegistro);

                                                    $valorUnitario = moneda_chilena($valorUnitario);
                                                    $totalSinIva = moneda_chilena($totalSinIva);
                                                    $IvaCalculado = moneda_chilena($IvaCalculado);
                                                    $totalConIva = moneda_chilena($totalConIva);

                                                    $cantidadLitros = (float)$cantidadLitros;


                                                    ?>
                                                    <tr>
                                                        <td><?php echo date('d/m/Y', $fechaRegistro) ?></td>
                                                        <td><?php echo strtoupper($numGuia) ?></td>
                                                        <td><?php echo $cantidadLitros ?></td>
                                                        <td><?php echo $valorUnitario ?></td>
                                                        <td><?php echo $totalSinIva ?></td>
                                                        <td><?php echo $IvaCalculado ?></td>
                                                        <td><?php echo $totalConIva ?></td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm editarPetroleo" id="<?php echo $idPetroleo  ?> "  data-toggle="modal" data-target="#editarPetroleo"><i class="fas fa-pen"></i></button>
                                                            <button id="<?php echo $idPetroleo ?> " class="btn btn-danger btn-sm eliminarPetroleo"><i class="fas fa-trash"></i></button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="editarPetroleo" tabindex="-1" aria-labelledby="editarPetroleoLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title" id="editarRegistroLabel">Editando el registro del petroleo</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="../sdo-funciones/guardar-edicion-petroleo.php" method="POST">
                                                                                <input type="hidden" name="idPetroleo" id="idPetroleoEditar" value="">
                                                                                <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                                                <div class="form-row">
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12px">Fecha Reporte:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                            </div>
                                                                                            <input type="date" class="form-control form-control-sm" required id="fechaReportePet"  name="fechaReporte">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12" id="contenedorGuia">
                                                                                        <label for="codigoBarra" style="font-size:12px">N° Guia:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fa-solid fa-ticket"></i></span>
                                                                                            </div>
                                                                                            <input type="text" class="form-control form-control-sm" required id="numGuiaPet" name="numGuia">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12px">Litros:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fa-solid fa-gas-pump"></i></span>
                                                                                            </div>
                                                                                            <input type="number" name="litros" step="any" id="litrosPet" class="form-control form-control-sm">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12px">Valor Unitario:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                                                                            </div>
                                                                                            <input type="number" name="valorUnitario" id="valorUnitarioPet" class="form-control form-control-sm">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                                            <button type="submit" class="btn btn-success"> <b> Guardar Cambios</b></button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="text-center bg-warning">
                                                <tr>
                                                    <th scope="col" class="text-center">Fecha</th>
                                                    <th scope="col" class="text-center">N° Guía</th>
                                                    <th scope="col" class="text-center">Litros</th>
                                                    <th scope="col" class="text-center">Valor Unitario</th>
                                                    <th scope="col" class="text-center">Total Sin Iva</th>
                                                    <th scope="col" class="text-center">IVA</th>
                                                    <th scope="col" class="text-center">Total</th>
                                                    <th scope="col" class="text-center">Acciones</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse width" id="collapseKilometraje">
                                    <div class="card card-body">
                                        <div class=" col-12 justify-content-center ">


                                            <br>
                                            <h5>&nbsp;&nbsp; Colores para Identificar:</h5><br>
                                            <ul>
                                                <li><i style="color: #ffff00; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Inicio Proximo Mantenimiento</li>

                                            </ul>

                                            <br>
                                            <table class="table  table-sm  table-bordered" id="table_kilometro">
                                                <thead class="  text-center bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">Fecha Registro</th>
                                                    <th scope="col" class="text-center">Kilometraje</th>
                                                    <th scope="col" class="text-center">Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                <?php
                                                $sqlHistKilometro = "SELECT * FROM historial_kilometraje_moviles WHERE idMovil = $idMovil ORDER BY fechaRegistro DESC";
                                                $resHistKilometros = mysqli_query($conexion, $sqlHistKilometro);
                                                while ($rowHistKilometros = $resHistKilometros->fetch_assoc()) {
                                                    $datos = [];
                                                    $datos['idKilometraje'] = $rowHistKilometros['idKilometraje'];
                                                    $datos['fechaRegistro'] = $rowHistKilometros['fechaRegistro'];
                                                    $datos['cantKilometraje'] = $rowHistKilometros['cantKilometraje'];
                                                    $datos['registroMantenimiento'] = $rowHistKilometros['registroMantenimiento'];

                                                    $idKilometraje = $datos['idKilometraje'];
                                                    $fechaRegistro = $datos['fechaRegistro'];
                                                    $cantKilometraje = $datos['cantKilometraje'];
                                                    $cantKilometraje = number_format($cantKilometraje, 0, ",", ".");

                                                    $fechaRegistro = strtotime($fechaRegistro);

                                                    $registroMantenimiento = $datos['registroMantenimiento'];

                                                    if($registroMantenimiento == 1){
                                                        $colorTR = '#ffff00';
                                                    }else{
                                                        $colorTR = '#ffffff';
                                                    }
                                                    ?>
                                                    <tr style="background-color: <?php echo $colorTR; ?>">
                                                        <td><?php echo date('d/m/Y', $fechaRegistro) ?></td>
                                                        <td><?php echo strtoupper($cantKilometraje) ?></td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm editar" id="<?php echo $idKilometraje  ?> "  data-toggle="modal" data-target="#editarRegistro"><i class="fas fa-pen"></i></button>
                                                            <button id="<?php echo $idKilometraje ?> " class="btn btn-danger btn-sm eliminar"><i class="fas fa-trash"></i></button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="editarRegistro" tabindex="-1" aria-labelledby="editarRegistroLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title" id="editarRegistroLabel">Editando el registro</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="../sdo-funciones/guardar-edicion-kilometraje.php" method="POST">
                                                                                <input type="hidden" name="idKilometraje" id="idKilometrajeEditar" value="">
                                                                                <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="codigoBarra" style="font-size:12px">Fecha Reporte:<code>*</code></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="date" class="form-control form-control-sm" required id="fechaReporte" value="" name="fechaReporte">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="codigoBarra" style="font-size:12px">Kilometraje:<code>*</code></label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                                                                        </div>
                                                                                        <input type="number" step="1" class="form-control form-control-sm" required id="numKilometraje" name="numKilometraje">
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                                            <button type="submit" class="btn btn-success"> <b> Guardar Cambios</b></button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="text-center bg-secondary">
                                                <tr>
                                                    <th scope="col" class="text-center">Fecha Registro</th>
                                                    <th scope="col" class="text-center">Kilometraje</th>
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
                </div>
                <!-- general form elements -->
                <div class="card card-info">
                    <div class="card-header">
                        <h1 class="card-title" style="font-size:15px"> <b>Visualizando Movil - <?php echo $nomVehiculo ?></b></h1>
                    </div>
                    <!-- AVISOS -->

                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row col-md-6 col-12">
                                    <h5 class="text-info" style="font-size:18; font-weight:bold">Información General del vehículo</h5>
                                    <br>
                                    <br>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label text-primary" for="customSwitch1">Click Aqui para habilitar o deshabilitar la edición de la información del movil</label>
                                    </div>
                                    <br>
                                </div>
                                <?php

                                $obtenerUltimoRegistroMantenimiento = obtenerUltimoRegistroMantenimiento($idMovil);

                                $ultRegMantDiezMil = $obtenerUltimoRegistroMantenimiento + 10000;

                                $obtenerUltimoRegistroKilometraje = obtenerUltimoRegistroKilometraje($idMovil);

                                $diferenciaRegistros = $ultRegMantDiezMil - $obtenerUltimoRegistroKilometraje;


                                $obtenerUltimoRegistroKilometraje2 = number_format($obtenerUltimoRegistroKilometraje, 0, ",", ".");
                                $diferenciaRegistros2 = number_format($diferenciaRegistros, 0, ",", ".");

                                if($diferenciaRegistros > 4999){
                                    ?>
                                    <div class="alert bg-green text-center  alert-dismissible fade show " role="alert">
                                        <b>Ultimo Kilometraje Registrado: <?php echo $obtenerUltimoRegistroKilometraje2; ?> . Quedan  <?php echo $diferenciaRegistros2; ?> Kms. para su Proximo Mantenimiento. </b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }

                                if($diferenciaRegistros < 5000 && $diferenciaRegistros > 2999){
                                    ?>
                                    <div class="alert bg-yellow text-center  alert-dismissible fade show " role="alert">
                                        <b>Ultimo Kilometraje Registrado: <?php echo $obtenerUltimoRegistroKilometraje2; ?> . Quedan  <?php echo $diferenciaRegistros2; ?> Kms. para su Proximo Mantenimiento. </b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }

                                if($diferenciaRegistros < 3000 && $diferenciaRegistros > 0){
                                    ?>
                                    <div class="alert bg-red text-center  alert-dismissible fade show " role="alert">
                                        <b>Ultimo Kilometraje Registrado: <?php echo $obtenerUltimoRegistroKilometraje2; ?> . Quedan  <?php echo $diferenciaRegistros2; ?> Kms. para su Proximo Mantenimiento. </b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }




                                if (isset($_GET['exitoKilometraje'])) {
                                    ?>
                                    <div class="alert alert-success text-center  alert-dismissible fade show " role="alert">
                                        <b> Se guardo correctamente el registro del kilometraje</b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }


                                if (isset($_GET['errorKilometraje'])) {
                                    ?>
                                    <div class="alert alert-danger text-center  alert-dismissible fade show " role="alert">
                                        <b> Error al guardar el registro del kilometraje</b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }

                                if (isset($_GET['exitoPetroleo'])) {
                                    ?>
                                    <div class="alert alert-success text-center  alert-dismissible fade show " role="alert">
                                        <b> Se guardo correctamente el registro del petroleo</b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }



                                if (isset($_GET['errorPetroleo'])) {
                                    ?>
                                    <div class="alert alert-danger text-center  alert-dismissible fade show " role="alert">
                                        <b> Error al guardar el registro del petroleo</b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }

                                if (isset($_GET['noDatos'])) {
                                    ?>
                                    <div class="alert alert-warning text-center  alert-dismissible fade show " role="alert">
                                        <b> No se encontraron datos para el periodo seleccionado</b>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <?php
                                }




                                ?>
                                <div class="row col-12 justify-content-center align-items-center">
                                    <div class="col-md-6 col-12 ">
                                        <form id="form">
                                            <fieldset id="fielset" class="border p-3 shadow p-3 mb-5  animate__animated  animate__zoomIn rounded " disabled>
                                                <div class="form-row">
                                                    <div class="form-group  col-12 col-md-12">
                                                        <label for="codigoBarra" style="font-size:12px">ID:<code>*</code></label>
                                                        <div class="input-group mb-3 ">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-id-card"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm " required id="idVehiculo" name="idVehiculo" value="<?php echo $idVehiculo ?>" placeholder="Escriba el id">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-12 col-md-12">
                                                        <label for="codigoBarra" style="font-size:12px">Nombre:<code>*</code></label>
                                                        <div class="input-group mb-3 ">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-id-card"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm " required id="nomVehiculo" name="nomVehiculo" value="<?php echo $nomVehiculo ?>" placeholder="Escriba el nombre">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Patente:<code>*</code></label>
                                                        <div class="input-group mb-3 ">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="patenteVehiculo" value="<?php echo $patenteVehiculo ?>" name="patenteVehiculo" placeholder="Escriba la patente">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Marca:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-car-alt"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="marcaVehiculo" value="<?php echo $marcaVehiculo ?>" name="marcaVehiculo" placeholder="Escriba la marca">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Modelo:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-car-alt"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="modeloVehiculo" value="<?php echo $modeloVehiculo ?>" name="modeloVehiculo" placeholder="Escriba el modelo">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">N° de Inventario:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="numInventario" value="<?php echo $numInventario ?>" name="numInventario" placeholder="Escriba la marca">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Servicio:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-hospital"></i></span>
                                                            </div>
                                                            <select name="servicioVehiculo" id="servicioVehiculo" class="form-control form-control-sm">
                                                                <?php
                                                                $sqlServicios = "SELECT * FROM servicios";
                                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                                    $datosServicios = [];
                                                                    $datosServicios['idServicio'] = $rowServicios['idServicio'];
                                                                    $datosServicios['nomServicio'] = $rowServicios['nomServicio'];
                                                                    $idServicio = $datosServicios['idServicio'];
                                                                    $nomServicio = $datosServicios['nomServicio'];

                                                                    if ($servicioVehiculo == $nomServicio) {
                                                                        ?>
                                                                        <option value="<?php echo $nomServicio ?>" selected><?php echo $nomServicio ?></option>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <option value="<?php echo $nomServicio ?>"><?php echo $nomServicio ?></option>
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
                                                        <label for="codigoBarra" style="font-size:12px">N° de Chasis:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="numChasis" name="numChasis" value="<?php echo $numChasis ?>" placeholder="Escriba N° Chasis">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">N° de Motor:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="numMotor" name="numMotor" value="<?php echo $numMotor ?>" placeholder="Escriba N° Motor">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Fabricante:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="frabricante" name="frabricante" value="<?php echo $fabricante ?>" placeholder="Escriba nombre fabricante">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Fecha de Compra:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="fechaCompra" value="<?php echo $fechaCompra ?>" name="fechaCompra">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Orden de Compra:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="ordenCompra" value="<?php echo $ordenCompra ?>" name="ordenCompra" placeholder="Escriba orden de compra">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="codigoBarra" style="font-size:12px">Vida Útil:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" id="vidaUtil" name="vidaUtil" value="<?php echo $vidaUtil ?>" placeholder="Indique la vida útil">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row justify-content-center">
                                                    <div class="form-group col-md-6">
                                                        <label for="codigoBarra" style="font-size:12px">Valor con IVA $:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                                            </div>
                                                            <input type="number" class="form-control form-control-sm" value="<?php echo $valorConIva ?>" id="valorConIva" name="valorConIva">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="idMovil" name="idMovil" value="<?php echo $idMovil ?>">
                                                <button type="submit" class="btn btn-sm  justify-content-center  btn-primary"><b>Guardar Cambios</b> </button>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class=" row col-md-6 col-12 align-items-center">
                                        <div class="card  text-white bg-primary mb-3 mr-1 animate__animated animate__backInDown" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-file-pdf"></i> Visualizar Hoja de Vida </b> </h5>
                                                <p class="card-text"> <b>Haga Click para visualizar la hoja de vida del movil.</b> </p>
                                            </div>
                                            <?php
                                            $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
                                            //
                                            // Obtén la IP del cliente
                                            $ipCliente = $_SERVER['REMOTE_ADDR'];
                                            //
                                            /// // Verifica si la IP del cliente es la permitida


//                                            if ($ipCliente === $ipPermitida) {
//                                                $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php';
//                                            }else{
//                                                $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil.php';
//
//                                            }

                                            $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php';

                                            ?>
                                            <form action="<?php echo $rutaVida; ?>" target="_blank" method="POST">
                                                <input type="hidden" value="<?php echo $idMovil ?>" name="idMovil">
                                                <button type="submit" class="btn btn-primary btn-block card-footer"><i class="fas fa-eye"></i><b> Visualizar</b> </button>
                                            </form>
                                        </div>
                                        <div class="card  text-white bg-secondary mb-3 mr-1 animate__animated animate__backInDown" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"><b><i class="fas fa-tachometer-alt"></i> Kilometraje</b> </h5>
                                                <p class="card-text"> <b>Haga Click en el lapiz para registrar el kilometraje diario.</b> </p>
                                            </div>
                                            <a href="#" data-toggle="modal" data-target="#kilometrajeModal" class="btn btn-secondary card-footer"><i class="fas fa-pen"></i><b> Registrar</b> </a>
                                        </div>
                                        <div class="card  text-white bg-warning mb-3 mr-1 animate__animated animate__backInLeft" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-gas-pump"></i> Petróleo</b> </h5>
                                                <p class="card-text"> <b>Haga Click en el lapiz para registrar la gasolina diaria.</b> </p>
                                            </div>
                                            <a href="#" data-toggle="modal" data-target="#gasolinaModal" class="btn btn-warning card-footer"><i class="fas fa-pen"></i> <b>Registrar</b> </a>
                                        </div>
                                        <div class="card  text-white bg-danger mb-3 mr-1 animate__animated animate__backInRight" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-battery-three-quarters"></i> Estado</b> </h5>
                                                <p class="card-text"> <b>Haga Click en el lapiz para registrar el nuevo estado.</b> </p>
                                            </div>
                                            <button type="button" data-toggle="modal" data-target="#estadoModal" class="btn btn-danger card-footer"><i class="fas fa-pen"></i> <b>Registrar</b> </button>
                                        </div>
                                        <!-- <div class="card  text-white bg-danger mb-3 mr-1 animate__animated animate__backInLeft" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-gas-pump"></i> Gasolina</b> </h5>
                                                <p class="card-text"> <b>Haga Click aquí en el lapiz para ingresar la gasolina diaria.</b> </p>
                                            </div>
                                            <a href="#" class="btn btn-danger card-footer"><i class="fas fa-eye"></i> <b>Visualizar</b> </a>
                                        </div>
                                        <div class="card  text-white bg-info mb-3 mr-1 animate__animated animate__backInLeft" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-gas-pump"></i> Gasolina</b> </h5>
                                                <p class="card-text"> <b>Haga Click aquí en el lapiz para ingresar la gasolina diaria.</b> </p>
                                            </div>
                                            <a href="#" class="btn btn-info card-footer"><i class="fas fa-eye"></i> <b>Visualizar</b> </a>
                                        </div>
                                        <div class="card  text-white bg-orange mb-3 mr-1 animate__animated animate__backInLeft" style="width: 18rem;">
                                            <div class="card-body">
                                                <h5 class="card-title"> <b><i class="fas fa-gas-pump"></i> Gasolina</b> </h5>
                                                <p class="card-text"> <b>Haga Click aquí en el lapiz para ingresar la gasolina diaria.</b> </p>
                                            </div>
                                            <a href="#" class="btn btn-orange card-footer"><i class="fas fa-eye"></i> <b>Visualizar</b> </a>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="modal fade" id="gasolinaModal" tabindex="-1" aria-labelledby="gasolinaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title" id="gasolinaModalLabel"> <b>Nuevo Registro - Petróleo</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../sdo-funciones/guardar-petroleo-moviles.php" method="POST">
                                                    <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Fecha Reporte:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm" required id="fechaReporte" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12" id="contenedorGuia">
                                                            <label for="codigoBarra" style="font-size:12px">N° Guia:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fa-solid fa-ticket"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" required id="numGuia" name="numGuia">

                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Litros:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fa-solid fa-gas-pump"></i></span>
                                                                </div>
                                                                <input type="number" name="litros" step="any" id="litros" class="form-control form-control-sm">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Valor Unitario:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                                                </div>
                                                                <input type="number" name="valorUnitario" id="valorUnitario" class="form-control form-control-sm">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                <button type="submit" id="guardarPetroleo" disabled class="btn btn-success"> <b>Guardar Petróleo</b> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="kilometrajeModal" tabindex="-1" aria-labelledby="kilometrajeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-secondary">
                                                <h5 class="modal-title" id="kilometrajeModalLabel"> <b>Nuevo Registro - Kilometraje</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../sdo-funciones/guardar-kilometraje-moviles.php" method="POST">
                                                    <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                    <input type="hidden" name="estadoVehiculo" value="<?php echo $estadoVehiculo ?>">

                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Fecha Reporte:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm" required id="fechaReporte" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Kilometraje:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                                                </div>
                                                                <input type="number" step="1" class="form-control form-control-sm" required id="numKilometraje" name="numKilometraje">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Registro Mantenimiento:</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="registroMantenimiento" value="1">
                                                                <label for="customCheckbox1" class="custom-control-label">Calcular Proximo Mantenimiento</label>
                                                            </div>
                                                        </div>


                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                <button type="submit" class="btn btn-success"> <b>Guardar Kilometraje</b> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="estadoModal" tabindex="-1" aria-labelledby="estadoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title" id="estadoModalLabel"> <b>Nuevo Estado</b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../sdo-funciones/guardar-estado-moviles.php" method="POST">
                                                    <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Fecha Reporte:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="date" class="form-control form-control-sm" disabled id="fechaReporte" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Estado:<code></code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" id="estadoMovil" required name="estadoMovil" placeholder="Escriba orden de compra">
                                                                    <option value="" disabled selected>--Seleccione --</option>
                                                                    <option value="Operativa">Operativa</option>
                                                                    <option value="Mantencion Preventiva">Mantención Preventiva</option>
                                                                    <option value="Mantencion Correctiva">Mantención Correctiva</option>
                                                                    <option value="Debaja">Debaja</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="codigoBarra" style="font-size:12px">Motivo:</label>
                                                            <div class="input-group mb-3">
                                                                <textarea rows="5" class="form-control form-control-sm" id="motivo" name="motivo" placeholder="Explique el motivo del nuevo estado..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar Modal</b> </button>
                                                <button type="submit" class="btn btn-success"> <b>Guardar Estado</b> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div><!-- .tabla-responsive -->
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>
    <!--/.col  -->
    </section>
</div>
<!-- /.row -->
<!-- /.content -->
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
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(document).ready(function() {
        $('#table_kilometro').DataTable({
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
            // initComplete: function() {
            //     this.api().columns([3]).every(function() {
            //         var column = this;
            //         var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
            //             .appendTo($(column.header()).empty())
            //             .on('change', function() {
            //                 var val = $.fn.dataTable.util.escapeRegex(
            //                     $(this).val()
            //                 );

            //                 column
            //                     .search(val ? '^' + val + '$' : '', true, false)
            //                     .draw();
            //             });

            //         column.data().unique().sort().each(function(d, j) {
            //             select.append('<option value="' + d + '">' + d + '</option>')
            //         });
            //     });


            // }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#table_estado').DataTable({
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
            // initComplete: function() {
            //     this.api().columns([3]).every(function() {
            //         var column = this;
            //         var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
            //             .appendTo($(column.header()).empty())
            //             .on('change', function() {
            //                 var val = $.fn.dataTable.util.escapeRegex(
            //                     $(this).val()
            //                 );

            //                 column
            //                     .search(val ? '^' + val + '$' : '', true, false)
            //                     .draw();
            //             });

            //         column.data().unique().sort().each(function(d, j) {
            //             select.append('<option value="' + d + '">' + d + '</option>')
            //         });
            //     });


            // }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#table_gasolina').DataTable({
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
            // initComplete: function() {
            //     this.api().columns([3]).every(function() {
            //         var column = this;
            //         var select = $('<select style="width: 40%;"><option value="">Servicio</option></select>')
            //             .appendTo($(column.header()).empty())
            //             .on('change', function() {
            //                 var val = $.fn.dataTable.util.escapeRegex(
            //                     $(this).val()
            //                 );

            //                 column
            //                     .search(val ? '^' + val + '$' : '', true, false)
            //                     .draw();
            //             });

            //         column.data().unique().sort().each(function(d, j) {
            //             select.append('<option value="' + d + '">' + d + '</option>')
            //         });
            //     });


            // }
        });
    });
</script>
<script src="js/dark-mode.js"></script>
<script>
    $(customSwitch1).click(function() {
        customSwitch1 = document.querySelector('#customSwitch1');
        fielset = document.querySelector("#fielset");
        if (customSwitch1.checked) {
            fielset.disabled = false;
        } else {
            fielset.disabled = true;
        }
    })
</script>
<script>
    $('#form').submit(function(e) {
        e.preventDefault();
        console.log('hola mundo');
        customSwitch1 = document.querySelector('#customSwitch1');
        fielset = document.querySelector("#fielset");

        //Obtenemos los valores de los inputs
        idMovil = document.querySelector('#idMovil').value;
        idVehiculo = document.querySelector('#idVehiculo').value;
        nomVehiculo = document.querySelector('#nomVehiculo').value;
        patenteVehiculo = document.querySelector('#patenteVehiculo').value;
        marcaVehiculo = document.querySelector('#marcaVehiculo').value;
        modeloVehiculo = document.querySelector('#modeloVehiculo').value;
        numInventario = document.querySelector('#numInventario').value;
        servicioVehiculo = document.querySelector('#servicioVehiculo').value;
        numChasis = document.querySelector('#numChasis').value;
        numMotor = document.querySelector('#numMotor').value;
        frabricante = document.querySelector('#frabricante').value;
        fechaCompra = document.querySelector('#fechaCompra').value;
        ordenCompra = document.querySelector('#ordenCompra').value;
        vidaUtil = document.querySelector('#vidaUtil').value;
        valorConIva = document.querySelector('#valorConIva').value;

        //Crearmos el objeto formdata y agregamos los datos
        let formData = new FormData();

        formData.append('idMovil', idMovil);
        formData.append('idVehiculo', idVehiculo);
        formData.append('nomVehiculo', nomVehiculo);
        formData.append('patenteVehiculo', patenteVehiculo);
        formData.append('marcaVehiculo', marcaVehiculo);
        formData.append('modeloVehiculo', modeloVehiculo);
        formData.append('numInventario', numInventario);
        formData.append('servicioVehiculo', servicioVehiculo);
        formData.append('numChasis', numChasis);
        formData.append('numMotor', numMotor);
        formData.append('frabricante', frabricante);
        formData.append('fechaCompra', fechaCompra);
        formData.append('ordenCompra', ordenCompra);
        formData.append('vidaUtil', vidaUtil);
        formData.append('valorConIva', valorConIva);
        //Enviamos los datos a tráves de fetch
        fetch('../sdo-funciones/editar-moviles.php', {
            method: 'POST',
            body: formData
        })
            .then(function(response) {
                if (response) {
                    customSwitch1.checked = false;
                    console.log(response.json());
                    fielset.disabled = true;
                    Swal.fire(
                        'Exito!',
                        'Se guarda la modificación correctamente!',
                        'success'
                    )
                }
            })
            .then(function(data) {
                console.log(data);
            })
            .catch(function(error) {
                Swal.fire(
                    'Problemas!',
                    'Error al intentar guardar la modificación!',
                    'error'
                )
            })

    })
</script>
<script type="text/javascript">
    $(document).ready(function() {

        // Show div by removing inline display none style rule
        $(".show-verdash").click(function() {
            $("#rowMenu3").show();
            $("#verDashAC").hide();
            $("#ocultarDashAC").show();
        });

        $(".show-ocultardash").click(function() {
            $("#rowMenu3").hide();
            $("#verDashAC").show();
            $("#ocultarDashAC").hide();
        });
    });
</script>
<script>
    $("#numGuia").blur(function() {
        guardarPetroleo = document.getElementById('guardarPetroleo');
        let campo = $('#numGuia');
        let contenedorGuia = $("#contenedorGuia")
        let alertaExito = $('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Número de guía Disponible</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        let alertaError = $('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Este número de guía ya existe!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        valor = $(this).val();
        var dataString = 'valor=' + valor;
        console.log(valor);
        $.ajax({
            url: 'consulta-numero-guia.php',
            type: 'POST',
            data: dataString,
            success: function(response) {
                console.log(response)
                if (response == '1') {
                    campo.css('border-color', 'red')
                    guardarPetroleo.disabled = true;
                    contenedorGuia.after(alertaError);

                } else if (response == '0') {
                    guardarPetroleo.disabled = false;
                    campo.css('border-color', 'green')
                    contenedorGuia.after(alertaExito);

                }
            }
        })

    });
</script>
<script>
    function mostrarInputs(valor) {

        var enviar = document.getElementById("enviar");

        //Mensual
        mesInforme = document.getElementById('mesInforme');
        mensual = document.getElementById('mensual');

        //Rango de fechas
        rangoFecha1 = document.getElementById('rangoFecha1');
        fechaInicio = document.getElementById('fechaInicio1');
        fechaFin = document.getElementById('fechaFin1');

        if (valor == 'mensual') {
            enviar.disabled = false;

            mensual.required = true;
            mesInforme.style.display = 'block';

            //Configura rango fechas
            rangoFecha1.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;
        } else if (valor == 'rangoFechas') {
            enviar.disabled = false;

            fechaInicio.required = true;
            fechaFin.required = true;
            rangoFecha1.style.display = 'block';

            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        } else if (valor == 'todos') {
            enviar.disabled = false;

            //Configura rango fechas
            rangoFecha1.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;

            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        }
        console.log(valor);
    }
</script>
<script>
    function ValidarFechas() {
        var fechainicial = document.getElementById("fechaInicio1").value;
        var fechafinal = document.getElementById("fechaFin1").value;
        var enviar = document.getElementById("enviar");

        console.log(fechainicial)
        console.log(fechafinal)

        fechainicial = Date.parse(fechainicial);
        fechafinal = Date.parse(fechafinal);

        console.log(fechainicial)
        console.log(fechafinal)

        if (fechainicial > fechafinal) {
            alert("La fecha final debe ser mayor a la fecha inicial");
            enviar.disabled = true;

        } else {
            enviar.disabled = false;
        }
    }
</script>
<script>
    $(document).ready(
        $('.eliminar').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este registro?',
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
                        url: '../sdo-funciones/eliminar-registro-kilometraje.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro eliminado',
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
    $(document).ready(
        $('.eliminarPetroleo').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este registro del petroleo?',
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
                        url: '../sdo-funciones/eliminar-registro-petroleo.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro eliminado',
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
    $(document).ready(
        $('.editar').click(function(){
            idKilometrajeEditar = document.querySelector('#idKilometrajeEditar');
            let id = $(this).attr('id');
            idKilometrajeEditar.value = id;
            console.log(id);
            var elementos = {};
            //AJAX
            $.ajax({
                type: 'POST',
                url: '../sdo-funciones/obtener-datos-kilometraje.php',
                data: {
                    'id': id
                },
                success: function(response) {
                    console.log(response);
                    var datos = JSON.parse(response);

                    elementos.fechaRegistro = datos.fechaRegistro;
                    elementos.cantKilometraje = datos.cantKilometraje;
                    elementos.estadoVehiculo = datos.estadoVehiculo;

                    $('#fechaReporte').val(elementos.fechaRegistro);
                    $('#numKilometraje').val(elementos.cantKilometraje);

                    console.log(elementos.folio);
                }
            })

        })
    );
</script>
<script>
    $(document).ready(
        $('.editarPetroleo').click(function(){
            idPetroleoEditar = document.querySelector('#idPetroleoEditar');
            let id = $(this).attr('id');
            idPetroleoEditar.value = id;
            console.log(id);
            var elementos = {};
            //AJAX
            $.ajax({
                type: 'POST',
                url: '../sdo-funciones/obtener-datos-petroleo.php',
                data: {
                    'id': id
                },
                success: function(response) {
                    console.log(response);
                    var datos = JSON.parse(response);

                    elementos.fechaRegistro = datos.fechaRegistro;
                    elementos.numGuia = datos.numGuia;
                    elementos.cantidadLitros = datos.cantidadLitros;
                    elementos.valorUnitario = datos.valorUnitario;

                    $('#fechaReportePet').val(elementos.fechaRegistro);
                    $('#numGuiaPet').val(elementos.numGuia);
                    $('#litrosPet').val(elementos.cantidadLitros);
                    $('#valorUnitarioPet').val(elementos.valorUnitario);

                    console.log(elementos.folio);
                }
            })

        })
    );
</script>
</body>

</html>