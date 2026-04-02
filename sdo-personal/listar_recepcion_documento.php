<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial Recepcion de Documentos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    if (isset($_GET['exitoAgregarDocumento'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se a ingresado el documento con exito!',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorAgregarDocumento'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Ha ocurrido un problema al ingresar un documento!',
                'error'
            )
        </script>
        <?php
    }

    if (isset($_GET['exitoModificacionDocumento'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se edito el documento correctamente!',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorModificacionDocumento'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Hubo un problema al editar el documento!',
                'error'
            )
        </script>
        <?php
    }
    if (isset($_GET['exitoFinDelTrabajo'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se ha agregado una observacion con exito!',
                'success'
            )
        </script>
        <?php
    }
    if (isset($_GET['errorFinDelTrabajo'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Hubo un problema al ingresar la observacion!',
                'error'
            )
        </script>
        <?php
    }
    if (isset($_GET['exitoAgregarDocumentoconArchivo'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se ha agregado un documento con archivo adjunto con exito!',
                'success'
            )
        </script>
        <?php
    }
    ?>


    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                        <h3> <b>Historial de Recepcion de Documentos</b> </h3>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title"> <b> Listado de Recepcion de Documentos</b></h4>
                            </div>
                            <div class="card-body">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                <br>
                                <br>
                                <hr>
                                <br>
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead class="text-center bg-secondary">
                                    <tr>
                                        <th>Folio Documento</th>
                                        <th>Fecha de Recepci&oacute;n</th>
                                        <th>Nombre Unidad</th>
                                        <th>Nombre Emisor</th>
                                        <th>Persona Asignada</th>
                                        <th>Estado</th>
                                        <th>Detalles</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    $sql = "SELECT * FROM recepcion_documento ORDER BY folioRecep DESC";
                                    $res = mysqli_query($conexion,$sql);
                                    while($row = $res->fetch_assoc()){
                                        $idRecepcion = $row['idRecepcion'];
                                        $folioRecep = $row['folioRecep'];
                                        $fechaRecepcion = $row['fechaRecepcion'];
                                        $nomUnidad = $row['unidadEmisor'];
                                        $nomEmisor = $row['nomEmisor'];
                                        $asignadoRecepcion = $row['asignadoRecepcion'];
                                        $estado = $row['estado'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $folioRecep;?>
                                            </td>
                                            <td>
                                                <?php
                                                $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRecepcion)));
                                                echo $registrofechaAC   ;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $nomUnidad1 = mb_convert_case($nomUnidad,MB_CASE_UPPER,'UTF-8');
                                                echo $nomUnidad1;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $nomEmisor1 = mb_convert_case($nomEmisor,MB_CASE_UPPER,'UTF-8');;
                                                echo $nomEmisor1;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $asignadoRecepcion1 = mb_convert_case($asignadoRecepcion,MB_CASE_UPPER,'UTF-8');
                                                echo $asignadoRecepcion1;
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                $estado2 = mb_convert_case($estado,MB_CASE_UPPER,'UTF-8');

                                                echo $estado2;

                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if($idRecepcion == 0 || empty($idRecepcion) || $idRecepcion == ''){
                                                    ?>
                                                    <button type="submit" disabled class="btn-primary btn"><i class="fas fa-eye"></i></button>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <form action="detalle_recepcion_documentos.php" method="POST">
                                                        <input type="hidden" name="idRecepcion" id="idRecepcion" value="<?php echo $idRecepcion?>">
                                                        <button type="submit" class="btn-primary btn"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                    <?php
                                                }
                                                ?>

                                            </td>

                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot class="text-center bg-secondary" >
                                    <tr>
                                        <th>Folio Documento</th>
                                        <th>Fecha de Recepci&oacute;n</th>
                                        <th>Nombre Unidad</th>
                                        <th>Nombre Emisor</th>
                                        <th>Persona Asignada</th>
                                        <th>Estado</th>
                                        <th>Detalles</th>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
</div>
<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
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
