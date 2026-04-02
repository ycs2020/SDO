<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Compra - SDO</title>

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


    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>


</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h4>Solicitudes de Compras - SDO - Resumen</h4>

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

                    <!-- Timelime example  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                    <h5>Listado de Solicitudes de Compra - Resumen</h5>
                                    <hr>

                                    <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Solicitante
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Numero Interior SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Area de derivación
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Ordenes de compra
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Envio OC Proveedor
                                                </th>

                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado General Solicitud
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            //CONSULTA SC SDO
                                            $sqlSC = "SELECT * FROM solicitudes_sc_sdo_resumen ORDER BY idSoliCompra DESC";
                                            $res = mysqli_query($conexion, $sqlSC);



                                            while ($rowSC = $res->fetch_assoc()) {

                                                $datos = [];

                                                $datos['fechaSDA'] = $rowSC['fechaSDA'];
                                                $fechaSDA = $datos['fechaSDA'];

                                            ?>

                                                <tr>

                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $idSoliCompra  = mb_strtoupper($rowSC['idSoliCompra']);
                                                        echo $idSoliCompra;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $descripcion = $rowSC['descripcion'];
                                                        echo substr($descripcion, 0, 50) . '...';
                                                        // echo $argumentosSoliCompra;


                                                        ?>
                                                        <button class="btn  btn-xs btn-success" id='#idHerramienta<?php echo $idSoliCompra ?>' data-toggle='modal' data-target='#idHerramienta<?php echo $idSoliCompra ?>'> <b>Ver mas</b> </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="idHerramienta<?php echo $idSoliCompra ?>" name="idHerramienta<?php echo $idSoliCompra ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="EjemploModalLabel">Descripcion Solicitud</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <?php

                                                                        $sqlDescript = "SELECT descripcion FROM solicitudes_sc_sdo_resumen WHERE idSoliCompra = ${idSoliCompra}";
                                                                        $resDescript = mysqli_query($conexion, $sqlDescript);

                                                                        while ($rowDescript = $resDescript->fetch_assoc()) {

                                                                            $descript = $rowDescript['descripcion'];
                                                                        }


                                                                        ?>

                                                                        <div style="text-align:left;" class="form-group">
                                                                            <textarea type="text" readonly class="form-control" rows="10"><?php echo $descript ?></textarea>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowSC['fechaSoliCompra']))));
                                                        $inicioHora = date('H:i', strtotime($rowSC['fechaSoliCompra']));

                                                        echo $inicioFecha . '<br>' . $inicioHora;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $solicitante = mb_strtoupper($rowSC['solicitante']);

                                                        $solicitante = explode(' (', $solicitante);

                                                        $unidadSoliCompra = mb_strtoupper($rowSC['unidadSoliCompra']);

                                                        if (empty($solicitante[1])) {
                                                            echo $solicitante[0] . '<br>(' . $unidadSoliCompra . ')';
                                                        } else {
                                                            echo $solicitante[0] . '<br>(' . $solicitante[1] . '<br>(' . $unidadSoliCompra . ')';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php


                                                        if ($rowSC['numInterior'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['numInterior']);
                                                        }

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['estadoSDA'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['estadoSDA']);
                                                        }


                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($fechaSDA == '') {
                                                            echo 'SIN INFORMACIÓN';
                                                        } else {

                                                            $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $rowSC['fechaSDA']))));
                                                            $inicioHora = date('H:i', strtotime($rowSC['fechaSDA']));

                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                        }




                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['areaDerivacion'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['areaDerivacion']);
                                                        }



                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php

                                                        if ($rowSC['ordenCompra'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['ordenCompra']);
                                                        }


                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php


                                                        if ($rowSC['fechaEnvioOC'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['fechaEnvioOC']);
                                                        }


                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php


                                                        if ($rowSC['valor'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['valor']);
                                                        }



                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        
                                                        if ($rowSC['estadoSolicitud'] == '') {
                                                            echo "SIN INFORMACIÓN";
                                                        } else {
                                                            echo mb_strtoupper($rowSC['estadoSolicitud']);
                                                        }


                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>


                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    N°
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Descripcion
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Solicitud
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Solicitante
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Numero Interior SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha SDA
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Area de derivación
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Ordenes de compra
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Fecha Envio OC Proveedor
                                                </th>

                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Valor
                                                </th>
                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    Estado General Solicitud
                                                </th>
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
    <script type="text/javascript">
        function verMas(id) {

            vermas = document.getElementById('vermas');

            vermas.addEventListener('click', function(e) {
                e.preventDefault();
            })

            event.preventDefault();
            console.log('click en ver mas: ', id);
        }
    </script>


</body>

</html>