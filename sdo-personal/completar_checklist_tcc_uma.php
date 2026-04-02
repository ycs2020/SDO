<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


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
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
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
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List TCC de UMA</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
            var enlaceBotones = document.getElementById('limpiezaTablero');
            var enlaceImagenes = document.getElementById('verificacionVoltaje');
            var enlaceparametros = document.getElementById('limpiezaReapriete');
            var enlacegraficas = document.getElementById('listadoPuntos');
            var enlacetendencias = document.getElementById('rotuladoComponentes');
            var ordenbloques = document.getElementById('verificacionIntegracion');
            var voltaje = document.getElementById('chequeoRutina');
            var sensorTemp1 = document.getElementById('sensorInyeccionTemp1');
            var sensorTemp2 = document.getElementById('sensorInyeccionTemp2');
            var sensorTemp3 = document.getElementById('sensorInyeccionTemp3');
            var sensorTemp4 = document.getElementById('sensorRetornoTemp1');
            var sensorTemp5 = document.getElementById('sensorRetornoTemp2');
            var sensorTemp6 = document.getElementById('sensorRetornoTemp3');
            var sensorPresion1 = document.getElementById('sensorInyeccionPresion1');
            var sensorPresion2 = document.getElementById('sensorInyeccionPresion2');
            var sensorPresion3 = document.getElementById('sensorInyeccionPresion3');
            var sensorPresion4 = document.getElementById('sensorRetornoPresion1');
            var sensorPresion5 = document.getElementById('sensorRetornoPresion2');
            var sensorPresion6 = document.getElementById('sensorRetornoPresion3');
            var sensorHumedad1 = document.getElementById('sensorInyeccionHumedad1');
            var sensorHumedad2 = document.getElementById('sensorInyeccionHumedad2');
            var sensorHumedad3 = document.getElementById('sensorInyeccionHumedad3');
            var sensorHumedad4 = document.getElementById('sensorRetornoHumedad1');
            var sensorHumedad5 = document.getElementById('sensorRetornoHumedad2');
            var sensorHumedad6 = document.getElementById('sensorRetornoHumedad3');
            var variadorFrecuencia1 = document.getElementById('variadorFrecuenciaInyeccion1');
            var variadorFrecuencia2 = document.getElementById('variadorFrecuenciaInyeccion2');
            var variadorFrecuencia3 = document.getElementById('variadorFrecuenciaInyeccion3');
            var variadorFrecuencia4 = document.getElementById('variadorFrecuenciaRetorno1');
            var variadorFrecuencia5 = document.getElementById('variadorFrecuenciaRetorno2');
            var variadorFrecuencia6 = document.getElementById('variadorFrecuenciaRetorno3');
            var spdEstado1 = document.getElementById('spdEstadoInyeccion1');
            var spdEstado2 = document.getElementById('spdEstadoInyeccion2');
            var spdEstado3 = document.getElementById('spdEstadoInyeccion3');
            var spdEstado4 = document.getElementById('spdEstadoRetorno1');
            var spdEstado5 = document.getElementById('spdEstadoRetorno2');
            var spdEstado6 = document.getElementById('spdEstadoRetorno3');
            var valvulasMotorizadas1 = document.getElementById('valvulasMotorizadasInyeccion1');
            var valvulasMotorizadas2 = document.getElementById('valvulasMotorizadasInyeccion2');
            var valvulasMotorizadas3 = document.getElementById('valvulasMotorizadasInyeccion3');
            var valvulasMotorizadas4 = document.getElementById('valvulasMotorizadasRetorno1');
            var valvulasMotorizadas5 = document.getElementById('valvulasMotorizadasRetorno2');
            var valvulasMotorizadas6 = document.getElementById('valvulasMotorizadasRetorno3');
            var g4inyeccion1 = document.getElementById('G4Inyeccion1');
            var g4inyeccion2 = document.getElementById('G4Inyeccion2');
            var g4inyeccion3 = document.getElementById('G4Inyeccion3');
            var g4retorno1 = document.getElementById('G4Retorno1');
            var g4retorno2 = document.getElementById('G4Retorno2');
            var g4retorno3 = document.getElementById('G4Retorno3');
            var f71_1 = document.getElementById('F7_1');
            var f71_2 = document.getElementById('F7_2');
            var f71_3 = document.getElementById('F7_3');
            var f9_1 = document.getElementById('F9_1');
            var f9_2 = document.getElementById('F9_2');
            var f9_3 = document.getElementById('F9_3');
            var h13_1 = document.getElementById('H13_1');
            var h13_2 = document.getElementById('H13_2');
            var h13_3 = document.getElementById('H13_3');
            var e12_1 = document.getElementById('E12_1');
            var e12_2 = document.getElementById('E12_2');
            var e12_3 = document.getElementById('E12_3');

            if (primerInput.checked) {
                enlaceBotones.checked = true;
                enlaceImagenes.checked = true;
                enlaceparametros.checked = true;
                enlacegraficas.checked = true;
                enlacetendencias.checked = true;
                ordenbloques.checked = true;
                voltaje.checked = true;
                sensorTemp1.checked = true;
                sensorTemp2.checked = true;
                sensorTemp3.checked = true;
                sensorTemp4.checked = true;
                sensorTemp5.checked = true;
                sensorTemp6.checked = true;
                sensorPresion1.checked = true;
                sensorPresion2.checked = true;
                sensorPresion3.checked = true;
                sensorPresion4.checked = true;
                sensorPresion5.checked = true;
                sensorPresion6.checked = true;
                sensorHumedad1.checked = true;
                sensorHumedad2.checked = true;
                sensorHumedad3.checked = true;
                sensorHumedad4.checked = true;
                sensorHumedad5.checked = true;
                sensorHumedad6.checked = true;
                variadorFrecuencia1.checked = true;
                variadorFrecuencia2.checked = true;
                variadorFrecuencia3.checked = true;
                variadorFrecuencia4.checked = true;
                variadorFrecuencia5.checked = true;
                variadorFrecuencia6.checked = true;
                spdEstado1.checked = true;
                spdEstado2.checked = true;
                spdEstado3.checked = true;
                spdEstado4.checked = true;
                spdEstado5.checked = true;
                spdEstado6.checked = true;
                valvulasMotorizadas1.checked = true;
                valvulasMotorizadas2.checked = true;
                valvulasMotorizadas3.checked = true;
                valvulasMotorizadas4.checked = true;
                valvulasMotorizadas5.checked = true;
                valvulasMotorizadas6.checked = true;
                g4inyeccion1.checked = true;
                g4inyeccion2.checked = true;
                g4inyeccion3.checked = true;
                g4retorno1.checked = true;
                g4retorno2.checked = true;
                g4retorno3.checked = true;
                f71_1.checked = true;
                f71_2.checked = true;
                f71_3.checked = true;
                f9_1.checked = true;
                f9_2.checked = true;
                f9_3.checked = true;
                h13_1.checked = true;
                h13_2.checked = true;
                h13_3.checked = true;
                e12_1.checked = true;
                e12_2.checked = true;
                e12_3.checked = true;
            } else {
                enlaceBotones.checked = false;
                enlaceImagenes.checked = false;
                enlaceparametros.checked = false;
                enlacegraficas.checked = false;
                enlacetendencias.checked = false;
                ordenbloques.checked = false;
                voltaje.checked = false;
                sensorTemp1.checked = false;
                sensorTemp2.checked = false;
                sensorTemp3.checked = false;
                sensorTemp4.checked = false;
                sensorTemp5.checked = false;
                sensorTemp6.checked = false;
                sensorPresion1.checked = false;
                sensorPresion2.checked = false;
                sensorPresion3.checked = false;
                sensorPresion4.checked = false;
                sensorPresion5.checked = false;
                sensorPresion6.checked = false;
                sensorHumedad1.checked = false;
                sensorHumedad2.checked = false;
                sensorHumedad3.checked = false;
                sensorHumedad4.checked = false;
                sensorHumedad5.checked = false;
                sensorHumedad6.checked = false;
                variadorFrecuencia1.checked = false;
                variadorFrecuencia2.checked = false;
                variadorFrecuencia3.checked = false;
                variadorFrecuencia4.checked = false;
                variadorFrecuencia5.checked = false;
                variadorFrecuencia6.checked = false;
                spdEstado1.checked = false;
                spdEstado2.checked = false;
                spdEstado3.checked = false;
                spdEstado4.checked = false;
                spdEstado5.checked = false;
                spdEstado6.checked = false;
                valvulasMotorizadas1.checked = false;
                valvulasMotorizadas2.checked = false;
                valvulasMotorizadas3.checked = false;
                valvulasMotorizadas4.checked = false;
                valvulasMotorizadas5.checked = false;
                valvulasMotorizadas6.checked = false;
                g4inyeccion1.checked = false;
                g4inyeccion2.checked = false;
                g4inyeccion3.checked = false;
                g4retorno1.checked = false;
                g4retorno2.checked = false;
                g4retorno3.checked = false;
                f71_1.checked = false;
                f71_2.checked = false;
                f71_3.checked = false;
                f9_1.checked = false;
                f9_2.checked = false;
                f9_3.checked = false;
                h13_1.checked = false;
                h13_2.checked = false;
                h13_3.checked = false;
                e12_1.checked = false;
                e12_2.checked = false;
                e12_3.checked = false;
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
            }else{
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
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List TCC de UMA</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas: <?php echo $contarchecklisttccuma = contarchecklisttccuma($idTG); ?></b>
                                    </h5>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2"
                                     name="volverAtras2">
                                    <!-- small box -->

                                    <a href="visualizardetalles.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>
                                <hr>
                                <form action="../sdo-funciones/guardar_checklist_tcc_uma.php" method="POST">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">TCC:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="tcc" name="tcc" oninput="this.value = this.value.toUpperCase()" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">CONTROLADOR:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="controlador" name="controlador" oninput="this.value = this.value.toUpperCase()" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">UMA:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="uma" name="uma" oninput="this.value = this.value.toUpperCase()" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">FRECUENCIA:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="frecuencia" name="frecuencia" oninput="this.value = this.value.toUpperCase()" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-mb-2">
                                            <label class="form-check-label" style="float: right;"> Marcar Todos</label>
                                            <br>
                                            <input type="checkbox" style="float: right;" class="show-mostrar" onchange="marcarOtroInput()">
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <table cellpadding="2" class="table table-hover table-bordered" style="text-align: center; width: 45%;">
                                                <thead style="background-color: indigo;color: #FFFFFF;text-align: justify;">
                                                    <tr>
                                                        <td>
                                                            TCC de Unidad manejadora de aire
                                                        </td>
                                                        <td>
                                                            Estado
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Limpieza general del tablero y su entorno</td>
                                                        <td>
                                                            <input type="checkbox" id="limpiezaTablero" name="limpiezaTablero" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Verificación de voltaje (DC/AC)</td>
                                                        <td>
                                                            <input type="checkbox" id="verificacionVoltaje" name="verificacionVoltaje" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Limpieza y Reapriete de terminales</td>
                                                        <td>
                                                            <input type="checkbox" id="limpiezaReapriete" name="limpiezaReapriete" value="1" class="form-check-input">

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Listado de puntos (entradas y salidas)</td>
                                                        <td>
                                                            <input type="checkbox" id="listadoPuntos" name="listadoPuntos" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rotulado de componentes</td>
                                                        <td>
                                                            <input type="checkbox" id="rotuladoComponentes" name="rotuladoComponentes" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Verificación de integración y funcionamiento en BMS</td>
                                                        <td>
                                                            <input type="checkbox" id="verificacionIntegracion" name="verificacionIntegracion" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Chequeo a la rutina de programación y alarmas</td>
                                                        <td>
                                                            <input type="checkbox" id="chequeoRutina" name="chequeoRutina" value="1" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-hover table-bordered" style="width: 35%; margin-left: 200pt; text-align: center">
                                                <thead style="background-color: indigo; color: #FFFFFF">
                                                <tr>
                                                    <td colspan="2" style="width: 100%; height: 50px;">
                                                        Equipos y Dispositivos
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 20%; height: 50px;">1</td>
                                                    <td style="width: 80%; height: 50px;">Verificación de estado del actuador y/o sensor</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%; height: 50px;">2</td>
                                                    <td style="width: 80%; height: 50px;">Se realiza limpieza al actuador y/o sensor</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%; height: 50px;">3</td>
                                                    <td style="width: 80%; height: 50px;">Revisión de conexionado del actuador y/o sensor</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-8 col-12">
                                                <div class="form-row">
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">Sensor de Temperatura</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyeccion
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionTemp1" name="sensorInyeccionTemp1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionTemp2" name="sensorInyeccionTemp2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionTemp3" name="sensorInyeccionTemp3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoTemp1" name="sensorRetornoTemp1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoTemp2" name="sensorRetornoTemp2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoTemp3" name="sensorRetornoTemp3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">Sensor de Presión</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyección
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionPresion1" name="sensorInyeccionPresion1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionPresion2" name="sensorInyeccionPresion2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionPresion3" name="sensorInyeccionPresion3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoPresion1" name="sensorRetornoPresion1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoPresion2" name="sensorRetornoPresion2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoPresion3" name="sensorRetornoPresion3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">Sensor de Humedad</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyección
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionHumedad1" name="sensorInyeccionHumedad1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionHumedad2" name="sensorInyeccionHumedad2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorInyeccionHumedad3" name="sensorInyeccionHumedad3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoHumedad1" name="sensorRetornoHumedad1" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoHumedad2" name="sensorRetornoHumedad2" value="1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="sensorRetornoHumedad3" name="sensorRetornoHumedad3" value="1" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="form-row">
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">Variador de Frecuencia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyección
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaInyeccion1" value="1" name="variadorFrecuenciaInyeccion1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaInyeccion2" value="1" name="variadorFrecuenciaInyeccion2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaInyeccion3" value="1" name="variadorFrecuenciaInyeccion3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaRetorno1" value="1" name="variadorFrecuenciaRetorno1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaRetorno2" value="1" name="variadorFrecuenciaRetorno2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="variadorFrecuenciaRetorno3" value="1" name="variadorFrecuenciaRetorno3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">SPD Estado</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyección
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoInyeccion1" value="1" name="spdEstadoInyeccion1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoInyeccion2" value="1" name="spdEstadoInyeccion2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoInyeccion3" value="1" name="spdEstadoInyeccion3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoRetorno1" value="1" name="spdEstadoRetorno1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoRetorno2" value="1" name="spdEstadoRetorno2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="spdEstadoRetorno3" value="1" name="spdEstadoRetorno3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="table table-hover table-bordered" style="text-align: center; width: 33%;">
                                                        <thead>
                                                        <tr style="background-color: indigo;color: #FFFFFF">
                                                            <td colspan="4">Válvulas motorizadas</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Seccion</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Inyección
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasInyeccion1" value="1" name="valvulasMotorizadasInyeccion1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasInyeccion2" value="1" name="valvulasMotorizadasInyeccion2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasInyeccion3" value="1" name="valvulasMotorizadasInyeccion3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retorno</td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasRetorno1" value="1" name="valvulasMotorizadasRetorno1" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasRetorno2" value="1" name="valvulasMotorizadasRetorno2" class="form-check-input">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="valvulasMotorizadasRetorno3" value="1" name="valvulasMotorizadasRetorno3" class="form-check-input">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-12">
                                                <table class="table table-hover table-bordered" style="text-align: center;width: 100%;">
                                                    <thead>
                                                    <tr style="background-color: indigo;color: #FFFFFF">
                                                        <td colspan="4">Sensor de presión diferencial (Filtro)</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Seccion</td>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            G4 Inyección
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="G4Inyeccion1" value="1" name="G4Inyeccion1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="G4Inyeccion2" value="1" name="G4Inyeccion2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="G4Inyeccion3" value="1" name="G4Inyeccion3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>G4 Retorno</td>
                                                        <td>
                                                            <input type="checkbox" id="G4Retorno1" value="1" name="G4Retorno1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="G4Retorno2" value="1" name="G4Retorno2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="G4Retorno3" value="1" name="G4Retorno3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>F7</td>
                                                        <td>
                                                            <input type="checkbox" id="F7_1" value="1" name="F7_1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="F7_2" value="1" name="F7_2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="F7_3" value="1" name="F7_3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>F9</td>
                                                        <td>
                                                            <input type="checkbox" id="F9_1" value="1" name="F9_1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="F9_2" value="1" name="F9_2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="F9_3" value="1" name="F9_3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>H13</td>
                                                        <td>
                                                            <input type="checkbox" id="H13_1" value="1" name="H13_1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="H13_2" value="1" name="H13_2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="H13_3" value="1" name="H13_3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>E12</td>
                                                        <td>
                                                            <input type="checkbox" id="E12_1" value="1" name="E12_1" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="E12_2" value="1" name="E12_2" class="form-check-input">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" id="E12_3" value="1" name="E12_3" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>


                                    </fieldset>
                                    <br>
                                    <fieldset class="border p-3">
                                        <label style="font-size: 20px">OBSERVACIONES:</label>
                                        <textarea required class="form-control" style="resize: none" rows="15" id="obs" name="obs"></textarea>
                                    </fieldset>
                                    <br>
                                    <button type="submit" class="btn btn-block bg-success">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
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
</html>
