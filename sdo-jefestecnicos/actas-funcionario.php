<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['rutActa2']) || isset($_SESSION['rutActa2'])) {

    $rutActa2 = isset($_POST['rutActa2']) ? trim($_POST['rutActa2']) : trim($_SESSION['rutActa2']);

    if (empty($rutActa2)) {
        header("Location: ../$ruta/actas-listado.php?errorModificarActa=true");
        exit;
    }

    $idActa = isset($_POST['idActa']) ? trim($_POST['idActa']) : (isset($_SESSION['idActa']) ? trim($_SESSION['idActa']) : '');

    if (empty($idActa) || $idActa < 1) {
        header("Location: ../$ruta/actas-listado.php?errorModificarActa=true");
        exit;
    }

    // Guardar en sesión si viene desde POST
    if (isset($_POST['rutActa2'])) {
        $_SESSION['rutActa2'] = $rutActa2;
        $_SESSION['idActa'] = $idActa;
    }

    $datosActa = getActa($idActa);

    // Asignar y convertir variables
    $idActa2 = $datosActa['idActa'];
    $folioActa = $datosActa['folioActa'];
    $fechaActa = $datosActa['fechaActa'];
    $rutTarjeta = $datosActa['rutTarjeta'];
    $codigoTarjeta = mb_convert_case($datosActa['codigoTarjeta'], MB_CASE_UPPER, "UTF-8");
    $nomTarjeta = $datosActa['nomTarjeta'];
    $unidadTarjeta = mb_convert_case($datosActa['unidadTarjeta'], MB_CASE_TITLE, "UTF-8");
    $nomSolicitante = $datosActa['nomSolicitante'];
    $nomControlCentralizado = $datosActa['nomControlCentralizado'];
    $calidadContractual = $datosActa['calidadContractual'];
    $numTelefono = $datosActa['numTelefono'];
    $tipoActa = $datosActa['tipoActa'];
    $personaDiscapacitada = $datosActa['personaDiscapacitada'];
    $personaBecada = $datosActa['personaBecada'];

    $origen = isset($_POST['origen']) ? $_POST['origen'] : 'actas-listado.php';


} else {
    header('Location: ../'.$ruta.'/actas-listado.php?errorModificarActa=true');
    exit;
}

