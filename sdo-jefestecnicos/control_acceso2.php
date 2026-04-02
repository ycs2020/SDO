<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Control de Acceso</title>

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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Control de Acceso</h4>
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
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>RUT<center>
                                                </th>
                                                <th>
                                                    <center>N° Tarjeta<center>
                                                </th>
                                                <th>
                                                    <center>Departamento<center>
                                                </th>
                                                <th>
                                                    <center>¿Existe en Intercom?<center>
                                                </th>
                                                <th>
                                                    <center>Calidad Juridica<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ACM_owner ORDER BY OWNER_Name, DEPT_ID ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numAcceso = 1;

                                            while ($row = $res->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numAcceso; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_convert_case($row['OWNER_Name'], MB_CASE_UPPER, "UTF-8");
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $rutAcceso = $row['OWNER_CODE'];
                                                        echo $rutAcceso;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $tarjetaAcceso = $row['CARD_NUMBER'];
                                                        echo $tarjetaAcceso;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <?php

                                                        $nomDepartamento = getNomDepartamentoCCAA($row['DEPT_ID']);
                                                        $departamentoAcceso = mb_convert_case($nomDepartamento, MB_CASE_UPPER, "UTF-8");
                                                        echo $departamentoAcceso;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                        <?php

                                                        $existeIntercom = existeIntercom($rutAcceso);

                                                        if ($existeIntercom == 1) {
                                                            $fraseExisteIntercom = "Posee Derecho a Estacionamiento";
                                                        } else {
                                                            $fraseExisteIntercom = "No Posee Derecho a Estacionamiento";
                                                        }

                                                        echo mb_convert_case($fraseExisteIntercom, MB_CASE_UPPER, "UTF-8");
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                        <?php

                                                        $obtenerCalidadJuridica = obtenerCalidadJuridica($rutAcceso);

                                                        $obtenerCalidadJuridica = trim($obtenerCalidadJuridica);

                                                        $obtenerCalidadJuridica = mb_convert_case($obtenerCalidadJuridica, MB_CASE_UPPER, "UTF-8");

                                                        if (empty($obtenerCalidadJuridica)) {
                                                            echo 'Sin Información';
                                                        } else {
                                                            echo $obtenerCalidadJuridica;
                                                        }

                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php

                                                $numAcceso = $numAcceso + 1;
                                            }
                                            ?>



                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>RUT<center>
                                                </th>
                                                <th>
                                                    <center>N° Tarjeta<center>
                                                </th>
                                                <th>
                                                    <center>Departamento<center>
                                                </th>
                                                <th>
                                                    <center>¿Existe en Intercom?<center>
                                                </th>
                                                <th>
                                                    <center>Calidad Juridica<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

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
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function() {
                this.api().columns([5]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">¿Existe en Intercom?...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([6]).every(function() {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Calidad Juridica</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
<script src="js/dark-mode.js"></script>
</body>

</html>