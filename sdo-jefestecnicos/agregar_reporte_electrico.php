<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creacion del Reporte</title>

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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarMC").show();
                $("#registrarMC").hide();
                $("#listarMC2").show();
                $("#registrarMC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarMC").hide();
                $("#registrarMC").show();
                $("#listarMC2").hide();
                $("#registrarMC2").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>
    <style>
        /* select2-custom.css */
        /* Estilos para modo claro */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Color rojo para el texto en modo claro */
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo claro */
            background-color: #b1b1b3;
        }

        /* Estilos para modo oscuro */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff; /* Color rojo para el texto en modo oscuro */

        }

        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo oscuro */
            background-color: #b1b1b3;
        }

    </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Crear Reporte</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <a href="reportes_electricos.php" style="float: right" class="btn btn-danger btn-md"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align: center;">
                                <form action="../sdo-funciones/guardar_creacion_reporte.php" method="POST">
                                    <div class="form-group row">
                                        <!-- Técnico Encargado del Turno -->
                                        <div class="col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">
                                                Turno: <code>*</code>
                                            </label>
                                            <select class="form-control" name="turno" id="turno" required onchange="actualizarHoras()">
                                                <option value="" selected disabled>Seleccione</option>
                                                <option value="DIURNO">Dia (8:00 AM hasta 20:00 PM)</option>
                                                <option value="NOCHE">Noche (20:00 PM hasta 08:00 AM)</option>
                                            </select>
                                        </div>

                                        <!-- Funcionarios del Turno -->
                                        <div class="col-md-6">
                                            <label for="nomFuncionariosTurno">
                                                Funcionarios del Turno: <code>*</code>
                                            </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                </div>
                                                <select class="form-control listadoEspecialidades" required id="nomFuncionariosTurno" name="nomFuncionariosTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="" disabled>Seleccione...</option>

                                                    <?php
                                                    $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos WHERE idDepartamento = 4 ORDER BY nomDepartamento";
                                                    $resDepto = mysqli_query($conexion, $sqlDepto);

                                                    $departamentos = [];

                                                    if ($resDepto && $resDepto->num_rows > 0) {
                                                        while ($rowDepto = $resDepto->fetch_assoc()) {
                                                            // Renombrar si es necesario
                                                            if ($rowDepto['nomDepartamento'] === 'Instrumentación') {
                                                                $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                            }
                                                            $departamentos[] = $rowDepto;
                                                        }

                                                        // Ordenar alfabéticamente
                                                        usort($departamentos, function ($a, $b) {
                                                            return strcmp($a['nomDepartamento'], $b['nomDepartamento']);
                                                        });

                                                        // Mostrar usuarios por departamento
                                                        foreach ($departamentos as $departamento) {
                                                            $idDeptoBuscar = $departamento['idDepartamento'];
                                                            echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento']) . '">';

                                                            $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal WHERE departamentoPersonal = $idDeptoBuscar AND activoPersonal = 1 ORDER BY nomPersonal";
                                                            $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                            if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                    $nomEmpleado = trim($rowUsuarios['nomEmpleado']);
                                                                    echo '<option value="' . htmlspecialchars($nomEmpleado) . '">' . htmlspecialchars($nomEmpleado) . '</option>';
                                                                }
                                                            }

                                                            echo '</optgroup>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs" id="tabTodos" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="1-tab" data-toggle="tab" data-target="#tab-1" type="button" role="tab">Rondas E.E Sector Nivel 1</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="2-tab" data-toggle="tab" data-target="#tab-2" type="button" role="tab">Rondas por Sector Nivel -1</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="3-tab" data-toggle="tab" data-target="#tab-3" type="button" role="tab">Tareas Adicionales, Rondas y Recintos Clinicos</button>
                                        </li>
                                    </ul>

                                    <hr>
                                    <div class="tab-content" id="tabTodos">
                                        <div class="tab-pane fade show active p-3" id="tab-1" role="tabpanel">
                                            <div class="row">
                                                <!-- Sección A: Sala de Celdas Media Tensión 1 -->
                                                <div class="col-md-6 mb-4">
                                                    <h5><strong>a.-</strong> Sala de Celdas Media Tensión 1</h5>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaCeldasM">

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Sección B: Sala de Transformadores -->
                                                <div class="col-md-6 mb-4">
                                                    <h5><strong>b.-</strong> Sala de Transformadores</h5>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaSalaTransformadores">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <strong>c.-</strong>Temperaturas de Transformadores en Sala de Transformadores
                                            <br>
                                            <div id="contenedorTablaTemperaturas">

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <strong>d.-</strong>Sala de Transferencia

                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaSalaTransferencia">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <strong>e.-</strong>Sala de TGBT 1
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaSalaTGBT1">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <strong>f.-</strong>UPS y Temperaturas de Sala de TGBT 1
                                            <br>
                                            <div id="contenedorTablaUPS"></div>
                                            <hr>
                                            <strong>g.-</strong>Sala de Grupos Electrógenos
                                            <br>
                                            <strong style="font-size: 15px">MAN: Manual &nbsp;&nbsp;&nbsp;AUTO: Automatico&nbsp;&nbsp;&nbsp;STOP: Detenido</strong>
                                            <br>
                                            <div id="contenedorTablaGruposElectrogenos">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade p-3" id="tab-2" role="tabpanel">
                                            <strong>a.- </strong>Sala TBGT 2
                                            <br>
                                            <table class="table table-bordered" >
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>Horas</th>
                                                    <th>Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaSalaTGBT2">

                                                </tbody>
                                            </table>
                                            <hr>
                                            <strong>b.- </strong>UPS y Temperatura en Sala TBGT 2
                                            <br>
                                            <div id="contenedorTablaUPSTBGT2">
                                                <table class="table table-bordered">
                                                    <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th>UPS</th>
                                                        <th>Hora</th>
                                                        <th>Temp</th>
                                                        <th>Alarma</th>
                                                        <th>Último Evento</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td rowspan="4">DIN 2</td>
                                                        <td>09:00</td>
                                                        <td><input type="text" class="form-control" name="tempSalaTBGT2"></td>
                                                        <td><input type="text" class="form-control" name="alarmaSalaTBGT2"></td>
                                                        <td><input type="text" class="form-control" name="eventoSalaTBGT2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>12:00</td>
                                                        <td><input type="text" class="form-control" name="tempSalaTBGT2_1"></td>
                                                        <td><input type="text" class="form-control" name="alarmaSalaTBGT2_1"></td>
                                                        <td><input type="text" class="form-control" name="eventoSalaTBGT2_1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>15:00</td>
                                                        <td><input type="text" class="form-control" name="tempSalaTBGT2_2"></td>
                                                        <td><input type="text" class="form-control" name="alarmaSalaTBGT2_2"></td>
                                                        <td><input type="text" class="form-control" name="eventoSalaTBGT2_2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>18:00</td>
                                                        <td><input type="text" class="form-control" name="tempSalaTBGT2_3"></td>
                                                        <td><input type="text" class="form-control" name="alarmaSalaTBGT2_3"></td>
                                                        <td><input type="text" class="form-control" name="eventoSalaTBGT2_3"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr>
                                            <strong>c.- </strong>Salas Celdas Media Tensión 2
                                            <table class="table table-bordered" >
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>Horas</th>
                                                    <th>Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaMediaTension2">

                                                </tbody>
                                            </table>
                                            <hr>
                                            <strong>d.- </strong> Temperaturas de Transformadores en Sala de Celdas Media Tensión 2
                                            <div id="contenedorTablaTemperaturasTrafoMT2"></div>
                                        </div>
                                        <div class="tab-pane fade p-3" id="tab-3" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <strong>3.- </strong>Recintos Clínicos
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaRecintosClinicos">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <strong>4.- </strong>Rondas por Exterior: Revisar alumbrado de postes
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tablaRondasExterior">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <strong>5.- </strong><span id="tituloRondaPabellon">Rondas de Pabellones:</span>
                                            <br>
                                            <div id="contenedorTablaPabellonesClinicos"></div>
                                            <hr>
                                            <div>
                                                <strong>6.- Tareas Adicionales</strong>
                                                <br><br>
                                                <ol type="a">
                                                    <li>Hora en la cual se atiende la solicitud</li>
                                                    <li>N° de Ticket</li>
                                                    <li>Persona que solicita</li>
                                                    <li>PMA del sector y detalle de la habitación</li>
                                                    <li>Motivo del trabajo realizado</li>
                                                    <li>Avisar a Enfermera - Supervisora del sector</li>
                                                    <li>Si el trabajo se extiende, indicar el tiempo estimado</li>
                                                </ol>
                                                <p>En caso contrario, indicar: Sin tareas adicionales</p>
                                                <textarea class="form-control" style="resize: none" rows="10" name="obsTareasAdicionales"></textarea>
                                            </div>
                                            <hr>
                                            <button class="btn btn-block bg-success" type="submit">Guardar</button>

                                        </div>
                                    </div>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- tu HTML va aquí... -->