$sqlObs = "SELECT * FROM actas_observacion WHERE idActa = $idActa";
$resObs = mysqli_query($conexion, $sqlObs);
while ($rowObs = $resObs->fetch_assoc()) {
    $idActasObs = $rowObs['idActasObs'];
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informaci&oacute;n del Funcionario</title>

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
    include '../sdo-templates/jefes-sidebar.php';
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
                            <div class="card-header">
                                <a href="<?php echo htmlspecialchars($origen); ?>" style="float: right;" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php
                    function mostrarAlerta($mensaje, $tipo) {
                        echo "<script>
            Swal.fire(
                '$mensaje',
                '',
                '$tipo'
            )
          </script>";
                    }

                    $alertas = [
                        'exitoModActa' => ['Modificación de Acta con Éxito.', 'success'],
                        'errorModActa' => ['Error al Modificar Acta. Reintente.', 'error'],
                        'exitoAgregarPatente' => ['Vehículo Agregado con Éxito.', 'success'],
                        'exitoModPatente' => ['Vehículo Modificado con Éxito.', 'success'],
                        'errorMaximoParking' => ['No es posible registrar más de 20 Vehículos por Usuario.', 'error'],
                        'errorPatenteExiste' => ['Patente de Vehículo ya se encuentra registrada. Reintente.', 'error'],
                        'errorAgregarPatente' => ['Error al Agregar Vehículo. Reintente.', 'error'],
                        'errorModPatente' => ['Error al Modificar Vehículo. Reintente.', 'error'],
                        'exitoEliminarPatente' => ['Vehículo Eliminado con Éxito.', 'success'],
                        'errorEliminarPatente' => ['Error al Eliminar Vehículo. Reintente.', 'error'],
                        'exitoObservacion' => ['Observación ingresada con éxito.', 'success'],
                    ];

                    foreach ($alertas as $param => $alerta) {
                        if (isset($_GET[$param])) {
                            mostrarAlerta($alerta[0], $alerta[1]);
                        }
                    }
                    ?>

                    <div class="col-md-8 col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="table-responsive">
                                    <table style="font-size: 15px;" class="table table-bordered table-sm">
                                        <tbody>
                                        <?php
                                        // Convertir las variables necesarias a mayúsculas o títulos
                                        $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_UPPER, "UTF-8");
                                        $rutTarjeta = mb_convert_case($rutTarjeta, MB_CASE_UPPER, "UTF-8");
                                        $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, "UTF-8");
                                        $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_UPPER, "UTF-8");
                                        $calidadContractual = !empty(trim($calidadContractual)) ? mb_convert_case($calidadContractual, MB_CASE_TITLE, "UTF-8") : '';
                                        $numTelefono = !empty(trim($numTelefono)) ? mb_convert_case($numTelefono, MB_CASE_TITLE, "UTF-8") : '';
                                        $personaDiscapacitada = !empty(trim($personaDiscapacitada)) ? ($personaDiscapacitada == 1 ? 'Sí' : 'No') : '';
                                        $personaBecada = !empty(trim($personaBecada)) ? ($personaBecada == 1 ? 'Sí' : 'No') : '';

                                        $obtenerStatusCredencialCCAA = obtenerStatusCredencialCCAA($rutTarjeta);

                                        $obtenerFechaCaducidadACM = obtenerFechaCaducidadACM($rutTarjeta);

                                        if ($obtenerFechaCaducidadACM) {
                                            $textoCaducidad = "Caduca el: " . date("d-m-Y", strtotime($obtenerFechaCaducidadACM));
                                        } else {
                                            $textoCaducidad = "Sin fecha registrada";
                                        }
                                        $textoCaducidad = mb_convert_case($textoCaducidad, MB_CASE_UPPER, "UTF-8");


                                        $obtenerStatusCredencialActas = obtenerStatusCredencialActas($idActa);

                                        // Fecha formateada
                                        $fechaParaFormatear = strtotime($fechaActa);
                                        $fechaActa2 = fechaEspañol2($fechaParaFormatear);
                                        $horaActa = date('H:i', strtotime($fechaActa));

                                        // Tabla con los datos
                                        $filas = [
                                            ['Nombre', $nomTarjeta],
                                            ['RUT', $rutTarjeta],
                                            ['Codigo Tarjeta', $codigoTarjeta],
                                            ['Unidad', $unidadTarjeta],
                                            ['Ingreso/Actualización', "$fechaActa2 a las $horaActa hrs."],

                                        ];

                                        // Agregar filas opcionales
                                        if (!empty($calidadContractual)) $filas[] = ['Calidad Contractual', $calidadContractual];
                                        if (!empty($numTelefono)) $filas[] = ['N° Telefono', $numTelefono];
                                        if (!empty($personaDiscapacitada)) $filas[] = ['Discapacidad', $personaDiscapacitada];
                                        if (!empty($personaBecada)) $filas[] = ['Beca', $personaBecada];

                                        $filas[] = ['Estado de Credencial', $obtenerStatusCredencialCCAA]; // Llamada a la función para obtener el estado
                                        $filas[] = ['Fecha Bloqueo en SmartPSS', $textoCaducidad]; // Llamada a la función para obtener el estado


                                        // Generar las filas de la tabla
                                        foreach ($filas as $fila) {
                                            // Para el estado de credencial aplicamos los estilos condicionales
                                            if ($fila[0] == 'Estado de Credencial') {
                                                $estadoCredencial = $fila[1];
                                                $estilo = '';
                                                if ($estadoCredencial == 'Sin Credencial en SmartPSS' || $estadoCredencial == 'Credencial Bloqueada en SmartPSS' || $estadoCredencial == 'Estado Desconocido en SmartPSS') {
                                                    $estilo = "style='font-weight: bold; color: #ff0707; width: 40%'";
                                                } elseif ($estadoCredencial == 'Credencial Activada en SmartPSS') {
                                                    $estilo = "style='font-weight: bold; color: #00A100; width: 40%;'";
                                                } else {
                                                    $estilo = "style='width: 40%;'";
                                                }

                                                if ($obtenerStatusCredencialActas == 'Funcionario(a) Habilitado(a) en Sistema de Actas') {
                                                    $estilo2 = "style='font-weight: bold; color: #00A100; width: 40%;'";
                                                } else {
                                                    $estilo2 = "style='font-weight: bold; color: #ff0707; width: 40%'";
                                                }


                                                echo "<tr><td style='width: 20%'><b>{$fila[0]}:</b></td><td $estilo>{$fila[1]}</td><td $estilo2 >$obtenerStatusCredencialActas</td></tr>";
                                            } else {
                                                echo "<tr><td style='width: 20%'><b>{$fila[0]}:</b></td><td colspan='2' style='width: 80%'>{$fila[1]}</td></tr>";
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="row mb-3">

                                    <div class="col-12 mb-3">

                                        <?php
                                        if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                            ?>
                                            <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Acta</button>
                                            <?php
                                        } else {

                                            ?>

                                            <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Acta.php" method="post">

                                                <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Acta</button>

                                            </form>

                                            <?php

                                        }
                                        ?>

                                    </div>

                                    <!-- MODIFICAR FUNCIONARIO -->
                                    <div class="col-12 mb-3">

                                        <?php
                                        if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                            ?>
                                            <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Funcionario</button>
                                            <?php
                                        } else {

                                            ?>

                                            <form style="margin: 0; text-align: center;" class="" action="actas-modificar.php" method="post">

                                                <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Funcionario</button>

                                            </form>

                                            <?php

                                        }
                                        ?>

                                    </div>
                                    <!-- FIN MODIFICAR FUNCIONARIO -->


                                    <!-- AGREGAR ESTACIONAMIENTO -->
                                    <div class="col-12 mb-3">

                                        <?php
                                        if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                            ?>
                                            <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block bg-purple"><i class="fas fa-parking"></i>&nbsp;&nbsp;Agregar Estacionamiento</button>
                                            <?php
                                        } else {

                                            ?>

                                            <form method="post" action="../sdo-funciones/estacionamiento-agregar.php">

                                                <button class="btn bg-purple btn-block" data-toggle="modal" data-target="#modalAddEstacionamiento" type="button"><i class="fa fa-parking"></i>&nbsp;&nbsp;Agregar Estacionamiento</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modalAddEstacionamiento" name="modalAddEstacionamiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Estacionamiento</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                    <input type="text" style="text-transform:uppercase" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($rutTarjeta), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                    <input style="text-align:left; text-transform:uppercase;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $rutTarjeta; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                    <input type="text" style="text-transform:uppercase" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                    <input style="text-align:left; text-transform:uppercase;" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                                <input type="hidden" name="rutTarjeta" id="rutTarjeta" value="<?php echo $rutTarjeta; ?>">
                                                                <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="<?php echo $nomUsuario; ?>">
                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <?php

                                        }
                                        ?>

                                    </div>


                                    <!-- FIN AGREGAR ESTACIONAMIENTO -->

                                    <div class="col-12 mb-3">
                                        <button class="btn btn-block bg-success" data-toggle="modal" data-target="#modalAgregarObs" type="button"><i class="fa fa-eye"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                        <div class="modal fade" id="modalAgregarObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion al Acta</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/ingresar-observacion-acta.php" method="POST">
                                                        <div class="modal-body">

                                                            <label class="w-auto">Usuario Creador:</label>
                                                            <input type="text" readonly class="form-control form-control-border" name="nomUsuario" id="nomUsuario" value="<?php echo $nomUsuario ?>">
                                                            <br>
                                                            <label class="w-auto">Fecha de la Observacion:</label>
                                                            <?php
                                                            date_default_timezone_set('America/Santiago');
                                                            $now = date('Y-m-d H:i:s');
                                                            ?>
                                                            <input type="text" readonly class="form-control form-control-border" name="fechaObs" id="fechaObs" value="<?php echo $now ?>">
                                                            <br>
                                                            <label class="w-auto">Detalle de la Observacion:</label>
                                                            <textarea class="form-control" rows="10" name="detalleObs" id="detalleObs"></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="rutFuncionario" id="rutFuncionario" value="<?php echo $rutTarjeta ?>">
                                                            <input type="hidden" name="nomFuncionario" id="nomFuncionario" value="<?php echo $nomTarjeta ?>">
                                                            <input type="hidden" name="calidadContractual" id="calidadContractual" value="<?php echo $calidadContractual ?>">
                                                            <input type="hidden" name="unidadFuncionario" id="unidadFuncionario" value="<?php echo $unidadTarjeta ?>">
                                                            <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa ?>">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-12 mb-3">
                                        <button class="btn btn-block bg-danger" data-toggle="modal" data-target="#modalBloqueoFuncionario" type="button"><i class="fa fa-lock"></i>&nbsp;&nbsp;Bloquear Funcionario</button>

                                        <div class="modal fade" id="modalBloqueoFuncionario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="exampleModalLabel">Bloqueo de Funcionario</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/actas_bloquearFuncionario.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <label for="nomUsuario" class="w-auto">Usuario Creador:</label>
                                                                        <input type="text" readonly class="form-control " name="nomUsuario" id="nomUsuario" value="<?php echo $nomUsuario ?>">
                                                                    </div>

                                                                </div>
                                                                <br>
                                                                <div class="row">

                                                                    <div class="col-md-6 col-12">
                                                                        <label for="funcionarioBloqueo" class="w-auto">Funcionario CCAA:</label>
                                                                        <input type="text" readonly class="form-control " name="funcionarioBloqueo" id="funcionarioBloqueo" value="<?php echo $nomTarjeta . ' (' . $rutTarjeta . ')'; ?>">

                                                                        <input type="hidden" name="rutFuncionarioBloqueo" id="rutFuncionarioBloqueo" value="<?php echo $rutTarjeta ?>">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">

                                                                    <div class="col-md-6 col-12">
                                                                        <label for="estadoCCAA" class="w-auto">Estado Sistema CCAA:</label>

                                                                        <?php
                                                                        $buscarRUTenCCAA = buscarRUTenControlAcceso($rutTarjeta);

                                                                        if ($buscarRUTenCCAA > 0) {
                                                                            $colorCCAA = 'text-green font-weight-bold';
                                                                            $estadoCCAA = 'Inscrito en Sistema CCAA';
                                                                        } else {
                                                                            $colorCCAA = 'text-red font-weight-bold';
                                                                            $estadoCCAA = 'Funcionario No Registrado';
                                                                        }

                                                                        ?>

                                                                        <input type="text" readonly class="form-control  <?php echo $colorCCAA; ?>" name="estadoCCAA" id="estadoCCAA" value="<?php echo $estadoCCAA; ?>">

                                                                    </div>

                                                                    <div class="col-md-6 col-12">
                                                                        <label for="estadoActas" class="w-auto">Estado Actas:</label>

                                                                        <?php
                                                                        $buscarRUTenActa = buscarRUTenActa($rutTarjeta);

                                                                        if ($buscarRUTenActa > 0) {
                                                                            $colorActas = 'text-green font-weight-bold';
                                                                            $estadoActa = 'Inscrito en Actas';
                                                                        } else {
                                                                            $colorActas = 'text-red font-weight-bold';
                                                                            $estadoActa = 'Funcionario No Registrado';
                                                                        }


                                                                        ?>

                                                                        <input type="text" readonly class="form-control  <?php echo $colorActas; ?>" name="estadoActas" id="estadoActas" value="<?php echo $estadoActa; ?>">

                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">

                                                                    <div class=" col-12">
                                                                        <label for="funcionarioSolicitante" class="w-auto">Funcionario Solicitante:</label>
                                                                        <input type="text" class="form-control" style="text-transform: capitalize;" name="funcionarioSolicitante" id="funcionarioSolicitante" value="">

                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label for="detalleObs" class="w-auto">Detalle de la Observacion:</label>
                                                                        <textarea class="form-control" rows="7" name="detalleObs" id="detalleObs"></textarea>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div style="text-align:left;" class="form-group">
                                                                            <label for="subidaa" class="w-auto">Seleccione Archivo:</label>
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="archivo_bloqueo" name="archivo_bloqueo" accept=".jpeg, .jpg, .png, .pdf, .eml">
                                                                                <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                            </div>
                                                                            <script>
                                                                                document.getElementById('archivo_bloqueo').addEventListener('change', function () {
                                                                                    var fileName = this.value.split('\\').pop(); // Obtener solo el nombre del archivo
                                                                                    var label = document.querySelector('.custom-file-label');
                                                                                    label.textContent = fileName;
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idFuncionario" id="idFuncionario" value="<?php echo $rutTarjeta ?>">
                                                            <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa ?>">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link text-white active" href="#tabHistorial" data-toggle="tab">Historial</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabEstacionamiento" data-toggle="tab">Estacionamiento</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabPermisos" data-toggle="tab">Permisos</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabSolicitudes" data-toggle="tab">Solicitudes de Accesos</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabBloqueos" data-toggle="tab">Bloqueos / Desbloqueos</a></li>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabMemos" data-toggle="tab">Memorandum</a></li>
                                </ul>
                            </div>


                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabHistorial">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Historial</h5>

                                            <br>

                                            <table style="font-size: 13px; text-transform: uppercase" id="observacionTotal" class="table table-bordered table-sm">
                                                <thead style="background-color: indianred; color: white" class="text-center align-content-center">

                                                <tr>
                                                    <th class="align-content-center">Fecha de Ingreso de la Observacion</th>
                                                    <th class="align-content-center">Creador de la Observacion</th>
                                                    <th class="align-content-center">Nombre del Funcionario</th>
                                                    <th class="align-content-center">Rut del Funcionario</th>
                                                    <th class="align-content-center">Detalle de la Observacion</th>
                                                </tr>

                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlObservaciones = "
    SELECT 'observacion' AS tipo, fechaIngreso AS fecha, creadorObs AS creador, nomFuncionario AS funcionario, rutFuncionario AS rut, detalleObservacion AS detalle, 'vacio' AS solicitante
    FROM actas_observacion
    WHERE idActa = $idActa

    UNION ALL

    SELECT 'bloqueo' AS tipo, fechaBloqueo AS fecha, creadorBloqueo AS creador, funcionarioBloqueo AS funcionario, rutBloqueo AS rut, detalleBloqueo AS detalle, solicitanteBloqueo AS solicitante
    FROM actas_bloqueos
    WHERE actaBloqueo = $idActa

    ORDER BY fecha DESC
";

                                                $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                    $tipo = $rowObservaciones['tipo']; // Puede ser 'observacion' o 'bloqueo'
                                                    $fechaCompleta = $rowObservaciones['fecha'];
                                                    $creador = $rowObservaciones['creador'];
                                                    $funcionario = $rowObservaciones['funcionario'];
                                                    $rut = $rowObservaciones['rut'];
                                                    $detalle = $rowObservaciones['detalle'];
                                                    $solicitante = $rowObservaciones['solicitante'];

                                                    $dateBloqueo = strtotime($fechaCompleta);
                                                    $fechaTexto = fechaEspañol2($dateBloqueo);
                                                    $horaTexto = date('H:i', $dateBloqueo);
                                                    $fechaCompleta = $fechaTexto . '<br>' . $horaTexto;

                                                    if ($tipo === 'bloqueo') {
                                                        $detalle = 'Según lo solicitado por ' . $solicitante . ' se realiza: <br><br>' . $detalle;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center align-content-center">
                                                            <?php echo $fechaCompleta; ?>
                                                        </td>
                                                        <td class="text-center align-content-center">
                                                            <?php echo $creador ?>
                                                        </td>
                                                        <td class="text-center align-content-center">
                                                            <?php echo $funcionario ?>
                                                        </td>
                                                        <td class="text-center align-content-center">
                                                            <?php echo $rut ?>
                                                        </td>
                                                        <td class="text-center align-content-center">
                                                            <?php echo nl2br($detalle) ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                                ?>
                                                </tbody>
                                                <tfoot style="background-color: indianred; color: white" class="text-center">
                                                <tr>
                                                    <th class="align-content-center">Fecha de Ingreso de la Observacion</th>
                                                    <th class="align-content-center">Creador de la Observacion</th>
                                                    <th class="align-content-center">Nombre del Funcionario</th>
                                                    <th class="align-content-center">Rut del Funcionario</th>
                                                    <th class="align-content-center">Detalle de la Observacion</th>
                                                </tr>
                                                </tfoot>
                                            </table>


                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabEstacionamiento">
                                        <div class="col-12">
                                            <h4 class="text-center align-content-center">Estacionamiento</h4>

                                            <br>

                                            <div class="table-responsive">
                                                <table style="font-size: 13px; text-transform: uppercase" id="tablaEstacionamiento" class="table table-bordered table-hover table-sm">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Patente</center>
                                                        </th>
                                                        <th>
                                                            <center>Modelo</center>
                                                        </th>
                                                        <th>
                                                            <center>Modificar</center>
                                                        </th>
                                                        <th>
                                                            <center>Eliminar</center>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php

                                                    $contarPatente = contarPatente($rutActa2);

                                                    if ($contarPatente > 0) {

                                                        $query = 'SELECT * FROM parking WHERE rutParking LIKE "' . $rutActa2 . '"';

                                                        $res = mysqli_query($conexion, $query);

                                                        $numAcceso = 1;

                                                        $totalAcceso = 0;

                                                        while ($row = $res->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td style="vertical-align:middle;">
                                                                    <center><?php echo $numAcceso; ?></center>
                                                                </td>
                                                                <td style="vertical-align:middle;" align="center">
                                                                    <?php
                                                                    $patenteParking = mb_convert_case($row['patenteParking'], MB_CASE_UPPER, "UTF-8");
                                                                    echo $patenteParking;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;" align="center">
                                                                    <?php
                                                                    $modeloParking = mb_convert_case($row['modeloParking'], MB_CASE_UPPER, "UTF-8");
                                                                    echo $modeloParking;

                                                                    ?>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <center>
                                                                        <?php
                                                                        $idParking = $row['idParking'];
                                                                        $idParking = trim($idParking);
                                                                        if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                            ?>
                                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                            <?php
                                                                        } else {

                                                                            ?>

                                                                            <form method="post" action="../sdo-funciones/estacionamiento-modificar.php">
                                                                                <button class="btn btn-warning" data-toggle="modal" data-target="#modalModEstacionamiento<?php echo $numAcceso; ?>" type="button"><i class="fa fa-pen"></i></button>

                                                                                <!-- Modal -->
                                                                                <div class="modal fade" id="modalModEstacionamiento<?php echo $numAcceso; ?>" name="modalModEstacionamiento<?php echo $numAcceso; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-primary">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Estacionamiento</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>

                                                                                            <div class="modal-body">
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Nombre de Usuario de la Tarjeta: <code>*</code></label>
                                                                                                    <input type="text" style="text-transform:uppercase" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Usuario de la Tarjeta" value="<?php echo mb_convert_case(obtenerNombreRutCA($row['rutParking']), MB_CASE_TITLE, "UTF-8"); ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">RUT de Usuario de la Tarjeta: <code>*</code></label>
                                                                                                    <input style="text-align:left; text-transform:uppercase" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="rutAcceso" name="rutAcceso" value="<?php echo $row['rutParking']; ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Patente de Vehiculo: <code>*</code></label>
                                                                                                    <input type="text" style="text-transform:uppercase" class="form-control " id="patenteParking" name="patenteParking" placeholder="Patente de Vehiculo" value="<?php echo $row['patenteParking']; ?>" required>
                                                                                                </div>
                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Modelo del Vehiculo: <code>*</code></label>
                                                                                                    <input style="text-align:left; text-transform:uppercase" type="text" style="text-align:center;" class="form-control " id="modeloParking" name="modeloParking" placeholder="Modelo del Vehiculo" value="<?php echo $row['modeloParking']; ?>" required>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <input type="hidden" name="antiguaPatente" id="antiguaPatente" value="<?php echo $row['patenteParking']; ?>">
                                                                                                <input type="hidden" name="idParking" id="idParking" value="<?php echo $row['idParking']; ?>">
                                                                                                <input type="hidden" name="rutActa2" id="rutActa2" value="<?php echo $rutActa2; ?>">
                                                                                                <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa; ?>">
                                                                                                <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="<?php echo $nomUsuario; ?>">
                                                                                                <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                                                <button type="submit" class="btn btn-primary">Modificar</button>
                                                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>

                                                                            <?php

                                                                        }
                                                                        ?>


                                                                    </center>
                                                                </td>
                                                                <td style="vertical-align:middle;">
                                                                    <center>
                                                                        <?php
                                                                        $idParking = $row['idParking'];
                                                                        $idParking = trim($idParking);
                                                                        if ($idParking == 0 || $idParking == "" || $idParking == " " || $idParking == "  ") {
                                                                            ?>
                                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                            <?php
                                                                        } else {

                                                                            ?>

                                                                            <form action="../sdo-funciones/estacionamiento-eliminar.php" method="post">
                                                                                <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                                                                <input type="hidden" name="idParking" id="idParking" value="<?php echo $idParking; ?>">
                                                                                <button class="btn btn-danger" type="submit" name="eliminarParking<?php echo $idParking; ?>" id="eliminarParking<?php echo $idParking ?>"><i class="fa fa-trash"></i></button>
                                                                            </form>

                                                                            <?php

                                                                        }
                                                                        ?>


                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            <?php

                                                            $numAcceso = $numAcceso + 1;
                                                            $totalAcceso = $totalAcceso + 1;
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="7" align="center">
                                                                No Existen Registros...
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>


                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Patente</center>
                                                        </th>
                                                        <th>
                                                            <center>Modelo</center>
                                                        </th>
                                                        <th>
                                                            <center>Modificar</center>
                                                        </th>
                                                        <th>
                                                            <center>Eliminar</center>
                                                        </th>
                                                    </tr>
                                                    </tfoot>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabPermisos">
                                        <div class="col-12 ">
                                            <h4 class="text-center align-content-center">Listado de Permisos</h4>
                                            <br>

                                            <div class="table-responsive">
                                                <table id="tablaPermisos" class="table table-bordered table-hover table-sm" style="font-size: 13px;">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Nombre</center>
                                                        </th>
                                                        <th>
                                                            <center>Grupo</center>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $queryPuertas = "SELECT ad.name AS nomFinal, ag.DoorGroupName AS grupoPuerta FROM ACS_DOOR_GROUP_USER agu JOIN ACS_DOOR_GROUP_DOOR agd ON agd.DoorGroupID = agu.DoorGroupID JOIN acs_door ad ON ad.idUnique = agd.DoorUniqueID JOIN ACS_DOOR_GROUP ag ON ag.DoorGroupID = agu.DoorGroupID WHERE agu.UserID = '$rutActa2' ORDER BY agd.DoorGroupID ASC;";
                                                    $resPuertas = mysqli_query($conexion, $queryPuertas);

                                                    $numAcceso = 1;
                                                    $puertasArray = array();  // Array para guardar los nombres sin duplicados

                                                    if ($resPuertas && mysqli_num_rows($resPuertas) > 0) {
                                                        while ($rowPuertas = $resPuertas->fetch_assoc()) {
                                                            // Crear un array asociativo con nombre de puerta y grupo
                                                            $nombrePuerta = $rowPuertas['nomFinal'];
                                                            $nombreGrupo = $rowPuertas['grupoPuerta'];

                                                            // Verificar si ya existe una entrada con el mismo nombre de puerta y grupo
                                                            if (!in_array([$nombrePuerta, $nombreGrupo], $puertasArray)) {
                                                                $puertasArray[] = [$nombrePuerta, $nombreGrupo];  // Agregar nombre de puerta y grupo al array si no existe
                                                            }
                                                        }
                                                    }

                                                    // Ordenar el array alfabéticamente por el nombre de la puerta
                                                    usort($puertasArray, function($a, $b) {
                                                        return strcmp($a[0], $b[0]);
                                                    });

                                                    // Mostrar los resultados en la tabla
                                                    $numAcceso = 1;
                                                    foreach ($puertasArray as $puerta) {
                                                        $nombrePuerta = $puerta[0];
                                                        $nombreGrupo = $puerta[1];
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $numAcceso; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $nombrePuerta; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $nombreGrupo; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $numAcceso++;
                                                    }
                                                    Expl


                                                    ?>
                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Nombre</center>
                                                        </th>
                                                        <th>
                                                            <center>Grupo</center>
                                                        </th>
                                                    </tr>
                                                    </tfoot>
                                                </table>

                                                <br>
<!---->
<!--                                                --><?php
//                                                echo $queryPuertas;
//                                                ?>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabSolicitudes">
                                        <div class="col-12 text-center">
                                            <h5>Solicitudes de Accesos</h5>

                                            <br>

                                            <h6 class="text-left font-weight-bold">&nbsp;&nbsp; Colores para Identificar:</h6>
                                            <ul class="text-left">
                                                <li><i style="color: #00ffff; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; <b>Solicitud de Acceso Firmado</b></li>
                                                <li><i style="color: #ffffff; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; <b>Solicitud de Acceso Generada y Sin Firmar</b></li>

                                            </ul>

                                            <br>

                                            <div class="table-responsive">
                                                <table id="tableFormatos" class="table table-bordered align-content-center table-sm" style="font-size: 13px; text-transform: uppercase">
                                                    <thead class="bg-secondary">
                                                    <tr class="text-center align-content-center">
                                                        <th>Folio</th>
                                                        <th>Fecha</th>
                                                        <th>Unidad</th>
                                                        <th>Visualizar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="text-center align-content-center">

                                                    <?php
                                                    $rutFormateado = verificarYFormatearRUT($rutActa2);
                                                    $querySolicitudes = 'SELECT idFormRegAcceso FROM formularios_funcionarios WHERE rutFormFuncCCAA LIKE "%' . $rutFormateado . '%" GROUP BY idFormRegAcceso ORDER BY idFormRegAcceso DESC  ';

                                                    $resSolicitudes = mysqli_query($conexion, $querySolicitudes);

                                                    $numSolicitudes = 1;

                                                    while ($rowSolicitudes = $resSolicitudes->fetch_assoc()) {

                                                        $idSolicitud = $rowSolicitudes['idFormRegAcceso'];

                                                        if ($idSolicitud > 0) {

                                                            $folioSolicitud = getFolioFormRegAcceso($idSolicitud);
                                                            $fechaSolicitud = getFechaFormRegAcceso($idSolicitud);

                                                            $fechaStr = strtotime($fechaSolicitud);

                                                            $fechaPalabras = fechaEspañol2($fechaStr);
                                                            $fechaHora = date("H:i", $fechaStr);

                                                            $concatenarHora = $fechaPalabras . ' a las ' . $fechaHora . ' Hrs.';

                                                            $obtenerRutaFormFirma = obtenerRutaFormFirma($idSolicitud);

                                                            if (!empty($obtenerRutaFormFirma)) {
                                                                $colorTR = 'background-color:#00ffff; color: black;';
                                                            } else {
                                                                $colorTR = 'background-color:#ffffff; color: black;';
                                                            }

                                                            ?>
                                                            <tr style="<?php echo $colorTR; ?>">
                                                                <td style="text-align: center; vertical-align: middle;">
                                                                    <?php echo $folioSolicitud; ?>
                                                                </td>
                                                                <td style="text-align: center; vertical-align: middle;">
                                                                    <?php echo $concatenarHora; ?>
                                                                </td>
                                                                <td style="text-align: center; vertical-align: middle;">
                                                                    <?php
                                                                    $sqlFuncionarios = "SELECT idUnidad FROM formularios_funcionarios WHERE idFormRegAcceso = $idSolicitud GROUP BY idUnidad";
                                                                    $resFuncionarios = mysqli_query($conexion, $sqlFuncionarios);
                                                                    while ($rowFuncionarios = $resFuncionarios->fetch_assoc()) {
                                                                        $idUnidad = $rowFuncionarios['idUnidad'];
                                                                        $buscarNomUnidad = buscarnombreunidad($idUnidad);
                                                                        echo $buscarNomUnidad . '<br>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="text-align: center; vertical-align: middle;">
                                                                    <?php

                                                                    if (!empty($obtenerRutaFormFirma)) {
                                                                        ?>
                                                                        <a href="<?php echo $obtenerRutaFormFirma; ?>" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i></a>
                                                                        <?php
                                                                    } else {
                                                                        ?>

                                                                        <form action="../sdo-pdf/SDO-Form-Solic-Acceso.php" target="_blank" method="POST">
                                                                            <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo $idSolicitud ?>">
                                                                            <button type="submit" class="btn btn-success"><i class="fas fa-file-pdf"></i></button>
                                                                        </form>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }

                                                    }
                                                    ?>
                                                    </tbody>

                                                    <tfoot class="bg-secondary">
                                                    <tr class="text-center align-content-center">
                                                        <th>Folio</th>
                                                        <th>Fecha</th>
                                                        <th>Unidad</th>
                                                        <th>Visualizar</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tabBloqueos">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Bloqueos / Desbloqueos</h5>

                                            <br>
                                            <input type="hidden" name="idActaBloqueo" id="idActaBloqueo" value="<?php echo $idActa; ?>">
                                            <table style="font-size: 13px; text-transform: uppercase" id="tableBloqueo" class="table table-bordered table-sm">
                                                <thead style="background-color: #2852a7; color: white" class="text-center align-content-center">
                                                <tr>
                                                    <th class="align-content-center">Fecha de Bloqueo</th>
                                                    <th class="align-content-center">Creador del Bloqueo</th>
                                                    <th class="align-content-center">Funcionario Bloqueado</th>
                                                    <th class="align-content-center">Funcionario Solicitante</th>
                                                    <th class="align-content-center">Detalle del Bloqueo</th>
                                                    <th class="align-content-center">Comprobante del Bloqueo</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot style="background-color: #2852a7; color: white" class="text-center align-content-center">
                                                <tr>
                                                    <th class="align-content-center">Fecha de Bloqueo</th>
                                                    <th class="align-content-center">Creador del Bloqueo</th>
                                                    <th class="align-content-center">Funcionario Bloqueado</th>
                                                    <th class="align-content-center">Funcionario Solicitante</th>
                                                    <th class="align-content-center">Detalle del Bloqueo</th>
                                                    <th class="align-content-center">Comprobante del Bloqueo</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <script>
                                                $(document).ready(function () {
                                                    var tableBloqueo = $('#tableBloqueo').DataTable({
                                                        "lengthChange": false,
                                                        "searching": true,
                                                        "autoWidth": false,
                                                        "responsive": true,
                                                        "ordering": false,
                                                        "language": {
                                                            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                            "zeroRecords": "No se han encontrado Registros.",
                                                            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                            "infoEmpty": "No existen Registros.",
                                                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                            "search": "Buscar:",
                                                            "processing": "Procesando...",
                                                            "paginate": {
                                                                "previous": "Anterior",
                                                                "next": "Siguiente"
                                                            }
                                                        },
                                                        "ajax": {
                                                            "url": "/sdo-includes/bloqueos_cargar.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                                d.idActaBloqueo = $('#idActaBloqueo').val();
                                                                console.log(d.idActaBloqueo); // Corregido: cierra el paréntesis correctamente y muestra el valor
                                                            },
                                                            "dataSrc": function (json) {
                                                                console.log("Datos recibidos del servidor:", json); // Mostrar en consola los datos recibidos
                                                                return json; // Devuelve los datos para mostrarlos en la tabla
                                                            }
                                                        },
                                                        "columns": [
                                                            {"data": "fechaBloqueo"},
                                                            {"data": "creadorBloqueo"},
                                                            {"data": "funcionarioCompleto"},
                                                            {"data": "solicitanteBloqueo"},
                                                            {
                                                                "data": null, "render": function (data, type, row) {
                                                                    // return '<button class="btn btn-primary btn-detalle" data-detalle="' + row.detalleBloqueoSolic + '"><i class="fas fa-eye">&nbsp;&nbsp;Detalle</button>';
                                                                    return '<button class="btn btn-primary btn-detalle" data-detalle="' + row.detalleBloqueoSolic + '"><i class="fas fa-eye"></button>';
                                                                }
                                                            },
                                                            {
                                                                "data": null, "render": function (data, type, row) {
                                                                    return '<form action="../sdo-pdf/SDO-Comprobante-Bloqueo.php" target="_blank" method="POST">' +
                                                                        '<input type="hidden" name="idBloqueo" id="idBloqueo" value="'+data.idBloqueo+'">' +
                                                                        '<input type="hidden" name="idActa" id="idActa" value="'+data.actaBloqueo+'">' +
                                                                        '<button type="submit" class="btn btn-success"><i class="fas fa-file-pdf"></i></button>' +
                                                                        '</form>';
                                                                }
                                                            },


                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                                                        }
                                                    });

                                                    // Evento para capturar el click en el botón
                                                    $('#tableBloqueo tbody').on('click', '.btn-detalle', function () {
                                                        var detalleBloqueo = $(this).data('detalle'); // Obtiene el detalle del atributo 'data-detalle'
                                                        $('#modalContent').html(detalleBloqueo); // Inserta el detalle en el modal
                                                        $('#detalleModal').modal('show'); // Muestra el modal
                                                    });
                                                });
                                            </script>
                                            <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color: #2852a7; color: white">
                                                            <h5 class="modal-title" id="detalleModalLabel">Detalle del Bloqueo</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body" id="modalContent">
                                                            <!-- Aquí se mostrará el detalle del bloqueo -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabMemos">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Memorandum</h5>
                                            <br>
                                            <table style="font-size: 13px; text-transform: uppercase;text-align: center" id="tableMemo" class="table table-bordered table-sm">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th>Fecha de Emisión del Documento</th>
                                                    <th>Fecha de Recepcion del Documento</th>
                                                    <th>Nombre del Emisor</th>
                                                    <th>Nombre de la Unidad Creadora</th>
                                                    <th>Numero Del Memo</th>
                                                    <th>Detalles</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlFuncionariosMemo = "SELECT * FROM `memos_funcionarios` WHERE rutFuncionario = $rutTarjeta";
                                                $resFuncionariosMemo = mysqli_query($conexion,$sqlFuncionariosMemo);
                                                while($rowFuncionariosMemo = $resFuncionariosMemo->fetch_assoc()){
                                                    $idMemo = $rowFuncionariosMemo['idMemo'];
                                                    $sqlMemos = "SELECT * FROM memos_control_acceso WHERE id = $idMemo";
                                                    $resMemos = mysqli_query($conexion,$sqlMemos);
                                                    while($rowMemos = $resMemos->fetch_assoc()){

                                                        $fechaEmisionMemo = $rowMemos['fechaEmision'];
                                                        $fechaRecepcionMemo = $rowMemos['fechaRecepcion'];
                                                        $nomEmisor = $rowMemos['nomEmisor'];
                                                        $unidadMemo = $rowMemos['unidad'];
                                                        $numeroMemo = $rowMemos['numeroMemo'];
                                                        $idMemo = $rowMemos['id'];

                                                        $sqlArchivo = "SELECT * FROM `archivos_memo_control_acceso` WHERE idMemo = $idMemo";
                                                        $resArchivo = mysqli_query($conexion,$sqlArchivo);
                                                        while($rowArchivo = $resArchivo->fetch_assoc()){
                                                            $rutaArchivo = $rowArchivo['rutaArchivo'];
                                                        }

                                                        ?>
                                                        <tr>
                                                            <td><?php echo date('d/m/Y', strtotime($fechaEmisionMemo)); ?></td>
                                                            <td><?php echo date('d/m/Y', strtotime($fechaRecepcionMemo)); ?></td>
                                                            <td><?php echo $nomEmisor?></td>
                                                            <td><?php echo $unidadMemo?></td>
                                                            <td><?php echo $numeroMemo?></td>
                                                            <td>
                                                                <?php
                                                                if($rutaArchivo == '' || empty($rutaArchivo)){
                                                                    echo 'Sin Archivo';
                                                                }else{
                                                                    ?>
                                                                    <form action="memo_control_acceso.php" target="_blank" method="POST" style="display: inline">
                                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i></button>
                                                                    </form>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th>Fecha de Emisión del Documento</th>
                                                    <th>Fecha de Recepcion del Documento</th>
                                                    <th>Nombre del Emisor</th>
                                                    <th>Nombre de la Unidad Creadora</th>
                                                    <th>Numero Del Memo</th>
                                                    <th>Detalles</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <script>
                                                $(document).ready(function () {
                                                    var tableBloqueo = $('#tableMemo').DataTable({
                                                        "lengthChange": false,
                                                        "searching": true,
                                                        "autoWidth": false,
                                                        "responsive": true,
                                                        "ordering": false,
                                                        "language": {
                                                            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                            "zeroRecords": "No se han encontrado Registros.",
                                                            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                            "infoEmpty": "No existen Registros.",
                                                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                            "search": "Buscar:",
                                                            "processing": "Procesando...",
                                                            "paginate": {
                                                                "previous": "Anterior",
                                                                "next": "Siguiente"
                                                            }
                                                        }
                                                    });
                                                });
                                            </script>

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
    $(function () {

        $('#tablaPermisos').DataTable({
            "order": [[1, 'ASC']],
            "pageLength": 15,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando _TOTAL_ funcionarios",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        }).buttons().container().appendTo('#tablaPermisos_wrapper .col-md-6:eq(0)');

        $('#tablaEstacionamiento').DataTable({
            "order": [[1, 'ASC']],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando _TOTAL_ funcionarios",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        }).buttons().container().appendTo('#tablaEstacionamiento_wrapper .col-md-6:eq(0)');

        $('#observacionTotal').DataTable({
            "order": [[1, 'ASC']],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando _TOTAL_ funcionarios",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });

        $('#tableFormatos').DataTable({
            "order": [[1, 'ASC']],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando _TOTAL_ funcionarios",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

</body>

</html>
