<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

$idDepartamento = $_SESSION['idDepartamento'];

if (isset($_POST['idEquipo'])) {
    $idEquipo = $_POST['idEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $idCarpeta = $_POST['idCategoria'];
    //echo $idCarpeta;
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }

    $_SESSION["idEquipo"] = $idEquipo;
    $_SESSION["idCategoria"] = $idCategoria;

} else if ($_SESSION["idEquipo"]) {
    $idEquipo = $_SESSION["idEquipo"];
    $idCategoria = $_SESSION["idCategoria"];
    $idCarpeta = $_POST['idCategoria'];
    //echo $idCarpeta;
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipos - Gastos</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <style>
        #mesInforme {
            display: none;
        }
        #rangoFecha {
            display: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a href="equipo.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Equipo <?php echo $nombreEquipo ?> - Gastos </h1>
                                        <hr>
                                        <br>
                                        <button class="btn btn-primary text-center " data-toggle="modal" data-target="#modalGasto"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Gasto al Equipo</b> </button>
                                        <div class="modal fade " tabindex="-1" id="modalGasto" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del gasto</b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-funciones/guardar-gasto-carpeta-inge-clinica.php" method="POST" enctype="multipart/form-data">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Mantenimiento:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" required id="nomMantenimiento" name="nomMantenimiento">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hoja Técnica Asociada:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                                                                            <input type="file" class="custom-file-input" id="registrosAc" name="registrosAc">
                                                                            <label class="custom-file-label" for="customFileInge" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo de Intervención:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                        </div>
                                                                        <select class="form-control" id="tipoIntervencion1" name="tipoIntervencion1">
                                                                            <option value="" selected disabled>Seleccion...</option>
                                                                            <option value="MPPC">MPPC</option>
                                                                            <option value="MPPA">MPPA</option>
                                                                            <option value="PREDEC">PREDEC.</option>
                                                                            <option value="REPARAT">REPARAT.</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="solicitudCompraAc" style="font-size:12px">Subir solicitud de Compra Asociada:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                        </div>
                                                                        <input type="file" class="custom-file-input" id="solicitudCompraAc" name="solicitudCompraAc">
                                                                        <label class="custom-file-label" for="solicitudCompraAc" style="font-size:12px;"><b>Seleccionar Archivo...</b></label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Código Orden Compra:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                                        </div>
                                                                        <input type="text" min="1" class="form-control form-control-sm" id="codOrdenCompra" name="codOrdenCompra">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Orden de Compra:</label><br>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" id="archivoOC" name="archivoOC">
                                                                        <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-8">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Total:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                        </div>
                                                                        <input type="number" required  class="form-control form-control-sm" id="costoTotal" name="costoTotal">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                                        </div>
                                                                        <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-8">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Encargado Mantenimiento:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" id="encargadoMant" name="encargadoMant">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria ?>">
                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" class="btn btn-success"> <b>Añadir Gasto</b> </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Trabajo Realizado</th>
                                                <th scope="col" class="text-center">Hojas Técnicas</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Orden de Compra</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM gasto_equipo_carpeta_inge_clinica WHERE idEquipo = $idEquipo ORDER BY fechaGasto DESC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowEquipo['id'];
                                                $datos['fechaRegistro'] = $rowEquipo['fechaRegistro'];
                                                $datos['fechaGasto'] = $rowEquipo['fechaGasto'];
                                                $datos['mesGasto'] = $rowEquipo['mesGasto'];
                                                $datos['nombreMantenimiento'] = $rowEquipo['nombreMantenimiento'];
                                                $datos['idHojaTecnica'] = $rowEquipo['idHojaTecnica'];
                                                $datos['tipoIntervencion'] = $rowEquipo['tipoIntervencion'];
                                                //$datos['solicitudCompra'] = $rowEquipo['solicitudCompra'];
                                                $datos['codOrdenCompra'] = $rowEquipo['codOrdenCompra'];
                                                $datos['costoTotal'] = $rowEquipo['costoTotal'];
                                                $datos['encargadoMant'] = $rowEquipo['encargadoMant'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                $idGasto = $datos['id'];
                                                $fechaRegistro = $datos['fechaRegistro'];
                                                $mesGasto = $datos['mesGasto'];
                                                $fechaGasto = $datos['fechaGasto'];
                                                $nomMantenimiento = $datos['nombreMantenimiento'];
                                                $tipoMantencion = $datos['tipoIntervencion'];
                                                //$solicitudCompra = $datos['solicitudCompra'];
                                                $codOrdenCompra = $datos['codOrdenCompra'];
                                                $costoTotal = $datos['costoTotal'];
                                                $encargadoMant = $datos['encargadoMant'];
                                                $idEquipo = $datos['idEquipo'];

                                                $formatoFecha = date('d/m/Y', strtotime($fechaGasto));



                                                $costoTotal = moneda_chilena($costoTotal);

                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $num ?></td>
                                                    <td class="text-center"><?php echo $formatoFecha ?></td>
                                                    <td class="text-center"><?php echo $tipoMantencion ?></td>
                                                    <td class="text-center"><?php echo $nomMantenimiento ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        $sqlHT = "SELECT COUNT(*) as totalHT FROM gastos_ht_archivo_inge_clinica WHERE idGasto = $idGasto";
                                                        $resHT = mysqli_query($conexion,$sqlHT);
                                                        while($rowHT = $resHT->fetch_assoc()){
                                                            $totalHT = $rowHT['totalHT'];
                                                        }
                                                        if($totalHT > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGastoHT" value="<?php echo $idGasto ?>">
                                                                <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Hoja Técnica</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $sqlSoliCompra = "SELECT COUNT(*) as totalSoli FROM gastos_sc_archivo_inge_clinica WHERE idGasto = $idGasto";
                                                        $resSoliCompra = mysqli_query($conexion,$sqlSoliCompra);
                                                        while($rowSoliCompra = $resSoliCompra->fetch_assoc()){
                                                            $totalSC = $rowSoliCompra['totalSoli'];
                                                        }
                                                        if($totalSC > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGastoSC" value="<?php echo $idGasto ?>">
                                                                <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Solicitud de Compra</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $sqlOrdenCompra = "SELECT COUNT(*) AS total FROM `gastos_oc_archivo_inge_clinica` WHERE idGasto = $idGasto";
                                                        $resOrdenCompra = mysqli_query($conexion,$sqlOrdenCompra);
                                                        while($rowOrdenCompra = $resOrdenCompra->fetch_assoc()){
                                                            $total = $rowOrdenCompra['total'];
                                                        }
                                                        if($total > 0){
                                                            ?>
                                                            <form action="carpeta-gastos-inge-clinica.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                                                <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta?>">
                                                                <button type="submit" class="btn btn-block btn-info "><i class="fas fa-download"></i> Descargar Orden de Compra</button>
                                                            </form>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            Sin Información
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-success text-bold"><?php echo $costoTotal ?></td>
                                                    <td class="text-center">
                                                        <?php echo $encargadoMant?>
                                                    </td>
                                                    <td>
                                                        <form action="editar-gasto-equipo-inge-clinica.php" style="display: inline;" method="POST">
                                                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                            <input type="hidden" name="idGasto" value="<?php echo $idGasto ?>">
                                                            <button type="submit" class="btn btn-sm bg-pink "><i class="fas fa-pen"></i></button>
                                                        </form>
                                                        <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idGasto ?>"><i class="fas fa-trash-alt"></i></a>
                                                    </td>

                                                </tr>
                                                <?php
                                                $num++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Tipo</th>
                                                <th scope="col" class="text-center">Trabajo Realizado</th>
                                                <th scope="col" class="text-center">Hojas Técnicas</th>
                                                <th scope="col" class="text-center">Solicitud Compra</th>
                                                <th scope="col" class="text-center">Orden de Compra</th>
                                                <th scope="col" class="text-center">Monto</th>
                                                <th scope="col" class="text-center">Encargado de Mantenimiento</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
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

<script>
    $(document).ready(
        $('.eliminar').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            console.log(dataString);
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este gasto?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , eliminar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/eliminar-gasto-equipo-inge.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Gasto eliminado con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>

<!-- ./wrapper -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
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
</body>
</html>
