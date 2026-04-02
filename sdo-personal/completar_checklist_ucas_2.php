<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$now = date('H:i:s');

$fecha = date('d-m-Y H:i');

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];

if (isset($_POST['idTG']) || isset($_SESSION['idTG'])) {
    if (isset($_POST['idTG'])) {
        $idTG = $_POST['idTG'];


        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    } else if (isset($_SESSION['idTG'])) {
        $idTG = $_SESSION['idTG'];
        if ($idTG > 0) {
            $_SESSION['idTG'] = $idTG;
        } else {
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }

    }
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        // $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $ticketAsociado = $row['ticketAsociado'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
        $idEquipamiento = $row['idEquipamiento'];
    }
    if ($idEquipamiento == 0) {
        $idTareaP = 0;
    } else {
        $sqlPlanificador = "SELECT * FROM `tareas_carta_gantt` WHERE idEquipamiento = $idEquipamiento";
        $resPlanificador = mysqli_query($conexion, $sqlPlanificador);
        while ($rowPlanificador = $resPlanificador->fetch_assoc()) {
            $idTareaP = $rowPlanificador['idTareaP'];
        }
    }


}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List UCAS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jssha@3.2.0/dist/sha256.min.js"></script>
    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>

    <script>
        function marcarOtroInput() {
            var primerInput = document.querySelector('.show-mostrar');
            var enlaceBotones = document.getElementById('limpiezaGeneralEquipo');
            var enlaceImagenes = document.getElementById('verificarEstadoEquipo');
            var enlaceparametros = document.getElementById('limpiezaContactos');
            var enlacegraficas = document.getElementById('verificacionEstadoPatchCord');
            var enlacetendencias = document.getElementById('verificacionIntegracion');
            var ordenbloques = document.getElementById('verificacionFuncionamiento');
            var voltaje = document.getElementById('verificacionVoltaje');


            if (primerInput.checked) {
                enlaceBotones.checked = true;
                enlaceImagenes.checked = true;
                enlaceparametros.checked = true;
                enlacegraficas.checked = true;
                enlacetendencias.checked = true;
                ordenbloques.checked = true;
                voltaje.checked = true;
            } else {
                enlaceBotones.checked = false;
                enlaceImagenes.checked = false;
                enlaceparametros.checked = false;
                enlacegraficas.checked = false;
                enlacetendencias.checked = false;
                ordenbloques.checked = false;
                voltaje.checked = false;
            }
        }
    </script>
    <script>
        function marcarInputs1() {
            var marcartodos = document.querySelector('.marcar-todos');
            var limpiezaequiposbiometrico1 = document.querySelector('[name="limpiezaequiposbiometrico1"]');
            var lectortarjeta1 = document.querySelector('[name="lectortarjeta1"]');
            var lectorhuella1 = document.querySelector('[name="lectorhuella1"]');
            var lucesindicadorasbiometrico1 = document.querySelector('[name="lucesindicadorasbiometrico1"]');
            var limpiezaequiposmagnetico1 = document.querySelector('[name="limpiezaequiposmagnetico1"]');
            var lucesindicadorasmagnetico1 = document.querySelector('[name="lucesindicadorasmagnetico1"]');
            var limpiezaequiposbotonera1 = document.querySelector('[name="limpiezaequiposbotonera1"]');
            var botonesoperativos1 = document.querySelector('[name="botonesoperativos1"]');

            if (marcartodos.checked) {
                limpiezaequiposbiometrico1.checked = true;
                lectortarjeta1.checked = true;
                lectorhuella1.checked = true;
                lucesindicadorasbiometrico1.checked = true;
                limpiezaequiposmagnetico1.checked = true;
                lucesindicadorasmagnetico1.checked = true;
                limpiezaequiposbotonera1.checked = true;
                botonesoperativos1.checked = true;
            } else {
                limpiezaequiposbiometrico1.checked = false;
                lectortarjeta1.checked = false;
                lectorhuella1.checked = false;
                lucesindicadorasbiometrico1.checked = false;
                limpiezaequiposmagnetico1.checked = false;
                lucesindicadorasmagnetico1.checked = false;
                limpiezaequiposbotonera1.checked = false;
                botonesoperativos1.checked = false;
            }
        }
    </script>
    <style>
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Orden de Trabajo N° <?php echo $folioTG ?> - Check List UCAS</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="visualizardetalles.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <?php
                            $contarchecklistucas = contarchecklistucas($idTG);

                            $textoCantidad = "Cantidad de CheckList UCAS Realizadas: <b>$contarchecklistucas</b>";
                            $textoCantidad2 = mb_convert_case($textoCantidad, MB_CASE_UPPER, "UTF-8");
                            ?>
                            <div class="card-header bg-warning">
                                <a style="pointer-events: none;" class="btn"><?php echo $textoCantidad2; ?></a>
                            </div>
                        </div>
                    </div>
                    <form id="formCheckUCA" action="../sdo-funciones/guardar-checklist-ucas.php" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaP ?>">
                        <input type="hidden" name="idUsuarioPost" id="idUsuarioPost" value="<?php echo $idUsuario ?>">
                        <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario2 ?>">
                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">

                        <!-- SELECCION UCA -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-hover table table-bordered" style="text-align: center">
                                            <tbody>
                                            <tr>

                                                <td style='width: 25%; vertical-align: middle; text-align: center;'>
                                                    <b>UCA CORRESPONDIENTE:</b>
                                                </td>
                                                <td style='width: 75%; vertical-align: middle; text-align: center;'>
                                                    <?php
                                                    $arrayUCACheckList = obtenerDatosUCACheckList($idEquipamiento);

                                                    if (!empty($arrayUCACheckList) && is_array($arrayUCACheckList)) {
                                                        // procesar los datos
                                                        $nomEquipoCheck = $arrayUCACheckList['nombreEquipo'];
                                                        $idEquipoCheck = $arrayUCACheckList['id'];
                                                    }

                                                    ?>
                                                    <input class="form-control font-weight-bold" type="text" name="nombreEquipo" id="nombreEquipo" value="<?php echo $nomEquipoCheck ?>" readonly="readonly">
                                                    <input type="hidden" name="ucaID" id="ucaID_hidden" value="<?php echo $idEquipoCheck ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='width: 25%; vertical-align: middle; text-align: center;'>
                                                    <b>Seleccione un Estado:</b>
                                                </td>
                                                <td style='width: 75%; vertical-align: middle; text-align: center;'>
                                                    <select id="estado" name="estado" class="form-control" style="width: 100%; " required>
                                                        <option selected disabled value="">Seleccione....</option>
                                                        <option value="Operativo">Operativo</option>
                                                        <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                        <option value="OperativoObs">Operativo con Observaciones</option>
                                                        <option value="Mantenimiento">Mantenimiento</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- FIN SELECCION UCA -->

                        <!-- SELECCION ITEMS -->
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered text-center">
                                                    <thead class="bg-gradient-info">
                                                    <tr>
                                                        <th style="width: 50%; vertical-align: middle;">ITEMS</th>
                                                        <th style="width: 50%; vertical-align: middle;">
                                                            Estado<br>
                                                            <input type="checkbox" id="checkAllEstado" onchange="marcarTodosChecklistUCA()">
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <b>Limpieza general del equipo y su entorno</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="limpiezaGeneralEquipo">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Verificar el estado del equipo</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="verificarEstadoEquipo">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Limpieza de contactos eléctricos y electrónicos</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="limpiezaContactos">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Verificación del estado del patch cord</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="verificacionEstadoPatchCord">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Verificación de voltaje (DC/AC) en contactos, de acuerdo a ficha técnica</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="verificacionVoltaje">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Verificación de integración de software de control de acceso</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="verificacionIntegracion">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Verificación de funcionamiento</b>
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input mx-auto check-uca" type="checkbox" value="1" name="verificacionFuncionamiento">
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="table-responsive">

                                                <table cellpadding="2" class="table table-hover table-bordered" style="text-align: center;">
                                                    <thead class="bg-gradient-info">
                                                    <tr>
                                                        <th colspan="5">Dispositivos Terminales</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            1
                                                        </td>
                                                        <td width="50%">
                                                            Se realiza limpieza a los equipos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2
                                                        </td>
                                                        <td width="50%">
                                                            Lectores de tarjeta se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            3
                                                        </td>
                                                        <td width="50%">
                                                            Lectores de huella se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            4
                                                        </td>
                                                        <td width="50%">
                                                            Luces indicadoras se encuentran operativas
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            5
                                                        </td>
                                                        <td width="50%">
                                                            Botones se encuentran operativos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            6
                                                        </td>
                                                        <td width="50%">
                                                            Estado del Ferro Magnetico
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN SELECCION ITEMS -->

                        <!-- SELECCION PUERTAS -->
                        <!-- FIN SELECCION PUERTAS -->

                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-body">



                                    <hr>

                                    <div class="col-12">

                                    </div>

                                    <fieldset class="border p-3">
                                        <button type="button" style="float: right" class="bg-primary btn" id="agregarFila">
                                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Puerta
                                        </button>
                                        <br>
                                        <br>
                                        <fieldset style="width: 100%" class="border p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="form-check-label" style="float: right;"> Marcar Todos</label>
                                                    <br>
                                                    <input type="checkbox" style="float: right;" class="marcar-todos" onchange="marcarInputs1()">
                                                    <br>
                                                    <table id="tablaOriginal" class="table table-hover table-bordered" style="text-align: center;margin-bottom: 20px;">
                                                        <thead>
                                                        <tr>
                                                            <th rowspan="2" style="vertical-align: middle;">Puerta</th>
                                                            <th>PMA:</th>
                                                            <th colspan="5">
                                                                <input type="text" id="pmapuerta1" name="pmapuerta1" class="form-control">
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>1</th>
                                                            <th>2</th>
                                                            <th>3</th>
                                                            <th>4</th>
                                                            <th>5</th>
                                                            <th>6</th>
                                                        </tr>
                                                        <input type="hidden" id="numeroPuerta1" name="numeroPuerta1" value="Puerta 1">
                                                        </thead>
                                                        <tbody id="miTbody">
                                                        <tr>
                                                            <td>Biometrico</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="limpiezaequiposbiometrico1">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="lectortarjeta1">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="lectorhuella1">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="lucesindicadorasbiometrico1">
                                                            </td>
                                                            <td>No Aplica</td>
                                                            <td>No Aplica</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Magnetico</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="limpiezaequiposmagnetico1">
                                                            </td>
                                                            <td>No Aplica</td>
                                                            <td>No Aplica</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="lucesindicadorasmagnetico1">
                                                            </td>
                                                            <td>No Aplica</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="estadoferroMagnetico1">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Botonera</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="limpiezaequiposbotonera1">
                                                            </td>
                                                            <td>No Aplica</td>
                                                            <td>No Aplica</td>
                                                            <td>No Aplica</td>
                                                            <td>
                                                                <input type="checkbox" value="1" name="botonesoperativos1">
                                                            </td>
                                                            <td>No Aplica</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div id="contenedorTablas">

                                                </div>
                                            </div>

                                        </fieldset>
                                    </fieldset>
                                    <br>
                                    <br>
                                    <table id="tablaOriginalImagenes" class="table-bordered table">
                                        <thead style="text-align: center">
                                        <tr class="bg-secondary">
                                            <th colspan="2">Puerta (Imagen Termografica)</th>
                                        </tr>
                                        <tr>
                                            <th>Subir Imagen</th>
                                            <th>Valores</th>
                                        </tr>
                                        </thead>
                                        <tbody style="text-align: center">
                                        <tr>
                                            <td>
                                                <input type="file" required class="form-control" id="imagenes1" name="imagenes1">
                                            </td>
                                            <td>
                                                <input type="text" required class="form-control" name="valorTermo1" id="valorTermo1">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="hashImagen1" name="hashImagen1">
                                    <div id="contenedorImagenes">

                                    </div>
                                    <br>
                                    <br>
                                    <fieldset class="border p-3">
                                        <div style="display: flex; gap: 20px; justify-content: center;">
                                            <table style="width: 50%; text-align: center;" class="table table-bordered">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="2">Fuentes de Poder (UCA)</th>
                                                </tr>
                                                <tr>
                                                    <th>Voltaje de Entrada</th>
                                                    <th>Voltaje de Salida</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" id="voltajeEntrada" name="voltajeEntrada">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="voltajeSalida" name="voltajeSalida">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table style="width: 50%; text-align: center;" class="table table-bordered">
                                                <thead>
                                                <tr class="bg-secondary">
                                                    <th colspan="2">Fuentes de Poder (Tablero de Puertas)</th>
                                                </tr>
                                                <tr>
                                                    <th>Voltaje de Entrada</th>
                                                    <th>Voltaje de Salida</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" id="voltajeEntradaTablero" name="voltajeEntradaTablero">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" id="voltajeSalidaTablero" name="voltajeSalidaTablero">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <table style="text-align: center;width: 100%" class="table table-bordered">
                                            <thead>
                                            <tr class="bg-secondary">
                                                <th colspan="6">Bateria (UCA)</th>
                                            </tr>
                                            <tr>
                                                <th>Estado de la Bateria</th>
                                                <th>SOH</th>
                                                <th>SOC</th>
                                                <th>A</th>
                                                <th>V</th>
                                                <th>R</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaUCA">
                                                        <option selected disabled value="">Seleccione</option>
                                                        <option value="goodcondition">Good Condition (Color Verde)</option>
                                                        <option value="replace">Replace (Color Rojo)</option>
                                                        <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                        <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOH" name="SOH">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOC" name="SOC">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="A" name="A">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="V" name="V">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="R" name="R">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <table style="text-align: center" width="50%" class="table-bordered table">
                                            <thead>
                                            <tr class="bg-secondary">
                                                <th colspan="7">Bateria (Tablero de Puertas)</th>
                                            </tr>
                                            <tr>
                                                <th>Baterias</th>
                                                <th>Estado de la Bateria</th>
                                                <th>SOH</th>
                                                <th>SOC</th>
                                                <th>A</th>
                                                <th>V</th>
                                                <th>R</th>
                                            </tr>
                                            </thead>


                                            <tbody>
                                            <tr>
                                                <td>N°1</td>
                                                <td>
                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero1">
                                                        <option selected disabled value="">Seleccione</option>
                                                        <option value="goodcondition">Good Condition (Color Verde)</option>
                                                        <option value="replace">Replace (Color Rojo)</option>
                                                        <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                        <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOHTablero1" name="SOHTablero1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOCTablero1" name="SOCTablero1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="ATablero1" name="ATablero1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="VTablero1" name="VTablero1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="RTablero1" name="RTablero1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>N°2</td>
                                                <td>
                                                    <select class="form-control" STYLE="text-align: center" name="estadoBateriaTablero2">
                                                        <option selected disabled value="">Seleccione</option>
                                                        <option value="goodcondition">Good Condition (Color Verde)</option>
                                                        <option value="replace">Replace (Color Rojo)</option>
                                                        <option value="chargebattery">Charge the battery (Color Amarillo)</option>
                                                        <option value="batterybadcondition">Battery Bad Condition (Sin Color)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOHTablero2" name="SOHTablero2">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="SOCTablero2" name="SOCTablero2">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="ATablero2" name="ATablero2">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="VTablero2" name="VTablero2">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="RTablero2" name="RTablero2">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                    <br>
                                    <br>
                                    <?php
                                    // $voltaje = "Valor Voltaje (AC): \n";
                                    // $voltaje2 = "Valor Voltaje (DC): \n";
                                    ?>
                                    <fieldset class="border p-3">
                                        <legend class="w-100">Observaciones</legend>
                                        <textarea rows="10" name="obs" style="resize: none;width: 100%"><?php echo $voltaje;
                                            echo $voltaje2 ?></textarea>
                                    </fieldset>
                                    <br>
                                    <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                                    <button type="submit" class="btn btn-primary btn-block">Guardar</button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
