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
        $idEquipamiento = $row['idEquipamiento'];
    }
    if($idEquipamiento == 0){
        $idTareaP = 0;
    }else{
        $sqlPlanificador = "SELECT * FROM `tareas_carta_gantt` WHERE idEquipamiento = $idEquipamiento";
        $resPlanificador = mysqli_query($conexion,$sqlPlanificador);
        while($rowPlanificador = $resPlanificador->fetch_assoc()){
            $idTareaP = $rowPlanificador['idTareaP'];
        }
    }

    $contarchecklistLLE = contarchecklistLLE($idTG);

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List LLE</title>

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
    <script src="https://cdn.jsdelivr.net/npm/jssha@3.2.0/dist/sha256.min.js"></script>
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>

    <script>
        function marcarOtroInput(){
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
            }else{
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
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List LLE</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas: <?php echo $contarchecklistucas = contarchecklistucas($idTG); ?></b>
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
                                <form action="../sdo-funciones/guardar-checklist-lle.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idTG" value="<?php echo $idTG ?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <div class="form-row">
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">RECINTO:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text"  class="form-control" name="recinto" required value="">
                                                </div>
                                            </div>
                                            <div class="form-group col-mb-2">
                                                <label style="float: left;">FECHA:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="datetime-local" class="form-control" name="fecha" required value="">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tirador de Baño con Estados-->

                                        <fieldset class="border p-3">
                                            <div class="form-row">

                                                <!-- Tirador de Baño -->
                                                <div class="form-group col-md-6">
                                                    <label style="float: left;">Tirador de Baño:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-desktop"></i>
                    </span>
                                                        </div>
                                                        <select name="id_tirador_bano" id="tirador_bano" class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <?php
                                                            $sqlBano = "SELECT id, recinto, pma 
                                FROM equipamiento_hbqp 
                                WHERE nombreEquipo = 'Tirador de baño (NX0073)'";
                                                            $resBano = mysqli_query($conexion, $sqlBano);
                                                            while ($row = mysqli_fetch_assoc($resBano)) {
                                                                ?>
                                                                <option value="<?= $row['id']; ?>">
                                                                    <?= $row['recinto']; ?> - <?= $row['pma']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="hidden" name="id_modulo_bano" id="id_modulo_bano">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label style="float: left;">Modulo de 3 botones:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <select name="id_modulo_3_botones" id="modulo_3_botones" class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <?php
                                                            $sqlBotones = "SELECT id, recinto, pma 
                                                        FROM equipamiento_hbqp 
                                                        WHERE nombreEquipo = 'Módulo de 3 Botones (NX0392)'";
                                                            $resBotones = mysqli_query($conexion, $sqlBotones);

                                                            while ($rowBotones = mysqli_fetch_assoc($resBotones)) {
                                                                ?>
                                                                <option value="<?= $rowBotones['id']; ?>">
                                                                    <?= $rowBotones['recinto']; ?> - <?= $rowBotones['pma']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label style="float: left;">Luz de Aviso - Plafón:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <select name="id_luz_plafon" id="luz_plafon" class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <?php
                                                            $sqlAvisoPlafon = "SELECT id, recinto, pma 
                                                        FROM equipamiento_hbqp 
                                                        WHERE nombreEquipo = 'Luz Aviso - Plafón (NX0173)'";
                                                            $resAvisoPlafon = mysqli_query($conexion, $sqlAvisoPlafon);

                                                            while ($rowAvisoPlafon = mysqli_fetch_assoc($resAvisoPlafon)) {
                                                                ?>
                                                                <option value="<?= $rowAvisoPlafon['id']; ?>">
                                                                    <?= $rowAvisoPlafon['recinto']; ?> - <?= $rowAvisoPlafon['pma']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label style="float: left;">Consola de LLamado de Enfermeria:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <select name="id_consola_enfermeria" id="consola_enfermeria" class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <?php
                                                            $sqlAvisoConsola = "SELECT id, recinto, pma 
                                                        FROM equipamiento_hbqp 
                                                        WHERE nombreEquipo = 'Consola de Enfermera (NX0418)'";
                                                            $resAvisoConsola = mysqli_query($conexion, $sqlAvisoConsola);

                                                            while ($rowAvisoConsola = mysqli_fetch_assoc($resAvisoConsola)) {
                                                                ?>
                                                                <option value="<?= $rowAvisoConsola['id']; ?>">
                                                                    <?= $rowAvisoConsola['recinto']; ?> - <?= $rowAvisoConsola['pma']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Estado del Tirador -->

                                            </div>
                                        </fieldset>
                                        <br>
                                        <br>
                                        <table class="table table-bordered" id="tabla_checklist_lle">
                                            <thead class="text-center">
                                                <tr>
                                                    <td rowspan="2">Item</td>
                                                    <td rowspan="2">Componente</td>
                                                    <td rowspan="2">Tarea de Inspección</td>
                                                    <td colspan="3">Estado(ok/falla/no aplica)</td>
                                                    <td rowspan="2" style="width: 30%">Observación</td>
                                                    <td rowspan="2" style="width: 20%">Estados Generales</td>
                                                </tr>
                                                <tr>
                                                    <td>OK</td>
                                                    <td>Falla</td>
                                                    <td>N/A</td>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>I.1</td>
                                                    <td>
                                                        Tirador de Baño (nx0073)
                                                    </td>
                                                    <td>
                                                        🔍 Condición Física: Inspeccionar cable/tirador por daños, cortes, desgaste excesivo o faltante.
                                                    </td>
                                                    <td><input type="checkbox" name="ok_I_1"></td>
                                                    <td><input type="checkbox" name="falla_I_1"></td>
                                                    <td><input type="checkbox" name="na_I_1"></td>
                                                    <td><textarea class="form-control" name="obsI_1"></textarea></td>
                                                    <td>
                                                        <select name="id_tirador_bano_estado"
                                                                id="tirador_bano_estado"
                                                                class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>I.2</td>
                                                    <td>
                                                        Módulo de Baño (NX0062)
                                                    </td>
                                                    <td>
                                                        🚨 Prueba Funcional: Accionar manualmente el tirador para activar la alarma. Confirmar que el anclaje soporta la tensión.
                                                    </td>
                                                    <td><input type="checkbox" name="ok_I_2"></td>
                                                    <td><input type="checkbox" name="falla_I_2"></td>
                                                    <td><input type="checkbox" name="na_I_2"></td>
                                                    <td><textarea class="form-control" name="obsI_2"></textarea></td>
                                                    <td rowspan="2">
                                                        <br>
                                                        <br>
                                                        <select name="id_modulo_bano_estado"
                                                                id="modulo_bano_estado"
                                                                class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>I.3</td>
                                                    <td>Módulo de Baño (NX0062)</td>
                                                    <td>
                                                        🔍 Condición Física: Inspeccionar cable/tirador por daños, cortes, desgaste excesivo o faltante.
                                                    </td>
                                                    <td><input type="checkbox" name="ok_I_3"></td>
                                                    <td><input type="checkbox" name="falla_I_3"></td>
                                                    <td><input type="checkbox" name="na_I_3"></td>
                                                    <td><textarea class="form-control" name="obsI_3"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>I.4</td>
                                                    <td>Plafón (nx0173)</td>
                                                    <td>🔊 Visualización/Sonido: Confirmar que, tras I.2, enciendan las luces (Roja/Blanca) y se emita el sonido de alarma.</td>
                                                    <td><input type="checkbox" name="ok_I_4"></td>
                                                    <td><input type="checkbox" name="falla_I_4"></td>
                                                    <td><input type="checkbox" name="na_I_4"></td>
                                                    <td><textarea class="form-control" name="obsI_4"></textarea></td>
                                                    <td>
                                                        <select name="id_luz_plafon_estado"
                                                                id="luz_plafon_estado"
                                                                class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>I.5</td>
                                                    <td>Módulo 3 Botones (nx0392)</td>
                                                    <td>🟢 Prueba de Presencia: Presionar el botón Verde. Confirmar que se activa la Luz Verde en el Plafón (nx0173) y en la propia unidad.</td>
                                                    <td><input type="checkbox" name="ok_I_5"></td>
                                                    <td><input type="checkbox" name="falla_I_5"></td>
                                                    <td><input type="checkbox" name="na_I_5"></td>
                                                    <td><textarea class="form-control" name="obsI_5"></textarea></td>
                                                    <td rowspan="4">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <select name="id_modulo_3_botones_estado"
                                                                id="modulo_3_botones_estado"
                                                                class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>I.6</td>
                                                    <td>Módulo 3 Botones (nx0392)</td>
                                                    <td>Prueba de Cancelación: Presionar nuevamente el botón Verde. Confirmar que se cancela la alarma y se apagan todas las luces.</td>
                                                    <td><input type="checkbox" name="ok_I_6"></td>
                                                    <td><input type="checkbox" name="falla_I_6"></td>
                                                    <td><input type="checkbox" name="na_I_6"></td>
                                                    <td><textarea class="form-control" name="obsI_6"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>I.7</td>
                                                    <td>Módulo 3 Botones (nx0392)</td>
                                                    <td>Prueba con Aviso a Personal Clínico: Presionar el botón rojo para probar si se detecta la alarma en la consola y sale el color rojo en el plafón</td>
                                                    <td><input type="checkbox" name="ok_I_7"></td>
                                                    <td><input type="checkbox" name="falla_I_7"></td>
                                                    <td><input type="checkbox" name="na_I_7"></td>
                                                    <td><textarea class="form-control" name="obsI_7"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>I.8</td>
                                                    <td>Módulo 3 Botones (nx0392)</td>
                                                    <td>Limpieza/Mecánica: Realizar limpieza externa. Revisar el tacto y funcionamiento de los tres botones (sin atascos).</td>
                                                    <td><input type="checkbox" name="ok_I_8"></td>
                                                    <td><input type="checkbox" name="falla_I_8"></td>
                                                    <td><input type="checkbox" name="na_I_8"></td>
                                                    <td><textarea class="form-control" name="obsI_8"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>J.1</td>
                                                    <td>Consola (nx0019/nx0418)</td>
                                                    <td>🚨 Recepción de Alarma: Confirmar que la alarma activada en la Habitación (I.2) (I.7) (I.9) (I.12) aparece Visual y Auditivamente. Verificar que muestre el tipo de alarma (Baño) (Solicitud de Personal Clínico) y la ubicación correcta.</td>
                                                    <td><input type="checkbox" name="ok_I_14"></td>
                                                    <td><input type="checkbox" name="falla_I_14"></td>
                                                    <td><input type="checkbox" name="na_I_14"></td>
                                                    <td><textarea class="form-control" name="obsI_14"></textarea></td>
                                                    <td rowspan="5">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <select name="id_consola_llamado_estado"
                                                                id="consola_llamado_estado"
                                                                class="form-control">
                                                            <option value="" disabled selected>Seleccione</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>J.2</td>
                                                    <td>Consola (nx0019/nx0418)</td>
                                                    <td>🔔 Manejo de Alarma (Acuse de Recibo): Presionar el botón o acción de "Confirmar presencia". Confirmar que la alerta sonora se detiene y la alarma visual queda registrada como Pendiente (sin desaparecer) hasta que el técnico la cancele en la habitación (I.5)(I.7).</td>
                                                    <td><input type="checkbox" name="ok_I_15"></td>
                                                    <td><input type="checkbox" name="falla_I_15"></td>
                                                    <td><input type="checkbox" name="na_I_15"></td>
                                                    <td><textarea class="form-control" name="obsI_15"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>J.3</td>
                                                    <td>Consola (nx0019/nx0418)</td>
                                                    <td>🔌 Conectividad y Estado Operacional: Verificar que esté encendida, conectada a la red POE y que el símbolo de operativa se muestre en pantalla.</td>
                                                    <td><input type="checkbox" name="ok_I_16"></td>
                                                    <td><input type="checkbox" name="falla_I_16"></td>
                                                    <td><input type="checkbox" name="na_I_16"></td>
                                                    <td><textarea class="form-control" name="obsI_16"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>J.4</td>
                                                    <td>Consola (nx0019/nx0418)</td>
                                                    <td>Limpieza y Condición Física: Realizar limpieza externa y desinfección. Inspeccionar la pantalla y botones por daños.</td>
                                                    <td><input type="checkbox" name="ok_I_17"></td>
                                                    <td><input type="checkbox" name="falla_I_17"></td>
                                                    <td><input type="checkbox" name="na_I_17"></td>
                                                    <td><textarea class="form-control" name="obsI_17"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>J.5</td>
                                                    <td>Consola (nx0019/nx0418)</td>
                                                    <td>📄 Revisión de Alarmas Históricas: Revisar el registro de eventos de las últimas 24/48 horas para detectar fallas intermitentes o "alarmas fantasmas".</td>
                                                    <td><input type="checkbox" name="ok_I_18"></td>
                                                    <td><input type="checkbox" name="falla_I_18"></td>
                                                    <td><input type="checkbox" name="na_I_18"></td>
                                                    <td><textarea class="form-control" name="obsI_18"></textarea></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <hr>
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-sm btn-success" onclick="agregarTerminal()">➕ Agregar Terminal y Pulsador</button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="quitarTerminal()">➖ Quitar Terminal y Pulsador</button>
                                        </div>

                                        <table class="table table-bordered"
                                                id="tabla_checklist_lle_2"
                                                style="width: 100%; table-layout: fixed;">

                                            <thead class="text-center">
                                            <tr id="thead-terminales">
                                                <th style="width:5%">Item</th>
                                                <th style="width:15%">Componente</th>
                                                <th style="width:45%">Tarea de Inspección</th>

                                                <!-- Terminal inicial -->
                                                <th class="terminal-col" style="width:10%">
                                                    <select name="id_terminal_ip_0" id="terminal_ip_0" class="form-control">
                                                        <option value="" disabled selected>Seleccione</option>
                                                        <?php
                                                        $sqlTerminal = "SELECT id, recinto, pma 
                    FROM equipamiento_hbqp 
                    WHERE nombreEquipo like '%Terminal IP%'";
                                                        $resTerminal = mysqli_query($conexion, $sqlTerminal);
                                                        while ($rowTerminal = mysqli_fetch_assoc($resTerminal)) {
                                                            ?>
                                                            <option
                                                                    value="<?= $rowTerminal['id']; ?>"
                                                                    data-recinto="<?= $rowTerminal['recinto']; ?>"
                                                            >
                                                                <?= $rowTerminal['recinto']; ?> - <?= $rowTerminal['pma']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody class="text-center">

                                            <!-- I.9 -->
                                            <tr>
                                                <td>I.9</td>
                                                <td>Terminal IP</td>
                                                <td>Prueba de Alarma: botón rojo</td>

                                                <td class="terminal-col">
                                                    <input type="radio" name="I9_0" value="OK"> OK<br>
                                                    <input type="radio" name="I9_0" value="FALLA"> Falla<br>
                                                    <input type="radio" name="I9_0" value="NA"> N/A
                                                </td>
                                            </tr>

                                            <!-- I.10 -->
                                            <tr>
                                                <td>I.10</td>
                                                <td>Terminal IP</td>
                                                <td>Prueba de Presencia: botón verde</td>

                                                <td class="terminal-col">
                                                    <input type="radio" name="I10_0" value="OK"> OK<br>
                                                    <input type="radio" name="I10_0" value="FALLA"> Falla<br>
                                                    <input type="radio" name="I10_0" value="NA"> N/A
                                                </td>
                                            </tr>

                                            <!-- I.11 -->
                                            <tr>
                                                <td>I.11</td>
                                                <td>Terminal IP</td>
                                                <td>Prueba de Cancelación: Quitar las alarmas o la presencia</td>

                                                <td class="terminal-col">
                                                    <input type="radio" name="I11_0" value="OK"> OK<br>
                                                    <input type="radio" name="I11_0" value="FALLA"> Falla<br>
                                                    <input type="radio" name="I11_0" value="NA"> N/A
                                                </td>
                                            </tr>

                                            <!-- I.12 -->
                                            <tr>
                                                <td>I.12</td>
                                                <td>Terminal IP</td>
                                                <td>Condición Física</td>

                                                <td class="terminal-col">
                                                    <input type="radio" name="I12_0" value="OK"> OK<br>
                                                    <input type="radio" name="I12_0" value="FALLA"> Falla<br>
                                                    <input type="radio" name="I12_0" value="NA"> N/A
                                                </td>
                                            </tr>

                                            </tbody>
                                            <tfoot>
                                            <tr id="fila-observaciones">
                                                <td colspan="3">Observaciones</td>

                                                <!-- Observación terminal inicial -->
                                                <td class="terminal-col" data-index="0">
                                                    <textarea name="observacion_0" style="width:100%"></textarea>
                                                </td>
                                            </tr>
                                            <tr id="fila-estado-general">
                                                <td colspan="3">Estado General del Equipo</td>
                                                <td class="terminal-col" data-index="0">
                                                    <select name="estado_general_terminal_0"
                                                            class="form-control">
                                                        <option value="" disabled selected>Seleccione</option>
                                                        <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                        <option value="Mantenimiento">Mantenimiento</option>
                                                        <option value="Operativo">Operativo</option>
                                                        <option value="OperativoObs">Operativo con Observaciones</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <br>
                                        <br>
                                        <hr>
                                        <table class="table table-bordered"
                                                id="tabla_checklist_lle_4"
                                                style="width: 100%; table-layout: fixed;">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width:5%">Item</th>
                                                <th style="width:15%">Componente</th>
                                                <th style="width:45%">Tarea de Inspección</th>
                                                <th class="terminal-col" style="width:10%">
                                                    <select name="pulsador_0" id="pulsador_0" class="form-control">
                                                        <option selected value="" disabled>Seleccione</option>
                                                        <?php
                                                        $sqlPulsador = "SELECT id, recinto, pma 
                    FROM equipamiento_hbqp 
                    WHERE nombreEquipo LIKE '%Pulsador de Cama (NX0881)%'";
                                                        $resPulsador = mysqli_query($conexion, $sqlPulsador);

                                                        while ($rowPulsador = mysqli_fetch_assoc($resPulsador)) {
                                                            ?>
                                                            <option
                                                                    value="<?= $rowPulsador['id']; ?>"
                                                                    data-recinto="<?= $rowPulsador['recinto']; ?>"
                                                            >
                                                                <?= $rowPulsador['recinto']; ?> - <?= $rowPulsador['pma']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                        <tr>
                                            <td>I.13</td>
                                            <td>Pulsador de Cama (NX0881)</td>
                                            <td>
                                                Prueba de Alarma: Botón rojo
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="I13_0" value="OK"> OK<br>
                                                <input type="radio" name="I13_0" value="FALLA"> Falla<br>
                                                <input type="radio" name="I13_0" value="NA"> N/A
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>I.14</td>
                                            <td>Pulsador de Cama (NX0881)</td>
                                            <td>
                                                Condición Física
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="I14_0" value="OK"> OK<br>
                                                <input type="radio" name="I14_0" value="FALLA"> Falla<br>
                                                <input type="radio" name="I14_0" value="NA"> N/A
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr id="fila-observaciones-pulsador">
                                            <td colspan="3">Observaciones</td>

                                            <!-- Observación terminal inicial -->
                                            <td class="terminal-col">
                                                <textarea name="observacion_pulsador_0" style="width:100%"></textarea>
                                            </td>
                                        </tr>
                                        <tr id="fila-estado-general-pulsador">
                                            <td colspan="3">Estado General del Equipo</td>
                                            <td class="terminal-col">
                                                <select name="estado_general_pulsador_0"
                                                        class="form-control">
                                                    <option value="" disabled selected>Seleccione</option>
                                                    <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                    <option value="Operativo">Operativo</option>
                                                    <option value="OperativoObs">Operativo con Observaciones</option>
                                                </select>
                                            </td>
                                        </tr>
                                        </tfoot>
                                        </table>
                                    </fieldset>
                                    <br>
                                    <button class="btn-success btn-block btn-lg">Guardar</button>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script>
    document.getElementById('tirador_bano').addEventListener('change', function () {
        let idTirador = this.value;

        fetch('buscar_modulo_bano.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id_tirador=' + idTirador
        })
            .then(response => response.text())
            .then(idModulo => {
                document.getElementById('id_modulo_bano').value = idModulo;
            });
    });
</script>
<script>
    const opcionesTerminalIP = `
        <option value="" disabled selected>Seleccione</option>
        <?php
    mysqli_data_seek($resTerminal, 0);
    while ($rowTerminal = mysqli_fetch_assoc($resTerminal)) {
        echo "<option value='{$rowTerminal['id']}'>
                    {$rowTerminal['recinto']} - {$rowTerminal['pma']}
                  </option>";
    }
    ?>
    `;
    const opcionesPulsador = `
        <option value="" disabled selected>Seleccione</option>
        <?php
    mysqli_data_seek($resPulsador, 0);
    while ($rowPulsador = mysqli_fetch_assoc($resPulsador)) {
        echo "<option value='{$rowPulsador['id']}'>
                    {$rowPulsador['recinto']} - {$rowPulsador['pma']}
                  </option>";
    }
    ?>
    `;
</script>
<script>
    let terminalIndex = 1; // 0 es fijo

    function agregarTerminal() {

        /* =====================================================
           TABLA TERMINAL IP (tabla_checklist_lle_2)
        ===================================================== */

        // THEAD
        const thTerminal = document.createElement('th');
        thTerminal.className = 'terminal-col';
        thTerminal.setAttribute('data-index', terminalIndex);
        thTerminal.innerHTML = `
        <select name="id_terminal_ip_${terminalIndex}"
                class="form-control form-control-sm">
            ${opcionesTerminalIP}
        </select>
    `;
        document.getElementById('thead-terminales').appendChild(thTerminal);

        // TBODY
        document.querySelectorAll('#tabla_checklist_lle_2 tbody tr').forEach(tr => {
            const item = tr.children[0].innerText.replace('.', '');
            const td = document.createElement('td');
            td.className = 'terminal-col text-center';
            td.setAttribute('data-index', terminalIndex);
            td.innerHTML = `
            <input type="radio" name="${item}_${terminalIndex}" value="OK"> OK<br>
            <input type="radio" name="${item}_${terminalIndex}" value="FALLA"> Falla<br>
            <input type="radio" name="${item}_${terminalIndex}" value="NA"> N/A
        `;
            tr.appendChild(td);
        });

        // TFOOT – Observaciones
        const tdObs = document.createElement('td');
        tdObs.className = 'terminal-col';
        tdObs.setAttribute('data-index', terminalIndex);
        tdObs.innerHTML = `
        <textarea name="observacion_${terminalIndex}" style="width:100%"></textarea>
    `;
        document.getElementById('fila-observaciones').appendChild(tdObs);

        // TFOOT – Estado General
        const tdEstado = document.createElement('td');
        tdEstado.className = 'terminal-col';
        tdEstado.setAttribute('data-index', terminalIndex);
        tdEstado.innerHTML = `
        <select name="estado_general_terminal_${terminalIndex}" class="form-control">
            <option value="" disabled selected>Seleccione</option>
            <option value="Fuera de Servicio">Fuera de Servicio</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Operativo">Operativo</option>
            <option value="OperativoObs">Operativo con Observaciones</option>
        </select>
    `;
        document.getElementById('fila-estado-general').appendChild(tdEstado);


        /* =====================================================
           TABLA PULSADOR (tabla_checklist_lle_4)
        ===================================================== */

        // THEAD
        const thPulsador = document.createElement('th');
        thPulsador.className = 'terminal-col';
        thPulsador.setAttribute('data-index', terminalIndex);
        thPulsador.innerHTML = `
        <select name="pulsador_${terminalIndex}" class="form-control">
            ${opcionesPulsador}
        </select>
    `;
        document.querySelector('#tabla_checklist_lle_4 thead tr')
            .appendChild(thPulsador);

        // TBODY
        document.querySelectorAll('#tabla_checklist_lle_4 tbody tr').forEach(tr => {
            const item = tr.children[0].innerText.replace('.', '');
            const td = document.createElement('td');
            td.className = 'terminal-col text-center';
            td.setAttribute('data-index', terminalIndex);
            td.innerHTML = `
            <input type="radio" name="${item}_${terminalIndex}" value="OK"> OK<br>
            <input type="radio" name="${item}_${terminalIndex}" value="FALLA"> Falla<br>
            <input type="radio" name="${item}_${terminalIndex}" value="NA"> N/A
        `;
            tr.appendChild(td);
        });

        // TFOOT – Observaciones Pulsador
        const tdObsP = document.createElement('td');
        tdObsP.className = 'terminal-col';
        tdObsP.setAttribute('data-index', terminalIndex);
        tdObsP.innerHTML = `
        <textarea name="observacion_pulsador_${terminalIndex}" style="width:100%"></textarea>
    `;
        document.getElementById('fila-observaciones-pulsador').appendChild(tdObsP);

        // TFOOT – Estado Pulsador
        const tdEstadoP = document.createElement('td');
        tdEstadoP.className = 'terminal-col';
        tdEstadoP.setAttribute('data-index', terminalIndex);
        tdEstadoP.innerHTML = `
        <select name="estado_general_pulsador_${terminalIndex}" class="form-control">
            <option value="" disabled selected>Seleccione</option>
            <option value="Fuera de Servicio">Fuera de Servicio</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Operativo">Operativo</option>
            <option value="OperativoObs">Operativo con Observaciones</option>
        </select>
    `;
        document.getElementById('fila-estado-general-pulsador').appendChild(tdEstadoP);

        terminalIndex++;
    }
</script>
<script>
    function quitarTerminal() {

        if (terminalIndex <= 1) {
            alert('No se puede eliminar la terminal inicial');
            return;
        }

        const index = terminalIndex - 1;

        document.querySelectorAll(`th[data-index="${index}"]`).forEach(e => e.remove());
        document.querySelectorAll(`td[data-index="${index}"]`).forEach(e => e.remove());

        terminalIndex--;
    }
</script>

</html>
