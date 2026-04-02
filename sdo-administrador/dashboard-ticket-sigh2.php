

<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$tipoUsuarioVer = 'todos';

include '../vendor/quickchart/QuickChart.php';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Ticketera SIGH</title>

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
    <script src="js/dark-mode.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../plugins/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini ">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                </div>
                <div class="card-header" style="background-color: white">
                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                    <br>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">

                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#myModal">
                                    <i class="fas fa-file-pdf"></i> Generar Informe
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <button class="btn btn-block btn-primary" onclick="actualizarTickets()"><i class="fas fa-arrow-alt-circle-down"></i> Actualizar Tickets SIGH</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        function actualizarTickets(){
                            $('.overlay').show();

                            var form = $('<form>', {
                                'action': '../sdo-funciones/sigh_actualizarTickets.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                'method': 'POST'
                            });

                            // Agregar el formulario al documento y enviarlo
                            form.appendTo('body').submit();
                        }
                    </script>
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title" id="myModalLabel">Generar Informe</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php

                                $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
                                //                                    $ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
                                //                                    $ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
                                //
                                // Obtén la IP del cliente
                                $ipCliente = $_SERVER['REMOTE_ADDR'];
                                //
                                /// // Verifica si la IP del cliente es la permitida


                                if ($ipCliente === $ipPermitida) {
                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php';
                                } else {
//                                        $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH.php';
                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php';
                                }


                                ?>
                                <form action="../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php" target="_blank" method="POST">
                                    <div class="modal-body">
                                        <label>Escoga una fecha</label>
                                        <select name="tipo" id="tipo" onchange="cambiarEstado()" class="form-control">
                                            <option selected disabled>Seleccione</option>
                                            <option value="Anio">Anual</option>
                                            <option value="Mensual">Mensual</option>
                                            <option value="Trimestral">Trimestral</option>
                                        </select>
                                        <div class="mensual" style="display: none">
                                            <br>
                                            <input type="month" id="meses" name="meses" class="form-control" onchange="verificarSeleccion()">
                                        </div>
                                        <div class="anio" style="display: none">
                                            <br>
                                            <label>Año:</label>
                                            <select name="anio" id="anio" class="form-control" onchange="verificarSeleccion()">
                                                <option value="" selected>Seleccione</option>
                                                <?php
                                                $sqlAño = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS año FROM ticket_sigh;";
                                                $resAño = mysqli_query($conexion,$sqlAño);
                                                while($rowAño = $resAño->fetch_assoc()){
                                                    $año = $rowAño['año'];
                                                    ?>
                                                    <option value="<?php echo $año?>"><?php echo $año?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="trimestral" style="display: none">
                                            <br>
                                            <label>Trimestres:</label>
                                            <select name="trimestral" id="trimestral" class="form-control" onchange="verificarSeleccion()">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="1er Trimestre">1er Trimestre</option>
                                                <option value="2do Trimestre">2do Trimestre</option>
                                                <option value="3er Trimestre">3er Trimestre</option>
                                                <option value="4to Trimestre">4to Trimestre</option>
                                            </select>
                                            <br>
                                            <label>Año:</label>
                                            <select name="anioTrim" id="anioTrim" class="form-control" onchange="verificarSeleccion()">
                                                <option value="" selected>Seleccione</option>
                                                <?php
                                                $sqlAño = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS año FROM ticket_sigh;";
                                                $resAño = mysqli_query($conexion,$sqlAño);
                                                while($rowAño = $resAño->fetch_assoc()){
                                                    $año = $rowAño['año'];
                                                    ?>
                                                    <option value="<?php echo $año?>"><?php echo $año?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" id="visualizarBtn" disabled>Visualizar Informe</button>
                                    </div>
                                </form>

                                <script>
                                    function cambiarEstado() {
                                        var tipo = document.getElementById('tipo').value;
                                        document.querySelector('.mensual').style.display = 'none';
                                        document.querySelector('.anio').style.display = 'none';
                                        document.querySelector('.trimestral').style.display = 'none';

                                        if (tipo === 'Mensual') {
                                            document.querySelector('.mensual').style.display = 'block';
                                        } else if (tipo === 'Anio') {
                                            document.querySelector('.anio').style.display = 'block';
                                        } else if (tipo === 'Trimestral') {
                                            document.querySelector('.trimestral').style.display = 'block';
                                        }
                                        verificarSeleccion();
                                    }

                                    function verificarSeleccion() {
                                        var tipo = document.getElementById('tipo').value;
                                        var visualizarBtn = document.getElementById('visualizarBtn');
                                        var habilitar = false;

                                        if (tipo === 'Mensual') {
                                            var meses = document.getElementById('meses').value;
                                            habilitar = meses !== '';
                                        } else if (tipo === 'Anio') {
                                            var anio = document.getElementById('anio').value;
                                            habilitar = anio !== '';
                                        } else if (tipo === 'Trimestral') {
                                            var trimestral = document.getElementById('trimestral').value;
                                            var anioTrim = document.getElementById('anioTrim').value;
                                            habilitar = (trimestral !== '' && anioTrim !== '');
                                        }

                                        visualizarBtn.disabled = !habilitar;
                                    }
                                </script>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-12">
                    <div class="card-header d-flex p-0" style="background-color: white">
                        <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
                        <ul class="nav nav-pills ml-auto p-2">
                            <?php
                            date_default_timezone_set('America/Santiago');
                            $nowAnio = date('Y', time());

                            $sqlAnios = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS anio FROM ticket_sigh ORDER BY fechaSolicTicketSIGH ASC;";
                            $resAnios = mysqli_query($conexion, $sqlAnios);
                            while ($row = $resAnios->fetch_assoc()) {
                                if ($row['anio'] == $nowAnio) {
                                    ?>
                                    <li class="nav-item"><a class="nav-link active" href="#tab<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="nav-item"><a class="nav-link" href="#tab<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="card-body">

                        <div class="tab-content">
                            <?php
                            date_default_timezone_set('America/Santiago');
                            $nowAnio = date('Y', time());
                            $sqlAnios = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS anio FROM ticket_sigh ORDER BY fechaSolicTicketSIGH ASC;";
                            $resAnios = mysqli_query($conexion, $sqlAnios);
                            while ($row = $resAnios->fetch_assoc()) {
                                $anio = $row['anio'];
                                $anioAnterior = $anio - 1;
                                $nowMes = date('m', time());
                                if ($anio == $nowAnio) {
                                    $tabPane = 'active';
                                } else {
                                    $tabPane = '';
                                }
                                ?>
                                <div class="tab-pane <?php echo $tabPane; ?>" id="tab<?php echo $row['anio']; ?>">
                                    <div class="col-12">
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    $nowHT = date('Y-m', time());
                                                    $anio = date('Y', time()); // Se define $anio
                                                    $titulo1 = "Cantidad de Tickets del SIGH en Año " . $anio . " por Departamento";
                                                    $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                    ?>
                                                    <p class="badge-info w-100 text-center" style="font-size: 12px; font-weight:bold; overflow-wrap: break-word;">
                                                        <?php echo $titulo2; ?>
                                                    </p>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead class="bg-success">
                                                            <tr>
                                                                <th scope="col">Departamento</th>
                                                                <?php for ($mes = 1; $mes <= 12; $mes++) { ?>
                                                                    <th scope="col" class="text-center"><?php echo mesEspañol2($mes); ?></th>
                                                                <?php } ?>
                                                                <th scope="col" class="text-center">Total Año <?php echo $anio; ?></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="bg-white">
                                                            <?php
                                                            // Consulta de departamentos según tipo de usuario
                                                            $sqlDeptoT = ($tipoUsuarioVer == 'unico')
                                                                ? "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC"
                                                                : "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";

                                                            $resDeptoT = mysqli_query($conexion, $sqlDeptoT);
                                                            $arrayCantTotal = array_fill(0, 12, 0); // Array para totales mensuales
                                                            $cantPorMesTotal = 0;

                                                            while ($rowDeptoT = $resDeptoT->fetch_assoc()) {
                                                                $idDepartamentoT = $rowDeptoT['idDepartamento'];
                                                                if ($idDepartamentoT == 11 || $idDepartamentoT == 9) {
                                                                    continue;
                                                                }

                                                                $cantTotalDepto = 0;
                                                                $nomDepartamentoT = $rowDeptoT['nomDepartamento'];
                                                                ?>
                                                                <tr>
                                                                    <td class="font-weight-bold"><?php echo $nomDepartamentoT; ?></td>
                                                                    <?php for ($i = 1; $i <= 12; $i++) {
                                                                        $mesHT = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                        $fechaT = $anio . '-' . $mesHT;
                                                                        $totalHojasTecnicas = 0;

                                                                        // Consulta de jefes y empleados por departamento
                                                                        $sqlBuscarJefesHT = "SELECT jt.rutJefes AS nomEmpleado FROM jefes_tecnicos jt WHERE idDepartamento = '$idDepartamentoT'
                                                 UNION 
                                                 SELECT p.rutPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = '$idDepartamentoT'";
                                                                        $resBuscarJefesHT = mysqli_query($conexion, $sqlBuscarJefesHT);

                                                                        while ($rowBuscarJefesHT = $resBuscarJefesHT->fetch_assoc()) {
                                                                            $rutHT = substr_replace($rowBuscarJefesHT['nomEmpleado'], '-', 8, 0);
                                                                            $totalHojasTecnicas += contarTicketSIGHMesAnio($rutHT, $fechaT);
                                                                        }

                                                                        $cantTotalDepto += $totalHojasTecnicas;
                                                                        $arrayCantTotal[$i - 1] += $totalHojasTecnicas; // Suma al total mensual
                                                                        ?>
                                                                        <td class="text-center"><?php echo $totalHojasTecnicas; ?></td>
                                                                    <?php } ?>
                                                                    <td class="text-center text-bold"><?php echo $cantTotalDepto; ?></td>
                                                                </tr>
                                                            <?php } ?>

                                                            <!-- Fila de totales -->
                                                            <tr class="bg-info">
                                                                <th>Totales</th>
                                                                <?php foreach ($arrayCantTotal as $totalMes) { ?>
                                                                    <td class="text-center text-bold"><?php echo $totalMes; ?></td>
                                                                    <?php
                                                                    $cantPorMesTotal += $totalMes;
                                                                } ?>
                                                                <td class="text-center text-bold"><?php echo $cantPorMesTotal; ?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist" style="background-color: white">
                                            <?php
                                            $activeDash = '';
                                            for ($i = 1; $i < 13; $i++) {

                                                $mesNow = date('m', time());
                                                if ($i == $mesNow) {
                                                    $activeDash = 'active';
                                                } else {
                                                    $activeDash = '';
                                                }
                                                ?>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i.$anio ?>" data-toggle="pill" href="#pills-home<?php echo $i.$anio ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <?php
                                            $activeDash2 = '';
                                            for ($i = 1; $i < 13; $i++) {
                                                $mesAnioTab = date("$anio", time());
                                                if ($i < 10) {
                                                    $mesAnioTab = $mesAnioTab . '-0' . $i;
                                                } else {
                                                    $mesAnioTab = $mesAnioTab . '-' . $i;
                                                }
                                                $mesNow = date('m', time());
                                                $mesNow = ltrim(date('m'), '0');

                                                $mesEspañol2Dash = mesEspañol2($mesNow);
                                                if ($i == $mesNow) {
                                                    $activeDash2 = 'show active';
                                                } else {
                                                    $activeDash2 = '';
                                                }
                                                ?>

                                                <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-home<?php echo $i.$anio ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i.$anio ?>">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card-header text-center" style="background-color: indigo;color: white" >
                                                                <h5>Tickets por Estados: Mes: <?php echo mesEspañol2($i)?> - Año: <?php echo $anio?></h5>
                                                                <h5>Total de Tickets: <?php echo $contarTicketsAnoMes = contarTicketSIGHaniomes($anio,$i); ?></h5>
                                                            </div>
                                                            <div class="card-body" style="background-color: white">
                                                                <?php

                                                                $sqlUnidad = "SELECT estadoTicketSIGH, COUNT(*) AS frecuencia FROM ticket_sigh WHERE YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' AND nomTecnicoTicketSIGH NOT LIKE ('%Pamela Vicencio Carvacho%') AND nomTecnicoTicketSIGH NOT LIKE ('%Estefano Villaseca%') AND nomTecnicoTicketSIGH NOT LIKE ('%Vicente Donoso%') GROUP BY estadoTicketSIGH";
                                                                $resUnidad = mysqli_query($conexion, $sqlUnidad);
                                                                $nombres = array();
                                                                $frecuencias = array();
                                                                if($resUnidad){
                                                                    while($rowUnidad = $resUnidad->fetch_assoc()){
                                                                        $nombres[] = $rowUnidad['estadoTicketSIGH'];
                                                                        $frecuencias[] = $rowUnidad['frecuencia'];
                                                                    }
                                                                    ?>
                                                                    <canvas id="myChart<?php echo $i?><?php echo $row['anio']?>" height="100"></canvas>

                                                                    <script>
                                                                        // Datos obtenidos de PHP
                                                                        const nombres<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($nombres); ?>;
                                                                        const frecuencias<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($frecuencias); ?>;

                                                                        // Configurar el gráfico
                                                                        const ctx<?php echo $i?><?php echo $row['anio']?> = document.getElementById('myChart<?php echo $i?><?php echo $row['anio']?>').getContext('2d');
                                                                        const myChart<?php echo $i?><?php echo $row['anio']?> = new Chart(ctx<?php echo $i?><?php echo $row['anio']?>, {
                                                                            type: 'bar',
                                                                            data: {
                                                                                labels: nombres<?php echo $i?><?php echo $row['anio']?>,
                                                                                datasets: [{
                                                                                    label: 'Cantidad de Tickets',
                                                                                    data: frecuencias<?php echo $i?><?php echo $row['anio']?>,
                                                                                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Color de las barras
                                                                                    borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                                                                                    borderWidth: 1,
                                                                                }]
                                                                            },
                                                                            options: {
                                                                                scales: {
                                                                                    y: {
                                                                                        beginAtZero: true // Empezar el eje Y desde cero
                                                                                    },
                                                                                    x: {
                                                                                        display: true,
                                                                                    }
                                                                                },
                                                                                plugins: {
                                                                                    legend: {
                                                                                        display: false
                                                                                    },
                                                                                    datalabels: {
                                                                                        anchor: 'end',
                                                                                        align: 'top',
                                                                                        formatter: function(value, context) {
                                                                                            return Math.round(value); // Formato de los números
                                                                                        },
                                                                                        color: '#000000'
                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card-header text-center" style="background-color: indigo;color: white" >
                                                                <h5>Las 5 unidades clínicas con más tickets en el mes: <?php echo mesEspañol2($i)?> - Año: <?php echo $anio?></h5>
                                                            </div>
                                                            <div class="card-body" style="background-color: white">
                                                                <?php
                                                                $sqlUnidadMes = "SELECT unidadTicketSIGH, COUNT(*) AS frecuenciaUnidad FROM ticket_sigh where YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' GROUP BY unidadTicketSIGH ORDER BY COUNT(*) DESC LIMIT 5";
                                                                $resUnidadMes = mysqli_query($conexion,$sqlUnidadMes);
                                                                $nombresUnidad = array();
                                                                $frecuenciasUnidad = array();
                                                                while($rowUnidadMes = $resUnidadMes->fetch_assoc()){
                                                                    $nombresUnidad[] = $rowUnidadMes['unidadTicketSIGH'];
                                                                    $frecuenciasUnidad[] = $rowUnidadMes['frecuenciaUnidad'];
                                                                }
                                                                ?>
                                                                <canvas id="myChartUnidad<?php echo $i?><?php echo $row['anio']?>" height="100"></canvas>
                                                                <script>
                                                                    // Datos obtenidos de PHP
                                                                    const nombresUnidad<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($nombresUnidad); ?>;
                                                                    const frecuenciasUnidad<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($frecuenciasUnidad); ?>;

                                                                    // Configurar el gráfico
                                                                    const ctxUnidad<?php echo $i?><?php echo $row['anio']?> = document.getElementById('myChartUnidad<?php echo $i?><?php echo $row['anio']?>').getContext('2d');
                                                                    const myChartUnidad<?php echo $i?><?php echo $row['anio']?> = new Chart(ctxUnidad<?php echo $i?><?php echo $row['anio']?>, {
                                                                        type: 'bar',
                                                                        data: {
                                                                            labels: nombresUnidad<?php echo $i?><?php echo $row['anio']?>,
                                                                            datasets: [{
                                                                                label: 'Cantidad de Tickets por Unidad',
                                                                                data: frecuenciasUnidad<?php echo $i?><?php echo $row['anio']?>,
                                                                                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Color de las barras
                                                                                borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                                                                                borderWidth: 1,
                                                                            }]
                                                                        },
                                                                        options: {
                                                                            scales: {
                                                                                y: {
                                                                                    beginAtZero: true // Empezar el eje Y desde cero

                                                                                },
                                                                                x: {
                                                                                    display: true,

                                                                                }
                                                                            },
                                                                            plugins: {
                                                                                datalabels: {
                                                                                    formatter: (value) => {
                                                                                        return Math.round(value);
                                                                                    },
                                                                                    color: '#000000'
                                                                                }
                                                                            }

                                                                        }
                                                                    });
                                                                </script>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card-header text-center" style="background-color: indigo,color:white">


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-12">
                                                        <?php
                                                        $numCardHT = 1;

                                                        $obtenerCantidadDepartamentos = obtenerCantidadDepartamentos();

                                                        $colorTipoDepartamento = '';
                                                        ?>
                                                        <div class="row">
                                                            <?php
                                                            $sqlDeptoT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional'  AND nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Control Centralizado' ORDER BY nomDepartamento ASC";

                                                            $resDeptoT3 = mysqli_query($conexion, $sqlDeptoT3);
                                                            while ($rowDeptoT3 = $resDeptoT3->fetch_assoc()) {
                                                                $idDeptoT3 = $rowDeptoT3['idDepartamento'];
                                                                $nomDeptoT3 = $rowDeptoT3['nomDepartamento'];

                                                                switch ($numCardHT) {
                                                                    case 1:
                                                                        $colorTipoDepartamento = 'card-success';
                                                                        break;
                                                                    case 2:
                                                                        $colorTipoDepartamento = 'card-purple';
                                                                        break;
                                                                    case 3:
                                                                        $colorTipoDepartamento = 'card-info';
                                                                        break;
                                                                    case 4:
                                                                        $colorTipoDepartamento = 'card-danger';
                                                                        break;
                                                                    case 5:
                                                                        $colorTipoDepartamento = 'card-orange';
                                                                        break;
                                                                    case 6:
                                                                        $colorTipoDepartamento = 'card-yellow';
                                                                        break;
                                                                    case 7:
                                                                        $colorTipoDepartamento = 'card-success';
                                                                        break;
                                                                    case 8:
                                                                        $colorTipoDepartamento = 'card-purple';
                                                                        break;
                                                                    case 9:
                                                                        $colorTipoDepartamento = 'card-info';
                                                                        break;
                                                                    case 10:
                                                                        $colorTipoDepartamento = 'card-danger';
                                                                        break;
                                                                    case 11:
                                                                        $colorTipoDepartamento = 'card-orange';
                                                                        break;
                                                                    case 11:
                                                                        $colorTipoDepartamento = 'card-yellow';
                                                                        break;
                                                                    default:
                                                                        $colorTipoDepartamento = '';
                                                                }
                                                                ?>
                                                                <div class="col-12 col-md-6 h-100">
                                                                    <div class="card <?php echo $colorTipoDepartamento; ?> collapsed-card">
                                                                        <div class="card-header">
                                                                            <h3 class="card-title">&nbsp;&nbsp;<b>
                                                                                    <?php
                                                                                    echo $nomDeptoT3;

                                                                                    $numCardHT = $numCardHT + 1;
                                                                                    ?>
                                                                                </b></h3>

                                                                            <div class="card-tools">
                                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                                    <i class="fas fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                            <!-- /.card-tools -->
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <table class="table table-hover">
                                                                                <thead style="font-size: 12px">
                                                                                <tr>
                                                                                    <th scope="col" class="text-center">PERSONAL</th>
                                                                                    <th scope="col" class="text-center">CANTIDAD DE TICKET</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody style="font-size: 12px">
                                                                                <?php
                                                                                $totalDepto = 0;
                                                                                $sqlPersonal = "SELECT rutPersonal,nomPersonal,cargoPersonal FROM personal WHERE departamentoPersonal = $idDeptoT3 AND activoPersonal = 1";
                                                                                $resPersonal = mysqli_query($conexion,$sqlPersonal);
                                                                                while($rowPersonal = $resPersonal->fetch_assoc()){
                                                                                    $nomPersonal = $rowPersonal['nomPersonal'];
                                                                                    $cargoPersonal = $rowPersonal['cargoPersonal'];
                                                                                    $rutPersonal = $rowPersonal['rutPersonal'];
                                                                                    $rut_con_guion = substr_replace($rutPersonal, '-', 8, 0);
                                                                                    $sqlBuscarPersonal = "SELECT COUNT(*) as total FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$rut_con_guion' AND fechaSolicTicketSIGH BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' ";
                                                                                    $resBuscarPersonal = mysqli_query($conexion,$sqlBuscarPersonal);
                                                                                    while($rowBuscarPersonal = $resBuscarPersonal->fetch_assoc()){
                                                                                        $totalPersonal = $rowBuscarPersonal['total'];
                                                                                    }
                                                                                    $totalDepto = $totalDepto + $totalPersonal;

                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $nomPersonal . " (" . $cargoPersonal . ")" ?></td>
                                                                                        <td class="text-center"><?php echo $totalPersonal ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                $sqlJefes = "SELECT rutJefes,nomJefe,cargoJefe FROM jefes_tecnicos WHERE nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND idDepartamento = $idDeptoT3 AND userActivado = 1";
                                                                                $resJefes = mysqli_query($conexion,$sqlJefes);
                                                                                while($rowJefes = $resJefes->fetch_assoc()){
                                                                                    $nomJefes = $rowJefes['nomJefe'];
                                                                                    $cargoJefes = $rowJefes['cargoJefe'];
                                                                                    $rutJefes = $rowJefes['rutJefes'];
                                                                                    $rut_con_guionJefes = substr_replace($rutJefes, '-', 8, 0);
                                                                                    $sqlBuscarJefesCol = "SELECT COUNT(*) as total FROM ticket_sigh WHERE rutTecnicoTicketSIGH = '$rut_con_guionJefes' AND fechaSolicTicketSIGH BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' ";
                                                                                    $resBuscarJefesCol = mysqli_query($conexion,$sqlBuscarJefesCol);
                                                                                    while($rowBuscarJefesCol = $resBuscarJefesCol->fetch_assoc()){
                                                                                        $totalJefes = $rowBuscarJefesCol['total'];
                                                                                    }
                                                                                    $totalDepto = $totalDepto + $totalJefes;
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $nomJefes . " (" . $cargoJefes . ")" ?></td>
                                                                                        <td class="text-center"><?php echo $totalJefes ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                <tr>
                                                                                    <td class="text-center">Total</td>
                                                                                    <td class="text-center"><?php echo $totalDepto ?> </td>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>

                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>


                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <div class="overlay">
        <div class="spinner">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <!-- Spinner icon -->
        </div>
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
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