<!-- justo antes del cierre del body -->

<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoEncInstru').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEspecialidades').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });

        $('.listadoEncInstruAnterior').select2({
            placeholder: 'Técnico Encargado de Instrumentación en Turno Anterior',
            theme: 'bootstrap4',
            allowClear: true
        });

    });
</script>
<script>
    function actualizarHoras() {
        const turno = document.getElementById("turno").value;
        const tabla = document.getElementById("tablaCeldasM");
        const tabla2 = document.getElementById("tablaSalaTransformadores");
        const tabla3 = document.getElementById("tablaSalaTransferencia");
        const tabla4 = document.getElementById("tablaSalaTGBT1");
        const tabla5 = document.getElementById("tablaSalaTGBT2");
        const tabla6 = document.getElementById("tablaMediaTension2");
        const tabla7 = document.getElementById("tablaRecintosClinicos");
        const tabla8 = document.getElementById("tablaRondasExterior");
        const contenedorTransformadores = document.getElementById("contenedorTablaTemperaturas");
        const contenedorUPS = document.getElementById("contenedorTablaUPS");
        const contenedorGrupos = document.getElementById("contenedorTablaGruposElectrogenos");
        const contenedorTablaUPSTBGT2 = document.getElementById("contenedorTablaUPSTBGT2");
        const contenedorTablaTemperaturasTrafoMT2 = document.getElementById("contenedorTablaTemperaturasTrafoMT2");
        const contenedorTablaPabellonesClinicos = document.getElementById("contenedorTablaPabellonesClinicos");

        if (!contenedorTablaPabellonesClinicos || !tabla8 || !tabla7 || !contenedorTablaTemperaturasTrafoMT2 || !tabla6 || !contenedorTablaUPSTBGT2 || !tabla || !tabla2 || !contenedorTransformadores || !tabla3 || !tabla4 || !contenedorUPS || !contenedorGrupos || !tabla5) {
            console.error("No se encontró uno de los elementos requeridos.");
            return;
        }

        // Limpiar contenido previo
        tabla.innerHTML = "";
        tabla2.innerHTML = "";
        tabla3.innerHTML = "";
        tabla4.innerHTML = "";
        tabla5.innerHTML = "";
        tabla6.innerHTML = "";
        tabla7.innerHTML = "";
        tabla8.innerHTML = "";
        contenedorTransformadores.innerHTML = "";
        contenedorUPS.innerHTML = "";
        contenedorGrupos.innerHTML = "";
        contenedorTablaUPSTBGT2.innerHTML = "";
        contenedorTablaTemperaturasTrafoMT2.innerHTML = "";
        contenedorTablaPabellonesClinicos.innerHTML = "";

        // Definir horas según turno
        let horas = [];
        if (turno === "DIURNO") {
            horas = ["9:00", "12:00", "15:00", "18:00"];
        } else if (turno === "NOCHE") {
            horas = ["21:00", "00:00", "03:00", "06:00"];
        }

        const tituloRondaPabellon = document.getElementById("tituloRondaPabellon");
        if (tituloRondaPabellon) {
            tituloRondaPabellon.textContent = turno === "NOCHE"
                ? "Rondas de Pabellones: Hora 06:00"
                : "Rondas de Pabellones: No Aplica";
        }

        // Tabla Media Tensión
        horas.forEach((hora, index) => {
            const inputName = `obsMT${index + 1}`;
            const rowMT = `
            <tr>
                <td><input type="hidden" name="obsMTHora${index + 1}" value="${hora}">${hora}</td>
                <td><input type="text" name="${inputName}" class="form-control"></td>
            </tr>`;
            tabla.insertAdjacentHTML("beforeend", rowMT);
        });

        // Tabla Sala Transformadores
        horas.forEach((hora, index) => {
            const inputName2 = `obsST${index + 1}`;
            const rowST = `
            <tr>
                <td><input type="hidden" name="obsSTHora${index + 1}" value="${hora}">${hora}</td>
                <td><input type="text" name="${inputName2}" class="form-control"></td>
            </tr>`;
            tabla2.insertAdjacentHTML("beforeend", rowST);
        });

        // Tabla Sala Transferencia
        horas.forEach((hora, index) => {
            const inputName3 = `obsSalaTrans${index + 1}`;
            const rowSALATRANS = `
            <tr>
                <td><input type="hidden" name="obsSalaTransHora${index + 1}" value="${hora}">${hora}</td>
                <td><input type="text" name="${inputName3}" class="form-control"></td>
            </tr>`;
            tabla3.insertAdjacentHTML("beforeend", rowSALATRANS);
        });

        // Tabla Sala TGBT1
        horas.forEach((hora, index) => {
            const inputName4 = `obsSalaTGBT1_${index + 1}`;
            const rowTGBT = `
            <tr>
                <td><input type="hidden" name="obsSalaTGBTHORA1_${index + 1}" value="${hora}">${hora}</td>
                <td><input type="text" name="${inputName4}" class="form-control"></td>
            </tr>`;
            tabla4.insertAdjacentHTML("beforeend", rowTGBT);
        });

        // Tabla Sala TGBT2
        horas.forEach((hora, index) => {
            const inputName5 = `obsSalaTGBT2_${index + 1}`;
            const rowTGBT2 = `
            <tr>
                <td><input type="hidden" name="obsSalaTGBTHORA2_${index + 1}" value="${hora}">${hora}</td>
                <td><input type="text" name="${inputName5}" class="form-control"></td>
            </tr>`;
            tabla5.insertAdjacentHTML("beforeend", rowTGBT2);
        });

        horas.forEach((hora, index) => {
            const inputName6 = `obsSalaMT2_${index + 1}`;
            const rowMT2 = `
               <tr>
                    <td><input type="hidden" name="obsSalaMTHORA2_${index + 1}" value="${hora}">${hora}</td>
                    <td><input type="text" name="${inputName6}" class="form-control"></td>
                </tr>
               `;
            tabla6.insertAdjacentHTML("beforeend", rowMT2);
        });
        horas.forEach((hora, index) => {
            const inputName7 = `obsRecintosClinicos_${index + 1}`;
            const rowRC = `
               <tr>
                    <td><input type="hidden" name="obsRecintosClinicosHora_${index + 1}" value="${hora}">${hora}</td>
                    <td><input type="text" name="${inputName7}" class="form-control"></td>
                </tr>
               `;
            tabla7.insertAdjacentHTML("beforeend", rowRC);
        });
        horas.forEach((hora, index) => {
            const inputName8 = `obsRondasExterior_${index + 1}`;
            const rowRE = `
               <tr>
                    <td><input type="hidden" name="obsRondasExteriorHora_${index + 1}" value="${hora}">${hora}</td>
                    <td><input type="text" name="${inputName8}" class="form-control"></td>
                </tr>
               `;
            tabla8.insertAdjacentHTML("beforeend", rowRE);
        });
// Mostrar tabla solo en el turno Noche
        if (turno === "NOCHE") {
            let tablaPabellones = `
        <table class="table table-bordered">
            <thead class="bg-secondary text-white">
                <tr>
                    <th rowspan="2">Pabellón</th>
                    <th rowspan="2">Protecciones Tablero</th>
                    <th colspan="2">Alarmas</th>
                    <th rowspan="2">Estado Luces</th>
                    <th rowspan="2">Condición Puertas</th>
                    <th rowspan="2">Otras Observaciones</th>
                </tr>
                <tr>
                    <th>Finder</th>
                    <th>UPS</th>
                </tr>
            </thead>
            <tbody>
    `;

            for (let i = 1; i <= 10; i++) {
                tablaPabellones += `
            <tr>
                <td>${i}</td>
                <td><input type="text" class="form-control" name="proteccionesTablero_${i}"></td>
                <td><input type="text" class="form-control" name="alarmaFinder_${i}"></td>
                <td><input type="text" class="form-control" name="alarmaUPS_${i}"></td>
                <td><input type="text" class="form-control" name="estadoLuces_${i}"></td>
                <td><input type="text" class="form-control" name="condicionPuertas_${i}"></td>
                <td><input type="text" class="form-control" name="otrasObservaciones_${i}"></td>
            </tr>
        `;
            }

            tablaPabellones += `
            </tbody>
        </table>
    `;

            contenedorTablaPabellonesClinicos.innerHTML = tablaPabellones;
        }

// Tabla Temperaturas Trafos T6 y T7 (contenedorTablaTemperaturasTrafoMT2)
        let tablaTransformadoresMT2 = `
<table class="table table-bordered">
    <thead class="bg-secondary text-white">
        <tr>
            <th rowspan="2">TRAFO</th>
            <th rowspan="2">Linea</th>
            <th colspan="${horas.length}">Hora</th>
        </tr>
        <tr>
            ${horas.map(h => `<th><input type="hidden" name="tranformadoresHora6y7${h}" value="${h}">${h}</th>`).join('')}
        </tr>
    </thead>
    <tbody>
`;

        for (let trafo = 6; trafo <= 7; trafo++) {
            for (let linea = 1; linea <= 3; linea++) {
                tablaTransformadoresMT2 += `<tr>`;
                if (linea === 1) {
                    tablaTransformadoresMT2 += `<td rowspan="3">T${trafo}</td>`;
                }
                tablaTransformadoresMT2 += `<td>TL°${linea}</td>`;
                for (let h = 1; h <= horas.length; h++) {
                    const inputName = `temperaturaSalaTL${trafo}_${linea}_${h}`;
                    tablaTransformadoresMT2 += `<td><input type="text" class="form-control" name="${inputName}"></td>`;
                }
                tablaTransformadoresMT2 += `</tr>`;
            }
        }

        tablaTransformadoresMT2 += `</tbody></table>`;
        document.getElementById("contenedorTablaTemperaturasTrafoMT2").innerHTML = tablaTransformadoresMT2;

// Tabla DIN 2 para contenedorTablaUPSTBGT2
        let tablaDIN2 = `
            <table class="table table-bordered">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>UPS</th>
                        <th>Hora</th>
                        <th>Temp</th>
                        <th>Alarma</th>
                        <th>Último Evento</th>
                    </tr>
                </thead>
                <tbody>
`;

        horas.forEach((hora, index) => {
            const sufijo = `DIN2_${index + 1}`;
            tablaDIN2 += `
                <tr${index === 0 ? `><td rowspan="${horas.length}">DIN 2</td>` : ">"}
                    <td><input type="hidden" value="${hora}" name="DIN2Hora${hora}">${hora}</td>
                    <td><input type="text" class="form-control" name="tempSala${sufijo}"></td>
                    <td><textarea name="alarmaSala${sufijo}" class="form-control" rows="10"></textarea></td>
                    <td><textarea name="eventoSala${sufijo}" class="form-control" rows="10"></textarea></td>
                </tr>
    `;
        });

        tablaDIN2 += `
                    </tbody>
                </table>
`;

        contenedorTablaUPSTBGT2.innerHTML = tablaDIN2;

        // Tabla Temperaturas de Transformadores
        let tablaTransformadores = `
        <table class="table table-bordered">
            <thead class="bg-secondary text-white">
                <tr>
                    <th rowspan="2">Trafo</th>
                    <th rowspan="2">Línea</th>
                    <th colspan="${horas.length}">Hora</th>
                </tr>
                <tr>
                    ${horas.map(h => `<th><input type="hidden" name="tranformadoresHora${h}" value="${h}">${h}</th>`).join('')}
                </tr>
            </thead>
            <tbody>
        `;

        for (let trafo = 1; trafo <= 5; trafo++) {
            for (let linea = 1; linea <= 3; linea++) {
                tablaTransformadores += `<tr>`;
                if (linea === 1) {
                    tablaTransformadores += `<td rowspan="3">T${trafo}</td>`;
                }
                tablaTransformadores += `<td>TL°${linea}</td>`;
                for (let h = 1; h <= horas.length; h++) {
                    const inputName = `temperaturaSalaTL${trafo}_${linea}_${h}`;
                    tablaTransformadores += `<td><input type="text" class="form-control" name="${inputName}"></td>`;
                }
                tablaTransformadores += `</tr>`;
            }
        }

        tablaTransformadores += `</tbody></table>`;
        contenedorTransformadores.innerHTML = tablaTransformadores;

        // Tabla de UPS
        const upsNames = ["SAI-EST", "SAI-EST 1", "SAI-EST 2", "DIN 1", "DIN IMA"];
        let tablaUPS = `
        <table class="table table-bordered">
            <thead class="bg-secondary text-white">
                <tr>
                    <th>UPS</th>
                    <th>HORA</th>
                    <th>TEMP</th>
                    <th>ALARMA</th>
                    <th>ULTIMO EVENTO</th>
                </tr>
            </thead>
            <tbody>
        `;

        upsNames.forEach(ups => {
            horas.forEach((hora, index) => {
                const i = index + 1;
                const sufijo = ups.replace(/\s+/g, '').replace(/-/g, '') + "_" + i;
                tablaUPS += `
                <tr${index === 0 ? `><td rowspan="${horas.length}">${ups}</td>` : ">"}
                    <td><input type="hidden" name="upsHoras${hora}" value="${hora}">${hora}</td>
                    <td><input type="text" class="form-control" name="tempSala${sufijo}"></td>
                    <td><textarea class="form-control" name="alarmaSala${sufijo}" rows="10"></textarea></td>
                    <td><textarea class="form-control" name="ultimoeventoSala${sufijo}" rows="10"></textarea></td>
                </tr>`;
            });
        });

        tablaUPS += `</tbody></table>`;
        contenedorUPS.innerHTML = tablaUPS;

        // Tabla Grupos Electrógenos
        const equiposGG = ["GG1", "GG2", "GG3", "GG4"];
        let tablaGG = `
        <table class="table table-bordered">
            <thead class="bg-secondary text-white">
                <tr>
                    <th rowspan="2">Equipo</th>
                    <th colspan="${horas.length}">Hora</th>
                    <th rowspan="2">Observaciones</th>
                </tr>
                <tr>
                    ${horas.map(h => `<th><input type="hidden" value="${h}" name="salaGrupoElecHora${h}">${h}</th>`).join("")}
                </tr>
            </thead>
            <tbody>
        `;

        equiposGG.forEach(equipo => {
            tablaGG += `<tr><td>${equipo}</td>`;
            for (let i = 1; i <= horas.length; i++) {
                const selectName = `modo${equipo}_${i}`;
                tablaGG += `
                <td>
                    <select name="${selectName}" id="${selectName}" class="form-control">
                        <option value="" disabled selected>Seleccione</option>
                        <option value="MAN">MAN</option>
                        <option value="AUTO">AUTO</option>
                        <option value="STOP">STOP</option>
                    </select>
                </td>`;
            }
            tablaGG += `
                <td><input type="text" name="observaciones${equipo}" id="observaciones${equipo}" class="form-control"></td>
            </tr>`;
        });

        tablaGG += `</tbody></table>`;
        contenedorGrupos.innerHTML = tablaGG;
    }

</script>


</body>
</html>