<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


$fecha = date('d-m-Y H:i');

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Documentacion General</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jssha@3.2.0/dist/sha256.min.js"></script>
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
            <!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-body" style="text-align:center;">
                                </div>
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <div class="text-center">
                                    <button type="button" style="float: right"
                                        title="Agregar un manual"
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#agregarmanual">
                                        <i class="fas fa-plus"></i> Agregar Documento
                                    </button>
                                </div>
                                <br>
                                <br>
                                <div class="text-center">
                                    <button type="button" style="float: right"
                                        title="Agregar un manual"
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#agregarcategoria">
                                        <i class="fas fa-plus"></i> Agregar Categoria
                                    </button>
                                </div>
                                <div class="modal fade" id="agregarcategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Categoria</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/guardar-categorias-documentacion-general.php" method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Nombre Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="nomCategoria" name="nomCategoria">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </div>
                                                <input type="hidden" name="nomUsuario" value="<?php echo $nomUsuario; ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/guardar-documentos.php" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-12">
                                                            <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
                                                                    <option selected disabled value="">Seleccione</option>
                                                                    <?php
                                                                    $sql = "SELECT * FROM `categorias_documentacion_general`";
                                                                    $res = mysqli_query($conexion, $sql);
                                                                    while($row = $res->fetch_assoc()){
                                                                        $idCategoria = $row['id'];
                                                                        $nomCategoria = $row['nomCategoria'];
                                                                        ?>
                                                                        <option
                                                                            data-idcategoria="<?php echo $idCategoria ?>"
                                                                            value="<?php echo $nomCategoria ?>">
                                                                            <?php echo $nomCategoria ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                        </div>
                                                        <div class="form-group col-md-12 col-12">
                                                            <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <input type="file" class="form-control form-control-sm" required id="archivoAdjunto" name="archivoAdjunto">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                                </div>
                                                <input type="hidden" id="idCategoria" name="idCategoria" >
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $sql = "SELECT id, nomCategoria FROM categorias_documentacion_general ORDER BY id ASC";
                                $res = mysqli_query($conexion, $sql);
                                ?>
                            </div>
                            <div class="card-body text-center">
                                <div class="card card-success card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <?php
                                            $activo = true;
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                $id = $row['id'];
                                                $nombre = $row['nomCategoria'];
                                                ?>
                                                <li class="nav-item" role="presentation">
                                                    <button
                                                        class="nav-link <?php echo $activo ? 'active' : ''; ?>"
                                                        id="tab-<?php echo $id; ?>"
                                                        data-toggle="tab"
                                                        data-target="#contenido-<?php echo $id; ?>"
                                                        type="button"
                                                        role="tab"
                                                        aria-selected="<?php echo $activo ? 'true' : 'false'; ?>">
                                                        <?php echo $nombre; ?>
                                                    </button>
                                                </li>
                                                <?php
                                                $activo = false;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                    // Reiniciamos el puntero del resultado
                                    mysqli_data_seek($res, 0);
                                    $activo = true;
                                    ?>

                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">
                                            <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                                                <?php $idCategoria = $row['id']; ?>
                                                <div
                                                    class="tab-pane fade <?php echo $activo ? 'show active' : ''; ?>"
                                                    id="contenido-<?php echo $row['id']; ?>"
                                                    role="tabpanel">

                                                    <h5><?php echo $row['nomCategoria']; ?></h5>
                                                    <!--<form action="../sdo-personal/documentos_detalles.php" method="POST">
                                                        <input type="text" name="idCategoria" value="<?php echo $row['id']; ?>">
                                                        <input type="text" name="nomCategoria" value="<?php echo $row['nomCategoria']; ?>">
                                                        <button type="submit" class="btn btn-block bg-info">Revisar</button>

                                                    </form>-->
                                                    <div class="card-body">
                                                        <p class="text-center"><b>Listado de Documentación:</b></p>
                                                        <hr>

                                                        <div class="row">
                                                            <br>
                                                            <table id="tabla_nombres" class="table table-bordered table-striped text-center">
                                                                <thead class=" bg-secondary ">
                                                                <tr>
                                                                    <th>N°</th>
                                                                    <th>Nombre</th>
                                                                    <th>Fecha de Subida</th>
                                                                    <th>Descargar</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $sqlArchivos = "SELECT * FROM archivos_documentacion_general WHERE idCategoria = $idCategoria";
                                                                $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                                                $count = 1;

                                                                while ($rowArchivos = mysqli_fetch_assoc($resArchivos)) {
                                                                    $nomDocumentos = $rowArchivos['nomArchivo'];
                                                                    $rutaArchivo   = $rowArchivos['rutaArchivo']; // ejemplo
                                                                    $fechaArchivo = date('d/m/Y', strtotime($rowArchivos['fecha']));

                                                                    $extension = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

                                                                    $icono = 'fa-file';        // por defecto
                                                                    $clase = 'btn-secondary';  // color por defecto

                                                                    switch ($extension) {
                                                                        case 'pdf':
                                                                            $icono = 'fa-file-pdf';
                                                                            $clase = 'btn-danger';
                                                                            break;

                                                                        case 'doc':
                                                                        case 'docx':
                                                                            $icono = 'fa-file-word';
                                                                            $clase = 'btn-primary';
                                                                            break;

                                                                        case 'xls':
                                                                        case 'xlsx':
                                                                            $icono = 'fa-file-excel';
                                                                            $clase = 'btn-success';
                                                                            break;
                                                                    }

                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $count; ?></td>
                                                                        <td><?php echo $nomDocumentos; ?></td>
                                                                        <td><?php echo $fechaArchivo; ?></td>
                                                                        <td>
                                                                            <a href="<?php echo $rutaArchivo; ?>"
                                                                                class="btn <?php echo $clase; ?> btn-md"
                                                                                target="_blank"
                                                                                download
                                                                                title="Descargar archivo">
                                                                                <i class="fas <?php echo $icono; ?>"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $count++;
                                                                }
                                                                ?>
                                                                </tbody>
                                                                <tfoot  class=" bg-secondary ">
                                                                <tr>
                                                                    <th>N°</th>
                                                                    <th>Nombre</th>
                                                                    <th>Fecha de Subida</th>
                                                                    <th>Descargar</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                                <?php
                                                $activo = false;
                                            } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
        </section>
    </div>
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script>
    document.getElementById('tipoArchivo').addEventListener('change', function () {
        const opcionSeleccionada = this.options[this.selectedIndex];
        const idCategoria = opcionSeleccionada.dataset.idcategoria;
        document.getElementById('idCategoria').value = idCategoria;
    });
</script>

</body>
</html>
