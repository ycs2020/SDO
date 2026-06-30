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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <style>
        .nav-pills .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff !important;
            box-shadow: 0 4px 6px rgba(0, 123, 255, .3);
        }

        .nav-pills .nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
        }

        .input-group-text {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .table th {
            vertical-align: middle !important;
        }
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
                                <h5 class="mb-0 w-100 font-weight-bold" id="nombreVehiculoCabecera">
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

                <div id="contenedorAlertasMantenimiento">
                    <?php
                    include '../sdo-includes/vehiculos/vehiculos_mantenimiento_info.php';
                    ?>
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
                            <?php
                            include '../sdo-includes/vehiculos_tabs/tab-informacion.php';
                            include '../sdo-includes/vehiculos_tabs/tab-estados.php';
                            include '../sdo-includes/vehiculos_tabs/tab-petroleo.php';
                            include '../sdo-includes/vehiculos_tabs/tab-kilometraje.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
<script src="js/dark-mode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<script src="../dist/js/datatables-config.js"></script>

<script src="../sdo-includes/vehiculos_js/vehiculos-info.js"></script>
<script src="../sdo-includes/vehiculos_js/vehiculos-estados.js"></script>
<script src="../sdo-includes/vehiculos_js/vehiculos-petroleo.js"></script>
<script src="../sdo-includes/vehiculos_js/vehiculos-kilometraje.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("input[type=datetime-local]", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "es"
        });
    });
</script>
</body>
</html>