<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = $_SESSION['nomUsuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

    <style>

        .cantidad1 {
            background-color: blue;
            color: white;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php

    $ipCliente = $_SERVER['REMOTE_ADDR'];

    $enlace = '../sdo-templates/admtec-sidebar.php';

    if ($ipCliente == '10.69.250.151') {
        $enlace = '../sdo-templates/admtec-sidebar-2.php';
    }

    include $enlace;
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php


                if (in_array($idDepartamento, [1, 6, 13])) {
                    include_once "../sdo-includes/div_weather.php";
                }

                include_once '../sdo-includes/admtec_menu_opciones.php'

                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">


                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php

                                    if ($nomDepartamento == "Instrumentación") {

                                    ?>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_4">
                                            <?php
                                            if ($nomDepartamento == "Instrumentación") {
                                                ?>
                                                <h5>
                                                    <b>Organigrama</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="hbqp_organigrama.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-users"></i><br> Organigrama HBQP</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Turnos Control Centralizado</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="turnos_calendario.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-users"></i><br> Turnos Control Centralizado</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Memorandum de Control de Acceso</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="memo_control_acceso.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br> Memos Control</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Listado de Informe EDAN</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="listado_informe_edan.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br> Informes EDAN</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Memorandum</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="memorandum_crear.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br> Memorandum - Agregar</a>
                                                    </div>
                                                    <div class="col-lg-2 col-12">
                                                        <a href="memorandum_listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-list"></i><br> Memorandum - Historial</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Ticket SIGH</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <a href="tickets_sigh.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-list"></i><br> Listado de Tickets SIGH</a>
                                                    </div>
                                                    <div class="col-lg-2 col-12">
                                                        <a href="dashboard-ticket-sigh.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-tachometer-alt"></i><br> Dashboard de Tickets SIGH</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Recepcion de Documentos</b>
                                                </h5>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br>Agregar Documento</a>
                                                    </div>
                                                    <div class="col-lg-2 col-12">
                                                        <?php
                                                        $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
                                                        if ($obtenerHistorialDocu > 0) {
                                                            ?>
                                                            <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-clipboard-list"></i><br>Historial Documentos<span class="cantidad-badge cantidad1"><i class="fas fa-flag"><span id="cantidad1">0</span></i></span></a>
                                                            <script>
                                                                document.getElementById("cantidad1").innerHTML = '&nbsp;<?php echo $obtenerHistorialDocu ?>';
                                                            </script>
                                                        <?php
                                                        }else{
                                                        ?>
                                                            <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-clipboard-list"></i><br>Historial Documentos</a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>

                                                <h5>
                                                    <b>Revisi&oacute;n Formatos de Acceso</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="formulariosCCAA_menuSeleccion.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br>Selecci&oacute;n Formularios</a>

                                                    </div>

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <?php
                                                        $cantidadRecibidos = contarFormulariosRecibidos();

                                                        if ($cantidadRecibidos > 0) {
                                                            ?>

                                                            <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-md" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formularios - Recibidos<span class="cantidad-badge"><i class="fas fa-flag"><span id="cantidad">0</span></i></span></a>


                                                            <script>
                                                                document.getElementById("cantidad").innerHTML = '&nbsp;<?php echo $cantidadRecibidos ?>';
                                                            </script>
                                                        <?php
                                                        }else{
                                                        ?>

                                                            <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-md" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formularios Recibidos</a>

                                                            <?php
                                                        }
                                                        ?>

                                                    </div>

                                                    &nbsp;
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="formulariosCCAA_listadoFinalizado.php" class="btn btn-block bg-gradient-info btn-md"><i class="fas fa-clipboard-list"></i><br>Formularios Finalizados</a>

                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Equipamiento HBQP</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-list"></i><br> Listado de Equipos</a>

                                                    </div>

                                                </div>
                                                <hr>
                                                <h5>
                                                    <b>Ordenes de Trabajo (OT)</b>
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-list"></i><br> Trabajos</a>
                                                    </div>
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="trabajos_general_listado_terminado.php" class="btn btn-block bg-gradient-secondary btn-md   "><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <?php
                                            }
                                            ?>
                                            <?php

                                            $mostrarOcultar = deshabilitarOpciones();

                                            if ($mostrarOcultar == 'Mostrar') {
                                                ?>
                                                <h5>
                                                    <b>Hojas Técnicas
                                                </h5>
                                                <br>
                                                <div class="row">

                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-file"></i><br> Generar Hoja Técnica</a>

                                                    </div>
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="ordenes-subir-firmas.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-clipboard-list"></i><br>Listar Hojas Técnicas</a>
                                                    </div>

                                                </div>

                                                <hr>
                                                <?php
                                            }
                                            ?>


                                            <h5>
                                                <b>Rutina Diaria</b>
                                            </h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="reportes-listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br> Reportes Eventos</a>
                                                </div>
                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="bitacora-listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-eye"></i><br>Visualizar Bitacora
                                                    </a>
                                                </div>


                                            </div>
                                            <hr>

                                            <h5>
                                                <b>Control de Acceso y Parking</b>
                                            </h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="control-acceso.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br>Listado Control de Acceso</a>
                                                </div>


                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="parking-acceso.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-parking"></i><br> Listado Parking</a>

                                                </div>


                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="llaves_entregadas.php" class="btn btn-block bg-gradient-info btn-md"><i class="fas fa-key"></i><br> Listado de Llaves Entregadas</a>
                                                </div>
                                                <?php
                                                if ($idDepartamento == 1) {
                                                    ?>
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="eventos_registro.php" class="btn btn-block bg-gradient-purple btn-md"><i class="nav-icon fas fa-clock"></i><br>Eventos</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                            </div>

                                            <hr>

                                            <h5>
                                                <b>Actas de Activaci&oacute;n</b>
                                            </h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->

                                                    <a href="actas-formulario.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-file"></i><br> Generar Acta de Activación</a>

                                                </div>
                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="actas-listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-clipboard-list"></i><br> Listado Actas de Activación</a>

                                                </div>


                                            </div>

                                            <hr>

                                            <h5>
                                                <b>Solicitudes Telefonicas</b>
                                            </h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->

                                                    <a href="solicitudes_telefonicas.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-address-book"></i><br> Solicitudes Telefonicas
                                                    </a>

                                                </div>

                                            </div>
                                            <hr>

                                            <h5>
                                                <b>Tickets de Soporte </b>
                                            </h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-ticket-alt"></i><br> Ticket Recibidos</a>

                                                </div>
                                                <div class="col-lg-2 col-12">
                                                    <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-check"></i><br> Ticket Finalizados</a>
                                                </div>

                                                <div class="col-lg-2 col-12">
                                                    <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-md"><i class="fas fa-times"></i><br> Ticket Cancelados</a>

                                                </div>
                                                <?php
                                                if ($nomUsuario == 'Barbara Gallo' || $nomUsuario == 'Giselle Morales Lizana') {
                                                    ?>
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="usuarios_tickets.php" class="btn btn-block bg-gradient-warning btn-md"><i class="nav-icon fas fa-list"></i><br>Listado de Usuarios</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>


                                        </div>
                                        <hr>

                                        <h5>
                                            <b>Combustible </b>
                                        </h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-2 col-12">
                                                <!-- small box -->
                                                <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump    "></i><br>Registrar Medici&oacute;n Combustible</a>

                                            </div>

                                        </div>


                                        <hr>

                                        <?php
                                        } else {
                                            ?>

                                            <h5>
                                                <b>Tickets de Soporte </b>
                                            </h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-2 col-12">
                                                    <!-- small box -->
                                                    <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-ticket-alt"></i><br> Ticket Recibidos</a>

                                                </div>
                                                <div class="col-lg-2 col-12">
                                                    <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-check"></i><br> Ticket Finalizados</a>
                                                </div>

                                                <div class="col-lg-2 col-12">
                                                    <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-md"><i class="fas fa-times"></i><br> Ticket Cancelados</a>

                                                </div>
                                                <?php
                                                if ($idDepartamento == '1') {
                                                    ?>
                                                    <div class="col-lg-2 col-12">
                                                        <!-- small box -->
                                                        <a href="usuarios_tickets.php" class="btn btn-block bg-gradient-warning btn-md"><i class="nav-icon fas fa-list"></i><br>Listado de Usuarios</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <hr>
                                            <?php
                                        }
                                        ?>


                                        <h5>
                                            <b>Consumos Basicos</b>
                                        </h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-2 col-12">
                                                <!-- small box -->
                                                <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5>
                                            <b>Solicitudes de Compra</b>
                                        </h5>
                                        <br>

                                        <div class="row">
                                            <div class="col-lg-2 col-12">

                                                <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-shopping-bag"></i><br>SC - Puesta en Marcha</a>

                                            </div>
                                            <div class="col-lg-2 col-12">

                                                <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-shopping-bag"></i><br>SC - SDO</a>

                                            </div>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
$obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);
$obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
$obtenerSolicitudesAdministrativo = obtenerSolicitudesAdministrativo($nomUsuario);

if (empty($nomDepartamento)) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$toastrMessages = [
    'loginExito' => [
        'type' => 'success',
        'msg' => 'Ingreso Exitoso a la Plataforma.'
    ],
    'exitoCambioPassword' => [
        'type' => 'success',
        'msg' => 'Contraseña Modificada con Éxito'
    ],
];

foreach ($toastrMessages as $param => $data) {
    if (isset($_GET[$param])) {
        echo "<script>toastr.{$data['type']}('{$data['msg']}');</script>";
    }
}


// Mensajes dinámicos
$toastrMessages = [];

if ($obtenerSolicitudesSegunNombre > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Existen nuevas Solicitudes Creadas.'
    ];
}

if ($obtenerHistorialDocu > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Existen nuevos Documentos.'
    ];
}

if ($obtenerSolicitudesAdministrativo > 0) {
    $toastrMessages[] = [
        'type' => 'info',
        'msg' => 'Ud. tiene Solicitudes Asignadas.'
    ];
}

foreach ($toastrMessages as $data) {
    echo "<script>toastr.{$data['type']}('{$data['msg']}');</script>";
}
?>

<!-- Page specific script -->
<script>
    $(function () {
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
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>