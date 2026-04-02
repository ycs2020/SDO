<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idUCA'])) {

    $idUCA = $_POST['idUCA'];

    if ($idUCA > 0) {

        $nomUCA = obtenerNomUCA($idUCA);

        $_SESSION['idUCA'] = $idUCA;

    } else {

        header('Location: ../sdo-jefestecnicos/ucas_levantamiento_incendio.php?errorPuerta=true');

    }

} else if (isset($_SESSION['idUCA'])) {
    $idUCA = $_SESSION['idUCA'];

    if ($idUCA > 0) {

        $nomUCA = obtenerNomUCA($idUCA);

        $_SESSION['idUCA'] = $idUCA;

    } else {

        header('Location: ../sdo-jefestecnicos/ucas_levantamiento_incendio.php?errorPuerta=true');

    }
} else {
    header('Location: ../sdo-jefestecnicos/ucas_levantamiento_incendio.php?errorPuerta=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Puertas por Grupo de Puertas</title>

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
                        <h4>Listado de Puertas seg&uacute;n <?php echo $nomUCA; ?></h4>
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

                                <a href="ucas_levantamiento_incendio.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h4><b>Visualizando UCA: </b><?php echo $nomUCA; ?></h4>
                                <br>
                                <?php

                                $contarPuertasUCA = contarPuertasUCA($idUCA);

                                if ($contarPuertasUCA > 0) {
                                    ?>
                                    <input type="hidden" name="idUCAMostrar" id="idUCAMostrar" value="<?php echo $idUCA; ?>">

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
                                                    <center>Grupo de Puerta<center>
                                                </th>
                                                <th>
                                                    <center>Usuarios Grupo de Puerta<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

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
                                                    <center>Grupo de Puerta<center>
                                                </th>
                                                <th>
                                                    <center>Usuarios Grupo de Puerta<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>


                                        <script>
                                            $(document).ready(function () {
                                                var columns = [
                                                    {
                                                        "data": null,
                                                        "render": function (data, type, row, meta) {
                                                            // Devuelve el número correlativo basado en el índice de fila (comienza desde 0)
                                                            return meta.row + 1;
                                                        }
                                                    },
                                                    {"data": "nomPuerta"},
                                                    {"data": "nomGrupo"},
                                                    {"data": null,
                                                        "render": function (data, type, row) {
                                                            if (row.idGrupo > 0) {
                                                                return '<form style="margin: 0; text-align: center;" class="" action="../sdo-jefestecnicos/ucas_grupo_puertas_usuarios_incendio.php" method="post">' +
                                                                    '<input type="hidden" name="idGrupoPuerta" id="idGrupoPuerta" value="' + row.idGrupo + '">' +
                                                                    '<button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-users"></i></button>' +
                                                                    '</form>';
                                                            } else {
                                                                return '<center>Error al Cargar Datos.</center>';
                                                            }
                                                        }
                                                    },

                                                ];




                                                $('#example2').DataTable({
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
                                                        "url": "ajax-listado-ucas.php", // Archivo PHP que contiene la consulta SQL
                                                        "type": "POST",
                                                        "data": function (d) {
                                                            d.idUCAMostrar = $('#idUCAMostrar').val();
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
                                    <?php
                                } else {
                                    echo 'error';
                                }

                                ?>
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
<script src="js/dark-mode.js"></script>
</body>

</html>