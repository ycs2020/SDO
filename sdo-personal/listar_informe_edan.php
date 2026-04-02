<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i');
$nomUsuario = $_SESSION['nomUsuario'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Informe EDAN</title>

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listar Informe EDAN</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Termino Programada</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Informe</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ingresar Obs</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Modificar obs</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Enviar Correo</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                $sql = "SELECT * FROM `informes_edan`";
                                                $res = mysqli_query($conexion,$sql);
                                                while($row = $res->fetch_assoc()){
                                                    $id = $row['id'];
                                                    $fecha = $row['fecha'];
                                                    $fecha_formateada = date('d-m-Y H:i', strtotime($fecha));
                                                    $folio = $row['folio'];
                                                    $fechaTermino = $row['fechaTermino'];
                                                    $fechaTerminoFormateada = date('d-m-Y H:i',strtotime($fechaTermino));
                                                    $correoEnviado = $row['correo_enviado'];
                                                    if($now >= $fechaTermino){
                                                    }else{
                                                        ?>
                                                        <tr>
                                                            <td style="font-size: 15px">
                                                                <?php echo $fecha_formateada?>
                                                            </td>
                                                            <td style="font-size: 15px">
                                                                <?php echo $fechaTerminoFormateada?>
                                                            </td>
                                                            <td style="font-size: 15px">
                                                                <?php echo $folio?>
                                                            </td>
                                                            <td style="font-size: 15px">
                                                                <form action="../sdo-pdf/SDO-EDAN.php" target="_blank" method="post">
                                                                    <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                                                                    <button type="submit" class="btn btn-sm bg-gradient-cyan"><i class="fas fa-eye"></i></button>
                                                                </form>
                                                            </td>
                                                            <?php
                                                            if($correoEnviado == 0){
                                                                ?>
                                                                <td style="font-size: 15px">
                                                                    <form action="completar_control_edan.php"  method="post">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                                                                        <button class="btn btn-sm bg-gradient-success"><i class="fas fa-list-alt"></i></button>
                                                                    </form>
                                                                </td>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <td style="font-size: 15px">
                                                                    <?php
                                                                    echo "Deshabilitado";
                                                                    ?>
                                                                </td>
                                                                <?php
                                                            }
                                                            ?>

                                                            <?php
                                                                if($correoEnviado == 0){
                                                                    ?>
                                                                    <td style="font-size: 15px">
                                                                        <form action="editar_control_edan.php"  method="post">
                                                                            <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                                                                            <button class="btn btn-sm bg-gradient-warning"><i class="fas fa-pen"></i></button>
                                                                        </form>
                                                                    </td>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <td style="font-size: 15px">
                                                                        <?php
                                                                        echo "Deshabilitado";
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                }
                                                            ?>

                                                            <?php
                                                                if($correoEnviado == 0){
                                                                    ?>
                                                                    <td style="font-size: 15px">
                                                                        <button class="btn btn-sm bg-gradient-indigo enviar" id="<?php echo $id?>"><i class="fas fa-envelope"></i></button>
                                                                    </td>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <td style="font-size: 15px">
                                                                        <?php
                                                                        echo "Deshabilitado";
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                }
                                                            ?>

                                                        </tr>
                                                        <?php
                                                    }

                                                }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Termino Programada</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Informe</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ingresar Obs</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Modificar obs</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Enviar Correo</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
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
            "responsive": false,
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
            }

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    $(document).on('click', '.enviar', function (e) {
        e.preventDefault();
        let id = $(this).attr('id');
        var dataString = 'id=' + id;
        Swal.fire({
            icon: 'question',
            title: '¿Desea enviar el informe EDAN?',
            text: 'Esta acción no se puede revertir',
            showConfirmButton: true,
            confirmButtonText: 'Si , enviar',
            confirmButtonColor: '#3085d6',
            showCancelButton: true,
            cancelButtonText: 'No, lo pensare',
            cancelButtonColor: '#d33',
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../sdo-pdf/SDO-EDAN-ENVIAR-2.php',
                    type: 'POST',
                    data: dataString,
                    success: function(response) {
                        //console.log('eliminado.. ' + dataString);
                        Swal.fire({
                            icon: 'success',
                            title: 'Correo enviado con exito',
                            showConfirmButton: false,
                            timer: 2000,
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 2100)
                    }
                })
            }
        })
    });
</script>
<script src="js/dark-mode.js"></script>
</body>
</html>
