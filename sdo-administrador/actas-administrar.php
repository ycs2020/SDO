<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Actas</title>

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
</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h4>Sistema de Actas</h4>
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
                                    <br>
                                    <br>

                                    <form method="post" action="actas-formulario.php">

                                        <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Actas</button>

                                    </form>
                                    <br>
                                    <br>

                                    <form method="post" action="../sdo-pdf/SDO-Estadistica-Actas.php">

                                        <button type="submit" style="float: right;" id="inputMozi" class="btn btn-secondary"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Estadistica</button>

                                    </form>

                                    <br><br>
                                    <?php
                                    if (isset($_GET['exitoCrearActa'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Creada con Exito.</h5></div>';
                                    }

                                    if (isset($_GET['exitoModActa'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Modificada con Exito.</h5></div>';
                                    }

                                    if (isset($_GET['errorCrearActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorCrearFolio'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Folio de Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorVisualizarActa'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Visualizar Acta. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['vehiculo1Vacio'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Datos de Vehiculo N° 1 estan Vacios. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['soloVehiculo1'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1.</h5></div>';
                                    }

                                    if (isset($_GET['sinEstacionamiento'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Persona No Requiere Estacionamiento.</h5></div>';
                                    }

                                    if (isset($_GET['exitoCrearActaVehiculo1y2'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1 y Vehiculo N° 2.</h5></div>';
                                    }

                                    if (isset($_GET['errorVehiculo1'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 1. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorVehiculo2'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 2. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['existePatente1'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 1 ya se encuentra registrada.</h5></div>';
                                    }

                                    if (isset($_GET['existePatente2'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 2 ya se encuentra registrada.</h5></div>';
                                    }


                                    ?>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Folio Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Usuario de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>RUT de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>N° Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Unidad de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Solicitante<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Tecnico<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Visualizar Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Modificar Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Modificar<br>Estacionamiento<center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                $query = 'SELECT * FROM actas ORDER BY fechaActa DESC;';

                                                $res = mysqli_query($conexion, $query);

                                                $numActa = 1;

                                                while ($row = $res->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php echo $numActa; ?>
                                                            </center>
                                                        </td>
                                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                                            <center>
                                                                <?php

                                                                $folioActa = mb_strtoupper($row['folioActa']);
                                                                echo $folioActa;

                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;">
                                                            <center>
                                                                <?php
                                                                echo $row['nomTarjeta'];
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;">
                                                            <center>
                                                                <?php
                                                                echo $row['rutTarjeta'];
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                                            <center>
                                                                <?php echo mb_strtoupper($row['codigoTarjeta']); ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                if ($row['fechaActa'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaActa'])));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaActa']));
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                }
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align:justify;">
                                                            <center>
                                                                <?php
                                                                echo mb_strtoupper($row['unidadTarjeta']);
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align:justify;">
                                                            <center>
                                                                <?php
                                                                echo $row['nomSolicitante'];
                                                                ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle; text-align:justify;">
                                                            <center>
                                                                <?php
                                                                echo $row['nomControlCentralizado'];
                                                                ?>
                                                            </center>
                                                        </td>
                                                        
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idActa = $row['idActa'];
                                                                $idActa = trim($idActa);
                                                                if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Acta.php" method="post">

                                                                        <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i></button>

                                                                    </form>

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idActa = $row['idActa'];
                                                                $idActa = trim($idActa);
                                                                if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-administrador/actas-modificar.php" method="post">

                                                                        <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i></button>

                                                                    </form>

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idActa = $row['idActa'];
                                                                $idActa = trim($idActa);
                                                                if ($idActa == 0 || $idActa == "" || $idActa == " " || $idActa == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <form style="margin: 0; text-align: center;" class="" action="../sdo-administrador/actas-estacionamiento.php" method="post">

                                                                        <input type="hidden" name="idActa" value="<?php echo $idActa; ?>">
                                                                        <input type="hidden" name="rutActa2" value="<?php echo $row['rutTarjeta']; ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fas fa-parking"></i></button>

                                                                    </form>

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        
                                                    </tr>
                                                <?php

                                                    $numActa++;
                                                }


                                                ?>



                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Folio Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Usuario de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>RUT de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>N° Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Unidad de la Tarjeta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Solicitante<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre de Tecnico<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Visualizar Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Modificar Acta<center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Modificar<br>Estacionamiento<center>
                                                    </th>
                                                </tr>
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
</body>


</html>