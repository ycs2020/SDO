<?php
require_once '../sdo-funciones/conexion.php';
require_once '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar rol del usuario
$rolUsuario = mb_strtolower($_SESSION['rolUsuario'] ?? '', 'UTF-8');
if ($rolUsuario !== 'rrhh') {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}
$ruta = 'sdo-rrhh';


function traspasoFechasEspañol($fecha)
{
    $timestamp = strtotime(str_replace('/', '-', $fecha));

    if ($timestamp !== false && $timestamp > 0) {
        $fecha2 = fechaEspañol2($timestamp);
    } else {
        $fecha2 = 'Fecha inválida';
    }
    $fecha3 = mb_convert_case($fecha2, MB_CASE_UPPER, "UTF-8");
    return $fecha3;
}

// Obtener ID de la credencial desde POST o sesión
$idCredencialVer = $_POST['idCredencialVer'] ?? ($_SESSION['idCredencialVer'] ?? null);
$idCredencialVer = trim($idCredencialVer ?? '');

// Validar ID
if (empty($idCredencialVer) || !is_numeric($idCredencialVer)) {
    header("Location: ../$ruta/credenciales_administracion.php?errorCargaCredencial=true");
    exit;
}

// Obtener datos
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
    header("Location: ../$ruta/credenciales_administracion.php?errorCargaCredencial=true");
    exit;
}


function enMayus($texto)
{
    return mb_convert_case(trim($texto), MB_CASE_UPPER, "UTF-8");
}

$campos = [
    'nombreCredencial', 'apellidoCredencial', 'rutCredencial', 'calidadCredencial',
    'unidadCredencial', 'cargoCredencial', 'codigoCredencial', 'tipoCredencial',
    'telefonoCredencial', 'jefaturaCredencial'
];

foreach ($campos as $campo) {
    ${$campo . '2'} = enMayus($$campo);
}

