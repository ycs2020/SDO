<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Listado de AST</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">

        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header" style="background-color: #0a30f2; color: white">
                                <h4>Listado de AST</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br><br><a href="construccionAST.php" style="float: right;" class="btn bg-gradient-blue float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Hacer AST</a>
                                </div>
                            </div>
                            <hr>
                            <table id="AST" style="width: 100%" class="table text-center">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Persona Asignada</th>
                                    <th>Servicio</th>
                                    <th>Es por CheckList?</th>
                                    <th>Visualizar</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM `ast_participantes_otros` ORDER BY id DESC ";
                                        $res = mysqli_query($conexion,$sql);
                                        while($row = $res->fetch_assoc()){
                                            $id = $row['id'];
                                            $nomParticipantes  = $row['nomParticipantes'];
                                            $fecha = $row['fecha'];
                                            $fecha_formateada = date("d-m-Y", strtotime($fecha));
                                            $servicio = $row['servicio'];
                                            $idTG = $row['idTG'];

                                            $sqlBuscarUCA = "SELECT COUNT(*) as total FROM `datos_checklist_ucas` WHERE `idTG` = $idTG ORDER BY `id` DESC";
                                            $resBuscarUCA = mysqli_query($conexion,$sqlBuscarUCA);
                                            while($rowBuscarUCA = $resBuscarUCA->fetch_assoc()){
                                                $total = $rowBuscarUCA['total'];
                                            }

                                            ?>
                                            <tr>
                                                <td><?php echo $fecha_formateada?></td>
                                                <td><?php echo $nomParticipantes?></td>
                                                <td><?php echo $servicio?></td>
                                                <td>
                                                    <?php
                                                    if($total > 0){
                                                        echo 'Si';
                                                    }else{
                                                        echo 'NO';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $formGeneral = "../sdo-pdf/SDO-AST-GENERAL.php";
                                                    if($total > 0){
                                                        $form = "../sdo-pdf/SDO-AST-UCAS.php";
                                                        ?>
                                                        <form action="<?php echo $form?>" method="POST" target="_blank">
                                                            <input type="submit" class="btn btn-success" value="Ver AST">
                                                            <input type="hidden" value="<?php echo $idTG?>" name="idTG">
                                                        </form>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <form action="<?php echo $formGeneral?>" method="POST" target="_blank">
                                                            <input type="submit" class="btn btn-primary" value="Ver AST">
                                                            <input type="hidden" value="<?php echo $id?>" name="idAST">
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
                                <tfoot class="thead-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Persona Asignada</th>
                                    <th>Servicio</th>
                                    <th>Es por CheckList?</th>
                                    <th>Visualizar</th>
                                </tr>
                                </tfoot>
                            </table>
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
</body>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

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
<script>
    $(function () {
        $('#AST').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
</html>
