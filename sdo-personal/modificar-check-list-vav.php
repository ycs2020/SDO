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
    $idVav = $_POST['idVAV'];
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

    $sqlVAV = "SELECT * FROM `datos_vav` WHERE idVav = $idVav";
    $resVAV = mysqli_query($conexion, $sqlVAV);
    while($rowVAV = $resVAV->fetch_assoc()){
        $ecbos = $rowVAV['ecbos'];
        $macaddress = $rowVAV['macaddress'];
        $controlador = $rowVAV['controlador'];
        $redComunicacion = $rowVAV['redComunicación'];
        $sistemaosub = $rowVAV['sistemaosub'];
        $folio = $rowVAV['folio'];
        $obsGeneral = $rowVAV['obsGeneral'];
        $fechaVav = $rowVAV['fecha'];
        $idEquipamiento = $rowVAV['idEquipamiento'];
    }


    $sqlEquipamiento = "SELECT * FROM `equipamiento_hbqp` WHERE id = $idEquipamiento";
    $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
    while($rowEquipamiento = $resEquipamiento->fetch_assoc()){
        $estadoEquipo = $rowEquipamiento['estadoEquipo'];
    }

    $respuestas = array();

    for ($item_id = 1; $item_id <= 14; $item_id++) {
        // Consulta SQL para obtener las respuestas del item actual
        $sqlRespuesta = "SELECT * FROM `respuestas` WHERE item_id = $item_id AND idVAV = $idVav;";
        $resRespuesta = mysqli_query($conexion, $sqlRespuesta);

        // Verificamos si hay resultados
        if ($resRespuesta && $rowRespuesta = $resRespuesta->fetch_assoc()) {
            // Guardamos los valores en el array asociativo
            $idRespuestas = $rowRespuesta['id'];
            $respuestas[$item_id] = array(
                'id' => $idRespuestas,
                'f' => $rowRespuesta['f'],
                'si' => $rowRespuesta['si'],
                'na' => $rowRespuesta['na'],
                'no' => $rowRespuesta['no'],
                'condicion' => $rowRespuesta['condicion'],
                'parametros' => $rowRespuesta['parametros'],
                'observacion' => $rowRespuesta['observacion']
            );
        } else {
            // Si no se encontró la respuesta para este item_id, asignamos valores predeterminados
            $respuestas[$item_id] = array(
                'f' => '',
                'si' => 0,
                'na' => 0,
                'no' => 0,
                'condicion' => '',
                'parametros' => '',
                'observacion' => ''
            );
        }
    }

// Ahora puedes acceder a los valores de cada item de la siguiente manera:
// item 1
    $idRegistros1 = $respuestas[1]['id'];
    $f1 = $respuestas[1]['f'];
    $SI1 = $respuestas[1]['si'];
    $NA1 = $respuestas[1]['na'];
    $NO1 = $respuestas[1]['no'];
    $condicion1 = $respuestas[1]['condicion'];
    $parametros1 = $respuestas[1]['parametros'];
    $observacion1 = $respuestas[1]['observacion'];

// item 2
    $idRegistros2 = $respuestas[2]['id'];
    $f2 = $respuestas[2]['f'];
    $SI2 = $respuestas[2]['si'];
    $NA2 = $respuestas[2]['na'];
    $NO2 = $respuestas[2]['no'];
    $condicion2 = $respuestas[2]['condicion'];
    $parametros2 = $respuestas[2]['parametros'];
    $observacion2 = $respuestas[2]['observacion'];

// item 3
    $idRegistros3 = $respuestas[3]['id'];
    $f3 = $respuestas[3]['f'];
    $SI3 = $respuestas[3]['si'];
    $NA3 = $respuestas[3]['na'];
    $NO3 = $respuestas[3]['no'];
    $condicion3 = $respuestas[3]['condicion'];
    $parametros3 = $respuestas[3]['parametros'];
    $observacion3 = $respuestas[3]['observacion'];

// item 4
    $idRegistros4 = $respuestas[4]['id'];
    $f4 = $respuestas[4]['f'];
    $SI4 = $respuestas[4]['si'];
    $NA4 = $respuestas[4]['na'];
    $NO4 = $respuestas[4]['no'];
    $condicion4 = $respuestas[4]['condicion'];
    $parametros4 = $respuestas[4]['parametros'];
    $observacion4 = $respuestas[4]['observacion'];

