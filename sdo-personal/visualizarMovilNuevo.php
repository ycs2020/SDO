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
function obtenerDatosMovil($conexion, $idMovil)
{
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

    $nombreArchivo = $nomVehiculo . ' - ' . $idVehiculo;

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

    <title>Visualizar Vehiculo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <a style="pointer-events: none;" class="btn font-weight-bold ">Informaci&oacute;n del Vehiculo: <?php echo $nombreArchivo ?></a>
                                <input type="hidden" name="idMovilAjax" id="idMovilAjax" value="<?php echo $idMovil; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="administrar-moviles.php" style="float: right;" class="btn btn-block bg-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="row justify-content-md-center">

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                                        <?php
                                        //
                                        //                                        $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
                                        //                                        //
                                        //                                        // Obtén la IP del cliente
                                        //                                        $ipCliente = $_SERVER['REMOTE_ADDR'];
                                        //
                                        //                                        if ($ipCliente === $ipPermitida) {
                                        //                                            $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php';
                                        //                                        } else {
                                        //                                            $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil.php';
                                        //
                                        //                                        }

                                        $rutaVida = '../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php';

                                        ?>
                                        <form action="<?php echo $rutaVida; ?>" target="_blank" method="POST">

                                            <input type="hidden" value="<?php echo $idMovil ?>" name="idMovil">
                                            <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Hoja de Vida</button>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 12pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link text-white active" href="#tabInformacion" data-toggle="tab">Informaci&oacute;n</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabEstados" data-toggle="tab">Estados</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabPetroleo" data-toggle="tab">Petroleo</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabKilometraje" data-toggle="tab">Kilometraje</a></li>
                                </ul>
                            </div>


                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabInformacion">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Informaci&oacute;n</h5>

                                            <br>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                                        <label class="custom-control-label text-primary" for="customSwitch1">Click Aqui para habilitar o deshabilitar la edición de la información del movil</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 ">
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
                                                            <button type="submit" class="btn btn-sm  justify-content-center  btn-primary"><b>Guardar Cambios</b></button>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabEstados">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Registro de Estados</h5>

                                            <br>

                                            <div class="card ">
                                                <div class="card-header">
                                                    <h6><b> <i class="fas fa-hand-pointer"></i> - Registros de los estados - <i class="fas fa-chart-bar"></i></b></h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-center justify-content-center">
                                                        <button class="btn btn-success mb-3 " data-toggle="modal" data-target="#estadoModal"><b><i class="fas fa-plus"></i> Añadir Nuevo Estado</b></button>
                                                    </div>
                                                    <div class="modal fade" id="estadoModal" tabindex="-1" aria-labelledby="estadoModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title" id="estadoModalLabel"><b>Nuevo Estado</b></h5>
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
                                                                                        <option value="Operativo">Operativa</option>
                                                                                        <option value="Mantencion Preventiva">Mantención Preventiva</option>
                                                                                        <option value="Mantencion Correctiva">Mantención Correctiva</option>
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
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Cerrar Modal</b></button>
                                                                    <button type="submit" class="btn btn-success"><b>Guardar Estado</b></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <br>
                                                    <div class=" col-12 justify-content-center ">
                                                        <table class="table  table-sm  table-bordered " id="table_estado">
                                                            <thead class="text-center bg-danger">
                                                            <tr>
                                                                <th scope="col">Fecha</th>
                                                                <th scope="col">Estado</th>
                                                                <th scope="col">Motivo</th>
                                                            </tr>
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
                                    </div>

                                    <div class="tab-pane" id="tabPetroleo">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Registro de Petroleo</h5>
                                            <br>
                                            <div class="row justify-content-center">
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <a href="#" data-toggle="modal" data-target="#gasolinaModal" class="btn btn-warning btn-block"><i class="fas fa-pen"></i> <b>Registrar Petroleo</b> </a>

                                                            <div class="modal fade" id="gasolinaModal" tabindex="-1" aria-labelledby="gasolinaModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title" id="gasolinaModalLabel"><b>Nuevo Registro - Petróleo</b></h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/guardar-petroleo-moviles.php" method="POST">
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="idMovil" value="<?php echo $idMovil ?>">
                                                                                <div class="form-row">
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12pt">Fecha Reporte:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                            </div>
                                                                                            <input type="date" class="form-control form-control-sm" required id="fechaReporte" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12" id="contenedorGuia">
                                                                                        <label for="codigoBarra" style="font-size:12pt">N° Guia:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                                                            </div>
                                                                                            <input type="text" class="form-control form-control-sm" required id="numGuia" name="numGuia">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12pt">Litros:<code>*</code></label>
                                                                                        <div class="input-group mb-3">
                                                                                            <div class="input-group-prepend">
                                                                                                <span class="input-group-text"><i class="fas fa-gas-pump"></i></span>
                                                                                            </div>
                                                                                            <input type="number" name="litros" step="any" id="litros" class="form-control form-control-sm">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="codigoBarra" style="font-size:12pt">Valor Unitario :<code style="font-size:12pt">* SIN IVA</code></label>
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
                                                                                <button type="submit" id="guardarPetroleo" disabled class="btn btn-success"><b>Guardar Petróleo</b></button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Cerrar Modal</b></button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <br>
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
                                                            <th scope="col" class="text-center">Modificar</th>
                                                            <th scope="col" class="text-center">Eliminar</th>
                                                        </tr>

                                                        </thead>
                                                        <tfoot class="text-center bg-warning">
                                                        <tr>
                                                            <th scope="col" class="text-center">Fecha</th>
                                                            <th scope="col" class="text-center">N° Guía</th>
                                                            <th scope="col" class="text-center">Litros</th>
                                                            <th scope="col" class="text-center">Valor Unitario</th>
                                                            <th scope="col" class="text-center">Total Sin Iva</th>
                                                            <th scope="col" class="text-center">IVA</th>
                                                            <th scope="col" class="text-center">Total</th>
                                                            <th scope="col" class="text-center">Modificar</th>
                                                            <th scope="col" class="text-center">Eliminar</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title" id="modalEditarLabel">Editar Datos</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="formEditar">
                                                                <div class="mb-3">
                                                                    <label for="fechaReporteMod" class="form-label">Fecha Reporte</label>
                                                                    <input type="date" class="form-control" id="fechaReporteMod" name="fechaReporteMod">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="numGuiaMod" class="form-label">Número de Guía</label>
                                                                    <input type="text" class="form-control" id="numGuiaMod" name="numGuiaMod">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="cantidadLitrosMod" class="form-label">Cantidad de Litros</label>
                                                                    <input type="text" class="form-control" id="cantidadLitrosMod" name="cantidadLitrosMod">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="valorUnitMod" class="form-label">Valor Unitario</label>
                                                                    <input type="text" class="form-control" id="valorUnitMod" name="valorUnitMod">
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-success" id="guardarCambios">Guardar Cambios</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabKilometraje">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Registro de Kilometraje</h5>

                                            <br>

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

                                                            if ($registroMantenimiento == 1) {
                                                                $colorTR = 'background-color: #ffff00; color:black; font-weight:bold;';
                                                            } else {
                                                                $colorTR = '';
                                                            }
                                                            ?>
                                                            <tr style="<?php echo $colorTR; ?>">
                                                                <td><?php echo date('d/m/Y', $fechaRegistro) ?></td>
                                                                <td><?php echo strtoupper($cantKilometraje) ?></td>
                                                                <td>
                                                                    <button class="btn btn-warning btn-sm editar" id="<?php echo $idKilometraje ?> " data-toggle="modal" data-target="#editarRegistro"><i class="fas fa-pen"></i></button>
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
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Cerrar Modal</b></button>
                                                                                    <button type="submit" class="btn btn-success"><b> Guardar Cambios</b></button>
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
                    </div>


                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
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
<!-- jQuery -->
<script src="js/dark-mode.js"></script>
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

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(customSwitch1).click(function () {
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
    $('#form').submit(function (e) {
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
            .then(function (response) {
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
            .then(function (data) {
                console.log(data);
            })
            .catch(function (error) {
                Swal.fire(
                    'Problemas!',
                    'Error al intentar guardar la modificación!',
                    'error'
                )
            })

    })
</script>
<script>
    $(document).ready(function () {
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
        });
    });
</script>
<script>
    $(document).ready(function () {
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
        });
    });
</script>
<script>
    $(document).ready(function () {
        let idMovilAjax2 = document.getElementById('idMovilAjax').value;

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
            "ajax": {
                "url": "../sdo-ajax/movilizacion_petroleo.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                    d.idMovilAjax = $('#idMovilAjax').val();
                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "fechaRegistro2"},
                {"data": "numGuia"},
                {"data": "cantidadLitros2"},
                {"data": "valorUnitario2"},
                {"data": "totalSinIva2"},
                {"data": "IvaCalculado2"},
                {"data": "totalConIva2"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.idPetroleo > 0) {
                            return '<button class="btn btn-warning btn-modificar" data-id="' + row.idPetroleo + '" data-fecha="' + row.fechaRegistro + '"  data-guia="' + row.numGuia + '"  data-litros="' + row.cantidadLitros + '" data-valorUnit="' + row.valorUnitario + '"><i class="fas fa-pen"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {"data": "idPetroleo"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });
    });

    $(document).on('click', '.btn-modificar', function () {
        // Obtener valores del botón
        const idPetroleo = $(this).data('id');
        const numGuia = $(this).data('guia');
        const cantidadLitros = $(this).data('litros');
        const valorUnit = $(this).data('valorunit');
        const fechaRegistro = $(this).data('fecha');

        // Cargar valores en el modal
        $('#fechaReporteMod').val(fechaRegistro)
        $('#numGuiaMod').val(numGuia);
        $('#cantidadLitrosMod').val(cantidadLitros);
        $('#valorUnitMod').val(valorUnit);

        // Abrir el modal
        $('#modalEditar').modal('show');

        // Asignar el idPetroleo como atributo en el botón de guardar
        $('#guardarCambios').data('id', idPetroleo);
    });
</script>
</body>

</html>