</div>
</body>
<script>
    // Obtener el input de archivo y el campo oculto
    const inputFile1 = document.getElementById('imagenes1');
    const hashInput1 = document.getElementById('hashImagen1');

    // Escuchar el evento de cambio (cuando se selecciona un archivo)
    inputFile1.addEventListener('change', function () {
        // Limpiar el campo oculto antes de asignar un nuevo hash
        hashInput1.value = '';

        if (this.files.length === 0) {
            // Si no se selecciona ningún archivo, salir
            return;
        }

        const file = this.files[0]; // Tomamos el primer archivo

        // Validar si el archivo es una imagen (si es necesario)
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecciona una imagen válida.');
            return;
        }

        // Crear un lector para el archivo seleccionado
        const reader = new FileReader();

        reader.onload = function () {
            // Crear el objeto SHA-256
            const shaObj = new jsSHA("SHA-256", "ARRAYBUFFER");
            shaObj.update(reader.result);

            // Obtener el hash en formato hexadecimal
            const hashHex = shaObj.getHash("HEX");

            // Asignar el hash al campo oculto
            hashInput1.value = hashHex;

            // Si quieres verificarlo en la consola (aunque no se muestre al usuario)
            console.log(`Archivo: ${file.name}`);
            console.log(`Hash SHA-256: ${hashHex}`);
        };

        // Leer el archivo como arrayBuffer
        reader.readAsArrayBuffer(file);
    });

