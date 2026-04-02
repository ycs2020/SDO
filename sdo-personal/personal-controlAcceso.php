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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <form id="permisosForm" method="post" target="_blank" action="accesos_funcionarios.php">
                                            <table id="example2" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Nombre</center>
                                                    </th>
                                                    <th>
                                                        <center>RUT</center>
                                                    </th>
                                                    <th>
                                                        <center>N° Tarjeta</center>
                                                    </th>
                                                    <th>
                                                        <center>Departamento</center>
                                                    </th>
                                                    <th>
                                                        <center>¿Existe en Intercom?</center>
                                                    </th>
                                                    <th>
                                                        <center>Estado de Credencial</center>
                                                    </th>
                                                    <th>
                                                        <center>Visualizar Permisos</center>
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

                                                        <?php
                                                        $obtenerStatusCredencialCCAA = obtenerStatusCredencialCCAA($rutAcceso);

                                                        $estilo = '';
                                                        if ($obtenerStatusCredencialCCAA == 'Sin Credencial' || $obtenerStatusCredencialCCAA == 'Credencial Bloqueada' || $obtenerStatusCredencialCCAA == 'Estado Desconocido') {
                                                            $estilo = "style='font-weight: bold; color: #ff0707;vertical-align: middle;'";
                                                        } elseif ($obtenerStatusCredencialCCAA == 'Credencial Activada') {
                                                            $estilo = "style='font-weight: bold; color: #00A100;vertical-align: middle;'";
                                                        } else {
                                                            $estilo = "vertical-align: middle;";
                                                        }
                                                        echo "<td $estilo>$obtenerStatusCredencialCCAA</td>";
                                                        ?>

                                                        <td style="vertical-align: middle;" align="center">
                                                            <button type="button" class="verPermisosBtn btn btn-sm btn-primary" data-owner-code="<?php echo $row['OWNER_CODE']; ?>"><i class="fas fa-eye"></i></button>
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
                                                        <center>Nombre</center>
                                                    </th>
                                                    <th>
                                                        <center>RUT</center>
                                                    </th>
                                                    <th>
                                                        <center>N° Tarjeta</center>
                                                    </th>
                                                    <th>
                                                        <center>Departamento</center>
                                                    </th>
                                                    <th>
                                                        <center>¿Existe en Intercom?</center>
                                                    </th>
                                                    <th>
                                                        <center>Estado de Credencial</center>
                                                    </th>
                                                    <th>
                                                        <center>Visualizar Permisos</center>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <!-- Agrega un campo oculto para almacenar el OWNER_CODE -->
                                            <input type="hidden" id="ownerCodeInput" name="owner_code" value="">
                                        </form>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var permisosForm = document.getElementById('permisosForm');
                                                var ownerCodeInput = document.getElementById('ownerCodeInput');

                                                // Asigna un controlador de eventos al formulario
                                                permisosForm.addEventListener('submit', function () {
                                                    // Obtén el valor del OWNER_CODE almacenado en el campo oculto
                                                    var ownerCode = ownerCodeInput.value;

                                                    // Actualiza el valor del campo oculto con el OWNER_CODE del botón clicado
                                                    ownerCodeInput.value = ownerCode;
                                                });

                                                // Asigna un controlador de eventos a cada botón
                                                var verPermisosBtns = document.querySelectorAll('.verPermisosBtn');
                                                verPermisosBtns.forEach(function (btn) {
                                                    btn.addEventListener('click', function () {
                                                        // Obtén el valor del atributo data-owner-code
                                                        var ownerCode = this.getAttribute('data-owner-code');

                                                        // Actualiza el valor del campo oculto con el OWNER_CODE del botón clicado
                                                        ownerCodeInput.value = ownerCode;

                                                        // Envía el formulario
                                                        permisosForm.submit();
                                                    });
                                                });
                                            });
                                        </script>
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
            initComplete: function () {
                this.api().columns([5]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">¿Existe en Intercom?...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                this.api().columns([6]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Estado de Credencial...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
<!-- DARK MODE-->
<script src="js/dark-mode.js"></script>
</body>

</html>