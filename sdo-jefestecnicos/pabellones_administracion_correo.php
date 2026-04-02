<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; - Pabellones</title>

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


    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarMC").show();
                $("#registrarMC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarMC").hide();
                $("#registrarMC").show();
            });
        });
    </script>

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
                        <h4>Men&uacute; - Pabellones</h4>
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
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 ">
                        <div class="card">
                            <div class="card-header">

                                <h3>Administraci&oacute;n de Variables de Pabellones</h3>

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <div class="col-12 ">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <button class="btn bg-yellow show-realizar" id="registrarMC" name="registrarMC">Registrar y Enviar Parametros</button>
                                <button class="btn bg-yellow show-listar" id="listarMC" style="display:none;" name="listarMC">Ver Listado de Registros</button>

                            </div>
                            <!-- /.card-header -->
                        </div>
                        <!-- /.card -->


                    </div>
                </div>
                <!-- /.row -->

                <div class="row" id="rowMenu1" name="rowMenu1" style="">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Registro de Parametros Diarios</h5>
                                <hr>

                                <table style="font-size:80%" id="example2" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Funcionario</center>
                                        </th>
                                        <?php

                                        for ($i = 2; $i <= 10; $i++) {
                                            ?>
                                            <th style="vertical-align:middle;">
                                                <center>Pabellon <?php echo $i; ?></center>
                                            </th>
                                            <!--                                            <th style="vertical-align:middle;">-->
                                            <!--                                                <center>Temp Pab --><?php //echo $i; ?><!--</center>-->
                                            <!--                                            </th>-->
                                            <!--                                            <th style="vertical-align:middle;">-->
                                            <!--                                                <center>[Pa] Pab --><?php //echo $i; ?><!--</center>-->
                                            <!--                                            </th>-->
                                            <?php
                                        }

                                        ?>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle;">
                                            <center>Fecha</center>
                                        </th>
                                        <th style="vertical-align:middle;">
                                            <center>Funcionario</center>
                                        </th>
                                        <?php

                                        for ($i = 2; $i <= 10; $i++) {
                                            ?>
                                            <th style="vertical-align:middle;">
                                                <center>Pabellon <?php echo $i; ?></center>
                                            </th>
                                            <!--                                            <th style="vertical-align:middle;">-->
                                            <!--                                                <center>Temp Pab --><?php //echo $i; ?><!--</center>-->
                                            <!--                                            </th>-->
                                            <!--                                            <th style="vertical-align:middle;">-->
                                            <!--                                                <center>[Pa] Pab --><?php //echo $i; ?><!--</center>-->
                                            <!--                                            </th>-->
                                            <?php
                                        }

                                        ?>

                                    </tr>
                                    </tfoot>
                                </table>

                                <script>
                                    $(document).ready(function () {
                                        var columns = [
                                            {"data": "fechaRegistroPab"},
                                            {"data": "nomRegistroPab"},
                                            // Otras columnas existentes

                                            // Agregar columnas dinámicamente
                                        ];

                                        for (var i = 2; i <= 10; i++) {
                                            columns.push({"data": "valorTotal" + i});
                                            // Agrega más columnas según sea necesario
                                        }

                                        var example2 = $('#example2').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
                                            "responsive": true,
                                            "ordering": false,
                                            "language": {
                                                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                "zeroRecords": "No se han encontrado Registros.",
                                                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                "infoEmpty": "No existen Registros.",
                                                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                "search": "Buscar:",
                                                "processing": "Procesando...",
                                                "paginate": {
                                                    "previous": "Anterior",
                                                    "next": "Siguiente"
                                                }
                                            },
                                            "ajax": {
                                                "url": "ajax-pabCorreo-listado.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {

                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": columns,
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Ingreso Parametros Diarios</h5>
                                <br>

                                <br>
                                <form action="../sdo-funciones/pabellones_registroDiario.php" method="post" enctype="multipart/form-data">
                                    <?php

                                    for ($j = 2; $j <= 10; $j++) {
                                        ?>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputTempPab<?php echo $j; ?>">Temperatura Pabellon <?php echo $j; ?>: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-thermometer-half"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="inputTempPab<?php echo $j; ?>" placeholder="Temperatura Pabellon <?php echo $j; ?>" type="number" min="0" step=".1" name="inputTempPab<?php echo $j; ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputTempPab<?php echo $j; ?>">Humedad Pabellon <?php echo $j; ?>: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                                                    </div>
                                                    <input class="form-control" id="inputHumedadPab<?php echo $j; ?>" type="number" min="0" step=".1" required placeholder="Humedad Pabellon <?php echo $j; ?>" name="inputHumedadPab<?php echo $j; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    ?>
                                    <br><br>
                                    <div class="form-group">
                                        <input type="hidden" name="personalRegistro" id="personalRegistro" value="<?php echo $nomUsuario; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>


                                <!--HASTA AQUI-->

                                <hr>


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

<!-- jQuery -->
<script src="js/dark-mode.js"></script>
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
</body>

</html>