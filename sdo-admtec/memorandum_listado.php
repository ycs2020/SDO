<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Memorandums</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarMC").show();
                $("#registrarMC").hide();
                $("#listarMC2").show();
                $("#registrarMC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarMC").hide();
                $("#registrarMC").show();
                $("#listarMC2").hide();
                $("#registrarMC2").show();
            });
        });
    </script>
    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Memorandums</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php
        $messages = [
            'exitoGenerarMemo' => ['Exito!', 'Se ha generado Memorandum.', 'success'],
            'errorGenerarMemo' => ['Error!', 'Error al Crear Memorandum.', 'error'],
            'exitoFechaEnvioMemo' => ['Exito!', 'Fecha de Envio de Memorandum Actualizada.', 'success'],
            'errorFechaEnvioMemo' => ['Error!', 'Error al Cambiar Fecha de Envio Memorandum.', 'error'],
            'exitoModificarMemo' => ['Exito!','Se ha modificado el Memorandum','success'],
        ];

        foreach ($messages as $param => list($title, $text, $icon)) {
            if (isset($_GET[$param])) {
                ?>
                <script>
                    Swal.fire(
                        '<?php echo $title; ?>',
                        '<?php echo $text; ?>',
                        '<?php echo $icon; ?>'
                    )
                </script>
                <?php
            }
        }
        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3 ">
                                        <h4 class="card-title">Listado de Memorandums</h4>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3 text-right">

                                        <a href="index.php" class="btn btn-danger btn-md"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>
                                        <a href="memorandum_crear.php" class="btn btn-primary btn-md"><i class="fas fa-file"></i> Crear Memorandum</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <table id="tableListMemos" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>N°</th>
                                        <th>Materia</th>
                                        <th>Destinatario</th>
                                        <th>Fecha Envio</th>
                                        <th>Visualizar</th>
                                        <th>Editar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>N°</th>
                                        <th>Materia</th>
                                        <th>Destinatario</th>
                                        <th>Fecha Envio</th>
                                        <th>Visualizar</th>
                                        <th>Editar</th>
                                    </tr>
                                    </tfoot>
                                </table>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.timeline -->

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
<script src="js/dark-mode.js"></script>
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


    $(document).ready(function () {
        var columns = [
            {"data": "numeroMemo"},
            {"data": "materiaMemo"},
            {"data": "nomDestinatarioMemo"},
            {"data": null,
                "render": function (data, type, row) {
                    if (row.fechaEnvioMemo === '0') {
                        var modalContent = '<center>' +
                            '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCambiarFecha' + row.idMemo + '"><i class="fas fa-pen"></i></button>' +
                            '</center>' +
                            generarModalCambioFecha(row);

                        return modalContent;
                    } else {
                        return '<center>'+row.fechaEnvioMemo+'</center>';
                    }
                }
            },
            {"data": null,
                "render": function (data, type, row) {
                    if (row.idMemo > 0) {
                        return '<form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-MEMORANDUM-PDF-ADMIN-2.php" method="post">' +
                            '<input type="hidden" name="idMemoVer" id="idMemoVer" value="' + row.idMemo + '">' +
                            '<button type="submit" id="inputMozi" class="btn  btn-primary"><i class="fas fa-file"></i></button>' +
                            '</form>';
                    } else {
                        return '<center>Error al Cargar Datos.</center>';
                    }
                }
            },
            {"data": null,
                "render": function (data,type,row) {
                    if (row.fechaEnvioMemo === '0') {
                        return '<form style="margin: 0; text-align: center;" class="" action="editar-memorandum.php" method="post">'+
                            '<input type="hidden" name="idMemoVer" id="idMemoVer" value="' + row.idMemo + '">'+
                            '<button type="submit" id="inputMozi" class="btn  btn-warning"><i class="fas fa-pen"></i></button>' +
                            '</form>';
                    }else{
                        return '<center><button type="submit" disabled id="inputMozi" class="btn  btn-warning"><i class="fas fa-pen"></i></button></center>';
                    }
                }

            }
            // Otras columnas existentes

            // Agregar columnas dinámicamente
        ];




        var tableListMemos = $('#tableListMemos').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
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
                "url": "ajax-listado-memos.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    console.log(d);
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
<script>
    function generarModalCambioFecha(row) {
        return '<form method="post" action="../sdo-funciones/memorandum_cambiarFecha_admin.php" enctype="multipart/form-data">' +
            '<div class="modal fade" id="modalCambiarFecha' + row.idMemo + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
            '<div class="modal-dialog modal-md modal-dialog-centered" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header bg-success">' +
            '<h5 class="modal-title" id="exampleModalLabel">Registrar Fecha de Envio de Memorandum #'+row.idMemo+'</h5>' +
            '</div>' +
            '<div class="modal-body" id="modalBodyMemo">' +
            '<div class="form-row">' +
            '<div class="form-group col-12">' +
            '<label for="fechaEnvioCambiar">Fecha de Envio de Memorandum:</label>' +
            '<div class="input-group mb-3">' +
            '<div class="input-group-prepend">' +
            '<span class="input-group-text"><i class="fas fa-calendar"></i></span>' +
            '</div>' +
            '<input type="datetime-local" class="form-control" required id="fechaEnvioMemo" name="fechaEnvioMemo" placeholder="Fecha Memorandum">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<input type="hidden" name="idMemoCambio" id="idMemoCambio" value="' + row.idMemo + '">' +
            '<button type="submit" class="btn btn-primary">Guardar</button>' +
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
            '</div>' +
            '</div>' +
            '</div' +
            '</div>' +
            '</form>';
    }
</script>
</body>

</html>