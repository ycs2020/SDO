<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['volverAtras'] = "Recibidas";


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tickets Recibidos</title>

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
</head>


<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">

                </div><!-- /.container-fluid -->
            </section>
            <?php
            $obtenerSolicitudesSegunNombreJefe = obtenerSolicitudesSegunNombreJefe($nomUsuario);

            $obtenerSolicitudesSegunNombreProfesional = obtenerSolicitudesSegunNombreProfesional($nomUsuario);

            if ($obtenerSolicitudesSegunNombreJefe > 0) {
            ?>
                <script>
                    Swal.fire(
                        'Ud. tiene Solicitudes Asignadas.',
                        '',
                        'info'
                    )
                </script>
            <?php
            } else if ($obtenerSolicitudesSegunNombreProfesional > 0) {
            ?>
                <script>
                    Swal.fire(
                        'Ud. tiene Solicitudes Asignadas.',
                        '',
                        'info'
                    )
                </script>
            <?php
            }
            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-12 d-flex">
                            <div class="card w-100 h-100">
                                <div class="card-body">
                                    Tickets Recibidos
                                </div>
                            </div>
                        </div>
                        <?php
                        if($nomUsuario == 'Yorgo Casimis Subiabre'){
                            ?>
                            <div class="col-xl-3 col-lg-3 col-12 d-flex">
                                <div class="card w-100 h-100">
                                    <div class="card-body">
                                        <button class="btn btn-block btn-sm btn-primary" onclick="actualizarTickets()">
                                            <i class="fas fa-arrow-alt-circle-down"></i> Actualizar Tickets SIGH
                                        </button>
                                        <script>
                                            function actualizarTickets() {
                                                $('.overlay').show();

                                                var form = $('<form>', {
                                                    'action': '../sdo-funciones/sigh_actualizarTickets2.php',
                                                    'method': 'POST'
                                                });

                                                form.appendTo('body').submit();
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="col-xl-3 col-lg-3 col-12 d-flex">
                            <div class="card w-100 h-100">
                                <div class="card-body">
                                    <a href="index.php" class="btn btn-danger btn-block btn-sm">
                                        <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <br>
                                    <style>
                                        .estado-ticket {
                                            list-style: none;
                                            padding-left: 0;
                                        }

                                        .estado-ticket li {
                                            margin-bottom: 6px;
                                            font-size: 15px;
                                            display: flex;
                                            align-items: center;
                                        }

                                        .estado-icono {
                                            width: 16px;
                                            height: 16px;
                                            margin-right: 8px;
                                            text-shadow: 0 0 3px #000;
                                        }
                                    </style>

                                    <h5 class="mb-2">
                                        <i class="fas fa-palette"></i> Colores para Identificar:
                                    </h5>
                                    <ul class="estado-ticket">
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #28A745;"></i> Solicitud Ingresada
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #FFC107;"></i> Solicitud con Personal Asignado
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #563D7C;"></i> Se encuentra con Solicitud de Compra
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #76D7C4;"></i> Se modifica Departamento Responsable
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #A04000;"></i> Se encuentra con Observaciones Nuevas
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #868678;"></i> Ticket con Programación de Trabajos
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #FF6600;"></i> Ticket - Proyecto
                                        </li>
                                        <li>
                                            <i class="fa fa-circle estado-icono" style="color: #ff0000;"></i> Pendiente
                                        </li>
                                    </ul>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 10pt;">
                                            <thead class="bg-info">
                                            <th style="vertical-align:middle;">
                                                <center>FOLIO SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>NOMBRE SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>UBICACI&Oacute;N SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TIPO DE PROBLEMA<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA DE SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>PERSONAL ASIGNADO<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>SITUACI&Oacute;N<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VISUALIZAR<center>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if($nomDepartamento == 'Instrumentación'){
                                                $nomDepartamento = 'Control Centralizado';
                                            }
                                            $query = 'SELECT * FROM solicitudes_soportes WHERE tecnicoSolicitud IS NOT NULL AND tecnicoSolicitud NOT LIKE "" AND canceladaSolicitud = 0 AND finalizadaSolicitud = 0 AND deptoResponsable = "' . $nomDepartamento . '" ORDER BY inicioSolicitud DESC';
                                            //echo $query;

                                            $res = mysqli_query($conexion, $query);

                                            $numSolicitud = 1;

                                            $colorTR = "";

                                            while ($row = $res->fetch_assoc()) {

                                            $situacionSolicitud1 = $row['situacionSolicitud'];

                                            $tecnicoSolicitud1 = $row['tecnicoSolicitud'];

                                            $situacionSolicitud1 = mb_strtoupper($situacionSolicitud1, "UTF-8");

                                            if ($situacionSolicitud1 == "SOLICITUD CREADA" || $situacionSolicitud1 == 'ACTIVO') {
                                                $colorTR = "background-color: #28A745; color:white;";
                                            }

                                            if ($situacionSolicitud1 == "SOLICITUD CON PERSONAL ASIGNADO" || $tecnicoSolicitud1 != 'No Aplica') {
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            }

                                            $contarSolicitudComprasSC = contarSolicitudComprasSC($row['idSolicitud']);

                                            if ($contarSolicitudComprasSC > 0) {
                                                $colorTR = "background-color: #563D7C; color:white;";
                                            }

                                            $getNuevaObservacionSolicitud = getNuevaObservacionSolicitud($row['idSolicitud']);

                                            if ($getNuevaObservacionSolicitud == 1) {
                                                $colorTR = "background-color: #A04000; color:white;";
                                            }

                                            if ($situacionSolicitud1 == "TICKET CON PROGRAMACIÓN DE TRABAJOS" || $situacionSolicitud1 == "PROGRAMADO") {
                                                $colorTR = "background-color: #868678; color:black;";
                                            }

                                            if ($situacionSolicitud1 == "TICKET DE PROYECTO") {
                                                $colorTR = "background-color: #FF6600; color:white;";
                                            }
                                            if ($situacionSolicitud1 == "SE MODIFICA DEPARTAMENTO RESPONSABLE") {
                                                $colorTR = "background-color: #76D7C4; color:black;";
                                            }

                                            ?>
                                            <tr style="<?php echo $colorTR; ?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    $folioSolicitud = mb_strtoupper($row['folioSolicitud']);
                                                    echo $folioSolicitud;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                    <center>
                                                        <?php
                                                        $nomSolicitante = mb_strtoupper($row['nomSolicitante']);

                                                        $deptoSolicitante = explode('/', $row['deptoSolicitante']);

                                                        $longitud_deptoSolicitante = count($deptoSolicitante);

                                                        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

                                                        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);



                                                        echo $nomSolicitante . '<br> ( ' . $ultima_deptoSolicitante . ' )';

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                    <center>
                                                        <?php
                                                        $ubicacionSolicitud = mb_strtoupper($row['ubicacionSolicitud']);
                                                        echo $ubicacionSolicitud;

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                    <center>
                                                        <?php
                                                        $deptoResponsable = mb_strtoupper($row['deptoResponsable']);

                                                        $tipoProblemaSolicitud = mb_strtoupper($row['tipoProblemaSolicitud']);

                                                        echo $deptoResponsable . ':<br>' . $tipoProblemaSolicitud;

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                    <center>
                                                        <?php if ($row['inicioSolicitud'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {

                                                            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['inicioSolicitud'])));
                                                            $inicioHora = date('H:i', strtotime($row['inicioSolicitud']));

                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                        } ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                    <center>
                                                        <?php
                                                        $tecnicoSolicitud = trim($row['tecnicoSolicitud']);
                                                        $tecnicoSolicitud = mb_strtoupper($tecnicoSolicitud, "UTF-8");

                                                        if (empty($tecnicoSolicitud)) {
                                                            echo 'Sin Asignar';
                                                        } else {
                                                            $soloNombre = preg_replace('/\s*\(.*?\)/', '', $tecnicoSolicitud);
//                                                                $deptoTecnico = explode(' (', $tecnicoSolicitud);
                                                            echo $soloNombre;
                                                        }

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <center>
                                                        <?php
                                                        $situacionSolicitud = $row['situacionSolicitud'];
                                                        $situacionSolicitud = mb_strtoupper($situacionSolicitud, "UTF-8");
                                                        echo $situacionSolicitud;

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $idSolicitud = $row['idSolicitud'];
                                                        $idSolicitud = trim($idSolicitud);
                                                        if ($idSolicitud == 0 || $idSolicitud == "" || $idSolicitud == " " || $idSolicitud == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" action="solicitudes_detalle.php" target="_blank" method="post">

                                                                <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>

                                                <?php

                                                $numSolicitud = $numSolicitud + 1;
                                                }
                                                ?>



                                            </tbody>
                                            <tfoot class="bg-info">
                                            <th style="vertical-align:middle;">
                                                <center>FOLIO SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>NOMBRE SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>UBICACI&Oacute;N SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TIPO DE PROBLEMA<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA DE SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>PERSONAL ASIGNADO<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>SITUACI&Oacute;N<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VISUALIZAR<center>
                                            </th>
                                            </tfoot>
                                        </table>

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