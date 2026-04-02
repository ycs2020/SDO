<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$contarForzarEquipo = contarEquiposForzados();

// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipos Forzados en Sistema BMS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Sistema de Control Centralizado</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="card-body">
                                    <div class="card card-primary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <?php
                                                if ($contarForzarEquipo > 0) {

                                                    if ($contarForzarEquipo == 1) {
                                                ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><?php echo '<b>Existe ' . $contarForzarEquipo . ' Equipo Forzado</b>'; ?></a>
                                                        </li>

                                                    <?php

                                                    } else {

                                                    ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false"><?php echo '<b>Existen ' . $contarForzarEquipo . ' Equipos Forzados</b>'; ?></a>
                                                        </li>
                                                <?php

                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <br>
                                                            <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                                            <ul>
                                                                <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido</li>

                                                                <li><i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado</li>

                                                                <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables</li>

                                                                <li><i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado</li>

                                                                <li><i style="color: #138496; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico</li>

                                                                <li><i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal</li>
                                                            </ul>



                                                            <table id="example2" class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Equipo</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Fecha Observaciones:<center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Departamento:</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Nombre de Tecnico:</center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Tipo de Observaci&oacute;n:<center>
                                                                        </th>
                                                                        <th style="vertical-align:middle;">
                                                                            <center>Observaci&oacute;n:<center>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $query2 = 'SELECT idEquipo, detalleObservaciones, fechaObservaciones, personalObservacion, esForzado FROM equipos_observaciones WHERE esForzado NOT LIKE "%Observación Normal%" AND esForzado NOT LIKE "%Modo Automatico%" ORDER BY fechaObservaciones DESC;';
                                                                    $res2 = mysqli_query($conexion, $query2);

                                                                    /* obtener el array por índices */
                                                                    while ($filaObservaciones = mysqli_fetch_assoc($res2)) {
                                                                        $arrayObservaciones[] = $filaObservaciones;
                                                                    }

                                                                    function unique_multidim_array($arrayObservaciones, $key)
                                                                    {
                                                                        $temp_array = array();
                                                                        $i = 0;
                                                                        $key_array = array();

                                                                        foreach ($arrayObservaciones as $val) {
                                                                            if (!in_array($val[$key], $key_array)) {
                                                                                $key_array[$i] = $val[$key];
                                                                                $temp_array[$i] = $val;
                                                                            }
                                                                            $i++;
                                                                        }
                                                                        return $temp_array;
                                                                    }

                                                                    $details = unique_multidim_array($arrayObservaciones, 'idEquipo');

                                                                    foreach ($details as $key => $value) {

                                                                        $fila = $value; // obtiene fila de array actual

                                                                        if ($fila['esForzado'] == "Forzado - Encendido") {

                                                                            $classObservacion = "bg-success";
                                                                        } else if ($fila['esForzado'] == "Forzado - Apagado") {

                                                                            $classObservacion = "bg-pink";
                                                                        } else if ($fila['esForzado'] == "Forzado - Variables") {

                                                                            $classObservacion = "bg-yellow";
                                                                        } else if ($fila['esForzado'] == "Modo Automatico") {

                                                                            $classObservacion = "bg-info";
                                                                        } else if ($fila['esForzado'] == "Observación Normal") {

                                                                            $classObservacion = "bg-navy";
                                                                        } else if ($fila['esForzado'] == "Forzado") {

                                                                            $classObservacion = "bg-gray";
                                                                        } else {

                                                                            $classObservacion = "bg-light";
                                                                        }
                                                                        // echo $fila["fechaObservaciones"] . "<br><br>"; //accediendo a propiedad

                                                                        echo '<tr class="' . $classObservacion . '">';

                                                                        echo '<td style="vertical-align:middle;"><center>' . mb_strtoupper(getSubCategoriaConEquipo($fila["idEquipo"]), "UTF-8") . '</center></td>';

                                                                        if ($fila["fechaObservaciones"] == 0) {
                                                                            $fechaConvertida = "No registra fecha";
                                                                        } else {
                                                                            $fechaInicio = strtotime($fila["fechaObservaciones"]);

                                                                            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fila["fechaObservaciones"])));
                                                                            $inicioHora = date('H:i', strtotime($fila["fechaObservaciones"]));
                                                                            $fechaConvertida = fechaEspañol2($fechaInicio) . '<br>' . $inicioHora . ' hrs.';
                                                                        }

                                                                        echo '<td style="vertical-align:middle;"><center>' . $fechaConvertida . '</center></td>';

                                                                        echo '<td style="vertical-align:middle;"><center>' . getCategoriaEquipo($fila['idEquipo']) . '</center></td>';

                                                                        $personalObservacion2 = explode("(", $fila['personalObservacion']);

                                                                        echo '<td style="vertical-align:middle;"><center>' . $personalObservacion2[0] . '<br>(' . $personalObservacion2[1] . '</center></td>';

                                                                        echo '<td style="vertical-align:middle;"><center>' . $fila['esForzado'] . '</center></td>';

                                                                        echo '<td style="vertical-align:middle;">' . nl2br($fila['detalleObservaciones']) . '</td>';


                                                                        echo '</tr>';

                                                                        //si quieres acceder a una propiedad que no esta en todas las filas debes preguntar si existe el indice 
                                                                    }

                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

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

    <script src="js/dark-mode.js"></script>
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
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
            $('#example2').DataTable({
                "autoWidth": false,
                "responsive": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "info": false,
                "ordering": false,
                "language": {
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "",
                        "next": ""
                    }
                }
            });
        });
    </script>


</body>

</html>