// item 5
    $idRegistros5 = $respuestas[5]['id'];
    $f5 = $respuestas[5]['f'];
    $SI5 = $respuestas[5]['si'];
    $NA5 = $respuestas[5]['na'];
    $NO5 = $respuestas[5]['no'];
    $condicion5 = $respuestas[5]['condicion'];
    $parametros5 = $respuestas[5]['parametros'];
    $observacion5 = $respuestas[5]['observacion'];

// item 6
    $idRegistros6 = $respuestas[6]['id'];
    $f6 = $respuestas[6]['f'];
    $SI6 = $respuestas[6]['si'];
    $NA6 = $respuestas[6]['na'];
    $NO6 = $respuestas[6]['no'];
    $condicion6 = $respuestas[6]['condicion'];
    $parametros6 = $respuestas[6]['parametros'];
    $observacion6 = $respuestas[6]['observacion'];

// item 7
    $idRegistros7 = $respuestas[7]['id'];
    $f7 = $respuestas[7]['f'];
    $SI7 = $respuestas[7]['si'];
    $NA7 = $respuestas[7]['na'];
    $NO7 = $respuestas[7]['no'];
    $condicion7 = $respuestas[7]['condicion'];
    $parametros7 = $respuestas[7]['parametros'];
    $observacion7 = $respuestas[7]['observacion'];

// item 8
    $idRegistros8 = $respuestas[8]['id'];
    $f8 = $respuestas[8]['f'];
    $SI8 = $respuestas[8]['si'];
    $NA8 = $respuestas[8]['na'];
    $NO8 = $respuestas[8]['no'];
    $condicion8 = $respuestas[8]['condicion'];
    $parametros8 = $respuestas[8]['parametros'];
    $observacion8 = $respuestas[8]['observacion'];

// item 9
    $idRegistros9 = $respuestas[9]['id'];
    $f9 = $respuestas[9]['f'];
    $SI9 = $respuestas[9]['si'];
    $NA9 = $respuestas[9]['na'];
    $NO9 = $respuestas[9]['no'];
    $condicion9 = $respuestas[9]['condicion'];
    $parametros9 = $respuestas[9]['parametros'];
    $observacion9 = $respuestas[9]['observacion'];

// item 10
    $idRegistros10 = $respuestas[10]['id'];
    $f10 = $respuestas[10]['f'];
    $SI10 = $respuestas[10]['si'];
    $NA10 = $respuestas[10]['na'];
    $NO10 = $respuestas[10]['no'];
    $condicion10 = $respuestas[10]['condicion'];
    $parametros10 = $respuestas[10]['parametros'];
    $observacion10 = $respuestas[10]['observacion'];

// item 11
    $idRegistros11 = $respuestas[11]['id'];
    $f11 = $respuestas[11]['f'];
    $SI11 = $respuestas[11]['si'];
    $NA11 = $respuestas[11]['na'];
    $NO11 = $respuestas[11]['no'];
    $condicion11 = $respuestas[11]['condicion'];
    $parametros11 = $respuestas[11]['parametros'];
    $observacion11 = $respuestas[11]['observacion'];

// item 12
    $idRegistros12 = $respuestas[12]['id'];
    $f12 = $respuestas[12]['f'];
    $SI12 = $respuestas[12]['si'];
    $NA12 = $respuestas[12]['na'];
    $NO12 = $respuestas[12]['no'];
    $condicion12 = $respuestas[12]['condicion'];
    $parametros12 = $respuestas[12]['parametros'];
    $observacion12 = $respuestas[12]['observacion'];

// item 13
    $idRegistros13 = $respuestas[13]['id'];
    $f13 = $respuestas[13]['f'];
    $SI13 = $respuestas[13]['si'];
    $NA13 = $respuestas[13]['na'];
    $NO13 = $respuestas[13]['no'];
    $condicion13 = $respuestas[13]['condicion'];
    $parametros13 = $respuestas[13]['parametros'];
    $observacion13 = $respuestas[13]['observacion'];

