<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {

    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $unidadSoliCompra = $datosSoliCompra['unidadSoliCompra'];
        $emailSoliCompra = $datosSoliCompra['emailSoliCompra'];

        $argumentosSoliCompra = $datosSoliCompra['argumentosSoliCompra'];

        $adjuntaCotizacion = $datosSoliCompra['adjuntaCotizacion'];

        $siCotiza = '';

        $noCotiza = '';

        if ($adjuntaCotizacion == 1) {

            $siCotiza = 'X';
        } else if ($adjuntaCotizacion == 0) {

            $noCotiza = 'X';
        } else {

            $noCotiza = 'X';
        }

        $estadoSoliCompra = $datosSoliCompra['estadoSoliCompra'];

        $fechaSC = strtotime($fechaSoliCompra);
        $fechaSC = fechaEspañol2($fechaSC);

        $horaSC = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $unidadSoliCompra = $datosSoliCompra['unidadSoliCompra'];
        $emailSoliCompra = $datosSoliCompra['emailSoliCompra'];

        $argumentosSoliCompra = $datosSoliCompra['argumentosSoliCompra'];

        $adjuntaCotizacion = $datosSoliCompra['adjuntaCotizacion'];

        $siCotiza = '';

        $noCotiza = '';

        if ($adjuntaCotizacion == 1) {

            $siCotiza = 'X';
        } else if ($adjuntaCotizacion == 0) {

            $noCotiza = 'X';
        } else {

            $noCotiza = 'X';
        }

        $estadoSoliCompra = $datosSoliCompra['estadoSoliCompra'];

        $fechaSC = strtotime($fechaSoliCompra);
        $fechaSC = fechaEspañol2($fechaSC);

        $fechaSC = trim($fechaSC);

        $horaSC = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_pm_administracion.php?errorSolicitudCompra=true');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra</title>

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

</head>

<body class="hold-transition sidebar-mini">
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
                            <h4>Solicitudes de Compras para Puesta en Marcha</h4>

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

            if (isset($_GET['exitoCrearSC'])) {
            ?>
                <script>
                    Swal.fire({
                        title: 'Solicitud de Compra Ingresada con &Eacute;xito.',
                        confirmButtonText: 'Cerrar',
                        icon: 'success',
                    }).then((result) => {
                        <?php
                        if ($_GET['material'] == "Con") {
                        ?>
                            Swal.fire('Materiales solicitados con &eacute;xito.', '', 'success')
                        <?php
                        } else if ($_GET['material'] == "Sin") {
                        ?>
                            Swal.fire('Solicitud sin Materiales espec&iacute;ficos.', '', 'success')
                        <?php
                        } else {
                        ?>
                            Swal.fire('Error al Solicitar Materiales.', '', 'error')
                        <?php
                        }
                        ?>
                    })
                </script>
            <?php
            }
            if (isset($_GET['errorCrearSC'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al crear la Solicitud de Compra',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['errorAsignarNumInterno'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'Error al Ingresar N&uacute; Interno a la Solicitud de Compra',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['existeAsignarNumInterno'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Ha ocurrido un Problema !',
                        'El N&uacute;mero Ingresado ya existe en el Sistema. Reintente.',
                        'error'
                    )
                </script>
            <?php
            }

            if (isset($_GET['exitoAsignarNumInterno'])) {
            ?>
                <script>
                    Swal.fire(
                        '¡ Actualizaci&oacute;n Realizada con &Eacute;xito. !',
                        'El N&uacute;mero Interno de esta Solicitud ha sido actualizado.',
                        'success'
                    )
                </script>
            <?php
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
                                    <a href="soliCompra_pm_administracion.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <h5><b>Solicitud de Compra: </b><?php echo $folioSoliCompra; ?></h5>

                                    <h5><b>Personal Solicitante: </b><?php echo $personalSoliCompra; ?></h5>

                                    <h5><b>Unidad Solicitante: </b><?php echo $unidadSoliCompra; ?></h5>

                                    <?php
                                    $fechaSoliCompra2 = strtotime($fechaSoliCompra);
                                    ?>

                                    <h5><b>Fecha de Solicitud: </b><?php echo fechaEspañol($fechaSoliCompra2); ?></h5>



                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu2" name="rowMenu2">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Anular Solicitud de Compra de Materiales</h5>
                                    <hr>
                                    <form action="../sdo-funciones/soliCompra_anularSolicitudPM.php" method="post">
                                        <div class="form-group">
                                            <label for="exampleInputArgumentos">Razones de Anulaci&oacute;n de la Solicitud:<code>*</code></label>
                                            <textarea id="razonesAnulacion" style="resize:none;" required name="razonesAnulacion" class="form-control" rows="10" placeholder="Razones de Anulaci&oacute;n de Solicitud"></textarea>
                                        </div>


                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                                            <input type="hidden" name="cargoSC" id="cargoSC" value="Mesa de Ayuda - SDO">
                                            <input type="hidden" name="deptoSC" id="deptoSC" value="Subdirección de Operaciones">
                                            <input type="hidden" name="emailSC" id="emailSC" value="<?php echo $emailJefe; ?>">
                                            <input type="hidden" name="idSoliCompra2" id="idSoliCompra2" value="<?php echo $idSoliCompra; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>


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
            $('#solicitudesCompraPM').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": true,
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
                }
            });
        });
    </script>
    <script src="js/dark-mode.js"></script>
</body>

</html>