$fechaInicioCredencial2 = traspasoFechasEspañol($fechaInicioCredencial);
$fechaTerminoCredencial2 = traspasoFechasEspañol($fechaTerminoCredencial);

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
    <style>
        /* Alinear Select2 dentro de input-group */
        .input-group .select2-container {
            flex: 1 1 auto !important;
            width: 1% !important;
        }

        .input-group .select2-selection {
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container .select2-selection__arrow {
            height: 36px !important;
        }

        /* Ajuste botón verde */
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding-left: 12px;
            padding-right: 12px;
        }

        /* Cambiar texto de warning a negro */
        .toast-warning {
            color: #000 !important;
        }

        /* Opcional: también el título si usas titulo en toastr.warning(title,message) */
        .toast-warning strong {
            color: #000 !important;
        }

        .toast-warning .toast-message::before {
            color: #000 !important;
        }

        .toast-warning::before {
            color: #000 !important;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/unidadPersonal-sidebar.php';
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
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Informaci&oacute;n del Funcionario</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="credenciales_administracion.php" class="btn btn-block btn-danger">
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
                                            <td><?php echo $nombreCredencial2; ?></td>
                                            <td>
                                                <b>APELLIDO:</b>
                                            </td>
                                            <td><?php echo $apellidoCredencial2; ?></td>
                                            <td>
                                                <b>RUT:</b>
                                            </td>
                                            <td><?php echo $rutCredencial2; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>CALIDAD:</b>
                                            </td>
                                            <td><?php echo $calidadCredencial2; ?></td>
                                            <td>
                                                <b>TELEFONO:</b>
                                            </td>
                                            <td><?php echo $telefonoCredencial2; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Datos de la Credencial -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <b>CREDENCIAL:</b>
                                            </td>
                                            <td><?php echo $tipoCredencial2; ?></td>
                                            <td>
                                                <b>CODIGO:</b>
                                            </td>
                                            <td><?php echo $codigoCredencial2; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>UNIDAD:</b>
                                            </td>
                                            <td><?php echo $unidadCredencial2; ?></td>
                                            <td>
                                                <b>CARGO:</b>
                                            </td>
                                            <td><?php echo $cargoCredencial2; ?></td>
                                            <td>
                                                <b>JEFATURA:</b>
                                            </td>
                                            <td><?php echo $jefaturaCredencial2; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>INICIO:</b>
                                            </td>
                                            <td><?php echo $fechaInicioCredencial2; ?></td>
                                            <td>
                                                <b>TERMINO:</b>
                                            </td>
                                            <td><?php echo $fechaTerminoCredencial2; ?></td>
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


                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 10pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabModificar" data-toggle="tab">Modificar Datos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabObservaciones" data-toggle="tab">Observaciones</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabPerfiles" data-toggle="tab">Perfiles de Acceso</a>
                                    </li>

                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabModificar">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Informaci&oacute;n de Funcionario</h5>

                                            <br>

                                            <form id="formModificarCredenciales" method="POST" autocomplete="off">
                                                <div class="form-row justify-content-center text-center">
                                                    <!-- Nombre -->
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="nombreCredencial" class="font-weight-bold">Nombre del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <input type="text"
                                                                class="form-control"
                                                                id="nombreCredencial"
                                                                name="nombreCredencial"
                                                                placeholder="Ej: Jean Pierre o Jean-Pierre"
                                                                pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                title="Solo letras, espacios y guiones"
                                                                required
                                                                value="<?php echo $nombreCredencial2; ?>">
                                                    </div>

                                                    <!-- Apellido -->
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="apellidoCredencial" class="font-weight-bold">Apellido del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <input type="text"
                                                                class="form-control"
                                                                id="apellidoCredencial"
                                                                name="apellidoCredencial"
                                                                placeholder="Ej: Pérez García o Pérez-García"
                                                                pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                title="Solo letras, espacios y guiones"
                                                                required
                                                                value="<?php echo $apellidoCredencial2; ?>">
                                                    </div>

                                                    <!-- RUT -->
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="rutCredencial" class="font-weight-bold">RUT del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <input type="text"
                                                                class="form-control"
                                                                id="rutCredencial"
                                                                name="rutCredencial"
                                                                placeholder="Ej: 12.345.678-9"
                                                                pattern="^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$"
                                                                title="Formato válido: 12.345.678-9"
                                                                required
                                                                value="<?php echo $rutCredencial2; ?>">
                                                    </div>

                                                    <!-- Telefono -->
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="telefonoCredencial" class="font-weight-bold">Telefono del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <input type="text"
                                                                class="form-control"
                                                                id="telefonoCredencial"
                                                                name="telefonoCredencial"
                                                                placeholder="Ej: +56987654321"
                                                                title="Ej: +56987654321"
                                                                required
                                                                value="<?php echo $telefonoCredencial2; ?>">
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-row justify-content-center text-center">

                                                    <!-- Unidad -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label class="font-weight-bold">Unidad
                                                            <code>*</code></label>
                                                        <div class="input-group">
                                                            <select id="unidadTarjeta" class="form-control listUnidadTarjeta" name="unidadTarjeta">
                                                                <option value="" disabled selected>Seleccione unidad</option>
                                                                <?php
                                                                $sqlUnidades = "SELECT * FROM credenciales_rrhh_unidades ORDER BY nomUnidad ASC";
                                                                $resUnidades = mysqli_query($conexion, $sqlUnidades);
                                                                while ($u = mysqli_fetch_assoc($resUnidades)) {
                                                                    $selectedUnidad = '';
                                                                    if ($u['nomUnidad'] == $unidadCredencial2) {
                                                                        $selectedUnidad = 'selected';
                                                                    }
                                                                    echo "<option value='{$u['idUnidad']}' $selectedUnidad>{$u['nomUnidad']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <button type="button" class="btn btn-success" id="btnAddUnidad">+</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label class="font-weight-bold">Jefatura
                                                            <code>*</code></label>
                                                        <div class="input-group">
                                                            <select id="jefaturaCredencial" class="form-control listJefaturaTarjeta" name="jefaturaCredencial">
                                                                <option value="" disabled selected>Seleccione unidad</option>
                                                            </select>
                                                            <button type="button" class="btn btn-success" id="btnAddJefatura">+</button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <hr>

                                                <div class="form-row justify-content-center text-center">
                                                    <!-- Cargo -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="cargoCredencial" class="font-weight-bold">Cargo del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <select class="form-control" name="cargoCredencial" id="cargoCredencial">
                                                            <option value="Seleccione">Seleccione...</option>
                                                            <?php
                                                            $arrayCargo = ['Auxiliar', 'Profesional', 'Técnico'];
                                                            foreach ($arrayCargo as $cargo) {
                                                                $cargoMayus = mb_strtoupper($cargo);
                                                                if ($cargoMayus == $cargoCredencial2) {
                                                                    echo "<option value='$cargo' selected>$cargo</option>";
                                                                } else {
                                                                    echo "<option value='$cargo'>$cargo</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <!-- Calidad -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="calidadCredencial" class="font-weight-bold">Calidad del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                        <select class="form-control" name="calidadCredencial" id="calidadCredencial">
                                                            <option value="Seleccione">Seleccione...</option>
                                                            <?php
                                                            $arrayCalidad = ['Alumno en Practica', 'Clinico Interno', 'Contrata', 'Empresa Externa', 'Honorarios', 'Medico Becado', 'Reemplazo', 'Titular'];
                                                            foreach ($arrayCalidad as $calidad) {
                                                                $calidadMayus = mb_strtoupper($calidad);
                                                                if ($calidadMayus == $calidadCredencial2) {
                                                                    echo "<option value='$calidad' selected>$calidad</option>";
                                                                } else {
                                                                    echo "<option value='$calidad'>$calidad</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-row justify-content-center text-center">
                                                    <!-- Tipo -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="tipoCredencial" class="font-weight-bold">Tipo de la Credencial&nbsp;<code>*</code></label>
                                                        <select class="form-control" name="tipoCredencial" id="tipoCredencial">
                                                            <option value="Seleccione">Seleccione...</option>
                                                            <?php
                                                            $arrayTipo = ['Generica', 'Personalizada'];
                                                            foreach ($arrayTipo as $tipo) {
                                                                $tipoMayus = mb_strtoupper($tipo);
                                                                if ($tipoMayus == $tipoCredencial2) {
                                                                    echo "<option value='$tipo' selected>$tipo</option>";
                                                                } else {
                                                                    echo "<option value='$tipo'>$tipo</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <!-- Codigo -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="codigoCredencial" class="font-weight-bold">
                                                            Código de la Credencial&nbsp;<code>*</code>
                                                        </label>
                                                        <input type="text"
                                                                class="form-control"
                                                                id="codigoCredencial"
                                                                name="codigoCredencial"
                                                                placeholder="Ej: ABC12345"
                                                                pattern="^[A-Za-z0-9]+$"
                                                                title="Solo letras y números sin espacios ni símbolos"
                                                                required
                                                                value="<?php echo $codigoCredencial2; ?>">
                                                    </div>

                                                </div>

                                                <hr>

                                                <div class="form-row justify-content-center text-center">

                                                    <!-- Fecha Inicio -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="fechaInicioCredencial" class="font-weight-bold">Fecha de Inicio&nbsp;<code>*</code></label>
                                                        <input type="date"
                                                                class="form-control"
                                                                id="fechaInicioCredencial"
                                                                name="fechaInicioCredencial"
                                                                placeholder="Ej: XX-XX-XXXX"
                                                                pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                title="Ej: XX-XX-XXXX"
                                                                required
                                                                value="<?php echo $fechaInicioCredencial; ?>">
                                                    </div>

                                                    <!-- Fecha Termino -->
                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                                        <label for="fechaTerminoCredencial" class="font-weight-bold">Fecha de Termino&nbsp;<code>*</code></label>
                                                        <input type="date"
                                                                class="form-control"
                                                                id="fechaTerminoCredencial"
                                                                name="fechaTerminoCredencial"
                                                                placeholder="Ej: XX-XX-XXXX"
                                                                pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$"
                                                                title="Ej: XX-XX-XXXX"
                                                                required
                                                                value="<?php echo $fechaTerminoCredencial; ?>">
                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                    <div class="tab-pane " id="tabObservaciones">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Observaciones</h5>

                                            <br>

                                            <div class="row text-center" style="display: flex; justify-content:center;">
                                                <div class="col-lg-3 col-md-4 col-12">

                                                    <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarObservacion">
                                                        <i class="fa fa-plus"></i> Agregar Observación
                                                    </button>

                                                    <!-- Modal Observación -->
                                                    <div class="modal fade" id="modalAgregarObservacion" tabindex="-1" role="dialog" aria-labelledby="modalObsLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <div class="modal-content">

                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title" id="modalObsLabel">
                                                                        <i class="fas fa-comment-dots"></i> Agregar Observación
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <input type="hidden" id="idCredencialObs" value="<?php echo $idCredencial; ?>">
                                                                    <input type="hidden" id="nomUsuarioObs" value="<?php echo $nomUsuario; ?>">

                                                                    <label for="textoObservacion" class="font-weight-bold">Escriba su observación
                                                                        <code>*</code></label>
                                                                    <textarea id="textoObservacion" rows="5" class="form-control" placeholder="Ej: Solicita extensión de acceso..."></textarea>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-success" id="btnAgregarObservacion">
                                                                        <i class="fas fa-save"></i> Guardar Observación
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <i class="fas fa-times"></i> Cerrar
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>

                                            <hr>

                                            <div class="table-responsive">
                                                <input type="hidden" name="idCredencialDatos" id="idCredencialDatos" value="<?php echo $idCredencial; ?>">
                                                <table id="observacionTotal" class="table table-bordered table-hover table-sm text-center">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Fecha</th>
                                                        <th>Usuario</th>
                                                        <th>Tipo</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Fecha</th>
                                                        <th>Usuario</th>
                                                        <th>Tipo</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabPerfiles">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Perfiles de Acceso</h5>

                                            <hr>

                                            <div class="table-responsive">
                                                <input type="hidden" name="idCredencialPerfil" id="idCredencialPerfil" value="<?php echo $idCredencial; ?>">
                                                <table id="perfilesCredencial" class="table table-bordered table-hover table-sm text-center">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Perfil</th>
                                                        <th>Descripci&oacute;n</th>
                                                        <th>Grupos</th>
                                                        <th>Activar / Desactivar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Perfil</th>
                                                        <th>Descripci&oacute;n</th>
                                                        <th>Estado</th>
                                                        <th>Activar / Desactivar</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>

<script>
    // activar select2
    $(".listUnidadTarjeta, .listJefaturaTarjeta").select2({
        placeholder: "Seleccione...",
        width: "100%"
    });
</script>

<!-- Modal Unidad -->
<div class="modal fade" id="modalUnidad">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5>Nueva Unidad</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="text" id="inputUnidad" class="form-control" placeholder="Nombre Unidad">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="guardarUnidad">
                    <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar
                </button>
                <button class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Jefatura -->
<div class="modal fade" id="modalJefatura" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-tie"></i> Nueva Jefatura</h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <label class="font-weight-bold">Unidad Relacionada <code>*</code></label>
                <input class="form-control" type="text" name="nomUnidadMod" id="nomUnidadMod" readonly="readonly">
                <input type="hidden" name="idUnidadMod" id="idUnidadMod">

                <label class="mt-3 font-weight-bold">Nombre de la Jefatura <code>*</code></label>
                <input type="text" id="inputJefatura" class="form-control" placeholder="Ej: Coordinación UCA">

            </div>

            <div class="modal-footer d-flex justify-content-between">

                <button class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times"></i>&nbsp; Cancelar
                </button>

                <button class="btn btn-success" id="guardarJefatura">
                    <i class="fas fa-save"></i>&nbsp; Guardar
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    const idCredencial = $('#idCredencialDatos').val();
    const idCredencialPerfil = $('#idCredencialPerfil').val();
    const nomUsuario = $("#nomUsuarioObs").val();
    const jefaturaSeleccionada = "<?php echo strtoupper(trim($jefaturaCredencial2)); ?>";

    $(document).ready(function () {
        initObservacionTotal();
        initPerfilesCredencial();


        $('#unidadTarjeta').on('change', function () {
            const idUnidad = $(this).val();
            cargarJefaturasPorUnidad(idUnidad, jefaturaSeleccionada);
        });

        // 👇 Si necesitas cargar al iniciar, por ejemplo en edición:
        const unidadPreseleccionada = $('#unidadTarjeta').val();
        if (unidadPreseleccionada) {
            cargarJefaturasPorUnidad(unidadPreseleccionada, jefaturaSeleccionada);
        }

    });

    // === FUNCIONES ===
    function initObservacionTotal() {
        $('#observacionTotal').DataTable({
            processing: true,
            responsive: true,
            searching: true,
            lengthChange: false,
            ordering: false,
            autoWidth: false,
            language: {
                zeroRecords: "No existen registros.",
                processing: "Procesando...",
                search: "Buscar:",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No existen registros.",
                infoFiltered: "(filtrado desde _MAX_ registros totales)",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            },
            ajax: {
                url: "/sdo-includes/credenciales_rrhh_funcionario_observaciones.php",
                type: "POST",
                data: { idCredencialDatos: idCredencial },
                dataSrc: json => Array.isArray(json) ? json : [],
                error: function (xhr) {
                    console.error("❌ Error AJAX:", xhr.responseText);
                    toastr.error("Error al cargar las observaciones.");
                }
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: "fecha" },
                { data: "usuario" },
                { data: "tipo" },
                {
                    data: "observacion",
                    render: (data) => data ? data.replace(/\n/g, "<br>") : ''
                }
            ],
            createdRow: function (row) {
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center',
                    'text-transform': 'uppercase',
                    'font-size': '10pt'
                });
            }
        });
    }

    function initPerfilesCredencial() {
        $('#perfilesCredencial').DataTable({
            lengthChange: false,
            searching: true,
            autoWidth: false,
            responsive: true,
            ordering: false,
            language: {
                lengthMenu: "Visualizando _MENU_ Registros por Página",
                zeroRecords: "No se han encontrado Registros.",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No existen Registros.",
                infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                search: "Buscar:",
                processing: "Procesando...",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            },
            ajax: {
                url: "/sdo-includes/credenciales_rrhh_funcionario_perfiles.php",
                type: "POST",
                data: d => { d.idCredencialPerfil = idCredencialPerfil; },
                dataSrc: ""
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: "nomPerfil" },
                { data: "descripPerfil" },
                { data: "idPerfil" },
                { data: "perfilActivado" }
            ],
            columnDefs: [
                { width: "30%", targets: 1 },
                { width: "40%", targets: 2 }
            ],
            createdRow: function (row) {
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center',
                    'text-transform': 'uppercase',
                    'font-size': '10pt'
                });
            }
        });
    }

    function cargarJefaturasPorUnidad(idUnidad, jefaturaSeleccionada = '') {
        if (!idUnidad) {
            $('#jefaturaCredencial').html('<option value="" disabled selected>Seleccione unidad</option>');
            return;
        }

        $.ajax({
            url: '/sdo-includes/credenciales_rrhh_jefaturas_modificar_credencial.php',
            type: 'POST',
            dataType: 'json',
            data: { idUnidad: idUnidad },
            success: function (response) {
                let options = '<option value="" disabled>Seleccione jefatura</option>';

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(j => {
                        const nombreJefatura = (j.nomJefatura || '').toUpperCase().trim();
                        const selected = nombreJefatura === jefaturaSeleccionada.toUpperCase().trim() ? 'selected' : '';
                        options += `<option value="${j.idJefatura}" ${selected}>${j.nomJefatura}</option>`;
                    });
                } else {
                    options = '<option value="" disabled selected>No hay jefaturas</option>';
                }

                $('#jefaturaCredencial').html(options);
            },
            error: function (xhr) {
                console.error('Error al cargar jefaturas:', xhr.responseText);
                $('#jefaturaCredencial').html('<option value="" disabled selected>Error al cargar</option>');
            }
        });
    }


    // === AGREGAR OBSERVACIÓN ===
    $("#btnAgregarObservacion").click(function () {
        const texto = $("#textoObservacion").val().trim();
        const usuario = nomUsuario;
        const id = $("#idCredencialObs").val();

        if (texto === "") {
            Swal.fire("Observación vacía", "Debes ingresar una observación antes de guardar.", "warning");
            return;
        }

        const btn = $(this);
        btn.prop("disabled", true);

        $.ajax({
            url: "/sdo-includes/credenciales_rrhh_insertar_observacion.php",
            type: "POST",
            dataType: "json",
            data: { observacion: texto, idCredencial: id, usuario },
            success: function (resp) {
                if (resp.status === "ok") {
                    Swal.fire({
                        icon: "success",
                        title: "Observación agregada",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#modalAgregarObservacion").modal("hide");
                    $("#textoObservacion").val("");
                    $('#observacionTotal').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire("Error", resp.mensaje || "No se pudo guardar la observación.", "error");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                Swal.fire("Error inesperado", "No se pudo conectar con el servidor", "error");
            },
            complete: function () {
                btn.prop("disabled", false);
            }
        });
    });

    // === BOTÓN REGISTRAR PERFIL ===
    $(document).on("click", ".btnRegistroPerfil", function () {
        const btn = $(this);
        const perfil = Number(btn.data("perfil"));
        const credencial = Number(btn.data("credencial"));
        const accion = String(btn.data("accion") ?? "").trim();
        const nomPerfil = btn.data("nomperfil");

        if (perfil <= 0 || credencial <= 0 || !["Autoriza", "Anular"].includes(accion)) {
            toastr.error("Datos inválidos o acción no reconocida.", "Error");
            return;
        }

        if (!nomUsuario || nomUsuario.trim() === "") {
            toastr.error("Usuario no válido.", "Error");
            return;
        }

        btn.prop("disabled", true);

        $.ajax({
            url: "/sdo-includes/credenciales_rrhh_asociar_perfiles.php",
            type: "POST",
            dataType: "json",
            data: {
                perfil,
                credencial,
                accion,
                usuario: nomUsuario,
                nomPerfil
            },
            success: function (resp) {
                if (resp.status === "ok") {
                    toastr.success(resp.mensaje || "Perfil actualizado correctamente.");
                    $('#perfilesCredencial').DataTable().ajax.reload(null, false);
                    $('#observacionTotal').DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(resp.mensaje || "No se pudo modificar el perfil.", "Error");
                }
            },
            error: function (xhr) {
                console.error("Error AJAX:", xhr.responseText);
                toastr.error("Error inesperado en el servidor.");
            },
            complete: function () {
                btn.prop("disabled", false);
            }
        });
    });

    // abrir modales
    $("#btnAddUnidad").click(() => $("#modalUnidad").modal("show"));
    $("#btnAddJefatura").click(() => {
        const selectUnidad = $("#unidadTarjeta");
        const idUnidad = selectUnidad.val(); // valor del <option>
        const nomUnidad = selectUnidad.find("option:selected").text(); // texto visible

        // Validación con Toastr
        if (!idUnidad) {
            toastr.warning("Debes seleccionar una unidad antes de agregar una jefatura.", "Unidad no seleccionada");
            return;
        }

        console.log("ID Unidad:", idUnidad);
        console.log("Nombre Unidad:", nomUnidad);

        // Puedes cargar esos valores a inputs ocultos si deseas
        $("#idUnidadMod").val(idUnidad);
        $("#nomUnidadMod").val(nomUnidad);




        // Mostrar modal
        $("#modalJefatura").modal("show");

        // Notificación de éxito con Toastr
        // toastr.success(`Unidad seleccionada: ${nomUnidad} (ID: ${idUnidad})`, "Unidad seleccionada");
    });


    // guardar nueva unidad
    $("#guardarUnidad").click(() => {

        let nombre = $("#inputUnidad").val().trim();

        if (!nombre) {
            toastr.warning("Debe ingresar un nombre");
            return;
        }

        $.ajax({
            url: "/sdo-includes/credenciales_rrhh_unidades_guardar.php",
            type: "POST",
            dataType: "json", // evita JSON.parse
            data: {nombre: nombre},
            success: function (res) {

                if (res.status === "success") {

                    // Usar el nombre normalizado devuelto desde PHP
                    $("#unidadTarjeta")
                        .append(`<option value="${res.id}" selected>${res.nombre}</option>`)
                        .trigger("change");

                    toastr.success(res.message);

                    // limpiar y cerrar modal
                    $("#inputUnidad").val("");
                    $("#modalUnidad").modal("hide");
                    cargarJefaturasPorUnidad(res.id, jefaturaSeleccionada);

                } else if (res.status === "warning") {
                    toastr.warning(res.message);
                } else {
                    toastr.error(res.message);
                }
            },

            error: function (xhr, status, error) {
                toastr.error("Error de comunicación con el servidor.");
                console.error(error);
            }
        });

    });
</script>

</body>

</html>