// item 14
    $idRegistros14 = $respuestas[14]['id'];
    $f14 = $respuestas[14]['f'];
    $SI14 = $respuestas[14]['si'];
    $NA14 = $respuestas[14]['na'];
    $NO14 = $respuestas[14]['no'];
    $condicion14 = $respuestas[14]['condicion'];
    $parametros14 = $respuestas[14]['parametros'];
    $observacion14 = $respuestas[14]['observacion'];

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List VAV</title>

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
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List VAV</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas: <?php echo $contarchecklistvav = contarchecklistvav($idTG);?></b>
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
                                <form action="../sdo-funciones/modificar-checklist-vav.php" method="POST">
                                    <input type="hidden" name="folio" id="folio" value="<?php echo $folio?>">
                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipamiento?>">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <input type="hidden" name="idVav" id="idVav" value="<?php echo $idVav?>">
                                    <input type="hidden" name="idRegistro1" id="idRegistro1" value="<?php echo $idRegistros1?>">
                                    <input type="hidden" name="idRegistro2" id="idRegistro2" value="<?php echo $idRegistros2?>">
                                    <input type="hidden" name="idRegistro3" id="idRegistro3" value="<?php echo $idRegistros3?>">
                                    <input type="hidden" name="idRegistro4" id="idRegistro4" value="<?php echo $idRegistros4?>">
                                    <input type="hidden" name="idRegistro5" id="idRegistro5" value="<?php echo $idRegistros5?>">
                                    <input type="hidden" name="idRegistro6" id="idRegistro6" value="<?php echo $idRegistros6?>">
                                    <input type="hidden" name="idRegistro7" id="idRegistro7" value="<?php echo $idRegistros7?>">
                                    <input type="hidden" name="idRegistro8" id="idRegistro8" value="<?php echo $idRegistros8?>">
                                    <input type="hidden" name="idRegistro9" id="idRegistro9" value="<?php echo $idRegistros9?>">
                                    <input type="hidden" name="idRegistro10" id="idRegistro10" value="<?php echo $idRegistros10?>">
                                    <input type="hidden" name="idRegistro11" id="idRegistro11" value="<?php echo $idRegistros11?>">
                                    <input type="hidden" name="idRegistro12" id="idRegistro12" value="<?php echo $idRegistros12?>">
                                    <input type="hidden" name="idRegistro13" id="idRegistro13" value="<?php echo $idRegistros13?>">
                                    <input type="hidden" name="idRegistro14" id="idRegistro14" value="<?php echo $idRegistros14?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <div class="form-group col-mb-6">
                                            <label style="float: left;">Controlador actualmente seleccionado:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                                </div>
                                                <input type="text" class="form-control" readonly id="controlador" name="controlador" value="<?php echo $controlador?>" oninput="this.value = this.value.toUpperCase()" required>
                                            </div>
                                        </div>
                                        <label>Cambiar Controlador:</label>
                                        <div class="form-row">
                                            <select name="vavID" id="vavID" class="form-control select2" style="width: 100%" onchange="updateInput()">
                                                <?php
                                                $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE ('%VAV_%') ORDER BY nombreEquipo ASC";
                                                $resServicio = mysqli_query($conexion,$sqlServicio);
                                                ?>
                                                <option value="" disabled selected>Seleccione un equipo</option>
                                                <?php
                                                while($rowServicio = $resServicio->fetch_assoc()){
                                                    $idEquipo = $rowServicio['id'];
                                                    $nombreEquipo = $rowServicio['nombreEquipo'];
                                                    ?>
                                                    <option value="<?php echo $idEquipo?>"><?php echo $nombreEquipo?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="nuevocontrolador" id="nuevocontrolador">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="">
                                            <script>
                                                function updateInput() {
                                                    var selectElement = document.getElementById("vavID");
                                                    var inputElement = document.getElementById("nuevocontrolador");
                                                    var idEquipo = document.getElementById("idEquipo");
                                                    var selectedOption = selectElement.options[selectElement.selectedIndex];

                                                    // Verifica si se ha seleccionado una opción válida
                                                    if (selectedOption.value !== "") {
                                                        inputElement.value = selectedOption.textContent;
                                                        idEquipo.value = selectedOption.value;
                                                    } else {
                                                        // Si se selecciona la opción predeterminada, limpia el valor del input
                                                        inputElement.value = "";
                                                    }
                                                }
                                            </script>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">EC-BOS:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="ecbos" name="ecbos" oninput="this.value = this.value.toUpperCase()" value="<?php echo $ecbos?>">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">MAC-ADDRESS:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="macaddress" name="macaddress" value="<?php echo $macaddress?>" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">RED DE COMUNICACIÓN:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="redComunicacion" name="redComunicacion" value="<?php echo $redComunicacion?>" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="form-row">
                                                <div class="form-group col-12">
                                                    <label style="float: left;">ESTADO DEL EQUIPO:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <?php
                                                            if($estadoEquipo == 'Operativo'){
                                                                ?>
                                                                <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                    <option  disabled value="">Seleccione....</option>
                                                                    <option value="Operativo" selected>Operativo</option>
                                                                    <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                                    <option value="OperativoObs">Operativo con Observaciones</option>
                                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoEquipo == 'Fuera de Servicio'){
                                                                ?>
                                                                <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                    <option  disabled value="">Seleccione....</option>
                                                                    <option value="Operativo" >Operativo</option>
                                                                    <option value="Fuera de Servicio" selected>Fuera de Servicio</option>
                                                                    <option value="OperativoObs">Operativo con Observaciones</option>
                                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoEquipo == 'OperativoObs'){
                                                                ?>
                                                                <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                    <option  disabled value="">Seleccione....</option>
                                                                    <option value="Operativo" >Operativo</option>
                                                                    <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                    <option value="OperativoObs" selected>Operativo con Observaciones</option>
                                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                                </select>
                                                                <?php
                                                            }else if($estadoEquipo == 'Mantenimiento'){
                                                                ?>
                                                                <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                    <option  disabled value="">Seleccione....</option>
                                                                    <option value="Operativo" >Operativo</option>
                                                                    <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                    <option value="OperativoObs" >Operativo con Observaciones</option>
                                                                    <option value="Mantenimiento" selected>Mantenimiento</option>
                                                                </select>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                                    <option  disabled selected value="">Seleccione....</option>
                                                                    <option value="Operativo" >Operativo</option>
                                                                    <option value="Fuera de Servicio" >Fuera de Servicio</option>
                                                                    <option value="OperativoObs" >Operativo con Observaciones</option>
                                                                    <option value="Mantenimiento" >Mantenimiento</option>
                                                                </select>
                                                                <?php
                                                            }
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="form-row">
                                                <div class="form-group col-12"> <!-- Cambié col-mb-12 por col-12 para usar todo el ancho -->
                                                    <label style="float: left;">SISTEMA O SUB-SISTEMA:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="sistemaosub" name="sistemaosub" value="<?php echo $sistemaosub?>" oninput="this.value = this.value.toUpperCase()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $datos = 'f=FRECUENCIA  M=Mensual  T=Trimestral  S=Semestral  A=Anual  A+=5 AÑOS O MAS  O=OPERATIVO';
                                        ?>
                                        <input type="text" disabled style="text-align: center" class="form-control" value="<?php echo $datos?>">
                                        <br>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center;">ITEMIZADO DE MANTENCIÓN</th>
                                                <th style="text-align: center;">f</th>
                                                <th style="text-align: center;">SI</th>
                                                <th style="text-align: center;">N/A</th>
                                                <th style="text-align: center;">NO</th>
                                                <th style="text-align: center;">INDICAR<br>CONDICION</th>
                                                <th style="text-align: center;">INDICAR<br>PARAMETROS</th>
                                                <th style="text-align: center;">OBSERVACION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>¿Existe comunicación entre el BMS y el controlador?</td>
                                                <td><input type="text" class="form-control" name="f1" id="f1" value="<?php echo $f1?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI1 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI1" id="SI1" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI1" id="SI1" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NA1 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NA1" id="NA1" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NA1" id="NA1" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO1 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO1" id="NO1" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO1" id="NO1" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion1?>" class="form-control" name="condicion1" id="condicion1"></td>
                                                <td><input type="text" value="<?php echo $parametros1?>" class="form-control" name="parametros1" id="parametros1"></td>
                                                <td><input type="text" value="<?php echo $observacion1?>" class="form-control" name="observacion1" id="observacion1"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Las terminales en el controlador se encuentran bien apretadas y libres de sulfato?</td>
                                                <td><input type="text" class="form-control" name="f2" id="f2" value="<?php echo $f2?>"></td>
                                                <td>
                                                    <?php
                                                    if($SI2 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="SI2" id="SI2" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="SI2" id="SI2" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NA2 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NA2" id="NA2" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NA2" id="NA2" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NO2 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NO2" id="NO2" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NO2" id="NO2" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion2?>" class="form-control" name="condicion2" id="condicion2"></td>
                                                <td><input type="text" value="<?php echo $parametros2?>" class="form-control" name="parametros2" id="parametros2"></td>
                                                <td><input type="text" value="<?php echo $observacion2?>" class="form-control" name="observacion2" id="observacion2"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Las mangueras se encuentran en buenas condiciones? </td>
                                                <td><input type="text" class="form-control" name="f3" id="f3" value="<?php echo $f3?>"></td>
                                                <td>
                                                    <?php
                                                    if($SI3 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="SI3" id="SI3" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="SI3" id="SI3" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NA3 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NA3" id="NA3" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NA3" id="NA3" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NO3 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NO3" id="NO3" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NO3" id="NO3" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion3?>" class="form-control" name="condicion3" id="condicion3"></td>
                                                <td><input type="text" value="<?php echo $parametros3?>" class="form-control" name="parametros3" id="parametros3"></td>
                                                <td><input type="text" value="<?php echo $observacion3?>" class="form-control" name="observacion3" id="observacion3"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El voltaje de C.A de alimentación es el adecuado? (MIN: 24 - MAX 27 V)</td>
                                                <td><input type="text" class="form-control" name="f4" id="f4" value="<?php echo $f4?>"></td>
                                                <td>
                                                    <?php
                                                    if($SI4 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="SI4" id="SI4" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="SI4" id="SI4" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NA4 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NA4" id="NA4" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NA4" id="NA4" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NO4 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NO4" id="NO4" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NO4" id="NO4" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion4?>" class="form-control" name="condicion4" id="condicion4"></td>
                                                <td><input type="text" value="<?php echo $parametros4?>" class="form-control" name="parametros4" id="parametros4"></td>
                                                <td><input type="text" value="<?php echo $observacion4?>" class="form-control" name="observacion4" id="observacion4"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El termostato se encuentra en buenas condiciones?</td>
                                                <td><input type="text" class="form-control" name="f5" id="f5" value="<?php echo $f5?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI5){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI5" id="SI5" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox"  class="d-flex justify-content-center" name="SI5" id="SI5" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NA5){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NA5" id="NA5" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NA5" id="NA5" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO5){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO5" id="NO5" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO5" id="NO5" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion5?>" class="form-control" name="condicion5" id="condicion5"></td>
                                                <td><input type="text" value="<?php echo $parametros5?>" class="form-control" name="parametros5" id="parametros5"></td>
                                                <td><input type="text" value="<?php echo $observacion5?>" class="form-control" name="observacion5" id="observacion5"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja el estado del termostato en BMS?</td>
                                                <td><input type="text" class="form-control" name="f6" id="f6" value="<?php echo $f6?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI6 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI6" id="SI6" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI6" id="SI6" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA6" id="NA6" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA6" id="NA6" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO6" id="NO6" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO6" id="NO6" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion6?>" class="form-control" name="condicion6" id="condicion6"></td>
                                                <td><input type="text" value="<?php echo $parametros6?>" class="form-control" name="parametros6" id="parametros6"></td>
                                                <td><input type="text" value="<?php echo $observacion6?>" class="form-control" name="observacion6" id="observacion6"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se puede modificar el Set de temperatura?</td>
                                                <td><input type="text" class="form-control" name="f7" id="f7" value="<?php echo $f7?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI7 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI7" id="SI7" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI7" id="SI7" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA7 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA7" id="NA7" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA7" id="NA7" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO7 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO7" id="NO7" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO7" id="NO7" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion7?>" class="form-control" name="condicion7" id="condicion7"></td>
                                                <td><input type="text" value="<?php echo $parametros7?>" class="form-control" name="parametros7" id="parametros7"></td>
                                                <td><input type="text" value="<?php echo $observacion7?>" class="form-control" name="observacion7" id="observacion7"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja el Set de temperatura en BMS?</td>
                                                <td><input type="text" class="form-control" name="f8" id="f8" value="<?php echo $f8?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI8 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI8" id="SI8" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI8" id="SI8" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA8 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA8" id="NA8" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA8" id="NA8" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($NO8 == 1){
                                                        ?>
                                                        <input type="checkbox" checked class="d-flex justify-content-center" name="NO8" id="NO8" value="1">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="checkbox" class="d-flex justify-content-center" name="NO8" id="NO8" value="1">
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion8?>" class="form-control" name="condicion8" id="condicion8"></td>
                                                <td><input type="text" value="<?php echo $parametros8?>" class="form-control" name="parametros8" id="parametros8"></td>
                                                <td><input type="text" value="<?php echo $observacion8?>" class="form-control" name="observacion8" id="observacion8"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Funciona la apertura y cierre del equipo?</td>
                                                <td><input type="text" class="form-control" name="f9" id="f9" value="<?php echo $f9?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI9 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI9" id="SI9" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI9" id="SI9" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA9 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA9" id="NA9" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA9" id="NA9" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO9 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO9" id="NO9" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO9" id="NO9" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion9?>" class="form-control" name="condicion9" id="condicion9"></td>
                                                <td><input type="text" value="<?php echo $parametros9?>" class="form-control" name="parametros9" id="parametros9"></td>
                                                <td><input type="text" value="<?php echo $observacion9?>" class="form-control" name="observacion9" id="observacion9"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja la apertura y cierre de la VAV en el BMS?</td>
                                                <td><input type="text" class="form-control" name="f10" id="f10" value="<?php echo $f10?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI10 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI10" id="SI10" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox"  class="d-flex justify-content-center" name="SI10" id="SI10" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA10 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA10" id="NA10" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA10" id="NA10" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO10 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO10" id="NO10" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO10" id="NO10" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion10?>" class="form-control" name="condicion10" id="condicion10"></td>
                                                <td><input type="text" value="<?php echo $parametros10?>" class="form-control" name="parametros10" id="parametros10"></td>
                                                <td><input type="text" value="<?php echo $observacion10?>" class="form-control" name="observacion10" id="observacion10"></td>
                                            </tr>
                                            <tr>
                                                <td>¿La lógica de programación opera adecuadamente?</td>
                                                <td><input type="text" class="form-control" name="f11" id="f11" value="<?php echo $f11?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI11 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI11" id="SI11" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI11" id="SI11" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA11 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA11" id="NA11" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA11" id="NA11" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO11 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO11" id="NO11" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO11" id="NO11" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion11?>" class="form-control" name="condicion11" id="condicion11"></td>
                                                <td><input type="text" value="<?php echo $parametros11?>" class="form-control" name="parametros11" id="parametros11"></td>
                                                <td><input type="text" value="<?php echo $observacion11?>" class="form-control" name="observacion11" id="observacion11"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El voltaje de la red de comunicación MSTP/Bacnet es el adecuado? (MIN: 0,4 - MAX1,5)</td>
                                                <td><input type="text" class="form-control" name="f12" id="f12" value="<?php echo $f12?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI12 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI12" id="SI12" value="1">

                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI12" id="SI12" value="1">

                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA12 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA12" id="NA12" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA12" id="NA12" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO12 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO12" id="NO12" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO12" id="NO12" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion12?>" class="form-control" name="condicion12" id="condicion12"></td>
                                                <td><input type="text" value="<?php echo $parametros12?>" class="form-control" name="parametros12" id="parametros12"></td>
                                                <td><input type="text" value="<?php echo $observacion12?>" class="form-control" name="observacion12" id="observacion12"></td>
                                            </tr>
                                            <tr>
                                                <td>¿La medición de la presión diferencial del equipo VAV es el adecuado?</td>
                                                <td>
                                                    <input type="text" class="form-control" name="f13" id="f13" value="<?php echo $f13?>">
                                                </td>
                                                <td>
                                                    <?php
                                                        if($SI13 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI13" id="SI13" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI13" id="SI13" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA13 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA13" id="NA13" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NA13" id="NA13" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO13 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO13" id="NO13" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO13" id="NO13" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion13?>" class="form-control" name="condicion13" id="condicion13"></td>
                                                <td><input type="text" value="<?php echo $parametros13?>" class="form-control" name="parametros13" id="parametros13"></td>
                                                <td><input type="text" value="<?php echo $observacion13?>" class="form-control" name="observacion13" id="observacion13"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se realiza limpieza completa a la carcasa y placa?</td>
                                                <td><input type="text" class="form-control" name="f14" id="f14" value="<?php echo $f14?>"></td>
                                                <td>
                                                    <?php
                                                        if($SI14 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="SI14" id="SI14" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="SI14" id="SI14" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NA14 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NA14" id="NA14" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox"  class="d-flex justify-content-center" name="NA14" id="NA14" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($NO14 == 1){
                                                            ?>
                                                            <input type="checkbox" checked class="d-flex justify-content-center" name="NO14" id="NO14" value="1">
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <input type="checkbox" class="d-flex justify-content-center" name="NO14" id="NO14" value="1">
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><input type="text" value="<?php echo $condicion14?>" class="form-control" name="condicion14" id="condicion14"></td>
                                                <td><input type="text" value="<?php echo $parametros14?>" class="form-control" name="parametros14" id="parametros14"></td>
                                                <td><input type="text" value="<?php echo $observacion14?>" class="form-control" name="observacion14" id="observacion14"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <nt
                                        <label>Observaciones Generales:</label>
                                        <textarea class="form-control" rows="10" name="observacionesGenerales" id="observacionesGenerales"><?php echo $obsGeneral?></textarea>
                                        <hr>
                                        <button type="submit" class="btn btn-block bg-success">Guardar</button>
                                    </fieldset>
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
</html>