</script>
<script>
    // Obtener el botón y el contenedor de tablas
    const btnAgregarFila = document.getElementById('agregarFila');
    const contenedorTablas = document.getElementById('contenedorTablas');
    const contenedorImagenes = document.getElementById('contenedorImagenes')
    let correlativo = 1; // Iniciar el correlativo en 1
    let contadorTablas = 1;
    const cantidadFilasInput = document.getElementById('cantidadFilas');

    function marcarInputs(correlativo) {
        const marcartodos = document.getElementById(`marcar-todos-${correlativo}`);
        const checkboxes = document.querySelectorAll(`input[name*="${correlativo}"]`);

        checkboxes.forEach(checkbox => {
            checkbox.checked = marcartodos.checked;
        });
    }

    // Agregar evento al botón
    btnAgregarFila.addEventListener('click', () => {
        // Crear una nueva tabla
        const nuevaTabla = document.createElement('table');
        nuevaTabla.className = 'table table-hover table-bordered';
        nuevaTabla.style.textAlign = 'center';
        nuevaTabla.style.marginBottom = '20px';

        const nuevaTablaImagenes = document.createElement('table');
        nuevaTablaImagenes.className = 'table table-hover table-bordered';
        nuevaTablaImagenes.style.textAlign = 'center';
        nuevaTablaImagenes.style.marginBottom = '20px';

        // Incrementar el correlativo cada vez que se agrega una nueva tabla
        correlativo++;

        // Agregar contenido a la tabla
        nuevaTabla.innerHTML = `
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Puerta</th>
                <th>PMA:</th>
                <th colspan="5"><input type="text"  id="pmapuerta${correlativo}" name="pmapuerta${correlativo}" class="form-control"></th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
            </tr>
            <input type="hidden" id="numeroPuerta${correlativo}" name="numeroPuerta${correlativo}" value="Puerta ${correlativo}">
        </thead>
        <tbody>
            <tr>
                <td>Biometrico</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposbiometrico${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lectortarjeta${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lectorhuella${correlativo}"></td>
                <td><input type="checkbox" value="1" name="lucesindicadorasbiometrico${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
            </tr>
            <tr>
                <td>Magnetico</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposmagnetico${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="lucesindicadorasmagnetico${correlativo}"></td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="estadoferroMagnetico${correlativo}"></td>
            </tr>
            <tr>
                <td>Botonera</td>
                <td><input type="checkbox" value="1" name="limpiezaequiposbotonera${correlativo}"></td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td>No Aplica</td>
                <td><input type="checkbox" value="1" name="botonesoperativos${correlativo}"></td>
                <td>No Aplica</td>
            </tr>
        </tbody>
        <br>
        <label>Marcar Todos</label>
        <br>
        <input type="checkbox" id="marcar-todos-${correlativo}" style="float: right;" onchange="marcarInputs(${correlativo})">
    `;

        nuevaTablaImagenes.innerHTML = `
        <thead style="text-align: center">
            <tr class="bg-secondary">
                <th colspan="2">Puerta (Imagen Termografica)</th>
            </tr>
            <tr>
                <th>Subir Imagen</th>
                <th>Valores</th>
            </tr>
        </thead>
         <tbody style="text-align: center">
            <tr>
                <td>
                    <input type="file" required class="form-control" name="imagenes${correlativo}">
                </td>
                <td>
                    <input type="text" required  class="form-control" name="valorTermo${correlativo}" id="valorTermo${correlativo}">
                </td>
            </tr>
        </tbody>
        <input type="hidden" name="hashImagen${correlativo}" id="hashImagen${correlativo}">
        `;

        // Crear botón de eliminar
        const botonEliminar = document.createElement('button');
        botonEliminar.textContent = 'Eliminar';
        botonEliminar.className = 'btn btn-danger';
        botonEliminar.style.marginTop = '10px';

        // Evento para eliminar ambas tablas
        botonEliminar.addEventListener('click', () => {
            nuevaTabla.remove();
            nuevaTablaImagenes.remove();
            contadorTablas--;
            cantidadFilasInput.value = contadorTablas;
            correlativo--;
            console.log('Número total de tablas:', contadorTablas);
        });

        nuevaTabla.appendChild(botonEliminar);

        // Agregar tabla principal al contenedor de tablas
        contenedorTablas.appendChild(nuevaTabla);

        // Agregar tabla de imágenes al contenedor de imágenes
        contenedorImagenes.appendChild(nuevaTablaImagenes);

        // Actualizar contador
        contadorTablas++;
        cantidadFilasInput.value = contadorTablas;

        console.log('Número total de tablas:', contadorTablas);

// === Asignar evento de hash a input dinámico ===
        // === Asignar evento de hash a input dinámico ===
        const inputFile = nuevaTablaImagenes.querySelector(`input[type="file"]`);
        const hashInput = nuevaTablaImagenes.querySelector(`#hashImagen${correlativo}`);

        inputFile.addEventListener('change', async function () {
            // Limpiar el campo de hash antes de asignar un nuevo hash
            hashInput.value = ''; // Limpiar campo oculto

            for (const file of this.files) {
                const reader = new FileReader();
                reader.onload = function () {
                    // Crear objeto SHA-256
                    const shaObj = new jsSHA("SHA-256", "ARRAYBUFFER");
                    shaObj.update(reader.result);
                    const hashHex = shaObj.getHash("HEX");

                    // Asignar el hash al campo oculto
                    hashInput.value = hashHex;

                    // Si quieres mostrar el hash en consola o verificarlo
                    console.log(`Archivo: ${file.name}`);
                    console.log(`Hash SHA-256: ${hashHex}`);
                };
                reader.readAsArrayBuffer(file);
            }
        });

    });

