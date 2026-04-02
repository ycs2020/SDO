<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
//
// Obtén la IP del cliente
$ipCliente = $_SERVER['REMOTE_ADDR'];

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idRegistro']) || isset($_SESSION['idRegistro'])) {
    $idRegistro = isset($_POST['idRegistro']) ? $_POST['idRegistro'] : $_SESSION['idRegistro'];

    // Lógica a ejecutar si existe idRegistro
//    echo "El ID de registro es: " . htmlspecialchars($idRegistro);

    if ($idRegistro > 0) {

        $datosRegistro = getDatosRegistroAlarmas($idRegistro);

        if (is_array($datosRegistro)) {

            $idRegistroObtenido = $datosRegistro['idRegistro'];
            $fechaRegistro = $datosRegistro['fechaRegistro'];
            $nomRegistro = $datosRegistro['nomRegistro'];
            $controlRegistro = $datosRegistro['controlRegistro'];

            if (!empty($fechaRegistro)) {

                $fechaTime = strtotime($fechaRegistro);

                if (validateTimestamp($fechaRegistro)) {
                    $fechaEscrita = fechaEspañol2($fechaTime);
                    $horaEscrita = date('H:i', $fechaTime);

                    $fechaFinal = "$fechaEscrita a las $horaEscrita hrs.";
                } else {
                    $fechaFinal = 'No Aplica';
                }

            } else {
                $fechaFinal = 'No Aplica';
            }

            if (!empty($nomRegistro)) {

                $nomRegistroMayus = mb_convert_case($nomRegistro, MB_CASE_UPPER, 'UTF-8');

            } else {
                header("Location: ../$ruta/bms_reporte_admin.php?errorCargaRegistro=true");
            }

        } else {
            // Lógica si $datosRegistro no es un array
//            echo "Error: No se obtuvieron datos válidos.";
            header("Location: ../$ruta/bms_reporte_admin.php?errorCargaRegistro=true");

        }

    } else {

        header("Location: ../$ruta/bms_reporte_admin.php?errorCargaRegistro=true");

    }

} else {

    header("Location: ../$ruta/bms_reporte_admin.php?errorCargaRegistro=true");

}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $nomRegistroMayus; ?></title>

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
    <link rel="stylesheet" href="../sdo-funciones/jquery-3.2.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Bootstrap Switch CSS (si lo necesitas, AdminLTE puede ya tenerlo integrado) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css">

    <style>
        /* ============================
           MODO CLARO (por defecto)
        ============================ */

        /* Texto dentro del campo seleccionado */
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #000000; /* Texto negro */
        }

        /* Opciones en el listado desplegable */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Texto negro */
        }

        /* Fondo del dropdown */
        .select2-container--bootstrap4 .select2-dropdown {
            background-color: #ffffff; /* Fondo blanco */
            color: #000000;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important; /* Azul */
            color: #ffffff !important; /* Texto blanco */
            border: none;
        }

        /* ============================
           MODO OSCURO (con clase .dark-mode en <body>)
        ============================ */

        /* Fondo del campo de selección */
        .dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #343a40 !important; /* Fondo oscuro */
            color: #ffffff !important; /* Texto blanco */
            border-color: #495057 !important;
        }

        /* Texto dentro del campo seleccionado */
        .dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
            color: #ffffff !important;
        }

        /* Opciones seleccionadas múltiples (chips) */
        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            color: #ffffff !important;
            border: none;
        }

        /* Dropdown fondo y texto */
        .dark-mode .select2-container--bootstrap4 .select2-dropdown {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        /* Opciones en la lista desplegable */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff !important;
        }

        /* Opción seleccionada en dropdown */
        .dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        /* Opción resaltada con el mouse */
        .dark-mode .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        .bg-rojo-personalizado {
            background-color: #b81414;
            color: white;
        }

        .bg-amarillo-personalizado {
            background-color: #fff475;
            color: black;
        }
    </style>

    <style>
        #tablaObservaAlarmas th, #tablaObservaAlarmas td {
            text-align: center; /* Centra el contenido horizontalmente */
            vertical-align: middle; /* Centra el contenido verticalmente */
        }

        .custom-control-input ~ .custom-control-label::before {
            width: 1.5rem;
            height: 1.5rem;
        }

        .custom-control-input:checked ~ .custom-control-label::after {
            width: 1.5rem;
            height: 1.5rem;
            line-height: 1.5rem;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
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
                            <div class="card-header">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black "><?php echo $nomRegistroMayus; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="bms_reporte_admin.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-8 col-12">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="table-responsive">
                                    <table style="font-size: 15px;" class="table table-bordered table-sm">
                                        <tbody>
                                        <tr>
                                            <td style='width: 20%'>
                                                <b>Nombre Reporte:</b>
                                            </td>
                                            <td style='width: 80%'><?php echo $nomRegistroMayus ?></td>
                                        </tr>
                                        <tr>
                                            <td style='width: 20%'>
                                                <b>Fecha Reporte:</b>
                                            </td>
                                            <td style='width: 80%'><?php echo mb_convert_case($fechaFinal, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <td style='width: 20%'>
                                                <b>Creador Reporte:</b>
                                            </td>
                                            <td style='width: 80%'><?php echo $controlRegistro ?></td>
                                        </tr>
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
                                        if ($idRegistro == 0 || $idRegistro == "" || $idRegistro == " " || $idRegistro == "  ") {
                                            ?>
                                            <button title="Error al Obtener ID de Registro. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block btn-info">
                                                <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;Visualizar Registro en Excel
                                            </button>
                                            <?php
                                        } else {

                                            ?>

                                            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target=".generarArchivoExcel">
                                                <b><i class="far fa-file-excel"></i>&nbsp;&nbsp;Visualizar Registro en Excel</b>
                                            </button>


                                            <div class="modal fade generarArchivoExcel" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title">
                                                                <b>Archivo Excel - Alarmas BMS</b>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-pdf/SDO-Reporte-Alarmas.php" target="_blank" method="POST" id="formulario">

                                                            <div class="modal-body">
                                                                <input type="hidden" name="idRegistro" id="idRegistro" value="<?php echo $idRegistro; ?>">
                                                                <div class="form-group col-md-12">
                                                                    <input type="hidden" name="periodo1" value="rangoFechas1">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo de Alarmas:</label>
                                                                    <small id="emailHelp" class="form-text text-muted">Dejar en 'Seleccione' si quiere visualizar todas las Alarmas.</small>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                        </div>
                                                                        <select class="form-control" id="tipoAlarma" name="tipoAlarma" aria-describedby="emailHelp">

                                                                            <option value="todos" selected>-- Seleccione --</option>

                                                                            <?php

                                                                            $sqlEstado = "SELECT stateHistorial FROM bms_alarmas_historial WHERE idRegistro = $idRegistro GROUP BY stateHistorial ORDER BY stateHistorial ASC;
";
                                                                            $resEstado = mysqli_query($conexion, $sqlEstado);

                                                                            while ($rowEstado = $resEstado->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['stateHistorial'] = $rowEstado['stateHistorial'];
                                                                                $stateHistorial = $datos['stateHistorial'];
                                                                                ?>
                                                                                <option value="<?php echo $stateHistorial ?>"><?php echo $stateHistorial ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" id="enviar" class="btn btn-primary">
                                                                    <b>Generar Reporte</b>
                                                                </button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                    <b>Cerrar</b>
                                                                </button>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php

                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabAlarmas" data-toggle="tab">Alarmas Registradas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabObservaciones" data-toggle="tab">Observaciones Reporte</a>
                                    </li>
                                </ul>
                            </div>


                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabAlarmas">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Alarmas Registradas</h5>

                                            <br>

                                            <div class="row justify-content-center mb-3">
                                                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                                    <button id="enviarSeleccionados" class="btn btn-primary btn-block">
                                                        <i class="fas fa-save"></i>&nbsp;&nbsp;Enviar Seleccionados
                                                    </button>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-6 col-12" id="countNumAvisos">

                                                    <?php
                                                    $countRegistroAvisos = countAvisosPorReporteAlarmas($idRegistro);

                                                    if ($countRegistroAvisos > 0) {

                                                        $claseAviso = 'bg-danger';
                                                        $cantidadFinalAvisos = $countRegistroAvisos;

                                                    } else {

                                                        $claseAviso = 'bg-success';
                                                        $cantidadFinalAvisos = 0;
                                                    }
                                                    ?>

                                                    <button class="btn <?php echo $claseAviso; ?> btn-block">
                                                        Avisos Relevantes de este Reporte: &nbsp;&nbsp;
                                                        <b><?php echo $cantidadFinalAvisos; ?></b>
                                                    </button>

                                                </div>
                                            </div>

                                            <br>

                                            <table style="font-size: 10pt; text-transform: uppercase" id="tableAlarmas" class="table table-bordered table-sm">
                                                <thead style="background-color: indianred; color: white" class="text-center align-content-center">

                                                <tr>
                                                    <th class="align-content-center">N°</th>
                                                    <th class="align-content-center">Fecha</th>
                                                    <th class="align-content-center">Departamento</th>
                                                    <th class="align-content-center">Clase</th>
                                                    <th class="align-content-center">Estado</th>
                                                    <th class="align-content-center">Observación</th>
                                                    <th class="align-content-center">Data de Alarma</th>
                                                    <th class="align-content-center">Notas</th>
                                                    <th class="align-content-center">Seleccionar</th>
                                                </tr>

                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot style="background-color: indianred; color: white" class="text-center">
                                                <tr>
                                                    <th class="align-content-center">N°</th>
                                                    <th class="align-content-center">Fecha</th>
                                                    <th class="align-content-center">Departamento</th>
                                                    <th class="align-content-center">Clase</th>
                                                    <th class="align-content-center">Estado</th>
                                                    <th class="align-content-center">Observación</th>
                                                    <th class="align-content-center">Data de Alarma</th>
                                                    <th class="align-content-center">Notas</th>
                                                    <th class="align-content-center">Seleccionar</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div class="modal fade" id="modalDataAlarma" tabindex="-1" role="dialog" aria-labelledby="modalDataAlarmaLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="modalDataAlarmaLabel">Detalle de Data de Alarma</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label>DATOS DE ALARMA</label>
                                                            <p id="alarmDataText" class="form-control-plaintext"></p>
                                                            <!-- Aquí puedes agregar más campos -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal para mostrar observaciones -->
                                            <div class="modal fade" id="modalObservaAlarmas" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="modalLabel">Observaciones</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body" style="background-color: #dfdfdf">
                                                            <form id="formAgregarObservacion">
                                                                <div class="col-md-12 ml-auto">

                                                                    <div class="form-group">
                                                                        <label for="exampleInputRounded0">Fecha de Observación:</label>
                                                                        <div class="input-group mb-2">
                                                                            <input type="datetime-local" class="form-control" id="fechaObservacionAlarmas" name="fechaObservacionAlarmas" value="">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 ml-auto">

                                                                    <div class="form-group">
                                                                        <label for="exampleInputRounded0">Observación:
                                                                            <code>*</code></label>
                                                                        <div class="input-group mb-2">
                                                                            <textarea class="form-control" id="nuevaObservacion" placeholder="Escribe una observación..." rows="3" style="resize: none;" required></textarea>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary btn-block">Agregar Observacion</button>
                                                            </form>
                                                            <br>
                                                            <hr>

                                                            <table class="table table-bordered table-sm" id="tablaObservaAlarmas">
                                                                <thead style="background-color: #0c84ff; color: white" class="text-center">
                                                                <tr>
                                                                    <th>N°</th>
                                                                    <th>Observación</th>
                                                                    <th>Fecha</th>
                                                                    <th>Eliminar</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody style="background-color: #ffffff"></tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer" style="background-color: #dfdfdf">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal para Enviar Seleccionados -->
                                            <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                <b>Enviar Aviso</b>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="tituloAviso">Titulo de Aviso...<code>*</code></label>
                                                                        <input type="text" name="tituloAviso" id="tituloAviso" class="form-control" required placeholder="Titulo del Aviso...">

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="departamentoAviso">Departamento que corresponde...<code>*</code></label>
                                                                        <select class="form-control listDepartamentoAviso" name="departamentoAvisoAlarmas" id="departamentoAvisoAlarmas">
                                                                            <option></option>
                                                                            <?php
                                                                            $departamentos = [];

                                                                            $queryDepartamentos1 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN('Informática', 'Ingeniería Operacional', 'Control Centralizado')";
                                                                            $resDepartamentos1 = mysqli_query($conexion, $queryDepartamentos1);

                                                                            // Guardar en array con nombre modificado si aplica
                                                                            while ($row = $resDepartamentos1->fetch_assoc()) {
                                                                                $idDepa = $row['idDepartamento'];
                                                                                $nomDepa1 = $row['nomDepartamento'];

                                                                                if ($nomDepa1 == 'Instrumentación') {
                                                                                    $nomDepa1 = 'Control Centralizado';
                                                                                }

                                                                                $departamentos[] = ['id' => $idDepa, 'nombre' => $nomDepa1];
                                                                            }

                                                                            // Ordenar alfabéticamente por el nombre mostrado
                                                                            usort($departamentos, function ($a, $b) {
                                                                                return strcmp($a['nombre'], $b['nombre']);
                                                                            });

                                                                            foreach ($departamentos as $dep) {
                                                                                ?>
                                                                                <option value="<?php echo htmlspecialchars($dep['nombre']) ?>">
                                                                                    <?php echo htmlspecialchars($dep['nombre']) ?>
                                                                                </option>
                                                                                <?php
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="modalDescripcion">Descripci&oacute;n de Aviso...<code>*</code></label>
                                                                        <textarea name="modalDescripcion" id="modalDescripcion" class="form-control" rows="8" style="resize: none" required placeholder="Descripcion del Aviso"></textarea>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">

                                                                        <label for="departamentoAviso">Destinatarios...<code>*</code></label>
                                                                        <select class="form-control listDestinatarioAviso" name="destinatarioAviso[]" id="destinatarioAviso" multiple>
                                                                            <?php
                                                                            $usuariosAvisos = [];

                                                                            //                                                                            $queryUsuariosAvisos = "SELECT jt.nomJefe AS nomEmpleado
                                                                            //                                                                            FROM jefes_tecnicos jt
                                                                            //                                                                            WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
                                                                            //                                                                              AND jt.userActivado = 1
                                                                            //
                                                                            //                                                                            UNION
                                                                            //
                                                                            //                                                                            SELECT p.nomPersonal AS nomEmpleado
                                                                            //                                                                            FROM personal p
                                                                            //                                                                            WHERE p.activoPersonal = 1
                                                                            //
                                                                            //                                                                            UNION
                                                                            //
                                                                            //                                                                            SELECT d.nomDirectivo AS nomEmpleado
                                                                            //                                                                            FROM directivos d
                                                                            //
                                                                            //                                                                            ORDER BY nomEmpleado ASC;
                                                                            //                                                                            ";

                                                                            $queryUsuariosAvisos = "SELECT jt.nomJefe AS nomEmpleado
                                                                            FROM jefes_tecnicos jt
                                                                            WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'
                                                                              AND jt.nomJefe NOT LIKE '%Prueba%'
                                                                              AND jt.userActivado = 1
                                                                            
                                                                            UNION
                                                                            
                                                                            SELECT d.nomDirectivo AS nomEmpleado
                                                                            FROM directivos d
                                                                            
                                                                            ORDER BY nomEmpleado ASC;
                                                                            ";
                                                                            $resUsuariosAvisos = mysqli_query($conexion, $queryUsuariosAvisos);

                                                                            // Guardar en array con nombre modificado si aplica
                                                                            while ($rowUsuariosAvisos = $resUsuariosAvisos->fetch_assoc()) {
                                                                                $nomEmpleadoUA = $rowUsuariosAvisos['nomEmpleado'];

                                                                                if ($nomEmpleadoUA == 'Alejandro Cantillano') {
                                                                                    $nomEmpleadoUA = 'Jorge Cantillano';
                                                                                }

                                                                                $usuariosAvisos[] = ['nombre' => $nomEmpleadoUA];
                                                                            }

                                                                            // Ordenar alfabéticamente por el nombre mostrado
                                                                            usort($usuariosAvisos, function ($a, $b) {
                                                                                return strcmp($a['nombre'], $b['nombre']);
                                                                            });

                                                                            foreach ($usuariosAvisos as $ua) {
                                                                                ?>
                                                                                <option value="<?php echo htmlspecialchars($ua['nombre']) ?>"><?php echo htmlspecialchars($ua['nombre']) ?></option>

                                                                                <?php
                                                                            } ?>
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="creadorAviso" id="creadorAviso" value="<?php echo $nomUsuario; ?>">
                                                            <input type="hidden" name="idReporteAlarmas" id="idReporteAlarmas" value="<?php echo $idRegistro; ?>">
                                                            <button type="button" class="btn btn-success" id="confirmarEnvio">
                                                                <span id="spinnerEnvio" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                <span id="textoEnvio"><i class="fas fa-save"></i>&nbsp;&nbsp;Confirmar</span>
                                                            </button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane" id="tabObservaciones">
                                        <div class="col-12">
                                            <h5 class="text-center align-content-center">Observaciones de Reporte de Alarmas</h5>

                                            <br>

                                            <div class="row text-center" style="display: flex; justify-content:center;">
                                                <div class="col-xl-3 col-lg-4 col-12">
                                                    <button type="button" data-toggle="modal" data-target="#modalAddObs" id="addObs" class="btn bg-gradient-success">
                                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observación
                                                    </button>

                                                    <form id="formAgregarAlarmaRegistro">
                                                        <div class="modal fade" style="display:none;" id="modalAddObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-success">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Añadir Observación a Reporte</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div class="form-group">
                                                                                    <label for="exampleInputRounded0">Fecha de Observación:</label>
                                                                                    <div class="input-group mb-2">
                                                                                        <input type="datetime-local" class="form-control" id="fechaObservacionReporte" name="fechaObservacionReporte" value="">

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 ml-auto">

                                                                                <div class="form-group">
                                                                                    <label for="exampleInputRounded0">Observación:
                                                                                        <code>*</code></label>
                                                                                    <div class="input-group mb-2">
                                                                                        <textarea id="observacionReporte" style="resize:none;" name="observacionReporte" class="form-control" rows="8" placeholder="Observación...."></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="controlReporte" id="controlReporte" value="<?php echo $nomUsuario; ?>">
                                                                        <input type="hidden" name="idRegistroAgregar" id="idRegistroAgregar" value="<?php echo $idRegistro; ?>">
                                                                        <input type="hidden" name="idHistorialAgregar" id="idHistorialAgregar" value="0">

                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                            <i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                            <br>

                                            <table style="font-size: 10pt; text-transform: uppercase" id="tableObservacionesRegistro" class="table table-bordered table-sm">
                                                <thead style="background-color: #0c84ff; color: white" class="text-center align-content-center">

                                                <tr>
                                                    <th class="align-content-center">N°</th>
                                                    <th class="align-content-center">Fecha</th>
                                                    <th class="align-content-center">Creador</th>
                                                    <th class="align-content-center">Observación</th>
                                                    <th class="align-content-center">Eliminar</th>
                                                </tr>

                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot style="background-color: #0c84ff; color: white" class="text-center">
                                                <tr>
                                                    <th class="align-content-center">N°</th>
                                                    <th class="align-content-center">Fecha</th>
                                                    <th class="align-content-center">Creador</th>
                                                    <th class="align-content-center">Observación</th>
                                                    <th class="align-content-center">Eliminar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <!-- Modal -->


                                            <script>
                                                $(document).ready(function () {
                                                    var tableObservacionesRegistro = $('#tableObservacionesRegistro').DataTable({
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
                                                            "url": "/sdo-includes/bms_alarmas_cargar_obs_registro.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                                d.idRegistro = <?php echo $idRegistro; ?>
                                                            },
                                                            "dataSrc": ""
                                                        },
                                                        "columns": [

                                                            {"data": "numFila"},
                                                            {"data": "fechaObservacion2"},
                                                            {"data": "creadorObservacion"},
                                                            {
                                                                data: 'detalleObservacion',
                                                                render: function (data) {
                                                                    return data; // Devuelve el contenido respetando el HTML y los saltos de línea
                                                                }
                                                            },
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    if (row.idObservacion > 0) {
                                                                        return '<button class="btn btn-danger btn-block eliminarobsregistro" data-id="' + row.idObservacion + '"><i class="fas fa-trash"></i></button>';

                                                                    } else {

                                                                        return 'Deshabilitado';
                                                                    }
                                                                }
                                                            }

                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Centrado vertical
                                                            $('td', row).css('text-align', 'center'); // Centrado horizontal para todas las celdas
                                                            $('td:eq(3)', row).css({
                                                                'text-align': 'left', // Alineación a la izquierda
                                                                'text-justify': 'inter-word' // Justificación
                                                            }); // Aplica estilos solo a la columna "detalleObservacion"
                                                        },

                                                    });

                                                    $('#formAgregarAlarmaRegistro').on('submit', function (e) {
                                                        e.preventDefault();

                                                        let fechaObservacion = $('#fechaObservacionReporte').val();
                                                        fechaObservacion = fechaObservacion === '' ? null : fechaObservacion;

                                                        console.log('FECHA: "' + fechaObservacion + '"');
                                                        let nuevaObservacion = $('#observacionReporte').val().trim().replace(/\r?\n/g, '<br>'); // Codifica saltos de línea
                                                        let controlReporte = $('#controlReporte').val().trim();
                                                        let registro = $('#idRegistroAgregar').val().trim();
                                                        let idHistorial = $('#idHistorialAgregar').val().trim();

                                                        if (nuevaObservacion === '') {
                                                            alert('La observación no puede estar vacía.');
                                                            return;
                                                        }

                                                        // Enviar la nueva observación al servidor
                                                        $.ajax({
                                                            url: '/sdo-funciones/bms_alarmas_agregar_observacion.php',
                                                            type: 'POST',
                                                            data: {
                                                                idHistorial: idHistorial,
                                                                nomControl: controlReporte,
                                                                idRegistro: registro,
                                                                observacion: nuevaObservacion,
                                                                fechaObservacion: fechaObservacion
                                                            },
                                                            success: function (response) {
                                                                let result = JSON.parse(response);

                                                                if (result.status === 'success') {
                                                                    // Mostrar mensaje de éxito
                                                                    Swal.fire({
                                                                        icon: 'success',
                                                                        title: 'Observación agregada correctamente',
                                                                        showConfirmButton: false,
                                                                        timer: 1500
                                                                    });

                                                                    // Limpiar el input y actualizar la tabla
                                                                    $('#observacionReporte').val('');

                                                                    $('#tableObservacionesRegistro').DataTable().ajax.reload();

                                                                    $('#modalAddObs').modal('hide');

                                                                } else {
                                                                    Swal.fire({
                                                                        icon: 'error',
                                                                        title: 'Error',
                                                                        text: result.message
                                                                    });
                                                                }
                                                            },
                                                            error: function () {
                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: 'Error',
                                                                    text: 'No se pudo agregar la observación.'
                                                                });
                                                            }
                                                        });
                                                    });

                                                    $('#tableObservacionesRegistro').on('click', '.eliminarobsregistro', function () {
                                                        const idObservacion = $(this).data('id'); // Obtener el ID del historial

                                                        Swal.fire({
                                                            title: '¿Estás seguro?',
                                                            text: "Esta acción no se puede deshacer",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#d33',
                                                            cancelButtonColor: '#3085d6',
                                                            confirmButtonText: 'Sí, eliminar',
                                                            cancelButtonText: 'Cancelar'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                $.ajax({
                                                                    url: '/sdo-funciones/bms_alarmas_eliminar_observaciones.php',
                                                                    type: 'POST',
                                                                    data: {idObservacion: idObservacion},
                                                                    success: function (response) {
                                                                        const jsonResponse = JSON.parse(response);
                                                                        if (jsonResponse.status === 'success') {
                                                                            Swal.fire(
                                                                                '¡Eliminado!',
                                                                                jsonResponse.message,
                                                                                'success'
                                                                            );

                                                                            // Recargar las observaciones después de eliminar
                                                                            $('#tableObservacionesRegistro').DataTable().ajax.reload();
                                                                        } else {
                                                                            Swal.fire(
                                                                                'Error',
                                                                                jsonResponse.message,
                                                                                'error'
                                                                            );
                                                                        }
                                                                    },
                                                                    error: function () {
                                                                        Swal.fire(
                                                                            'Error',
                                                                            'Ocurrió un problema al intentar eliminar la observación.',
                                                                            'error'
                                                                        );
                                                                    }
                                                                });
                                                            }
                                                        });
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

<!-- Bootstrap Switch JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>

<script>
    $(document).ready(function () {
        $('.listDepartamentoAviso').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0
        });

        $('.listDestinatarioAviso').select2({
            placeholder: 'Seleccione',
            theme: 'bootstrap4',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0
        });
    });
