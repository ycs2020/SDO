<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$bitacora = obtenerIDBitacora();

$nomCategoria = '';

if (empty($bitacora)) {
    if (isset($_POST['idCategoria'])) {
        $idCategoria = trim($_POST['idCategoria']);

        if (!empty($idCategoria) && $idCategoria > 0) {
            $nomCategoria = getNomCategoria($idCategoria);

            $nomCategoria = mb_convert_case($nomCategoria, MB_CASE_TITLE, "UTF-8");

            $_SESSION['idCategoria'] = $idCategoria;
        } else {
            $nomCategoria = '';
        }
    } else if (isset($_GET['idCategoria'])) {
        $idCategoria = trim($_GET['idCategoria']);

        if (!empty($idCategoria) && $idCategoria > 0) {
            $nomCategoria = getNomCategoria($idCategoria);

            $nomCategoria = mb_convert_case($nomCategoria, MB_CASE_TITLE, "UTF-8");

            $_SESSION['idCategoria'] = $idCategoria;
        } else {
            $nomCategoria = '';
        }
    } else if (isset($_SESSION['idCategoria'])) {
        $idCategoria = trim($_SESSION['idCategoria']);

        if (!empty($idCategoria) && $idCategoria > 0) {
            $nomCategoria = getNomCategoria($idCategoria);

            $nomCategoria = mb_convert_case($nomCategoria, MB_CASE_TITLE, "UTF-8");
        } else {
            $nomCategoria = '';
        }
    } else {
        header('Location: index.php?errorCategoria=true');
    }
} else {
    $idCategoria = $bitacora;

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);

        $nomCategoria = mb_convert_case($nomCategoria, MB_CASE_TITLE, "UTF-8");

        $_SESSION['idCategoria'] = $idCategoria;
    } else {
        $nomCategoria = '';
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $nomCategoria; ?></title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarDataEquipos();

            $('#selectSubCategoria').change(function() {
                recargarDataEquipos();
            });


            function recargarDataEquipos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-subcategoria/bitacora-listado.php",
                    data: "idSubcategoria=" + $('#selectSubCategoria').val(),
                    success: function(r) {
                        $('#displayTableExample').html(r);


                    }
                });

            };
        });
    </script>
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
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
                        <h4><?php echo $nomCategoria; ?></h4>
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
                                <a href="index.php" style="float: right;" class="btn btn-info"><i class="fa-solid fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>


                                <?php

                                //AVISOS AGREGAR CATEGORIA
                                if (isset($_GET['exitoAgregarCategoria'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Categoria Creada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarCategoria'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Categoria. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeCategoria'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;La Categoria ya se encuentra registrada. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR CATEGORIA

                                //AVISOS AGREGAR OBSERVACIÓN
                                if (isset($_GET['exitoAgregarObservacion'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observacion Creada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarObservacion'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Observacion. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR OBSERVACIÓN

                                //AVISOS MODIFICAR OBSERVACIÓN
                                if (isset($_GET['exitoModificarObservacion'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observacion Modificada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorModificarObservacion'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Observacion. Reintente.</h5></div>';
                                }
                                //AVISOS MODIFICAR OBSERVACIÓN

                                //AVISOS ELIMINAR OBSERVACIÓN
                                if (isset($_GET['exitoEliminarObservacion'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observacion Eliminada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorEliminarObservacion'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Eliminar Observacion. Reintente.</h5></div>';
                                }
                                //AVISOS ELIMINAR OBSERVACIÓN

                                //AVISOS DESCARGA BITACORA

                                if (isset($_GET['errorDescargarBitacora'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Descargar Bitacora. Reintente.</h5></div>';
                                }

                                if (isset($_GET['bitacoraSinEventos'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error: No Existen Eventos en el Periodo Consultado.</h5></div>';
                                }

                                if (isset($_GET['errorFechaTermino'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;La Fecha de Termino no debe ser antes que la Fecha de Inicio. Reintente.</h5></div>';
                                }
                                //AVISOS DESCARGA BITACORA
                                ?>
                                <br>

                                <?php
                                $contarCategoria = contarSubCategoria($idCategoria);

                                if ($contarCategoria > 0) {
                                    ?>
                                    <form method="post" action="../sdo-funciones/admin-bitacora-agregar.php">
                                        <button type="button" style="float: right;" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn btn-secondary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observaci&oacute;n</button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                            <label for="exampleInputBorder">Categoria: <code>*</code></label>
                                                            <select class="custom-select" name="categoriaBitacora" id="categoriaBitacora">
                                                                <?php

                                                                $querySubCategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria ASC";

                                                                $resSubCategoria = mysqli_query($conexion, $querySubCategoria);

                                                                while ($rowSubCategoria = $resSubCategoria->fetch_assoc()) {

                                                                    ?>
                                                                    <option value="<?php echo $rowSubCategoria['idSubcategoria']; ?>">
                                                                        <?php echo $rowSubCategoria['nomSubcategoria']; ?>
                                                                    </option>
                                                                    <?php

                                                                }


                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Observaci&oacute;n: <code>*</code></label>
                                                            <textarea id="observacionBitacora" style="resize:none;" name="observacionBitacora" class="form-control" rows="15" placeholder="Observaciones..."></textarea>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="tecnicoBitacora" id="tecnicoBitacora" value="<?php echo $nomUsuario . " (" . $cargoUsuario . ")"; ?>">
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
                                <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <form method="post" action="../sdo-funciones/admin-bitacora-categorias-agregar.php">
                                    <button type="button" style="float: right;" data-toggle="modal" data-target="#modalAddCategoria" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Categoria</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="modalAddCategoria" name="modalAddCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Categoria</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre Categoria: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-border" id="addNomCategoria" name="addNomCategoria" placeholder="Nombre Categoria:" value="" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria; ?>">
                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <form method="post" action="../sdo-funciones/bitacora-descargar.php">
                                    <button type="button" style="float: right;" data-toggle="modal" data-target="#modalDownloadBitacora" id="inputMozi" class="btn btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Bitacora</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalDownloadBitacora" name="modalDownloadBitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Descargar Bitacora</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="customRadio1" value="false" name="customRadio" checked="checked">
                                                            <label for="customRadio1" class="custom-control-label">Descargar Bitacora Seg&uacute;n Fechas</label>
                                                        </div>
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label>Fecha de Inicio:</label>
                                                        <input type="datetime-local" class="form-control" name="fechaInicio" id="fechaInicio">
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label>Fecha de Termino:</label>
                                                        <input type="datetime-local" class="form-control" name="fechaTermino" id="fechaTermino">

                                                    </div>
                                                    <br>
                                                    <div style="text-align:left;" class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="customRadio2" value="true" name="customRadio">
                                                            <label for="customRadio2" class="custom-control-label">Descargar Bitacora Completa</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="nomUsuario" id="nomUsuario" value="<?php echo $nomUsuario . " (" . $cargoUsuario . ")"; ?>">
                                                    <input type="hidden" name="tipoUsuario" id="tipoUsuario" value="Administrador">
                                                    <button type="submit" class="btn btn-primary">Descargar</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="form-group col-md-3">
                                    <label for="exampleInputDepartamento">Filtrar Categoria:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                                        </div>
                                        <?php
                                        $contarCategoria = contarSubCategoria($idCategoria);

                                        if ($contarCategoria > 0) {
                                            ?>
                                            <select id="selectSubCategoria" class="form-control" name="selectSubCategoria">

                                                <?php

                                                $querySubCategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria  ASC";

                                                $resSubCategoria = mysqli_query($conexion, $querySubCategoria);

                                                while ($rowSubCategoria = $resSubCategoria->fetch_assoc()) {

                                                    ?>
                                                    <option value="<?php echo $rowSubCategoria['idSubcategoria']; ?>">
                                                        <?php echo $rowSubCategoria['nomSubcategoria']; ?>
                                                    </option>
                                                    <?php

                                                }


                                                ?>
                                            </select>
                                            <?php
                                        } else {
                                            ?>
                                            <select id="selectSubCategoria" class="form-control" name="selectSubCategoria">

                                                <option value="0">No Existen Categorias</option>

                                            </select>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>
                                            <th>
                                                <center>Observaci&oacute;n</center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Modificar</center>
                                            </th>
                                            <th>
                                                <center>Eliminar</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="displayTableExample" name="displayTableExample">
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>
                                            <th>
                                                <center>Observaci&oacute;n</center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Modificar</center>
                                            </th>
                                            <th>
                                                <center>Eliminar</center>
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
<script src="js/dark-mode.js"></script>

</body>

</html>