</script>
<script>
    $(document).ready(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            placeholder: 'Seleccione...',
            theme: 'bootstrap4',
            width: '100%',

        });
    });
</script>

<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
<script src="js/dark-mode.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "10000",
        "closeButton": true,
        "progressBar": true
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formCheckUCA');
        const estadoSelect = document.getElementById('estado');

        form.addEventListener('submit', function (e) {
            if (!estadoSelect.value) {
                e.preventDefault(); // Detiene el envío
                estadoSelect.classList.add('is-invalid');

                toastr.error('Debe seleccionar un estado válido.', 'Campo obligatorio');
            } else {
                estadoSelect.classList.remove('is-invalid');
            }
        });
    });
</script>
<style>
    .is-invalid {
        border: 2px solid #dc3545 !important;
        background-color: #fff5f5 !important;
    }
</style>
<script>
    function marcarTodosChecklistUCA() {
        const masterCheck = document.getElementById('checkAllEstado');
        const checkboxes = document.querySelectorAll('.check-uca');

        checkboxes.forEach(cb => cb.checked = masterCheck.checked);
    }
</script>
<script>
    // Desmarcar "checkAllEstado" si se desmarca alguno individualmente
    document.querySelectorAll('.check-uca').forEach(cb => {
        cb.addEventListener('change', () => {
            const todos = document.querySelectorAll('.check-uca');
            const marcados = document.querySelectorAll('.check-uca:checked');
            document.getElementById('checkAllEstado').checked = (todos.length === marcados.length);
        });
    });
</script>

</html>