<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_POST['idCategoria'])){
    $idCategoria = $_POST['idCategoria'];
}


$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Equipos al Inventario</title>

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
    <script>
        function select2(e){
            var select = document.getElementById("tipoDocumento").value;
            var scsdo = document.getElementById("sc-sdo");
            var scpm = document.getElementById("sc-pm");
            var ce = document.getElementById("ce");
            var ci = document.getElementById("ci");
            var oc = document.getElementById("oc");

            if(select === 'Solicitud de Compra - SDO'){
                scsdo.style.display = "block";
                scpm.style.display = "none";
                ce.style.display = "none";
                ci.style.display = "none";
                oc.style.display = "none";
            }else if(select === 'Solicitud de Compra - PM'){
                scpm.style.display = "block";
                scsdo.style.display = "none";
                ce.style.display = "none";
                ci.style.display = "none";
                oc.style.display = "none";
            }else if(select === 'Cotizacion Empresa'){
                ce.style.display = "block";
                scsdo.style.display = "none";
                scpm.style.display = "none";
                ci.style.display = "none";
                oc.style.display = "none";
            }else if(select === 'Cotizacion Internet'){
                ce.style.display = "none";
                scsdo.style.display = "none";
                scpm.style.display = "none";
                ci.style.display = "block";
                oc.style.display = "none";
            }else if(select === 'Orden de Compra'){
                ce.style.display = "none";
                scsdo.style.display = "none";
                scpm.style.display = "none";
                ci.style.display = "none";
                oc.style.display = "block";
            }
        }
    </script>
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
                        <h4>Ingresar al Inventario de Equipos</h4>
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
                                <a href="equipamiento_hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <form action="../sdo-funciones/agregar-equipos-inventario.php" method="POST" enctype="multipart/form-data">
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Proveedor:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Señor(ES): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="text" id="nomProveedor" name="nomProveedor" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Rut: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="text" id="rutProveedor" name="rutProveedor" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Producto:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Fecha de Ingreso del Equipo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                     date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" readonly="readonly" id="fechaEquipo" name="fechaEquipo" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Nombre del Producto: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-file-signature"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="nombreEquipo" id="nombreEquipo" placeholder="Ingrese el Nombre del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Marca del Producto: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="marcaEquipo" id="marcaEquipo" placeholder="Ingrese la Marca del Equipo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Modelo del Producto: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="modeloEquipo" id="modeloEquipo" placeholder="Ingrese el Modelo del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Cantidad: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-truck-loading"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" name="cantidadEquipo" id="cantidadEquipo" placeholder="Ingrese la Cantidad del Equipo">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="exampleInputTurno">Tipo de Documento: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-truck-loading"></i></span>
                                                    </div>
                                                    <select id="tipoDocumento" name="tipoDocumento" onchange="select2(event)" class="form-control">
                                                            <option value="" selected disabled>--Seleccione--</option>
                                                            <option value="Solicitud de Compra - SDO">Solicitud de Compra - SDO</option>
                                                            <option value="Solicitud de Compra - PM">Solicitud de Compra - PM</option>
                                                            <option value="Cotizacion Empresa">Cotizacion Empresa</option>
                                                            <option value="Cotizacion Internet">Cotizacion Internet</option>
                                                            <option value="Orden de Compra">Orden de Compra</option>
                                                            <option value="Otros">Otros</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="sc-sdo" class="form-group col-md-3" style="display: none">
                                                <label for="exampleInputTurno">Solicitud de Compra - SDO: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                    </div>
                                                    <input list="sc-sdo1" class="form-control" name="sc-sdo1">
                                                    <datalist id="sc-sdo1">
                                                        <?php
                                                        $sqlscsdo = "SELECT * FROM solicitudes_compra_sdo WHERE estadoSoliCompra = 'Solicitud Completada'";
                                                        $resscsdo = mysqli_query($conexion,$sqlscsdo);
                                                        while($rowscsdo = $resscsdo->fetch_assoc()){
                                                        $folioSoliCompra = $rowscsdo['folioSoliCompra'];
                                                        ?>
                                                        <option value="<?php echo $folioSoliCompra?>">
                                                            <?php
                                                            }
                                                            ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div id="ce" class="form-group col-md-3" style="display: none">
                                                <label for="exampleInputTurno">Cotizacion Empresa: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                    </div>
                                                    <input type="file" class="form-control" name="ce1" id="ce1">
                                                </div>
                                            </div>
                                            <div id="ci" class="form-group col-md-3" style="display: none">
                                                <label for="exampleInputTurno">Cotizacion Internet: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                    </div>
                                                    <input type="file" class="form-control" name="ci1" id="ci1">
                                                </div>
                                            </div>
                                            <div id="oc" class="form-group col-md-3" style="display: none">
                                                <label for="exampleInputTurno">Orden de Compra: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                    </div>
                                                    <input class="form-control" name="oc1" id="oc1" value="N°: 1704-">
                                                </div>
                                            </div>
                                            <div id="sc-pm" class="form-group col-md-3" style="display: none">
                                                <label for="exampleInputTurno">Solicitud de Compra - PM: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clipboard"></i></span>
                                                    </div>
                                                    <input list="sc-pm1" class="form-control" name="sc-pm1">
                                                    <datalist id="sc-pm1">
                                                        <?php
                                                        $sqlscpm = "SELECT * FROM solicitudes_compra_pm";
                                                        $resscpm = mysqli_query($conexion,$sqlscpm);
                                                        while($rowscpm = $resscpm->fetch_assoc()){
                                                        $folioSoliCompraPm = $rowscpm['folioSoliCompra'];
                                                        ?>
                                                        <option value="<?php echo $folioSoliCompraPm?>">
                                                            <?php
                                                            }
                                                            ?>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exampleInputTurno">Precio Unitario (S/IVA): <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-cash-register"></i></span>
                                                    </div>
                                                    <input class="form-control" type="text" name="precioUnitario" id="precioUnitario" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">

                                        </div>
                                    </fieldset>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Observaciones:&nbsp;&nbsp;</legend>
                                        <textarea class="form-control" id="obs" name="obs" rows="5"></textarea>
                                    </fieldset>
                                    <div class="form-group">
                                        <br>
                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                    </div>
                                </form>
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