</script>
<script>

    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //CARGA DE ALARMAS SEGUN REGISTRO Y SUS RESPECTIVAS OBSERVACIONES
    $(function () {
        var idsSeleccionados = [];

        $('#tableAlarmas').DataTable({


            "order": [[1, 'ASC']],
            "paging": true,
            "lengthChange": false,
            "processing": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "processing": "Cargando Información...",
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
            initComplete: function () {
                var api = this.api();

                // Definir columnas y sus títulos
                var filtros = [
                    {
                        col: 2,
                        titulo: 'DEPARTAMENTO'
                    },
                    {
                        col: 3,
                        titulo: 'CLASE'
                    },
                    {
                        col: 4,
                        titulo: 'ESTADO'
                    },
                    {
                        col: 5,
                        titulo: 'OBSERVACIÓN'
                    }
                ];


                filtros.forEach(function (filtro) {
                    api.columns([filtro.col]).every(function () {
                        var column = this;
                        var select = $('<select><option value=""><b>' + filtro.titulo + '</b></option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function (d) {
                            select.append('<option value="' + d + '">' + d.toUpperCase() + '</option>');
                        });
                    });
                });
            },
            "ajax": {
                "url": "/sdo-includes/bms_alarmas_cargar_historial.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idRegistro = <?php echo $idRegistro; ?>
                },
                "dataSrc": ""
            },
            "columns": [

                {"data": "numFila"},
                {"data": "fechaHistorial2"},
                {"data": "deptoHistorial"},
                {"data": "classHistorial"},
                {"data": "stateHistorial"},
                {"data": "mensajeHistorial"},
                {
                    "data": null,
                    "render": function (data, type, row) {


                        return `
                                <button class="btn btn-info btn-sm btn-data-alarma"
                                    data-id="${row.idHistorial}">
                                    <i class="fas fa-eye"></i> Data
                                </button>
                            `;
                    },
                    "orderable": false
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return `
                                <button class="btn btn-primary btn-sm btn-observaciones"
                                        data-id="${row.idHistorial}"
                                        data-control="<?php echo $nomUsuario ?>"
                                        data-registro="${row.idRegistro}">
                                    <i class="fas fa-eye"></i>
                                    <span class="badge bg-danger">${row.cantidadObsHistorial}</span>
                                </button>`;

                    },
                    "orderable": false
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return `
        <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
            <input type="checkbox" class="seleccionar-checkbox" value="${row.mensajeHistorial2}" style="transform: scale(1.5);">
        </div>
    `;
                    },
                    "orderable": false
                },

                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

            },
        });

        // Delegación de evento porque el contenido es dinámico
        $('#tableAlarmas').on('click', '.btn-data-alarma', function () {
            var idHistorial = $(this).data('id');

            // Petición AJAX para buscar datos en otro PHP
            $.ajax({
                url: '/sdo-includes/bms_alarmas_obtener_dataAlarm.php', // tu nuevo PHP
                type: 'POST',
                data: { idHistorial: idHistorial },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#alarmDataText').html(response.alarmDataHistorial_html); // ya viene formateado
                        $('#modalDataAlarma').modal('show');
                    } else {
                        alert(response.error || 'No se encontró información para este historial.');
                    }
                },
                error: function () {
                    alert('Error en la conexión con el servidor.');
                }
            });
        });

        // Función para manejar el envío del formulario de observaciones
        $('#formAgregarObservacion').on('submit', function (e) {
            e.preventDefault();

            let fechaObservacion = $('#fechaObservacionAlarmas').val();
            fechaObservacion = fechaObservacion === '' ? null : fechaObservacion;

            let nuevaObservacion = $('#nuevaObservacion').val().trim();
            let idHistorial = $('#modalObservaAlarmas').data('id-historial'); // ID asociado al modal
            let control = $('#modalObservaAlarmas').data('id-control'); // ID asociado al modal
            let registro = $('#modalObservaAlarmas').data('id-registro'); // ID asociado al modal

            if (nuevaObservacion === '') {
                alert('La observación no puede estar vacía.');
                return;
            }

            // Enviar la nueva observación al servidor
            $.ajax({
                url: '/sdo-funciones/bms_alarmas_agregar_observacion.php',
                type: 'POST',
                data: {
                    idHistorial: idHistorial,
                    nomControl: control,
                    idRegistro: registro,
                    observacion: nuevaObservacion,
                    fechaObservacion: fechaObservacion

                },
                success: function (response) {
                    let result = JSON.parse(response);

                    if (result.status === 'success') {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Observación agregada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Limpiar el input y actualizar la tabla
                        $('#nuevaObservacion').val('');
                        $('#fechaObservacionAlarmas').val('');
                        cargarObservaciones(idHistorial);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo agregar la observación.'
                    });
                }
            });
        });

