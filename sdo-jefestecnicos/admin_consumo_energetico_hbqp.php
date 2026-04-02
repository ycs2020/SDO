<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');

if(isset($_POST['idAE'])){
    $idAE = $_POST['idAE'];
    $sqlelectrico = "SELECT * FROM electricidad_administracion WHERE idAE = $idAE";
    $reselectrico = mysqli_query($conexion,$sqlelectrico);
    while($rowelectrico = $reselectrico->fetch_assoc()){
        $fechaDocumento = $rowelectrico['fechaDocumento'];
        $tipoDocumento = $rowelectrico['tipoDocumento'];
        $nDocumento = $rowelectrico['nDocumento'];
        $proveedor = $rowelectrico['proveedor'];
        $fechaLectura = $rowelectrico['fechaLectura'];
        $consumoEnergia = $rowelectrico['consumoEnergia'];
        $neto = $rowelectrico['neto'];
        $iva = $rowelectrico['iva'];
        $exento = $rowelectrico['exento'];
        $montoTotal = $rowelectrico['montoTotalMes'];
        $estado = $rowelectrico['estado'];
        $numeroOC = $rowelectrico['numeroOC'];
        $notas = $rowelectrico['notas'];
    }
}else if(isset($_SESSION['idAE'])){
    $idAE = $_POST['idAE'];
    $sqlelectrico = "SELECT * FROM electricidad_administracion WHERE idAE = $idAE";
    $reselectrico = mysqli_query($conexion,$sqlelectrico);
    while($rowelectrico = $reselectrico->fetch_assoc()){
        $fechaDocumento = $rowelectrico['fechaDocumento'];
        $tipoDocumento = $rowelectrico['tipoDocumento'];
        $nDocumento = $rowelectrico['nDocumento'];
        $proveedor = $rowelectrico['proveedor'];
        $fechaLectura = $rowelectrico['fechaLectura'];
        $consumoEnergia = $rowelectrico['consumoEnergia'];
        $neto = $rowelectrico['neto'];
        $iva = $rowelectrico['iva'];
        $exento = $rowelectrico['exento'];
        $montoTotal = $rowelectrico['montoTotalMes'];
        $estado = $rowelectrico['estado'];
        $numeroOC = $rowelectrico['numeroOC'];
        $notas = $rowelectrico['notas'];
    }
}else{

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Informaci&oacute;n del Consumo</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Informaci&oacute;n del Consumo</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="electricidad_administracion_hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n del Consumo: Energia HBQP&nbsp;</legend>
                                    <h5>
                                        <b>N° de Compra: </b>
                                        <?php
                                            if($numeroOC == 0 || empty($numeroOC) || $numeroOC == ''){
                                                echo 'Orden de Compra no Registrada';
                                            }else{
                                                echo $numeroOC;
                                            }
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha del Documento: </b>
                                        <?php
                                        $registrofechaAC = date('d/m/Y', strtotime(str_replace('/', '-', $fechaDocumento)));
                                        echo $registrofechaAC;
                                        ?>
                                    </h5>
                                    <h5><b>Tipo de Documento: </b><?php echo $tipoDocumento?></h5>
                                    <h5><b>Número del Documeto: </b><?php echo $nDocumento?></h5>
                                    <h5><b>Proveedor: </b><?php echo $proveedor?></h5>
                                    <h5><b>Consumo de Energia (kwH): </b><?php echo number_format($consumoEnergia, 0, ",", ".");?></h5>
                                    <h5><b>Estado: </b><?php echo $estado?></h5>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Notas y/o Observaciones:&nbsp;</legend>
                                    <table id="observacionTotal" class="table table-secondary">
                                        <thead class="thead-dark text-center">
                                            <tr>
                                                <th>Fecha de Ingreso</th>
                                                <th>Nombre del Creador</th>
                                                <th>Detalle Observacion</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sqlelectricoobs = "SELECT * FROM electricidad_observacion WHERE idAE = $idAE";
                                            $reselectricoobs = mysqli_query($conexion,$sqlelectricoobs);
                                            while($rowelectricoobs = $reselectricoobs->fetch_assoc()){
                                                $fechaIngreso = $rowelectricoobs['fechaIngreso'];
                                                $detalleObs = $rowelectricoobs['detalleObservacion'];
                                                $nomCreador = $rowelectricoobs['nomCreador'];
                                                ?>
                                                    <tr>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                            $registrofechaAC1 = date('d/m/Y', strtotime(str_replace('/', '-', $fechaIngreso)));
                                                            echo $registrofechaAC1;
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                                echo $nomCreador;
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align: middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                                echo $detalleObs;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Administraci&oacute;n del Consumo:&nbsp;</legend>
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-2">
                                            <?php
                                                if($idAE == 0 || $idAE == '' || empty($idAE)){
                                                    ?>
                                                    <button title="Error al Obtener ID de Acta. Opcion Deshabilitada." disabled="disabled" type="submit" id="inputMozi" class="btn btn-block bg-warning"><i class="fas fa-parking"></i>&nbsp;&nbsp;Moficar Consumo</button>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <form action="editar_consumo_energetico.php" method="POST">
                                                        <input name="idAE" id="idAE" type="hidden" value="<?php echo $idAE?>">
                                                        <button  type="submit" id="inputMozi" class="btn btn-block bg-warning"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar Consumo</button>
                                                    </form>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ingresarObservacionTotal"><i class="fas fa-eye"></i>
                                                Agregar Observacion
                                            </button>
                                            <div class="modal fade" id="ingresarObservacionTotal" name="ingresarObservacionTotal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <form method="POST" action="../sdo-funciones/ingresar-observacion-consumo.php">
                                                    <input type="hidden" id="tipoHospital" name="tipoHospital" value="HBQP">
                                                    <input type="hidden" name="idAE" id="idAE" value="<?php echo $idAE?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Observaci&oacute;n</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">

                                                                    <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                                    <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                                    <br>

                                                                    <label for="exampleInputBorder" style="font-size: 15px">Detalle de la Observaci&oacute;n: <code>*</code></label>
                                                                    <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" placeholder="Observaciones..." required></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>


                                        </div>
                                    </div>
                                </fieldset>
                                <br>

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
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>
<script src="js/dark-mode.js"></script>
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

<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(function () {
        $('#observacionTotal').DataTable({
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
</body>
</html>
