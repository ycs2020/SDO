<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//AQUI LAS CONSULTAS
if ($_GET['carpeta'] == 'enfermeria' ||  $_GET['carpeta'] == 'ENFERMERIA') {

    $carpeta = $_GET['carpeta'];

    $titulo = 'Llamado de Enfermería';

    $consultaCarpeta = "SELECT * FROM equipamiento_hbqp WHERE carpeta = 'ENFERMERIA'";
    $resConsulta = mysqli_query($conexion, $consultaCarpeta);
}


$query = "SELECT COUNT(*) as cantidadHojaTec FROM ordenes_trabajos_general as o JOIN equipamiento_hbqp as e ON e.id = o.idEquipo WHERE e.carpeta = 'ENFERMERIA' AND o.idEquipo != '';";
$res = mysqli_query($conexion, $query);

while ($row = $res->fetch_assoc()) {
    $datos = [];
    $datos['cantidadHojaTec'] = $row['cantidadHojaTec'];
    $cantidadHojaTec = $datos['cantidadHojaTec'];
}

$query = "SELECT SUM(costoTotal) as Total FROM `gastoEquipos2` as g JOIN equipamiento_hbqp as e ON e.id = g.idEquipo WHERE e.carpeta = 'ENFERMERIA';";
$res = mysqli_query($conexion, $query);

while ($row = $res->fetch_assoc()) {
    $datos = [];
    $datos['Total'] = $row['Total'];
    $totalGasto = $datos['Total'];

    $totalGasto = moneda_chilena($totalGasto);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>

    </div>
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>

            <?php

            if (isset($_GET['exitoGuardar'])) {
            ?>
                <script>
                    Swal.fire(
                        'Muy Bien!',
                        'Se guardo correctamente los datos del nuevo equipo',
                        'success'
                    )
                </script>

            <?php
            }


            ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Administración Sistema - <?php echo $titulo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->

                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="administrar-equipos-cc.php" class="btn btn-danger" style="float: right;"><b><i class="fas fa-arrow-left"></i> Volver Atras</b> </a>
                                    <div class="justify-content-center text-center">
                                        <form action="agregar-equipo.php" method="POST">

                                            <input type="hidden" value="ENFERMERIA" name="tipoEquipo">
                                            <input type="hidden" value="enfermeria" name="carpeta">
                                            <button type="submit" class="btn btn-success "><b><i class="fas fa-plus"></i> Crear un nuevo Equipo</b> </button>
                                        </form>

                                    </div>
                                    <br>
                                    <br>
                                    <br>

                                    <fieldset class="border p-3 shadow p-3 mb-5  animate__animated  animate__zoomIn rounded ">

                                        <h2 style="font-size: 19px; text-align:center;font-weight:bold; color: green;">Total de cada ítem en la carpeta</h2>
                                        <br>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="hojastecnicas-carpeta.php?id=<?php echo $carpeta ?>" style="text-decoration: none;" class="text-info">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon bg-secondary elevation-1">
                                                            <i class="fas fa-file-alt"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-center" style="font-size: 18px;"> <b>Hojas Técnicas</b> </span>
                                                            <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><?php echo $cantidadHojaTec ?></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="solicitudesCompra">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon bg-secondary elevation-1">
                                                            <i class="fas fa-cart-arrow-down"></i>
                                                        </span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text"> <b>Solicitudes de Compra</b> </span>
                                                            <span class="info-box-number"><?php echo $cantidad ?></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="archivosAdjuntosEquipos.php?idHoja=<?php echo $id ?>" style="text-decoration: none;" class="text-danger">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon bg-secondary elevation-1">
                                                            <i class="fas fa-file-upload"></i>
                                                        </span>
                                                        <div class="info-box-content">

                                                            <span class="info-box-text"> <b>Archivos Adjuntos</b> </span>
                                                            <span class="info-box-number"><?php echo $cantidad ?></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="gastoEquipo-carpeta.php?id=<?php echo $carpeta ?>" style="text-decoration: none;" class="text-primary">
                                                    <div class="info-box bg-primary">
                                                        <span class="info-box-icon bg-secondary elevation-1">
                                                            <i class="fas fa-money-check-alt"></i>
                                                        </span>
                                                        <div class="info-box-content">

                                                            <span class="info-box-text text-center" style="font-size: 18px;"> <b>Dinero Gastado</b> </span>
                                                            <span class="info-box-number badge badge-secondary" style="font-size: 20px;"><b>
                                                                    <?php

                                                                    echo $totalGasto
                                                                    ?>
                                                                </b> </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br>
                                    <hr>
                                    <h4 class="text-center"> Programación </h4>
                                    <br>
                                    <br>
                                    <hr>
                                    <table class="table table-sm table-bordered " id="tabla_equipos">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Numero Inventario</th>
                                                <th class="text-center">Nombre Equipo</th>
                                                <th class="text-center">Servicio</th>
                                                <th class="text-center">Recinto</th>
                                                <th class="text-center">Visualizar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($rowConsulta = $resConsulta->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowConsulta['id'];
                                                $datos['identificadorUnico'] = $rowConsulta['identificadorUnico'];
                                                $datos['numeroInventario'] = $rowConsulta['numeroInventario'];
                                                $datos['nombreEquipo'] = $rowConsulta['nombreEquipo'];
                                                $datos['idServicio'] = $rowConsulta['idServicio'];
                                                $datos['recinto'] = $rowConsulta['recinto'];

                                                $id = $datos['id'];
                                                $identificadorUnico = $datos['identificadorUnico'];
                                                $identificadorUnico = $datos['identificadorUnico'];
                                                $numeroInventario = $datos['numeroInventario'];
                                                $nombreEquipo = $datos['nombreEquipo'];
                                                $idServicio = $datos['idServicio'];
                                                $recinto = $datos['recinto'];
                                            ?>

                                                <tr>
                                                    <td class="text-center" style="font-size: 12px;"><?php echo $identificadorUnico ?></td>
                                                    <td class="text-center" style="font-size: 12px;"><?php echo $numeroInventario ?></td>
                                                    <td class="text-center" style="font-size: 12px;"><?php echo $nombreEquipo ?></td>
                                                    <td class="text-center" style="font-size: 12px;">
                                                        <?php

                                                        $sqlServicio = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                                        $resServicio = mysqli_query($conexion, $sqlServicio);

                                                        while ($rowServico = $resServicio->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['nomServicio'] = $rowServico['nomServicio'];
                                                            $nomServicio = $datos['nomServicio'];
                                                        }
                                                        echo $nomServicio;

                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="font-size: 12px;">
                                                        <?php

                                                        if ($recinto !== '') {
                                                            echo $recinto;
                                                        } else {
                                                            echo '-';
                                                        }

                                                        ?>
                                                    </td>
                                                    <td class="text-center">

                                                        <form action="visualizarEquipo-cc.php" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $id ?>">
                                                            <button type="submit" class="btn btn-block btn-primary btn-sm"><i class="fas fa-folder"></i></button>
                                                        </form>

                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Numero Inventario</th>
                                                <th class="text-center">Nombre Equipo</th>
                                                <th class="text-center">Servicio</th>
                                                <th class="text-center">Recinto</th>
                                                <th class="text-center">Visualizar</th>
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
                <!--/.col  -->
        </div>
        <!-- /.row -->
        </section>
        <!-- /.content -->
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
    <!-- AdminLTE App -->
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(function() {
            $("#example").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#tabla_equipos').DataTable({
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
                    "search": "Buscar:",
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