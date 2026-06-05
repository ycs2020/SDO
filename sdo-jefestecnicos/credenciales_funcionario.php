<?php
require_once '../sdo-funciones/conexion.php';
require_once '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar rol del usuario
$rolUsuario = mb_strtolower($_SESSION['rolUsuario'] ?? '', 'UTF-8');
/*if ($rolUsuario !== 'rrhh') {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}
*/
$ruta = 'sdo-jefes';


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
    $telefonoCredencial = $credencial['telefonoCredencial'];
    $activacionCredencial = $credencial['activacionCredencial'];
    $esBecado = $credencial['esBecado'];
    $esDiscapacitado = $credencial['esDiscapacitado'];

    if($esBecado == 1){
        $textBecado = "SI";
        $checkeboxBecado = "checked";
    }else{
        $textBecado = "NO";
    }

    if($esDiscapacitado == 1){
        $textDisca = "SI";
        $checkeboxDisca = "checked";
    }else{
        $textDisca = "NO";
    }

} else {
    header("Location: ../$ruta/credenciales_administracion.php?errorCargaCredencial=true");
    exit;
}


function enMayus($texto)
{
    return mb_convert_case(trim($texto), MB_CASE_UPPER, "UTF-8");
}

$campos = [
    'nombreCredencial', 'apellidoCredencial', 'rutCredencial', 'telefonoCredencial'
];

foreach ($campos as $campo) {
    ${$campo . '2'} = enMayus($$campo);
}

$estado = [
    1 => ["FUNCIONARIO(A) ACTIVADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #16D104; font-weight: bold;", "Deshabilitado"],
    2 => ["FUNCIONARIO(A) BLOQUEADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #D10404; font-weight: bold;", "Deshabilitado"],
    0 => ["FUNCIONARIO(A) NO ACTIVADO(A) EN SISTEMA DE CONTROL DE ACCESOS", "color: #D10404; font-weight: bold;", "Habilitado"]
];

[$activacionCredencial2, $colorCredencial, $habilitadoCredencial2] = $estado[$activacionCredencial] ?? $estado[0];


$contarHabilitados = contarCredencialesHabilitadas($idCredencialVer);
//echo $contarHabilitados;
$contarDeshabilitados = contarCredencialesDeshabilitadas($idCredencialVer);


