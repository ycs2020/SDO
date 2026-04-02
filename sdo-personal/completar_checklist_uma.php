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

    <title>Check List UMA</title>

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
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Check List UMA</h4>
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
                                <form action="../sdo-funciones/guardar-checklist-uma.php" method="POST">
                                    <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                    <input type="hidden" name="idTareaP" id="idTareaP" value="<?php echo $idTareaP?>">
                                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario?>">
                                    <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario2?>">
                                    <?php date_default_timezone_set('America/Santiago');?>
                                    <?php $now = date('H:i:s')?>
                                    <fieldset class="border p-3">
                                        <label>Controlador</label>
                                        <!--<input type="text" class="form-control" id="controlador" name="controlador">-->
                                        <select name="controlador" id="controlador" class="form-control select2" style="width: 100%" required>
                                            <option value="" disabled selected>Seleccione un equipo</option>
                                            <?php
                                            // Consultar la base de datos para obtener los equipos
                                            $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE 'ECB%' ORDER BY nombreEquipo ASC";
                                            $resServicio = mysqli_query($conexion, $sqlServicio);

                                            // Verificar si $idEquipamiento tiene un valor
                                            while ($rowServicio = $resServicio->fetch_assoc()) {
                                                $idEquipo = $rowServicio['id'];
                                                $idCategoria = $rowServicio['idCategoria'];
                                                $nombreEquipo = $rowServicio['nombreEquipo'];

                                                ?>
                                                <option value="<?php echo $nombreEquipo; ?>"><?php echo $nombreEquipo; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
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
                                                        <!--<div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                                                        </div>-->
                                                        <!--<input type="text" class="form-control" id="sistemaosub" name="sistemaosub" oninput="this.value = this.value.toUpperCase()">-->
                                                        <select name="UMA" id="UMA" class="form-control select2" style="width: 100%" required>
                                                            <option value="" disabled selected>Seleccione un equipo</option>
                                                            <?php
                                                            // Consultar la base de datos para obtener los equipos
                                                            $sqlServicio = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE 'UMA-%' ORDER BY nombreEquipo ASC";
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
                                                        <input type="hidden" id="nombreEquipoInput" name="nombreEquipoInput" class="form-control" placeholder="Ingrese el nombre del equipo">
                                                        <script>
                                                            const selectEquipo = document.getElementById('UMA');
                                                            const inputNombre = document.getElementById('nombreEquipoInput');

                                                            // Si ya hay un equipo seleccionado al cargar, llenar el input
                                                            window.addEventListener('DOMContentLoaded', () => {
                                                                if (selectEquipo.value) {
                                                                    inputNombre.value = selectEquipo.options[selectEquipo.selectedIndex].text;
                                                                }
                                                            });

                                                            // Actualizar el input cada vez que cambie la selección
                                                            selectEquipo.addEventListener('change', () => {
                                                                const selectedOption = selectEquipo.options[selectEquipo.selectedIndex];
                                                                inputNombre.value = selectedOption.text;
                                                            });
                                                        </script>
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
                                            <tbody id="tablaPreguntas">

                                            </tbody>
                                        </table>
                                        <hr>
                                        <?php
                                        $datos2 = "Sensores de presión diferencial (Estados):\nSensores de presión diferencial (Filtros):\nSensores de presión diferencial (Modulación VDF):\nSensores de temperatura:\nSensores de humedad:\n\nEntradas:\nSalidas:\n";
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
<!-- ... tu HTML arriba ... -->

<script>
    const preguntas = [
        "¿Existe comunicación entre el BMS y el controlador?",
        "¿Las terminales en el controlador se encuentran bien apretadas y libres de sulfato?",
        "¿El tablero está en buenas condiciones?",
        "¿El tablero y puerta se encuentran conectados a tierra?",
        "¿El sensor de temperatura de impulsión está en buenas condiciones?",
        "¿Existe lectura de temperatura de impulsión?",
        "¿El sensor de presión de impulsión está en buenas condiciones?",
        "¿Existe lectura de presión de impulsión?",
        "¿El sensor de humedad de impulsión está en buenas condiciones?",
        "¿Existe lectura de humedad de impulsión?",
        "¿El sensor de temperatura de retorno está en buenas condiciones?",
        "¿Existe lectura de temperatura de retorno?",
        "¿El sensor de presión de retorno está en buenas condiciones?",
        "¿Existe lectura de presión de retorno?",
        "¿El sensor de humedad de retorno está en buenas condiciones?",
        "¿Existe lectura de humedad de retorno?",
        "¿El sensor de pre-Filtro impulsión (G4) está en buenas condiciones?",
        "¿Existe lectura de pre-Filtro impulsión?",
        "¿El sensor de Filtro de bolsa está en buenas condiciones?",
        "¿Existe lectura de Filtro de bolsa impulsión?",
        "¿El sensor de Filtro HEPA u otro está en buenas condiciones?",
        "¿Existe lectura de impulsión en Filtro HEPA u otro?",
        "¿El sensor de Filtro retorno está en buenas condiciones?",
        "¿Existe lectura de Filtro retorno?",
        "¿El sensor de pre-Filtro retorno está en buenas condiciones?",
        "¿Existe lectura de pre-Filtro retorno?",
        "¿El conexionado con el controlador del humificador está en buenas condiciones?",
        "¿Existe lectura de Humificador?",
        "¿El sensor de Estado de VEX está en buenas condiciones?",
        "¿Existe lectura de Estado de funcionamiento de VEX?",
        "¿El conexionado con el VDF del VEX está en buenas condiciones?",
        "¿Existe lectura de la modulación del VDF del VEX?",
        "¿El sensor de Estado de VIN está en buenas condiciones?",
        "¿Existe lectura de Estado de funcionamiento de VIN?",
        "¿El conexionado con el VDF del VIN está en buenas condiciones?",
        "¿Existe lectura de la modulación del VDF del VIN?",
        "¿El conexionado del dámper de impulsión está en buenas condiciones?",
        "¿Existe lectura de dámper de impulsión?",
        "¿El conexionado del dámper de retorno está en buenas condiciones?",
        "¿Existe lectura de dámper de retorno?",
        "¿El conexionado de la válvula de agua caliente está en buenas condiciones?",
        "¿Existe lectura de la válvula de agua caliente?",
        "¿El conexionado de la válvula de agua fría está en buenas condiciones?",
        "¿Existe lectura de la válvula de agua fría?",
        "¿El voltaje de C.A. de la fuente primaria es el adecuado?",
        "¿El voltaje de C.A. de la fuente secundaria es el adecuado?",
        "¿La lógica de programación opera adecuadamente?",
        "¿Existe una sujeción en las mangueras de los sensores estados VIN/VEX?"
    ];

    let html = "";
    preguntas.forEach((pregunta, index) => {
        const i = index + 1;
        html += `
<tr>
  <td>${pregunta}</td>
  <td><input type="text" class="form-control" name="f${i}" id="f${i}"></td>
  <td><input type="checkbox" class="d-flex justify-content-center" name="SI${i}" id="SI${i}" value="1"></td>
  <td><input type="checkbox" class="d-flex justify-content-center" name="NA${i}" id="NA${i}" value="1"></td>
  <td><input type="checkbox" class="d-flex justify-content-center" name="NO${i}" id="NO${i}" value="1"></td>
  <td><input type="text" class="form-control" name="condicion${i}" id="condicion${i}"></td>
  <td><input type="text" class="form-control" name="parametros${i}" id="parametros${i}"></td>
  <td><input type="text" class="form-control" name="observacion${i}" id="observacion${i}"></td>
</tr>
`;
    });

    // Insertar en el tbody
    document.getElementById("tablaPreguntas").innerHTML = html;
</script>
</body>
</html>
