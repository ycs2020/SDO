<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$tipoUsuarioVer = 'todos';

include '../vendor/quickchart/QuickChart.php';

$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
// Obtén la IP del cliente
$ipCliente = $_SERVER['REMOTE_ADDR'];

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
        $(document).ready(function () {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function () {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function (r) {
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
                <div class="card-header">
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
                        function actualizarTickets() {
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

                                /// // Verifica si la IP del cliente es la permitida


                                //                                if ($ipCliente === $ipPermitida) {
                                //                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php';
                                //                                } else {
                                ////                                        $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH.php';
                                //                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php';
                                //                                }

                                if ($ipCliente === $ipPermitida4) {
                                    $valorAnterior = 1;
                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH4.php';
                                } else {
                                    $valorAnterior = 0;
                                    $valorDesarrollo = '../sdo-pdf/SDO-Informe-Ticketera-SIGH.php';
                                }

                                ?>
                                <form action="<?php echo $valorDesarrollo; ?>" target="_blank" method="POST">
                                    <div class="modal-body">
                                        <?php
                                        if ($valorAnterior == 1) {
                                            ?>

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
                                                    $resAño = mysqli_query($conexion, $sqlAño);
                                                    while ($rowAño = $resAño->fetch_assoc()) {
                                                        $año = $rowAño['año'];
                                                        ?>
                                                        <option value="<?php echo $año ?>"><?php echo $año ?></option>
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
                                                    $resAño = mysqli_query($conexion, $sqlAño);
                                                    while ($rowAño = $resAño->fetch_assoc()) {
                                                        $año = $rowAño['año'];
                                                        ?>
                                                        <option value="<?php echo $año ?>"><?php echo $año ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>


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
                                            <?php
                                        } else {
                                            ?>
                                            <div style="text-align:left;" class="form-group">
                                                <label>Fecha de Inicio:</label>
                                                <input type="date" class="form-control" name="fechaInicioInforme" id="fechaInicioInforme">
                                            </div>
                                            <div style="text-align:left;" class="form-group">
                                                <label>Fecha de Termino:</label>
                                                <input type="date" class="form-control" name="fechaTerminoInforme" id="fechaTerminoInforme">

                                            </div>
                                            <script>
                                                $(document).ready(function () {
                                                    $("#visualizarBtn").click(function (event) {
                                                        let fechaInicio = $("#fechaInicioInforme").val();
                                                        let fechaTermino = $("#fechaTerminoInforme").val();

                                                        if (!fechaInicio || !fechaTermino) {
                                                            Swal.fire({
                                                                icon: "warning",
                                                                title: "Campos vacíos",
                                                                text: "Debe seleccionar ambas fechas antes de visualizar el informe.",
                                                                confirmButtonText: "Entendido"
                                                            });
                                                            event.preventDefault(); // Evita que el formulario se envíe
                                                            return;
                                                        }

                                                        // Convertir las fechas a formato Date para compararlas
                                                        let inicio = new Date(fechaInicio);
                                                        let termino = new Date(fechaTermino);

                                                        if (termino < inicio) {
                                                            Swal.fire({
                                                                icon: "error",
                                                                title: "Fecha inválida",
                                                                text: "La fecha de término no puede ser menor a la fecha de inicio.",
                                                                confirmButtonText: "Entendido"
                                                            });
                                                            event.preventDefault(); // Evita que el formulario se envíe
                                                        }
                                                    });
                                                });
                                            </script>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" id="visualizarBtn"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Informe</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-12 col-md-12">
                    <div class="card-header d-flex p-0 bg-secondary">
                        <h3 class="card-title p-3 ">Dashboard de Información</h3>
                        <ul class="nav nav-pills ml-auto p-2">
                            <?php
                            date_default_timezone_set('America/Santiago');
                            $nowAnio = date('Y');

                            $sqlAnios = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS anio FROM ticket_sigh ORDER BY fechaSolicTicketSIGH ASC;";
                            $resAnios = mysqli_query($conexion, $sqlAnios);

                            $listaAnios = []; // Declarar el array

                            while ($row = $resAnios->fetch_assoc()) {
                                $anio = $row['anio'];
                                $listaAnios[] = $anio; // Agregar año al array

                                $activeAnio = ($anio == $nowAnio) ? 'active' : '';
                                echo "<li class='nav-item'><a style='color: black' class='nav-link $activeAnio' href='#tab$anio' data-toggle='tab'>$anio</a></li>";
                            }

                            // Si necesitas ver el array después, puedes hacer un var_dump para depurar
                            // var_dump($listaAnios);
                            ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content ">
                            <?php
                            $resAnios = mysqli_query($conexion, $sqlAnios);
                            while ($row = $resAnios->fetch_assoc()) {
                                $anio = $row['anio'];
                                $activeTab = ($anio == $nowAnio) ? 'active' : '';
                                ?>
                                <div class="tab-pane <?php echo $activeTab; ?>" id="tab<?php echo $anio; ?>">
                                    <ul class="nav nav-pills mb-3 justify-content-center">
                                        <?php
                                        for ($mes = 1; $mes <= 12; $mes++) {
                                            $mesActual = date('m');
                                            $activeMes = ($mes == $mesActual) ? 'active' : '';
                                            echo "<li class='nav-item'><a class='nav-link $activeMes' data-toggle='pill' href='#pills-mes$mes$anio'><b>" . mesEspañol2($mes) . "</b></a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                        for ($mes = 1; $mes <= 12; $mes++) {
                                            $activeMesTab = ($mes == $mesActual) ? 'show active' : '';
                                            $mesAnio = sprintf("$anio-%02d", $mes);
                                            ?>
                                            <div class="tab-pane fade <?php echo $activeMesTab; ?>" id="pills-mes<?php echo $mes . $anio; ?>">

                                                <div class="row justify-content-center">

                                                    <div class="col-12">
                                                        <div class="card-header text-center" style="background-color: indigo;color: white">
                                                            <h5>Calendario: Mes: <?php echo mesEspañol2($mes); ?> - Año: <?php echo $anio; ?></h5>

                                                        </div>
                                                        <div class="card-body">
                                                            <?php
                                                            // Obtener el primer día del mes y el número de días del mes
                                                            $primerDiaMes = mktime(0, 0, 0, $mes, 1, $anio); // Primer día del mes (timestamp)
                                                            $numeroDiasMes = date('t', $primerDiaMes); // Número total de días en el mes
                                                            $diaDeSemana = date('w', $primerDiaMes); // Día de la semana en que empieza el mes (0 = domingo)

                                                            // Ajustar para que lunes sea 0 en lugar de domingo
                                                            $diaDeSemana = ($diaDeSemana + 6) % 7; // 0 (domingo) -> 6, 1 (lunes) -> 0, ..., 6 (sábado) -> 5

                                                            // Días de la semana en español
                                                            $diasSemana = array("Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom");

                                                            // Iniciar el calendario
                                                            echo '<table style="border: 2px solid black; border-collapse: collapse;" class="table table-bordered">';
                                                            echo '<thead><tr>';
                                                            foreach ($diasSemana as $dia) {
                                                                echo "<th style='border: 1px solid black; text-align: center; background-color: blueviolet'>$dia</th>";
                                                            }
                                                            echo '</tr></thead>';
                                                            echo '<tbody><tr>';

                                                            // Crear celdas vacías antes del primer día del mes
                                                            for ($j = 0; $j < $diaDeSemana; $j++) {
                                                                echo '<td style="border: 1px solid black;"></td>';
                                                            }

                                                            $cantidadTotalMes = 0;

                                                            // Llenar los días del mes
                                                            for ($dia = 1; $dia <= $numeroDiasMes; $dia++) {
                                                                // Nueva fila al comenzar un lunes
                                                                if (($diaDeSemana + $dia - 1) % 7 == 0 && $dia != 1) {
                                                                    echo '</tr><tr>';
                                                                }

                                                                // Formatear la fecha para la consulta SQL
                                                                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);

                                                                // Realizar la consulta para contar tickets
                                                                $query = "SELECT COUNT(*) as total FROM ticket_sigh WHERE fechaSolicTicketSIGH LIKE '%$fecha%' AND (nomTecnicoTicketSIGH NOT LIKE ('%Pamela Vicencio Carvacho%') AND nomTecnicoTicketSIGH NOT LIKE ('%Estefano Villaseca%') AND nomTecnicoTicketSIGH NOT LIKE ('%Vicente Donoso%'))";
                                                                $resultado = mysqli_query($conexion, $query); // Asegúrate de tener una conexión activa
                                                                $row = mysqli_fetch_assoc($resultado);
                                                                $cantidadTickets = $row['total'];


                                                                if ($cantidadTickets < 1) {
                                                                    $cantidadTickets = 0;
                                                                }

                                                                $cantidadTotalMes = $cantidadTotalMes + $cantidadTickets;

                                                                ?>
                                                                <td style="border: 1px solid black; text-align: center;">
                                                                    <?php echo '<b>' . $dia . '</b><br><br>Cantidad Tickets: <b>' . $cantidadTickets . '</b>'; ?>
                                                                </td>
                                                                <?php
                                                            }

                                                            // Completar las celdas vacías al final del mes
                                                            while (($diaDeSemana + $dia - 1) % 7 != 0) {
                                                                echo '<td style="border: 1px solid black;"></td>';
                                                                $dia++;
                                                            }

                                                            echo '</tr></tbody>';
                                                            echo '</table>';

                                                            ?>

                                                        </div>
                                                        <!-- Ahora mostramos el total aquí después de haber calculado los tickets -->
                                                        <div class="card-footer text-center" style="background-color: indigo;color: white">
                                                            <h5>Total de Tickets: <?php echo $cantidadTotalMes; ?></h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <hr>

                                                <div class="row justify-content-center">


                                                    <?php
                                                    $sqlDepto = "SELECT * FROM departamentos 
                 WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional' 
                 AND nomDepartamento NOT LIKE 'Informática' 
                 AND nomDepartamento NOT LIKE 'Control Centralizado' 
                 ORDER BY nomDepartamento ASC";
                                                    $resDepto = mysqli_query($conexion, $sqlDepto);
                                                    $colores = ['card-success', 'card-purple', 'card-info', 'card-danger', 'card-orange', 'card-yellow'];
                                                    $numCard = 0;

                                                    while ($rowDepto = $resDepto->fetch_assoc()) {
                                                        $color = $colores[$numCard % count($colores)];
                                                        $idDepto = $rowDepto['idDepartamento'];
                                                        $nomDepto = $rowDepto['nomDepartamento'];
                                                        ?>
                                                        <div class="col-12 col-md-6 h-100">
                                                            <div class="card <?php echo $color; ?> collapsed-card">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">&nbsp;&nbsp;<b><?php echo $nomDepto; ?></b></h3>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-hover">
                                                                        <thead style="font-size: 12px">
                                                                        <tr>
                                                                            <th class="text-center">PERSONAL</th>
                                                                            <th class="text-center">CANTIDAD DE TICKETS</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody style="font-size: 12px">
                                                                        <?php
                                                                        $sqlPersonal = "SELECT rutPersonal, nomPersonal, cargoPersonal FROM personal 
                                        WHERE departamentoPersonal = $idDepto";
                                                                        $resPersonal = mysqli_query($conexion, $sqlPersonal);
                                                                        $totalDepto = 0;
                                                                        $rutsProcesados = []; // Array para almacenar RUTs procesados

                                                                        // Agregar personal
                                                                        while ($rowPersonal = $resPersonal->fetch_assoc()) {
                                                                            $nomPersonal = $rowPersonal['nomPersonal'];
                                                                            $cargoPersonal = $rowPersonal['cargoPersonal'];
                                                                            $rutPersonal = substr_replace($rowPersonal['rutPersonal'], '-', 8, 0);

                                                                            // Verificar si el RUT ya fue procesado
                                                                            if (!in_array($rutPersonal, $rutsProcesados)) {
                                                                                $sqlTickets = "SELECT COUNT(*) as total 
                                               FROM ticket_sigh 
                                               WHERE rutTecnicoTicketSIGH = '$rutPersonal' 
                                               AND fechaSolicTicketSIGH BETWEEN '$mesAnio-01 00:00:00' AND '$mesAnio-31 23:59:59'";
                                                                                $totalTickets = mysqli_query($conexion, $sqlTickets)->fetch_assoc()['total'];
                                                                                $totalDepto += $totalTickets;
                                                                                echo "<tr><td class='text-center'>$nomPersonal ($cargoPersonal)</td><td class='text-center'>$totalTickets</td></tr>";
                                                                                $rutsProcesados[] = $rutPersonal; // Agregar a array de RUTs procesados
                                                                            }
                                                                        }

                                                                        // Jefes técnicos
                                                                        $sqlJefes = "SELECT rutJefes, nomJefe, cargoJefe FROM jefes_tecnicos 
                                     WHERE idDepartamento = $idDepto 
                                     AND nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%'";
                                                                        $resJefes = mysqli_query($conexion, $sqlJefes);

                                                                        // Agregar jefes
                                                                        while ($rowJefes = $resJefes->fetch_assoc()) {
                                                                            $nomJefe = $rowJefes['nomJefe'];
                                                                            $cargoJefe = $rowJefes['cargoJefe'];
                                                                            $rutJefe = substr_replace($rowJefes['rutJefes'], '-', 8, 0);

                                                                            // Verificar si el RUT ya fue procesado
                                                                            if (!in_array($rutJefe, $rutsProcesados)) {
                                                                                $sqlTicketsJefe = "SELECT COUNT(*) as total 
                                                   FROM ticket_sigh 
                                                   WHERE rutTecnicoTicketSIGH = '$rutJefe' 
                                                   AND fechaSolicTicketSIGH BETWEEN '$mesAnio-01 00:00:00' AND '$mesAnio-31 23:59:59'";
                                                                                $totalTicketsJefe = mysqli_query($conexion, $sqlTicketsJefe)->fetch_assoc()['total'];
                                                                                $totalDepto += $totalTicketsJefe;
                                                                                echo "<tr><td class='text-center'>$nomJefe ($cargoJefe)</td><td class='text-center'>$totalTicketsJefe</td></tr>";
                                                                                $rutsProcesados[] = $rutJefe; // Agregar a array de RUTs procesados
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td class="text-center">Total</td>
                                                                            <td class="text-center"><?php echo $totalDepto; ?></td>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $numCard++;
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                            <?php
                                        }
                                        ?>
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


