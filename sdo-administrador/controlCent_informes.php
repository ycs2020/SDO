<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Informes de Plataforma SDO</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">


    <script>
        function cambiarEstadoInformeHT() {
            var tipo = document.getElementById("tipoHT").value;
            var divMensual = document.querySelector(".mensualHT");
            var divAnio = document.querySelector(".anioHT");
            var divTrimestral = document.querySelector(".trimestralHT");

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

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Informes de Plataforma SDO</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">


                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="row">
                                    <?php
                                    $botones = [
                                        [
                                            'icon' => 'fas fa-file-pdf',
                                            'text' => 'Tickets SIGH',
                                            'modal' => '#modalTicketsSIGH',
                                            'type' => 'modal'
                                        ],
                                        [
                                            'icon' => 'fas fa-file-pdf',
                                            'text' => 'Hojas Tecnicas',
                                            'modal' => '#modalHT',
                                            'type' => 'modal'
                                        ],
                                        [
                                            'icon' => 'fa fa-download',
                                            'text' => 'Estadistica de Actas',
                                            'action' => '../sdo-pdf/SDO-Estadistica-Actas.php',
                                            'type' => 'form'
                                        ],
                                        [
                                            'icon' => 'fa fa-gas-pump',
                                            'text' => 'Reporte de Combustible',
                                            'action' => '../sdo-pdf/SDO-Reporte-Combustible.php',
                                            'type' => 'form'
                                        ],
                                        [
                                            'icon' => 'fa fa-shopping-cart',
                                            'text' => 'Solicitudes de Compra',
                                            'action' => '../sdo-pdf/SDO-Informe-SC-SDO.php',
                                            'type' => 'form'
                                        ],
//                                        [
//                                            'icon' => 'fas fa-file-pdf',
//                                            'text' => 'Valor Hojas Tecnicas',
//                                            'modal' => '#valorHT',
//                                            'type' => 'modal'
//                                        ],
                                        [
                                            'icon' => 'fa fa-file-pdf',
                                            'text' => 'Mediciones Tanque Indura',
                                            'action' => '../sdo-pdf/SDO-Documentacion.php',
                                            'type' => 'form',
                                            'cantidadHidden' => 2,
                                            'hidden1' => 'SDO-Medicion-Indura.php', //Ruta Complementaria
                                            'hidden2' => 'Registro de Mediciones de Tanque Indura', //Titulo
                                        ],
                                        [
                                            'icon' => 'fas fa-file-pdf',
                                            'text' => 'Enlace de SC - HT',
                                            'modal' => '#valorHTSC',
                                            'type' => 'modal'
                                        ],
                                        [
                                            'icon' => 'fa fa-file-pdf',
                                            'text' => 'Pulsadores de Cama Reemplazados',
                                            'action' => '../sdo-pdf/SDO-Pulsadores-NX0881-Reemplazados.php',
                                            'type' => 'form'
                                        ],
                                        [
                                            'icon' => 'fas fa-file-pdf',
                                            'text' => 'Informe de Control de Acceso',
                                            'modal' => '#modalControlAcceso',
                                            'type' => 'modal'
                                        ]
                                        // Puedes agregar más botones aquí si es necesario
                                    ];

                                    $colores = ['primary', 'success', 'warning', 'secondary'];
                                    ?>

                                    <?php foreach ($botones as $index => $boton): ?>
                                        <?php $color = $colores[$index % count($colores)]; ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                            <?php if ($boton['type'] == 'modal'): ?>
                                                <button type="button" class="btn btn-block btn-<?php echo $color; ?>" data-toggle="modal" data-target="<?php echo $boton['modal']; ?>">
                                                    <i class="<?php echo $boton['icon']; ?>"></i><br><?php echo $boton['text']; ?>
                                                </button>
                                            <?php else: ?>
                                                <form method="post" action="<?php echo $boton['action']; ?>" target="_blank">
                                                    <?php
                                                    // Añadir los campos ocultos
                                                    for ($i = 1; $i <= $boton['cantidadHidden']; $i++) {
                                                        if (isset($boton["hidden$i"])) {
                                                            echo '<input type="hidden" name="hidden' . $i . '" value="' . $boton["hidden$i"] . '">';
                                                        }
                                                    }
                                                    ?>
                                                    <input type="hidden" name="cantidadHidden" value="<?php echo $boton['cantidadHidden'] ?>">
                                                    <button type="submit" style="float: right;" class="btn btn-block btn-<?php echo $color; ?>">
                                                        <i class="<?php echo $boton['icon']; ?>"></i><br><?php echo $boton['text']; ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>



                                    <!-- MODAL INFORME TICKET SIGH -->
                                    <div class="modal fade" id="modalTicketsSIGH" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="myModalLabel">Generar Informe Ticket SIGH</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                                $ipCliente = $_SERVER['REMOTE_ADDR'];

                                                if($ipCliente == '10.69.250.151'){
                                                    $rutaInformeTicket = '../sdo-pdf/SDO-Informe-Ticketera-SIGH-5.php';
                                                }else{
                                                    $rutaInformeTicket = '../sdo-pdf/SDO-Informe-Ticketera-SIGH2.php';
                                                }
                                                ?>
                                                <form action="<?php echo $rutaInformeTicket; ?>" target="_blank" method="POST">
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
                                    <!-- FIN MODAL INFORME TICKET SIGH -->


                                    <!-- MODAL INFORME HT -->
                                    <div class="modal fade" id="modalHT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="myModalLabel">Generar Informe Hojas Tecnicas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                                $ipCliente = $_SERVER['REMOTE_ADDR'];

                                                if($ipCliente == '10.69.250.151'){
                                                    $rutaInformeHT = '../sdo-pdf/SDO-Informe-HT-Completo-2.php';
                                                }else{
                                                    $rutaInformeHT = '../sdo-pdf/SDO-Informe-HT-Completo.php';
                                                }
                                                ?>
                                                <form action="<?php echo $rutaInformeHT ?>" target="_blank" method="POST">
                                                    <div class="modal-body">
                                                        <label>Escoga una fecha</label>
                                                        <select name="tipoHT" id="tipoHT" onchange="cambiarEstadoInformeHT()" class="form-control">
                                                            <option selected disabled>Seleccione</option>
                                                            <option value="Anio">Anual</option>
                                                            <option value="Mensual">Mensual</option>
                                                            <option value="Trimestral">Trimestral</option>
                                                        </select>
                                                        <div class="mensualHT" style="display: none">
                                                            <br>
                                                            <input type="month" id="meses" name="meses" class="form-control">
                                                        </div>
                                                        <div class="anioHT" style="display: none">
                                                            <br>
                                                            <label>Año:</label>
                                                            <select name="anio" id="anio" class="form-control">
                                                                <option value="" selected>Seleccione</option>
                                                                <?php
                                                                $sqlAño = "SELECT DISTINCT YEAR(fechaOrden) AS año FROM ordenes_trabajos_general;";
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
                                                        <div class="trimestralHT" style="display: none">
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
                                                                $sqlAño = "SELECT DISTINCT YEAR(fechaOrden) AS año FROM ordenes_trabajos_general;";
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
                                    <!-- FIN MODAL INFORME HT -->
                                    <div class="modal fade" id="valorHT" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="exampleModalLabel">Valores de Hojas Técnicas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="#" method="POST" target="_blank" id="formulario">
                                                    <div class="modal-body">
                                                        <label>Seleccione una opción:</label>
                                                        <select onchange="tipoArchivoElegido()" name="opciones" id="opciones" required class="form-control">
                                                            <option value="" selected disabled>Seleccione</option>
                                                            <option value="Excel">Excel</option>
                                                            <option value="PDF">PDF</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="valorHTSC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="exampleModalLabel">Enlace de SC - HT</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="#" method="POST" target="_blank" id="formulariovalorhtsc">
                                                    <div class="modal-body">
                                                        <label>Seleccione una opción:</label>
                                                        <select onchange="tipoArchivoElegidoValorHTSC()" name="opcionesvalorhtcs" id="opcionesvalorhtcs" required class="form-control">
                                                            <option value="" selected disabled>Seleccione</option>
                                                            <option value="Excel">Excel</option>
                                                            <option value="PDF">PDF</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalControlAcceso" tabindex="-1" role="dialog" aria-labelledby="modalControlAcceso" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title" id="modalControlAcceso">Seleccionar Periodo de Tiempo</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="formInformeAcceso" action="../sdo-pdf/SDO-Informe-Control-Acceso2.php" method="post" target="_blank">
                                                    <div class="modal-body">
                                                        <label>Periodo</label>
                                                        <select name="periodo" id="periodo" class="form-control" required>
                                                            <option value="" selected disabled>Seleccione</option>
                                                            <option value="mensual">Mensual</option>
                                                            <option value="anual">Anual</option>
                                                            <option value="todo">Todo</option>
                                                        </select>

                                                        <!-- MENSUAL -->
                                                        <div id="selectMensual" style="display: none; margin-top: 10px;">
                                                            <label>Mes</label>
                                                            <select class="form-control" name="mes" id="mes">
                                                                <option value="">Seleccione mes</option>
                                                                <?php
                                                                $meses = [
                                                                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                                                    '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                                                    '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                                                                ];
                                                                foreach ($meses as $num => $nombre) {
                                                                    echo "<option value=\"$num\">$nombre</option>";
                                                                }
                                                                ?>
                                                            </select>

                                                            <label style="margin-top: 10px;">Año</label>
                                                            <select class="form-control" name="anioMensual" id="anioMensual">
                                                                <option value="">Seleccione año</option>
                                                                <?php
                                                                include_once('../sdo-funciones/conexion.php');
                                                                $queryAnios = "SELECT DISTINCT YEAR(fecha_creacion) as anio FROM permisos_revisados ORDER BY anio DESC";
                                                                $resAnios = mysqli_query($conexion, $queryAnios);
                                                                while ($row = mysqli_fetch_assoc($resAnios)) {
                                                                    echo "<option value=\"{$row['anio']}\">{$row['anio']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <!-- ANUAL -->
                                                        <div id="selectAnual" style="display: none; margin-top: 10px;">
                                                            <label>Año</label>
                                                            <select class="form-control" name="anio" id="anioAnual">
                                                                <option value="">Seleccione año</option>
                                                                <?php
                                                                mysqli_data_seek($resAnios, 0);
                                                                while ($row = mysqli_fetch_assoc($resAnios)) {
                                                                    echo "<option value=\"{$row['anio']}\">{$row['anio']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;Generar Informe</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                    </div>
                                                </form>


                                                <script>
                                                    const periodo = document.getElementById('periodo');
                                                    const form = document.getElementById('formInformeAcceso');

                                                    periodo.addEventListener('change', function () {
                                                        const valor = this.value.toLowerCase();

                                                        document.getElementById('selectMensual').style.display = 'none';
                                                        document.getElementById('selectAnual').style.display = 'none';

                                                        if (valor === 'mensual') {
                                                            document.getElementById('selectMensual').style.display = 'block';
                                                        } else if (valor === 'anual') {
                                                            document.getElementById('selectAnual').style.display = 'block';
                                                        }
                                                    });

                                                    form.addEventListener('submit', function (e) {
                                                        const tipo = periodo.value;

                                                        if (tipo === 'mensual') {
                                                            const mes = document.getElementById('mes').value;
                                                            const anioMensual = document.getElementById('anioMensual').value;

                                                            if (!mes || !anioMensual) {
                                                                e.preventDefault();
                                                                Swal.fire({
                                                                    icon: 'warning',
                                                                    title: 'Faltan datos',
                                                                    text: 'Debes seleccionar un mes y un año para el informe mensual.',
                                                                    confirmButtonColor: '#3085d6',
                                                                });
                                                            }
                                                        }

                                                        if (tipo === 'anual') {
                                                            const anioAnual = document.getElementById('anioAnual').value;

                                                            if (!anioAnual) {
                                                                e.preventDefault();
                                                                Swal.fire({
                                                                    icon: 'warning',
                                                                    title: 'Falta el año',
                                                                    text: 'Debes seleccionar un año para el informe anual.',
                                                                    confirmButtonColor: '#3085d6',
                                                                });
                                                            }
                                                        }
                                                    });
                                                </script>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    function tipoArchivoElegido() {

        tipoArchivo = document.getElementById("opciones").value;
        formulario = document.getElementById("formulario");
        console.log(tipoArchivo);
        console.log(formulario);
        if (tipoArchivo == 'Excel') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-VALOR-HT-EXCEL.php')
        } else if (tipoArchivo == 'PDF') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-VALOR-HT.php')
        }
    }
</script>
<script>
    function tipoArchivoElegidoValorHTSC() {

        tipoArchivo = document.getElementById("opcionesvalorhtcs").value;
        formulario = document.getElementById("formulariovalorhtsc");
        //console.log(tipoArchivo);
        //console.log(formulario);
        if (tipoArchivo == 'Excel') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-VALOR-HT-SC-EXCEL.php')
        } else if (tipoArchivo == 'PDF') {
            formulario.setAttribute('action', '../sdo-pdf/SDO-Informe-SC-Materiales.php')
        }
    }
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>