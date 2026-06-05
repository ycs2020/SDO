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

    $sqlPlanificador = "SELECT * FROM `tareas_carta_gantt` WHERE idEquipamiento = $idEquipamiento";
    $resPlanificador = mysqli_query($conexion,$sqlPlanificador);
    while($rowPlanificador = $resPlanificador->fetch_assoc()){
        $idTareaP = $rowPlanificador['idTareaP'];
    }

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
                                <form action="../sdo-funciones/guardar-checklist-vav.php" method="POST">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaP?>">
                                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario?>">
                                    <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario2?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <label>Controlador</label>
                                        <div class="form-row">
                                            <select name="vavID" id="vavID" class="form-control select2" style="width: 100%" required onchange="updateInput()">
                                                <option value="" disabled selected>Seleccione un equipo</option>
                                                <?php
                                                // Consultar la base de datos para obtener los equipos
                                                $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE '%VAV_%' ORDER BY nombreEquipo ASC";
                                                $resServicio = mysqli_query($conexion, $sqlServicio);

                                                // Verificar si $idEquipamiento tiene un valor
                                                while ($rowServicio = $resServicio->fetch_assoc()) {
                                                    $idEquipo = $rowServicio['id'];
                                                    $idCategoria = $rowServicio['idCategoria'];
                                                    $nombreEquipo = $rowServicio['nombreEquipo'];

                                                    // Si el id del equipo es igual al $idEquipamiento, marcarlo como seleccionado
                                                    $selected = ($idEquipo == $idEquipamiento) ? 'selected' : '';

                                                    ?>
                                                    <option value="<?php echo $idEquipo; ?>" <?php echo $selected; ?> data-categoria="<?php echo $idCategoria; ?>"><?php echo $nombreEquipo; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>


                                            <!-- Campo de texto para el nombre del controlador -->
                                            <input type="hidden" name="controlador" id="controlador" value="" readonly>
                                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria?>">

                                            <script>
                                                function updateInput() {
                                                    var selectElement = document.getElementById("vavID");
                                                    var inputElement = document.getElementById("controlador");
                                                    var idCategoriaElement = document.getElementById("idCategoria");
                                                    var selectedOption = selectElement.options[selectElement.selectedIndex];

                                                    // Verifica si se ha seleccionado una opción válida
                                                    if (selectedOption.value !== "") {
                                                        inputElement.value = selectedOption.textContent; // Guarda el nombre del controlador en el input
                                                        idCategoriaElement.value = selectedOption.getAttribute('data-categoria'); // Actualiza el valor de idCategoria
                                                    } else {
                                                        // Si se selecciona la opción predeterminada, limpia los valores
                                                        inputElement.value = "";
                                                        idCategoriaElement.value = ""; // Limpiar el valor de idCategoria
                                                    }
                                                }

                                                // Si $idEquipamiento tiene un valor, seleccionar la opción correspondiente y actualizar el campo de texto
                                                window.onload = function() {
                                                    <?php if (!empty($idEquipamiento)): ?>
                                                    // Establecer el valor del controlador en el campo de texto cuando la página cargue
                                                    var selectElement = document.getElementById("vavID");
                                                    var inputElement = document.getElementById("controlador");
                                                    var idCategoriaElement = document.getElementById("idCategoria");

                                                    // Buscar la opción que coincide con $idEquipamiento
                                                    var selectedOption = selectElement.querySelector('option[value="<?php echo $idEquipamiento; ?>"]');

                                                    // Establecer el valor del input con el nombre del equipo
                                                    if (selectedOption) {
                                                        inputElement.value = selectedOption.textContent;
                                                        idCategoriaElement.value = selectedOption.getAttribute('data-categoria'); // Establecer el idCategoria
                                                    }
                                                    <?php endif; ?>
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
                                                    <input type="text" class="form-control" id="ecbos" name="ecbos" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">MAC-ADDRESS:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="macaddress" name="macaddress" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">RED DE COMUNICACIÓN:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="redComunicacion" name="redComunicacion" oninput="this.value = this.value.toUpperCase()">
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
                                                        <select class="form-control" name="estadoEquipo" required id="estadoEquipo">
                                                            <option selected disabled value="">Seleccione....</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="OperativoObs">Operativo con Observaciones</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                        </select>
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
                                                        <input type="text" class="form-control" id="sistemaosub" name="sistemaosub" oninput="this.value = this.value.toUpperCase()">
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
                                                <td><input type="text" class="form-control" name="f1" id="f1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI1" id="SI1" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA1" id="NA1" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO1" id="NO1" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion1" id="condicion1"></td>
                                                <td><input type="text" class="form-control" name="parametros1" id="parametros1"></td>
                                                <td><input type="text" class="form-control" name="observacion1" id="observacion1"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Las terminales en el controlador se encuentran bien apretadas y libres de sulfato?</td>
                                                <td><input type="text" class="form-control" name="f2" id="f2"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI2" id="SI2" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA2" id="NA2" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO2" id="NO2" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion2" id="condicion2"></td>
                                                <td><input type="text" class="form-control" name="parametros2" id="parametros2"></td>
                                                <td><input type="text" class="form-control" name="observacion2" id="observacion2"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Las mangueras se encuentran en buenas condiciones?</td>
                                                <td><input type="text" class="form-control" name="f3" id="f3"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI3" id="SI3" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA3" id="NA3" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO3" id="NO3" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion3" id="condicion3"></td>
                                                <td><input type="text" class="form-control" name="parametros3" id="parametros3"></td>
                                                <td><input type="text" class="form-control" name="observacion3" id="observacion3"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El voltaje de C.A de alimentación es el adecuado? (MIN: 24 - MAX 27 V)</td>
                                                <td><input type="text" class="form-control" name="f4" id="f4"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI4" id="SI4" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA4" id="NA4" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO4" id="NO4" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion4" id="condicion4"></td>
                                                <td><input type="text" class="form-control" name="parametros4" id="parametros4"></td>
                                                <td><input type="text" class="form-control" name="observacion4" id="observacion4"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El termostato se encuentra en buenas condiciones?</td>
                                                <td><input type="text" class="form-control" name="f5" id="f5"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI5" id="SI5" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA5" id="NA5" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO5" id="NO5" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion5" id="condicion5"></td>
                                                <td><input type="text" class="form-control" name="parametros5" id="parametros5"></td>
                                                <td><input type="text" class="form-control" name="observacion5" id="observacion5"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja el estado del termostato en BMS?</td>
                                                <td><input type="text" class="form-control" name="f6" id="f6"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI6" id="SI6" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA6" id="NA6" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO6" id="NO6" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion6" id="condicion6"></td>
                                                <td><input type="text" class="form-control" name="parametros6" id="parametros6"></td>
                                                <td><input type="text" class="form-control" name="observacion6" id="observacion6"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se puede modificar el Set de temperatura?</td>
                                                <td><input type="text" class="form-control" name="f7" id="f7"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI7" id="SI7" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA7" id="NA7" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO7" id="NO7" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion7" id="condicion7"></td>
                                                <td><input type="text" class="form-control" name="parametros7" id="parametros7"></td>
                                                <td><input type="text" class="form-control" name="observacion7" id="observacion7"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja el Set de temperatura en BMS?</td>
                                                <td><input type="text" class="form-control" name="f8" id="f8"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI8" id="SI8" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA8" id="NA8" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO8" id="NO8" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion8" id="condicion8"></td>
                                                <td><input type="text" class="form-control" name="parametros8" id="parametros8"></td>
                                                <td><input type="text" class="form-control" name="observacion8" id="observacion8"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Funciona la apertura y cierre del equipo?</td>
                                                <td><input type="text" class="form-control" name="f9" id="f9"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI9" id="SI9" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA9" id="NA9" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO9" id="NO9" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion9" id="condicion9"></td>
                                                <td><input type="text" class="form-control" name="parametros9" id="parametros9"></td>
                                                <td><input type="text" class="form-control" name="observacion9" id="observacion9"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se refleja la apertura y cierre de la VAV en el BMS?</td>
                                                <td><input type="text" class="form-control" name="f10" id="f10"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI10" id="SI10" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA10" id="NA10" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO10" id="NO10" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion10" id="condicion10"></td>
                                                <td><input type="text" class="form-control" name="parametros10" id="parametros10"></td>
                                                <td><input type="text" class="form-control" name="observacion10" id="observacion10"></td>
                                            </tr>
                                            <tr>
                                                <td>¿La lógica de programación opera adecuadamente?</td>
                                                <td><input type="text" class="form-control" name="f11" id="f11"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI11" id="SI11" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA11" id="NA11" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO11" id="NO11" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion11" id="condicion11"></td>
                                                <td><input type="text" class="form-control" name="parametros11" id="parametros11"></td>
                                                <td><input type="text" class="form-control" name="observacion11" id="observacion11"></td>
                                            </tr>
                                            <tr>
                                                <td>¿El voltaje de la red de comunicación MSTP/Bacnet es el adecuado? (MIN: 0,4 - MAX1,5)</td>
                                                <td><input type="text" class="form-control" name="f12" id="f12"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI12" id="SI12" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA12" id="NA12" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO12" id="NO12" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion12" id="condicion12"></td>
                                                <td><input type="text" class="form-control" name="parametros12" id="parametros12"></td>
                                                <td><input type="text" class="form-control" name="observacion12" id="observacion12"></td>
                                            </tr>
                                            <tr>
                                                <td>¿La medición de la presión diferencial del equipo VAV es el adecuado?</td>
                                                <td><input type="text" class="form-control" name="f13" id="f13"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI13" id="SI13" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA13" id="NA13" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO13" id="NO13" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion13" id="condicion13"></td>
                                                <td><input type="text" class="form-control" name="parametros13" id="parametros13"></td>
                                                <td><input type="text" class="form-control" name="observacion13" id="observacion13"></td>
                                            </tr>
                                            <tr>
                                                <td>¿Se realiza limpieza completa a la carcasa y placa?</td>
                                                <td><input type="text" class="form-control" name="f14" id="f14"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="SI14" id="SI14" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NA14" id="NA14" value="1"></td>
                                                <td><input type="checkbox" class="d-flex justify-content-center" name="NO14" id="NO14" value="1"></td>
                                                <td><input type="text" class="form-control" name="condicion14" id="condicion14"></td>
                                                <td><input type="text" class="form-control" name="parametros14" id="parametros14"></td>
                                                <td><input type="text" class="form-control" name="observacion14" id="observacion14"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <?php
                                            $datos2 = "Entradas:\nSalidas:\n";
                                        ?>
                                        <label>Observaciones Generales:</label>
                                        <textarea class="form-control" rows="10" name="observacionesGenerales" id="observacionesGenerales"><?php echo $datos2?></textarea>
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
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

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
</body>
</html>
