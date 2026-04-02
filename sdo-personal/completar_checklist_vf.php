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

    <title>Check List VF</title>

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
            var limpiezaequiposbiometrico1 = document.querySelector('[name="limpiezaGeneral"]');
            var lectortarjeta1 = document.querySelector('[name="estadoDispositivo"]');
            var lectorhuella1 = document.querySelector('[name="limpiezaContactos"]');
            var lucesindicadorasbiometrico1 = document.querySelector('[name="verificacionVoltaje"]');
            var limpiezaequiposmagnetico1 = document.querySelector('[name="chequeoRed"]');
            var lucesindicadorasmagnetico1 = document.querySelector('[name="verificacionFuncionamiento"]');
            var limpiezaequiposbotonera1 = document.querySelector('[name="verificacionLogicaControl"]');
            var botonesoperativos1 = document.querySelector('[name="verificacionIntegracionSoftware"]');
            var chequeoProgramacion = document.querySelector('[name="chequeoProgramacion"]');

            if (marcartodos.checked) {
                limpiezaequiposbiometrico1.checked = true;
                lectortarjeta1.checked = true;
                lectorhuella1.checked = true;
                lucesindicadorasbiometrico1.checked = true;
                limpiezaequiposmagnetico1.checked = true;
                lucesindicadorasmagnetico1.checked = true;
                limpiezaequiposbotonera1.checked = true;
                botonesoperativos1.checked = true;
                chequeoProgramacion.checked = true;
            }else{
                limpiezaequiposbiometrico1.checked = false;
                lectortarjeta1.checked = false;
                lectorhuella1.checked = false;
                lucesindicadorasbiometrico1.checked = false;
                limpiezaequiposmagnetico1.checked = false;
                lucesindicadorasmagnetico1.checked = false;
                limpiezaequiposbotonera1.checked = false;
                botonesoperativos1.checked = false;
                chequeoProgramacion.checked = false;
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
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List VF</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas: <?php echo $contarchecklistvf = contarchecklistvf($idTG);?></b>
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
                                <form action="../sdo-funciones/guardar-checklist-vf.php" method="POST">
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
                                                $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE '%VF_%' AND nombreEquipo NOT LIKE ('%BRAVF-%') ORDER BY nombreEquipo ASC";
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
                                                <label style="float: left;">UBICACIÓN:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-12">
                                                <label style="float: left;">NETWORK:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" id="network" name="network" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-12">
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
                                        <br>
                                        <div style="display: flex; justify-content: flex-end; align-items: center;">
                                            <label for="marcarTodos" style="margin-right: 8px;">Marcar Todos</label>
                                            <input type="checkbox" name="marcarTodos" id="marcarTodos" class="marcar-todos" onchange="marcarInputs1()">
                                        </div>

                                        <table class="table table-bordered">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th style="text-align: center;">Interacciones</th>
                                                <th style="text-align: center;">Estado</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                    <td style="width: 50%">Limpieza general del dispositivo y su entorno</td>
                                                    <td><input type="checkbox" name="limpiezaGeneral" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Verificar el estado del dispositivo</td>
                                                    <td><input type="checkbox" name="estadoDispositivo" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Limpieza de los contactos electrónicos</td>
                                                    <td><input type="checkbox" name="limpiezaContactos" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Verificación de voltaje (AC) de los contactos de acuerdo con ficha técnica (220V AC)</td>
                                                    <td><input type="checkbox" name="verificacionVoltaje" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Chequeo de la red de comunicacion Bacnet MS/TP (0,7 - 1,2V AC)</td>
                                                    <td><input type="checkbox" name="chequeoRed" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Verificación de funcionamiento (Entradas y salidas)</td>
                                                    <td><input type="checkbox" name="verificacionFuncionamiento" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Verificación de lógica de control (Programación)</td>
                                                    <td><input type="checkbox" name="verificacionLogicaControl" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Verificación de integración en software de BMS</td>
                                                    <td><input type="checkbox" name="verificacionIntegracionSoftware" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td>Chequeo a la rutina de programación y alarmas</td>
                                                    <td><input type="checkbox" name="chequeoProgramacion" value="1"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <?php
                                        $datos = "Hora de Inicio:\nHora de Término:";
                                        ?>
                                        <label>Observaciones</label>
                                        <textarea class="form-control" rows="15" name="obs"><?php echo $datos?></textarea>
                                    </fieldset>
                                    <hr>
                                    <button type="submit" class="btn btn-block bg-success">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
</body>
</html>


