<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['idInventario'])){
    $idInventario = $_POST['idInventario'];
    $getDatosInventario = getDatosInventario($idInventario);
    $equipoInventario = $getDatosInventario['equipoInventario'];
    $marcaInventario = $getDatosInventario['marcaInventario'];
    $modeloInventario = $getDatosInventario['modeloInventario'];
    $cantidadInventario = $getDatosInventario['cantidadInventario'];
    $categoriaInventario = $getDatosInventario['categoriaInventario'];
    $nomProveedor = $getDatosInventario['nomProveedor'];
    $rutProveedor = $getDatosInventario['rutProveedor'];
    $tipoDocumento = $getDatosInventario['tipoDocumento'];
    $solicompra = $getDatosInventario['solicompra'];
    $observacion = $getDatosInventario['observacion'];
    $valorUnitario = $getDatosInventario['valorUnitario'];

    $getnombrecategoria = getnombrecategoria($categoriaInventario);
    $nomCategoria = $getnombrecategoria['nomCategoria'];



}


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {
            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            if($folioTG == 'cotizacion-empresa'){
                $dir = "../sdo-cotizacion-inventario/cotizacion-empresa/id-".$idTG1."/";
            }else{
                $dir = "../sdo-cotizacion-inventario/cotizacion-internet/id-".$idTG1."/";
            }
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Documentos-" . $folioTG . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zipSC($dir2, $zip);
                $zip->close();
                // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
                if (file_exists($rutaFinal . $archivoZip)) {
                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventario - <?php echo $equipoInventario?></title>

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
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Inventario</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="inventario-hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <fieldset class="border p-4">
                            <div class="card-header bg-gradient-info">
                                <legend style="text-align: center"><b>Información sobre el producto: <?php echo $equipoInventario?></b></legend>
                            </div>
                            <div class="card-body" style="text-align:center;">
                                <div class="row">
                                    <div class="col-md-8 col-12 text-justify">
                                        <h5>
                                            <b>Proveedor: </b><?php echo $nomProveedor?>
                                        </h5>
                                        <br>
                                        <h5>
                                            <b>RUT Proveedor: </b><?php echo $rutProveedor?>
                                        </h5>
                                        <h5>
                                            <b>Marca: </b><?php echo $marcaInventario?>
                                        </h5>
                                        <h5>
                                            <b>Modelo: </b><?php echo $modeloInventario?>
                                        </h5>
                                        <h5>
                                            <b>Cantidad: </b><?php echo $cantidadInventario?>
                                        </h5>
                                        <h5>
                                            <b>Categoría: </b><?php echo $nomCategoria?>
                                        </h5>
                                        <?php
                                        if($tipoDocumento == 'Solicitud de Compra - SDO' || $tipoDocumento == 'Solicitud de Compra - PM' || $tipoDocumento == 'Orden de Compra'){
                                            ?>
                                            <h5>
                                                <b>Solicitud: </b><?php echo $solicompra?>
                                            </h5>
                                            <?php
                                        }
                                        ?>
                                        <h5>
                                            <b>Tipo de Documento: </b><?php echo $tipoDocumento?>
                                        </h5>
                                        <h5>
                                            <b>Valor Unitario: </b><?php echo '$'.number_format($valorUnitario, 0, ",", ".");?>

                                        </h5>
                                    </div>
                                    <div class="col-md-4 col-12 text-center">
                                        <div class="col-md-12">
                                            <?php
                                            $sql = "SELECT * FROM archivos_cotizacion_inventario WHERE idInventario = $idInventario";
                                            $res = mysqli_query($conexion, $sql);
                                            while($row = $res->fetch_assoc()){
                                                $idArchivo = $row['idArchivo'];
                                                $tipoCotizacion = $row['tipoCotizacion'];
                                                $nomArchivo = $row['nomArchivo'];
                                                $rutaArchivo = $row['rutaArchivo'];
                                                if (empty($idArchivo) || $idArchivo == 0) {
                                                    ?>
                                                    <button type="button" class="btn btn-block" disabled title="No hay Archivo Adjunto" style="background-color: indigo; color: white">No hay Archivo Adjunto</button>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <form action="ver-detalles-inventario.php" method="POST" style="display: inline">
                                                        <button type="submit" class="btn btn-block" title="Descargar Archivo Adjunto" style="background-color: indigo; color: white"><i class="fas fa-download"></i> Descargar Archivo Adjunto</button>
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idInventario ?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $tipoCotizacion ?>">
                                                        <input type="hidden" name="nomArchivo" id="nomArchivo" value="<?php echo $nomArchivo ?>">
                                                        <!--<input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo?>">-->
                                                    </form>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="border p-3">
                            <div class="card-header bg-gradient-info">
                                <legend style="text-align: center"><b>Observaciones</b></legend>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" readonly><?php echo $observacion?></textarea>
                            </div>
                        </fieldset>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>

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
<script src="js/dark-mode.js"></script>
</body>
</html>
