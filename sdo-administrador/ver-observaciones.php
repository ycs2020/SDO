<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];


if(isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Observaciones de los Equipos</title>

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
                        <h4>Observaciones de los Equipos </h4>
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
                                <a href="javascript:history.back()" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm" id="tabla-equipo-falla">
                                    <thead  class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center">Usuario Observador</th>
                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                        <th scope="col" class="text-center">Equipo</th>
                                        <th scope="col" class="text-center">Fecha</th>
                                        <th scope="col" class="text-center">Tipo</th>
                                        <th scope="col" class="text-center">Detalle</th>
                                        <th scope="col" class="text-center">Marca</th>
                                        <th scope="col" class="text-center">Editar</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = $idCarpeta ORDER BY fecha DESC";
                                    $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                    while($rowObservaciones = $resObservaciones->fetch_assoc()){
                                        $datos = [];
                                        $datos['idCambio'] = $rowObservaciones['idCambio'];
                                        $idCambio = $datos['idCambio'];
                                        $datos['fecha'] = $rowObservaciones['fecha'];
                                        $fecha = $datos['fecha'];
                                        $datos['estado'] = $rowObservaciones['estado'];
                                        $estado = $datos['estado'];
                                        $datos['observaciones'] = $rowObservaciones['observaciones'];
                                        $observaciones = $datos['observaciones'];
                                        $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                        $idEquipo = $datos['idEquipo'];
                                        $datos['nombreEquipo'] = $rowObservaciones['nombreEquipo'];
                                        $nomEquipo = $datos['nombreEquipo'];
                                        $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                        $tipoUsuario = $datos['tipoUsuario'];
                                        $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                        $idUsuarioCambia = $datos['idUsuarioCambia'];
                                        $fecha = strtotime($fecha);
                                        $fechaFormateada = date('d/m/Y', $fecha);
                                        ?>
                                        <tr>
                                            <?php
                                            if($tipoUsuario == 'administrativo') {
                                                $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                $res = mysqli_query($conexion, $sql);
                                                while ($row2 = $res->fetch_assoc()) {
                                                    $datos2 = [];
                                                    $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                    $nombre = $datos2['nomAdmTecnicos'];
                                                }

                                            }else if($tipoUsuario == 'jefe tecnico'){
                                                $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                $res = mysqli_query($conexion, $sql);
                                                while ($row2 = $res->fetch_assoc()) {
                                                    $datos2 = [];
                                                    $datos2['nomJefe'] = $row2['nomJefe'];
                                                    $nombre = $datos2['nomJefe'];
                                                }

                                            }else if($tipoUsuario == 'personal'){
                                                $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                $res = mysqli_query($conexion, $sql);
                                                while ($row2 = $res->fetch_assoc()) {
                                                    $datos2 = [];
                                                    $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                    $nombre = $datos2['nomPersonal'];
                                                }
                                            } else if($tipoUsuario == 'administrador'){
                                                $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                $res = mysqli_query($conexion,$sql);
                                                while($row2 = $res->fetch_assoc()){
                                                    $datos2 = [];
                                                    $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                    $nombre = $datos2['nomAdministrador'];

                                                }

                                            } else if($tipoUsuario == 'mesa ayuda'){
                                                $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                $res = mysqli_query($conexion,$sql);
                                                while($row2 = $res->fetch_assoc()){
                                                    $datos2 = [];
                                                    $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                    $nombre = $datos2['nomMesaAyuda'];

                                                }
                                            } else if($tipoUsuario == 'directiva'){
                                                $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                $res = mysqli_query($conexion,$sql);
                                                while($row2 = $res->fetch_assoc()){
                                                    $datos2 = [];
                                                    $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                    $nombre = $datos2['nomDirectivo'];
                                                }
                                            }
                                            ?>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $nombre;
                                                ?>

                                            </td>

                                            <td class="text-center"  style="font-size: 14px">
                                                <?php echo $nomEquipo ?>
                                            </td>
                                            <td  class="text-center">
                                                <?php


                                                $tipoMenu = obtenerTipoMenu($idEquipo);

                                                if ($tipoMenu == 'nuevo') {
                                                    $rutaMenu = 'equipo_menuNuevo.php';
                                                } else {
                                                    $rutaMenu = 'equipo_menuAntiguo.php';

                                                }
                                                ?>
                                                <form  method="POST" action="<?php echo $rutaMenu; ?>">
                                                    <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                </form>
                                            </td>
                                            <td class="text-center"  style="font-size: 14px"><?php echo  $fechaFormateada ?></td>
                                            <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                            <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                            <td class="text-center" style="font-size: 14px">
                                                <form method="POST" action="quitar_observacion_de_lista.php">
                                                    <input type="hidden" value="<?php echo $idCambio?>" name="idCambio" id="idCambio">
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="fas fa-check"></i><b></b></i>
                                                    </button>
                                                </form>

                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <form method="POST" action="editar_observacion.php">
                                                    <input type="hidden" value="<?php echo $idCambio?>" name="idCambio" id="idCambio">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot class="bg-info">
                                    <th scope="col" class="text-center">Usuario Observador</th>
                                    <th scope="col" class="text-center">Nombre del Equipo</th>
                                    <th scope="col" class="text-center">Equipo</th>
                                    <th scope="col" class="text-center">Fecha</th>
                                    <th scope="col" class="text-center">Tipo</th>
                                    <th scope="col" class="text-center">Detalle</th>
                                    <th scope="col" class="text-center">Marca</th>
                                    <th scope="col" class="text-center">Editar</th>
                                    </tfoot>
                                </table>
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
        $('#tabla-equipo-falla').DataTable({
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
<script src="js/dark-mode.js"></script>
</body>

</html>
