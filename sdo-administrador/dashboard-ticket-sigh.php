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
    <style>
        /* Colores para tabs de meses */
        .nav-meses-sigh .nav-link {
            color: #020000 !important; /* gris inicial */
            background-color: #f8f9fa;
            font-weight: 600;
            border: 1px solid transparent;
            margin-right: 2px;
            border-radius: .25rem;
            transition: all 0.3s ease;
        }

        .nav-meses-sigh .nav-link:hover {
            background-color: #e2e6ea;
        }

        .nav-meses-sigh .nav-link.active {
            background-color: #6f42c1 !important; /* púrpura Bootstrap */
            color: white !important;
            border-color: #6f42c1;
            font-weight: bold;
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
                                <button class="btn btn-block btn-primary" onclick="actualizarTickets()">
                                    <i class="fas fa-arrow-alt-circle-down"></i> Actualizar Tickets SIGH
                                </button>
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
                                        <button type="submit" class="btn btn-success" id="visualizarBtn">
                                            <i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar Informe
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                                        </button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>

                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y');

                // Conexión a BD
                include("../conexion.php");

                // Obtener años disponibles
                $sqlAnios = "SELECT DISTINCT YEAR(fechaSolicTicketSIGH) AS anio FROM ticket_sigh ORDER BY fechaSolicTicketSIGH ASC;";
                $resAnios = mysqli_query($conexion, $sqlAnios);

                $listaAnios = [];
                while ($row = $resAnios->fetch_assoc()) {
                    $listaAnios[] = $row['anio'];
                }

                // Nombres de meses
                $nombresMeses = [
                    1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
                    5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
                    9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
                ];
                ?>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex p-0 bg-secondary">
                            <h3 class="card-title p-3 text-white">Dashboard de Información</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <?php foreach ($listaAnios as $anio): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($anio == $nowAnio) ? 'active' : '' ?>" style="color:black" href="#tab<?= $anio ?>" data-toggle="tab"><?= $anio ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content mt-3">
                                <?php foreach ($listaAnios as $anio): ?>
                                    <div class="tab-pane fade <?= ($anio == $nowAnio) ? 'active show' : '' ?>" id="tab<?= $anio ?>">
                                        <!-- Subtabs Meses -->
                                        <ul class="nav nav-tabs mb-3 nav-meses-sigh">
                                            <?php foreach ($nombresMeses as $numMes => $nombreMes): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link mes-tab <?= ($anio == $nowAnio && $numMes == date('n')) ? 'active' : '' ?>"

                                                            data-toggle="tab"
                                                            href="#mes<?= $anio . $numMes ?>"
                                                            data-anio="<?= $anio ?>"
                                                            data-mes="<?= $numMes ?>">
                                                        <?= $nombreMes ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <!-- Contenido por mes -->
                                        <div class="tab-content">
                                            <?php foreach ($nombresMeses as $numMes => $nombreMes): ?>
                                                <div class="tab-pane text-black fade <?= ($anio == $nowAnio && $numMes == date('n')) ? 'active show' : '' ?>"
                                                        id="mes<?= $anio . $numMes ?>">
                                                    <div class="loading-spinner text-center my-3">
                                                        <div class="spinner-border text-info" role="status"></div>
                                                        <p class="mt-2">Cargando calendario...</p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        // Cargar inicial (mes actual)
                        const anioActual = <?= $nowAnio ?>;
                        const mesActual = <?= date('n') ?>;
                        cargarCalendario(anioActual, mesActual);

                        // Cuando cambias de pestaña de mes
                        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                            const anio = $(this).data("anio");
                            const mes = $(this).data("mes");
                            const contenedorID = "#mes" + anio + mes;

                            // Evitar recargar si ya fue cargado
                            if (!$(contenedorID).data("loaded")) {
                                cargarCalendario(anio, mes);
                                $(contenedorID).data("loaded", true);
                            }
                        });

                        function cargarCalendario(anio, mes) {
                            const contenedor = $('#mes' + anio + mes);
                            $.ajax({
                                url: '/sdo-includes/tickets_cargar_calendario.php',
                                type: 'POST',
                                data: { anio: anio, mes: mes },
                                success: function (respuesta) {
                                    contenedor.html(respuesta);
                                    $('#tabla_' + anio + '_' + mes).DataTable({
                                        responsive: true,
                                        pageLength: 10,
                                        language: {
                                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                                        }
                                    });
                                },
                                error: function () {
                                    contenedor.html('<p class="text-danger">Error al cargar la tabla.</p>');
                                }
                            });
                        }
                    });
                </script>



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


