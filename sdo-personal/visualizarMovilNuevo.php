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
    $sql = "SELECT * FROM moviles_movilizacion WHERE idMovil = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idMovil);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $datos = $res->fetch_assoc();
    }

    $stmt->close();
    return $datos;
}

$idMovil = null;

if (isset($_POST['idMovil'])) {
    $idMovil = trim($_POST['idMovil']);
    if (empty($idMovil)) {
        header('Location: administrar-moviles.php?noId=true');
        exit();
    } else {
        $_SESSION['idMovil'] = $idMovil;
    }
} elseif (isset($_SESSION['idMovil'])) {
    $idMovil = trim($_SESSION['idMovil']);
    if (empty($idMovil)) {
        header('Location: administrar-moviles.php?noId=true');
        exit();
    }
}

if ($idMovil) {
    $datos = obtenerDatosMovil($conexion, $idMovil);
    if (empty($datos)) {
        header('Location: administrar-moviles.php?noData=true');
        exit();
    }

    // SANITIZACIÓN DE DATOS (Seguridad XSS para los inputs)
    $idMovil = htmlspecialchars($datos['idMovil'] ?? '');
    $idVehiculo = htmlspecialchars($datos['idUnico'] ?? '');
    $nomVehiculo = htmlspecialchars($datos['nomVehiculo'] ?? '');
    $patenteVehiculo = htmlspecialchars($datos['patenteVehiculo'] ?? '');
    $marcaVehiculo = htmlspecialchars($datos['marcaVehiculo'] ?? '');
    $modeloVehiculo = htmlspecialchars($datos['modeloVehiculo'] ?? '');
    $servicioVehiculo = htmlspecialchars($datos['servicioVehiculo'] ?? '');
    $valorConIva = htmlspecialchars($datos['valorConIva'] ?? '');
    $estadoVehiculo = htmlspecialchars($datos['estadoVehiculo'] ?? '');
    $numInventario = htmlspecialchars($datos['numInventario'] ?? '');
    $numChasis = htmlspecialchars($datos['numChasis'] ?? '');
    $numMotor = htmlspecialchars($datos['numMotor'] ?? '');
    $vidaUtil = htmlspecialchars($datos['vidaUtil'] ?? '');
    $fabricante = htmlspecialchars($datos['frabricante'] ?? ''); // Mantenido 'frabricante' de la BD
    $fechaCompra = htmlspecialchars($datos['fechaCompra'] ?? '');
    $ordenCompra = htmlspecialchars($datos['ordenCompra'] ?? '');

    $nombreArchivo = $nomVehiculo . ' - ' . $idVehiculo;
} else {
    header('Location: administrar-moviles.php?noId=true');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Vehículo | SDO</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <style>
        .nav-pills .nav-link { font-weight: 500; transition: all 0.3s ease; }
        .nav-pills .nav-link.active { background-color: #007bff !important; box-shadow: 0 4px 6px rgba(0,123,255,.3); }
        .nav-pills .nav-link:not(.active):hover { background-color: rgba(255,255,255,0.2); }
        .form-group label { font-weight: 600; color: #495057; }
        .input-group-text { background-color: #f8f9fa; color: #6c757d; }
        .table th { vertical-align: middle !important; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/user-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="row mb-3">
                    <div class="col-xl-9 col-lg-8 col-md-7 col-12 mb-2 mb-md-0">
                        <div class="card h-100 mb-0 shadow-sm border-0">
                            <div class="card-header bg-primary text-white d-flex align-items-center">
                                <h5 class="mb-0 w-100 font-weight-bold">
                                    <i class="fas fa-car mr-2"></i> Vehículo: <?php echo $nombreArchivo; ?>
                                </h5>
                                <input type="hidden" name="idMovilAjax" id="idMovilAjax" value="<?php echo $idMovil; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-5 col-12">
                        <a href="administrar-moviles.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center shadow-sm">
                            <i class="fa fa-chevron-left mr-2"></i> Volver Atrás
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <form action="../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php" target="_blank" method="POST">
                            <input type="hidden" value="<?php echo $idMovil; ?>" name="idMovil">
                            <button type="submit" class="btn btn-outline-primary btn-block shadow-sm font-weight-bold">
                                <i class="fas fa-file-pdf mr-2"></i> Visualizar Hoja de Vida
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark p-0 border-bottom-0">
                        <ul class="nav nav-pills nav-fill p-2">
                            <li class="nav-item">
                                <a class="nav-link text-white active rounded" href="#tabInformacion" data-toggle="tab">
                                    <i class="fas fa-info-circle mr-1"></i> Información
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white rounded" href="#tabEstados" data-toggle="tab">
                                    <i class="fas fa-clipboard-list mr-1"></i> Estados
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white rounded" href="#tabPetroleo" data-toggle="tab">
                                    <i class="fas fa-gas-pump mr-1"></i> Petróleo
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white rounded" href="#tabKilometraje" data-toggle="tab">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Kilometraje
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="tabInformacion">
                                <div class="row mb-3">
                                    <div class="col-12 text-center">
                                        <h4 class="text-secondary font-weight-bold mb-3">Información General</h4>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mb-3">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                            <label class="custom-control-label text-muted" style="cursor:pointer;" for="customSwitch1">
                                                Habilitar Edición
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <form id="formDatosVehiculo">
                                    <fieldset id="fielset" class="border p-4 shadow-sm bg-light rounded" disabled>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>ID:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-id-card"></i></span></div>
                                                    <input type="text" class="form-control" required id="idVehiculo" name="idVehiculo" value="<?php echo $idVehiculo; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nombre:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-car"></i></span></div>
                                                    <input type="text" class="form-control" required id="nomVehiculo" name="nomVehiculo" value="<?php echo $nomVehiculo; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Patente:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-fingerprint"></i></span></div>
                                                    <input type="text" class="form-control" required id="patenteVehiculo" name="patenteVehiculo" value="<?php echo $patenteVehiculo; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Marca:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-copyright"></i></span></div>
                                                    <input type="text" class="form-control" required id="marcaVehiculo" name="marcaVehiculo" value="<?php echo $marcaVehiculo; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Modelo:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-car-side"></i></span></div>
                                                    <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" value="<?php echo $modeloVehiculo; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>N° de Inventario:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-box"></i></span></div>
                                                    <input type="text" class="form-control" id="numInventario" name="numInventario" value="<?php echo $numInventario; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Servicio:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-hospital"></i></span></div>
                                                    <select name="servicioVehiculo" id="servicioVehiculo" class="form-control" required>
                                                        <?php
                                                        $sqlServicios = "SELECT nomServicio FROM servicios";
                                                        $resServicios = mysqli_query($conexion, $sqlServicios);
                                                        while ($rowServicios = $resServicios->fetch_assoc()) {
                                                            $nomServicio = htmlspecialchars($rowServicios['nomServicio']);
                                                            $selected = ($servicioVehiculo === $nomServicio) ? 'selected' : '';
                                                            echo "<option value=\"$nomServicio\" $selected>$nomServicio</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>N° de Chasis:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-cogs"></i></span></div>
                                                    <input type="text" class="form-control" id="numChasis" name="numChasis" value="<?php echo $numChasis; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>N° de Motor:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-wrench"></i></span></div>
                                                    <input type="text" class="form-control" id="numMotor" name="numMotor" value="<?php echo $numMotor; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Fabricante:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-industry"></i></span></div>
                                                    <input type="text" class="form-control" id="frabricante" name="frabricante" value="<?php echo $fabricante; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Vida Útil:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-heartbeat"></i></span></div>
                                                    <input type="text" class="form-control" id="vidaUtil" name="vidaUtil" value="<?php echo $vidaUtil; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Fecha de Compra:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                                                    <input type="date" class="form-control" id="fechaCompra" name="fechaCompra" value="<?php echo $fechaCompra; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Orden de Compra:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-invoice"></i></span></div>
                                                    <input type="text" class="form-control" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row justify-content-center mt-3">
                                            <div class="form-group col-md-6">
                                                <label>Valor con IVA ($):</label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend"><span class="input-group-text text-success"><i class="fas fa-money-bill-wave"></i></span></div>
                                                    <input type="number" class="form-control font-weight-bold" id="valorConIva" name="valorConIva" value="<?php echo $valorConIva; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                                            <button type="submit" class="btn btn-success btn-lg px-5 shadow"><i class="fas fa-save mr-2"></i> Guardar Cambios</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="tabEstados">
                                <div class="text-center mb-4">
                                    <h4 class="text-secondary font-weight-bold">Registro de Estados</h4>
                                    <button class="btn btn-success mt-2 shadow-sm" data-toggle="modal" data-target="#estadoModal">
                                        <i class="fas fa-plus mr-1"></i> Añadir Nuevo Estado
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered w-100" id="table_estado">
                                        <thead class="bg-dark text-white text-center">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Motivo</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                        <?php
                                        $sqlHistEstados = "SELECT * FROM historial_estados_moviles WHERE idMovil = ? ORDER BY fechaReporte DESC";
                                        $stmtEst = $conexion->prepare($sqlHistEstados);
                                        $stmtEst->bind_param('i', $idMovil);
                                        $stmtEst->execute();
                                        $resHistEstados = $stmtEst->get_result();

                                        while ($rowHistEstados = $resHistEstados->fetch_assoc()) {
                                            $fecha = date('d/m/Y H:i', strtotime($rowHistEstados['fechaReporte']));
                                            $estado = htmlspecialchars(strtoupper($rowHistEstados['estadoMovil']));
                                            $motivo = htmlspecialchars(strtoupper($rowHistEstados['motivo']));

                                            // Badges dinámicos para el estado
                                            $badgeClass = 'badge-secondary';
                                            if(strpos(strtolower($estado), 'operativ') !== false) $badgeClass = 'badge-success';
                                            if(strpos(strtolower($estado), 'preventiva') !== false) $badgeClass = 'badge-warning';
                                            if(strpos(strtolower($estado), 'correctiva') !== false) $badgeClass = 'badge-danger';

                                            echo "<tr>
                                                        <td>{$fecha}</td>
                                                        <td><span class='badge {$badgeClass} p-2' style='font-size: 0.9em'>{$estado}</span></td>
                                                        <td>{$motivo}</td>
                                                      </tr>";
                                        }
                                        $stmtEst->close();
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabPetroleo">
                                <div class="text-center mb-4">
                                    <h4 class="text-secondary font-weight-bold">Registro de Combustible</h4>
                                    <button class="btn btn-warning mt-2 shadow-sm font-weight-bold text-dark" data-toggle="modal" data-target="#gasolinaModal">
                                        <i class="fas fa-gas-pump mr-1"></i> Registrar Carga
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered w-100" id="table_gasolina">
                                        <thead class="bg-warning text-dark text-center">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>N° Guía</th>
                                            <th>Litros</th>
                                            <th>V. Unitario</th>
                                            <th>Total (Sin IVA)</th>
                                            <th>IVA</th>
                                            <th>Total</th>
                                            <th>Modificar</th>
                                            <th>ID</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabKilometraje">
                                <div class="text-center mb-4">
                                    <h4 class="text-secondary font-weight-bold">Registro de Kilometraje</h4>
                                </div>

                                <div class="alert alert-info mb-4 shadow-sm border-0">
                                    <i class="fas fa-info-circle"></i> <strong>Colores para Identificar:</strong><br>
                                    <span class="badge text-dark p-2 mt-2" style="background-color: #ffff00;">Fila Amarilla</span> = Inicio Próximo Mantenimiento
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered w-100" id="table_kilometro">
                                        <thead class="bg-secondary text-white text-center">
                                        <tr>
                                            <th>Fecha Registro</th>
                                            <th>Kilometraje</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                        <?php
                                        $sqlHistKilometro = "SELECT * FROM historial_kilometraje_moviles WHERE idMovil = ? ORDER BY fechaRegistro DESC";
                                        $stmtKm = $conexion->prepare($sqlHistKilometro);
                                        $stmtKm->bind_param('i', $idMovil);
                                        $stmtKm->execute();
                                        $resHistKilometros = $stmtKm->get_result();

                                        while ($row = $resHistKilometros->fetch_assoc()) {
                                            $idKm = $row['idKilometraje'];
                                            $fechaKm = date('d/m/Y', strtotime($row['fechaRegistro']));
                                            $cantKm = number_format($row['cantKilometraje'], 0, ",", ".");
                                            $mantenimiento = $row['registroMantenimiento'];

                                            $bgStyle = ($mantenimiento == 1) ? 'background-color: #fff9c4; font-weight:bold;' : '';

                                            echo "<tr style='{$bgStyle}'>
                                                        <td>{$fechaKm}</td>
                                                        <td>{$cantKm} Km</td>
                                                        <td>
                                                            <button class='btn btn-warning btn-sm shadow-sm' onclick='editarKilometraje({$idKm}, \"{$row['fechaRegistro']}\", {$row['cantKilometraje']})'><i class='fas fa-pen'></i></button>
                                                            <button class='btn btn-danger btn-sm shadow-sm' onclick='eliminarKilometraje({$idKm})'><i class='fas fa-trash'></i></button>
                                                        </td>
                                                      </tr>";
                                        }
                                        $stmtKm->close();
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> </div> </div> </div></section>
    </div>

    <?php include '../sdo-templates/footer.php'; ?>
</div>

<div class="modal fade" id="estadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i> Nuevo Estado</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="../sdo-funciones/guardar-estado-moviles.php" method="POST">
                <div class="modal-body bg-light">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                    <div class="form-group">
                        <label>Fecha Reporte:</label>
                        <input type="date" class="form-control" readonly id="fechaReporteEstado" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                    </div>
                    <div class="form-group">
                        <label>Estado:<span class="text-danger">*</span></label>
                        <select class="form-control" required name="estadoMovil">
                            <option value="" disabled selected>-- Seleccione --</option>
                            <option value="Operativa">Operativa</option>
                            <option value="Mantencion Preventiva">Mantención Preventiva</option>
                            <option value="Mantencion Correctiva">Mantención Correctiva</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Motivo:</label>
                        <textarea rows="4" class="form-control" name="motivo" placeholder="Explique el motivo del nuevo estado..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="gasolinaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-gas-pump mr-2"></i> Registro de Petróleo</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="../sdo-funciones/guardar-petroleo-moviles.php" method="POST">
                <div class="modal-body bg-light">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                    <div class="form-group">
                        <label>Fecha Reporte:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" required value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                    </div>
                    <div class="form-group">
                        <label>N° Guía:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="numGuia">
                    </div>
                    <div class="form-group">
                        <label>Litros:<span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control" required name="litros">
                    </div>
                    <div class="form-group">
                        <label>Valor Unitario:<span class="text-danger">* (SIN IVA)</span></label>
                        <input type="number" step="any" class="form-control" required name="valorUnitario">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save"></i> Guardar Carga</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarPetroleo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-pen mr-2"></i> Editar Registro</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-light">
                <form id="formEditarPetroleo">
                    <div class="form-group">
                        <label>Fecha Reporte</label>
                        <input type="date" class="form-control" id="fechaReporteMod" name="fechaReporteMod">
                    </div>
                    <div class="form-group">
                        <label>Número de Guía</label>
                        <input type="text" class="form-control" id="numGuiaMod" name="numGuiaMod">
                    </div>
                    <div class="form-group">
                        <label>Cantidad de Litros</label>
                        <input type="number" step="any" class="form-control" id="cantidadLitrosMod" name="cantidadLitrosMod">
                    </div>
                    <div class="form-group">
                        <label>Valor Unitario</label>
                        <input type="number" step="any" class="form-control" id="valorUnitMod" name="valorUnitMod">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarEdicionPetroleo"><i class="fas fa-save"></i> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarRegistroKm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-tachometer-alt mr-2"></i> Editar Kilometraje</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="../sdo-funciones/guardar-edicion-kilometraje.php" method="POST">
                <div class="modal-body bg-light">
                    <input type="hidden" name="idKilometraje" id="idKilometrajeEditar">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                    <div class="form-group">
                        <label>Fecha Reporte:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" required id="fechaReporteKmMod" name="fechaReporte">
                    </div>
                    <div class="form-group">
                        <label>Kilometraje:<span class="text-danger">*</span></label>
                        <input type="number" step="1" class="form-control" required id="numKilometrajeMod" name="numKilometraje">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function () {
        // 1. Iniciar inputs personalizados
        bsCustomFileInput.init();

        // 2. Lógica del Switch para habilitar/deshabilitar el formulario
        $('#customSwitch1').change(function() {
            $('#fielset').prop('disabled', !this.checked);
        });

        // 3. Envío AJAX del formulario de Información (Mucho más limpio con FormData automático)
        $('#formDatosVehiculo').submit(function (e) {
            e.preventDefault();

            // Al usar FormData(this), captura automáticamente todos los inputs que tengan el atributo "name"
            let formData = new FormData(this);

            fetch('../sdo-funciones/editar-moviles.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (response.ok) {
                        // Deshabilitar formulario nuevamente
                        $('#customSwitch1').prop('checked', false);
                        $('#fielset').prop('disabled', true);

                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'La modificación se ha guardado correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        throw new Error('Error en la respuesta de red');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: '¡Problemas!',
                        text: 'Error al intentar guardar la modificación.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
        });

        // 4. Configuración común para DataTables
        const dataTableConfig = {
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: true,
            language: {
                lengthMenu: "Visualizando _MENU_ Registros",
                zeroRecords: "No se encontraron registros.",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No existen registros.",
                search: "Buscar:",
                infoFiltered: "(Filtrado de _MAX_ registros)",
                paginate: { previous: "Anterior", next: "Siguiente" }
            }
        };

        // Inicializar Tablas Estáticas
        $('#table_kilometro, #table_estado').DataTable(dataTableConfig);

        // Inicializar Tabla AJAX (Petróleo)
        $('#table_gasolina').DataTable($.extend({}, dataTableConfig, {
            ajax: {
                url: "../sdo-ajax/movilizacion_petroleo.php",
                type: "POST",
                data: function (d) { d.idMovilAjax = $('#idMovilAjax').val(); },
                dataSrc: ""
            },
            columns: [
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
                            return `<button class="btn btn-warning btn-sm btn-modificar-petroleo shadow-sm"
                                        data-id="${row.idPetroleo}" data-fecha="${row.fechaRegistro}"
                                        data-guia="${row.numGuia}" data-litros="${row.cantidadLitros}"
                                        data-valorunit="${row.valorUnitario}">
                                    <i class="fas fa-pen"></i>
                                </button>`;
                        }
                        return '<span class="badge badge-secondary">Deshabilitado</span>';
                    }
                },
                {"data": "idPetroleo", "visible": false} // Oculto el ID pero lo mantengo accesible
            ]
        }));

        // 5. Cargar datos en el Modal de Edición de Petróleo
        $(document).on('click', '.btn-modificar-petroleo', function () {
            $('#fechaReporteMod').val($(this).data('fecha'));
            $('#numGuiaMod').val($(this).data('guia'));
            $('#cantidadLitrosMod').val($(this).data('litros'));
            $('#valorUnitMod').val($(this).data('valorunit'));

            $('#btnGuardarEdicionPetroleo').data('id', $(this).data('id'));
            $('#modalEditarPetroleo').modal('show');
        });
    });

    // Función global para inyectar datos en el Modal de Kilometraje (para no crear un modal por fila)
    function editarKilometraje(idKm, fecha, km) {
        $('#idKilometrajeEditar').val(idKm);
        $('#fechaReporteKmMod').val(fecha);
        $('#numKilometrajeMod').val(km);
        $('#editarRegistroKm').modal('show');
    }

    function eliminarKilometraje(idKm) {
        // Aquí puedes agregar tu alerta SweetAlert2 para confirmar la eliminación
        console.log("Intentando eliminar ID: " + idKm);
    }
</script>
</body>
</html>