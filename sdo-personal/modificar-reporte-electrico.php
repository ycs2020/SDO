<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if(isset($_POST['id'])){
    $id = $_POST['id'];
    $sqlElect = "SELECT * FROM `reportes_electricos_sdo` WHERE id = $id";
    $resElect = mysqli_query($conexion,$sqlElect);
    while($rowElect = $resElect->fetch_assoc()){
        $datosReporte = array();
        $turno = $rowElect['turno'];
        $tipoTurno = $rowElect['tipoTurno'];
        $nomFuncionariosTurno = $turno;
        $fecha = $rowElect['fecha'];
        $datosReporte['turno'] = $turno;
        $datosReporte['tipoTurno'] = $tipoTurno;
        $datosReporte['nomFuncionariosTurno'] = $nomFuncionariosTurno;
    }
    if($tipoTurno === "DIURNO"){
        $horas = ["9:00","12:00","15:00","18:00"];
    }else{
        $horas = ["21:00","00:00","03:00","06:00"];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Reporte</title>

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
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Modificar Reporte</h4>
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
                                <form action="../sdo-funciones/modificar_reporte_electrico.php" method="POST">
                                    <input type="hidden" name="idReporte" id="idReporte" value="<?php echo $id?>">
                                    <input type="hidden" name="tipoTurnoOriginal" id="tipoTurnoOriginal" value="<?php echo $tipoTurno?>">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="exampleInputNomEncargadoTurno">
                                                Fecha: <code>*</code>
                                            </label>
                                            <input type="date" class="form-control" name="fecha" value="<?php echo $fecha?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">
                                                <br>
                                                Turno: <code>*</code>
                                            </label>
                                            <?php
                                                if($tipoTurno === 'DIURNO'){
                                                    ?>
                                                    <select class="form-control" name="turno" id="turno" required onchange="actualizarHoras()">
                                                        <option value=""  disabled>Seleccione</option>
                                                        <option value="DIURNO" selected>Dia (8:00 AM hasta 20:00 PM)</option>
                                                        <option value="NOCHE">Noche (20:00 PM hasta 08:00 AM)</option>
                                                    </select>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <select class="form-control" name="turno" id="turno" required onchange="actualizarHoras()">
                                                        <option value=""  disabled>Seleccione</option>
                                                        <option value="DIURNO" >Dia (8:00 AM hasta 20:00 PM)</option>
                                                        <option value="NOCHE" selected>Noche (20:00 PM hasta 08:00 AM)</option>
                                                    </select>
                                                    <?php
                                                }
                                            ?>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <br>
                                            <label for="exampleInputNomEncargadoTurno">T&eacute;cnico Encargado del Turno:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control listadoEncInstru" required id="nomFuncionariosTurno" name="nomFuncionariosTurno[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                    <?php
                                                    //                                                    $sqlUsuarios = "SELECT nomPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = $idDepartamento ORDER BY nomPersonal";

                                                    $sqlUsuarios = "
                                                        SELECT nomEmpleado FROM (
                                                            SELECT nomPersonal AS nomEmpleado 
                                                            FROM personal 
                                                            WHERE departamentoPersonal = 4 
                                                            AND activoPersonal = 1
                                                        
                                                        ) AS empleados
                                                        ORDER BY nomEmpleado
                                                    ";

                                                    $resUsuarios = mysqli_query($conexion, $sqlUsuarios);


                                                    if ($resUsuarios->num_rows > 0) {
                                                        while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                            $nomEmpleado = trim($rowUsuarios['nomEmpleado']);

                                                            $nombres = explode(" - ", $nomFuncionariosTurno);

                                                            $encontrado = false;
                                                            foreach ($nombres as $nombre) {
                                                                if (strpos($nombre, $nomEmpleado) !== false) {
                                                                    $encontrado = true;
                                                                    break;
                                                                }
                                                            }

                                                            if ($encontrado) {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>" selected><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $nomEmpleado ?>"><?php echo $nomEmpleado ?></option>
                                                                <?php
                                                            }
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
                                                        <tbody class="text-center" id="tablaCeldasM" style="text-align: center">
                                                        <?php
                                                        $sql1 = "SELECT id, hora, obs FROM observaciones_sala_mt WHERE idReporte = $id";
                                                        $res1 = mysqli_query($conexion, $sql1);
                                                        $count1 = 0;

                                                        // Guardar tanto obs como id por hora
                                                        $observaciones = [];
                                                        while ($row = mysqli_fetch_assoc($res1)) {
                                                            $hora = substr($row['hora'], 0, 5); // "09:00:00" -> "09:00"
                                                            $observaciones[$hora] = [
                                                                'obs' => $row['obs'],
                                                                'id' => $row['id']
                                                            ];
                                                        }

                                                        foreach ($horas as $hora):
                                                            $count1++;
                                                            $horaFormatted = substr($hora, 0, 5);
                                                            $obs = $observaciones[$horaFormatted]['obs'] ?? '';
                                                            $idSalaMT = $observaciones[$horaFormatted]['id'] ?? '';
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <!-- Input oculto para la hora -->
                                                                    <input type="hidden" name="obsMTHora<?= $count1 ?>" value="<?= $horaFormatted ?>">
                                                                    <?= $horaFormatted ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="obsMT<?= $count1 ?>" value="<?= htmlspecialchars($obs) ?>" class="form-control">
                                                                    <input type="hidden" name="idMT<?= $count1 ?>" value="<?= $idSalaMT ?>">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <h5><strong>b.-</strong> Sala de Transformadores</h5>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Hora</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-center" id="tablaTransfo" style="text-align: center">
                                                        <?php
                                                        $sql2 = "SELECT id, hora, obs FROM observaciones_sala_transfo WHERE idReporte = $id";
                                                        $res2 = mysqli_query($conexion, $sql2);
                                                        $count2 = 0;

                                                        // Indexar por hora completa
                                                        $observaciones2 = [];
                                                        while ($row2 = mysqli_fetch_assoc($res2)) {
                                                            $horaCompleta = trim($row2['hora']); // e.g. "09:00:00"
                                                            $observaciones2[$horaCompleta] = [
                                                                'obs' => $row2['obs'],
                                                                'id'  => $row2['id']
                                                            ];
                                                        }

                                                        foreach ($horas as $hora2):
                                                            $count2++;
                                                            $horaCompleta = trim($hora2);
                                                            $horaFormatted2 = substr($horaCompleta, 0, 5); // Para mostrar
                                                            $obs2 = $observaciones2[$horaCompleta]['obs'] ?? '';
                                                            $idSalaST = $observaciones2[$horaCompleta]['id'] ?? '';
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <!-- Hora visible y oculta -->
                                                                    <input type="hidden" name="obsSTHora<?= $count2 ?>" value="<?= $horaCompleta ?>">
                                                                    <?= $horaFormatted2 ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="obsST<?= $count2 ?>" value="<?= htmlspecialchars($obs2) ?>" class="form-control">
                                                                    <input type="hidden" name="idST<?= $count2 ?>" value="<?= $idSalaST ?>">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            <hr>
                                            <strong>c.-</strong>Temperaturas de Transformadores en Sala de Transformadores
                                            <br>
                                            <div id="contenedorTablaTemperaturas">
                                                <?php
                                                // Obtener los datos de la BD
                                                $sql = "SELECT * FROM temperaturas_transformadores WHERE idReporte = $id ORDER BY trafo, linea, hora";
                                                $res = mysqli_query($conexion, $sql);

                                                // Organizar los datos en un array asociativo [trafo][linea][hora] = [temp, id]
                                                $datos = [];

                                                while ($row = mysqli_fetch_assoc($res)) {
                                                    $trafo = $row['trafo'];
                                                    $linea = $row['linea'];
                                                    $hora = $row['hora'];

                                                    $datos[$trafo][$linea][$hora] = [
                                                        'temp' => $row['temperatura'],
                                                        'id' => $row['id']
                                                    ];
                                                }
                                                ?>
                                                <table class="table table-bordered text-center" style="width: 100%">
                                                    <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th rowspan="2">TRAFO</th>
                                                        <th rowspan="2">LINEA</th>
                                                        <th colspan="<?= count($horas) ?>">Hora</th>
                                                    </tr>
                                                    <tr id="encabezadoHorasTransformador">
                                                        <?php foreach ($horas as $hora): ?>
                                                            <?php
                                                            $horaSoloHora = intval(explode(':', $hora)[0]); // Ej: "21:00" -> 21
                                                            ?>
                                                            <th class="th-hora-transformador" style="border: 1px solid black;">
                                                                <?= $hora ?> <!-- Esto se muestra al usuario -->
                                                            </th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="text-align: center">
                                                    <?php
                                                    // Contador para los campos
                                                    $contador = 1;
                                                    foreach ($datos as $trafo => $lineas): ?>
                                                        <?php if ($trafo > 5) continue; ?>
                                                        <?php foreach ($lineas as $linea => $temps): ?>
                                                            <tr>
                                                                <?php if ((int)$linea == 1): ?>
                                                                    <td style="border: 1px solid black;" rowspan="<?= count($lineas) ?>">T<?= $trafo ?></td>
                                                                <?php endif; ?>
                                                                <td style="border: 1px solid black;">TL°<?= $linea ?></td>
                                                                <?php foreach ($horas as $hora): ?>
                                                                    <?php
                                                                    // Obtener la temperatura y el id correspondiente
                                                                    $horaFormateada = str_pad($hora, 5, "0", STR_PAD_LEFT);
                                                                    $tempHora = '';
                                                                    $idHora = '';

                                                                    foreach ($temps as $horaBD => $data) {
                                                                        if (substr($horaBD, 0, 5) === $horaFormateada) {
                                                                            $tempHora = $data['temp'];
                                                                            $idHora = $data['id'];
                                                                            break;
                                                                        }
                                                                    }

                                                                    // El contador se incrementa para cada celda
                                                                    $suffix = "{$trafo}_{$linea}_{$contador}";
                                                                    $contador++; // Incrementar el contador para la siguiente celda
                                                                    ?>
                                                                    <td style="border: 1px solid black;">
                                                                        <!-- Aquí usas el contador como parte del name y id -->
                                                                        <input type="text" class="form-control"
                                                                                name="temperaturaSalaTL<?= $suffix ?>"
                                                                                value="<?= $tempHora !== '-' ? htmlspecialchars($tempHora) : '' ?>">
                                                                        <input type="hidden" name="idTL<?= $suffix ?>" value="<?= $idHora ?>">
                                                                        <input type="hidden" name="horaTL<?= $suffix ?>" value="<?= $hora ?>">

                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
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
                                                        <tbody class="text-center" id="tablaSalaTransferencia" style="text-align: center">
                                                        <?php
                                                        $sql3 = "SELECT id, hora, obs FROM observaciones_sala_transferencia WHERE idReporte = $id";
                                                        $res3 = mysqli_query($conexion, $sql3);
                                                        $count3 = 0;

                                                        // indexar por hora completa
                                                        $observaciones3 = [];
                                                        while ($row3 = mysqli_fetch_assoc($res3)) {
                                                            $horaCompleta = trim($row3['hora']); // ej: "09:00:00"
                                                            $observaciones3[$horaCompleta] = [
                                                                'obs' => $row3['obs'],
                                                                'id'  => $row3['id']
                                                            ];
                                                        }

                                                        foreach ($horas as $hora3):
                                                            $count3++;
                                                            $horaCompleta = trim($hora3);
                                                            $horaFormatted3 = substr($horaCompleta, 0, 5); // Para mostrar
                                                            $obs3 = $observaciones3[$horaCompleta]['obs'] ?? '';
                                                            $idSalaTrans = $observaciones3[$horaCompleta]['id'] ?? '';
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="obsSalaTransHora<?= $count3 ?>" value="<?= $horaCompleta ?>">
                                                                    <?= $horaFormatted3 ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="obsSalaTrans<?= $count3 ?>" value="<?= htmlspecialchars($obs3) ?>" class="form-control">
                                                                    <input type="hidden" name="idSalaTrans<?= $count3 ?>" value="<?= $idSalaTrans ?>">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
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
                                                        <?php
                                                        $sql4 = "SELECT id, hora, obs FROM observaciones_sala_tgbt_1 WHERE idReporte = $id";
                                                        $res4 = mysqli_query($conexion, $sql4);
                                                        $count4 = 0;

                                                        // Indexar por hora completa
                                                        $observaciones4 = [];
                                                        while ($row4 = mysqli_fetch_assoc($res4)) {
                                                            $horaCompleta4 = trim($row4['hora']);
                                                            $observaciones4[$horaCompleta4] = [
                                                                'obs' => $row4['obs'],
                                                                'id'  => $row4['id']
                                                            ];
                                                        }

                                                        foreach ($horas as $hora4):
                                                            $count4++;
                                                            $horaCompleta4 = trim($hora4);
                                                            $horaFormatted4 = substr($horaCompleta4, 0, 5);
                                                            $obs4 = $observaciones4[$horaCompleta4]['obs'] ?? '';
                                                            $idSalaTGBT1 = $observaciones4[$horaCompleta4]['id'] ?? '';
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="obsSalaTGBTHORA1_<?= $count4 ?>" value="<?= $horaCompleta4 ?>">
                                                                    <?= $horaFormatted4 ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="obsSalaTGBT1_<?= $count4 ?>" value="<?= htmlspecialchars($obs4) ?>" class="form-control">
                                                                    <input type="hidden" name="idSalaTGBT1_<?= $count4 ?>" value="<?= $idSalaTGBT1 ?>">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <strong>f.-</strong>UPS y Temperatura en Sala TGBT 1:
                                            <br>
                                            <div id="contenedorTablaUPS">
                                                <table class="table table-bordered text-center">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="border: 1px solid black;">UPS</th>
                                                        <th style="border: 1px solid black;">HORA</th>
                                                        <th style="border: 1px solid black;">TEMP</th>
                                                        <th style="border: 1px solid black;">ALARMA</th>
                                                        <th style="border: 1px solid black;">ÚLTIMO EVENTO</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="text-align: center">
                                                    <?php
                                                    $upsList = ['SAI-EST', 'SAI-EST 1', 'SAI-EST 2', 'DIN 1', 'DIN IMA'];

                                                    // Determinar las horas del turno
                                                    if ($tipoTurno === "DIURNO") {
                                                        $horasTurno = ["09:00", "12:00", "15:00", "18:00"];
                                                    } else if($tipoTurno === "NOCHE"){
                                                        $horasTurno = ["21:00", "00:00", "03:00", "06:00"];
                                                    }

                                                    // Consulta a la base de datos
                                                    $sqlUPS = "SELECT * FROM ups_registro WHERE ups IN ('" . implode("','", $upsList) . "') AND idReporte = $id";
                                                    //echo $sqlUPS;  // Para depuración
                                                    $resUPS = mysqli_query($conexion, $sqlUPS);

                                                    // Estructura de datos organizada por UPS y hora
                                                    $datosUPS = [];
                                                    while ($row = mysqli_fetch_assoc($resUPS)) {
                                                        $ups = $row['ups'];
                                                        $horaOriginal = trim($row['hora']);
                                                        $horaParts = explode(':', $horaOriginal);
                                                        $horaNormalizada = str_pad((int)$horaParts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($horaParts[1], 2, '0', STR_PAD_LEFT);
                                                        $datosUPS[$ups][$horaNormalizada] = $row;
                                                    }

                                                    ?>
                                                    <script>
                                                        const datosUPS = <?= json_encode($datosUPS) ?>;
                                                        const upsList = <?= json_encode($upsList) ?>;
                                                    </script>
                                                    <?php

                                                    $contadorGlobal = 0;

                                                    foreach ($upsList as $upsName):
                                                        if (!isset($datosUPS[$upsName])) continue;

                                                        $primeraFila = true;
                                                        foreach ($horasTurno as $horaTurno):
                                                            // Aseguramos que haya un registro para la hora, si no lo hay se asigna null
                                                            $rowData = $datosUPS[$upsName][$horaTurno] ?? null;

                                                            $temp = ($rowData && $rowData['temperatura'] !== '-') ? $rowData['temperatura'] . '°' : '-';
                                                            $alarma = $rowData['alarma'] ?? '-';
                                                            $evento = $rowData['ultimo_evento'] ?? '-';
                                                            $idRegistro = $rowData['id'] ?? '';
                                                            ?>
                                                            <tr>
                                                                <?php if ($primeraFila): ?>
                                                                    <td style="border: 1px solid black;" rowspan="<?= count($horasTurno) ?>"><?= $upsName ?></td>
                                                                    <?php $primeraFila = false; ?>
                                                                <?php endif; ?>
                                                                <td style="border: 1px solid black;"><?= $horaTurno ?></td>

                                                                <!-- TEMP -->
                                                                <td style="border: 1px solid black;">
                                                                    <input type="hidden" name="idUps1_<?= $contadorGlobal ?>" value="<?= $idRegistro ?>" />
                                                                    <input type="hidden" name="upsHora_<?= $contadorGlobal ?>" value="<?= $horaTurno ?>" />
                                                                    <input type="text" class="form-control" name="temp_<?= $contadorGlobal ?>" value="<?= ($rowData && $rowData['temperatura'] !== '-') ? $rowData['temperatura'] : '' ?>" >
                                                                </td>

                                                                <!-- ALARMA -->
                                                                <td style="border: 1px solid black;">
                                                                    <textarea class="form-control" name="alarmaSala_<?= $contadorGlobal ?>"><?= $alarma ?></textarea>
                                                                </td>

                                                                <!-- ÚLTIMO EVENTO -->
                                                                <td style="border: 1px solid black;">
                                                                    <textarea class="form-control" name="eventoSala_<?= $contadorGlobal ?>"><?= $evento ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $contadorGlobal++;
                                                        endforeach;
                                                    endforeach;
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <strong>g.-</strong>Sala de Grupos Electrógenos
                                            <br>
                                            <strong style="font-size: 15px">MAN: Manual &nbsp;&nbsp;&nbsp;AUTO: Automatico&nbsp;&nbsp;&nbsp;STOP: Detenido</strong>
                                            <br>
                                            <hr>

                                            <div id="contenedorTablaGruposElectrogenos">
                                                <?php
                                                // Determinar las horas del turno
                                                if ($tipoTurno === "DIURNO") {
                                                    $horasTurnoGG = ["09:00", "12:00", "15:00", "18:00"];
                                                } else if($tipoTurno === "NOCHE"){
                                                    $horasTurnoGG = ["21:00", "00:00", "03:00", "06:00"];
                                                }
                                                ?>
                                                <table class="table table-bordered text-center" style="width: 100%; border: 1px solid black; border-collapse: collapse;">
                                                    <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th style="border: 1px solid black;" rowspan="2">Equipo</th>
                                                        <th style="border: 1px solid black;" colspan="<?= count($horasTurnoGG) ?>">Hora</th>
                                                        <th style="border: 1px solid black;" rowspan="2">Observaciones</th>
                                                    </tr>
                                                    <tr id="encabezadoHorasGG">
                                                        <?php
                                                        $contador = 0;
                                                        foreach ($horasTurnoGG as $hora):
                                                            ?>
                                                            <th style="border: 1px solid black;" class="th-hora-gg" data-index="<?= $contador ?>">
                                                                <?= substr($hora, 0, 5); ?>
                                                                <input type="hidden" name="horaGG[<?= $contador ?>]" value="<?= $hora ?>" class="form-control" />
                                                            </th>
                                                            <?php
                                                            $contador++;
                                                        endforeach;
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="text-align: center">
                                                    <?php
                                                    $grupos = ['GG1', 'GG2', 'GG3', 'GG4'];
                                                    $sqlGG = "SELECT * FROM grupos_electrogenos WHERE equipo IN ('" . implode("','", $grupos) . "') AND idReporte = $id";
                                                    $resGG = mysqli_query($conexion, $sqlGG);

                                                    $datosGG = [];
                                                    $observacionesGG = [];

                                                    while ($row = mysqli_fetch_assoc($resGG)) {
                                                        $equipo = $row['equipo'];
                                                        $horaOriginal = trim($row['hora']);
                                                        $horaPartes = explode(':', $horaOriginal);

// Normalizamos la hora y minutos a 2 dígitos
                                                        $horaNormalizada = str_pad($horaPartes[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($horaPartes[1], 2, '0', STR_PAD_LEFT);

                                                       // echo $horaNormalizada . '<br>'; // Para depuración
                                                        $datosGG[$equipo][$horaNormalizada] = $row;
                                                        $observacionesGG[$equipo] = $row['observaciones'] ?? '';

                                                    }

                                                    foreach ($grupos as $equipo):
                                                        ?>
                                                        <tr>
                                                            <!-- Columna de Equipo con hora -->
                                                            <td style="border: 1px solid black;">
                                                                <?= $equipo ?>
                                                                <!-- Campo para ingresar la hora del grupo electrógeno -->
                                                            </td>
                                                            <?php foreach ($horasTurnoGG as $index => $horaStr):
                                                                $modo = isset($datosGG[$equipo][$horaStr]) && $datosGG[$equipo][$horaStr]['modo'] !== '-'
                                                                    ? $datosGG[$equipo][$horaStr]['modo']
                                                                    : '';
                                                                $idGrupo = $datosGG[$equipo][$horaStr]['id'] ?? '';
                                                                ?>
                                                                <td style="border: 1px solid black;">
                                                                    <select name="modo[<?= $equipo ?>][<?= $index ?>]" id="modo_<?= $equipo ?>_<?= $index ?>" class="form-control" style="text-align: center;">
                                                                        <option value="-" <?= ($modo === '' || $modo === '-') ? 'selected' : '' ?>>Seleccione</option>
                                                                        <option value="MAN" <?= $modo === 'MAN' ? 'selected' : '' ?>>MAN</option>
                                                                        <option value="AUTO" <?= $modo === 'AUTO' ? 'selected' : '' ?>>AUTO</option>
                                                                        <option value="STOP" <?= $modo === 'STOP' ? 'selected' : '' ?>>STOP</option>
                                                                    </select>
                                                                    <input type="hidden" name="id[<?= $equipo ?>][<?= $index ?>]" value="<?= $idGrupo ?>" />
                                                                </td>
                                                            <?php endforeach; ?>

                                                            <td style="border: 1px solid black;">
                                                                <textarea
                                                                        name="observaciones[<?= $equipo ?>]"
                                                                        style="width: 100%;" class="form-control"
                                                                ><?= htmlspecialchars($observacionesGG[$equipo] ?? '-') ?></textarea>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade p-3" id="tab-2" role="tabpanel">
                                            <strong>a.- </strong>Sala TBGT 2
                                            <br>
                                            <table class="table table-bordered">
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>Hora</th>
                                                    <th>Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaSalaTGBT2">
                                                <?php
                                                $sql5 = "SELECT id, hora, obs FROM observaciones_sala_tgbt_2 WHERE idReporte = $id";
                                                $res5 = mysqli_query($conexion, $sql5);
                                                $count5 = 0;

                                                // Indexar observaciones por hora completa
                                                $observaciones5 = [];
                                                while ($row5 = mysqli_fetch_assoc($res5)) {
                                                    $horaCompleta5 = trim($row5['hora']);
                                                    $observaciones5[$horaCompleta5] = [
                                                        'obs' => $row5['obs'],
                                                        'id'  => $row5['id']
                                                    ];
                                                }

                                                foreach ($horas as $hora5):
                                                    $count5++;
                                                    $horaCompleta5 = trim($hora5);
                                                    $horaFormatted5 = substr($horaCompleta5, 0, 5);
                                                    $obs5 = $observaciones5[$horaCompleta5]['obs'] ?? '';
                                                    $idSalaTGBT2 = $observaciones5[$horaCompleta5]['id'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="obsSalaTGBTHORA2_<?= $count5 ?>" value="<?= $horaCompleta5 ?>">
                                                            <?= $horaFormatted5 ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="obsSalaTGBT2_<?= $count5 ?>" value="<?= htmlspecialchars($obs5) ?>" class="form-control">
                                                            <input type="hidden" name="idSalaTGBT2_<?= $count5 ?>" value="<?= $idSalaTGBT2 ?>">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <hr>
                                            <div id="contenedorTablaUPSDIN2">
                                                <h6>b. UPS y Temperatura en Sala TGBT 2:</h6>
                                                <table class="table table-bordered text-center" style="width: 100%;">
                                                    <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th style="border: 1px solid black;">UPS</th>
                                                        <th style="border: 1px solid black;">HORA</th>
                                                        <th style="border: 1px solid black;">TEMP</th>
                                                        <th style="border: 1px solid black;">ALARMA</th>
                                                        <th style="border: 1px solid black;">ÚLTIMO EVENTO</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="text-align: center">
                                                    <?php
                                                    $upsList2 = ['DIN 2'];

                                                    if ($tipoTurno === "DIURNO") {
                                                        $horasTurno = ["09:00", "12:00", "15:00", "18:00"];
                                                    } else {
                                                        $horasTurno = ["21:00", "00:00", "03:00", "06:00"];
                                                    }

                                                    $sqlUPS = "SELECT * FROM ups_registro WHERE ups IN ('" . implode("','", $upsList2) . "') AND idReporte = $id";
                                                   // echo $sqlUPS;
                                                    $resUPS = mysqli_query($conexion, $sqlUPS);

                                                    $datosUPS2 = [];
                                                    while ($row = mysqli_fetch_assoc($resUPS)) {
                                                        $ups = $row['ups'];
                                                        $horaOriginal = trim($row['hora']);
                                                        $horaParts = explode(':', $horaOriginal);
                                                        $horaNormalizada = str_pad((int)$horaParts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($horaParts[1], 2, '0', STR_PAD_LEFT);
                                                        $datosUPS2[$ups][$horaNormalizada] = $row;
                                                    }
                                                    ?>
                                                    <script>
                                                        const datosUPSDIN2 = <?= json_encode($datosUPS2) ?>;
                                                        const upsListDIN2 = <?= json_encode($upsList2) ?>;
                                                    </script>
                                                    <?php

                                                    $contadorGlobalDIN2 = 0;

                                                    foreach ($upsList2 as $upsName2):
                                                        if (!isset($datosUPS2[$upsName2])) continue;

                                                        $upsKey = str_replace(' ', '', $upsName2);
                                                        $primeraFila = true;
                                                        foreach ($horasTurno as $index2 => $horaTurno):
                                                            $rowData = $datosUPS2[$upsName2][$horaTurno] ?? null;

                                                            $temp = ($rowData && $rowData['temperatura'] !== '-') ? $rowData['temperatura'] . '°' : '-';
                                                            $alarma = $rowData['alarma'] ?? '-';
                                                            $evento = $rowData['ultimo_evento'] ?? '-';
                                                            $idUps = $rowData['id'] ?? '';

                                                            $inputSuffix = "{$upsKey}_{$index2}";
                                                            ?>
                                                            <tr>
                                                                <?php if ($primeraFila): ?>
                                                                    <td style="border: 1px solid black;" rowspan="<?= count($horasTurno) ?>"><?= $upsName2 ?></td>
                                                                    <?php $primeraFila = false; ?>
                                                                <?php endif; ?>
                                                                <td style="border: 1px solid black;">
                                                                    <input type="hidden" name="upsHoraDIN2_<?= $contadorGlobalDIN2 ?>" value="<?= $horaTurno ?>">
                                                                    <?= $horaTurno ?>
                                                                </td>
                                                                <td style="border: 1px solid black;">
                                                                    <input type="text" class="form-control" name="tempDIN2_<?= $contadorGlobalDIN2 ?>" value="<?= $temp ?>">
                                                                    <input type="hidden" name="idUPSDIN2_<?= $contadorGlobalDIN2 ?>" value="<?= $idUps ?>">
                                                                </td>
                                                                <td style="border: 1px solid black;">
                                                                    <textarea class="form-control" name="alarmaDIN2_<?= $contadorGlobalDIN2 ?>"><?= $alarma ?></textarea>
                                                                </td>
                                                                <td style="border: 1px solid black;">
                                                                    <textarea class="form-control" name="eventoDIN2_<?= $contadorGlobalDIN2 ?>"><?= $evento ?></textarea>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $contadorGlobalDIN2++;
                                                        endforeach;
                                                    endforeach;
                                                    ?>
                                                    </tbody>
                                                </table>
                                                <hr>
                                            </div>
                                            <h6>c. Sala Celdas Media Tensión 2:</h6>
                                            <br>
                                            <table class="table table-bordered text-center" style="width: 100%;">
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th style="border: 1px solid black;">Hora</th>
                                                    <th style="border: 1px solid black;">Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaMT2">
                                                <?php
                                                $sql6 = "SELECT id, hora, obs FROM observaciones_sala_mt_2 WHERE idReporte = $id";
                                                $res6 = mysqli_query($conexion, $sql6);
                                                $count6 = 0;

                                                $observaciones6 = [];
                                                while ($row6 = mysqli_fetch_assoc($res6)) {
                                                    $horaCompleta6 = trim($row6['hora']);
                                                    $observaciones6[$horaCompleta6] = [
                                                        'obs' => $row6['obs'],
                                                        'id'  => $row6['id']
                                                    ];
                                                }

                                                foreach ($horas as $hora6):
                                                    $count6++;
                                                    $horaCompleta6 = trim($hora6);
                                                    $horaFormatted6 = substr($horaCompleta6, 0, 5);
                                                    $obs6 = $observaciones6[$horaCompleta6]['obs'] ?? '';
                                                    $idSalaMT2 = $observaciones6[$horaCompleta6]['id'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="obsSalaMTHORA2_<?= $count6 ?>" value="<?= $horaCompleta6 ?>">
                                                            <?= $horaFormatted6 ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="obsSalaMT2_<?= $count6 ?>" value="<?= htmlspecialchars($obs6) ?>" class="form-control">
                                                            <input type="hidden" name="idSalaMT2_<?= $count6 ?>" value="<?= $idSalaMT2 ?>">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <hr>
                                            <h6>d. Temperaturas de Transformadores en Sala de Celdas Media Tensión 2</h6>
                                            <div id="contenedorTablaTemperaturast6yt7">
                                                <?php
                                                $sql = "SELECT * FROM temperaturas_transformadores WHERE idReporte = $id ORDER BY trafo, linea, hora";
                                                $res = mysqli_query($conexion, $sql);

                                                $datos = [];

                                                while ($row = mysqli_fetch_assoc($res)) {
                                                    $trafo = $row['trafo'];
                                                    $linea = $row['linea'];
                                                    $hora = $row['hora'];

                                                    $datos[$trafo][$linea][$hora] = [
                                                        'temp' => $row['temperatura'],
                                                        'id' => $row['id']
                                                    ];
                                                }
                                                ?>
                                                <table class="table table-bordered text-center" style="width: 100%">
                                                    <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th rowspan="2">TRAFO</th>
                                                        <th rowspan="2">LINEA</th>
                                                        <th colspan="<?= count($horas) ?>">Hora</th>
                                                    </tr>
                                                    <tr id="encabezadoHorasTransformador6y7">
                                                        <?php foreach ($horas as $hora): ?>
                                                            <th class="th-hora-transformador-6y7" style="border: 1px solid black;">
                                                                <?= $hora ?>
                                                            </th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="text-align: center">
                                                    <?php
                                                    $contador = 1;
                                                    foreach ($datos as $trafo => $lineas): ?>
                                                        <?php if ($trafo != 6 && $trafo != 7) continue; ?>
                                                        <?php foreach ($lineas as $linea => $temps): ?>
                                                            <tr>
                                                                <?php if ((int)$linea == 1): ?>
                                                                    <td style="border: 1px solid black;" rowspan="<?= count($lineas) ?>">T<?= $trafo ?></td>
                                                                <?php endif; ?>
                                                                <td style="border: 1px solid black;">TL°<?= $linea ?></td>
                                                                <?php foreach ($horas as $hora): ?>
                                                                    <?php
                                                                    $horaFormateada = str_pad($hora, 5, "0", STR_PAD_LEFT);
                                                                    $tempHora = '';
                                                                    $idHora = '';

                                                                    foreach ($temps as $horaBD => $data) {
                                                                        if (substr($horaBD, 0, 5) === $horaFormateada) {
                                                                            $tempHora = $data['temp'];
                                                                            $idHora = $data['id'];
                                                                            break;
                                                                        }
                                                                    }

                                                                    $suffix = "{$trafo}_{$linea}_{$contador}";
                                                                    $contador++;
                                                                    ?>
                                                                    <td style="border: 1px solid black;">
                                                                        <input type="text" class="form-control"
                                                                                name="temperaturaSalaTL6y7<?= $suffix ?>"
                                                                                value="<?= $tempHora !== '-' ? htmlspecialchars($tempHora) : '' ?>">
                                                                        <input type="hidden" name="idTL6y7<?= $suffix ?>" value="<?= $idHora ?>">
                                                                        <input type="hidden" name="horaTL6y7<?= $suffix ?>" value="<?= $hora ?>">
                                                                    </td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade p-3" id="tab-3" role="tabpanel">
                                            <h5><strong>3.</strong> Recintos Clínicos:</h5>
                                            <table class="table table-bordered text-center" style="width: 100%;">
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th style="border: 1px solid black;">Hora</th>
                                                    <th style="border: 1px solid black;">Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaRecintosClinicos">
                                                <?php
                                                $sql7 = "SELECT id, hora, obs FROM observaciones_recintos_clinicos WHERE idReporte = $id";
                                                $res7 = mysqli_query($conexion, $sql7);
                                                $count7 = 0;

                                                $observaciones7 = [];
                                                while ($row7 = mysqli_fetch_assoc($res7)) {
                                                    $horaCompleta7 = trim($row7['hora']);
                                                    $observaciones7[$horaCompleta7] = [
                                                        'obs' => $row7['obs'],
                                                        'id'  => $row7['id']
                                                    ];
                                                }

                                                foreach ($horas as $hora7):
                                                    $count7++;
                                                    $horaCompleta7 = trim($hora7);
                                                    $horaFormatted7 = substr($horaCompleta7, 0, 5);
                                                    $obs7 = $observaciones7[$horaCompleta7]['obs'] ?? '';
                                                    $idRecintosClinicos = $observaciones7[$horaCompleta7]['id'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="obsRecintosClinicosHora_<?= $count7 ?>" value="<?= $horaCompleta7 ?>">
                                                            <?= $horaFormatted7 ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="obsRecintosClinicos_<?= $count7 ?>" value="<?= htmlspecialchars($obs7) ?>" class="form-control">
                                                            <input type="hidden" name="idRecintosClinicos_<?= $count7 ?>" value="<?= $idRecintosClinicos ?>">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                            <hr>
                                            <h5><strong>4.</strong> Rondas por Exterior: Revisar alumbrado exterior de postes</h5>
                                            <table class="table table-bordered text-center" style="width: 100%;">
                                                <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th style="border: 1px solid black;">Hora</th>
                                                    <th style="border: 1px solid black;">Observación</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tablaRondasExterior">
                                                <?php
                                                $sql8 = "SELECT id, hora, obs FROM observaciones_rondas_exterior WHERE idReporte = $id";
                                                $res8 = mysqli_query($conexion, $sql8);
                                                $count8 = 0;

                                                $observaciones8 = [];
                                                while ($row8 = mysqli_fetch_assoc($res8)) {
                                                    $horaCompleta8 = trim($row8['hora']);
                                                    $observaciones8[$horaCompleta8] = [
                                                        'obs' => $row8['obs'],
                                                        'id'  => $row8['id']
                                                    ];
                                                }

                                                foreach ($horas as $hora8):
                                                    $count8++;
                                                    $horaCompleta8 = trim($hora8);
                                                    $horaFormatted8 = substr($horaCompleta8, 0, 5);
                                                    $obs8 = $observaciones8[$horaCompleta8]['obs'] ?? '';
                                                    $idRondaExt = $observaciones8[$horaCompleta8]['id'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="obsRondasExteriorHora_<?= $count8 ?>" value="<?= $horaCompleta8 ?>">
                                                            <?= $horaFormatted8 ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="obsRondasExterior_<?= $count8 ?>" value="<?= htmlspecialchars($obs8) ?>" class="form-control">
                                                            <input type="hidden" name="idRondasExterior_<?= $count8 ?>" value="<?= $idRondaExt ?>">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <hr>
                                            <?php if ($tipoTurno === 'NOCHE'): ?>
                                                <div id="tablaRondasPabellon">
                                                    <h5><strong></strong> Rondas por Pabellones: Hora 06:00</h5>

                                                    <table class="table table-bordered text-center" style="width: 100%;">
                                                        <thead CLASS="bg-secondary text-white">
                                                        <tr>
                                                            <th style="border: 1px solid black;" rowspan="2">Pabellón</th>
                                                            <th style="border: 1px solid black;" rowspan="2">Protecciones Tablero</th>
                                                            <th style="border: 1px solid black;" colspan="2">Alarmas</th>
                                                            <th style="border: 1px solid black;" rowspan="2">Estado Luces</th>
                                                            <th style="border: 1px solid black;" rowspan="2">Condición Puertas</th>
                                                            <th style="border: 1px solid black;" rowspan="2">Otras Observaciones</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="border: 1px solid black;">Finder</th>
                                                            <th style="border: 1px solid black;">UPS</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="text-align: center">
                                                        <?php
                                                        $sqlRondasPabellones = "SELECT * FROM `pabellones_clinicos` WHERE idReporte = $id";
                                                        $resRondasPabellones = mysqli_query($conexion,$sqlRondasPabellones);
                                                        while($rowRondasPabellones = $resRondasPabellones->fetch_assoc()){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $rowRondasPabellones['pabellon']?></td>
                                                                <td><textarea class="form-control" name="protecciones_tablero<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['protecciones_tablero'] ?></textarea></td>
                                                                <td><textarea class="form-control" name="alarma_finder<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['alarma_finder'] ?></textarea></td>
                                                                <td><textarea class="form-control" name="alarma_ups<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['alarma_ups'] ?></textarea></td>
                                                                <td><textarea class="form-control" name="estado_luces<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['estado_luces'] ?></textarea></td>
                                                                <td><textarea class="form-control" name="condicion_puertas<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['condicion_puertas'] ?></textarea></td>
                                                                <td><textarea class="form-control" name="otras_observaciones<?php echo $rowRondasPabellones['pabellon'] ?>"><?php echo $rowRondasPabellones['otras_observaciones'] ?></textarea></td>

                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <!-- Div oculto para JS si es diurno, se mostrará solo cuando el usuario cambie a NOCHE -->
                                                <div id="tablaRondasPabellon" style="display: none;"></div>
                                            <?php endif; ?>
                                            <hr>
                                            <h5><strong><?php ?>.</strong> Tareas Adicionales:</h5>
                                            <?php
                                            $sqlTareasAdicionales = "SELECT * FROM tareas_adicionales WHERE idReporte = $id";
                                            $resTareasAdicionales = mysqli_query($conexion,$sqlTareasAdicionales);
                                            while($rowTareasAdicionales = $resTareasAdicionales->fetch_assoc()){
                                                ?>
                                                <textarea class="form-control" name="obsTareasAdicionales" style="resize: none" rows="10"><?php echo $rowTareasAdicionales['obs'];?></textarea>
                                                <?php
                                            }
                                            ?>
                                            <br>
                                            <button class="btn btn-block bg-success" type="submit">Modificar</button>
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
<script>
    $(document).ready(function () {
        // Inicializa Select2
        const select2Options = {
            theme: 'bootstrap4',
            allowClear: true,
            placeholder: function() {
                $(this).data('placeholder');
            }
        };

        $('.listadoEncInstru').select2(select2Options);
        $('.listadoEspecialidades').select2(select2Options);
        $('.listadoEncInstruAnterior').select2(select2Options);
        $('.listadoEspecialidades2').select2(select2Options);

    });
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>


</body>
<script>
    function normalizarHora(hora) {
        const [h, m] = hora.split(':');
        return `${h.padStart(2, '0')}:${m.padStart(2, '0')}`;
    }

    function actualizarHoras() {
        const turno = document.getElementById('turno').value;
        // Guardar observaciones actuales
        const observacionesMT = {};
        const idMT = {};
        document.querySelectorAll('#tablaCeldasM input[name^="obsMT"]').forEach(input => {
            observacionesMT[input.name] = input.value;
        });
        document.querySelectorAll('#tablaCeldasM input[name^="idMT"').forEach(input =>{
            idMT[input.name] = input.value;
        });

        const observacionesST = {};
        const idST = {};
        document.querySelectorAll('#tablaTransfo input[name^="obsST"]').forEach(input => {
            observacionesST[input.name] = input.value;
        });
        document.querySelectorAll('#tablaTransfo input[name^="idST"').forEach(input =>{
            idST[input.name] = input.value;
        });

        const observacionesSalaTrans = {};
        const idSalaTrans = {};
        document.querySelectorAll('#tablaSalaTransferencia input[name^="obsSalaTrans"]').forEach(input => {
            observacionesSalaTrans[input.name] = input.value;
        });
        document.querySelectorAll('#tablaSalaTransferencia input[name^="idSalaTrans"]').forEach(input => {
            idSalaTrans[input.name] = input.value;
        });


        const observacionSalaTGBT1 = {};
        const idSalaTGBT1_ = {};
        document.querySelectorAll('#tablaSalaTGBT1 input[name^="obsSalaTGBT1"]').forEach(input => {
            observacionSalaTGBT1[input.name] = input.value;
        });
        document.querySelectorAll('#tablaSalaTGBT1 input[name^="idSalaTGBT1_"]').forEach(input => {
            idSalaTGBT1_[input.name] = input.value;
        });

        const observacionSalaTGBT2 = {};
        const idSalaTGBT2_ = {};
        document.querySelectorAll('#tablaSalaTGBT2 input[name^="obsSalaTGBT2_"]').forEach(input =>{
            observacionSalaTGBT2[input.name] = input.value;
            console.log(observacionSalaTGBT2);
        });
        document.querySelectorAll('#tablaSalaTGBT2 input[name^="idSalaTGBT2_"]').forEach(input => {
            idSalaTGBT2_[input.name] = input.value;
        });

        const observaciontablaMT2 = {};
        const idSalaMT2_ = {};
        document.querySelectorAll('#tablaMT2 input[name^="obsSalaMT2_"]').forEach(input =>{
            observaciontablaMT2[input.name] = input.value;
            console.log(observaciontablaMT2);
        });
        document.querySelectorAll('#tablaMT2 input[name^="idSalaMT2_"]').forEach(input => {
            idSalaMT2_[input.name] = input.value;
        });

        const observaciontablaRecintos = {};
        const idRecintosClinicos_ = {};
        document.querySelectorAll('#tablaRecintosClinicos input[name^="obsRecintosClinicos_"]').forEach(input =>{
            observaciontablaRecintos[input.name] = input.value;
        });
        document.querySelectorAll('#tablaRecintosClinicos input[name^="idRecintosClinicos_"]').forEach(input => {
            idRecintosClinicos_[input.name] = input.value;
        });

        const observacionRondasExterior = {};
        const idRondasExterior_ = {};
        document.querySelectorAll('#tablaRondasExterior input[name^="obsRondasExterior_"]').forEach(input =>{
            observacionRondasExterior[input.name] = input.value;
        });
        document.querySelectorAll('#tablaRondasExterior input[name^="idRondasExterior_"]').forEach(input => {
            idRondasExterior_[input.name] = input.value;
        });

        // Definir nuevas horas según el turno
        let horas = turno === 'DIURNO' ? ["9:00", "12:00", "15:00", "18:00"] : ["21:00", "00:00", "03:00", "06:00"];

        // Regenerar tabla Sala MT
        const tbodyMT = document.getElementById('tablaCeldasM');
        tbodyMT.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameHora = `obsMTHora${index + 1}`;
            const nameObs = `obsMT${index + 1}`;
            const oldValue = observacionesMT[nameObs] || '';
            const nameid = `idMT${index + 1}`;
            const oldValueID = idMT[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" name="${nameHora}" value="${hora}">${hora}</td>
                <td>
                    <input type="text" name="${nameObs}" value="${oldValue}" class="form-control">
                    <input type="hidden" name="${nameid}" value="${oldValueID}">
                </td>
            `;
            tbodyMT.appendChild(row);
        });

        // Regenerar tabla Sala Transformadores
        const tbodyST = document.getElementById('tablaTransfo');
        tbodyST.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsST${index + 1}`;
            const oldValue = observacionesST[nameObs] || '';
            const nameid = `idST${index + 1}`;
            const oldValueID = idST[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsSTHora${hora}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control">
<input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodyST.appendChild(row);
        });

        // Regenerar tabla Sala Transferencia
        const tbodySalaTrans = document.getElementById('tablaSalaTransferencia');
        tbodySalaTrans.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsSalaTrans${index + 1}`;
            const oldValue = observacionesSalaTrans[nameObs] || '';
            const nameid = `idSalaTrans${index + 1}`;
            const oldValueID = idSalaTrans[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsSalaTransHora${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>

            `;
            tbodySalaTrans.appendChild(row);
        });

        // Regenerar tabla Sala TGBT1
        const tbodyTGBT1 = document.getElementById('tablaSalaTGBT1');
        tbodyTGBT1.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsSalaTGBT1_${index + 1}`;
            const oldValue = observacionSalaTGBT1[nameObs] || '';
            const nameid = `idSalaTGBT1_${index + 1}`;
            const oldValueID = idSalaTGBT1_[nameid] || '';


            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsSalaTGBTHORA1_${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodyTGBT1.appendChild(row);
        });

        // Regenerar tabla Sala TGBT1
        const tbodyTGBT2 = document.getElementById('tablaSalaTGBT2');
        tbodyTGBT2.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsSalaTGBT2_${index + 1}`;
            const oldValue = observacionSalaTGBT2[nameObs] || '';
            const nameid = `idSalaTGBT2_${index + 1}`;
            const oldValueID = idSalaTGBT2_[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsSalaTGBTHORA2_${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodyTGBT2.appendChild(row);
        });

        const tbodytablaMT2 = document.getElementById('tablaMT2');
        tbodytablaMT2.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsSalaMT2_${index + 1}`;
            const oldValue = observaciontablaMT2[nameObs] || '';
            const nameid = `idSalaMT2_${index + 1}`;
            const oldValueID = idSalaMT2_[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsSalaMTHORA2_${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodytablaMT2.appendChild(row);
        });

        const tbodyRecintosClinicos = document.getElementById('tablaRecintosClinicos');
        tbodyRecintosClinicos.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsRecintosClinicos_${index + 1}`;
            const oldValue = observaciontablaRecintos[nameObs] || '';
            const nameid = `idRecintosClinicos_${index + 1}`;
            const oldValueID = idRecintosClinicos_[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsRecintosClinicosHora_${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodyRecintosClinicos.appendChild(row);
        });

        const tbodyRondasExterior = document.getElementById('tablaRondasExterior');
        tbodyRondasExterior.innerHTML = '';
        horas.forEach((hora, index) => {
            const nameObs = `obsRondasExterior_${index + 1}`;
            const oldValue = observacionRondasExterior[nameObs] || '';
            const nameid = `idRondasExterior_${index + 1}`;
            const oldValueID = idRondasExterior_[nameid] || '';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="hidden" value="${hora}" name="obsRondasExteriorHora_${index + 1}">${hora}</td>
                <td><input type="text" name="${nameObs}" value="${oldValue}" class="form-control"><input type="hidden" name="${nameid}" value="${oldValueID}"></td>
            `;
            tbodyRondasExterior.appendChild(row);
        });

        // Actualizar encabezados de la tabla de transformadores
        const thsTransformador = document.querySelectorAll('#encabezadoHorasTransformador .th-hora-transformador');
        const thsTransformador6y7 = document.querySelectorAll('#encabezadoHorasTransformador6y7 .th-hora-transformador-6y7')

// Validación por si la cantidad de TH no coincide
        if (thsTransformador.length === horas.length) {
            thsTransformador.forEach((th, index) => {
                const horaVisible = horas[index]; // ej: "09:00"
                th.textContent = horaVisible; // Solo actualizamos el texto visible
            });
        } else {
            console.warn("La cantidad de columnas de hora no coincide con las nuevas horas.");
        }

        if (thsTransformador6y7.length === horas.length){
            thsTransformador6y7.forEach((th, index) =>{
                const horaVisible = horas[index]; // ej: "09:00"
                th.textContent = horaVisible; // Solo actualizamos el texto visible
            });
        }

// Guardar datos actuales UPS
        const tempUPS = {};
        const alarmaUPS = {};
        const eventoUPS = {};
        const idUPS = {};
        const horaUPS = {};


        const tempUPSDIN2 = {};
        const alarmaUPSDIN2 = {};
        const eventoUPSDIN2 = {};
        const idUPSDIN2 = {};
        const horaUPSDIN2 = {};

        document.querySelectorAll('input[name^="temp_"]').forEach(input => {
            tempUPS[input.name] = input.value;
        });
        document.querySelectorAll('textarea[name^="alarmaSala_"]').forEach(textarea => {
            alarmaUPS[textarea.name] = textarea.value;
        });
        document.querySelectorAll('textarea[name^="eventoSala_"]').forEach(textarea => {
            eventoUPS[textarea.name] = textarea.value;
        });
        document.querySelectorAll('input[name^="idUps1_"]').forEach(input => {
            idUPS[input.name] = input.value;
           // console.log(idUPS);

        });
        document.querySelectorAll('input[name^="upsHora_"]').forEach(input => {
            horaUPS[input.name] = input.value;
           // console.log(horaUPS);
        });


        document.querySelectorAll('input[name^="tempDIN2_"]').forEach(input => {
            tempUPSDIN2[input.name] = input.value;
        });
        document.querySelectorAll('textarea[name^="alarmaDIN2_"]').forEach(textarea => {
            alarmaUPSDIN2[textarea.name] = textarea.value;
        });
        document.querySelectorAll('textarea[name^="eventoDIN2_"]').forEach(textarea => {
            eventoUPSDIN2[textarea.name] = textarea.value;
        });
        document.querySelectorAll('input[name^="idUPSDIN2_"]').forEach(input => {
            idUPSDIN2[input.name] = input.value;

        });
        document.querySelectorAll('input[name^="upsHoraDIN2_"]').forEach(input => {
            horaUPSDIN2[input.name] = input.value;
            console.log(horaUPSDIN2);
        });


        actualizarTablaUPS(turno, tempUPS, alarmaUPS, eventoUPS, idUPS, horaUPS);
        actualizarTablaUPSDIN2(turno, tempUPSDIN2, alarmaUPSDIN2, eventoUPSDIN2, idUPSDIN2, horaUPSDIN2);

        actualizarTablaPabellonesPorTurno(turno);
        actualizarEncabezadosHoraGG(horas);


    }

    function actualizarTablaPabellonesPorTurno(turno) {
        const contenedor = document.getElementById('tablaRondasPabellon');

        if (turno === 'NOCHE') {
            // Mostrar tabla vacía del 1 al 10 (NO la de la BD)
            contenedor.style.display = 'block';
            contenedor.innerHTML = `
            <strong>5.- </strong><span id="tituloRondaPabellon">Rondas de Pabellones:</span>
            <br>
            <table class="table table-bordered text-center" style="width: 100%;">
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
                    ${[...Array(10)].map((_, i) => `
                        <tr>
                            <td>Pabellón ${i + 1}</td>
                            <td><textarea class="form-control" name="protecciones_tablero${i + 1}"></textarea></td>
                            <td><textarea class="form-control" name="alarma_finder${i + 1}"></textarea></td>
                            <td><textarea class="form-control" name="alarma_ups${i + 1}"></textarea></td>
                            <td><textarea class="form-control" name="estado_luces${i + 1}"></textarea></td>
                            <td><textarea class="form-control" name="condicion_puertas${i + 1}"></textarea></td>
                            <td><textarea class="form-control" name="otras_observaciones${i + 1}"></textarea></td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        } else {
            // Ocultar tabla si cambia a DIURNO
            contenedor.style.display = 'none';
            contenedor.innerHTML = '';
        }
    }

</script>
<script>
    function actualizarTablaUPSDIN2(turno, tempUPSDIN2 = {}, alarmaUPSDIN2 = {}, eventoUPSDIN2 = {}, idUPSDIN2 = {}, horaUPSDIN2 = {}) {
        const contenedorDIN2 = document.getElementById("contenedorTablaUPSDIN2");
        const horasTurno = turno === "DIURNO"
            ? ["9:00", "12:00", "15:00", "18:00"]
            : ["21:00", "00:00", "03:00", "06:00"];

        let html2 = `
        <table class="table table-bordered text-center">
            <thead class="bg-secondary text-white">
                <tr>
                    <th style="border: 1px solid black;">UPS</th>
                    <th style="border: 1px solid black;">HORA</th>
                    <th style="border: 1px solid black;">TEMP</th>
                    <th style="border: 1px solid black;">ALARMA</th>
                    <th style="border: 1px solid black;">ÚLTIMO EVENTO</th>
                </tr>
            </thead>
            <tbody>
    `;

        let contador2 = 0;

        upsListDIN2.forEach(ups => {
            if (!datosUPSDIN2[ups]) return;

            let primeraFilaDIN2 = true;

            horasTurno.forEach(hora => {
                const rowDataDIN2 = datosUPSDIN2[ups][hora] || {};

                const tempDIN2 = tempUPSDIN2[`tempDIN2_${contador2}`] ?? (rowDataDIN2.temperatura !== '-' ? rowDataDIN2.temperatura : '');
                const alarmaDIN2 = alarmaUPSDIN2[`alarmaDIN2_${contador2}`] ?? (rowDataDIN2.alarma || '');
                const eventoDIN2 = eventoUPSDIN2[`eventoDIN2_${contador2}`] ?? (rowDataDIN2.ultimo_evento || '');
                const idDIN2 = idUPSDIN2[`idUPSDIN2_${contador2}`] ?? (rowDataDIN2.id || '');
                const horaFormDIN2 = horaUPSDIN2[`upsHoraDIN2_${contador2}`] ?? hora;

                html2 += `<tr>`;
                if (primeraFilaDIN2) {
                    html2 += `<td rowspan="${horasTurno.length}" style="border: 1px solid black;">${ups}</td>`;
                    primeraFilaDIN2 = false;
                }
                html2 += `
                <td style="border: 1px solid black;">${hora}</td>
                <td style="border: 1px solid black;">
                    <input type="hidden" name="idUPSDIN2_${contador2}" value="${idDIN2}" />
                    <input type="hidden" name="upsHoraDIN2_${contador2}" value="${horaFormDIN2}" />
                    <input type="text" class="form-control" name="tempDIN2_${contador2}" value="${tempDIN2}">
                </td>
                <td style="border: 1px solid black;">
                    <textarea class="form-control" name="alarmaDIN2_${contador2}">${alarmaDIN2}</textarea>
                </td>
                <td style="border: 1px solid black;">
                    <textarea class="form-control" name="eventoDIN2_${contador2}">${eventoDIN2}</textarea>
                </td>
            </tr>`;
                contador2++;
            });
        });

        html2 += `</tbody></table>`;
        contenedorDIN2.innerHTML = html2;
    }

</script>
<script>
    function actualizarTablaUPS(turno, tempUPS = {}, alarmaUPS = {}, eventoUPS = {}, idUPS = {}, horaUPS = {}) {
        const contenedor = document.getElementById("contenedorTablaUPS");

        const horasTurno = turno === "DIURNO"
            ? ["9:00", "12:00", "15:00", "18:00"]
            : ["21:00", "00:00", "03:00", "06:00"];

        let html = `
    <table class="table table-bordered text-center">
        <thead class="bg-secondary">
            <tr>
                <th style="border: 1px solid black;">UPS</th>
                <th style="border: 1px solid black;">HORA</th>
                <th style="border: 1px solid black;">TEMP</th>
                <th style="border: 1px solid black;">ALARMA</th>
                <th style="border: 1px solid black;">ÚLTIMO EVENTO</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
    `;

        let contador = 0;
        upsList.forEach(ups => {
            if (!datosUPS[ups]) return;

            let primeraFila = true;
            horasTurno.forEach(hora => {
                const rowData = datosUPS[ups][hora] || {};
                const temp = tempUPS[`temp_${contador}`] ?? (rowData.temperatura && rowData.temperatura !== '-' ? rowData.temperatura : '');
                const alarma = alarmaUPS[`alarmaSala_${contador}`] ?? (rowData.alarma || '');
                const evento = eventoUPS[`eventoSala_${contador}`] ?? (rowData.ultimo_evento || '');
                const id = idUPS[`idUps1_${contador}`] ?? (rowData.id || '');
                const horaForm = horaUPS[`upsHora_${contador}`] ?? hora;

                html += `<tr>`;
                if (primeraFila) {
                    html += `<td rowspan="${horasTurno.length}" style="border: 1px solid black;">${ups}</td>`;
                    primeraFila = false;
                }
                html += `
            <td style="border: 1px solid black;">${hora}</td>
            <td style="border: 1px solid black;">
                <input type="hidden" name="idUps1_${contador}" value="${id}" />
                <input type="hidden" name="upsHora_${contador}" value="${horaForm}" />
                <input type="text" class="form-control" name="temp_${contador}" value="${temp}">
            </td>
            <td style="border: 1px solid black;">
                <textarea class="form-control" name="alarmaSala_${contador}">${alarma}</textarea>
            </td>
            <td style="border: 1px solid black;">
                <textarea class="form-control" name="eventoSala_${contador}">${evento}</textarea>
            </td>
        </tr>`;
                contador++;
            });
        });

        html += `</tbody></table>`;
        contenedor.innerHTML = html;
    }

</script>
<script>
    function actualizarEncabezadosHoraGG(nuevasHoras) {
        const ths = document.querySelectorAll('#encabezadoHorasGG .th-hora-gg');
        const inputsHora = document.querySelectorAll('#encabezadoHorasGG input[name^="horaGG"]');

        if (ths.length !== nuevasHoras.length || inputsHora.length !== nuevasHoras.length) {
            console.warn("Cantidad de columnas no coincide con horas del turno.");
            return;
        }

        nuevasHoras.forEach((hora, index) => {
            const horaFormateada = normalizarHora(hora);
            ths[index].childNodes[0].nodeValue = horaFormateada + " ";  // actualiza el texto visible
            inputsHora[index].value = horaFormateada; // actualiza el input oculto
        });
    }

</script>
</html>
