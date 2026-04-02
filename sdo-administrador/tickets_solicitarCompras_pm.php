<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idSoliCompraZip'])) {

    $idSoliCompraZip = $_POST['idSoliCompraZip'];

    if ($idSoliCompraZip > 0) {

        $nombreAmigable = 'Cotizaciones de Solicitud de Compra SC-' . $idSoliCompraZip . '.zip';

        require('../dist/pclzip/pclzip.lib.php');
        $zip = new PclZip($nombreAmigable);
        $zip->create('../sdo-soliCompra/SC-' . $idSoliCompraZip . '');


        // Send the file for download!
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=$nombreAmigable");
        header("Content-Description: File Transfer");
        @readfile($nombreAmigable);
    }
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSolicitud'])) {
    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));

        $_SESSION['idSolicitud'] = $idSolicitud;
    }
} else if (isset($_SESSION['idSolicitud'])) {
    $idSolicitud = trim($_SESSION['idSolicitud']);

    if ($idSolicitud == 0 || empty($idSolicitud)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSolicitud = array();
        $datosSolicitud = getSolicitudSoporte($idSolicitud);

        $idSolicitud2 = $datosSolicitud['idSolicitud'];
        $folioSolicitud = $datosSolicitud['folioSolicitud'];
        $aleatorioSolicitud = $datosSolicitud['aleatorioSolicitud'];
        $nomSolicitante = $datosSolicitud['nomSolicitante'];
        $rutSolicitante = $datosSolicitud['rutSolicitante'];
        $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

        $deptoSolicitante = explode('/', $deptoSolicitante);

        $longitud_deptoSolicitante = count($deptoSolicitante);

        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

        $emailSolicitante = $datosSolicitud['emailSolicitante'];
        $telefonoSolicitante = $datosSolicitud['telefonoSolicitante'];
        $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];
        $deptoResponsable = $datosSolicitud['deptoResponsable'];
        $tipoProblemaSolicitud = $datosSolicitud['tipoProblemaSolicitud'];
        $problemaSolicitud = $datosSolicitud['problemaSolicitud'];
        $inicioSolicitud = $datosSolicitud['inicioSolicitud'];
        $terminoSolicitud = $datosSolicitud['terminoSolicitud'];
        $situacionSolicitud = $datosSolicitud['situacionSolicitud'];
        $tecnicoSolicitud = $datosSolicitud['tecnicoSolicitud'];
        $canceladaSolicitud = $datosSolicitud['canceladaSolicitud'];
        $finalizadaSolicitud = $datosSolicitud['finalizadaSolicitud'];

        $fechaInicio = strtotime($inicioSolicitud);
        $fechaInicio = fechaEspañol2($fechaInicio);

        $horaInicio = date('H:i', strtotime($inicioSolicitud));
    }
} else {
    header('Location: solicitudes_detalle.php?errorSolicitudCompra=true');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitud de Compra</title>

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
                            <h4>Revisi&oacute;n Solicitudes de Compra</h4>
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
                        'El N&uacute;mero ingresado ya existe en el Sistema. Reintente.',
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
                                    <h5>Solicitud de Soporte:<br><b>#<?php echo $folioSolicitud; ?></b></h5>
                                    <a href="solicitudes_detalle.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

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
                                    <h5>Listado de Solicitudes de Compra</h5>
                                    <hr>

                                    <div class="table-responsive">
                                        <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Personal Solicitante</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Folio de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>N° Interno de <br>de Puesta en Marcha</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Estado de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ver Solicitud de Compra</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Asignar N&uacute;mero Interno<br>de Puesta en Marcha</center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $query = "SELECT * FROM solicitudes_compra_tickets_pm WHERE idSolicitudSoporte = '" . $idSolicitud . "' ORDER BY fechaSoliCompra DESC";

                                                $res = mysqli_query($conexion, $query);

                                                $numSolicitud = 1;

                                                while ($row = $res->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <center>
                                                                <?php if ($row['fechaSoliCompra'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {

                                                                    $inicioFecha =  date('d/M/Y', (strtotime(str_replace('/', '-', $row['fechaSoliCompra']))));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaSoliCompra']));

                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                } ?>
                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                            $personalSoliCompra = mb_strtoupper($row['personalSoliCompra']);
                                                            $unidadSoliCompra = mb_strtoupper($row['unidadSoliCompra']);
                                                            echo $personalSoliCompra . '<br>(' . $unidadSoliCompra . ')';

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                            $folioSoliCompra = mb_strtoupper($row['folioSoliCompra']);
                                                            echo $folioSoliCompra;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                            $numSoliCompra = mb_strtoupper($row['numSoliCompra']);

                                                            if ($numSoliCompra == 0) {
                                                                echo "SIN N&Uacute;MERO ASIGNADO";
                                                            } else {
                                                                echo $numSoliCompra;
                                                            }



                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                            $estadoSoliCompra = mb_strtoupper($row['estadoSoliCompra']);
                                                            echo $estadoSoliCompra;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idSoliCompra = $row['idSoliCompra'];
                                                                $idSoliCompra = trim($idSoliCompra);
                                                                if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <form style="margin: 0; text-align: center;" class="" action="tickets_visualizarSC_pm.php" method="post">

                                                                        <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                        <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                        <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                                    </form>

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idSoliCompra = $row['idSoliCompra'];
                                                                $idSoliCompra = trim($idSoliCompra);
                                                                if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <form method="post" action="../sdo-funciones/soliCompra_agregarNumero_ticket_pm.php">
                                                                        <button type="button" data-toggle="modal" data-target="#modalAddNumInterno<?php echo $idSoliCompra ?>" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="modalAddNumInterno<?php echo $idSoliCompra ?>" name="modalAddNumInterno<?php echo $idSoliCompra ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar N&uacute;mero Interno de PM a <?php echo $row['folioSoliCompra'] ?></h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">N&uacute;mero Interno de Puesta en Marcha: <code>*</code></label>
                                                                                            <input type="number" min="1" class="form-control form-control-border" id="addNumInterno" name="addNumInterno" placeholder="N&uacute;mero Interno de Puesta en Marcha" value="" required>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <input type="hidden" name="idSoliCompra" id="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>


                                                    <?php

                                                    $numSolicitud = $numSolicitud + 1;
                                                }
                                                    ?>

                                                    </tr>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Personal Solicitante</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Folio de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>N° Interno de <br>de Puesta en Marcha</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Estado de Solicitud</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Ver Solicitud de Compra</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Asignar N&uacute;mero Interno<br>de Puesta en Marcha</center>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
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

</body>

</html>