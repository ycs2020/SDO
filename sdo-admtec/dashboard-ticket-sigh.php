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
        <script>
            function cambiarEstado() {
                var tipo = document.getElementById("tipo").value;
                var divMensual = document.querySelector(".mensual");
                var divAnio = document.querySelector(".anio");
                var divTrimestral = document.querySelector(".trimestral");

                if (tipo === "Mensual") {
                    divMensual.style.display = "block";
                    divAnio.style.display = "none";
                    divTrimestral.style.display = "none";
                } else if (tipo === "Anio") {
                    divMensual.style.display = "none";
                    divAnio.style.display = "block";
                    divTrimestral.style.display = "none";
                } else if (tipo === "Trimestral") {
                    divMensual.style.display = "none";
                    divAnio.style.display = "none";
                    divTrimestral.style.display = "block";
                } else {
                    divMensual.style.display = "none";
                    divAnio.style.display = "none";
                    divTrimestral.style.display = "none";
                }
            }
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
            include '../sdo-templates/admtec-sidebar.php';
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
                                        <form action="<?php echo $valorDesarrollo; ?>" target="_blank" method="POST">
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
                                                    <input type="month" id="meses" name="meses" class="form-control">
                                                </div>
                                                <div class="anio" style="display: none">
                                                    <br>
                                                    <label>Año:</label>
                                                    <select name="anio" id="anio" class="form-control">
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
                                                    <select name="trimestral" id="trimestral" class="form-control">
                                                        <option value="" selected>Seleccione</option>

                                                        <option value="1er Trimestre">1er Trimestre</option>
                                                        <option value="2do Trimestre">2do Trimestre</option>
                                                        <option value="3er Trimestre">3er Trimestre</option>
                                                        <option value="4to Trimestre">4to Trimestre</option>
                                                    </select>

                                                    <br>
                                                    <label>Año:</label>
                                                    <select name="anioTrim" id="anioTrim" class="form-control">
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
                                                <button type="submit" class="btn btn-primary">Visualizar Informe</button>
                                            </div>
                                        </form>
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
                                                                $anioT = date('Y', time());

                                                                $titulosCancelados1 = "Cantidad de Tickets Cancelados del SIGH en Año " . $anio . " por Meses";

                                                                $titulo1 = "Cantidad de Tickets del SIGH en Año " . $anio . " por Departamento";

                                                                $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                                $titulosCancelados2 = mb_convert_case($titulosCancelados1, MB_CASE_UPPER, 'utf-8');

                                                                ?>
                                                                <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead class="bg-success">
                                                                        <tr>
                                                                            <th scope="col">Departamento</th>
                                                                            <?php
                                                                            for ($mes = 1; $mes < 13; $mes++) {
                                                                                ?>
                                                                                <th scope="col" class="text-center"><?php echo mesEspañol2($mes); ?></th>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anio; ?></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="bg-white">
                                                                        <?php
                                                                        if ($tipoUsuarioVer == 'unico') {
                                                                            $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                                        } else if ($tipoUsuarioVer == 'todos') {
                                                                            $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                                        }
                                                                        $resDeptoT = mysqli_query($conexion, $sqlDeptoT);


                                                                        $totalMes1 = 0;
                                                                        $totalMes2 = 0;
                                                                        $totalMes3 = 0;
                                                                        $totalMes4 = 0;
                                                                        $totalMes5 = 0;
                                                                        $totalMes6 = 0;
                                                                        $totalMes7 = 0;
                                                                        $totalMes8 = 0;
                                                                        $totalMes9 = 0;
                                                                        $totalMes10 = 0;
                                                                        $totalMes11 = 0;
                                                                        $totalMes12 = 0;
                                                                        $cantPorDepto = 0;
                                                                        $cantPorMesTotal = 0;





                                                                        $arrayCantTotal = array();
/*
                                                                        $totalMes1 = $totalMes1 + $totalCancelados1;
                                                                        $totalMes2 = $totalMes2 + $totalCancelados2;
                                                                        $totalMes3 = $totalMes3 + $totalCancelados3;
                                                                        $totalMes4 = $totalMes4 + $totalCancelados4;
                                                                        $totalMes5 = $totalMes5 + $totalCancelados5;
                                                                        $totalMes6 = $totalMes6 + $totalCancelados6;
                                                                        $totalMes7 = $totalMes7 + $totalCancelados7;
                                                                        $totalMes8 = $totalMes8 + $totalCancelados8;
                                                                        $totalMes9 = $totalMes9 + $totalCancelados9;
                                                                        $totalMes10 = $totalMes10 + $totalCancelados10;
                                                                        $totalMes11 = $totalMes11 + $totalCancelados11;
                                                                        $totalMes12 = $totalMes12 + $totalCancelados12;
                                                                        */
                                                                        while ($rowDeptoT = $resDeptoT->fetch_assoc()) {
                                                                            $cantTotalFinal = 0;
                                                                            $idDepartamentoT = $rowDeptoT['idDepartamento'];
                                                                            $sqlBuscarJefes = "SELECT jt.idJefe, jt.rutJefes AS nomEmpleado FROM jefes_tecnicos jt WHERE nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND idDepartamento = '".$idDepartamentoT."' UNION SELECT p.idPersonal AS idJefe, p.rutPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = '".$idDepartamentoT."';";
                                                                            $resBuscarJefes = mysqli_query($conexion, $sqlBuscarJefes);
                                                                            while($rowBuscarJefes = $resBuscarJefes->fetch_assoc()){
                                                                                $rut = $rowBuscarJefes['nomEmpleado'];
                                                                                $rut_con_guion = substr_replace($rut, '-', 8, 0);
                                                                                $contarHojasTecnicasAnio = contarTicketSIGHanio($rut_con_guion, $anio);
                                                                            }
                                                                            if ($idDepartamentoT == 11 || $idDepartamentoT == 9) {
                                                                            }else{
                                                                                $cantTotalDepto = 0;
                                                                                $nomDepartamentoT = $rowDeptoT['nomDepartamento'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td scope="row" class="font-weight-bold">
                                                                                        <?php echo $nomDepartamentoT; ?>
                                                                                    </td>
                                                                                    <?php
                                                                                    for ($i = 1; $i < 13; $i++) {
                                                                                         ?>
                                                                                        <td class="text-center">
                                                                                            <?php
                                                                                            $mesHT = '';
                                                                                            if ($i < 13) {
                                                                                                $mesHT = '0' . $i;
                                                                                            }
                                                                                            $fechaT = $anio. '-' . $mesHT;
                                                                                            $totalHojasTecnicas = 0;

                                                                                            $sqlBuscarJefesHT = "SELECT jt.idJefe, jt.rutJefes AS nomEmpleado FROM jefes_tecnicos jt WHERE nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND idDepartamento = '".$idDepartamentoT."' UNION SELECT p.rutPersonal AS idJefe, p.rutPersonal AS nomEmpleado FROM personal p WHERE departamentoPersonal = '".$idDepartamentoT."';";
                                                                                            $resBuscarJefesHT = mysqli_query($conexion, $sqlBuscarJefesHT);
                                                                                            while($rowBuscarJefesHT = $resBuscarJefesHT->fetch_assoc()) {
                                                                                                $rutHT = $rowBuscarJefesHT['nomEmpleado'];
                                                                                                $rut_con_guionHT = substr_replace($rutHT, '-', 8, 0);
                                                                                                $contarHojasTecnicasMesAnio = contarTicketSIGHMesAnio($rut_con_guionHT, $fechaT);
                                                                                                if ($contarHojasTecnicasMesAnio > 0) {
                                                                                                    $cantTotalDepto = $cantTotalDepto + $contarHojasTecnicasMesAnio;
                                                                                                    $totalHojasTecnicas = $totalHojasTecnicas + $contarHojasTecnicasMesAnio;
                                                                                                }

                                                                                            }
                                                                                            echo $totalHojasTecnicas;
                                                                                            ?>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($i == 1) {
                                                                                            $totalMes1 = $totalMes1 + $totalHojasTecnicas;
                                                                                        } else if ($i == 2) {
                                                                                            $totalMes2 = $totalMes2 + $totalHojasTecnicas;
                                                                                        } else if ($i == 3) {
                                                                                            $totalMes3 = $totalMes3 + $totalHojasTecnicas;
                                                                                        } else if ($i == 4) {
                                                                                            $totalMes4 = $totalMes4 + $totalHojasTecnicas;
                                                                                        } else if ($i == 5) {
                                                                                            $totalMes5 = $totalMes5 + $totalHojasTecnicas;
                                                                                        } else if ($i == 6) {
                                                                                            $totalMes6 = $totalMes6 + $totalHojasTecnicas;
                                                                                        } else if ($i == 7) {
                                                                                            $totalMes7 = $totalMes7 + $totalHojasTecnicas;
                                                                                        } else if ($i == 8) {
                                                                                            $totalMes8 = $totalMes8 + $totalHojasTecnicas;
                                                                                        } else if ($i == 9) {
                                                                                            $totalMes9 = $totalMes9 + $totalHojasTecnicas;
                                                                                        } else if ($i == 10) {
                                                                                            $totalMes10 = $totalMes10 + $totalHojasTecnicas;
                                                                                        } else if ($i == 11) {
                                                                                            $totalMes11 = $totalMes11 + $totalHojasTecnicas;
                                                                                        } else if ($i == 12) {
                                                                                            $totalMes12 = $totalMes12 + $totalHojasTecnicas;
                                                                                        }

                                                                                            $cantTotalFinal = $cantTotalFinal + $cantTotalDepto;

                                                                                    }
                                                                                    ?>
                                                                                    <td class="text-center text-bold">
                                                                                        <?php echo $cantTotalDepto; ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        array_push($arrayCantTotal, $totalMes1);
                                                                        array_push($arrayCantTotal, $totalMes2);
                                                                        array_push($arrayCantTotal, $totalMes3);
                                                                        array_push($arrayCantTotal, $totalMes4);
                                                                        array_push($arrayCantTotal, $totalMes5);
                                                                        array_push($arrayCantTotal, $totalMes6);
                                                                        array_push($arrayCantTotal, $totalMes7);
                                                                        array_push($arrayCantTotal, $totalMes8);
                                                                        array_push($arrayCantTotal, $totalMes9);
                                                                        array_push($arrayCantTotal, $totalMes10);
                                                                        array_push($arrayCantTotal, $totalMes11);
                                                                        array_push($arrayCantTotal, $totalMes12);

                                                                        ?>
                                                                        <tr class="bg-info">
                                                                            <th scope="row">Totales</th>

                                                                            <?php
                                                                            for ($i = 0; $i < 12; $i++) {
                                                                                ?>
                                                                                <td class="text-center text-bold"><?php echo $arrayCantTotal[$i] ?></td>
                                                                                <?php
                                                                                $cantPorMesTotal = $cantPorMesTotal + $arrayCantTotal[$i];
                                                                            }
                                                                            ?>
                                                                            <td class="text-center text-bold"><?php echo $cantPorMesTotal ?></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <p class="badge-success w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulosCancelados2?></p>
                                                                <br>
                                                                <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead class="bg-gradient-info">
                                                                    <tr>
                                                                        <th scope="col"></th>
                                                                        <?php
                                                                        for ($mes = 1; $mes < 13; $mes++) {
                                                                            ?>
                                                                            <th scope="col" class="text-center"><?php echo mesEspañol2($mes); ?></th>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anio; ?></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody style="text-align: center; background-color: white">
                                                                    <?php
                                                                    $totalCancelados1 = 0;
                                                                    $totalCancelados2 = 0;
                                                                    $totalCancelados3 = 0;
                                                                    $totalCancelados4 = 0;
                                                                    $totalCancelados5 = 0;
                                                                    $totalCancelados6 = 0;
                                                                    $totalCancelados7 = 0;
                                                                    $totalCancelados8 = 0;
                                                                    $totalCancelados9 = 0;
                                                                    $totalCancelados10 = 0;
                                                                    $totalCancelados11 = 0;
                                                                    $totalCancelados12 = 0;

                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            Tickets Cancelados
                                                                        </td>
                                                                          <?php
                                                                          $totalCanceladosFinal = 0;
                                                                          for ($j = 1; $j <= 12; $j++) {
                                                                              $contarCancelados = contarCancelados($anio, $j);
                                                                              if ($j == 1) {
                                                                                  $totalCancelados1 = $totalCancelados1 + $contarCancelados;
                                                                              } else if ($j == 2) {
                                                                                  $totalCancelados2 = $totalCancelados2 + $contarCancelados;
                                                                              } else if ($j == 3) {
                                                                                  $totalCancelados3 = $totalCancelados3 + $contarCancelados;
                                                                              } else if ($j == 4) {
                                                                                  $totalCancelados4 = $totalCancelados4 + $contarCancelados;
                                                                              } else if ($j == 5) {
                                                                                  $totalCancelados5 = $totalCancelados5 + $contarCancelados;
                                                                              } else if ($j == 6) {
                                                                                  $totalCancelados6 = $totalCancelados6 + $contarCancelados;
                                                                              } else if ($j == 7) {
                                                                                  $totalCancelados7 = $totalCancelados7 + $contarCancelados;
                                                                              } else if ($j == 8) {
                                                                                  $totalCancelados8 = $totalCancelados8 + $contarCancelados;
                                                                              } else if ($j == 9) {
                                                                                  $totalCancelados9 = $totalCancelados9 + $contarCancelados;
                                                                              } else if ($j == 10) {
                                                                                  $totalCancelados10 = $totalCancelados10 + $contarCancelados;
                                                                              } else if ($j == 11) {
                                                                                  $totalCancelados11 = $totalCancelados11 + $contarCancelados;
                                                                              } else if ($j == 12) {
                                                                                  $totalCancelados12 = $totalCancelados12 + $contarCancelados;
                                                                              }
                                                                              ?>
                                                                              <td>
                                                                                  <?php echo $contarCancelados?>
                                                                              </td>
                                                                              <?php
                                                                          }
                                                                          $totalCanceladosFinal = $totalCancelados1 + $totalCancelados2 + $totalCancelados3 + $totalCancelados4 + $totalCancelados5 + $totalCancelados6 + $totalCancelados7 + $totalCancelados8 + $totalCancelados9 + $totalCancelados10 + $totalCancelados11 + $totalCancelados12;
                                                                          ?>
                                                                            <td>
                                                                            <?php echo $totalCanceladosFinal ?>
                                                                            </td>
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
                                                                <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-homea<?php echo $i; ?><?php echo $anio ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
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

                                                            <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?><?php echo $anio ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
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
                                                                                <canvas id="myChart<?php echo $i?><?php echo $row['anio']?>" height="89"></canvas>

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
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-6">
                                                                        <div class="card-header text-center" style="background-color: indigo;color: white" >
                                                                            <h5>Las 5 unidades clínicas con más tickets en el mes: <?php echo mesEspañol2($i)?> - Año: <?php echo $anio?></h5>
                                                                        </div>
                                                                        <div class="card-body" style="background-color: white">
                                                                            <?php
                                                                            $sqlUnidadMes = "SELECT unidadTicketSIGH,COUNT(*) AS frecuenciaUnidad FROM ticket_sigh where YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' GROUP BY unidadTicketSIGH ORDER BY COUNT(*) DESC LIMIT 5";
                                                                            $resUnidadMes = mysqli_query($conexion,$sqlUnidadMes);
                                                                            $nombresUnidad = array();
                                                                            $frecuenciasUnidad = array();
                                                                            $estadoTicketUnidad = array();
                                                                            while($rowUnidadMes = $resUnidadMes->fetch_assoc()){
                                                                                $nombresUnidad[] = $rowUnidadMes['unidadTicketSIGH'];
                                                                                $frecuenciasUnidad[] = $rowUnidadMes['frecuenciaUnidad'];
                                                                            }
                                                                            ?>
                                                                            <canvas id="myChartUnidad<?php echo $i?><?php echo $row['anio']?>" height="95"></canvas>
                                                                            <script>
                                                                                // Datos obtenidos de PHP
                                                                                const nombresUnidad<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($nombresUnidad); ?>;
                                                                                const frecuenciasUnidad<?php echo $i?><?php echo $row['anio']?> = <?php echo json_encode($frecuenciasUnidad); ?>;

                                                                                // Configurar el gráfico
                                                                                const ctxUnidad<?php echo $i?><?php echo $row['anio']?> = document.getElementById('myChartUnidad<?php echo $i?><?php echo $row['anio']?>').getContext('2d');
                                                                                const myChartUnidad<?php echo $i?><?php echo $row['anio']?> = new Chart(ctxUnidad<?php echo $i?><?php echo $row['anio']?>, {
                                                                                    type: 'bar', // Cambiar el tipo de gráfico a bar
                                                                                    data: {
                                                                                        labels: nombresUnidad<?php echo $i?><?php echo $row['anio']?>,
                                                                                        datasets: [{
                                                                                            label: 'Cantidad de Tickets por Unidad',
                                                                                            data: frecuenciasUnidad<?php echo $i?><?php echo $row['anio']?>,
                                                                                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Cambiar el color de fondo de las barras
                                                                                            borderColor: 'rgba(54, 162, 235, 1)', // Cambiar el color del borde de las barras
                                                                                            borderWidth: 1
                                                                                        }]
                                                                                    },
                                                                                    options: {
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
                                                                    <div class="col-md-12">
                                                                        <div class="card-header text-center" style="background-color: indigo;color: white" >
                                                                             <h5>Estado de los tickets de las 5 unidades clínicas</h5>
                                                                        </div>
                                                                        <div class="card-body" style="background-color: white">
                                                                            <table class="table-hover table table-bordered" style="text-align: center">
                                                                                 <thead class="bg-gradient-secondary">
                                                                                        <tr>
                                                                                            <th>Unidad</th>
                                                                                            <th>Finalizado</th>
                                                                                            <th>En Desarrollo</th>
                                                                                            <th>Pendiente</th>
                                                                                            <th>Cancelado</th>
                                                                                            <th>Programado</th>
                                                                                            <th>Total</th>
                                                                                        </tr>
                                                                                 </thead>
                                                                                 <tbody>
                                                                                    <?php
                                                                                    $cantidadTotalFinalizadas = 0;
                                                                                    $cantidadTotalDesarrollo = 0;
                                                                                    $cantidadTotalPendiente = 0;
                                                                                    $cantidadTotalCancelado = 0;
                                                                                    $cantidadTotalProgramado = 0;
                                                                                    $cantidadTOTAL = 0;
                                                                                    $sqlUnidadMesEstadoTick = "SELECT unidadTicketSIGH,COUNT(*) AS frecuenciaUnidad FROM ticket_sigh where YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' GROUP BY unidadTicketSIGH ORDER BY COUNT(*) DESC LIMIT 5";
                                                                                    $resUnidadMesEstadoTick = mysqli_query($conexion,$sqlUnidadMesEstadoTick);
                                                                                    while($rowUnidadMesEstadoTick = $resUnidadMesEstadoTick->fetch_assoc()){
                                                                                        $nomUnidad = $rowUnidadMesEstadoTick['unidadTicketSIGH']
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $nomUnidad?></td>
                                                                                            <td>
                                                                                                <?php
                                                                                                $sqlEstadoUnidad = "SELECT COUNT(*) as total FROM ticket_sigh WHERE unidadTicketSIGH = '$nomUnidad' AND estadoTicketSIGH = 'Finalizado' AND YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i'";
                                                                                                $resEstadoUnidad = mysqli_query($conexion,$sqlEstadoUnidad);
                                                                                                while($rowEstadoUnidad = $resEstadoUnidad->fetch_assoc()){
                                                                                                    $totalFinalizado = $rowEstadoUnidad['total'];
                                                                                                    $cantidadTotalFinalizadas = $cantidadTotalFinalizadas + $totalFinalizado;
                                                                                                }
                                                                                                echo $totalFinalizado;

                                                                                                $sqlEstadoUnidadDesarrollo = "SELECT COUNT(*) as total FROM ticket_sigh WHERE unidadTicketSIGH = '$nomUnidad' AND estadoTicketSIGH = 'En Desarrollo' AND YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i'";
                                                                                                $resEstadoUnidadDesarrollo = mysqli_query($conexion,$sqlEstadoUnidadDesarrollo);
                                                                                                while($rowEstadoUnidadDesarrollo = $resEstadoUnidadDesarrollo->fetch_assoc()){
                                                                                                    $totalEnDesarrollo = $rowEstadoUnidadDesarrollo['total'];
                                                                                                    $cantidadTotalDesarrollo = $cantidadTotalDesarrollo + $totalEnDesarrollo;
                                                                                                }

                                                                                                $sqlEstadoUnidadPendiente = "SELECT COUNT(*) as total FROM ticket_sigh WHERE unidadTicketSIGH = '$nomUnidad' AND estadoTicketSIGH = 'Pendiente' AND YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i'";
                                                                                                $resEstadoUnidadPendiente = mysqli_query($conexion,$sqlEstadoUnidadPendiente);
                                                                                                while($rowEstadoUnidadPendiente = $resEstadoUnidadPendiente->fetch_assoc()){
                                                                                                    $totalPendiente = $rowEstadoUnidadPendiente['total'];
                                                                                                    $cantidadTotalPendiente = $cantidadTotalPendiente + $totalPendiente;
                                                                                                }

                                                                                                $sqlEstadoUnidadCancelado = "SELECT COUNT(*) as total FROM ticket_sigh WHERE unidadTicketSIGH = '$nomUnidad' AND estadoTicketSIGH = 'Cancelado' AND YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i'";
                                                                                                $resEstadoUnidadCancelado = mysqli_query($conexion,$sqlEstadoUnidadCancelado);
                                                                                                while($rowEstadoUnidadCancelado = $resEstadoUnidadCancelado->fetch_assoc()){
                                                                                                    $totalCancelado = $rowEstadoUnidadCancelado['total'];
                                                                                                    $cantidadTotalCancelado = $cantidadTotalCancelado + $totalCancelado;
                                                                                                }

                                                                                                $sqlEstadoUnidadProgramado = "SELECT COUNT(*) as total FROM ticket_sigh WHERE unidadTicketSIGH = '$nomUnidad' AND estadoTicketSIGH = 'Programado' AND YEAR(fechaSolicTicketSIGH) = '$anio' AND MONTH(fechaSolicTicketSIGH) = '$i' ";
                                                                                                $resEstadoUnidadProgramado = mysqli_query($conexion,$sqlEstadoUnidadProgramado);
                                                                                                while($rowEstadoUnidadProgramado = $resEstadoUnidadProgramado->fetch_assoc()){
                                                                                                    $totalProgramado = $rowEstadoUnidadProgramado['total'];
                                                                                                    $cantidadTotalProgramado = $cantidadTotalProgramado + $totalProgramado;
                                                                                                }
                                                                                                $totalTodosEstados = $totalFinalizado + $totalEnDesarrollo + $totalPendiente + $totalCancelado + $totalProgramado;
                                                                                                $cantidadTOTAL = $cantidadTotalFinalizadas + $cantidadTotalDesarrollo + $cantidadTotalPendiente + $cantidadTotalCancelado + $cantidadTotalProgramado;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php
                                                                                                echo $totalEnDesarrollo;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php
                                                                                                    echo $totalPendiente;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php
                                                                                                    echo $totalCancelado;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php
                                                                                                    echo $totalProgramado;
                                                                                                ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php
                                                                                                echo $totalTodosEstados;
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                 </tbody>
                                                                                 <tfoot>
                                                                                    <th>Total</th>
                                                                                    <th><?php echo $cantidadTotalFinalizadas?></th>
                                                                                    <th><?php echo $cantidadTotalDesarrollo?></th>
                                                                                    <th><?php echo $cantidadTotalPendiente?></th>
                                                                                    <th><?php echo $cantidadTotalCancelado?></th>
                                                                                    <th><?php echo $cantidadTotalProgramado?></th>
                                                                                    <th><?php echo $cantidadTOTAL?></th>
                                                                                 </tfoot>
                                                                            </table>
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