$stmt = $conexion->prepare("
    UPDATE credenciales_rrhh_observaciones
    SET notificado = 1
    WHERE idCredencial = ?
    AND tipoObservacion <> 'Trabajo Realizado'
");

$stmt->bind_param("i", $idCredencialVer);
$stmt->execute();

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
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php

            if (isset($_GET['exitoCreacionOT'])) {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Eror al guardar la información del vehículo, code: 1',
                    })
                </script>
                <?php
            }
            ?>
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


                    <div class="col-xl-9 col-lg-9 col-md-8 col-12 d-flex">
                        <div class="card card-primary card-outline w-100">
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
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>RUT:</b>
                                            </td>
                                            <td><?php echo $rutCredencial2; ?></td>
                                            <td>
                                                <b>TELEFONO:</b>
                                            </td>
                                            <td><?php echo $telefonoCredencial2; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>FUNCIONARIO BECADO</b></td>
                                            <td><?php echo $textBecado;?></td>
                                            <td><b>FUNCIONARIO DISCAPACITADO</b></td>
                                            <td><?php echo $textDisca;?></td>
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

                    <div class="col-xl-3 col-lg-3 col-md-4 col-12 d-flex">

                        <div class="card card-primary card-outline w-100">
                            <div class="card-body box-profile d-flex align-items-center justify-content-center">
                                <div class="row w-100">
                                    <div class="col-md-6 mb-2">
                                        <?php if ($habilitadoCredencial2 === "Habilitado"): ?>
                                            <!--
                                            <button
                                                class="btn btn-warning btn-block"
                                                id="btnModificarFuncionario"
                                                data-toggle="modal"
                                                data-target="#modalModificarFuncionario"
                                                title="Modificar los datos del funcionario"
                                            >
                                                <i class="fa fa-pen"></i> Modificar Datos
                                            </button>
                                            -->
                                            <!-- Modal Modificar Funcionario -->
                                            <div class="modal fade" id="modalModificarFuncionario" tabindex="-1" role="dialog" aria-labelledby="tituloModalModificarFuncionario" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title" id="tituloModalModificarFuncionario">
                                                                <i class="fa fa-user-edit"></i> Modificar Datos del Funcionario
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <!-- Aquí irá el formulario -->
                                                            <!-- FORMULARIO INICIO -->
                                                            <form id="formModificarCredenciales" method="POST" autocomplete="off">
                                                                <div class="form-row justify-content-center text-center">
                                                                    <!-- Nombre -->
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                                        <label for="nombreCredencial" class="font-weight-bold">Nombre del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                        <input type="text" class="form-control" id="nombreCredencial" name="nombreCredencial" placeholder="Ej: Jean Pierre o Jean-Pierre" pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$" title="Solo letras, espacios y guiones" required value="<?php echo $nombreCredencial2; ?>">
                                                                    </div>

                                                                    <!-- Apellido -->
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                                        <label for="apellidoCredencial" class="font-weight-bold">Apellido del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                        <input type="text" class="form-control" id="apellidoCredencial" name="apellidoCredencial" placeholder="Ej: Pérez García o Pérez-García" pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$" title="Solo letras, espacios y guiones" required value="<?php echo $apellidoCredencial2; ?>">
                                                                    </div>
                                                                </div>

                                                                <hr>

                                                                <div class="form-row justify-content-center text-center">
                                                                    <!-- RUT -->
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                                        <label for="rutCredencial" class="font-weight-bold">RUT del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                        <input type="text" class="form-control" id="rutCredencial" name="rutCredencial" placeholder="Ej: 12.345.678-9" pattern="^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$" title="Formato válido: 12.345.678-9" required value="<?php echo $rutCredencial2; ?>">
                                                                    </div>

                                                                    <!-- Teléfono -->
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                                                        <label for="telefonoCredencial" class="font-weight-bold">Teléfono del(la) Funcionario(a)&nbsp;<code>*</code></label>
                                                                        <input type="text" class="form-control" id="telefonoCredencial" name="telefonoCredencial" placeholder="Ej: +56987654321" title="Ej: +56987654321" required value="<?php echo $telefonoCredencial2; ?>">
                                                                    </div>
                                                                </div>

                                                                <hr>
                                                                <input type="hidden" name="idCredencial" id="idCredencial" value="<?php echo $idCredencial; ?>">

                                                                <div class="form-group text-center mt-3">
                                                                    <button id="btnGuardarCambios" type="submit" class="btn btn-primary font-weight-bold">
                                                                        <i class="fas fa-save"></i>&nbsp;Guardar Cambios
                                                                    </button>
                                                                </div>
                                                            </form>
                                                            <!-- FORMULARIO FIN -->
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        <?php else: ?>
                                            <button
                                                    class="btn btn-secondary btn-block"
                                                    disabled
                                                    aria-disabled="true"
                                                    title="Este botón está deshabilitado"
                                            >
                                                <i class="fa fa-ban"></i> Deshabilitado
                                            </button>
                                        <?php endif; ?>
                                        <button
                                                class="btn btn-primary btn-block"
                                                data-toggle="modal"
                                                data-target="#crearOT"
                                        >Crear OT
                                        </button>
                                        <form method="post" style="vertical-align: middle" action="../sdo-funciones/crear_ot_credencial.php">
                                            <input type="hidden" name="idCredencial" id="idCredencial" value="<?php echo $idCredencial?>">
                                            <div class="modal fade" id="crearOT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-purple">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar OT</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">

                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Turno Asignado
                                                                            <code>*</code></label>
                                                                        <select class="custom-select" name="turnoAsignado" id="turnoAsignado">
                                                                            <option value="0">Seleccione...</option>
                                                                            <option value="1">Personal Diurno</option>
                                                                            <option value="2">Personal Nocturno</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleFormControlSelect1">Seleccione Funcionario
                                                                            <code>*</code></label>
                                                                        <select class="form-control form-control-sm listFuncionarioOT" name="funcionarioOT" required>
                                                                            <option value="nulo">Seleccione...</option>

                                                                            <?php


                                                                            $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                                                                                             FROM jefes_tecnicos jt 
                                                                                                             WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                                                                                             AND (idDepartamento = 1 AND jt.userActivado = 1)
                                                                                                             AND nomJefe NOT LIKE('%Encargado Pruebas%')   
                                                                                                             UNION 
                                                                                                             SELECT p.nomPersonal AS nomPersonal
                                                                                                             FROM personal p 
                                                                                                             WHERE departamentoPersonal = 1
                                                                                                             AND activoPersonal = 1 
                                                                                                             AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                                                                                             ORDER BY nomPersonal;";
                                                                            $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                                                            if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                                                                while ($rowListadoFuncionarios = $resListadoFuncionarios->fetch_assoc()) {
                                                                                    $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                                                                    ?>
                                                                                    <option value="<?php echo $nomPersonal; ?>"><?php echo $nomPersonal; ?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">

                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Fecha de Programación del Trabajo
                                                                            <code>*</code></label>
                                                                        <input type="date" class="form-control" name="fechaProgramacion" id="fechaProgramacion">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="nombreCredencial" value="<?php echo $nombreCredencial?>">
                                                            <input type="hidden" name="apellidoCredencial" value="<?php echo $apellidoCredencial?>">
                                                            <input type="hidden" name="rutCredencial" value="<?php echo $rutCredencial?>">
                                                            <input type="hidden" name="idCredencial" value="<?php echo $idCredencial; ?>">
                                                            <!--<input type="hidden" name="activacionCredencial3" value="${row.activacionCredencial3}">-->
                                                            <input type="hidden" name="usuarioCreador" value="<?php echo $nomUsuario; ?>">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php
                                    if($contarHabilitados > 0 || $contarDeshabilitados > 0){
                                        ?>
                                        <div class="col-md-6 mb-2">

                                            <form
                                                    action="../sdo-funciones/habilitar_sacar_credenciales.php"
                                                    method="POST"
                                                    id="formHabilitarCredencial">

                                                <input
                                                        type="hidden"
                                                        name="idCredencial"
                                                        value="<?php echo $idCredencial; ?>">
                                                <input
                                                        type="hidden"
                                                        name="usuarioObs"
                                                        value="<?php echo $nomUsuario; ?>">

                                                <button
                                                        type="submit"
                                                        class="btn btn-success btn-block">

                                                    Habilitar / Deshabilitar Credenciales

                                                </button>

                                            </form>

                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 10pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <!--                      f              <li class="nav-item">-->
                                    <!--                                        <a class="nav-link text-white" href="#tabModificar" data-toggle="tab">Modificar Datos</a>-->
                                    <!--                                    </li>-->
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabLaboral" data-toggle="tab">1.- Relaci&oacute;n Laboral</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabTarjetas" data-toggle="tab">2.- Tarjetas Asociadas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabPerfiles" data-toggle="tab">3.- Perfiles</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabObservaciones" data-toggle="tab">4.- Observaciones</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="tabObservaciones">
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

                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <label for="textoObservacion" class="font-weight-bold mb-0">
                                                                            Escriba su observación <code>*</code>
                                                                        </label>

                                                                        <div class="custom-control custom-switch">
                                                                            <input type="checkbox"
                                                                                    class="custom-control-input"
                                                                                    id="observacionPrivada">

                                                                            <label class="custom-control-label font-weight-bold"
                                                                                    for="observacionPrivada">
                                                                                Informacion interna
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" id="esPrivada" value="0">

                                                                    <textarea id="textoObservacion"
                                                                            rows="5"
                                                                            class="form-control"
                                                                            placeholder="Ej: Solicita extensión de acceso..."></textarea>
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
                                    <div class="tab-pane" id="tabLaboral">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Historial de Relaci&oacute;n Laboral</h5>


                                            <hr>

                                            <div class="row text-center" style="display: flex; justify-content:center;">
                                                <div class="col-lg-3 col-md-4 col-12">

                                                </div>


                                            </div>


                                            <div class="table-responsive">

                                                <input type="hidden" name="idCredencialContratos" id="idCredencialContratos" value="<?php echo $idCredencial; ?>">
                                                <table id="tableContratos" class="table table-bordered table-hover table-sm text-center">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Calidad Juridica</th>
                                                        <th>Cargo</th>
                                                        <th>Unidad</th>
                                                        <th>Jefatura</th>
                                                        <th>Fecha Inicio</th>
                                                        <th>Termino</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Calidad Juridica</th>
                                                        <th>Cargo</th>
                                                        <th>Unidad</th>
                                                        <th>Jefatura</th>
                                                        <th>Fecha Inicio</th>
                                                        <th>Termino</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="tab-pane active" id="tabTarjetas">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Historial de Tarjetas Asociadas</h5>

                                            <hr>

                                            <div class="table-responsive">

                                                <input type="hidden" name="idCredencialTarjeta" id="idCredencialTarjeta" value="<?php echo $idCredencial; ?>">
                                                <table id="tableTarjetas" class="table table-bordered table-hover table-sm text-center">
                                                    <thead>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Texto Tarjeta</th>
                                                        <th>Codigo</th>
                                                        <th>Unidad</th>
                                                        <th>Tipo</th>
                                                        <th>Fecha Inicio</th>
                                                        <th>Fecha Termino</th>
                                                        <th>Tarjeta Activada</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="bg-secondary text-white">
                                                        <th>#</th>
                                                        <th>Texto Tarjeta</th>
                                                        <th>Codigo</th>
                                                        <th>Unidad</th>
                                                        <th>Tipo</th>
                                                        <th>Fecha Inicio</th>
                                                        <th>Fecha Termino</th>
                                                        <th>Tarjeta Activada</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabPerfiles">
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
                <h5 class="mb-0">
                    <i class="fas fa-user-tie"></i> Nueva Jefatura
                </h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <label class="font-weight-bold">Unidad Relacionada
                    <code>*</code></label>
                <input class="form-control" type="text" name="nomUnidadMod" id="nomUnidadMod" readonly="readonly">
                <input type="hidden" name="idUnidadMod" id="idUnidadMod">

                <label class="mt-3 font-weight-bold">Nombre de la Jefatura
                    <code>*</code></label>
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

    function limpiarFormatoRut(rut) {
        return rut.replace(/\./g, '').replace('-', '').toUpperCase();
    }

    function calcularDV(rut) {
        let suma = 0,
            multiplicador = 2;
        for (let i = rut.length - 1; i >= 0; i--) {
            suma += parseInt(rut[i]) * multiplicador;
            multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
        }
        const resto = 11 - (suma % 11);
        if (resto === 11) return '0';
        if (resto === 10) return 'K';
        return resto.toString();
    }

    function validarRutCompleto(rutCompleto) {
        rutCompleto = rutCompleto.trim().toUpperCase();
        if (!/^\d{1,2}\.\d{3}\.\d{3}-[\dK]$/.test(rutCompleto)) return false;

        const rutSinFormato = limpiarFormatoRut(rutCompleto);
        const rut = rutSinFormato.slice(0, -1);
        const dv = rutSinFormato.slice(-1);

        return calcularDV(rut) === dv;
    }

    // Capitaliza cada palabra y parte con guiones: ej. "maría-josé pÉrez-gómez" -> "María-José Pérez-Gómez"
    function capitalizar(valor) {
        return valor
            .toLowerCase()
            .split(' ')
            .map(palabra =>
                palabra
                    .split('-')
                    .map(parte => parte.charAt(0).toUpperCase() + parte.slice(1))
                    .join('-')
            )
            .join(' ');
    }
</script>

<script>
    // Corrige problemas de foco con modales SweetAlert y Bootstrap
    $.fn.modal.Constructor.prototype._enforceFocus = function () {
    };
    // Variables globales
    const idCredencial = $('#idCredencialDatos').val();
    const idCredencialPerfil = $('#idCredencialPerfil').val();
    const idCredencialTarjeta = $('#idCredencialTarjeta').val();
    const nomUsuario = $("#nomUsuarioObs").val();
    const jefaturaSeleccionada = "<?php echo strtoupper(trim($jefaturaCredencial2)); ?>";

    $(document).ready(function () {
        initObservacionTotal();
        initPerfilesCredencial();
        initContratosFuncionario();
        initTarjetasFuncionario();


        $('#modalAgregarRelacion').on('show.bs.modal', function () {
            recargarSelectUnidades();
            $('#nomJefaturaContrato').empty().append('<option value="" disabled selected>Seleccione una jefatura</option>');
            recargarCalidades();
            recargarCargos();
        });

        $('#nomUnidadContrato').on('change', function () {
            const idUnidad = $(this).val();
            recargarSelectJefaturas(idUnidad);
        });

        $('.select2').select2({width: '100%'});
        $('[data-toggle="tooltip"]').tooltip();


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
                url: "/sdo-includes/credenciales_rrhh_funcionario_observaciones_control.php",
                type: "POST",
                data: {idCredencialDatos: idCredencial},
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
                {data: "fecha"},
                {data: "usuario"},
                {data: "tipo"},
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
                data: d => {
                    d.idCredencialPerfil = idCredencialPerfil;
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {data: "nomPerfil"},
                {data: "descripPerfil"},
                {data: "idPerfil"},
                {data: "perfilActivado"}
            ],
            columnDefs: [
                {
                    width: "30%",
                    targets: 1
                },
                {
                    width: "40%",
                    targets: 2
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

    function initContratosFuncionario() {
        $('#tableContratos').DataTable({
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
                url: "/sdo-includes/credenciales_rrhh_contrato_datos.php",
                type: "POST",
                data: {idCredencialContratos: idCredencial},
                dataSrc: json => Array.isArray(json) ? json : [],
                error: function (xhr) {
                    console.error("❌ Error AJAX:", xhr.responseText);
                    toastr.error("Error al cargar los contratos.");
                }
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {data: "calidadContrato"},
                {data: "cargoContrato"},
                {data: "unidadContrato"},
                {data: "jefaturaContrato"},
                {data: "inicioContrato2"},
                {data: "terminoContrato2"},
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
            data: {idUnidad: idUnidad},
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

    function initTarjetasFuncionario() {

        let idCredencialTarjeta = $('#idCredencialTarjeta').val();

        $('#tableTarjetas').DataTable({
            destroy: true,
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
                url: "/sdo-includes/credenciales_rrhh_tarjetas_asociadas.php",
                type: "POST",
                data: {
                    idCredencialCargar: idCredencialTarjeta
                },
                dataSrc: function (json) {
                    return json.data || [];
                },
                error: function (xhr) {
                    console.error("❌ Error AJAX:", xhr.responseText);
                    toastr.error("Error al cargar las tarjetas.");
                }
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: "textoTarjeta" },
                { data: "codigoTarjeta" },
                { data: "unidadTarjeta" },
                { data: "tipoTarjeta" },
                { data: "inicioTarjeta2" },
                { data: "terminoTarjeta2" },
                { data: "activacionCredencial2" },
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




    // === AGREGAR OBSERVACIÓN ===
    $("#btnAgregarObservacion").click(function () {
        const texto = $("#textoObservacion").val().trim();
        const usuario = nomUsuario;
        const id = $("#idCredencialObs").val();
        const esPrivada = $("#esPrivada").val();

        if (texto === "") {
            Swal.fire("Observación vacía", "Debes ingresar una observación antes de guardar.", "warning");
            return;
        }

        const btn = $(this);
        btn.prop("disabled", true);

        $.ajax({
            url: "/sdo-includes/credenciales_rrhh_insertar_observacion_control.php",
            type: "POST",
            dataType: "json",
            data: {
                observacion: texto,
                idCredencial: id,
                usuario,
                esPrivada: esPrivada
            },
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

    $("#btnAddUnidad").click(() => {
        Swal.fire({
            title: "Agregar nueva Unidad",
            input: "text",
            inputLabel: "Nombre de la Unidad",
            inputPlaceholder: "Ej: Ingeniería Clínica",
            inputAttributes: {
                maxlength: 100,
                autocapitalize: "off",
                autocorrect: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            inputValidator: (value) => {
                if (!value.trim()) {
                    return "El nombre de la unidad no puede estar vacío.";
                }
            },
            preConfirm: (nombre) => {
                return $.post("/sdo-includes/credenciales_rrhh_contrato_unidad.php", {nombreUnidad: nombre.trim().toUpperCase()})
                    .then(response => {
                        if (response.status === "ok") {
                            recargarSelectUnidades(response.id); // <-- Se selecciona la nueva
                            return true;
                        } else if (response.status === "existe") {
                            Swal.showValidationMessage("⚠️ La unidad ya existe.");
                        } else {
                            Swal.showValidationMessage("❌ Error al guardar: " + response.mensaje);
                        }
                    })

                    .catch(() => {
                        Swal.showValidationMessage("❌ Error de conexión con el servidor.");
                    });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.success("Unidad agregada correctamente");
            }
        });
    });

    $("#btnAddJefatura").click(() => {
        const selectUnidad = $("#nomUnidadContrato");
        const idUnidad = selectUnidad.val();
        const nomUnidad = selectUnidad.find("option:selected").text();

        if (!idUnidad) {
            Swal.fire("Unidad no seleccionada", "Debes seleccionar una unidad antes de agregar jefatura.", "warning");
            return;
        }

        Swal.fire({
            title: `Agregar nueva Jefatura`,
            html: `<p style="margin-bottom:10px;"><strong>Unidad:</strong> ${nomUnidad}</p>`,
            input: "text",
            inputLabel: "Nombre de la Jefatura",
            inputPlaceholder: "Ej: Coordinación Técnica",
            inputAttributes: {
                maxlength: 100,
                autocapitalize: "off",
                autocorrect: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            inputValidator: (value) => {
                if (!value.trim()) {
                    return "El nombre de la jefatura no puede estar vacío.";
                }
            },
            preConfirm: (nombre) => {
                return $.post("/sdo-includes/credenciales_rrhh_contrato_jefatura.php", {
                    nombreJefatura: nombre.trim().toUpperCase(),
                    idUnidad: idUnidad
                })
                    .then(response => {
                        if (response.status === "ok") {
                            recargarSelectJefaturas(idUnidad, response.id); // <-- Se selecciona la nueva
                            return true;
                        } else if (response.status === "existe") {
                            Swal.showValidationMessage("⚠️ La jefatura ya existe en esta unidad.");
                        } else {
                            Swal.showValidationMessage("❌ Error al guardar: " + response.mensaje);
                        }
                    })
                    .catch(() => {
                        Swal.showValidationMessage("❌ Error de conexión con el servidor.");
                    });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.success("Jefatura agregada correctamente");
            }
        });
    });


    function recargarSelectUnidades(idSeleccionado = null) {
        $.getJSON("/sdo-includes/credenciales_rrhh_unidades.php", function (data) {
            const $select = $('#nomUnidadContrato');
            $select.empty();

            // Opción por defecto
            $select.append('<option value="" disabled selected>Seleccione una unidad</option>');

            if (Array.isArray(data)) {
                data.forEach(op => {
                    const opcion = new Option(op.text, op.id, false, op.id == idSeleccionado);
                    $select.append(opcion);
                });
                $select.trigger('change');
            }
        });
    }


    function recargarSelectJefaturas(idUnidad, idSeleccionado = null) {
        if (!idUnidad) return;

        $.post("/sdo-includes/credenciales_rrhh_unidades_jefaturas.php", {unidad: idUnidad}, function (data) {
            const $select = $('#nomJefaturaContrato');
            $select.empty();

            $select.append('<option value="" disabled selected>Seleccione una jefatura</option>');

            if (Array.isArray(data)) {
                data.forEach(op => {
                    const opcion = new Option(op.nombre.toUpperCase(), op.id, false, op.id == idSeleccionado);
                    $select.append(opcion);
                });
                $select.trigger('change');
            }
        }, "json");
    }


    // Recargar calidades
    function recargarCalidades(idSeleccionado = null) {
        $.getJSON('/sdo-includes/credenciales_rrhh_calidades.php', function (data) {
            const $select = $('#calidadContrato');
            $select.empty().append('<option value="" disabled selected>Seleccione una calidad jurídica</option>');

            data.forEach(op => {
                const opcion = new Option(op.text, op.id, false, op.id == idSeleccionado);
                $select.append(opcion);
            });

            $select.trigger('change');
        });
    }

    // Recargar cargos
    function recargarCargos(idSeleccionado = null) {
        $.getJSON('/sdo-includes/credenciales_rrhh_cargos.php', function (data) {
            const $select = $('#cargoContrato');
            $select.empty().append('<option value="" disabled selected>Seleccione un cargo</option>');

            data.forEach(op => {
                const opcion = new Option(op.text, op.id, false, op.id == idSeleccionado);
                $select.append(opcion);
            });

            $select.trigger('change');
        });
    }

    // Agregar nueva calidad jurídica
    $("#btnAddCalidad").click(() => {
        Swal.fire({
            title: "Agregar nueva Calidad Jurídica",
            input: "text",
            inputPlaceholder: "Ej: Contrata, Honorarios",
            showCancelButton: true,
            confirmButtonText: "Guardar",
            preConfirm: (nombre) => {
                nombre = nombre.trim().toUpperCase();
                if (!nombre) return Swal.showValidationMessage("Debe ingresar un nombre");

                return $.post("/sdo-includes/credenciales_rrhh_contrato_calidad.php", {nombre})
                    .then(response => {
                        if (response.status === "ok") {
                            recargarCalidades(response.id);
                            return true;
                        } else if (response.status === "existe") {
                            Swal.showValidationMessage("⚠️ Ya existe esa calidad jurídica.");
                        } else {
                            Swal.showValidationMessage("❌ Error: " + response.mensaje);
                        }
                    });
            }
        });
    });

    // Agregar nuevo cargo
    $("#btnAddCargo").click(() => {
        Swal.fire({
            title: "Agregar nuevo Cargo",
            input: "text",
            inputPlaceholder: "Ej: Técnico en Mantenimiento",
            showCancelButton: true,
            confirmButtonText: "Guardar",
            preConfirm: (nombre) => {
                nombre = nombre.trim().toUpperCase();
                if (!nombre) return Swal.showValidationMessage("Debe ingresar un nombre");

                return $.post("/sdo-includes/credenciales_rrhh_contrato_cargo.php", {nombre})
                    .then(response => {
                        if (response.status === "ok") {
                            recargarCargos(response.id);
                            return true;
                        } else if (response.status === "existe") {
                            Swal.showValidationMessage("⚠️ Ya existe ese cargo.");
                        } else {
                            Swal.showValidationMessage("❌ Error: " + response.mensaje);
                        }
                    });
            }
        });
    });

    $(document).on('click', '#btnAgregarRelacion', () => {
        const data = {
            idCredencial: $('#idCredencialRelacion').val(),
            idCalidad: $('#calidadContrato').val(),
            idCargo: $('#cargoContrato').val(),
            idUnidad: $('#nomUnidadContrato').val(),
            idJefatura: $('#nomJefaturaContrato').val(),
            inicio: $('#inicioContrato').val(),
            termino: $('#terminoContrato').val(),

            nomCalidad: $('#calidadContrato option:selected').text(),
            nomCargo: $('#cargoContrato option:selected').text(),
            nomUnidad: $('#nomUnidadContrato option:selected').text(),
            nomJefatura: $('#nomJefaturaContrato option:selected').text()
        };

        if (!data.idCalidad || !data.idCargo || !data.idUnidad || !data.idJefatura || !data.inicio) {
            toastr.warning('Debes completar todos los campos obligatorios');
            return;
        }

        $.post('/sdo-funciones/credenciales_rrhh_guardar_relacion.php', data, function (resp) {
            console.log(resp); // para debug
            if (resp.status === 'ok') {
                toastr.success('Relación laboral registrada');
                $('#modalAgregarRelacion').modal('hide');
                $('#tableContratos').DataTable().ajax.reload(null, false);
                $('#observacionTotal').DataTable().ajax.reload(null, false);
            } else if (resp.status === 'existe') {
                Swal.fire('Duplicado', resp.mensaje, 'warning');
            } else {
                Swal.fire('Error', resp.mensaje || 'No se pudo guardar', 'error');
            }
        }, 'json');
    });

    $(document).ready(function () {
        $('#modalAgregarTarjeta').on('show.bs.modal', function () {
            const idCredencial = $('#idCredencialTarjeta').val();
            $('#idCredencialTarjetaModal').val(idCredencial);

            // Limpia campos
            $('#codigoTarjeta').val('').prop('readonly', false);
            $('#tipoTarjeta, #unidadTarjeta, #textoTarjeta').val('');
        });

        $('#codigoTarjeta').on('blur', function () {
            const codigo = $(this).val().trim();
            if (codigo === '') return;

            $.ajax({
                url: '/sdo-includes/credenciales_rrhh_buscar_tarjeta.php',
                method: 'POST',
                data: {codigoTarjeta: codigo},
                dataType: 'json',
                success: function (response) {
                    if (response.existe) {
                        $('#tipoTarjeta').val(response.tipoTarjeta).prop('readonly', true);
                        $('#unidadTarjeta').val(response.unidadTarjeta).prop('readonly', true);
                        $('#textoTarjeta').val(response.textoTarjeta).prop('readonly', true);
                        $('#codigoTarjeta').prop('readonly', false);
                    } else {
                        toastr.warning('La tarjeta no existe, se creará nueva al guardar');

                        // 🔓 Habilita inputs para permitir ingreso manual
                        $('#tipoTarjeta').val('').prop('readonly', false);
                        $('#unidadTarjeta').val('').prop('readonly', false);
                        $('#textoTarjeta').val('').prop('readonly', false);
                    }
                },
                error: function () {
                    toastr.error('Error al buscar tarjeta');
                }
            });
        });

        $('#formAgregarTarjeta').on('submit', function (e) {
            e.preventDefault();

            // Si no están readonly, deben estar completos
            if (!$('#tipoTarjeta').prop('readonly') && $('#tipoTarjeta').val().trim() === '') {
                toastr.warning('Debe ingresar el tipo de tarjeta');
                return;
            }
            if (!$('#unidadTarjeta').prop('readonly') && $('#unidadTarjeta').val().trim() === '') {
                toastr.warning('Debe ingresar la unidad');
                return;
            }
            if (!$('#textoTarjeta').prop('readonly') && $('#unidadTarjeta').val().trim() === '') {
                toastr.warning('Debe ingresar el texto de la tarjeta');
                return;
            }

            $.ajax({
                url: '/sdo-includes/credenciales_rrhh_asociar_tarjeta.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'ok') {
                        toastr.success('Tarjeta asociada correctamente');
                        $('#modalAgregarTarjeta').modal('hide');
                        $('#tableTarjetas').DataTable().ajax.reload(null, false);
                        $('#observacionTotal').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.mensaje);
                    }
                },
                error: function () {
                    toastr.error('Error al guardar tarjeta');
                }
            });
        });

    });

    $(document).on('click', '[data-idcreddesh]', function () {
        const id = $(this).data('idcreddesh');

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'La tarjeta será marcada como "Por deshabilitar"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/sdo-includes/credenciales_rrhh_deshabilitar_tarjeta.php', {idRegistro: id}, function (resp) {
                    if (resp.status === 'ok') {
                        toastr.success(resp.mensaje);
                        $('#tableTarjetas').DataTable().ajax.reload(null, false);
                        $('#observacionTotal').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(resp.mensaje || 'Error al deshabilitar tarjeta');
                    }
                }, 'json');
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


</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formModificarCredenciales');

        if (!form) {
            console.error('Formulario NO encontrado');
            return;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log("Formulario enviado"); // PRUEBA VISUAL

            const rutInput = document.getElementById('rutCredencial');
            const nombreInput = document.getElementById('nombreCredencial');
            const apellidoInput = document.getElementById('apellidoCredencial');
            const telefonoInput = document.getElementById('telefonoCredencial');
            const soloLetrasEspaciosGuiones = /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s\-]+$/;

            // Validación RUT
            if (!validarRutCompleto(rutInput.value)) {
                toastr.error('RUT inválido. Verifica número y dígito verificador.', 'Error en RUT');
                rutInput.focus();
                return;
            }

            // Validación nombre
            if (!soloLetrasEspaciosGuiones.test(nombreInput.value.trim())) {
                toastr.error('El nombre solo puede incluir letras, espacios y guiones.', 'Error en Nombre');
                nombreInput.focus();
                return;
            }

            // Validación apellido
            if (!soloLetrasEspaciosGuiones.test(apellidoInput.value.trim())) {
                toastr.error('El apellido solo puede incluir letras, espacios y guiones.', 'Error en Apellido');
                apellidoInput.focus();
                return;
            }

            const formData = new FormData(form);

            fetch('/sdo-funciones/credenciales_rrhh_modificar.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        // Mostrar spinner overlay
                        const overlay = document.getElementById('spinnerOverlay');
                        const btnGuardar = document.getElementById('btnGuardarCambios');

                        if (overlay) overlay.style.display = 'flex';
                        if (btnGuardar) btnGuardar.disabled = true;

                        // Deshabilitar todos los campos del formulario
                        [...form.elements].forEach(el => el.disabled = true);

                        toastr.success("Cambios guardados correctamente", "✅ Éxito");

                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(data.mensaje);

                        // Rehabilitar botón si hubo error lógico
                        const btnGuardar = document.getElementById('btnGuardarCambios');
                        if (btnGuardar) btnGuardar.disabled = false;
                    }
                })
                .catch(error => {
                    toastr.error("Error en la solicitud AJAX");
                    console.error(error);

                    // Ocultar overlay y reactivar botón
                    const overlay = document.getElementById('spinnerOverlay');
                    const btnGuardar = document.getElementById('btnGuardarCambios');

                    if (overlay) overlay.style.display = 'none';
                    if (btnGuardar) btnGuardar.disabled = false;
                });

        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkbox = document.getElementById("observacionPrivada");
        const hidden = document.getElementById("esPrivada");

        checkbox.addEventListener("change", function () {
            hidden.value = this.checked ? "1" : "0";
        });
    });
</script>
<script>

    document
        .getElementById('formHabilitarCredencial')
        .addEventListener('submit', function(e) {

            e.preventDefault();

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción cambiará el estado de las credenciales.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    this.submit();

                }

            });

        });

</script>
<!-- Spinner Fullscreen -->
<div id="spinnerOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    text-align: center;
">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
        <span class="sr-only">Cargando...</span>
    </div>
    <div class="mt-3 font-weight-bold text-dark" style="font-size: 1.2rem;">
        Guardando cambios...
    </div>
</div>
<style>
    #spinnerOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>

</body>

</html>