// Función para cargar las observaciones en la tabla
        function cargarObservaciones(idHistorial) {


            // Inicializar DataTable para tablaObservaciones
            if ($.fn.dataTable.isDataTable('#tablaObservaAlarmas')) {
                $('#tablaObservaAlarmas').DataTable().clear().destroy();
            }

            $.ajax({
                url: '/sdo-includes/bms_alarmas_cargar_observacion.php',
                type: 'POST',
                data: {idHistorial: idHistorial},
                success: function (response) {
                    let observaciones = JSON.parse(response);
                    let tablaBody = $('#tablaObservaAlarmas tbody');
                    tablaBody.empty(); // Limpiar tabla anterior

                    console.log(observaciones.length);

                    if (observaciones.length > -1) {
                        observaciones.forEach(function (obs, index) {
                            let acciones = '';

                            if (obs.idHistorial > 0) {
                                acciones = `
                                <button class="btn btn-danger btn-sm btn-block eliminarObservacion" data-id="${obs.idObservacion}" data-historial="${obs.idHistorial}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                            } else {
                                acciones = 'Deshabilitado';
                            }

                            tablaBody.append(`
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td>${obs.detalleObservacion}</td>
                                    <td class="text-center">
                                        ${obs.fechaObservacion2}
                                    </td>
                                    <td class="text-center">
                                        ${acciones}
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tablaBody.append(`
                    <tr>
                        <td colspan="3" class="text-center">No hay observaciones.</td>
                    </tr>
                `);
                    }


                    // Inicializar DataTable para tablaObservaciones
                    $('#tablaObservaAlarmas').DataTable({
                        "paging": true,
                        "lengthChange": false,
                        "pageLength": 5, // Establecer el número de filas visibles por página
                        "searching": true,
                        "ordering": false,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "language": {
                            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                            "zeroRecords": "No se han encontrado Registros.",
                            "info": "Mostrando _TOTAL_ registros",
                            "infoEmpty": "No existen Registros.",
                            "search": "Buscar:",
                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                            "paginate": {
                                "previous": "Anterior",
                                "next": "Siguiente"
                            }
                        }
                    });

                },
                error: function () {
                    alert('Error al cargar las observaciones.');
                }


            });

            $('#tableAlarmas').DataTable().ajax.reload();

        }

// Evento para abrir el modal con el ID del historial asociado
        $('#tableAlarmas tbody').on('click', '.btn-observaciones', function () {
            let idHistorial = $(this).data('id');
            let controlHistorial = $(this).data('control');
            let idRegistro = $(this).data('registro');

            // Guardar el ID en el modal
            $('#modalObservaAlarmas').data('id-historial', idHistorial);
            $('#modalObservaAlarmas').data('id-control', controlHistorial);
            $('#modalObservaAlarmas').data('id-registro', idRegistro);

            // Cargar las observaciones para ese historial
            cargarObservaciones(idHistorial);

            // Mostrar el modal
            $('#modalObservaAlarmas').modal('show');


        });

        $('#tablaObservaAlarmas').on('click', '.eliminarObservacion', function () {
            const idObservacion = $(this).data('id'); // Obtener el ID del historial
            const idHistorial = $(this).data('historial');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/sdo-funciones/bms_alarmas_eliminar_observaciones.php',
                        type: 'POST',
                        data: {idObservacion: idObservacion},
                        success: function (response) {
                            const jsonResponse = JSON.parse(response);
                            if (jsonResponse.status === 'success') {
                                Swal.fire(
                                    '¡Eliminado!',
                                    jsonResponse.message,
                                    'success'
                                );

                                // Recargar las observaciones después de eliminar
                                cargarObservaciones(idHistorial);
                            } else {
                                Swal.fire(
                                    'Error',
                                    jsonResponse.message,
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error',
                                'Ocurrió un problema al intentar eliminar la observación.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#tableAlarmas tbody').on('change', '.seleccionar-checkbox', function () {
            const mensajeHistorial2 = $(this).val();
            if (this.checked) {
                if (!idsSeleccionados.includes(mensajeHistorial2)) {
                    idsSeleccionados.push(mensajeHistorial2);
                }
            } else {
                idsSeleccionados = idsSeleccionados.filter(id => id !== mensajeHistorial2);
            }
            console.log('IDs seleccionados actualmente:', idsSeleccionados);
        });

        $('#enviarSeleccionados').on('click', function () {
            if (idsSeleccionados.length === 0) {
                alert('No has seleccionado ningún historial.');
                return;
            }

            // Ordenar por fecha/hora ASC
            const idsOrdenados = idsSeleccionados.slice().sort((a, b) => {
                const fechaA = extraerFechaHora(a);
                const fechaB = extraerFechaHora(b);
                return fechaA - fechaB;
            });

            // Construir el contenido ordenado
            const descripcion = `Se registran las siguientes Alarmas:\n\n${idsOrdenados.join('\n')}`;

            // Insertar en el modal
            $('#modalDescripcion').html(descripcion);

            $('#modalConfirmacion').modal('show');
        });

        function extraerFechaHora(texto) {
            // Quitar el guion y espacios iniciales
            texto = texto.trim().replace(/^-\s*/, '');

            // Buscar el patrón de la fecha
            const match = texto.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/);
            if (match) {
                const [_, dd, mm, yyyy, hh, min] = match;
                return new Date(`${yyyy}-${mm}-${dd}T${hh}:${min}`);
            }
            return new Date(2100, 0, 1);
        }

        $('#confirmarEnvio').on('click', function () {
            // Obtener valores del formulario
            const tituloAviso = $('#tituloAviso').val().trim();
            const departamentoAviso = $('#departamentoAvisoAlarmas').val();
            const descripcionAviso = $('#modalDescripcion').val().trim();
            const destinatarios = $('#destinatarioAviso').val();  // Es un array
            const creadorAviso = $('#creadorAviso').val();  // Es un array
            const idReporteAlarmas = $('#idReporteAlarmas').val();  // Es un array
            const ids = idsSeleccionados; // Asumimos que esta variable global ya está definida

            // Validación básica
            if (!tituloAviso || !departamentoAviso || !descripcionAviso || !destinatarios || destinatarios.length === 0 || !creadorAviso || !idReporteAlarmas) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor completa todos los campos obligatorios.'
                });
                return;
            }

            // Desactivar botón y mostrar spinner
            $('#confirmarEnvio').prop('disabled', true);
            $('#spinnerEnvio').removeClass('d-none');
            $('#textoEnvio').text(' Enviando...');

            // Preparar datos
            const datos = {
                ids: ids,
                tituloAviso: tituloAviso,
                departamentoAviso: departamentoAviso,
                descripcionAviso: descripcionAviso,
                destinatarioAviso: destinatarios,
                creadorAviso: creadorAviso,
                idReporteAlarmas: idReporteAlarmas,
            };

            console.log('Datos enviados:', datos);

            $.ajax({
                url: '/sdo-funciones/avisos_relevantes_alarmas_agregar.php',
                method: 'POST',
                data: datos,
                dataType: 'json',
                success: function (respuesta) {
                    console.log('Respuesta del servidor:', respuesta);

                    if (respuesta.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Enviado',
                            text: respuesta.message || 'Aviso enviado correctamente.'
                        });

                        // Limpiar campos
                        $('#tituloAviso').val('');
                        $('#departamentoAvisoAlarmas').val('').trigger('change');
                        $('#modalDescripcion').val('');
                        $('#destinatarioAviso').val([]).trigger('change');

                        idsSeleccionados = [];
                        $('.seleccionar-checkbox').prop('checked', false);

                        // Cerrar modal
                        $('#modalConfirmacion').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: respuesta.message || 'Ocurrió un error al enviar el aviso.'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al enviar los datos al servidor.'
                    });
                },
                complete: function () {
                    // Restaurar botón
                    $('#confirmarEnvio').prop('disabled', false);
                    $('#spinnerEnvio').addClass('d-none');
                    $('#textoEnvio').html('<i class="fas fa-save"></i>&nbsp;&nbsp;Confirmar');
                    // Cerrar modal

                    // Al éxito del guardado, actualizas el contador
                    actualizarCountNumAvisos(<?php echo $idRegistro; ?>);

                    $('#modalConfirmacion').modal('hide');
                }
            });
        });

        function actualizarCountNumAvisos(idRegistro) {
            $.ajax({
                url: '/sdo-funciones/bms_reporte_cantidad_avisos.php',
                method: 'GET',
                data: {idRegistro: idRegistro},
                success: function (response) {
                    // Convertir a número por seguridad
                    var num = parseInt(response, 10);

                    // Decidir clase
                    var btnClass = (num > 0) ? 'btn-danger' : 'btn-success';

                    // Construir el botón completo
                    var nuevoBoton =
                        '<button class="btn ' + btnClass + ' btn-block">' +
                        'Avisos Relevantes de este Reporte: &nbsp;&nbsp;<b>' + num +
                        '</b></button>';

                    // Reemplazar contenido del div
                    $('#countNumAvisos').html(nuevoBoton);
                },
                error: function () {
                    console.error('Error al actualizar el contador de avisos.');
                }
            });
        }

        //FIN CARGA DE ALARMAS SEGUN REGISTRO Y SUS RESPECTIVAS OBSERVACIONES
        //----------------------------------------------------------------
        //----------------------------------------------------------------
        //----------------------------------------------------------------

        //----------------------------------------------------------------
        //----------------------------------------------------------------
        //----------------------------------------------------------------
        //FUNCIONES DE OBSERVACIONES DE REGISTRO


        //FIN FUNCIONES DE OBSERVACIONES DE REGISTRO
        //----------------------------------------------------------------
        //----------------------------------------------------------------
        //----------------------------------------------------------------

    });
</script>


</body>

</html>
