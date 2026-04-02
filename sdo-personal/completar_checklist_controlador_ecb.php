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

$contarchecklistcontroladores = contarchecklistcontroladores($idTG);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Check List Logica de Progamacion</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <script>
        function marcarOtroInput(){
            var primerInput = document.querySelector('.show-mostrar');
            var enlaceBotones = document.getElementById('limpiezadispositivo');
            var enlaceImagenes = document.getElementById('verficarEstadoDis');
            var enlaceparametros = document.getElementById('limpiezaContactosElec');
            var enlacegraficas = document.getElementById('verificarVoltaje');
            var enlacetendencias = document.getElementById('chequeoComunicacion');
            var ordenbloques = document.getElementById('verificacionFuncionamiento');
            var verificacionLogicaControl = document.getElementById('verificacionLogicaControl');
            var verificacionIntegracion = document.getElementById('verificacionIntegracion');
            var chequeoRutina = document.getElementById('chequeoRutina');

            if (primerInput.checked) {
                enlaceBotones.checked = true;
                enlaceImagenes.checked = true;
                enlaceparametros.checked = true;
                enlacegraficas.checked = true;
                enlacetendencias.checked = true;
                ordenbloques.checked = true;
                verificacionLogicaControl.checked = true;
                verificacionIntegracion.checked = true;
                chequeoRutina.checked = true;
            }else{
                enlaceBotones.checked = false;
                enlaceImagenes.checked = false;
                enlaceparametros.checked = false;
                enlacegraficas.checked = false;
                enlacetendencias.checked = false;
                ordenbloques.checked = false;
                verificacionLogicaControl.checked = false;
                verificacionIntegracion.checked = false;
                chequeoRutina.checked = false;
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
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List Controlador Modelo ECB</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Check List hechas: <?php echo $contarchecklistcontroladores?></b>
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
                                <form action="../sdo-funciones/guardar-checklist-controlador-ecb.php" method="POST">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <input type="hidden" name="tipoCheckList" id="tipoCheckList" value="Controlador Modelo ECB">
                                        <div class="row">
                                            <label>Controlador</label>
                                            <div class="form-row">
                                                <select name="ecbID" id="ecbID" class="form-control select2" style="width: 100%" required onchange="updateInput()">
                                                    <option value="" disabled selected>Seleccione un equipo</option>
                                                    <?php
                                                    // Consultar la base de datos para obtener los equipos
                                                    $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE '%ECB%' ORDER BY nombreEquipo ASC";
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
                                                        var selectElement = document.getElementById("ecbID");
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
                                            <br>
                                            <div class="form-row">
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">EC-BOS:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="ecbos" name="ecbos" oninput="this.value = this.value.toUpperCase()" required>

                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">MAC ADDRESS:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="macaddress"
                                                               oninput="this.value = this.value.toUpperCase()" name="macaddress" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-2">
                                                    <label style="float: left;">NETWORK:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="network"
                                                               oninput="this.value = this.value.toUpperCase()" name="network" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-mb-2">
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
                                            <div class="form-group col-mb-2">
                                                <label class="form-check-label" style="float: right;"> Marcar Todos</label>
                                                <br>
                                                <input type="checkbox" style="float: right;" class="show-mostrar" onchange="marcarOtroInput()">
                                            </div>
                                            <br>
                                            <div class="col-sm">
                                                <div class="form-check">
                                                    <table class="table table-hover table-bordered text-center">
                                                        <thead class="text-center bg-gradient-secondary">
                                                        <th>Item</th>
                                                        <th>Revisión en controlador modelo ECB</th>
                                                        <th>Estado</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Limpieza general del dispositivo y su entorno</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="limpiezadispositivo" id="limpiezadispositivo">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Verificar el estado del dispositivo</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="verficarEstadoDis" id="verficarEstadoDis">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Limpieza de contactos electrónicos</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="limpiezaContactosElec" id="limpiezaContactosElec">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Verificación de voltaje (DC/AC) de los contactos de acuerdo a ficha técnica (24V AC)</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="verificarVoltaje" id="verificarVoltaje">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Chequeo de la red de comunicación Bacnet MS/TP (0,7 - 1,2V  AC)</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="chequeoComunicacion" id="chequeoComunicacion">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>Verificación de funcionamiento (Entradas y salidas)</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="verificacionFuncionamiento" id="verificacionFuncionamiento">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>Verificación de lógica de control (Programación)</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="verificacionLogicaControl" id="verificacionLogicaControl">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>8</td>
                                                            <td>Verificación de integración en software de BMS</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="verificacionIntegracion" id="verificacionIntegracion">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td>Chequeo a la rutina de programación y alarmas</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"  value="1" name="chequeoRutina" id="chequeoRutina">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <legend>Observaciones:</legend>
                                        <textarea class="form-control" id="obs" name="obs" rows="5" style="resize: none"></textarea>
                                    </fieldset>
                                    <br>
                                    <br>
                                    <button type="submit" class="bg-gradient-primary btn btn-block" style="color: white">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
