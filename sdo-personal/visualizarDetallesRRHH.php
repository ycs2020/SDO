<?php
require_once '../sdo-funciones/conexion.php';
require_once '../sdo-funciones/funciones.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idCredencialVer = $_POST['idCredencialVer'] ?? ($_SESSION['idCredencialVer'] ?? null);
$idCredencialVer = trim($idCredencialVer ?? '');


$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (empty($idCredencialVer) || !is_numeric($idCredencialVer)) {
    header("Location: ../$rutaDireccion/activarRRHH.php?errorCargaCredencial=true");
    exit;
}

$credencial = getDatosCredencialesRRHH($idCredencialVer);
// Validar si existe
if (isset($credencial['idCredencial'])) {
//    echo "✅ Registro encontrado: {$credencial['nombreCredencial']} {$credencial['apellidoCredencial']}";
    // Aquí puedes continuar con la lógica (mostrar, renderizar, retornar JSON, etc.)

    $idCredencial = $credencial['idCredencial'];
    $nombreCredencial = $credencial['nombreCredencial'];
    $apellidoCredencial = $credencial['apellidoCredencial'];
    $rutCredencial = $credencial['rutCredencial'];
    $calidadCredencial = $credencial['calidadCredencial'];
    $unidadCredencial = $credencial['unidadCredencial'];
    $cargoCredencial = $credencial['cargoCredencial'];
    $fechaInicioCredencial = $credencial['fechaInicioCredencial'];
    $fechaTerminoCredencial = $credencial['fechaTerminoCredencial'];
    $codigoCredencial = $credencial['codigoCredencial'];
    $tipoCredencial = $credencial['tipoCredencial'];
    $telefonoCredencial = $credencial['telefonoCredencial'];
    $jefaturaCredencial = $credencial['jefaturaCredencial'];
    $activacionCredencial = $credencial['activacionCredencial'];

} else {
    header("Location: ../$rutaDireccion/activarRRHH.php?errorCargaCredencial=true");
    exit;
}
$estado = [
    1 => ["FUNCIONARIO(A) ACTIVADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #16D104; font-weight: bold;"],
    2 => ["FUNCIONARIO(A) BLOQUEADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #D10404; font-weight: bold;"],
    0 => ["FUNCIONARIO(A) NO ACTIVADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #D10404; font-weight: bold;"]
];

[$activacionCredencial2, $colorCredencial] = $estado[$activacionCredencial] ?? $estado[0];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informaci&oacute;n del Funcionario(a)</title>

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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Informaci&oacute;n del Funcionario</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="activarRRHH.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <!-- Datos Personales -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b>NOMBRE:</b>
                                            </td>
                                            <td><?php echo $nombreCredencial; ?></td>
                                            <td>
                                                <b>APELLIDO:</b>
                                            </td>
                                            <td><?php echo $apellidoCredencial; ?></td>
                                            <td>
                                                <b>RUT:</b>
                                            </td>
                                            <td><?php echo $rutCredencial; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>CALIDAD:</b>
                                            </td>
                                            <td><?php echo $calidadCredencial; ?></td>
                                            <td>
                                                <b>TELEFONO:</b>
                                            </td>
                                            <td><?php echo $telefonoCredencial; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b>CREDENCIAL:</b>
                                            </td>
                                            <td><?php echo $tipoCredencial; ?></td>
                                            <td>
                                                <b>CODIGO:</b>
                                            </td>
                                            <td><?php echo $codigoCredencial; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>UNIDAD:</b>
                                            </td>
                                            <td><?php echo $unidadCredencial; ?></td>
                                            <td>
                                                <b>CARGO:</b>
                                            </td>
                                            <td><?php echo $cargoCredencial; ?></td>
                                            <td>
                                                <b>JEFATURA:</b>
                                            </td>
                                            <td><?php echo $jefaturaCredencial; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>INICIO:</b>
                                            </td>
                                            <td><?php echo $fechaInicioCredencial; ?></td>
                                            <td>
                                                <b>TERMINO:</b>
                                            </td>
                                            <td><?php echo $fechaTerminoCredencial; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Estado de Activación -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b>ESTADO ACTIVACIÓN:</b>
                                            </td>
                                            <td style="<?php echo $colorCredencial; ?>"><?php echo $activacionCredencial2; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
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
</html>
