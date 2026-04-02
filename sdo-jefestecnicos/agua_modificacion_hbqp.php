<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];

if (isset($_POST['idAA'])) {

    $idAA = $_POST['idAA'];

    if ($idAA > 0) {

        $query = "SELECT * FROM agua_administracion WHERE idAA=" . $idAA;
        $res = mysqli_query($conexion, $query);
        $row = $res->fetch_assoc();
        if ($row) {
            $datos = array();
            $idAA2 = $idAA;
            $fechaDocumento = $row['fechaDocumento'];
            $tipoDocumento = $row['tipoDocumento'];
            $nDocumento = $row['nDocumento'];
            $proveedor = $row['proveedor'];
            $fechaLectura = $row['fechaLectura'];
            $nLectura = $row['nLectura'];
            $consumoAgua = $row['consumoAgua'];
            $neto = $row['neto'];
            $iva = $row['iva'];
            $montoTotalMes = $row['montoTotalMes'];
            $tipoHospital = $row['tipoHospital'];
            $nCliente = $row['nCliente'];
            $medidor = $row['medidor'];
            $estado = $row['estado'];
            $numeroOC = $row['numeroOC'];

        } else {
            header('Location: agua_administracion_hbqp.php?errorModificarConsumo=true');
        }

    } else {
        header('Location: agua_administracion_hbqp.php?errorModificarConsumo=true');
    }

} else {
    header('Location: agua_administracion_hbqp.php?errorModificarConsumo=true');
}


date_default_timezone_set('America/Santiago');


$sqlSuma = "SELECT SUM(consumoAgua) as total FROM agua_administracion WHERE tipohospital = 'HBQP'";
$resSuma = mysqli_query($conexion, $sqlSuma);
while ($rowSuma = $resSuma->fetch_assoc()) {
    $total = $rowSuma['total'];
}

$sqlMonto = "SELECT SUM(montoTotalMes) as totalMonto FROM agua_administracion WHERE tipoHospital = 'HBQP'";
$resMonto = mysqli_query($conexion, $sqlMonto);
while ($rowMonto = $resMonto->fetch_assoc()) {
    $totalMonto = $rowMonto['totalMonto'];

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificaci&oacute;n de Consumo de Agua HBQP</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                        <h4>Modificaci&oacute;n de Consumo de Agua HBQP</h4>

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

                                <a href="agua_administracion_hbqp.php" style="float: right;" class="btn bg-gradient-danger btn-md text-white text-center"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


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
                                <h5>Modificaci&oacute;n de Consumo de Agua</h5>
                                <br>

                                <br>
                                <form action="../sdo-funciones/agua_modificar_hbqp.php" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N° Orden de Compra: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control"  id="nCompra" placeholder="N° Orden de Compra" type="text" name="nCompra" value="<?php echo $numeroOC; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Ingrese Fecha del Documento: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input class="form-control" required id="fechaDocumento" placeholder="Fecha de Carga" type="datetime-local" name="fechaDocumento" value="<?php echo $fechaDocumento; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Tipo de Documento:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <select class="form-control"  required id="tipoDocumento" name="tipoDocumento">
                                                    <?php

                                                    if ($tipoDocumento == 'Boleta') {
                                                        ?>
                                                        <option value="Seleccione" disabled>Seleccione...</option>
                                                        <option value="Boleta" selected>Boleta</option>
                                                        <option value="Factura Electrónica">Factura Electrónica</option>
                                                        <?php
                                                    } else if ($tipoDocumento == 'Factura Electrónica') {
                                                        ?>
                                                        <option value="Seleccione" disabled>Seleccione...</option>
                                                        <option value="Boleta" >Boleta</option>
                                                        <option value="Factura Electrónica" selected>Factura Electrónica</option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="Seleccione" disabled selected>Seleccione...</option>
                                                        <option value="Boleta" >Boleta</option>
                                                        <option value="Factura Electrónica" >Factura Electrónica</option>
                                                        <?php
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N° del Documento:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nDocumento" required placeholder="Numero de Documento" type="number" name="nDocumento" value="<?php echo $nDocumento; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Proveedor:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="proveedor" required placeholder="Proveedor" type="text" name="proveedor" value="<?php echo $proveedor; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Fecha de Lectura:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="fechaLectura" required placeholder="Numero de Documento" type="datetime-local" name="fechaLectura" value="<?php echo $fechaLectura; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">N&uacute;mero de Lectura:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="nLectura" required placeholder="Numero de Lectura" type="number" name="nLectura" value="<?php echo $nLectura; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Consumo de Agua (Mt<sup>3</sup>):  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="consumoAgua" required placeholder="Consumo de Agua" type="number" name="consumoAgua" value="<?php echo $consumoAgua; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Valor Neto:  <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorNeto" required min="0" placeholder="Valor Neto" type="number" name="valorNeto" value="<?php echo $neto; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">IVA: <code>*</code> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorIVA" min="0" required placeholder="IVA" type="number" name="valorIVA" value="<?php echo $iva; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Monto Total del Mes y Documento: <code>*</code> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <input class="form-control" id="valorTotal" min="0" required placeholder="Valor Total" type="number" name="valorTotal" value="<?php echo $montoTotalMes; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputTerreno1">Estado: <code>*</code> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                </div>
                                                <select class="form-control" required id="estadoPagado" name="estadoPagado">

                                                    <?php

                                                    $estado = mb_convert_case($estado,MB_CASE_LOWER,'utf-8');

                                                    if ($estado == 'pagado') {
                                                        ?>
                                                        <option value="Seleccione" disabled>Seleccione...</option>
                                                        <option value="Pagado" selected>Pagado</option>
                                                        <option value="No Pagado">No Pagado</option>
                                                        <?php
                                                    } else if ($estado == 'no pagado') {
                                                        ?>
                                                        <option value="Seleccione" disabled>Seleccione...</option>
                                                        <option value="Pagado">Pagado</option>
                                                        <option value="No Pagado" selected>No Pagado</option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="Seleccione" disabled selected>Seleccione...</option>
                                                        <option value="Pagado">Pagado</option>
                                                        <option value="No Pagado">No Pagado</option>
                                                        <?php
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <?php

                                        $getMedidorAguaHBQP = getMedidorAguaHBQP();

                                        ?>
                                        <input type="hidden" name="tipoHospital" id="tipoHospital" value="HBQP">
                                        <input type="hidden" name="nCliente" id="nCliente" value="<?php echo $nCliente; ?>">
                                        <input type="hidden" name="idAA" id="idAA" value="<?php echo $idAA; ?>">
                                        <input type="hidden" name="medidor" id="medidor" value="<?php echo $medidor; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                    </div>
                                </form>


                                <!--HASTA AQUI-->

                                <hr>


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
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            // "buttons": [{
            //     extend: 'excelHtml5',
            //     className: 'btn btn-success',
            //     text: 'Exportar a Excel',
            //     title: 'Resumen Gasolina',
            //     exportOptions: {
            //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            //         format: {
            //             body: function(data, column, row) {
            //                 if (typeof data === 'string' || data instanceof String) {
            //                     data = data.replace(/<br\s*\/?>/ig, "\r\n");
            //                 }
            //                 return data;
            //             }
            //         }
            //     }
            // }],
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


<script type="text/javascript">
    $("input[name='opciones']").change(function () {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>
</body>

</html>