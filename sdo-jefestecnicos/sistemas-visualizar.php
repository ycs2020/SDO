<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomCategoria = '';

$idSubCategoria = 0;

if (isset($_POST['idCategoria'])) {
    $idCategoria = trim($_POST['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);

        $_SESSION['idCategoria'] = $idCategoria;
    } else {
        $nomCategoria = '';
    }
} else if (isset($_SESSION['idCategoria'])) {
    $idCategoria = trim($_SESSION['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);
    } else {
        $nomCategoria = '';
    }
} else {
    header('Location: index.php?errorCategoria=true');
}

if (isset($_SESSION['idSubCategoria'])) {

    $idSubCategoria = $_SESSION['idSubCategoria'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando <?php echo $nomCategoria; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
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
                        <h4>Visualizando <?php echo $nomCategoria; ?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
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

                                <a href="sistemas-listado.php" style="float: right;" class="btn btn-danger"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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

                                //AVISOS AGREGAR EQUIPO
                                if (isset($_GET['exitoAgregarEquipo'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Equipo Creado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarEquipo'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Equipo. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeEquipo'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;El Equipo ya se encuentra registrado. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR EQUIPO
                                ?>
                                <br>

                                <?php
                                $contarCategoria = contarSubCategoria($idCategoria);

                                if ($contarCategoria > 0) {
                                    ?>
                                    <form method="post" action="../sdo-funciones/equipos-agregar.php">
                                        <button type="button" style="float: right;" data-toggle="modal"
                                                data-target="#modalAddEquipo" id="inputMozi" class="btn btn-secondary">
                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Equipo
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalAddEquipo" name="modalAddEquipo" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar
                                                            Equipo</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Nombre de Equipo:
                                                                <code>*</code></label>
                                                            <input type="text" class="form-control form-control-border"
                                                                   id="addNomEquipo" name="addNomEquipo"
                                                                   placeholder="Nombre de Equipo" value="" required>
                                                        </div>
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Categoria:
                                                                <code>*</code></label>
                                                            <select class="custom-select" name="categoriaAgregar"
                                                                    id="categoriaAgregar">
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
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                        <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancelar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>


                                <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <form method="post" action="../sdo-funciones/categorias-agregar.php">
                                    <button type="button" style="float: right;" data-toggle="modal"
                                            data-target="#modalAddCategoria" id="inputMozi" class="btn btn-primary"><i
                                                class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Categoria
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalAddCategoria" name="modalAddCategoria"
                                         tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar
                                                        Categoria</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre Categoria: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-border"
                                                               id="addNomCategoria" name="addNomCategoria"
                                                               placeholder="Nombre Categoria:" value="" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idCategoria" id="idCategoria"
                                                           value="<?php echo $idCategoria; ?>">
                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php

                                if ($contarCategoria > 0) {

                                    $_SESSION['rolUsuario'] = $rolUsuario;
                                    ?>
                                    <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                    <form method="post" action="../sdo-funciones/sistemas-historico.php">
                                        <input type="hidden" name="idSubCategoriaParaDescarga"
                                               id="idSubCategoriaParaDescarga">
                                        <input type="hidden" name="nomUsuario" id="nomUsuario"
                                               value="<?php echo $nomUsuario; ?>"/>
                                        <input type="hidden" name="tipoUsuario" id="tipoUsuario"
                                               value="Tecnico de Departamento">
                                        <button type="submit" style="float: right;" id="inputMozi" class="btn bg-navy">
                                            <i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Historico
                                        </button>

                                    </form>
                                    <?php
                                }
                                ?>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputDepartamento">Filtrar por Categoria:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-server"></i></span>
                                            </div>
                                            <?php
                                            $contarCategoria = contarSubCategoria($idCategoria);

                                            if ($contarCategoria > 0) {
                                                ?>
                                                <select id="selectSubCategoria" class="form-control"
                                                        name="selectSubCategoria">
                                                    <?php

                                                    $querySubCategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria  ASC";

                                                    $resSubCategoria = mysqli_query($conexion, $querySubCategoria);

                                                    while ($rowSubCategoria = $resSubCategoria->fetch_assoc()) {


                                                        if ($idSubCategoria == $rowSubCategoria['idSubcategoria']) {
                                                            ?>


                                                            <option value="<?php echo $rowSubCategoria['idSubcategoria']; ?>"
                                                                    selected>
                                                                <?php echo mb_strtoupper($rowSubCategoria['nomSubcategoria'], "UTF-8"); ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>

                                                            <option value="<?php echo $rowSubCategoria['idSubcategoria']; ?>">
                                                                <?php echo mb_strtoupper($rowSubCategoria['nomSubcategoria'], "UTF-8"); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                                <?php
                                            } else {
                                                ?>
                                                <select id="selectSubCategoria" class="form-control"
                                                        name="selectSubCategoria">

                                                    <option value="0">No Existen Categorias</option>

                                                </select>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div>

                                </div>
                                <br>
                                <!-- Set up the datatable -->

                                <div class="table-responsive">
                                    <table id="miTabla" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Visualizar</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Visualizar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        var miTabla = $('#miTabla').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
                                            "responsive": true,
                                            "ordering": false,
                                            "language": {
                                                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                "zeroRecords": "No se han encontrado Registros.",
                                                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                "infoEmpty": "No existen Registros.",
                                                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                "search": "Buscar:",
                                                "processing": "Procesando...",
                                                "paginate": {
                                                    "previous": "Anterior",
                                                    "next": "Siguiente"
                                                }
                                            },
                                            "ajax": {
                                                "url": "ajax-sistemas.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                    d.categoria = $('#selectSubCategoria').val(); // Pasa el valor seleccionado del select como parámetro
                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": [
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row, meta) {
                                                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                    }
                                                },
                                                { "data": "nomEquipo" },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        // Agrega el formulario y el botón de visualización para cada fila
                                                        return '' +
                                                            '<form class="" action="equipos-observaciones.php" target="_blank" method="post">' +
                                                            '<input type="hidden" name="idEquipo" value="' + row.idEquipo + '">' +
                                                            '<center>' +
                                                            '<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar </button>' +
                                                            '</center>' +
                                                            '</form>';
                                                    }
                                                }
                                                // Agrega más columnas según sea necesario
                                            ],
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                            }
                                        });

                                        // Evento de cambio en el select
                                        $('#selectSubCategoria').on('change', function () {
                                            miTabla.ajax.reload(); // Recargar la DataTable al cambiar la categoría
                                        });
                                    });
                                </script>

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

